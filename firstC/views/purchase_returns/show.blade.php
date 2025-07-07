@extends('layouts.app')

@section('title', 'Purchase Return Details')

@section('content')
<div class="container-fluid px-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white py-2">
            <h5 class="mb-0">
                <i class="fas fa-undo me-2"></i> Purchase Return Details
            </h5>
        </div>
        <div class="card-body p-3">
            <div class="row mb-4">
                <div class="col-md-6">
                    <h6>Return Information</h6>
                    <table class="table table-sm table-borderless">
                        <tr>
                            <th>Return #:</th>
                            <td>{{ $return->return_number }}</td>
                        </tr>
                        <tr>
                            <th>Date:</th>
                            <td>{{ $return->return_date->format('d M Y') }}</td>
                        </tr>
                        <tr>
                            <th>Reference:</th>
                            <td>{{ $return->reference_number ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Reason:</th>
                            <td>{{ $return->reason ?? '-' }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h6>Company Information</h6>
                    <table class="table table-sm table-borderless">
                        <tr>
                            <th>Company:</th>
                            <td>{{ $return->company->name }}</td>
                        </tr>
                        <tr>
                            <th>Code:</th>
                            <td>{{ $return->company->code }}</td>
                        </tr>
                        <tr>
                            <th>Address:</th>
                            <td>{{ $return->company->address ?? '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            @if(isset($return->purchase) && $return->purchase)
            <div class="alert alert-info py-2 mb-3">
                <div class="d-flex justify-content-between align-items-center">
                    <small>
                        <strong>Original Purchase:</strong> 
                        {{ $return->purchase->invoice_number }} ({{ $return->purchase->invoice_date->format('d M Y') }})
                    </small>
                </div>
            </div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-sm">
                    <thead class="table-light">
                        <tr>
                            <th>Code</th>
                            <th>Item</th>
                            <th>Batch</th>
                            <th>Price</th>
                            <th>Purchase Price</th>
                            <th>Qty</th>
                            <th>Discount</th>
                            <th>Exp Date</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($return->items as $item)
                        <tr>
                            <td>{{ $item->item->id }}</td>
                            <td>{{ $item->item->item_name }}</td>
                            <td>{{ $item->batch_number ?? '-' }}</td>
                            <td>{{ number_format($item->price, 2) }}</td>
                            <td>{{ number_format($item->purchase_price, 2) }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ $item->discount }}%</td>
                            <td>{{ $item->exp_date ? $item->exp_date->format('d M Y') : '-' }}</td>
                            <td>{{ number_format($item->total, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="8" class="text-end">Total Return:</th>
                            <th>{{ number_format($return->total_return, 2) }}</th>
                        </tr>
                        <tr>
                            <th colspan="8" class="text-end">Net Payable:</th>
                            <th>{{ number_format($return->net_payable, 2) }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="d-flex justify-content-between mt-3">
                <a href="{{ route('purchase-returns.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Back to List
                </a>
                <button class="btn btn-primary" onclick="window.print()">
                    <i class="fas fa-print me-1"></i> Print
                </button>
            </div>
        </div>
    </div>
</div>
@endsection