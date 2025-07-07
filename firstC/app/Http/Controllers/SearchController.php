<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Item;
use App\Models\Employee;
use App\Models\Company;

class SearchController extends Controller
{
    // Search customers by name or code
    public function searchCustomers(Request $request)
    {
        $query = $request->input('query');
        $customers = Customer::where('name', 'like', "%{$query}%")
            ->orWhere('phone', 'like', "%{$query}%")
            ->get(['id', 'name', 'address', 'phone']);
        return response()->json($customers);
    }

    // Search employees by name or code
    public function searchEmployees(Request $request)
    {
        $query = $request->input('query');
        $employees = Employee::where('name', 'like', "%{$query}%")
            ->orWhere('code', 'like', "%{$query}%")
            ->get(['id', 'code', 'name', 'phone']);
        return response()->json($employees);
    }

    // Search items by code or name
    public function searchItems(Request $request)
    {
        $query = $request->input('query');
        $items = Item::where('item_name', 'like', "%{$query}%")
            ->orWhere('item_code', 'like', "%{$query}%") // Changed from id to item_code
            ->get(['id', 'item_code', 'item_name', 'tp', 'item_purchase_price']);
        return response()->json($items);
    }

    // Search items by code only
    public function searchItemsByCode(Request $request)
    {
        $query = $request->input('query');
        $items = Item::where('item_code', 'like', "%{$query}%")
            ->get(['id', 'item_code', 'item_name', 'tp', 'item_purchase_price']);
        return response()->json($items);
    }

    // Search companies
    public function searchCompanies(Request $request)
    {
        $query = $request->input('query');
        $companies = Company::where('name', 'like', "%{$query}%")
            ->orWhere('code', 'like', "%{$query}%")
            ->get(['id', 'code', 'name', 'address']);
        return response()->json($companies);
    }
}