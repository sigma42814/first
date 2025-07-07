<?php

namespace App\Traits;

use App\Models\Inventory;
use App\Models\Item;
use App\Models\PurchaseItem;
use Illuminate\Support\Facades\DB;

trait InventoryTrait
{
    /**
     * Update inventory for a purchase
     */
    public function updateInventoryForPurchase($purchase, $items)
    {
        DB::beginTransaction();
        try {
            foreach ($items as $purchaseItem) {
                $item = Item::find($purchaseItem->item_id);
                if (!$item) continue;

                // Create inventory movement for purchase
                Inventory::create([
                    'item_id' => $item->id,
                    'batch_number' => $purchaseItem->batch_number,
                    'quantity' => $purchaseItem->quantity,
                    'remaining_quantity' => $purchaseItem->quantity,
                    'purchase_id' => $purchase->id,
                    'purchase_item_id' => $purchaseItem->id,
                    'movement_type' => 'purchase',
                    'movement_date' => $purchase->invoice_date,
                    'expiry_date' => $purchaseItem->exp_date,
                    'unit_cost' => $purchaseItem->purchase_price,
                    'unit_price' => $purchaseItem->price,
                    'notes' => 'Purchase Invoice #' . $purchase->invoice_number
                ]);

                // Update item stock
                $item->stock += $purchaseItem->quantity;
                $item->save();
            }
            
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Update inventory for a sale (FIFO method)
     */
    public function updateInventoryForSale($sale, $items)
    {
        DB::beginTransaction();
        try {
            foreach ($items as $itemData) {
                $item = Item::find($itemData['code']);
                if (!$item) continue;

                // Get available batches (FIFO - oldest expiry first)
                $availableBatches = Inventory::where('item_id', $item->id)
                    ->where('remaining_quantity', '>', 0)
                    ->orderBy('expiry_date')
                    ->orderBy('movement_date')
                    ->get();

                $quantityToDeduct = $itemData['quantity'];
                
                foreach ($availableBatches as $batch) {
                    if ($quantityToDeduct <= 0) break;

                    $deductAmount = min($quantityToDeduct, $batch->remaining_quantity);
                    
                    // Create inventory movement for sale
                    Inventory::create([
                        'item_id' => $item->id,
                        'batch_number' => $batch->batch_number,
                        'quantity' => -$deductAmount,
                        'remaining_quantity' => $batch->remaining_quantity - $deductAmount,
                        'sale_id' => $sale->id,
                        'sale_item_id' => $itemData['id'] ?? null,
                        'movement_type' => 'sale',
                        'movement_date' => $sale->invoice_date,
                        'expiry_date' => $batch->expiry_date,
                        'unit_cost' => $batch->unit_cost,
                        'unit_price' => $itemData['price'],
                        'notes' => 'Sale Invoice #' . $sale->invoice_number
                    ]);

                    // Update the original batch
                    $batch->remaining_quantity -= $deductAmount;
                    $batch->save();

                    $quantityToDeduct -= $deductAmount;
                }

                if ($quantityToDeduct > 0) {
                    // Handle insufficient stock (shouldn't happen if proper checks are in place)
                    Inventory::create([
                        'item_id' => $item->id,
                        'quantity' => -$quantityToDeduct,
                        'remaining_quantity' => 0,
                        'sale_id' => $sale->id,
                        'sale_item_id' => $itemData['id'] ?? null,
                        'movement_type' => 'sale',
                        'movement_date' => $sale->invoice_date,
                        'unit_cost' => $item->cost_price,
                        'unit_price' => $itemData['price'],
                        'notes' => 'Sale Invoice #' . $sale->invoice_number . ' (Insufficient stock)'
                    ]);
                }

                // Update item stock
                $item->stock -= $itemData['quantity'];
                $item->save();
            }
            
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Reverse inventory for a sale
     */
    public function reverseInventoryForSale($sale)
    {
        $inventoryMovements = Inventory::where('sale_id', $sale->id)->get();
        
        DB::beginTransaction();
        try {
            foreach ($inventoryMovements as $movement) {
                if ($movement->quantity < 0) {
                    // Create reversal entry
                    Inventory::create([
                        'item_id' => $movement->item_id,
                        'batch_number' => $movement->batch_number,
                        'quantity' => abs($movement->quantity),
                        'remaining_quantity' => abs($movement->quantity),
                        'sale_id' => $movement->sale_id,
                        'sale_item_id' => $movement->sale_item_id,
                        'movement_type' => 'sale_return',
                        'movement_date' => now(),
                        'expiry_date' => $movement->expiry_date,
                        'unit_cost' => $movement->unit_cost,
                        'unit_price' => $movement->unit_price,
                        'notes' => 'Sale return for Invoice #' . $sale->invoice_number
                    ]);

                    // Restore original batch if exists
                    if ($movement->batch_number) {
                        $originalBatch = Inventory::where('item_id', $movement->item_id)
                            ->where('batch_number', $movement->batch_number)
                            ->where('quantity', '>', 0)
                            ->orderBy('movement_date')
                            ->first();

                        if ($originalBatch) {
                            $originalBatch->remaining_quantity += abs($movement->quantity);
                            $originalBatch->save();
                        }
                    }

                    // Update item stock
                    $item = Item::find($movement->item_id);
                    if ($item) {
                        $item->stock += abs($movement->quantity);
                        $item->save();
                    }
                }
            }
            
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Get current stock for an item
     */
    public function getCurrentStock($itemId)
    {
        return Inventory::where('item_id', $itemId)
            ->sum('remaining_quantity');
    }

    /**
     * Get item stock movement history
     */
    public function getStockMovementHistory($itemId, $limit = 50)
    {
        return Inventory::with(['purchase', 'sale', 'purchaseReturn', 'saleReturn'])
            ->where('item_id', $itemId)
            ->orderBy('movement_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get low stock items
     */
    public function getLowStockItems($threshold = null)
    {
        $query = Item::select([
                'items.id',
                'items.item_code',
                'items.item_name',
                'items.min_stock_level',
                'items.unit',
                'items.cost_price',
                'items.selling_price',
                DB::raw('COALESCE(SUM(inventories.remaining_quantity), 0) as current_stock')
            ])
            ->leftJoin('inventories', function($join) {
                $join->on('items.id', '=', 'inventories.item_id')
                     ->where('inventories.remaining_quantity', '>', 0);
            })
            ->groupBy(
                'items.id',
                'items.item_code',
                'items.item_name',
                'items.min_stock_level',
                'items.unit',
                'items.cost_price',
                'items.selling_price'
            );

        if ($threshold) {
            $query->having('current_stock', '<', $threshold);
        } else {
            $query->havingRaw('current_stock < items.min_stock_level');
        }

        return $query->get();
    }
}