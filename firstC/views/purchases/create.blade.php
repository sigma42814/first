@extends('layouts.app')

@section('title', 'Create Purchase')

@section('content')
<div class="container-fluid px-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white py-2">
            <h5 class="mb-0">
                <i class="fas fa-shopping-bag me-2"></i> Create Purchase
            </h5>
        </div>
        <div class="card-body p-3">
            <!-- Invoice Information Section -->
            <div class="row invoice-info mb-3">
                <!-- Company Code Search -->
                <div class="col-md-1 position-relative">
                    <label for="companyCode" class="small">Code</label>
                    <input type="text" id="companyCode" name="company_code" class="form-control form-control-sm" placeholder="Search by Code..." oninput="debouncedSearchCompanyCode(this)" onkeydown="handleCompanyCodeSearchKeydown(event)">
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
                    <input type="text" id="companyName" name="company_name" class="form-control form-control-sm" readonly>
                </div>

                <!-- Company Invoice Number -->
                <div class="col-md-2">
                    <label for="companyInvoice" class="small">Company Inv#</label>
                    <input type="text" id="companyInvoice" name="company_invoice" class="form-control form-control-sm" placeholder="Company invoice..." onkeydown="handleEnter(event, 'companyInvoice')">
                </div>

                <!-- Date -->
                <div class="col-md-2">
                    <label for="invoiceDate" class="small">Date</label>
                    <input type="date" id="invoiceDate" name="invoice_date" class="form-control form-control-sm" value="{{ now()->toDateString() }}" onkeydown="handleEnter(event, 'invoiceDate')">
                </div>

                <!-- Invoice Number -->
                <div class="col-md-2">
                    <label for="invoiceNumber" class="small">Invoice Number</label>
                    <input type="text" id="invoiceNumber" name="invoice_number" class="form-control form-control-sm" placeholder="Auto-generated">
                </div>

                <!-- Address -->
                <div class="col-md-2">
                    <label for="address" class="small">Company Address</label>
                    <input type="text" id="address" name="address" class="form-control form-control-sm" readonly>
                </div>
            </div>

            <!-- Purchases Table -->
            <table id="purchasesTable" class="table table-bordered table-sm">
                <thead>
                    <tr>
                        <th style="width: 10%;">Code</th>
                        <th style="width: 25%;">Item</th>
                        <th style="width: 12%;">Batch Number</th>
                        <th style="width: 10%;">Price</th>
                        <th style="width: 10%;">Purchase Price</th>
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
                                <input type="text" name="code[]" class="code form-control form-control-sm" placeholder="Search Item Code..." oninput="debouncedSearchItemCode(this)" onkeydown="handleItemCodeSearchKeydown(event)" style="border: none;" onkeydown="handleEnter(event, 'code')">
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
                                <input type="text" class="itemSearch form-control form-control-sm" placeholder="Search Item..." oninput="debouncedSearchItems(this)" onkeydown="handleItemSearchKeydown(event)" style="border: none;" onkeydown="handleEnter(event, 'itemSearch')">
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
                        <td><input type="text" name="batch_number[]" class="batchNumber form-control form-control-sm" style="border: none;" onkeydown="handleEnter(event, 'batchNumber')"></td>
                        <td><input type="number" name="price[]" class="price form-control form-control-sm" onkeydown="handleEnter(event, 'price')" oninput="calculateTotal(this)" style="border: none;"></td>
                        <td><input type="number" name="purchase_price[]" class="purchasePrice form-control form-control-sm" onkeydown="handleEnter(event, 'purchasePrice')" style="border: none;"></td>
                        <td><input type="number" name="quantity[]" class="quantity form-control form-control-sm" onkeydown="handleEnter(event, 'quantity')" oninput="calculateTotal(this)" style="border: none;"></td>
                        <td><input type="number" name="discount[]" class="discount form-control form-control-sm" onkeydown="handleEnter(event, 'discount')" oninput="calculateTotal(this)" style="border: none;"></td>
                        <td><input type="date" name="exp_date[]" class="expDate form-control form-control-sm" onkeydown="handleEnter(event, 'expDate')" style="border: none;"></td>
                        <td><input type="number" name="total[]" class="total form-control form-control-sm" disabled style="border: none;"></td>
                        <td><button type="button" class="btn btn-danger btn-sm" onclick="deleteRow(this)"><i class="fas fa-trash-alt"></i></button></td>
                    </tr>
                </tbody>
            </table>

            <!-- Buttons -->
            <div class="btn-group mt-3">
                <button type="button" class="btn btn-primary btn-sm" id="saveButton">
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
                <strong>Purchase Details:</strong>
                <p>Total Purchases: <span id="totalPurchases">0</span> USD</p>
            </div>

            <!-- Balance Section -->
            <div class="balance-section mt-3">
                <div class="row">
                    <div class="col-md-4">
                        <label for="netPayable" class="small">Net Payable</label>
                        <input type="number" id="netPayable" name="net_payable" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-4">
                        <label for="prevBalance" class="small">Previous Balance</label>
                        <input type="number" id="prevBalance" name="prev_balance" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-4">
                        <label for="totalBalance" class="small">Total Balance</label>
                        <input type="number" id="totalBalance" name="total_balance" class="form-control form-control-sm" disabled>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Debounce function to limit API calls
    function debounce(func, timeout = 300) {
        let timer;
        return (...args) => {
            clearTimeout(timer);
            timer = setTimeout(() => { func.apply(this, args); }, timeout);
        };
    }

    // Track the currently selected dropdown item
    let selectedIndex = -1;

    // Fetch companies by code
    async function searchCompanyCode(input) {
        if (!input) return;
        
        const query = input.value.trim();
        const dropdownBody = document.getElementById('companyCodeDropdownBody');
        if (!dropdownBody) return;
        
        dropdownBody.innerHTML = '';
        selectedIndex = -1;

        if (query.length > 0) {
            try {
                const response = await fetch(`/search-companies?query=${query}`);
                if (!response.ok) throw new Error('Network response was not ok');
                const companies = await response.json();

                if (companies.length > 0) {
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
                    const dropdown = document.getElementById('companyCodeDropdown');
                    if (dropdown) dropdown.style.display = 'block';
                } else {
                    const dropdown = document.getElementById('companyCodeDropdown');
                    if (dropdown) dropdown.style.display = 'none';
                }
            } catch (error) {
                console.error('Error fetching companies:', error);
                showError('Failed to fetch companies');
            }
        } else {
            const dropdown = document.getElementById('companyCodeDropdown');
            if (dropdown) dropdown.style.display = 'none';
        }
    }

    const debouncedSearchCompanyCode = debounce(searchCompanyCode);

    function selectCompany(company) {
        if (!company) return;
        
        const companyCodeInput = document.getElementById('companyCode');
        const companyNameInput = document.getElementById('companyName');
        const addressInput = document.getElementById('address');
        const dropdown = document.getElementById('companyCodeDropdown');
        
        if (companyCodeInput) companyCodeInput.value = company.code || '';
        if (companyNameInput) companyNameInput.value = company.name || '';
        if (addressInput) addressInput.value = company.address || '';
        if (dropdown) dropdown.style.display = 'none';
        
        // Focus on the company invoice input
        document.getElementById('companyInvoice').focus();
    }

    // Handle Enter key navigation
    function handleEnter(event, currentField) {
        if (event.key === 'Enter') {
            event.preventDefault();
            
            const navigationMap = {
                'companyInvoice': () => document.getElementById('invoiceDate').focus(),
                'invoiceDate': () => document.querySelector('#purchasesTable tbody tr:first-child .code').focus(),
                'code': (row) => row.querySelector('.itemSearch').focus(),
                'itemSearch': (row) => row.querySelector('.batchNumber').focus(),
                'batchNumber': (row) => row.querySelector('.price').focus(),
                'price': (row) => row.querySelector('.purchasePrice').focus(),
                'purchasePrice': (row) => row.querySelector('.quantity').focus(),
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

            if (currentField === 'companyInvoice' || currentField === 'invoiceDate') {
                navigationMap[currentField]();
            } else {
                const row = event.target.closest('tr');
                if (row && navigationMap[currentField]) {
                    navigationMap[currentField](row);
                }
            }
        }
    }

    // Fetch items by code or name
    async function searchItemCode(input) {
        if (!input) return;
        
        const query = input.value.trim();
        const dropdownBody = document.getElementById('itemCodeDropdownBody');
        if (!dropdownBody) return;
        
        dropdownBody.innerHTML = '';
        selectedIndex = -1;

        if (query.length > 0) {
            try {
                const response = await fetch(`/search-items?query=${query}`);
                if (!response.ok) throw new Error('Network response was not ok');
                const items = await response.json();

                if (items.length > 0) {
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
                    const dropdown = document.getElementById('itemCodeDropdown');
                    if (dropdown) dropdown.style.display = 'block';
                } else {
                    const dropdown = document.getElementById('itemCodeDropdown');
                    if (dropdown) dropdown.style.display = 'none';
                }
            } catch (error) {
                console.error('Error fetching items:', error);
                showError('Failed to fetch items');
            }
        } else {
            const dropdown = document.getElementById('itemCodeDropdown');
            if (dropdown) dropdown.style.display = 'none';
        }
    }

    const debouncedSearchItemCode = debounce(searchItemCode);

    function selectItemCode(item, input) {
        if (!item || !input) return;
        
        const row = input.closest('tr');
        if (!row) return;

        const codeInput = row.querySelector('.code');
        const itemSearchInput = row.querySelector('.itemSearch');
        const priceInput = row.querySelector('.price');
        const purchasePriceInput = row.querySelector('.purchasePrice');
        const dropdown = document.getElementById('itemCodeDropdown');
        
        if (codeInput) codeInput.value = item.id || '';
        if (itemSearchInput) itemSearchInput.value = item.item_name || '';
        if (priceInput) priceInput.value = item.tp || 0;
        if (purchasePriceInput) purchasePriceInput.value = item.purchase_price || 0;
        
        if (dropdown) dropdown.style.display = 'none';
        
        // After selecting item, focus on batch number
        row.querySelector('.batchNumber').focus();
    }

    function calculateTotal(input) {
        if (!input) return;
        
        const row = input.closest('tr');
        if (!row) return;

        const priceInput = row.querySelector('.price');
        const quantityInput = row.querySelector('.quantity');
        const discountInput = row.querySelector('.discount');
        const totalInput = row.querySelector('.total');

        // Exit if required fields are missing
        if (!priceInput || !quantityInput || !totalInput) return;

        const price = parseFloat(priceInput.value) || 0;
        const quantity = parseFloat(quantityInput.value) || 0;
        const discount = parseFloat(discountInput?.value) || 0;

        const subtotal = price * quantity;
        const afterDiscount = subtotal * (1 - discount / 100);
        
        totalInput.value = afterDiscount.toFixed(2);
        updateTotalPurchases();
    }

    function updateTotalPurchases() {
        let total = 0;
        const rows = document.querySelectorAll('#purchasesTable tbody tr');
        
        rows.forEach(row => {
            const totalInput = row.querySelector('.total');
            if (totalInput) {
                const rowTotal = parseFloat(totalInput.value) || 0;
                total += rowTotal;
            }
        });
        
        const totalPurchasesElement = document.getElementById('totalPurchases');
        const netPayableElement = document.getElementById('netPayable');
        const totalBalanceElement = document.getElementById('totalBalance');
        
        if (totalPurchasesElement) totalPurchasesElement.textContent = total.toFixed(2);
        if (netPayableElement) netPayableElement.value = total.toFixed(2);
        if (totalBalanceElement) totalBalanceElement.value = total.toFixed(2);
    }

    function setSelectedIndex(index, dropdownId) {
        const dropdownItems = document.querySelectorAll(`#${dropdownId} .dropdown-item`);
        if (selectedIndex >= 0 && dropdownItems[selectedIndex]) {
            dropdownItems[selectedIndex].classList.remove('selected');
        }
        selectedIndex = index;
        if (selectedIndex >= 0 && dropdownItems[selectedIndex]) {
            dropdownItems[selectedIndex].classList.add('selected');
        }
    }

    function handleDropdownNavigation(event, dropdownId) {
        if (!event) return;
        
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
            if (dropdownItems[selectedIndex]) {
                dropdownItems[selectedIndex].click();
            }
        }
    }

    function handleCompanyCodeSearchKeydown(event) {
        if (event.key === 'ArrowDown' || event.key === 'ArrowUp' || event.key === 'Enter') {
            handleDropdownNavigation(event, 'companyCodeDropdownBody');
        }
    }

    function handleItemCodeSearchKeydown(event) {
        if (event.key === 'ArrowDown' || event.key === 'ArrowUp' || event.key === 'Enter') {
            handleDropdownNavigation(event, 'itemCodeDropdownBody');
        }
    }

    function handleItemSearchKeydown(event) {
        if (event.key === 'ArrowDown' || event.key === 'ArrowUp' || event.key === 'Enter') {
            handleDropdownNavigation(event, 'itemDropdownBody');
        }
    }

    function attachRowEventListeners(row) {
        if (!row) return;
        
        const codeInput = row.querySelector('.code');
        const itemSearchInput = row.querySelector('.itemSearch');

        if (codeInput) {
            codeInput.addEventListener('keydown', (event) => {
                if (event.key === 'ArrowDown' || event.key === 'ArrowUp' || event.key === 'Enter') {
                    handleDropdownNavigation(event, 'itemCodeDropdownBody');
                }
            });
        }

        if (itemSearchInput) {
            itemSearchInput.addEventListener('keydown', (event) => {
                if (event.key === 'ArrowDown' || event.key === 'ArrowUp' || event.key === 'Enter') {
                    handleDropdownNavigation(event, 'itemDropdownBody');
                }
            });
        }

        // Add input event listeners for calculation
        ['price', 'quantity', 'discount'].forEach(field => {
            const input = row.querySelector(`.${field}`);
            if (input) {
                input.addEventListener('input', () => calculateTotal(input));
            }
        });
    }

    function addNewRow() {
        const tbody = document.querySelector('#purchasesTable tbody');
        if (!tbody) return;

        const firstRow = tbody.querySelector('tr');
        if (!firstRow) return;

        const newRow = firstRow.cloneNode(true);
        
        // Clear all inputs in the new row
        newRow.querySelectorAll('input').forEach(input => {
            if (input.type !== 'button') input.value = '';
            if (input.classList.contains('total')) input.value = '0.00';
            if (input.classList.contains('expDate')) {
                // Set default exp date to 1 year from now
                const oneYearLater = new Date();
                oneYearLater.setFullYear(oneYearLater.getFullYear() + 1);
                input.valueAsDate = oneYearLater;
            }
        });
        
        tbody.appendChild(newRow);
        attachRowEventListeners(newRow);
        const codeInput = newRow.querySelector('.code');
        if (codeInput) codeInput.focus();
    }

    function deleteRow(button) {
        if (!button) return;
        
        const row = button.closest('tr');
        if (row) {
            row.remove();
            updateTotalPurchases();
        }
    }

    function getPurchasesData() {
        const rows = document.querySelectorAll('#purchasesTable tbody tr');
        const purchasesData = [];

        rows.forEach(row => {
            const codeInput = row.querySelector('.code');
            const itemSearchInput = row.querySelector('.itemSearch');
            const priceInput = row.querySelector('.price');
            const quantityInput = row.querySelector('.quantity');

            // Only add if required fields are filled
            if (codeInput?.value && itemSearchInput?.value && priceInput?.value && quantityInput?.value) {
                purchasesData.push({
                    code: codeInput.value,
                    item_name: itemSearchInput.value,
                    batch_number: row.querySelector('.batchNumber')?.value || null,
                    price: parseFloat(priceInput.value) || 0,
                    purchase_price: parseFloat(row.querySelector('.purchasePrice')?.value) || 0,
                    quantity: parseFloat(quantityInput.value) || 0,
                    discount: parseFloat(row.querySelector('.discount')?.value) || 0,
                    exp_date: row.querySelector('.expDate')?.value || null,
                    total: parseFloat(row.querySelector('.total')?.value) || 0
                });
            }
        });

        return purchasesData;
    }

    async function savePurchase() {
        const saveButton = document.getElementById('saveButton');
        const originalButtonText = saveButton.innerHTML;
        saveButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
        saveButton.disabled = true;
        
        console.log('Save purchase triggered');
        
        const companyCode = document.getElementById('companyCode')?.value;
        if (!companyCode) {
            showError('Please select a company by searching with company code');
            saveButton.innerHTML = originalButtonText;
            saveButton.disabled = false;
            return;
        }

        const purchasesData = getPurchasesData();
        if (purchasesData.length === 0) {
            showError('Please add at least one valid item to the purchase.');
            saveButton.innerHTML = originalButtonText;
            saveButton.disabled = false;
            return;
        }

        const payload = {
            company_code: companyCode,
            company_name: document.getElementById('companyName')?.value || '',
            company_invoice: document.getElementById('companyInvoice')?.value || '',
            invoice_date: document.getElementById('invoiceDate')?.value || '',
            invoice_number: document.getElementById('invoiceNumber')?.value || '',
            address: document.getElementById('address')?.value || '',
            total_purchases: parseFloat(document.getElementById('totalPurchases')?.textContent) || 0,
            net_payable: parseFloat(document.getElementById('netPayable')?.value) || 0,
            prev_balance: parseFloat(document.getElementById('prevBalance')?.value) || 0,
            total_balance: parseFloat(document.getElementById('totalBalance')?.value) || 0,
            items: purchasesData,
        };

        try {
            console.log('Saving purchase:', payload);
            
            const response = await fetch('/purchases', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(payload)
            });

            const responseData = await response.json();
            
            if (!response.ok) {
                // Handle invoice number conflict specifically
                if (responseData.errors?.invoice_number) {
                    // Generate a new invoice number and retry
                    const randomId = Math.floor(Math.random() * 1000).toString().padStart(3, '0');
                    document.getElementById('invoiceNumber').value = `PUR-${new Date().getFullYear()}-${randomId}`;
                    showError('Invoice number conflict. Generated a new one. Please try saving again.');
                    saveButton.innerHTML = originalButtonText;
                    saveButton.disabled = false;
                    return;
                }
                
                // Handle other validation errors
                if (responseData.errors) {
                    let errorMessages = [];
                    for (const [field, messages] of Object.entries(responseData.errors)) {
                        errorMessages.push(...messages);
                    }
                    showError('Validation errors:<br>' + errorMessages.join('<br>'));
                    saveButton.innerHTML = originalButtonText;
                    saveButton.disabled = false;
                    return;
                }
                
                throw new Error(responseData.message || 'Failed to save purchase');
            }

            showSuccess('Purchase saved successfully!');
            clearForm();
            
        } catch (error) {
            console.error('Error saving purchase:', error);
            showError(error.message || 'An error occurred while saving the purchase.');
        } finally {
            saveButton.innerHTML = originalButtonText;
            saveButton.disabled = false;
        }
    }

    function clearForm() {
        const tbody = document.querySelector('#purchasesTable tbody');
        if (!tbody) return;

        // Keep only the first row
        const rows = tbody.querySelectorAll('tr');
        if (rows.length > 1) {
            for (let i = 1; i < rows.length; i++) {
                rows[i].remove();
            }
        }

        // Clear inputs in the first row
        const firstRow = tbody.querySelector('tr');
        if (firstRow) {
            firstRow.querySelectorAll('input').forEach(input => {
                if (!input.classList.contains('total')) {
                    input.value = '';
                } else {
                    input.value = '0.00';
                }
            });
        }

        // Clear other form fields
        ['companyCode', 'companyName', 'companyInvoice', 'address'].forEach(id => {
            const el = document.getElementById(id);
            if (el) el.value = '';
        });

        // Generate new invoice number
        const invoiceNumberInput = document.getElementById('invoiceNumber');
        if (invoiceNumberInput) {
            const randomId = Math.floor(Math.random() * 1000).toString().padStart(3, '0');
            invoiceNumberInput.value = `PUR-${new Date().getFullYear()}-${randomId}`;
        }

        const totalPurchasesEl = document.getElementById('totalPurchases');
        const netPayableEl = document.getElementById('netPayable');
        const prevBalanceEl = document.getElementById('prevBalance');
        const totalBalanceEl = document.getElementById('totalBalance');
        
        if (totalPurchasesEl) totalPurchasesEl.textContent = '0.00';
        if (netPayableEl) netPayableEl.value = '0.00';
        if (prevBalanceEl) prevBalanceEl.value = '0.00';
        if (totalBalanceEl) totalBalanceEl.value = '0.00';

        // Add focus to the company code input
        document.getElementById('companyCode')?.focus();
    }

    async function saveAndPrint() {
        await savePurchase();
        // Only print if save was successful
        printPurchase();
    }

    function printPurchase() {
        window.print();
    }

    function showSuccess(message) {
        Swal.fire({
            icon: 'success',
            title: 'Success',
            html: message,
            timer: 3000,
            showConfirmButton: false
        });
    }

    function showError(message) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            html: message
        });
    }

    // Initialize the first row
    document.addEventListener('DOMContentLoaded', () => {
        // Make sure the save button is properly connected
        const saveButton = document.getElementById('saveButton');
        if (saveButton) {
            saveButton.addEventListener('click', savePurchase);
        }

        const firstRow = document.querySelector('#purchasesTable tbody tr');
        if (firstRow) {
            attachRowEventListeners(firstRow);
        }
        
        // Set default exp date to 1 year from now for the first row
        const expDateInput = firstRow?.querySelector('.expDate');
        if (expDateInput) {
            const oneYearLater = new Date();
            oneYearLater.setFullYear(oneYearLater.getFullYear() + 1);
            expDateInput.valueAsDate = oneYearLater;
        }
        
        // Generate invoice number
        const invoiceNumberInput = document.getElementById('invoiceNumber');
        if (invoiceNumberInput && !invoiceNumberInput.value) {
            const randomId = Math.floor(Math.random() * 1000).toString().padStart(3, '0');
            invoiceNumberInput.value = `PUR-${new Date().getFullYear()}-${randomId}`;
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
</style>
@endsection