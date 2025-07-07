@extends('layouts.app')

@section('title', 'Item List')

@section('content')
<div class="container-fluid px-2">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white py-2">
            <h5 class="mb-0">
                <i class="fas fa-cube me-2"></i> Item List
            </h5>
        </div>
        <div class="card-body p-3">
            <div class="d-flex justify-content-between mb-4">
                <!-- Add New Item Button -->
                <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addItemModal">
                    <i class="fas fa-plus me-1"></i> Add New Item
                </button>
                <!-- Search Bar -->
                <div class="w-50">
                    <input type="text" class="form-control" id="searchItem" placeholder="Search items...">
                </div>
            </div>
            <!-- Item List Table -->
            <div class="table-responsive" style="max-height: 65vh;">
                <table class="table table-sm table-hover mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Item Code</th>
                            <th>Item Name</th>
                            <th>MRP</th>
                            <th>Sale Price</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="itemTableBody" class="small">
                        @foreach ($items as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->item_code }}</td>
                            <td>{{ $item->item_name }}</td>
                            <td>{{ $item->mrp }}</td>
                            <td>{{ $item->tp }}</td>
                            <td>
                                @if ($item->status)
                                    <span class="badge bg-success">Enabled</span>
                                @else
                                    <span class="badge bg-danger">Disabled</span>
                                @endif
                            </td>
                            <td>
                                <!-- Edit Button -->
                                <a href="#" 
                                   class="btn btn-sm btn-primary edit-item-btn" 
                                   data-item='{{ $item->toJson() }}' 
                                   data-bs-toggle="modal" 
                                   data-bs-target="#editItemModal{{ $item->id }}">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <!-- Delete Button -->
                                <form action="{{ route('items.destroy', $item->id) }}" method="POST" style="display: inline-block;">
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

<!-- Add Item Modal -->
<div class="modal fade" id="addItemModal" tabindex="-1" aria-labelledby="addItemModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white py-2">
                <h5 class="modal-title fs-6" id="addItemModalLabel">
                    <i class="fas fa-cube me-2"></i> Add New Item
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3">
                @include('items.create')
            </div>
        </div>
    </div>
</div>

<!-- Edit Item Modals -->
@foreach ($items as $item)
<div class="modal fade" id="editItemModal{{ $item->id }}" tabindex="-1" aria-labelledby="editItemModalLabel{{ $item->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white py-2">
                <h5 class="modal-title fs-6" id="editItemModalLabel{{ $item->id }}">
                    <i class="fas fa-cube me-2"></i> Edit Item
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3">
                @include('items.edit', ['item' => $item])
            </div>
        </div>
    </div>
</div>
@endforeach

@endsection

@section('scripts')
<script>
    // Live Search Functionality
    document.getElementById('searchItem').addEventListener('input', function () {
        const searchTerm = this.value.toLowerCase();
        const rows = document.querySelectorAll('#itemTableBody tr');

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    });
</script>
@endsection