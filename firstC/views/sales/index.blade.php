@extends('layouts.app')

@section('title', 'Sales List')

@section('content')
<div class="container-fluid px-2">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white py-2">
            <h5 class="mb-0">
                <i class="fas fa-shopping-cart me-2"></i> Sales List
            </h5>
        </div>
        <div class="card-body p-3">
            <div class="d-flex justify-content-between mb-4">
                <!-- Add New Sale Button -->
                <a href="{{ route('sales.create') }}" class="btn btn-success btn-sm">
                    <i class="fas fa-plus me-1"></i> New Sale
                </a>
                <!-- Search Bar -->
                <div class="w-50">
                    <input type="text" class="form-control" id="searchSale" placeholder="Search by invoice or customer...">
                </div>
            </div>
            <!-- Sales List Table -->
            <div class="table-responsive" style="max-height: 65vh;">
                <table class="table table-sm table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Invoice #</th>
                            <th>Date</th>
                            <th>Updated</th>
                            <th>Customer</th>
                            <th class="text-start">Total</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="saleTableBody" class="small">
                        @foreach ($sales as $sale)
                        <tr>
                            <td>
                                <a href="javascript:void(0)" 
                                   onclick="showSaleDetails({{ $sale->id }})" 
                                   class="text-primary">
                                    {{ $sale->invoice_number }}
                                </a>
                            </td>
                            <td>{{ $sale->invoice_date ? $sale->invoice_date->format('d/m/Y') : 'N/A' }}</td>
                            <td>{{ $sale->updated_at->format('d/m/Y H:i') }}</td>
                            <td>{{ $sale->customer->name ?? 'N/A' }}</td>
                            <td class="text-start">{{ number_format($sale->total_sales, 2) }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('sales.edit', $sale->id) }}" class="btn btn-primary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('sales.destroy', $sale->id) }}" method="POST" class="d-inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    <a href="{{ route('sales.print', $sale->id) }}" class="btn btn-secondary" title="Print">
                                        <i class="fas fa-print"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Sale Details -->
<div class="modal fade" id="saleDetailsModal" tabindex="-1" aria-labelledby="saleDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="saleDetailsModalLabel">Sale Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="saleDetailsContent">
                <!-- Content will be loaded here via AJAX -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- SweetAlert CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Bootstrap JS Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Live Search Functionality
    document.getElementById('searchSale').addEventListener('input', function () {
        const searchTerm = this.value.toLowerCase();
        const rows = document.querySelectorAll('#saleTableBody tr');

        rows.forEach(row => {
            const invoiceNum = row.querySelector('td:nth-child(1) a').textContent.toLowerCase();
            const customerName = row.querySelector('td:nth-child(4)').textContent.toLowerCase();
            row.style.display = (invoiceNum.includes(searchTerm) || customerName.includes(searchTerm)) ? '' : 'none';
        });
    });

    // SweetAlert for Delete Confirmation
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });
    });

    // Show sale details in modal
    function showSaleDetails(saleId) {
        fetch(`/sales/${saleId}/details`)
            .then(response => response.text())
            .then(html => {
                document.getElementById('saleDetailsContent').innerHTML = html;
                const modal = new bootstrap.Modal(document.getElementById('saleDetailsModal'));
                modal.show();
            })
            .catch(error => {
                console.error('Error loading sale details:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to load sale details'
                });
            });
    }

    // Show success message if exists
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '{{ session('success') }}',
            timer: 3000,
            showConfirmButton: false
        });
    @endif

    // Show error message if exists
    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: '{{ session('error') }}'
        });
    @endif
</script>
@endsection