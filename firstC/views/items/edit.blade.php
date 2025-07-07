<form id="editItemForm" action="{{ route('items.update', $item->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <!-- Item Code and Item Name in One Line -->
    <div class="row">
        <div class="col-md-6 mb-2">
            <label for="item_code" class="form-label">
                <i class="fas fa-barcode me-1"></i> Item Code
            </label>
            <input type="text" class="form-control form-control-sm" id="item_code" name="item_code" value="{{ $item->item_code }}" required>
            @error('item_code')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-6 mb-2">
            <label for="item_name" class="form-label">
                <i class="fas fa-tag me-1"></i> Item Name
            </label>
            <input type="text" class="form-control form-control-sm" id="item_name" name="item_name" value="{{ $item->item_name }}" required>
            @error('item_name')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <!-- Purchase Price and MRP -->
    <div class="row">
        <div class="col-md-6 mb-2">
            <label for="item_purchase_price" class="form-label">
                <i class="fas fa-dollar-sign me-1"></i> Purchase Price
            </label>
            <input type="number" step="0.01" class="form-control form-control-sm" id="item_purchase_price" name="item_purchase_price" value="{{ $item->item_purchase_price }}" required>
            @error('item_purchase_price')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-6 mb-2">
            <label for="mrp" class="form-label">
                <i class="fas fa-tags me-1"></i> MRP
            </label>
            <input type="number" step="0.01" class="form-control form-control-sm" id="mrp" name="mrp" value="{{ $item->mrp }}" required>
            @error('mrp')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <!-- Trade Price and Low Quantity Alert -->
    <div class="row">
        <div class="col-md-6 mb-2">
            <label for="tp" class="form-label">
                <i class="fas fa-handshake me-1"></i> Trade Price
            </label>
            <input type="number" step="0.01" class="form-control form-control-sm" id="tp" name="tp" value="{{ $item->tp }}" required>
            @error('tp')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-6 mb-2">
            <label for="low_quantity" class="form-label">
                <i class="fas fa-exclamation-triangle me-1"></i> Low Quantity Alert
            </label>
            <input type="number" class="form-control form-control-sm" id="low_quantity" name="low_quantity" value="{{ $item->low_quantity }}" required>
            @error('low_quantity')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <!-- Status and Company -->
    <div class="row">
        <div class="col-md-6 mb-2">
            <label for="status" class="form-label">
                <i class="fas fa-toggle-on me-1"></i> Status
            </label>
            <select class="form-control form-control-sm" id="status" name="status">
                <option value="1" {{ $item->status ? 'selected' : '' }}>Enabled</option>
                <option value="0" {{ !$item->status ? 'selected' : '' }}>Disabled</option>
            </select>
            @error('status')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-6 mb-2">
            <label for="company" class="form-label">
                <i class="fas fa-building me-1"></i> Company
            </label>
            <input type="text" class="form-control form-control-sm" id="company" name="company" value="{{ $item->company }}">
            @error('company')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <!-- Product Photo -->
    <div class="mb-2">
        <label for="product_photo" class="form-label">
            <i class="fas fa-camera me-1"></i> Product Photo
        </label>
        <input type="file" class="form-control form-control-sm" id="product_photo" name="product_photo" onchange="previewImage(event)">
        <img id="photo_preview" src="{{ $item->product_photo ? asset('storage/' . $item->product_photo) : '#' }}" alt="Product Photo" class="img-thumbnail mt-2" style="max-width: 100px; display: {{ $item->product_photo ? 'block' : 'none' }};">
        @error('product_photo')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <!-- Buttons -->
    <div class="d-flex justify-content-end mt-3">
        <button type="button" class="btn btn-secondary btn-sm me-2" data-bs-dismiss="modal">
            <i class="fas fa-times me-1"></i> Close
        </button>
        <button type="submit" class="btn btn-primary btn-sm">
            <i class="fas fa-save me-1"></i> Update
        </button>
    </div>
</form>

<script>
    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function () {
            const output = document.getElementById('photo_preview');
            output.src = reader.result;
            output.style.display = 'block';
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>