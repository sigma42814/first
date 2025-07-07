<form id="addCustomerForm" action="{{ route('customers.store') }}" method="POST" class="compact-form">
    @csrf
    <div class="row g-2">
        <!-- Left Column -->
        <div class="col-md-6">
            <!-- Name -->
            <div class="mb-2">
                <label for="name" class="form-label">
                    <i class="fas fa-user me-1"></i> Name
                </label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>

            <!-- Company -->
            <div class="mb-2">
                <label for="company" class="form-label">
                    <i class="fas fa-building me-1"></i> Company
                </label>
                <input type="text" class="form-control" id="company" name="company">
            </div>

            <!-- Email -->
            <div class="mb-2">
                <label for="email" class="form-label">
                    <i class="fas fa-envelope me-1"></i> Email
                </label>
                <input type="email" class="form-control" id="email" name="email">
            </div>

            <!-- Phone -->
            <div class="mb-2">
                <label for="phone" class="form-label">
                    <i class="fas fa-phone me-1"></i> Phone
                </label>
                <input type="text" class="form-control" id="phone" name="phone">
            </div>
        </div>

        <!-- Right Column -->
        <div class="col-md-6">
            <!-- Address -->
            <div class="mb-2">
                <label for="address" class="form-label">
                    <i class="fas fa-map-marker-alt me-1"></i> Address
                </label>
                <input type="text" class="form-control" id="address" name="address">
            </div>

            <!-- Credit Limit -->
            <div class="mb-2">
                <label for="credit_limit" class="form-label">
                    <i class="fas fa-credit-card me-1"></i> Credit Limit
                </label>
                <input type="number" step="0.01" class="form-control" id="credit_limit" name="credit_limit">
            </div>

            <!-- Area/Brick/Salesman -->
            <div class="row g-2">
                <div class="col-4">
                    <label for="area" class="form-label">Area</label>
                    <input type="text" class="form-control" id="area" name="area">
                </div>
                <div class="col-4">
                    <label for="brick" class="form-label">Brick</label>
                    <input type="text" class="form-control" id="brick" name="brick">
                </div>
                <div class="col-4">
                    <label for="salesman" class="form-label">Salesman</label>
                    <input type="text" class="form-control" id="salesman" name="salesman">
                </div>
            </div>

            <!-- Currency Checkboxes -->
            <div class="mt-2">
                <label class="form-label">Currencies</label>
                <div class="d-flex gap-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="usd" name="usd" value="1">
                        <label class="form-check-label" for="usd">USD</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="afn" name="afn" value="1">
                        <label class="form-check-label" for="afn">AFN</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="pkr" name="pkr" value="1">
                        <label class="form-check-label" for="pkr">PKR</label>
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
            <i class="fas fa-save me-1"></i> Save
        </button>
    </div>
</form>