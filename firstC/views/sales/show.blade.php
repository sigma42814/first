@extends('layouts.app')

@section('title', 'View Sale')

@section('content')
<div class="container-fluid px-2">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white py-2">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-receipt me-2"></i> Sale Details - {{ $sale->invoice_number }}
                </h5>
                <div>
                    <a href="{{ route('sales.print', $sale->id) }}" class="btn btn-sm btn-light me-1">
                        <i class="fas fa-print"></i> Print
                    </a>
                    <a href="{{ route('sales.edit', $sale->id) }}" class="btn btn-sm btn-warning">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <h6>Customer Information</h6>
                    <table class="table table-sm table-borderless">
                        <tr>
                            <th width="30%">Name:</th>
                            <td>{{ $sale->customer->name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Address:</th>
                            <td>{{ $sale->customer->address ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Phone:</th>
                            <td>{{ $sale->customer->phone ?? 'N/A' }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h6>Sale Information</h6>
                    <table class="table table-sm table-borderless">
                        <tr>
                            <th width="30%">Date:</th>
                            <td>{{ $sale->invoice_date->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <th>Invoice #:</th>
                            <td>{{ $sale->invoice_number }}</td>
                        </tr>
                        <tr>
                            <th>Employee:</th>
                            <td>{{ $sale->username }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-sm table-bordered">
                    <thead class="bg-light">
                        <tr>
                            <th>#</th>
                            <th>Item Code</th>
                            <th>Item Name</th>
                            <th class="text-end">Price</th>
                            <th class="text-end">Qty</th>
                            <th class="text-end">Discount</th>
                            <th class="text-end">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sale->items as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->item_code }}</td>
                            <td>{{ $item->item_name }}</td>
                            <td class="text-end">{{ number_format($item->price, 2) }}</td>
                            <td class="text-end">{{ $item->quantity }}</td>
                            <td class="text-end">{{ number_format($item->discount, 2) }}</td>
                            <td class="text-end">{{ number_format($item->total, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-light">
                        <tr>
                            <th colspan="6" class="text-end">Subtotal:</th>
                            <th class="text-end">{{ number_format($sale->total_sales, 2) }}</th>
                        </tr>
                        <tr>
                            <th colspan="6" class="text-end">Previous Balance:</th>
                            <th class="text-end">{{ number_format($sale->prev_balance, 2) }}</th>
                        </tr>
                        <tr>
                            <th colspan="6" class="text-end">Total Balance:</th>
                            <th class="text-end">{{ number_format($sale->total_balance, 2) }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: '{{ session('success') }}',
        timer: 3000,
        showConfirmButton: false
    });
</script>
@endif
@endsection