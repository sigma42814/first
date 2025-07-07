@extends('layouts.app')

@section('title', 'Inventory Management')

@section('content')
<div class="container-fluid px-4">
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-boxes me-2"></i>Inventory Management
                </h5>
                <div>
                    <a href="{{ route('inventory.low-stock') }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-exclamation-triangle me-1"></i> Low Stock
                    </a>
                    <a href="{{ route('inventory.batch-report') }}" class="btn btn-info btn-sm ms-1">
                        <i class="fas fa-layer-group me-1"></i> Batch Report
                    </a>
                    <a href="{{ route('inventory.expiry-report') }}" class="btn btn-danger btn-sm ms-1">
                        <i class="fas fa-calendar-times me-1"></i> Expiry Report
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
                            <th>Status</th>
                            <th>Unit</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $item)
                        <tr>
                            <td>{{ $item->item_code }}</td>
                            <td>{{ $item->item_name }}</td>
                            <td>{{ $item->category->name ?? 'N/A' }}</td>
                            <td class="{{ $item->current_stock < $item->min_stock_level ? 'text-danger fw-bold' : '' }}">
                                {{ $item->current_stock }}
                            </td>
                            <td>{{ $item->min_stock_level }}</td>
                            <td>
                                @if($item->current_stock < $item->min_stock_level)
                                    <span class="badge bg-warning text-dark">Low Stock</span>
                                @else
                                    <span class="badge bg-success">In Stock</span>
                                @endif
                            </td>
                            <td>{{ $item->unit }}</td>
                            <td>
                                <a href="{{ route('inventory.show', $item->id) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">No inventory items found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $items->links() }}
            </div>
        </div>
    </div>
</div>
@endsection