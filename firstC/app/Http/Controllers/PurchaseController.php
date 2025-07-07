<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Item;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Inventory;

class PurchaseController extends Controller
{
    public function create()
    {
        return view('purchases.create');
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        
        try {
            $validated = $request->validate([
                'company_code' => 'required',
                'company_name' => 'required',
                'company_invoice' => 'nullable',
                'invoice_number' => 'required|unique:purchases',
                'invoice_date' => 'required|date',
                'total_purchases' => 'required|numeric',
                'net_payable' => 'required|numeric',
                'prev_balance' => 'nullable|numeric',
                'total_balance' => 'required|numeric',
                'items' => 'required|array|min:1',
                'items.*.code' => 'required|exists:items,id',
                'items.*.item_name' => 'required',
                'items.*.batch_number' => 'nullable',
                'items.*.price' => 'required|numeric|min:0',
                'items.*.purchase_price' => 'required|numeric|min:0',
                'items.*.quantity' => 'required|numeric|min:1',
                'items.*.discount' => 'nullable|numeric|min:0|max:100',
                'items.*.exp_date' => 'nullable|date',
                'items.*.total' => 'required|numeric|min:0'
            ]);

            // Find or create company
            $company = Company::where('code', $validated['company_code'])->first();
            
            if (!$company) {
                $company = Company::create([
                    'code' => $validated['company_code'],
                    'name' => $validated['company_name'],
                    'address' => $request->input('address', '')
                ]);
            }

            // Create purchase
            $purchase = Purchase::create([
                'company_id' => $company->id,
                'company_invoice' => $validated['company_invoice'],
                'invoice_number' => $validated['invoice_number'],
                'invoice_date' => $validated['invoice_date'],
                'total_purchases' => $validated['total_purchases'],
                'net_payable' => $validated['net_payable'],
                'prev_balance' => $validated['prev_balance'] ?? 0,
                'total_balance' => $validated['total_balance']
            ]);

            // Create purchase items (inventory will be auto-updated via PurchaseItem booted method)
            foreach ($validated['items'] as $item) {
                PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'item_id' => $item['code'],
                    'batch_number' => $item['batch_number'],
                    'exp_date' => $item['exp_date'],
                    'price' => $item['price'],
                    'purchase_price' => $item['purchase_price'],
                    'quantity' => $item['quantity'],
                    'discount' => $item['discount'] ?? 0,
                    'total' => $item['total']
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Purchase saved successfully!',
                'purchase_id' => $purchase->id
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to save purchase: ' . $e->getMessage()
            ], 500);
        }
    }

    public function showDetails($id)
    {
        $purchase = Purchase::with(['company', 'items.item'])->findOrFail($id);
        return view('purchases.details', compact('purchase'));
    }

    public function index()
    {
        // Eager load company and items relationships
        $purchases = Purchase::with(['company', 'items'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('purchases.index', [
            'purchases' => $purchases
        ]);
    }

    public function edit($id)
    {
        $purchase = Purchase::with(['company', 'items.item'])->findOrFail($id);
        
        return view('purchases.edit', compact('purchase'));
    }

    public function update(Request $request, $id)
{
    DB::beginTransaction();

    try {
        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'company_invoice' => 'nullable|string',
            'invoice_number' => 'required|string|unique:purchases,invoice_number,'.$id,
            'invoice_date' => 'required|date',
            'net_payable' => 'required|numeric|min:0',
            'prev_balance' => 'nullable|numeric',
            'total_balance' => 'required|numeric',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.id' => 'nullable|exists:purchase_items,id',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.batch_number' => 'nullable|string|max:255',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.purchase_price' => 'required|numeric|min:0',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.discount' => 'nullable|numeric|min:0|max:100',
            'items.*.exp_date' => 'nullable|date',
            'items.*.total' => 'required|numeric|min:0',
        ]);

        $purchase = Purchase::with('items')->findOrFail($id);
        
        // Update purchase
        $purchase->update([
            'company_id' => $validated['company_id'],
            'company_invoice' => $validated['company_invoice'],
            'invoice_number' => $validated['invoice_number'],
            'invoice_date' => $validated['invoice_date'],
            'net_payable' => $validated['net_payable'],
            'prev_balance' => $validated['prev_balance'] ?? 0,
            'total_balance' => $validated['total_balance'],
            'notes' => $validated['notes'] ?? null,
            'total_purchases' => $validated['net_payable']
        ]);

        $existingItems = $purchase->items->keyBy('id');
        $newItemIds = [];

        foreach ($validated['items'] as $itemData) {
            if (isset($itemData['id']) && $existingItems->has($itemData['id'])) {
                // Update existing item
                $item = $existingItems->get($itemData['id']);
                $oldQuantity = $item->quantity;
                
                $item->update([
                    'item_id' => $itemData['item_id'],
                    'batch_number' => $itemData['batch_number'] ?? null,
                    'exp_date' => $itemData['exp_date'] ?? null,
                    'price' => $itemData['price'],
                    'purchase_price' => $itemData['purchase_price'],
                    'quantity' => $itemData['quantity'],
                    'discount' => $itemData['discount'] ?? 0,
                    'total' => $itemData['total']
                ]);
                
                // Update inventory
                Inventory::updateInventory($item, $oldQuantity);
                
                $newItemIds[] = $item->id;
            } else {
                // Create new item
                $item = PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'item_id' => $itemData['item_id'],
                    'batch_number' => $itemData['batch_number'] ?? null,
                    'exp_date' => $itemData['exp_date'] ?? null,
                    'price' => $itemData['price'],
                    'purchase_price' => $itemData['purchase_price'],
                    'quantity' => $itemData['quantity'],
                    'discount' => $itemData['discount'] ?? 0,
                    'total' => $itemData['total']
                ]);
                
                // Add to inventory
                Inventory::updateInventory($item);
                
                $newItemIds[] = $item->id;
            }
        }

        // Handle deleted items
        $itemsToDelete = $purchase->items()->whereNotIn('id', $newItemIds)->get();
        foreach ($itemsToDelete as $item) {
            // Remove from inventory
            Inventory::where('purchase_item_id', $item->id)
                ->decrement('quantity', $item->quantity);
                
            $item->delete();
        }

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Purchase updated successfully!',
            'redirect' => route('purchases.index')
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'success' => false,
            'message' => 'Error updating purchase: ' . $e->getMessage()
        ], 500);
    }
}

    protected function validatePurchaseRequest(Request $request, $purchaseId)
    {
        return $request->validate([
            'company_id' => 'required|exists:companies,id',
            'company_invoice' => 'nullable|string|max:255',
            'invoice_number' => 'required|string|max:255|unique:purchases,invoice_number,'.$purchaseId,
            'invoice_date' => 'required|date',
            'net_payable' => 'required|numeric|min:0',
            'prev_balance' => 'nullable|numeric',
            'total_balance' => 'required|numeric',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.id' => 'nullable|exists:purchase_items,id',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.batch_number' => 'nullable|string|max:255',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.purchase_price' => 'required|numeric|min:0',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.discount' => 'nullable|numeric|min:0|max:100',
            'items.*.exp_date' => 'nullable|date',
            'items.*.total' => 'required|numeric|min:0',
        ]);
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        
        try {
            $purchase = Purchase::with('items')->findOrFail($id);
            
            // Adjust inventory before deleting
            foreach ($purchase->items as $item) {
                $item->item->decrement('inventory_qty', $item->quantity);
            }
            
            $purchase->items()->delete();
            $purchase->delete();

            DB::commit();

            if(request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Purchase deleted successfully'
                ]);
            }

            return redirect()->route('purchases.index')
                ->with('success', 'Purchase deleted successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            
            if(request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error deleting purchase: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Error deleting purchase: ' . $e->getMessage());
        }
    }
}