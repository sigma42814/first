// resources/views/inventory/low-stock.blade.php
@extends('layouts.app')

@section('title', 'Low Stock Report')

@section('content')
<div class="container-fluid px-4">
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-warning text-dark py-2">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-exclamation-triangle me-2"></i>Low Stock Report
                </h5>
                <div>
                    <a href="{{ route('inventory.index') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-arrow-left me-1"></i> Back
                    </a>
                    <a href="{{ route('inventory.low-stock-pdf') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-file-pdf me-1"></i> PDF
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Item Code</th>
                            <th>Item Name</th>
                            <th>Category</th>
                            <th>Current Stock</th>
                            <th>Minimum Level</th>
                            <th>Difference</th>
                            <th>Unit</th>
                            <th>Cost Price</th>
                            <th>Selling Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->item_name }}</td>
                            <td>{{ $item->category->name ?? 'N/A' }}</td>
                            <td class="text-danger fw-bold">{{ $item->current_stock }}</td>
                            <td>{{ $item->min_stock_level }}</td>
                            <td class="text-danger fw-bold">{{ $item->current_stock - $item->min_stock_level }}</td>
                            <td>{{ $item->unit }}</td>
                            <td>{{ number_format($item->cost_price, 2) }}</td>
                            <td>{{ number_format($item->selling_price, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection