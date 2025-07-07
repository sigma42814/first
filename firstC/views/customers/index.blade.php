@extends('layouts.app')

@section('title', 'Customer List')

@section('content')
<div class="container-fluid px-2">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white py-2">
            <h5 class="mb-0">
                <i class="fas fa-users me-2"></i> Customer List
            </h5>
        </div>
        <div class="card-body p-3">
            <div class="d-flex justify-content-between mb-4">
                <!-- Add New Customer Button -->
                <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addCustomerModal">
                    <i class="fas fa-plus me-1"></i> Add New Customer
                </button>
                <!-- Search Bar -->
                <div class="w-50">
                    <input type="text" class="form-control" id="searchCustomer" placeholder="Search customers...">
                </div>
            </div>
            <!-- Customer List Table -->
            <div class="table-responsive" style="max-height: 65vh;">
                <table class="table table-sm table-hover mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Address</th>
                            <th>Company</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="customerTableBody" class="small">
                        @foreach ($customers as $customer)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $customer->name }}</td>
                            <td>{{ $customer->email }}</td>
                            <td>{{ $customer->phone }}</td>
                            <td>{{ $customer->address }}</td>
                            <td>{{ $customer->company }}</td>
                            <td>
                                <!-- Edit Button -->
                                <a href="#" 
                                   class="btn btn-sm btn-primary edit-customer-btn" 
                                   data-customer='{{ $customer->toJson() }}' 
                                   data-bs-toggle="modal" 
                                   data-bs-target="#editCustomerModal{{ $customer->id }}">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <!-- Delete Button -->
                                <form action="{{ route('customers.destroy', $customer->id) }}" method="POST" style="display: inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Customer Modal -->
<div class="modal fade" id="addCustomerModal" tabindex="-1" aria-labelledby="addCustomerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white py-2">
                <h5 class="modal-title fs-6" id="addCustomerModalLabel">
                    <i class="fas fa-user-plus me-2"></i> Add New Customer
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3">
                @include('customers.create')
            </div>
        </div>
    </div>
</div>

<!-- Edit Customer Modals -->
@foreach ($customers as $customer)
<div class="modal fade" id="editCustomerModal{{ $customer->id }}" tabindex="-1" aria-labelledby="editCustomerModalLabel{{ $customer->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white py-2">
                <h5 class="modal-title fs-6" id="editCustomerModalLabel{{ $customer->id }}">
                    <i class="fas fa-user-edit me-2"></i> Edit Customer
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3">
                @include('customers.edit', ['customer' => $customer])
            </div>
        </div>
    </div>
</div>
@endforeach

<!-- JavaScript for Live Search and Modal Population -->
<script>
    // Live Search Functionality
    document.getElementById('searchCustomer').addEventListener('input', function () {
        const searchTerm = this.value.toLowerCase();
        const rows = document.querySelectorAll('#customerTableBody tr');

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    });

    // Populate Edit Modal with JavaScript (Optional)
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.edit-customer-btn').forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();
                const customer = JSON.parse(this.getAttribute('data-customer'));

                // Populate fields (if needed)
                document.getElementById('edit_name').value = customer.name || '';
                document.getElementById('edit_email').value = customer.email || '';
                document.getElementById('edit_phone').value = customer.phone || '';
                document.getElementById('edit_address').value = customer.address || '';
                document.getElementById('edit_company').value = customer.company || '';
                document.getElementById('edit_credit_limit').value = customer.credit_limit || '';
                document.getElementById('edit_area').value = customer.area || '';
                document.getElementById('edit_brick').value = customer.brick || '';
                document.getElementById('edit_salesman').value = customer.salesman || '';

                // Handle checkboxes
                document.getElementById('edit_usd').checked = !!customer.usd;
                document.getElementById('edit_afn').checked = !!customer.afn;
                document.getElementById('edit_pkr').checked = !!customer.pkr;

                // Update form action URL
                const editForm = document.getElementById('editCustomerForm');
                editForm.action = `/customers/${customer.id}`;
            });
        });
    });

    

</script>
@endsection