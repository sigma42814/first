<form id="editEmployeeForm" action="{{ route('employees.update', $employee->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <!-- Code and Name -->
    <div class="row">
        <div class="col-md-6 mb-2">
            <label for="code" class="form-label">
                <i class="fas fa-id-card me-1"></i> Code
            </label>
            <input type="text" class="form-control form-control-sm" id="code" name="code" value="{{ $employee->code }}" required>
            @error('code')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-6 mb-2">
            <label for="name" class="form-label">
                <i class="fas fa-user me-1"></i> Name
            </label>
            <input type="text" class="form-control form-control-sm" id="name" name="name" value="{{ $employee->name }}" required>
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
        <textarea class="form-control form-control-sm" id="address" name="address">{{ $employee->address }}</textarea>
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
            <input type="text" class="form-control form-control-sm" id="phone" name="phone" value="{{ $employee->phone }}">
            @error('phone')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-6 mb-2">
            <label for="email" class="form-label">
                <i class="fas fa-envelope me-1"></i> Email
            </label>
            <input type="email" class="form-control form-control-sm" id="email" name="email" value="{{ $employee->email }}">
            @error('email')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <!-- Photo -->
    <div class="mb-2">
        <label for="photo" class="form-label">
            <i class="fas fa-camera me-1"></i> Photo
        </label>
        <input type="file" class="form-control form-control-sm" id="photo" name="photo" onchange="previewImage(event)">
        <img id="photo_preview" src="{{ $employee->photo ? asset('storage/' . $employee->photo) : '#' }}" alt="Employee Photo" class="img-thumbnail mt-2" style="max-width: 100px; display: {{ $employee->photo ? 'block' : 'none' }};">
        @error('photo')
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