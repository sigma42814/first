<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Item;
use App\Models\PurchaseItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Traits\InventoryTrait;

class InventoryController extends Controller
{
    use InventoryTrait;

    public function index()
    {
        $items = Item::with(['category'])
            ->select('items.*')
            ->selectRaw('COALESCE(SUM(inventories.remaining_quantity), 0) as current_stock')
            ->leftJoin('inventories', 'items.id', '=', 'inventories.item_id')
            ->groupBy('items.id')
            ->orderBy('items.item_name')
            ->paginate(20);

        return view('inventory.index', compact('items'));
    }

    public function show($itemId)
    {
        $item = Item::findOrFail($itemId);
        $currentStock = $this->getCurrentStock($itemId);
        $movements = $this->getStockMovementHistory($itemId, 100);

        // Get batch details for this item
        $batches = Inventory::where('item_id', $itemId)
            ->where('remaining_quantity', '>', 0)
            ->whereNotNull('batch_number')
            ->select('batch_number', 'expiry_date', DB::raw('SUM(remaining_quantity) as remaining_quantity'))
            ->groupBy('batch_number', 'expiry_date')
            ->orderBy('expiry_date')
            ->get();

        return view('inventory.show', compact('item', 'currentStock', 'movements', 'batches'));
    }

    public function lowStockReport()
    {
        $items = $this->getLowStockItems();
        return view('inventory.low-stock', compact('items'));
    }

    public function generateLowStockPdf()
    {
        $items = $this->getLowStockItems();
        $pdf = PDF::loadView('inventory.pdf.low-stock', compact('items'));
        return $pdf->download('low-stock-report-'.now()->format('Y-m-d').'.pdf');
    }

    public function stockMovementReport(Request $request)
    {
        $query = Inventory::with(['item', 'purchase', 'sale', 'purchaseReturn', 'saleReturn'])
            ->orderBy('movement_date', 'desc')
            ->orderBy('created_at', 'desc');

        if ($request->has('item_id') && $request->item_id) {
            $query->where('item_id', $request->item_id);
        }

        if ($request->has('type') && $request->type) {
            $query->where('movement_type', $request->type);
        }

        if ($request->has('from_date') && $request->from_date) {
            $query->where('movement_date', '>=', $request->from_date);
        }

        if ($request->has('to_date') && $request->to_date) {
            $query->where('movement_date', '<=', $request->to_date);
        }

        $movements = $query->paginate(50);
        $items = Item::orderBy('item_name')->get();

        return view('inventory.movement-report', compact('movements', 'items'));
    }

    public function stockAdjustment(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|numeric',
            'reason' => 'required|string|max:255',
            'adjustment_date' => 'required|date',
            'batch_number' => 'nullable|string|max:100',
            'expiry_date' => 'nullable|date',
        ]);

        DB::beginTransaction();
        try {
            $item = Item::find($request->item_id);
            $currentStock = $this->getCurrentStock($item->id);
            $adjustmentQuantity = $request->quantity;

            // Create inventory movement
            Inventory::create([
                'item_id' => $item->id,
                'quantity' => $adjustmentQuantity,
                'remaining_quantity' => $currentStock + $adjustmentQuantity,
                'movement_type' => 'adjustment',
                'movement_date' => $request->adjustment_date,
                'unit_cost' => $item->cost_price,
                'unit_price' => $item->selling_price,
                'batch_number' => $request->batch_number,
                'expiry_date' => $request->expiry_date,
                'notes' => $request->reason
            ]);

            // Update item stock
            $item->stock += $adjustmentQuantity;
            $item->save();

            DB::commit();
            return redirect()->back()->with('success', 'Stock adjusted successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to adjust stock: ' . $e->getMessage());
        }
    }

    public function batchReport(Request $request)
    {
        $batches = Inventory::select('item_id', 'batch_number', 
                DB::raw('SUM(remaining_quantity) as remaining_quantity'),
                DB::raw('MIN(expiry_date) as expiry_date'))
            ->where('remaining_quantity', '>', 0)
            ->whereNotNull('batch_number')
            ->groupBy('item_id', 'batch_number')
            ->with(['item'])
            ->orderBy('expiry_date', 'asc')
            ->paginate(20);

        return view('inventory.batch-report', compact('batches'));
    }

    public function expiryReport(Request $request)
    {
        $query = Inventory::where('remaining_quantity', '>', 0)
            ->whereNotNull('expiry_date')
            ->where('expiry_date', '>=', now())
            ->with(['item'])
            ->orderBy('expiry_date');

        if ($request->has('days') && $request->days) {
            $thresholdDate = now()->addDays($request->days);
            $query->where('expiry_date', '<=', $thresholdDate);
        }

        $expiries = $query->paginate(20);
        return view('inventory.expiry-report', compact('expiries'));
    }
}