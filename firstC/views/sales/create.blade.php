@extends('layouts.app')

@section('title', 'Create Sale')

@section('content')
<div class="container-fluid px-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white py-2">
            <h5 class="mb-0">
                <i class="fas fa-shopping-cart me-2"></i> Create Sale
            </h5>
        </div>
        <div class="card-body p-3">
            <!-- Invoice Information Section -->
            <div class="row invoice-info mb-3">
                <!-- Code Search Component -->
                <div class="col-md-1 position-relative">
                    <label for="code" class="small">Code</label>
                    <input type="text" id="code" name="code" class="form-control form-control-sm" placeholder="Search by Code or Name..." oninput="debouncedSearchCode(this)" onkeydown="handleCodeSearchKeydown(event)">
                    <div id="codeDropdown" class="dropdown-menu" style="display: none;">
                        <table class="table table-sm table-borderless mb-0">
                            <thead>
                                <tr>
                                    <th class="small" style="width: 50%;">Code</th>
                                    <th class="small" style="width: 50%;">Name</th>
                                </tr>
                            </thead>
                            <tbody id="codeDropdownBody" class="small"></tbody>
                        </table>
                    </div>
                </div>

                <!-- Customer Name -->
                <div class="col-md-3">
                    <label for="customerName" class="small">Customer Name</label>
                    <input type="text" id="customerName" name="customer_name" class="form-control form-control-sm" readonly>
                </div>

                <!-- Date -->
                <div class="col-md-2">
                    <label for="invoiceDate" class="small">Date</label>
                    <input type="date" id="invoiceDate" name="invoice_date" class="form-control form-control-sm" value="{{ now()->toDateString() }}" readonly>
                </div>

                <!-- Username Search Component -->
                <div class="col-md-2 position-relative">
                    <label for="username" class="small">Username</label>
                    <input type="text" id="username" name="username" class="form-control form-control-sm" placeholder="Search Username..." oninput="debouncedSearchUsername(this)" onkeydown="handleUsernameSearchKeydown(event)">
                    <div id="usernameDropdown" class="dropdown-menu" style="display: none; width: 300px;">
                        <table class="table table-sm table-borderless mb-0">
                            <thead>
                                <tr>
                                    <th class="small" style="width: 50%;">Code</th>
                                    <th class="small" style="width: 50%;">Name</th>
                                </tr>
                            </thead>
                            <tbody id="usernameDropdownBody" class="small"></tbody>
                        </table>
                    </div>
                </div>

                <!-- Invoice Number -->
                <div class="col-md-2">
                    <label for="invoiceNumber" class="small">Invoice Number</label>
                    <input type="text" id="invoiceNumber" name="invoice_number" class="form-control form-control-sm" placeholder="Auto-generated" readonly>
                </div>

                <!-- Address -->
                <div class="col-md-2">
                    <label for="address" class="small">Address</label>
                    <input type="text" id="address" name="address" class="form-control form-control-sm" readonly>
                </div>
            </div>

            <!-- Sales Table -->
            <table id="salesTable" class="table table-bordered table-sm">
                <thead>
                    <tr>
                        <th style="width: 10%;">Code</th>
                        <th style="width: 25%;">Item</th>
                        <th style="width: 12%;">Batch Number</th>
                        <th style="width: 10%;">Price</th>
                        <th style="width: 8%;">Quantity</th>
                        <th style="width: 8%;">Dis 1</th>
                        <th style="width: 8%;">Dis 2</th>
                        <th style="width: 8%;">Bonus</th>
                        <th style="width: 12%;">Total</th>
                        <th style="width: 5%;">Delete</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <div class="position-relative">
                                <input type="text" name="code[]" class="code form-control form-control-sm" placeholder="Search Item Code..." oninput="debouncedSearchItemCode(this)" onkeydown="handleItemCodeSearchKeydown(event)" style="border: none;">
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
                                <input type="text" class="form-control form-control-sm itemSearch" placeholder="Search Item..." oninput="debouncedSearchItems(this)" onkeydown="handleItemSearchKeydown(event)" style="border: none;">
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
                        <td><input type="text" name="batch_number[]" class="batchNumber form-control form-control-sm" style="border: none;"></td>
                        <td><input type="number" name="price[]" class="price form-control form-control-sm" onkeydown="handleEnter(event, 'quantity')" oninput="calculateTotal(this)" style="border: none;"></td>
                        <td><input type="number" name="quantity[]" class="quantity form-control form-control-sm" onkeydown="handleEnter(event, 'discount')" oninput="calculateTotal(this)" style="border: none;"></td>
                        <td><input type="number" name="discount[]" class="discount form-control form-control-sm" onkeydown="handleEnter(event, 'discount2')" oninput="calculateTotal(this)" style="border: none;"></td>
                        <td><input type="number" name="discount2[]" class="discount2 form-control form-control-sm" onkeydown="handleEnter(event, 'bonus')" oninput="calculateTotal(this)" style="border: none;"></td>
                        <td><input type="number" name="bonus[]" class="bonus form-control form-control-sm" onkeydown="handleEnter(event, 'newRow')" oninput="calculateTotal(this)" style="border: none;"></td>
                        <td><input type="number" name="total[]" class="total form-control form-control-sm" disabled style="border: none;"></td>
                        <td><button type="button" class="btn btn-danger btn-sm" onclick="deleteRow(this)"><i class="fas fa-trash-alt"></i></button></td>
                    </tr>
                </tbody>
            </table>

            <!-- Buttons -->
            <div class="btn-group mt-3">
                <button type="button" class="btn btn-primary btn-sm" onclick="saveSale()">
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
                <strong>Sales Details:</strong>
                <p>Total Sales: <span id="totalSales">0</span> USD</p>
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

    // Fetch customers by code or name
    async function searchCode(input) {
        if (!input) return;
        
        const query = input.value.trim();
        const dropdownBody = document.getElementById('codeDropdownBody');
        if (!dropdownBody) return;
        
        dropdownBody.innerHTML = '';
        selectedIndex = -1;

        if (query.length > 0) {
            try {
                const response = await fetch(`/search-customers?query=${query}`);
                if (!response.ok) throw new Error('Network response was not ok');
                const customers = await response.json();

                if (customers.length > 0) {
                    customers.forEach((customer, index) => {
                        const row = document.createElement('tr');
                        row.className = 'dropdown-item small';
                        row.innerHTML = `
                            <td>${customer.id}</td>
                            <td>${customer.name}</td>
                        `;
                        row.onclick = () => selectCode(customer);
                        row.addEventListener('mouseover', () => {
                            setSelectedIndex(index, 'codeDropdownBody');
                        });
                        dropdownBody.appendChild(row);
                    });
                    const dropdown = document.getElementById('codeDropdown');
                    if (dropdown) dropdown.style.display = 'block';
                } else {
                    const dropdown = document.getElementById('codeDropdown');
                    if (dropdown) dropdown.style.display = 'none';
                }
            } catch (error) {
                console.error('Error fetching customers:', error);
            }
        } else {
            const dropdown = document.getElementById('codeDropdown');
            if (dropdown) dropdown.style.display = 'none';
        }
    }

    const debouncedSearchCode = debounce(searchCode);

    function selectCode(customer) {
        if (!customer) return;
        
        const codeInput = document.getElementById('code');
        const customerNameInput = document.getElementById('customerName');
        const addressInput = document.getElementById('address');
        const dropdown = document.getElementById('codeDropdown');
        const usernameInput = document.getElementById('username');
        
        if (codeInput) codeInput.value = customer.id || '';
        if (customerNameInput) customerNameInput.value = customer.name || '';
        if (addressInput) addressInput.value = customer.address || '';
        if (dropdown) dropdown.style.display = 'none';
        if (usernameInput) usernameInput.focus();
    }

    // Fetch employees by code or name
    async function searchUsername(input) {
        if (!input) return;
        
        const query = input.value.trim();
        const dropdownBody = document.getElementById('usernameDropdownBody');
        if (!dropdownBody) return;
        
        dropdownBody.innerHTML = '';
        selectedIndex = -1;

        if (query.length > 0) {
            try {
                const response = await fetch(`/search-employees?query=${query}`);
                if (!response.ok) throw new Error('Network response was not ok');
                const employees = await response.json();

                if (employees.length > 0) {
                    employees.forEach((employee, index) => {
                        const row = document.createElement('tr');
                        row.className = 'dropdown-item small';
                        row.innerHTML = `
                            <td>${employee.id}</td>
                            <td>${employee.name}</td>
                        `;
                        row.onclick = () => selectUsername(employee);
                        row.addEventListener('mouseover', () => {
                            setSelectedIndex(index, 'usernameDropdownBody');
                        });
                        dropdownBody.appendChild(row);
                    });
                    const dropdown = document.getElementById('usernameDropdown');
                    if (dropdown) dropdown.style.display = 'block';
                } else {
                    const dropdown = document.getElementById('usernameDropdown');
                    if (dropdown) dropdown.style.display = 'none';
                }
            } catch (error) {
                console.error('Error fetching employees:', error);
            }
        } else {
            const dropdown = document.getElementById('usernameDropdown');
            if (dropdown) dropdown.style.display = 'none';
        }
    }

    const debouncedSearchUsername = debounce(searchUsername);

    function selectUsername(employee) {
        if (!employee) return;
        
        const usernameInput = document.getElementById('username');
        const dropdown = document.getElementById('usernameDropdown');
        const firstCodeInput = document.querySelector('.code');
        
        if (usernameInput) usernameInput.value = employee.name || '';
        if (dropdown) dropdown.style.display = 'none';
        if (firstCodeInput) firstCodeInput.focus();
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
        const dropdown = document.getElementById('itemCodeDropdown');
        
        if (codeInput) codeInput.value = item.id || '';
        if (itemSearchInput) itemSearchInput.value = item.item_name || '';
        if (priceInput) {
            priceInput.value = item.tp || 0;
            priceInput.focus();
            calculateTotal(priceInput);
        }
        if (dropdown) dropdown.style.display = 'none';
    }

    function calculateTotal(input) {
        if (!input) return;
        
        const row = input.closest('tr');
        if (!row) return;

        const priceInput = row.querySelector('.price');
        const quantityInput = row.querySelector('.quantity');
        const discountInput = row.querySelector('.discount');
        const discount2Input = row.querySelector('.discount2');
        const bonusInput = row.querySelector('.bonus');
        const totalInput = row.querySelector('.total');

        // Exit if required fields are missing
        if (!priceInput || !quantityInput || !totalInput) return;

        const price = parseFloat(priceInput.value) || 0;
        const quantity = parseFloat(quantityInput.value) || 0;
        const discount = parseFloat(discountInput?.value) || 0;
        const discount2 = parseFloat(discount2Input?.value) || 0;
        const bonus = parseFloat(bonusInput?.value) || 0;

        const subtotal = price * quantity;
        const afterFirstDiscount = subtotal * (1 - discount / 100);
        const afterSecondDiscount = afterFirstDiscount * (1 - discount2 / 100);
        const finalTotal = afterSecondDiscount - bonus;
        
        totalInput.value = finalTotal.toFixed(2);
        updateTotalSales();
    }

    function updateTotalSales() {
        let total = 0;
        const rows = document.querySelectorAll('#salesTable tbody tr');
        
        rows.forEach(row => {
            const totalInput = row.querySelector('.total');
            if (totalInput) {
                const rowTotal = parseFloat(totalInput.value) || 0;
                total += rowTotal;
            }
        });
        
        const totalSalesElement = document.getElementById('totalSales');
        const netPayableElement = document.getElementById('netPayable');
        const totalBalanceElement = document.getElementById('totalBalance');
        
        if (totalSalesElement) totalSalesElement.textContent = total.toFixed(2);
        if (netPayableElement) netPayableElement.value = total.toFixed(2);
        if (totalBalanceElement) totalBalanceElement.value = total.toFixed(2);
    }

    function handleEnter(event, nextField) {
        if (!event) return;
        
        if (event.key === 'Enter') {
            event.preventDefault();
            const currentInput = event.target;

            if (nextField === 'newRow') {
                addNewRow();
            } else {
                const currentRow = currentInput.closest('tr');
                if (currentRow) {
                    const nextInput = currentRow.querySelector(`.${nextField}`);
                    if (nextInput) {
                        nextInput.focus();
                    }
                }
            }
        }
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

    function handleCodeSearchKeydown(event) {
        if (event.key === 'ArrowDown' || event.key === 'ArrowUp' || event.key === 'Enter') {
            handleDropdownNavigation(event, 'codeDropdownBody');
        }
    }

    function handleUsernameSearchKeydown(event) {
        if (event.key === 'ArrowDown' || event.key === 'ArrowUp' || event.key === 'Enter') {
            handleDropdownNavigation(event, 'usernameDropdownBody');
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
        ['price', 'quantity', 'discount', 'discount2', 'bonus'].forEach(field => {
            const input = row.querySelector(`.${field}`);
            if (input) {
                input.addEventListener('input', () => calculateTotal(input));
            }
        });
    }

    function addNewRow() {
        const tbody = document.querySelector('#salesTable tbody');
        if (!tbody) return;

        const firstRow = tbody.querySelector('tr');
        if (!firstRow) return;

        const newRow = firstRow.cloneNode(true);
        
        // Clear all inputs in the new row
        newRow.querySelectorAll('input').forEach(input => {
            if (input.type !== 'button') input.value = '';
            if (input.classList.contains('total')) input.value = '0.00';
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
            updateTotalSales();
        }
    }

    function getSalesData() {
        const rows = document.querySelectorAll('#salesTable tbody tr');
        const salesData = [];

        rows.forEach(row => {
            const codeInput = row.querySelector('.code');
            const itemSearchInput = row.querySelector('.itemSearch');
            const priceInput = row.querySelector('.price');
            const quantityInput = row.querySelector('.quantity');

            // Only add if required fields are filled
            if (codeInput?.value && itemSearchInput?.value && priceInput?.value && quantityInput?.value) {
                salesData.push({
                    code: codeInput.value,
                    item_name: itemSearchInput.value,
                    batch_number: row.querySelector('.batchNumber')?.value || null,
                    price: parseFloat(priceInput.value) || 0,
                    quantity: parseFloat(quantityInput.value) || 0,
                    discount: parseFloat(row.querySelector('.discount')?.value) || 0,
                    discount2: parseFloat(row.querySelector('.discount2')?.value) || 0,
                    bonus: parseFloat(row.querySelector('.bonus')?.value) || 0,
                    total: parseFloat(row.querySelector('.total')?.value) || 0
                });
            }
        });

        return salesData;
    }

    async function saveSale() {
        const salesData = getSalesData();
        if (salesData.length === 0) {
            alert('Please add at least one valid item to the sale.');
            return;
        }

        const payload = {
            customer_id: document.getElementById('code')?.value || '',
            invoice_date: document.getElementById('invoiceDate')?.value || '',
            invoice_number: document.getElementById('invoiceNumber')?.value || '',
            username: document.getElementById('username')?.value || '',
            total_sales: parseFloat(document.getElementById('totalSales')?.textContent) || 0,
            net_payable: parseFloat(document.getElementById('netPayable')?.value) || 0,
            prev_balance: parseFloat(document.getElementById('prevBalance')?.value) || 0,
            total_balance: parseFloat(document.getElementById('totalBalance')?.value) || 0,
            items: salesData,
        };

        try {
            const response = await fetch('/save-sale', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                },
                body: JSON.stringify(payload)
            });

            const responseData = await response.json();
            
            if (response.ok) {
                alert('Sale saved successfully!');
                clearForm();
                return; // Exit after success
            } else {
                throw new Error(responseData.message || 'Failed to save sale');
            }
        } catch (error) {
            console.error('Error saving sale:', error);
            alert('An error occurred while saving the sale. Please check console for details.');
        }
    }

    function clearForm() {
        const tbody = document.querySelector('#salesTable tbody');
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
        ['code', 'customerName', 'address', 'username', 'invoiceNumber'].forEach(id => {
            const el = document.getElementById(id);
            if (el) el.value = '';
        });

        const totalSalesEl = document.getElementById('totalSales');
        const netPayableEl = document.getElementById('netPayable');
        const prevBalanceEl = document.getElementById('prevBalance');
        const totalBalanceEl = document.getElementById('totalBalance');
        
        if (totalSalesEl) totalSalesEl.textContent = '0.00';
        if (netPayableEl) netPayableEl.value = '0.00';
        if (prevBalanceEl) prevBalanceEl.value = '0.00';
        if (totalBalanceEl) totalBalanceEl.value = '0.00';

        // Add focus to the first code input
        document.querySelector('.code')?.focus();
    }

    async function saveAndPrint() {
        await saveSale();
        printSale();
    }

    function printSale() {
        window.print();
    }

    // Initialize the first row
    document.addEventListener('DOMContentLoaded', () => {
        const firstRow = document.querySelector('#salesTable tbody tr');
        if (firstRow) {
            attachRowEventListeners(firstRow);
        }
        
        // Generate invoice number
        const invoiceNumberInput = document.getElementById('invoiceNumber');
        if (invoiceNumberInput && !invoiceNumberInput.value) {
            const randomId = Math.floor(Math.random() * 1000).toString().padStart(3, '0');
            invoiceNumberInput.value = `INV-${new Date().getFullYear()}-${randomId}`;
        }
    });
</script>

<style>
    .dropdown-menu {
        width: 300px; /* Smaller dropdown */
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
        padding: 5px 10px; /* Add padding for better visibility */
    }
    .dropdown-item:hover {
        background-color: #f8f9fa;
    }
    .dropdown-item.selected {
        background-color: #222222; /* Highlight color */
        color: white;
    }
    .small {
        font-size: 0.875rem;
    }
</style>
@endsection