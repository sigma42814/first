@extends('layouts.app')

@section('title', 'Batch Report')

@section('content')
<div class="container-fluid px-4">
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white py-2">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-layer-group me-2"></i>Batch Report
                </h5>
                <div>
                    <a href="{{ route('inventory.index') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-arrow-left me-1"></i> Back
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Batch Number</th>
                            <th>Item</th>
                            <th>Remaining Quantity</th>
                            <th>Unit</th>
                            <th>Expiry Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($batches as $batch)
                        <tr>
                            <td>{{ $batch->batch_number }}</td>
                            <td>{{ $batch->item->item_name }}</td>
                            <td>{{ number_format($batch->remaining_quantity, 2) }}</td>
                            <td>{{ $batch->item->unit }}</td>
                            <td>
                                @if($batch->expiry_date)
                                    {{ $batch->expiry_date->format('Y-m-d') }}
                                    ({{ now()->diffInDays($batch->expiry_date) }} days)
                                @else
                                    N/A
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">No batches found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $batches->links() }}
            </div>
        </div>
    </div>
</div>
@endsection