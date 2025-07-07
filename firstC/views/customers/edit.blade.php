<form id="editCustomerForm" action="{{ route('customers.update', $customer->id) }}" method="POST" class="compact-form">
    @csrf
    @method('PUT')
    <div class="row g-2">
        <!-- Left Column -->
        <div class="col-md-6">
            <!-- Name -->
            <div class="mb-2">
                <label for="edit_name" class="form-label">
                    <i class="fas fa-user me-1"></i> Name
                </label>
                <input type="text" class="form-control" id="edit_name" name="name" value="{{ $customer->name }}" required>
            </div>

            <!-- Company -->
            <div class="mb-2">
                <label for="edit_company" class="form-label">
                    <i class="fas fa-building me-1"></i> Company
                </label>
                <input type="text" class="form-control" id="edit_company" name="company" value="{{ $customer->company }}">
            </div>

            <!-- Email -->
            <div class="mb-2">
                <label for="edit_email" class="form-label">
                    <i class="fas fa-envelope me-1"></i> Email
                </label>
                <input type="email" class="form-control" id="edit_email" name="email" value="{{ $customer->email }}">
            </div>

            <!-- Phone -->
            <div class="mb-2">
                <label for="edit_phone" class="form-label">
                    <i class="fas fa-phone me-1"></i> Phone
                </label>
                <input type="text" class="form-control" id="edit_phone" name="phone" value="{{ $customer->phone }}">
            </div>
        </div>

        <!-- Right Column -->
        <div class="col-md-6">
            <!-- Address -->
            <div class="mb-2">
                <label for="edit_address" class="form-label">
                    <i class="fas fa-map-marker-alt me-1"></i> Address
                </label>
                <input type="text" class="form-control" id="edit_address" name="address" value="{{ $customer->address }}">
            </div>

            <!-- Credit Limit -->
            <div class="mb-2">
                <label for="edit_credit_limit" class="form-label">
                    <i class="fas fa-credit-card me-1"></i> Credit Limit
                </label>
                <input type="number" step="0.01" class="form-control" id="edit_credit_limit" name="credit_limit" value="{{ $customer->credit_limit }}">
            </div>

            <!-- Area/Brick/Salesman -->
            <div class="row g-2">
                <div class="col-4">
                    <label for="edit_area" class="form-label">Area</label>
                    <input type="text" class="form-control" id="edit_area" name="area" value="{{ $customer->area }}">
                </div>
                <div class="col-4">
                    <label for="edit_brick" class="form-label">Brick</label>
                    <input type="text" class="form-control" id="edit_brick" name="brick" value="{{ $customer->brick }}">
                </div>
                <div class="col-4">
                    <label for="edit_salesman" class="form-label">Salesman</label>
                    <input type="text" class="form-control" id="edit_salesman" name="salesman" value="{{ $customer->salesman }}">
                </div>
            </div>

            <!-- Currency Checkboxes -->
            <div class="mt-2">
                <label class="form-label">Currencies</label>
                <div class="d-flex gap-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="edit_usd" name="usd" value="1" {{ $customer->usd ? 'checked' : '' }}>
                        <label class="form-check-label" for="edit_usd">USD</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="edit_afn" name="afn" value="1" {{ $customer->afn ? 'checked' : '' }}>
                        <label class="form-check-label" for="edit_afn">AFN</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="edit_pkr" name="pkr" value="1" {{ $customer->pkr ? 'checked' : '' }}>
                        <label class="form-check-label" for="edit_pkr">PKR</label>
                    </div>
                </div>
            </div>
        </div>
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