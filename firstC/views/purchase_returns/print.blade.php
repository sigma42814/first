@extends('layouts.app')

@section('title', 'Print Purchase Return #' . $purchaseReturn->return_number)

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Purchase Return #{{ $purchaseReturn->return_number }}</h4>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <h5>Company Information</h5>
                    <p><strong>Name:</strong> {{ $purchaseReturn->company->name ?? 'N/A' }}</p>
                    <p><strong>Address:</strong> {{ $purchaseReturn->company->address ?? 'N/A' }}</p>
                </div>
                <div class="col-md-6 text-end">
                    <h5>Return Details</h5>
                    <p><strong>Date:</strong> {{ $purchaseReturn->return_date->format('d/m/Y') }}</p>
                    <p><strong>Reason:</strong> {{ $purchaseReturn->reason ?? 'N/A' }}</p>
                </div>
            </div>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Item Code</th>
                        <th>Item Name</th>
                        <th>Batch</th>
                        <th>Price</th>
                        <th>Qty</th>
                        <th>Discount</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($purchaseReturn->items as $item)
                    <tr>
                        <td>{{ $item->item->id ?? 'N/A' }}</td>
                        <td>{{ $item->item->item_name ?? 'N/A' }}</td>
                        <td>{{ $item->batch_number ?? 'N/A' }}</td>
                        <td>{{ number_format($item->price, 2) }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ $item->discount }}%</td>
                        <td>{{ number_format($item->total, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="6" class="text-end">Total Return:</th>
                        <th>{{ number_format($purchaseReturn->total_return, 2) }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="card-footer text-muted text-end">
            Printed on: {{ now()->format('d/m/Y H:i') }}
        </div>
    </div>
</div>

<script>
    // Auto-print when page loads
    window.onload = function() {
        setTimeout(function() {
            window.print();
        }, 500);
    };
</script>

<style>
    @media print {
        body * {
            visibility: hidden;
        }
        .card, .card * {
            visibility: visible;
        }
        .card {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            border: none;
        }
        .no-print {
            display: none !important;
        }
    }
</style>
@endsection