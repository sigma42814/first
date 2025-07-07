@extends('layouts.app')

@section('title', 'View Sales Return')

@section('content')
<div class="container-fluid px-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white py-2">
            <h5 class="mb-0">
                <i class="fas fa-undo me-2"></i> Sales Return Details
            </h5>
        </div>
        <div class="card-body p-3">
            <div class="row mb-4">
                <div class="col-md-6">
                    <p><strong>Return Number:</strong> {{ $salesReturn->return_number }}</p>
                    <p><strong>Date:</strong> {{ $salesReturn->return_date->format('Y-m-d') }}</p>
                    <p><strong>Customer:</strong> {{ $salesReturn->customer->name }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Username:</strong> {{ $salesReturn->username }}</p>
                    <p><strong>Reason:</strong> {{ $salesReturn->reason ?? 'N/A' }}</p>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-sm">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Batch Number</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Discount 1</th>
                            <th>Discount 2</th>
                            <th>Bonus</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($salesReturn->items as $item)
                            <tr>
                                <td>{{ $item->item->item_name }}</td>
                                <td>{{ $item->batch_number ?? 'N/A' }}</td>
                                <td>{{ number_format($item->price, 2) }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ $item->discount }}%</td>
                                <td>{{ $item->discount2 }}%</td>
                                <td>{{ $item->bonus }}</td>
                                <td>{{ number_format($item->total, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="7" class="text-end"><strong>Total Return:</strong></td>
                            <td>{{ number_format($salesReturn->total_return, 2) }}</td>
                        </tr>
                        <tr>
                            <td colspan="7" class="text-end"><strong>Net Payable:</strong></td>
                            <td>{{ number_format($salesReturn->net_payable, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="mt-3">
                <a href="{{ route('sales-returns.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
                <a href="{{ route('sales-returns.print', $salesReturn->id) }}" class="btn btn-success" target="_blank">
                    <i class="fas fa-print"></i> Print
                </a>
            </div>
        </div>
    </div>
</div>
@endsection