@extends('layouts.app')

@section('title', 'Purchases List')

@section('content')
<div class="container-fluid px-2">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white py-2">
            <h5 class="mb-0">
                <i class="fas fa-shopping-bag me-2"></i> Purchases List
            </h5>
        </div>
        <div class="card-body p-3">
            <div class="d-flex justify-content-between mb-4">
                <!-- Add New Purchase Button -->
                <a href="{{ route('purchases.create') }}" class="btn btn-success btn-sm">
                    <i class="fas fa-plus me-1"></i> New Purchase
                </a>
                <!-- Search Bar -->
                <div class="w-50">
                    <input type="text" class="form-control" id="searchPurchase" placeholder="Search by invoice or company...">
                </div>
            </div>
            <!-- Purchases List Table -->
            <div class="table-responsive" style="max-height: 65vh;">
                <table class="table table-sm table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Invoice #</th>
                            <th>Date</th>
                            <th>Updated</th>
                            <th>Company</th>
                            <th class="text-start">Total</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="purchaseTableBody" class="small">
                        @foreach ($purchases as $purchase)
                        <tr>
                            <td>
                                <a href="javascript:void(0)" 
                                   onclick="showPurchaseDetails({{ $purchase->id }})" 
                                   class="text-primary">
                                    {{ $purchase->invoice_number }}
                                </a>
                            </td>
                            <td>{{ $purchase->invoice_date ? \Carbon\Carbon::parse($purchase->invoice_date)->format('d/m/Y') : 'N/A' }}</td>
                            <td>{{ \Carbon\Carbon::parse($purchase->updated_at)->format('d/m/Y H:i') }}</td>
                            <td>{{ $purchase->company->name ?? 'N/A' }}</td>
                            <td class="text-start">{{ number_format($purchase->total_purchases, 2) }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('purchases.edit', $purchase->id) }}" class="btn btn-primary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('purchases.destroy', $purchase->id) }}" method="POST" class="d-inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    <a href="{{ route('purchases.print', $purchase->id) }}" class="btn btn-secondary" title="Print">
                                        <i class="fas fa-print"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-3">
                {{ $purchases->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Modal for Purchase Details -->
<div class="modal fade" id="purchaseDetailsModal" tabindex="-1" aria-labelledby="purchaseDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="purchaseDetailsModalLabel">Purchase Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="purchaseDetailsContent">
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
    document.getElementById('searchPurchase').addEventListener('input', function () {
        const searchTerm = this.value.toLowerCase();
        const rows = document.querySelectorAll('#purchaseTableBody tr');

        rows.forEach(row => {
            const invoiceNum = row.querySelector('td:nth-child(1) a').textContent.toLowerCase();
            const companyName = row.querySelector('td:nth-child(4)').textContent.toLowerCase();
            row.style.display = (invoiceNum.includes(searchTerm) || companyName.includes(searchTerm)) ? '' : 'none';
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

    // Show purchase details in modal
    function showPurchaseDetails(purchaseId) {
        fetch(`/purchases/${purchaseId}/details`)
            .then(response => response.text())
            .then(html => {
                document.getElementById('purchaseDetailsContent').innerHTML = html;
                const modal = new bootstrap.Modal(document.getElementById('purchaseDetailsModal'));
                modal.show();
            })
            .catch(error => {
                console.error('Error loading purchase details:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to load purchase details'
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