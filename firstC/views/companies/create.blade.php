<form id="addCompanyForm" action="{{ route('companies.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <!-- Code and Name -->
    <div class="row">
        <div class="col-md-6 mb-2">
            <label for="code" class="form-label">
                <i class="fas fa-id-card me-1"></i> Code
            </label>
            <input type="text" class="form-control form-control-sm" id="code" name="code" required>
            @error('code')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-6 mb-2">
            <label for="name" class="form-label">
                <i class="fas fa-building me-1"></i> Name
            </label>
            <input type="text" class="form-control form-control-sm" id="name" name="name" required>
            @error('name')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <!-- Address -->
    <div class="mb-2">
        <label for="address" class="form-label">
            <i class="fas fa-map-marker-alt me-1"></i> Address
        </label>
        <textarea class="form-control form-control-sm" id="address" name="address"></textarea>
        @error('address')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <!-- Phone and Email -->
    <div class="row">
        <div class="col-md-6 mb-2">
            <label for="phone" class="form-label">
                <i class="fas fa-phone me-1"></i> Phone
            </label>
            <input type="text" class="form-control form-control-sm" id="phone" name="phone">
            @error('phone')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-6 mb-2">
            <label for="email" class="form-label">
                <i class="fas fa-envelope me-1"></i> Email
            </label>
            <input type="email" class="form-control form-control-sm" id="email" name="email">
            @error('email')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <!-- Website -->
    <div class="mb-2">
        <label for="website" class="form-label">
            <i class="fas fa-globe me-1"></i> Website
        </label>
        <input type="url" class="form-control form-control-sm" id="website" name="website">
        @error('website')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <!-- Logo -->
    <div class="mb-2">
        <label for="logo" class="form-label">
            <i class="fas fa-image me-1"></i> Logo
        </label>
        <input type="file" class="form-control form-control-sm" id="logo" name="logo" onchange="previewImage(event)">
        <img id="logo_preview" src="#" alt="Logo Preview" class="img-thumbnail mt-2" style="max-width: 100px; display: none;">
        @error('logo')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <!-- Buttons -->
    <div class="d-flex justify-content-end mt-3">
        <button type="button" class="btn btn-secondary btn-sm me-2" data-bs-dismiss="modal">
            <i class="fas fa-times me-1"></i> Close
        </button>
        <button type="submit" class="btn btn-primary btn-sm">
            <i class="fas fa-save me-1"></i> Save
        </button>
    </div>
</form>

<script>
    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function () {
            const output = document.getElementById('logo_preview');
            output.src = reader.result;
            output.style.display = 'block';
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>