<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Inventory;
use App\Models\Item;
use App\Models\PurchaseReturn;
use App\Models\PurchaseReturnItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseReturnController extends Controller
{
    public function index()
    {
        $returns = PurchaseReturn::with(['company', 'items.item'])
            ->latest()
            ->get();
            
        return view('purchase_returns.index', compact('returns'));
    }

    public function create()
    {
        $companies = Company::orderBy('name')->get(['id', 'code', 'name']);
        $items = Item::orderBy('item_name')->get(['id', 'item_name', 'tp']);
        
        return view('purchase_returns.create', compact('companies', 'items'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        
        try {
            $validated = $request->validate([
                'company_id' => 'required|exists:companies,id',
                'return_date' => 'required|date',
                'reason' => 'nullable|string|max:255',
                'items' => 'required|array|min:1',
                'items.*.item_id' => 'required|exists:items,id',
                'items.*.batch_number' => 'nullable|string|max:100',
                'items.*.exp_date' => 'nullable|date',
                'items.*.price' => 'required|numeric|min:0',
                'items.*.quantity' => 'required|numeric|min:1',
                'items.*.discount' => 'nullable|numeric|min:0|max:100',
                'items.*.total' => 'required|numeric|min:0'
            ]);

            // Generate return number
            $returnNumber = 'PR-' . date('Ymd') . '-' . str_pad(PurchaseReturn::count() + 1, 4, '0', STR_PAD_LEFT);

            $totalReturn = collect($request->items)->sum('total');
            
            $return = PurchaseReturn::create([
                'company_id' => $validated['company_id'],
                'return_number' => $returnNumber,
                'return_date' => $validated['return_date'],
                'total_return' => $totalReturn,
                'net_payable' => $totalReturn,
                'reason' => $validated['reason'] ?? null
            ]);

            foreach ($validated['items'] as $item) {
                $returnItem = $return->items()->create([
                    'item_id' => $item['item_id'],
                    'batch_number' => $item['batch_number'] ?? null,
                    'exp_date' => $item['exp_date'] ?? null,
                    'price' => $item['price'],
                    'purchase_price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'discount' => $item['discount'] ?? 0,
                    'total' => $item['total']
                ]);

                // Update inventory
                if ($item['batch_number']) {
                    $inventory = Inventory::where('item_id', $item['item_id'])
                        ->where('batch_number', $item['batch_number'])
                        ->first();
                } else {
                    $inventory = Inventory::where('item_id', $item['item_id'])
                        ->whereNull('batch_number')
                        ->first();
                }

                if ($inventory) {
                    $inventory->decrement('quantity', $item['quantity']);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Purchase return created successfully!',
                'redirect_url' => route('purchase-returns.index')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create purchase return: ' . $e->getMessage(),
                'error' => $e->getTraceAsString()
            ], 500);
        }
    }

    public function show(PurchaseReturn $purchaseReturn)
    {
        $purchaseReturn->load(['company', 'items.item']);
        return view('purchase_returns.show', compact('purchaseReturn'));
    }

    public function print(PurchaseReturn $purchaseReturn)
    {
        $purchaseReturn->load(['company', 'items.item']);
        return view('purchase_returns.print', compact('purchaseReturn'));
    }

    public function edit(PurchaseReturn $purchaseReturn)
    {
        $purchaseReturn->load(['company', 'items.item']);
        $companies = Company::orderBy('name')->get(['id', 'code', 'name']);
        $items = Item::orderBy('item_name')->get(['id', 'item_name', 'tp']);
        
        return view('purchase_returns.edit', compact('purchaseReturn', 'companies', 'items'));
    }

    public function update(Request $request, PurchaseReturn $purchaseReturn)
    {
        DB::beginTransaction();
        
        try {
            $validated = $request->validate([
                'company_id' => 'required|exists:companies,id',
                'return_date' => 'required|date',
                'reason' => 'nullable|string|max:255',
                'items' => 'required|array|min:1',
                'items.*.item_id' => 'required|exists:items,id',
                'items.*.batch_number' => 'nullable|string|max:100',
                'items.*.exp_date' => 'nullable|date',
                'items.*.price' => 'required|numeric|min:0',
                'items.*.quantity' => 'required|numeric|min:1',
                'items.*.discount' => 'nullable|numeric|min:0|max:100',
                'items.*.total' => 'required|numeric|min:0'
            ]);

            // First restore inventory for existing items
            foreach ($purchaseReturn->items as $item) {
                $this->adjustInventory($item, 'increment');
            }

            // Delete old items
            $purchaseReturn->items()->delete();

            $totalReturn = collect($request->items)->sum('total');
            
            // Update the return
            $purchaseReturn->update([
                'company_id' => $validated['company_id'],
                'return_date' => $validated['return_date'],
                'total_return' => $totalReturn,
                'net_payable' => $totalReturn,
                'reason' => $validated['reason'] ?? null
            ]);

            // Add new items
            foreach ($validated['items'] as $item) {
                $returnItem = $purchaseReturn->items()->create([
                    'item_id' => $item['item_id'],
                    'batch_number' => $item['batch_number'] ?? null,
                    'exp_date' => $item['exp_date'] ?? null,
                    'price' => $item['price'],
                    'purchase_price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'discount' => $item['discount'] ?? 0,
                    'total' => $item['total']
                ]);

                // Update inventory with new quantities
                $this->adjustInventory($returnItem, 'decrement');
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Purchase return updated successfully!',
                'redirect_url' => route('purchase-returns.index')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update purchase return: ' . $e->getMessage(),
                'error' => $e->getTraceAsString()
            ], 500);
        }
    }

    private function adjustInventory($item, $action = 'decrement')
    {
        $query = Inventory::where('item_id', $item->item_id);

        if ($item->batch_number) {
            $query->where('batch_number', $item->batch_number);
        } else {
            $query->whereNull('batch_number');
        }

        $inventory = $query->first();

        if ($inventory) {
            if ($action === 'decrement') {
                $inventory->decrement('quantity', $item->quantity);
            } else {
                $inventory->increment('quantity', $item->quantity);
            }
        }
    }

    public function destroy(PurchaseReturn $purchaseReturn)
    {
        DB::beginTransaction();
        
        try {
            foreach ($purchaseReturn->items as $item) {
                // Restore inventory
                if ($item->batch_number) {
                    $inventory = Inventory::where('item_id', $item->item_id)
                        ->where('batch_number', $item->batch_number)
                        ->first();
                } else {
                    $inventory = Inventory::where('item_id', $item->item_id)
                        ->whereNull('batch_number')
                        ->first();
                }

                if ($inventory) {
                    $inventory->increment('quantity', $item->quantity);
                }
            }
            
            $purchaseReturn->items()->delete();
            $purchaseReturn->delete();

            DB::commit();

            return redirect()->route('purchase-returns.index')
                ->with('success', 'Purchase return deleted successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error deleting purchase return: ' . $e->getMessage());
        }
    }

    // API Endpoints for search
    public function searchCompanies(Request $request)
    {
        $query = $request->input('query');
        
        return Company::when($query, function($q) use ($query) {
                $q->where('code', 'like', "%$query%")
                  ->orWhere('name', 'like', "%$query%");
            })
            ->orderBy('name')
            ->limit(10)
            ->get(['id', 'code', 'name', 'address']);
    }

    public function searchItems(Request $request)
    {
        $query = $request->input('query');
        
        return Item::when($query, function($q) use ($query) {
                $q->where('id', 'like', "%$query%")
                  ->orWhere('item_name', 'like', "%$query%");
            })
            ->orderBy('item_name')
            ->limit(10)
            ->get(['id', 'item_name', 'tp as price']);
    }

    public function searchItemsByName(Request $request)
    {
        $query = $request->input('query');
        
        return Item::when($query, function($q) use ($query) {
                $q->where('item_name', 'like', "%$query%");
            })
            ->orderBy('item_name')
            ->limit(10)
            ->get(['id', 'item_name', 'tp as price']);
    }

    public function searchInventory(Request $request)
    {
        $query = $request->input('query');
        
        return Inventory::with('item')
            ->whereHas('item', function($q) use ($query) {
                $q->where('item_name', 'like', "%$query%")
                  ->orWhere('id', 'like', "%$query%");
            })
            ->limit(10)
            ->get()
            ->map(function($item) {
                return [
                    'id' => $item->item_id,
                    'item_name' => $item->item->item_name,
                    'batch_number' => $item->batch_number,
                    'price' => $item->selling_price,
                    'exp_date' => $item->exp_date,
                    'available_quantity' => $item->quantity
                ];
            });
    }
}