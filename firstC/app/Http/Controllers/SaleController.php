<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Traits\InventoryTrait;

class SaleController extends Controller
{
    use InventoryTrait;

    public function index()
    {
        $sales = Sale::select(['id', 'customer_id', 'invoice_number', 'invoice_date', 'total_sales', 'created_at'])
            ->with(['customer:id,name', 'items'])
            ->withCount('items')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
    
        $customers = Customer::orderBy('name')->get(['id', 'name']);
    
        return view('sales.index', compact('sales', 'customers'));
    }

    public function show(Sale $sale)
    {
        $sale->load([
            'customer:id,name,address,phone',
            'items'
        ]);
        
        return view('sales.show', compact('sale'));
    }

    public function print(Sale $sale)
    {
        $sale->load(['customer', 'items']);
        return view('sales.print', compact('sale'));
    }

    public function invoice(Sale $sale)
    {
        $sale->load(['customer', 'items']);
        $pdf = PDF::loadView('sales.invoice', compact('sale'));
        return $pdf->download('invoice-'.$sale->invoice_number.'.pdf');
    }

    public function create()
    {
        $customers = Customer::orderBy('name')->get(['id', 'name']);
        $items = Item::select(['id as code', 'item_name', 'selling_price as price'])
            ->active()
            ->orderBy('item_name')
            ->get();
        $employees = Employee::orderBy('name')->get(['id', 'name']);
        
        return view('sales.create', compact('customers', 'items', 'employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'invoice_date' => 'required|date',
            'username' => 'required|string',
            'items' => 'required|array|min:1',
            'items.*.code' => 'required|exists:items,id',
            'items.*.item_name' => 'required|string',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.total' => 'required|numeric|min:0',
            'items.*.batch_number' => 'nullable|string',
        ]);

        // Calculate totals
        $totalSales = array_reduce($request->items, function($carry, $item) {
            return $carry + ($item['total'] ?? 0);
        }, 0);

        DB::beginTransaction();

        try {
            // Create the sale
            $sale = Sale::create([
                'customer_id' => $validated['customer_id'],
                'invoice_date' => $validated['invoice_date'],
                'username' => $validated['username'],
                'total_sales' => $totalSales,
                'net_payable' => $totalSales,
                'prev_balance' => 0,
                'total_balance' => $totalSales,
            ]);

            // Create sale items
            $saleItems = [];
            foreach ($request->items as $item) {
                $saleItem = $sale->items()->create([
                    'item_code' => $item['code'],
                    'item_name' => $item['item_name'],
                    'batch_number' => $item['batch_number'] ?? null,
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'discount' => $item['discount'] ?? 0,
                    'discount2' => $item['discount2'] ?? 0,
                    'bonus' => $item['bonus'] ?? 0,
                    'total' => $item['total'],
                ]);
                $saleItems[] = $saleItem;
            }

            // Update inventory
            $this->updateInventoryForSale($sale, $request->items);

            DB::commit();

            return response()->json([
                'success' => true,
                'invoice_number' => $sale->invoice_number,
                'redirect' => route('sales.show', $sale->id),
                'message' => 'Sale saved successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to save sale: ' . $e->getMessage()
            ], 500);
        }
    }

    public function edit(Sale $sale)
    {
        $sale->load(['customer:id,name', 'items']);
        $customers = Customer::orderBy('name')->get(['id', 'name']);
        $items = Item::select(['id as code', 'item_name', 'selling_price as price'])
            ->active()
            ->orderBy('item_name')
            ->get();
        $employees = Employee::orderBy('name')->get(['id', 'name']);
        
        return view('sales.edit', compact('sale', 'customers', 'items', 'employees'));
    }

    public function update(Request $request, Sale $sale)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'invoice_date' => 'required|date',
            'username' => 'required|string',
            'items' => 'required|array|min:1',
            'items.*.code' => 'required|exists:items,id',
            'items.*.item_name' => 'required|string',
            'items.*.batch_number' => 'nullable|string',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.discount' => 'nullable|numeric|min:0|max:100',
            'items.*.discount2' => 'nullable|numeric|min:0|max:100',
            'items.*.bonus' => 'nullable|numeric|min:0',
            'items.*.total' => 'required|numeric|min:0',
            'net_payable' => 'required|numeric|min:0',
            'prev_balance' => 'nullable|numeric',
            'total_balance' => 'required|numeric',
        ]);

        DB::beginTransaction();

        try {
            // First reverse the inventory
            $this->reverseInventoryForSale($sale);

            // Update sale
            $sale->update([
                'customer_id' => $validated['customer_id'],
                'invoice_date' => $validated['invoice_date'],
                'username' => $validated['username'],
                'total_sales' => array_reduce($validated['items'], function($carry, $item) {
                    return $carry + $item['total'];
                }, 0),
                'net_payable' => $validated['net_payable'],
                'prev_balance' => $validated['prev_balance'] ?? 0,
                'total_balance' => $validated['total_balance'],
            ]);

            // Delete existing items
            $sale->items()->delete();

            // Create new items
            $saleItems = [];
            foreach ($validated['items'] as $item) {
                $saleItem = $sale->items()->create([
                    'item_code' => $item['code'],
                    'item_name' => $item['item_name'],
                    'batch_number' => $item['batch_number'] ?? null,
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'discount' => $item['discount'] ?? 0,
                    'discount2' => $item['discount2'] ?? 0,
                    'bonus' => $item['bonus'] ?? 0,
                    'total' => $item['total'],
                ]);
                $saleItems[] = $saleItem;
            }

            // Update inventory with new values
            $this->updateInventoryForSale($sale, $validated['items']);

            DB::commit();

            return response()->json([
                'success' => true,
                'redirect' => route('sales.show', $sale->id),
                'message' => 'Sale updated successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update sale: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Sale $sale)
    {
        DB::beginTransaction();
        
        try {
            // Reverse inventory before deleting
            $this->reverseInventoryForSale($sale);
            
            $sale->items()->delete();
            $sale->delete();
            
            DB::commit();
            return redirect()->route('sales.index')
                ->with('success', 'Sale deleted successfully');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete sale: ' . $e->getMessage());
        }
    }

    public function showDetails(Sale $sale)
    {
        return view('sales.partials.details', [
            'sale' => $sale->load(['customer:id,name', 'items'])
        ]);
    }

    public function searchCustomersByName(Request $request)
    {
        $query = $request->input('q');
        $customers = Customer::where('name', 'like', "%$query%")
            ->select(['id', 'name', 'address', 'phone'])
            ->get();
        return response()->json($customers);
    }
}