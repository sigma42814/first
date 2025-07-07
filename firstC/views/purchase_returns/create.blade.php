@extends('layouts.app')

@section('title', 'Create Purchase Return')

@section('content')
<div class="container-fluid px-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white py-2">
            <h5 class="mb-0">
                <i class="fas fa-undo me-2"></i> Create Purchase Return
            </h5>
        </div>
        <div class="card-body p-3">
            <!-- Invoice Information Section -->
            <div class="row invoice-info mb-3">
                <!-- Company Code Search -->
                <div class="col-md-1 position-relative">
                    <label for="companyCode" class="small">Code</label>
                    <input type="text" id="companyCode" class="form-control form-control-sm" placeholder="Search..." 
                           oninput="debouncedSearchCompanyCode(this)" 
                           onkeydown="handleCompanyCodeSearchKeydown(event)">
                    <div id="companyCodeDropdown" class="dropdown-menu" style="display: none;">
                        <table class="table table-sm table-borderless mb-0">
                            <thead>
                                <tr>
                                    <th class="small" style="width: 50%;">Code</th>
                                    <th class="small" style="width: 50%;">Name</th>
                                </tr>
                            </thead>
                            <tbody id="companyCodeDropdownBody" class="small"></tbody>
                        </table>
                    </div>
                </div>

                <!-- Company Name -->
                <div class="col-md-3">
                    <label for="companyName" class="small">Company Name</label>
                    <input type="text" id="companyName" class="form-control form-control-sm" readonly>
                    <input type="hidden" id="companyId" name="company_id">
                </div>

                <!-- Reason -->
                <div class="col-md-2">
                    <label for="reason" class="small">Reason</label>
                    <input type="text" id="reason" class="form-control form-control-sm" placeholder="Reason..."
                           onkeydown="handleEnter(event, 'reason')">
                </div>

                <!-- Return Number -->
                <div class="col-md-2">
                    <label for="returnNumber" class="small">Return #</label>
                    <input type="text" id="returnNumber" class="form-control form-control-sm" placeholder="Auto-generated" readonly>
                </div>

                <!-- Date -->
                <div class="col-md-2">
                    <label for="returnDate" class="small">Date</label>
                    <input type="date" id="returnDate" class="form-control form-control-sm" value="{{ date('Y-m-d') }}">
                </div>

                <!-- Address -->
                <div class="col-md-2">
                    <label for="address" class="small">Address</label>
                    <input type="text" id="address" class="form-control form-control-sm" readonly>
                </div>
            </div>

            <!-- Returns Table -->
            <table id="returnsTable" class="table table-bordered table-sm">
                <thead>
                    <tr>
                        <th style="width: 10%;">Code</th>
                        <th style="width: 25%;">Item</th>
                        <th style="width: 12%;">Batch Number</th>
                        <th style="width: 10%;">Price</th>
                        <th style="width: 8%;">Quantity</th>
                        <th style="width: 8%;">Dis 1</th>
                        <th style="width: 8%;">Exp Date</th>
                        <th style="width: 12%;">Total</th>
                        <th style="width: 5%;">Delete</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <div class="position-relative">
                                <input type="text" name="code[]" class="code form-control form-control-sm" placeholder="Search Item Code..." 
                                       oninput="debouncedSearchItemCode(this)" 
                                       onkeydown="handleItemCodeSearchKeydown(event)" 
                                       style="border: none;">
                                <div id="itemCodeDropdown" class="dropdown-menu" style="display: none;">
                                    <table class="table table-sm table-borderless mb-0">
                                        <thead>
                                            <tr>
                                                <th class="small" style="width: 50%;">Code</th>
                                                <th class="small" style="width: 50%;">Name</th>
                                            </tr>
                                        </thead>
                                        <tbody id="itemCodeDropdownBody" class="small"></tbody>
                                    </table>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="position-relative">
                                <input type="text" class="itemSearch form-control form-control-sm" placeholder="Search Item..." 
                                       oninput="debouncedSearchItems(this)" 
                                       onkeydown="handleItemSearchKeydown(event)" 
                                       style="border: none;">
                                <div id="itemDropdown" class="dropdown-menu" style="display: none;">
                                    <table class="table table-sm table-borderless mb-0">
                                        <thead>
                                            <tr>
                                                <th class="small" style="width: 50%;">Item</th>
                                                <th class="small" style="width: 50%;">Price</th>
                                            </tr>
                                        </thead>
                                        <tbody id="itemDropdownBody" class="small"></tbody>
                                    </table>
                                </div>
                            </div>
                        </td>
                        <td><input type="text" name="batch_number[]" class="batchNumber form-control form-control-sm" 
                                   onkeydown="handleEnter(event, 'batchNumber')" style="border: none;"></td>
                        <td><input type="number" name="price[]" class="price form-control form-control-sm" 
                                  oninput="calculateTotal(this)" 
                                  onkeydown="handleEnter(event, 'price')" style="border: none;"></td>
                        <td><input type="number" name="quantity[]" class="quantity form-control form-control-sm" 
                                  oninput="calculateTotal(this)" 
                                  onkeydown="handleEnter(event, 'quantity')" style="border: none;"></td>
                        <td><input type="number" name="discount[]" class="discount form-control form-control-sm" 
                                  oninput="calculateTotal(this)" 
                                  onkeydown="handleEnter(event, 'discount')" style="border: none;"></td>
                        <td><input type="date" name="exp_date[]" class="expDate form-control form-control-sm" 
                                  onkeydown="handleEnter(event, 'expDate')" style="border: none;"></td>
                        <td><input type="number" name="total[]" class="total form-control form-control-sm" disabled style="border: none;"></td>
                        <td><button type="button" class="btn btn-danger btn-sm" onclick="deleteRow(this)"><i class="fas fa-trash-alt"></i></button></td>
                    </tr>
                </tbody>
            </table>

            <!-- Buttons -->
            <div class="btn-group mt-3">
                <button type="button" class="btn btn-primary btn-sm" id="saveButton" onclick="saveReturn()">
                    <i class="fas fa-save"></i> Save
                </button>
                <button type="button" class="btn btn-warning btn-sm" onclick="addNewRow()">
                    <i class="fas fa-plus-circle"></i> New
                </button>
                <button type="button" class="btn btn-success btn-sm" onclick="saveAndPrint()">
                    <i class="fas fa-print"></i> Save & Print
                </button>
            </div>

            <!-- Summary Section -->
            <div class="summary mt-3">
                <strong>Return Details:</strong>
                <p>Total Return: <span id="totalReturn">0</span> USD</p>
            </div>

            <!-- Balance Section -->
            <div class="balance-section mt-3">
                <div class="row">
                    <div class="col-md-4">
                        <label for="netPayable" class="small">Net Payable</label>
                        <input type="number" id="netPayable" class="form-control form-control-sm" readonly>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- SweetAlert CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Debounce function
    function debounce(func, timeout = 300) {
        let timer;
        return (...args) => {
            clearTimeout(timer);
            timer = setTimeout(() => { func.apply(this, args); }, timeout);
        };
    }

    // Track selected dropdown item
    let selectedIndex = -1;
    let isSaving = false; // Flag to prevent double saving

    // Search companies by code
    async function searchCompanyCode(input) {
        const query = input.value.trim();
        const dropdownBody = document.getElementById('companyCodeDropdownBody');
        
        if (query.length > 0) {
            try {
                const response = await fetch(`/search-companies?query=${query}`);
                const companies = await response.json();

                dropdownBody.innerHTML = '';
                selectedIndex = -1;

                companies.forEach((company, index) => {
                    const row = document.createElement('tr');
                    row.className = 'dropdown-item small';
                    row.innerHTML = `
                        <td>${company.code}</td>
                        <td>${company.name}</td>
                    `;
                    row.onclick = () => selectCompany(company);
                    row.addEventListener('mouseover', () => {
                        setSelectedIndex(index, 'companyCodeDropdownBody');
                    });
                    dropdownBody.appendChild(row);
                });

                document.getElementById('companyCodeDropdown').style.display = 'block';
            } catch (error) {
                console.error('Error:', error);
            }
        } else {
            document.getElementById('companyCodeDropdown').style.display = 'none';
        }
    }

    const debouncedSearchCompanyCode = debounce(searchCompanyCode);

    function selectCompany(company) {
        document.getElementById('companyCode').value = company.code;
        document.getElementById('companyName').value = company.name;
        document.getElementById('companyId').value = company.id;
        document.getElementById('address').value = company.address || '';
        document.getElementById('companyCodeDropdown').style.display = 'none';
        document.getElementById('reason').focus();
    }

    // Handle Enter key navigation
    function handleEnter(event, currentField) {
        if (event.key === 'Enter') {
            event.preventDefault();
            
            const navigationMap = {
                'companyCode': () => document.getElementById('reason').focus(),
                'reason': () => document.querySelector('#returnsTable tbody tr:first-child .code').focus(),
                'code': (row) => row.querySelector('.itemSearch').focus(),
                'itemSearch': (row) => row.querySelector('.batchNumber').focus(),
                'batchNumber': (row) => row.querySelector('.price').focus(),
                'price': (row) => row.querySelector('.quantity').focus(),
                'quantity': (row) => row.querySelector('.discount').focus(),
                'discount': (row) => row.querySelector('.expDate').focus(),
                'expDate': (row) => {
                    const nextRow = row.nextElementSibling;
                    if (nextRow) {
                        nextRow.querySelector('.code').focus();
                    } else {
                        addNewRow();
                    }
                }
            };

            if (currentField === 'companyCode' || currentField === 'reason') {
                navigationMap[currentField]();
            } else {
                const row = event.target.closest('tr');
                if (row && navigationMap[currentField]) {
                    navigationMap[currentField](row);
                }
            }
        }
    }

    // Search items by code
    async function searchItemCode(input) {
        const query = input.value.trim();
        const dropdownBody = document.getElementById('itemCodeDropdownBody');
        
        if (query.length > 0) {
            try {
                const response = await fetch(`/search-items?query=${query}`);
                const items = await response.json();

                dropdownBody.innerHTML = '';
                selectedIndex = -1;

                items.forEach((item, index) => {
                    const row = document.createElement('tr');
                    row.className = 'dropdown-item small';
                    row.innerHTML = `
                        <td>${item.id}</td>
                        <td>${item.item_name}</td>
                    `;
                    row.onclick = () => selectItemCode(item, input);
                    row.addEventListener('mouseover', () => {
                        setSelectedIndex(index, 'itemCodeDropdownBody');
                    });
                    dropdownBody.appendChild(row);
                });

                document.getElementById('itemCodeDropdown').style.display = 'block';
            } catch (error) {
                console.error('Error:', error);
            }
        } else {
            document.getElementById('itemCodeDropdown').style.display = 'none';
        }
    }

    const debouncedSearchItemCode = debounce(searchItemCode);

    // Search items by name
    async function searchItems(input) {
        const query = input.value.trim();
        const dropdownBody = document.getElementById('itemDropdownBody');
        
        if (query.length > 0) {
            try {
                const response = await fetch(`/search-items-by-name?query=${query}`);
                const items = await response.json();

                dropdownBody.innerHTML = '';
                selectedIndex = -1;

                items.forEach((item, index) => {
                    const row = document.createElement('tr');
                    row.className = 'dropdown-item small';
                    row.innerHTML = `
                        <td>${item.item_name}</td>
                        <td>${item.tp || 0}</td>
                    `;
                    row.onclick = () => selectItem(item, input);
                    row.addEventListener('mouseover', () => {
                        setSelectedIndex(index, 'itemDropdownBody');
                    });
                    dropdownBody.appendChild(row);
                });

                document.getElementById('itemDropdown').style.display = 'block';
            } catch (error) {
                console.error('Error:', error);
            }
        } else {
            document.getElementById('itemDropdown').style.display = 'none';
        }
    }

    const debouncedSearchItems = debounce(searchItems);

    function selectItemCode(item, input) {
        const row = input.closest('tr');
        if (row) {
            row.querySelector('.code').value = item.id;
            row.querySelector('.itemSearch').value = item.item_name;
            row.querySelector('.price').value = item.tp || 0;
            document.getElementById('itemCodeDropdown').style.display = 'none';
            row.querySelector('.batchNumber').focus();
        }
    }

    function selectItem(item, input) {
        const row = input.closest('tr');
        if (row) {
            row.querySelector('.code').value = item.id;
            row.querySelector('.itemSearch').value = item.item_name;
            row.querySelector('.price').value = item.tp || 0;
            document.getElementById('itemDropdown').style.display = 'none';
            row.querySelector('.batchNumber').focus();
        }
    }

    function calculateTotal(input) {
        const row = input.closest('tr');
        if (!row) return;

        const priceInput = row.querySelector('.price');
        const quantityInput = row.querySelector('.quantity');
        const discountInput = row.querySelector('.discount');
        const totalInput = row.querySelector('.total');

        if (!priceInput || !quantityInput || !discountInput || !totalInput) return;

        const price = parseFloat(priceInput.value) || 0;
        const quantity = parseFloat(quantityInput.value) || 0;
        const discount = parseFloat(discountInput.value) || 0;
        
        const subtotal = price * quantity;
        const total = subtotal - (subtotal * discount / 100);
        
        totalInput.value = total.toFixed(2);
        updateTotalReturn();
    }

    function updateTotalReturn() {
        let total = 0;
        document.querySelectorAll('#returnsTable tbody tr').forEach(row => {
            const totalInput = row.querySelector('.total');
            if (totalInput) {
                total += parseFloat(totalInput.value) || 0;
            }
        });
        
        document.getElementById('totalReturn').textContent = total.toFixed(2);
        document.getElementById('netPayable').value = total.toFixed(2);
    }

    function setSelectedIndex(index, dropdownId) {
        const dropdownItems = document.querySelectorAll(`#${dropdownId} .dropdown-item`);
        if (selectedIndex >= 0 && dropdownItems[selectedIndex]) {
            dropdownItems[selectedIndex].classList.remove('selected');
        }
        selectedIndex = index;
        if (dropdownItems[selectedIndex]) {
            dropdownItems[selectedIndex].classList.add('selected');
        }
    }

    function handleDropdownNavigation(event, dropdownId) {
        const dropdownItems = document.querySelectorAll(`#${dropdownId} .dropdown-item`);
        if (dropdownItems.length === 0) return;

        if (event.key === 'ArrowDown') {
            event.preventDefault();
            setSelectedIndex((selectedIndex + 1) % dropdownItems.length, dropdownId);
        } else if (event.key === 'ArrowUp') {
            event.preventDefault();
            setSelectedIndex((selectedIndex - 1 + dropdownItems.length) % dropdownItems.length, dropdownId);
        } else if (event.key === 'Enter' && selectedIndex >= 0) {
            event.preventDefault();
            dropdownItems[selectedIndex].click();
        }
    }

    function handleCompanyCodeSearchKeydown(event) {
        if (['ArrowDown', 'ArrowUp', 'Enter'].includes(event.key)) {
            handleDropdownNavigation(event, 'companyCodeDropdownBody');
        }
    }

    function handleItemCodeSearchKeydown(event) {
        if (['ArrowDown', 'ArrowUp', 'Enter'].includes(event.key)) {
            handleDropdownNavigation(event, 'itemCodeDropdownBody');
        }
    }

    function handleItemSearchKeydown(event) {
        if (['ArrowDown', 'ArrowUp', 'Enter'].includes(event.key)) {
            handleDropdownNavigation(event, 'itemDropdownBody');
        }
    }

    function attachRowEventListeners(row) {
        const codeInput = row.querySelector('.code');
        const itemSearchInput = row.querySelector('.itemSearch');

        if (codeInput) {
            codeInput.addEventListener('keydown', (event) => {
                if (['ArrowDown', 'ArrowUp', 'Enter'].includes(event.key)) {
                    handleDropdownNavigation(event, 'itemCodeDropdownBody');
                }
            });
        }

        if (itemSearchInput) {
            itemSearchInput.addEventListener('keydown', (event) => {
                if (['ArrowDown', 'ArrowUp', 'Enter'].includes(event.key)) {
                    handleDropdownNavigation(event, 'itemDropdownBody');
                }
            });
        }

        ['price', 'quantity', 'discount'].forEach(field => {
            const input = row.querySelector(`.${field}`);
            if (input) {
                input.addEventListener('input', () => calculateTotal(input));
            }
        });
    }

    function addNewRow() {
        const tbody = document.querySelector('#returnsTable tbody');
        if (!tbody) return;

        const newRow = tbody.querySelector('tr').cloneNode(true);
        
        newRow.querySelectorAll('input').forEach(input => {
            if (!input.classList.contains('total')) {
                input.value = '';
            } else {
                input.value = '0.00';
            }
        });
        
        tbody.appendChild(newRow);
        attachRowEventListeners(newRow);
        newRow.querySelector('.code').focus();
    }

    function deleteRow(button) {
        const row = button.closest('tr');
        if (!row) return;

        if (document.querySelectorAll('#returnsTable tbody tr').length > 1) {
            row.remove();
            updateTotalReturn();
        } else {
            row.querySelectorAll('input').forEach(input => {
                if (!input.classList.contains('total')) {
                    input.value = '';
                } else {
                    input.value = '0.00';
                }
            });
        }
    }

    function getReturnItemsData() {
        const items = [];
        document.querySelectorAll('#returnsTable tbody tr').forEach(row => {
            const codeInput = row.querySelector('.code');
            const itemSearchInput = row.querySelector('.itemSearch');
            const batchNumberInput = row.querySelector('.batchNumber');
            const priceInput = row.querySelector('.price');
            const quantityInput = row.querySelector('.quantity');
            const discountInput = row.querySelector('.discount');
            const expDateInput = row.querySelector('.expDate');
            const totalInput = row.querySelector('.total');

            if (codeInput && codeInput.value && 
                itemSearchInput && priceInput && quantityInput && 
                discountInput && expDateInput && totalInput) {
                items.push({
                    item_id: codeInput.value,
                    item_name: itemSearchInput.value,
                    batch_number: batchNumberInput ? batchNumberInput.value : '',
                    exp_date: expDateInput ? expDateInput.value : '',
                    price: parseFloat(priceInput.value) || 0,
                    quantity: parseFloat(quantityInput.value) || 0,
                    discount: parseFloat(discountInput.value) || 0,
                    total: parseFloat(totalInput.value) || 0
                });
            }
        });
        return items;
    }

    async function saveReturn() {
        if (isSaving) return; // Prevent multiple saves
        isSaving = true;
        
        const companyId = document.getElementById('companyId')?.value;
        if (!companyId) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Please select a company first'
            });
            document.getElementById('companyCode')?.focus();
            isSaving = false;
            return;
        }

        const items = getReturnItemsData();
        if (items.length === 0) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Please add at least one item to return'
            });
            isSaving = false;
            return;
        }

        const saveButton = document.getElementById('saveButton');
        if (saveButton) {
            saveButton.disabled = true;
            saveButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
        }

        try {
            const response = await fetch('/purchase-returns', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    company_id: companyId,
                    company_name: document.getElementById('companyName')?.value || '',
                    address: document.getElementById('address')?.value || '',
                    return_number: document.getElementById('returnNumber')?.value || '',
                    return_date: document.getElementById('returnDate')?.value || '',
                    reason: document.getElementById('reason')?.value || '',
                    items: items
                })
            });

            const data = await response.json();
            
            if (!response.ok) {
                throw new Error(data.message || 'Failed to save purchase return');
            }

            // Show success message and redirect
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'Purchase return created successfully!',
                showConfirmButton: false,
                timer: 1500
            }).then(() => {
                window.location.href = data.redirect_url || '/purchase-returns';
            });
            
        } catch (error) {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to save purchase return: ' + error.message
            });
        } finally {
            isSaving = false;
            if (saveButton) {
                saveButton.disabled = false;
                saveButton.innerHTML = '<i class="fas fa-save"></i> Save';
            }
        }
    }

    async function saveAndPrint() {
        await saveReturn();
        // Printing logic can be added here
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', () => {
        const saveButton = document.getElementById('saveButton');
        if (saveButton) {
            saveButton.addEventListener('click', saveReturn);
        }

        const firstRow = document.querySelector('#returnsTable tbody tr');
        if (firstRow) {
            attachRowEventListeners(firstRow);
        }
        
        const returnNumberInput = document.getElementById('returnNumber');
        if (returnNumberInput && !returnNumberInput.value) {
            returnNumberInput.value = 
                'PR-' + new Date().getFullYear() + '-' + Math.floor(Math.random() * 1000);
        }
    });
</script>

<style>
    .dropdown-menu {
        width: 300px;
        max-height: 200px;
        overflow-y: auto;
        border: 1px solid #ddd;
        border-radius: 4px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        position: absolute;
        z-index: 1000;
        background-color: white;
    }
    .dropdown-item {
        cursor: pointer;
        padding: 5px 10px;
    }
    .dropdown-item:hover {
        background-color: #f8f9fa;
    }
    .dropdown-item.selected {
        background-color: #222222;
        color: white;
    }
    .small {
        font-size: 0.875rem;
    }
    .form-control-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
        line-height: 1.5;
        border-radius: 0.2rem;
    }
    .col-md-1 .form-control-sm {
        max-width: 80px;
    }
    #reason {
        min-width: 150px;
    }
    table.table-bordered {
        border: 1px solid #dee2e6;
    }
    table.table-bordered th, 
    table.table-bordered td {
        border: 1px solid #dee2e6;
    }
</style>
@endsection