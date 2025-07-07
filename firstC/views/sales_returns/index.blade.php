@extends('layouts.app')

@section('title', 'Sales Returns List')

@section('content')
<div class="container-fluid px-2">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white py-2">
            <h5 class="mb-0">
                <i class="fas fa-undo me-2"></i> Sales Returns List
            </h5>
        </div>
        <div class="card-body p-3">
            <div class="d-flex justify-content-between mb-4">
                <!-- Add New Return Button -->
                <a href="{{ route('sales-returns.create') }}" class="btn btn-success btn-sm">
                    <i class="fas fa-plus me-1"></i> New Return
                </a>
                <!-- Search Bar -->
                <div class="w-50">
                    <input type="text" class="form-control" id="searchReturn" placeholder="Search by return number or customer...">
                </div>
            </div>
            <!-- Returns List Table -->
            <div class="table-responsive" style="max-height: 65vh;">
                <table class="table table-sm table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Return #</th>
                            <th>Date</th>
                            <th>Updated</th>
                            <th>Customer</th>
                            <th class="text-start">Total</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="returnTableBody" class="small">
                        @foreach ($returns as $return)
                        <tr>
                            <td>
                                <a href="javascript:void(0)" 
                                   onclick="showReturnDetails({{ $return->id }})" 
                                   class="text-primary">
                                    {{ $return->return_number }}
                                </a>
                            </td>
                            <td>{{ $return->return_date ? $return->return_date->format('d/m/Y') : 'N/A' }}</td>
                            <td>{{ $return->updated_at->format('d/m/Y H:i') }}</td>
                            <td>{{ $return->customer->name ?? 'N/A' }}</td>
                            <td class="text-start">{{ number_format($return->total_return, 2) }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('sales-returns.edit', $return->id) }}" class="btn btn-primary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('sales-returns.destroy', $return->id) }}" method="POST" class="d-inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    <a href="{{ route('sales-returns.print', $return->id) }}" class="btn btn-secondary" title="Print" target="_blank">
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

<!-- Modal for Return Details -->
<div class="modal fade" id="returnDetailsModal" tabindex="-1" aria-labelledby="returnDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="returnDetailsModalLabel">Return Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="returnDetailsContent">
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
    document.getElementById('searchReturn').addEventListener('input', function () {
        const searchTerm = this.value.toLowerCase();
        const rows = document.querySelectorAll('#returnTableBody tr');

        rows.forEach(row => {
            const returnNum = row.querySelector('td:nth-child(1) a').textContent.toLowerCase();
            const customerName = row.querySelector('td:nth-child(4)').textContent.toLowerCase();
            row.style.display = (returnNum.includes(searchTerm) || customerName.includes(searchTerm)) ? '' : 'none';
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

    // Show return details in modal
    function showReturnDetails(returnId) {
        fetch(`/sales-returns/${returnId}/details`)
            .then(response => response.text())
            .then(html => {
                document.getElementById('returnDetailsContent').innerHTML = html;
                const modal = new bootstrap.Modal(document.getElementById('returnDetailsModal'));
                modal.show();
            })
            .catch(error => {
                console.error('Error loading return details:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to load return details'
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