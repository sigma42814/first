@extends('layouts.app')

@section('title', 'Item Details')

@section('content')
<div class="container">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                <i class="fas fa-cube me-2"></i> Item Details
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Item Code:</strong> {{ $item->item_code }}</p>
                    <p><strong>Item Name:</strong> {{ $item->item_name }}</p>
                    <p><strong>Purchase Price:</strong> {{ $item->item_purchase_price }}</p>
                    <p><strong>MRP:</strong> {{ $item->mrp }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Trade Price:</strong> {{ $item->tp }}</p>
                    <p><strong>Low Quantity Alert:</strong> {{ $item->low_quantity }}</p>
                    <p><strong>Status:</strong> {{ $item->status ? 'Enabled' : 'Disabled' }}</p>
                    <p><strong>Company:</strong> {{ $item->company }}</p>
                </div>
            </div>
            <div class="mb-3">
                <p><strong>Product Photo:</strong></p>
                @if ($item->product_photo)
                    <img src="{{ asset('storage/' . $item->product_photo) }}" alt="Product Photo" class="img-thumbnail" style="max-width: 200px;">
                @else
                    <p>No photo available.</p>
                @endif
            </div>
            <a href="{{ route('items.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back to List
            </a>
        </div>
    </div>
</div>
@endsection