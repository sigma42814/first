@extends('layouts.app')

@section('title', 'Expiry Report')

@section('content')
<div class="container-fluid px-4">
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-warning text-dark py-2">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-calendar-times me-2"></i>Expiry Report
                </h5>
                <div>
                    <a href="{{ route('inventory.index') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-arrow-left me-1"></i> Back
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('inventory.expiry-report') }}" class="mb-4">
                <div class="row">
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="days" class="form-label">Show items expiring within (days)</label>
                            <select name="days" id="days" class="form-select">
                                <option value="7" {{ request('days') == '7' ? 'selected' : '' }}>7 days</option>
                                <option value="30" {{ request('days') == '30' ? 'selected' : '' }}>30 days</option>
                                <option value="60" {{ request('days') == '60' ? 'selected' : '' }}>60 days</option>
                                <option value="90" {{ request('days') == '90' ? 'selected' : '' }}>90 days</option>
                                <option value="" {{ empty(request('days')) ? 'selected' : '' }}>All future expiries</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">Filter</button>
                        <a href="{{ route('inventory.expiry-report') }}" class="btn btn-secondary">Reset</a>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Item</th>
                            <th>Batch Number</th>
                            <th>Expiry Date</th>
                            <th>Days Remaining</th>
                            <th>Remaining Quantity</th>
                            <th>Unit</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($expiries as $expiry)
                        @php
                            $daysRemaining = \Carbon\Carbon::parse($expiry->expiry_date)->diffInDays(now());
                        @endphp
                        <tr class="{{ $daysRemaining <= 30 ? 'table-danger' : '' }}">
                            <td>{{ $expiry->item->item_name }}</td>
                            <td>{{ $expiry->batch_number ?? 'N/A' }}</td>
                            <td>{{ $expiry->expiry_date->format('Y-m-d') }}</td>
                            <td>{{ $daysRemaining }}</td>
                            <td>{{ number_format($expiry->remaining_quantity, 2) }}</td>
                            <td>{{ $expiry->item->unit }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">No expiring items found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $expiries->links() }}
            </div>
        </div>
    </div>
</div>
@endsection