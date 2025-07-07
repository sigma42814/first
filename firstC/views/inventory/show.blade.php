@extends('layouts.app')

@section('title', 'Inventory Details')

@section('content')
<div class="container-fluid px-4">
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white py-2">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-box me-2"></i>Inventory Details: {{ $item->item_name }}
                </h5>
                <div>
                    <a href="{{ route('inventory.index') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-arrow-left me-1"></i> Back
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-light">
                        <div class="card-body text-center">
                            <h6 class="card-title">Current Stock</h6>
                            <h3 class="{{ $currentStock < $item->min_stock_level ? 'text-danger' : 'text-success' }}">
                                {{ $currentStock }} {{ $item->unit }}
                            </h3>
                            @if($currentStock < $item->min_stock_level)
                                <span class="badge bg-warning text-dark">Low Stock</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-light">
                        <div class="card-body text-center">
                            <h6 class="card-title">Minimum Level</h6>
                            <h3>{{ $item->min_stock_level }} {{ $item->unit }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-light">
                        <div class="card-body text-center">
                            <h6 class="card-title">Cost Price</h6>
                            <h3>{{ number_format($item->cost_price, 2) }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-light">
                        <div class="card-body text-center">
                            <h6 class="card-title">Selling Price</h6>
                            <h3>{{ number_format($item->selling_price, 2) }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Batch Information -->
            <div class="card mb-4">
                <div class="card-header bg-secondary text-white py-2">
                    <h6 class="mb-0">
                        <i class="fas fa-layer-group me-1"></i>Batch Information
                    </h6>
                </div>
                <div class="card-body">
                    @if($batches->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Batch Number</th>
                                        <th>Expiry Date</th>
                                        <th>Remaining Quantity</th>
                                        <th>Days Remaining</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($batches as $batch)
                                    <tr class="{{ \Carbon\Carbon::parse($batch->expiry_date)->diffInDays(now()) <= 30 ? 'table-danger' : '' }}">
                                        <td>{{ $batch->batch_number }}</td>
                                        <td>{{ $batch->expiry_date ? $batch->expiry_date->format('Y-m-d') : 'N/A' }}</td>
                                        <td>{{ $batch->remaining_quantity }}</td>
                                        <td>
                                            @if($batch->expiry_date)
                                                {{ \Carbon\Carbon::parse($batch->expiry_date)->diffInDays(now()) }}
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info mb-0">No batch information available for this item.</div>
                    @endif
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header bg-secondary text-white py-2">
                    <h6 class="mb-0">
                        <i class="fas fa-adjust me-1"></i>Stock Adjustment
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('inventory.adjust') }}" method="POST">
                        @csrf
                        <input type="hidden" name="item_id" value="{{ $item->id }}">
                        <div class="row">
                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label for="quantity" class="form-label">Quantity</label>
                                    <input type="number" step="0.01" class="form-control" id="quantity" name="quantity" required>
                                    <small class="text-muted">Use positive to add, negative to remove</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="batch_number" class="form-label">Batch Number</label>
                                    <input type="text" class="form-control" id="batch_number" name="batch_number">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label for="expiry_date" class="form-label">Expiry Date</label>
                                    <input type="date" class="form-control" id="expiry_date" name="expiry_date">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="reason" class="form-label">Reason</label>
                                    <input type="text" class="form-control" id="reason" name="reason" required>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label for="adjustment_date" class="form-label">Date</label>
                                    <input type="date" class="form-control" id="adjustment_date" name="adjustment_date" value="{{ date('Y-m-d') }}" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">Apply Adjustment</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-secondary text-white py-2">
                    <h6 class="mb-0">
                        <i class="fas fa-history me-1"></i>Stock Movement History
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Batch</th>
                                    <th>Document</th>
                                    <th>Quantity</th>
                                    <th>Remaining</th>
                                    <th>Cost</th>
                                    <th>Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($movements as $movement)
                                <tr>
                                    <td>{{ $movement->movement_date->format('Y-m-d') }}</td>
                                    <td>
                                        @switch($movement->movement_type)
                                            @case('purchase') <span class="badge bg-success">Purchase</span> @break
                                            @case('sale') <span class="badge bg-danger">Sale</span> @break
                                            @case('purchase_return') <span class="badge bg-warning text-dark">Pur. Return</span> @break
                                            @case('sale_return') <span class="badge bg-info">Sale Return</span> @break
                                            @case('adjustment') <span class="badge bg-primary">Adjustment</span> @break
                                        @endswitch
                                    </td>
                                    <td>{{ $movement->batch_number ?? 'N/A' }}</td>
                                    <td>
                                        @if($movement->purchase)
                                            <a href="{{ route('purchases.show', $movement->purchase_id) }}">Purchase #{{ $movement->purchase->invoice_number }}</a>
                                        @elseif($movement->sale)
                                            <a href="{{ route('sales.show', $movement->sale_id) }}">Sale #{{ $movement->sale->invoice_number }}</a>
                                        @elseif($movement->purchaseReturn)
                                            <a href="{{ route('purchase-returns.show', $movement->purchase_return_id) }}">Pur. Return #{{ $movement->purchaseReturn->return_number }}</a>
                                        @elseif($movement->saleReturn)
                                            <a href="{{ route('sale-returns.show', $movement->sale_return_id) }}">Sale Return #{{ $movement->saleReturn->return_number }}</a>
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td class="{{ $movement->quantity < 0 ? 'text-danger' : 'text-success' }}">
                                        {{ $movement->quantity }} {{ $item->unit }}
                                    </td>
                                    <td>{{ $movement->remaining_quantity }} {{ $item->unit }}</td>
                                    <td>{{ number_format($movement->unit_cost, 2) }}</td>
                                    <td>{{ number_format($movement->unit_price, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection