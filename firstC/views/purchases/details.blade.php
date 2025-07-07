@extends('layouts.app')

@section('title', 'Purchase Details')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white py-2">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-file-invoice me-2"></i> Purchase Invoice #{{ $purchase->invoice_number }}
                </h5>
                <div>
                    <a href="{{ route('purchases.print', $purchase->id) }}" class="btn btn-light btn-sm">
                        <i class="fas fa-print me-1"></i> Print
                    </a>
                    <a href="{{ route('purchases.index') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-arrow-left me-1"></i> Back
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <!-- Invoice Header -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <h6>Company Information</h6>
                    <p class="mb-1"><strong>{{ $purchase->company->name }}</strong></p>
                    <p class="mb-1">{{ $purchase->company->address }}</p>
                    <p class="mb-1">Phone: {{ $purchase->company->phone ?? 'N/A' }}</p>
                </div>
                <div class="col-md-6 text-end">
                    <h6>Invoice Details</h6>
                    <p class="mb-1"><strong>Invoice #:</strong> {{ $purchase->invoice_number }}</p>
                    <p class="mb-1"><strong>Date:</strong> {{ $purchase->invoice_date->format('d/m/Y') }}</p>
                    <p class="mb-1"><strong>Company Invoice #:</strong> {{ $purchase->company_invoice ?? 'N/A' }}</p>
                </div>
            </div>

            <!-- Items Table -->
            <div class="table-responsive mb-4">
                <table class="table table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Item</th>
                            <th>Batch</th>
                            <th class="text-end">Price</th>
                            <th class="text-end">Qty</th>
                            <th class="text-end">Disc %</th>
                            <th class="text-end">Expiry</th>
                            <th class="text-end">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($purchase->items as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->item->item_name }}</td>
                            <td>{{ $item->batch_number ?? 'N/A' }}</td>
                            <td class="text-end">{{ number_format($item->price, 2) }}</td>
                            <td class="text-end">{{ $item->quantity }}</td>
                            <td class="text-end">{{ number_format($item->discount, 2) }}</td>
                            <td class="text-end">{{ $item->exp_date ? \Carbon\Carbon::parse($item->exp_date)->format('d/m/Y') : 'N/A' }}</td>
                            <td class="text-end">{{ number_format($item->total, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Summary Section -->
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Notes:</label>
                        <p>{{ $purchase->notes ?? 'No notes available' }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th class="text-end">Total Purchases:</th>
                                    <td class="text-end">{{ number_format($purchase->total_purchases, 2) }}</td>
                                </tr>
                                <tr>
                                    <th class="text-end">Net Payable:</th>
                                    <td class="text-end">{{ number_format($purchase->net_payable, 2) }}</td>
                                </tr>
                                <tr>
                                    <th class="text-end">Previous Balance:</th>
                                    <td class="text-end">{{ number_format($purchase->prev_balance, 2) }}</td>
                                </tr>
                                <tr class="table-active">
                                    <th class="text-end">Total Balance:</th>
                                    <td class="text-end fw-bold">{{ number_format($purchase->total_balance, 2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer text-muted small">
            <div class="d-flex justify-content-between">
                <div>Created: {{ $purchase->created_at->format('d/m/Y H:i') }}</div>
                <div>Last Updated: {{ $purchase->updated_at->format('d/m/Y H:i') }}</div>
            </div>
        </div>
    </div>
</div>
@endsection