<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    // Display a list of items
    public function index()
    {
        $items = Item::all();
        return view('items.index', compact('items'));
    }

    // Show the form for creating a new item
    public function create()
    {
        return view('items.create');
    }

    // Store a newly created item in the database
    public function store(Request $request)
    {
        $request->validate([
            'item_code' => 'required|string|unique:items,item_code',
            'item_name' => 'required|string|max:255',
            'item_purchase_price' => 'required|numeric',
            'mrp' => 'required|numeric',
            'tp' => 'required|numeric',
            'low_quantity' => 'required|integer',
            'status' => 'required|boolean',
            'product_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'company' => 'nullable|string|max:255',
        ]);

        // Handle file upload
        $photoPath = $request->hasFile('product_photo') 
            ? $request->file('product_photo')->store('item_photos', 'public') 
            : null;

        // Create the item
        Item::create([
            'item_code' => $request->item_code,
            'item_name' => $request->item_name,
            'item_purchase_price' => $request->item_purchase_price,
            'mrp' => $request->mrp,
            'tp' => $request->tp,
            'low_quantity' => $request->low_quantity,
            'status' => $request->status,
            'product_photo' => $photoPath,
            'company' => $request->company,
        ]);

        return redirect()->route('items.index')->with('success', 'Item created successfully.');
    }

    // Display the specified item
    public function show(Item $item)
    {
        return view('items.show', compact('item'));
    }

    // Show the form for editing the specified item
    public function edit(Item $item)
    {
        return view('items.edit', compact('item'));
    }

    // Update the specified item in the database
    public function update(Request $request, Item $item)
    {
        $request->validate([
            'item_code' => 'required|string|unique:items,item_code,' . $item->id,
            'item_name' => 'required|string|max:255',
            'item_purchase_price' => 'required|numeric',
            'mrp' => 'required|numeric',
            'tp' => 'required|numeric',
            'low_quantity' => 'required|integer',
            'status' => 'required|boolean',
            'product_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'company' => 'nullable|string|max:255',
        ]);

        // Handle file upload
        $photoPath = $request->hasFile('product_photo') 
            ? $request->file('product_photo')->store('item_photos', 'public') 
            : $item->product_photo;

        // Update the item
        $item->update([
            'item_code' => $request->item_code,
            'item_name' => $request->item_name,
            'item_purchase_price' => $request->item_purchase_price,
            'mrp' => $request->mrp,
            'tp' => $request->tp,
            'low_quantity' => $request->low_quantity,
            'status' => $request->status,
            'product_photo' => $photoPath,
            'company' => $request->company,
        ]);

        return redirect()->route('items.index')->with('success', 'Item updated successfully.');
    }

    // Remove the specified item from the database
    public function destroy(Item $item)
    {
        $item->delete();
        return redirect()->route('items.index')->with('success', 'Item deleted successfully.');
    }

    public function getItemDetails($id)
    {
        $item = Item::find($id);

        if ($item) {
            return response()->json([
                'success' => true,
                'item' => [
                    'name' => $item->item_name, // Use 'item_name' instead of 'name'
                    'price' => $item->mrp, // Use 'mrp' or 'tp' as the price
                ],
            ]);
        }

        return response()->json(['success' => false]);
    }
}