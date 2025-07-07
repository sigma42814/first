@extends('layouts.app')

@section('title', 'Employee List')

@section('content')
<div class="container-fluid px-2">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white py-2">
            <h5 class="mb-0">
                <i class="fas fa-users me-2"></i> Employee List
            </h5>
        </div>
        <div class="card-body p-3">
            <div class="d-flex justify-content-between mb-4">
                <!-- Add New Employee Button -->
                <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addEmployeeModal">
                    <i class="fas fa-plus me-1"></i> Add New Employee
                </button>
                <!-- Search Bar -->
                <div class="w-50">
                    <input type="text" class="form-control" id="searchEmployee" placeholder="Search employees...">
                </div>
            </div>
            <!-- Employee List Table -->
            <div class="table-responsive" style="max-height: 65vh;">
                <table class="table table-sm table-hover mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Code</th>
                            <th>Name</th>
                            <th>Address</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Photo</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="employeeTableBody" class="small">
                        @foreach ($employees as $employee)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $employee->code }}</td>
                            <td>{{ $employee->name }}</td>
                            <td>{{ $employee->address }}</td>
                            <td>{{ $employee->phone }}</td>
                            <td>{{ $employee->email }}</td>
                            <td>
                                @if ($employee->photo)
                                    <img src="{{ asset('storage/' . $employee->photo) }}" alt="Employee Photo" class="img-thumbnail" style="max-width: 50px;">
                                @else
                                    <span class="text-muted">No Photo</span>
                                @endif
                            </td>
                            <td>
                                <!-- Edit Button -->
                                <a href="#" 
                                   class="btn btn-sm btn-primary edit-employee-btn" 
                                   data-employee='{{ $employee->toJson() }}' 
                                   data-bs-toggle="modal" 
                                   data-bs-target="#editEmployeeModal{{ $employee->id }}">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <!-- Delete Button -->
                                <form action="{{ route('employees.destroy', $employee->id) }}" method="POST" style="display: inline-block;">
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

<!-- Add Employee Modal -->
<div class="modal fade" id="addEmployeeModal" tabindex="-1" aria-labelledby="addEmployeeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white py-2">
                <h5 class="modal-title fs-6" id="addEmployeeModalLabel">
                    <i class="fas fa-user-plus me-2"></i> Add New Employee
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3">
                @include('employees.create')
            </div>
        </div>
    </div>
</div>

<!-- Edit Employee Modals -->
@foreach ($employees as $employee)
<div class="modal fade" id="editEmployeeModal{{ $employee->id }}" tabindex="-1" aria-labelledby="editEmployeeModalLabel{{ $employee->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white py-2">
                <h5 class="modal-title fs-6" id="editEmployeeModalLabel{{ $employee->id }}">
                    <i class="fas fa-user-edit me-2"></i> Edit Employee
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3">
                @include('employees.edit', ['employee' => $employee])
            </div>
        </div>
    </div>
</div>
@endforeach

@endsection

@section('scripts')
<script>
    console.log('Live search script loaded'); // Debugging: Check if script is loaded

    // Live Search Functionality
    const searchInput = document.getElementById('searchEmployee');
    const tableBody = document.getElementById('employeeTableBody');

    if (searchInput && tableBody) {
        searchInput.addEventListener('input', function () {
            const searchTerm = this.value.toLowerCase();
            const rows = tableBody.querySelectorAll('tr');

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });
    } else {
        console.error('Search input or table body not found'); // Debugging: Check if elements exist
    }
</script>
@endsection