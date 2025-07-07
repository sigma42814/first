// resources/views/inventory/movement-report.blade.php
@extends('layouts.app')

@section('title', 'Stock Movement Report')

@section('content')
<div class="container-fluid px-4">
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-info text-white py-2">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-exchange-alt me-2"></i>Stock Movement Report
                </h5>
                <div>
                    <a href="{{ route('inventory.index') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-arrow-left me-1"></i> Back
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('inventory.movement-report') }}" class="mb-4">
                <div class="row">
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="item_id" class="form-label">Item</label>
                            <select name="item_id" id="item_id" class="form-select">
                                <option value="">All Items</option>
                                @foreach($items as $item)
                                    <option value="{{ $item->id }}" {{ request('item_id') == $item->id ? 'selected' : '' }}>
                                        {{ $item->item_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="mb-3">
                            <label for="type" class="form-label">Movement Type</label>
                            <select name="type" id="type" class="form-select">
                                <option value="">All Types</option>
                                <option value="purchase" {{ request('type') == 'purchase' ? 'selected' : '' }}>Purchase</option>
                                <option value="sale" {{ request('type') == 'sale' ? 'selected' : '' }}>Sale</option>
                                <option value="purchase_return" {{ request('type') == 'purchase_return' ? 'selected' : '' }}>Purchase Return</option>
                                <option value="sale_return" {{ request('type') == 'sale_return' ? 'selected' : '' }}>Sale Return</option>
                                <option value="adjustment" {{ request('type') == 'adjustment' ? 'selected' : '' }}>Adjustment</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="mb-3">
                            <label for="from_date" class="form-label">From Date</label>
                            <input type="date" class="form-control" id="from_date" name="from_date" value="{{ request('from_date') }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="mb-3">
                            <label for="to_date" class="form-label">To Date</label>
                            <input type="date" class="form-control" id="to_date" name="to_date" value="{{ request('to_date') }}">
                        </div>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">Filter</button>
                        <a href="{{ route('inventory.movement-report') }}" class="btn btn-secondary">Reset</a>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th>Item</th>
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
                            <td>{{ $movement->item->item_name }}</td>
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
                                {{ $movement->quantity }} {{ $movement->item->unit }}
                            </td>
                            <td>{{ $movement->remaining_quantity }} {{ $movement->item->unit }}</td>
                            <td>{{ number_format($movement->unit_cost, 2) }}</td>
                            <td>{{ number_format($movement->unit_price, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $movements->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection