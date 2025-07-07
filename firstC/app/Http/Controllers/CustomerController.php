<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    // Display a list of customers
    public function index()
    {
        $customers = Customer::all();
        return view('customers.index', compact('customers'));
    }

    // Show the form for creating a new customer
    public function create()
    {
        return view('customers.create');
    }

    // Store a newly created customer in the database
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:customers,email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'company' => 'nullable|string',
            'credit_limit' => 'nullable|numeric',
            'area' => 'nullable|string',
            'brick' => 'nullable|string',
            'salesman' => 'nullable|string',
            'usd' => 'boolean',
            'afn' => 'boolean',
            'pkr' => 'boolean',
        ]);

        Customer::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'company' => $request->company,
            'credit_limit' => $request->credit_limit,
            'area' => $request->area,
            'brick' => $request->brick,
            'salesman' => $request->salesman,
            'usd' => $request->boolean('usd'),
            'afn' => $request->boolean('afn'),
            'pkr' => $request->boolean('pkr'),
        ]);

        return redirect()->route('customers.index')->with('success', 'Customer added successfully.');
    }

    // Display the specified customer
    public function show(Customer $customer)
    {
        return view('customers.show', compact('customer'));
    }

    // Show the form for editing the specified customer
    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    // Update the specified customer in the database
    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:customers,email,' . $customer->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'company' => 'nullable|string',
            'credit_limit' => 'nullable|numeric',
            'area' => 'nullable|string',
            'brick' => 'nullable|string',
            'salesman' => 'nullable|string',
            'usd' => 'boolean',
            'afn' => 'boolean',
            'pkr' => 'boolean',
        ]);

        $customer->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'company' => $request->company,
            'credit_limit' => $request->credit_limit,
            'area' => $request->area,
            'brick' => $request->brick,
            'salesman' => $request->salesman,
            'usd' => $request->boolean('usd'),
            'afn' => $request->boolean('afn'),
            'pkr' => $request->boolean('pkr'),
        ]);

        return redirect()->route('customers.index')->with('success', 'Customer updated successfully.');
    }

    // Remove the specified customer from the database
    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->route('customers.index')->with('success', 'Customer deleted successfully.');
    }

    // Search for customers
   // public function searchCustomersByName(Request $request)
    //{
      //  $query = $request->input('q');
        //$customers = Customer::where('name', 'like', "%$query%")->get();
        //dd($customers); // Debugging
        //return response()->json($customers);
    //}
    // app/Http/Controllers/CustomerController.php

    // app/Http/Controllers/CustomerController.php
    public function search(Request $request)
    {
        $query = $request->input('query');
        $customers = Customer::where('name', 'like', "%{$query}%")->get(['id', 'name', 'address']);
        return response()->json($customers);
    }

}