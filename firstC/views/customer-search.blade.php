<!-- resources/views/customer-search.blade.php -->
@extends('layouts.app')

@section('title', 'Customer Search')

@section('content')
<div class="container-fluid px-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white py-2">
            <h5 class="mb-0">
                <i class="fas fa-search me-2"></i> Customer Search
            </h5>
        </div>
        <div class="card-body p-3">
            <!-- Search Input -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <input type="text" id="searchQuery" class="form-control form-control-sm" placeholder="Search customer..." value="{{ $query }}">
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary btn-sm" onclick="performSearch()">Search</button>
                </div>
            </div>

            <!-- Search Results -->
            <table class="table table-bordered table-sm">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Address</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($customers as $customer)
                        <tr>
                            <td>{{ $customer->name }}</td>
                            <td>{{ $customer->address }}</td>
                            <td>
                                <button class="btn btn-success btn-sm" onclick="selectCustomer('{{ $customer->id }}', '{{ $customer->name }}', '{{ $customer->address }}')">Select</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    // Perform search when the search button is clicked
    function performSearch() {
        const query = document.getElementById('searchQuery').value.trim();
        if (query) {
            window.location.href = `/customer-search?query=${query}`;
        }
    }

    // Select a customer and return to the sales/create page
    function selectCustomer(id, name, address) {
        // Store the selected customer in localStorage
        localStorage.setItem('selectedCustomer', JSON.stringify({ id, name, address }));

        // Redirect back to the sales/create page
        window.location.href = "{{ route('sales.create') }}";
    }
</script>
@endsection