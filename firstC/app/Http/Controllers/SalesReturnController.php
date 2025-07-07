<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Employee;
use App\Models\Inventory;
use App\Models\Item;
use App\Models\SalesReturn;
use App\Models\SalesReturnItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalesReturnController extends Controller
{
    public function index()
    {
        $returns = SalesReturn::with(['customer', 'items.item'])
            ->latest()
            ->get();
            
        return view('sales_returns.index', compact('returns'));
    }

    public function create()
    {
        $customers = Customer::orderBy('name')->get(['id', 'name', 'address']);
        $items = Item::orderBy('item_name')->get(['id', 'item_name', 'tp as price']);
        $employees = Employee::orderBy('name')->get(['id', 'name']);
        
        return view('sales_returns.create', compact('customers', 'items', 'employees'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        
        try {
            $validated = $request->validate([
                'customer_id' => 'required|exists:customers,id',
                'return_date' => 'required|date',
                'username' => 'required|string',
                'reason' => 'nullable|string|max:255',
                'items' => 'required|array|min:1',
                'items.*.item_id' => 'required|exists:items,id',
                'items.*.batch_number' => 'nullable|string|max:100',
                'items.*.exp_date' => 'nullable|date',
                'items.*.price' => 'required|numeric|min:0',
                'items.*.quantity' => 'required|numeric|min:1',
                'items.*.discount' => 'nullable|numeric|min:0|max:100',
                'items.*.discount2' => 'nullable|numeric|min:0|max:100',
                'items.*.bonus' => 'nullable|numeric|min:0',
                'items.*.total' => 'required|numeric|min:0'
            ]);

            $totalReturn = collect($request->items)->sum('total');
            
            $return = SalesReturn::create([
                'customer_id' => $validated['customer_id'],
                'username' => $validated['username'],
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
                    'selling_price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'discount' => $item['discount'] ?? 0,
                    'discount2' => $item['discount2'] ?? 0,
                    'bonus' => $item['bonus'] ?? 0,
                    'total' => $item['total']
                ]);

                $this->adjustInventory($returnItem, 'increment');
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Sales return created successfully!',
                'redirect_url' => route('sales-returns.index'),
                'return_id' => $return->id
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create sales return: ' . $e->getMessage(),
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(SalesReturn $salesReturn)
    {
        $salesReturn->load(['customer', 'items.item']);
        return view('sales_returns.show', compact('salesReturn'));
    }

    public function print(SalesReturn $salesReturn)
    {
        $salesReturn->load(['customer', 'items.item']);
        return view('sales_returns.print', compact('salesReturn'));
    }

    public function edit(SalesReturn $salesReturn)
    {
        $salesReturn->load(['customer', 'items.item']);
        $customers = Customer::orderBy('name')->get(['id', 'name', 'address']);
        $items = Item::orderBy('item_name')->get(['id', 'item_name', 'tp as price']);
        $employees = Employee::orderBy('name')->get(['id', 'name']);
        
        return view('sales_returns.edit', compact('salesReturn', 'customers', 'items', 'employees'));
    }

    public function update(Request $request, SalesReturn $salesReturn)
    {
        DB::beginTransaction();
        
        try {
            $validated = $request->validate([
                'customer_id' => 'required|exists:customers,id',
                'return_date' => 'required|date',
                'username' => 'required|string',
                'reason' => 'nullable|string|max:255',
                'items' => 'required|array|min:1',
                'items.*.item_id' => 'required|exists:items,id',
                'items.*.batch_number' => 'nullable|string|max:100',
                'items.*.exp_date' => 'nullable|date',
                'items.*.price' => 'required|numeric|min:0',
                'items.*.quantity' => 'required|numeric|min:1',
                'items.*.discount' => 'nullable|numeric|min:0|max:100',
                'items.*.discount2' => 'nullable|numeric|min:0|max:100',
                'items.*.bonus' => 'nullable|numeric|min:0',
                'items.*.total' => 'required|numeric|min:0'
            ]);

            // First restore inventory for existing items
            foreach ($salesReturn->items as $item) {
                $this->adjustInventory($item, 'decrement');
            }

            // Delete old items
            $salesReturn->items()->delete();

            $totalReturn = collect($request->items)->sum('total');
            
            // Update the return
            $salesReturn->update([
                'customer_id' => $validated['customer_id'],
                'username' => $validated['username'],
                'return_date' => $validated['return_date'],
                'total_return' => $totalReturn,
                'net_payable' => $totalReturn,
                'reason' => $validated['reason'] ?? null
            ]);

            // Add new items
            foreach ($validated['items'] as $item) {
                $returnItem = $salesReturn->items()->create([
                    'item_id' => $item['item_id'],
                    'batch_number' => $item['batch_number'] ?? null,
                    'exp_date' => $item['exp_date'] ?? null,
                    'price' => $item['price'],
                    'selling_price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'discount' => $item['discount'] ?? 0,
                    'discount2' => $item['discount2'] ?? 0,
                    'bonus' => $item['bonus'] ?? 0,
                    'total' => $item['total']
                ]);

                $this->adjustInventory($returnItem, 'increment');
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Sales return updated successfully!',
                'redirect_url' => route('sales-returns.index'),
                'return_id' => $salesReturn->id
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update sales return: ' . $e->getMessage(),
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function adjustInventory($item, $action = 'increment')
    {
        $query = Inventory::where('item_id', $item->item_id);

        if ($item->batch_number) {
            $query->where('batch_number', $item->batch_number);
        } else {
            $query->whereNull('batch_number');
        }

        if ($item->exp_date) {
            $query->where('exp_date', $item->exp_date);
        } else {
            $query->whereNull('exp_date');
        }

        $inventory = $query->first();

        if ($inventory) {
            if ($action === 'increment') {
                $inventory->increment('quantity', $item->quantity);
            } else {
                $inventory->decrement('quantity', $item->quantity);
            }
        } else if ($action === 'increment') {
            Inventory::create([
                'item_id' => $item->item_id,
                'batch_number' => $item->batch_number,
                'exp_date' => $item->exp_date,
                'quantity' => $item->quantity,
                'purchase_price' => $item->price,
                'selling_price' => $item->price
            ]);
        }
    }

    public function destroy(SalesReturn $salesReturn)
    {
        DB::beginTransaction();
        
        try {
            foreach ($salesReturn->items as $item) {
                $this->adjustInventory($item, 'decrement');
            }
            
            $salesReturn->items()->delete();
            $salesReturn->delete();

            DB::commit();

            return redirect()->route('sales-returns.index')
                ->with('success', 'Sales return deleted successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error deleting sales return: ' . $e->getMessage());
        }
    }

    public function searchCustomers(Request $request)
    {
        $query = $request->input('query');
        
        return Customer::when($query, function($q) use ($query) {
                $q->where('id', 'like', "%$query%")
                  ->orWhere('name', 'like', "%$query%")
                  ->orWhere('phone', 'like', "%$query%");
            })
            ->orderBy('name')
            ->limit(10)
            ->get(['id', 'name', 'address', 'phone']);
    }

    public function searchEmployees(Request $request)
    {
        $query = $request->input('query');
        
        return Employee::when($query, function($q) use ($query) {
                $q->where('name', 'like', "%$query%")
                  ->orWhere('id', 'like', "%$query%");
            })
            ->orderBy('name')
            ->limit(10)
            ->get(['id', 'name']);
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
            ->get()
            ->map(function($item) {
                return [
                    'id' => $item->id,
                    'item_name' => $item->item_name,
                    'price' => $item->tp // Using tp as the price
                ];
            });
    }

    public function searchItemsByName(Request $request)
    {
        $query = $request->input('query');
        
        return Item::when($query, function($q) use ($query) {
                $q->where('item_name', 'like', "%$query%");
            })
            ->orderBy('item_name')
            ->limit(10)
            ->get()
            ->map(function($item) {
                return [
                    'id' => $item->id,
                    'item_name' => $item->item_name,
                    'price' => $item->tp // Using tp as the price
                ];
            });
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
                    'price' => $item->selling_price, // Using selling_price from inventory
                    'exp_date' => $item->exp_date,
                    'available_quantity' => $item->quantity
                ];
            });
    }

    public function generateReturnNumber()
    {
        return response()->json([
            'return_number' => 'SR-' . date('Ymd') . '-' . str_pad(SalesReturn::count() + 1, 4, '0', STR_PAD_LEFT)
        ]);
    }
}