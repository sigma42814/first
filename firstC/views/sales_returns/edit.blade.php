@extends('layouts.app')

@section('title', 'Edit Sales Return')

@section('content')
<div class="container-fluid px-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white py-2">
            <h5 class="mb-0">
                <i class="fas fa-edit me-2"></i> Edit Sales Return
            </h5>
        </div>
        <div class="card-body p-3">
            <!-- Invoice Information Section -->
            <div class="row invoice-info mb-3">
                <!-- Customer Code Search -->
                <div class="col-md-1 position-relative">
                    <label for="customerCode" class="small">Code</label>
                    <input type="text" id="customerCode" class="form-control form-control-sm" 
                           value="{{ $salesReturn->customer->id }}" 
                           oninput="debouncedSearchCustomerCode(this)" 
                           onkeydown="handleCustomerCodeSearchKeydown(event)">
                    <div id="customerCodeDropdown" class="dropdown-menu" style="display: none;">
                        <table class="table table-sm table-borderless mb-0">
                            <thead>
                                <tr>
                                    <th class="small" style="width: 50%;">Code</th>
                                    <th class="small" style="width: 50%;">Name</th>
                                </tr>
                            </thead>
                            <tbody id="customerCodeDropdownBody" class="small"></tbody>
                        </table>
                    </div>
                </div>

                <!-- Customer Name -->
                <div class="col-md-3">
                    <label for="customerName" class="small">Customer Name</label>
                    <input type="text" id="customerName" class="form-control form-control-sm" 
                           value="{{ $salesReturn->customer->name }}" readonly>
                    <input type="hidden" id="customerId" name="customer_id" value="{{ $salesReturn->customer_id }}">
                </div>

                <!-- Customer Address -->
                <div class="col-md-2">
                    <label for="address" class="small">Address</label>
                    <input type="text" id="address" class="form-control form-control-sm" 
                           value="{{ $salesReturn->customer->address }}" readonly>
                </div>

                <!-- Username Search -->
                <div class="col-md-2 position-relative">
                    <label for="username" class="small">Username</label>
                    <input type="text" id="username" name="username" class="form-control form-control-sm" 
                           value="{{ $salesReturn->username }}"
                           placeholder="Search username..." 
                           oninput="debouncedSearchUsername(this)"
                           onkeydown="handleUsernameSearchKeydown(event)">
                    <div id="usernameDropdown" class="dropdown-menu" style="display: none;">
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

                <!-- Return Number -->
                <div class="col-md-1">
                    <label for="returnNumber" class="small">Return #</label>
                    <input type="text" id="returnNumber" class="form-control form-control-sm" 
                           value="{{ $salesReturn->return_number }}" readonly>
                </div>

                <!-- Date -->
                <div class="col-md-1">
                    <label for="returnDate" class="small">Date</label>
                    <input type="date" id="returnDate" class="form-control form-control-sm" 
                           value="{{ $salesReturn->return_date->format('Y-m-d') }}">
                </div>

                <!-- Reason -->
                <div class="col-md-2">
                    <label for="reason" class="small">Reason</label>
                    <input type="text" id="reason" name="reason" class="form-control form-control-sm" 
                           value="{{ $salesReturn->reason }}"
                           onkeydown="handleEnter(event, 'reason')">
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
                        <th style="width: 8%;">Dis 2</th>
                        <th style="width: 8%;">Bonus</th>
                        <th style="width: 12%;">Total</th>
                        <th style="width: 5%;">Delete</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($salesReturn->items as $item)
                    <tr>
                        <td>
                            <div class="position-relative">
                                <input type="text" name="code[]" class="code form-control form-control-sm" 
                                       value="{{ $item->item_id }}"
                                       placeholder="Search Item Code..." 
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
                                <input type="text" class="itemSearch form-control form-control-sm" 
                                       value="{{ $item->item->item_name }}"
                                       placeholder="Search Item..." 
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
                                   value="{{ $item->batch_number }}"
                                   onkeydown="handleEnter(event, 'batchNumber')" style="border: none;"></td>
                        <td><input type="number" name="price[]" class="price form-control form-control-sm" 
                                  value="{{ $item->price }}"
                                  oninput="calculateTotal(this)" 
                                  onkeydown="handleEnter(event, 'price')" style="border: none;"></td>
                        <td><input type="number" name="quantity[]" class="quantity form-control form-control-sm" 
                                  value="{{ $item->quantity }}"
                                  oninput="calculateTotal(this)" 
                                  onkeydown="handleEnter(event, 'quantity')" style="border: none;"></td>
                        <td><input type="number" name="discount[]" class="discount form-control form-control-sm" 
                                  value="{{ $item->discount }}"
                                  oninput="calculateTotal(this)" 
                                  onkeydown="handleEnter(event, 'discount')" style="border: none;"></td>
                        <td><input type="number" name="discount2[]" class="discount2 form-control form-control-sm" 
                                  value="{{ $item->discount2 }}"
                                  oninput="calculateTotal(this)" 
                                  onkeydown="handleEnter(event, 'discount2')" style="border: none;"></td>
                        <td><input type="number" name="bonus[]" class="bonus form-control form-control-sm" 
                                  value="{{ $item->bonus }}"
                                  oninput="calculateTotal(this)" 
                                  onkeydown="handleEnter(event, 'bonus')" style="border: none;"></td>
                        <td><input type="number" name="total[]" class="total form-control form-control-sm" 
                                  value="{{ $item->total }}" disabled style="border: none;"></td>
                        <td><button type="button" class="btn btn-danger btn-sm" onclick="deleteRow(this)"><i class="fas fa-trash-alt"></i></button></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Buttons -->
            <div class="btn-group mt-3">
                <button type="button" class="btn btn-primary btn-sm" id="saveButton" onclick="updateReturn({{ $salesReturn->id }})">
                    <i class="fas fa-save"></i> Update
                </button>
                <button type="button" class="btn btn-warning btn-sm" onclick="addNewRow()">
                    <i class="fas fa-plus-circle"></i> New
                </button>
                <button type="button" class="btn btn-success btn-sm" onclick="saveAndPrint({{ $salesReturn->id }})">
                    <i class="fas fa-print"></i> Save & Print
                </button>
            </div>

            <!-- Summary Section -->
            <div class="summary mt-3">
                <strong>Return Details:</strong>
                <p>Total Return: <span id="totalReturn">{{ number_format($salesReturn->total_return, 2) }}</span> USD</p>
            </div>

            <!-- Balance Section -->
            <div class="balance-section mt-3">
                <div class="row">
                    <div class="col-md-4">
                        <label for="netPayable" class="small">Net Payable</label>
                        <input type="number" id="netPayable" class="form-control form-control-sm" 
                               value="{{ number_format($salesReturn->net_payable, 2) }}" readonly>
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

    // Search customers by code
    async function searchCustomerCode(input) {
        const query = input.value.trim();
        const dropdownBody = document.getElementById('customerCodeDropdownBody');
        
        if (query.length > 0) {
            try {
                const response = await fetch(`/search-customers?query=${query}`);
                const customers = await response.json();

                dropdownBody.innerHTML = '';
                selectedIndex = -1;

                customers.forEach((customer, index) => {
                    const row = document.createElement('tr');
                    row.className = 'dropdown-item small';
                    row.innerHTML = `
                        <td>${customer.id}</td>
                        <td>${customer.name}</td>
                    `;
                    row.onclick = () => selectCustomer(customer);
                    row.addEventListener('mouseover', () => {
                        setSelectedIndex(index, 'customerCodeDropdownBody');
                    });
                    dropdownBody.appendChild(row);
                });

                document.getElementById('customerCodeDropdown').style.display = 'block';
            } catch (error) {
                console.error('Error:', error);
            }
        } else {
            document.getElementById('customerCodeDropdown').style.display = 'none';
        }
    }

    // Search employees by name
    async function searchUsername(input) {
        const query = input.value.trim();
        const dropdownBody = document.getElementById('usernameDropdownBody');
        
        if (query.length > 0) {
            try {
                const response = await fetch(`/search-employees?query=${query}`);
                const employees = await response.json();

                dropdownBody.innerHTML = '';
                selectedIndex = -1;

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

                document.getElementById('usernameDropdown').style.display = 'block';
            } catch (error) {
                console.error('Error:', error);
            }
        } else {
            document.getElementById('usernameDropdown').style.display = 'none';
        }
    }

    const debouncedSearchUsername = debounce(searchUsername);

    function selectUsername(employee) {
        document.getElementById('username').value = employee.name;
        document.getElementById('usernameDropdown').style.display = 'none';
        document.getElementById('reason').focus();
    }

    function handleUsernameSearchKeydown(event) {
        if (['ArrowDown', 'ArrowUp', 'Enter'].includes(event.key)) {
            handleDropdownNavigation(event, 'usernameDropdownBody');
        }
    }

    const debouncedSearchCustomerCode = debounce(searchCustomerCode);

    function selectCustomer(customer) {
        document.getElementById('customerCode').value = customer.id;
        document.getElementById('customerName').value = customer.name;
        document.getElementById('customerId').value = customer.id;
        document.getElementById('address').value = customer.address || 'N/A';
        document.getElementById('customerCodeDropdown').style.display = 'none';
        document.getElementById('username').focus();
    }

    // Handle Enter key navigation
    function handleEnter(event, currentField) {
        if (event.key === 'Enter') {
            event.preventDefault();
            
            const navigationMap = {
                'customerCode': () => document.getElementById('username').focus(),
                'username': () => document.getElementById('reason').focus(),
                'reason': () => document.querySelector('#returnsTable tbody tr:first-child .code').focus(),
                'code': (row) => row.querySelector('.itemSearch').focus(),
                'itemSearch': (row) => row.querySelector('.batchNumber').focus(),
                'batchNumber': (row) => row.querySelector('.price').focus(),
                'price': (row) => row.querySelector('.quantity').focus(),
                'quantity': (row) => row.querySelector('.discount').focus(),
                'discount': (row) => row.querySelector('.discount2').focus(),
                'discount2': (row) => row.querySelector('.bonus').focus(),
                'bonus': (row) => {
                    const nextRow = row.nextElementSibling;
                    if (nextRow) {
                        nextRow.querySelector('.code').focus();
                    } else {
                        addNewRow();
                    }
                }
            };

            if (currentField === 'customerCode' || currentField === 'username' || currentField === 'reason') {
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
                        <td>${item.price || 0}</td>
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
            row.querySelector('.price').value = item.price || 0;
            document.getElementById('itemCodeDropdown').style.display = 'none';
            row.querySelector('.batchNumber').focus();
        }
    }

    function selectItem(item, input) {
        const row = input.closest('tr');
        if (row) {
            row.querySelector('.code').value = item.id;
            row.querySelector('.itemSearch').value = item.item_name;
            row.querySelector('.price').value = item.price || 0;
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
        const discount2Input = row.querySelector('.discount2');
        const bonusInput = row.querySelector('.bonus');
        const totalInput = row.querySelector('.total');

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

    function handleCustomerCodeSearchKeydown(event) {
        if (['ArrowDown', 'ArrowUp', 'Enter'].includes(event.key)) {
            handleDropdownNavigation(event, 'customerCodeDropdownBody');
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

        ['price', 'quantity', 'discount', 'discount2', 'bonus'].forEach(field => {
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
            const discount2Input = row.querySelector('.discount2');
            const bonusInput = row.querySelector('.bonus');
            const totalInput = row.querySelector('.total');

            if (codeInput && codeInput.value && 
                itemSearchInput && priceInput && quantityInput && 
                totalInput) {
                items.push({
                    item_id: codeInput.value,
                    item_name: itemSearchInput.value,
                    batch_number: batchNumberInput ? batchNumberInput.value : '',
                    price: parseFloat(priceInput.value) || 0,
                    quantity: parseFloat(quantityInput.value) || 0,
                    discount: parseFloat(discountInput?.value) || 0,
                    discount2: parseFloat(discount2Input?.value) || 0,
                    bonus: parseFloat(bonusInput?.value) || 0,
                    total: parseFloat(totalInput.value) || 0
                });
            }
        });
        return items;
    }

    async function updateReturn(returnId) {
        if (isSaving) return; // Prevent multiple saves
        isSaving = true;
        
        const customerId = document.getElementById('customerId')?.value;
        if (!customerId) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Please select a customer first'
            });
            document.getElementById('customerCode')?.focus();
            isSaving = false;
            return;
        }

        const username = document.getElementById('username')?.value;
        if (!username) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Please enter a username'
            });
            document.getElementById('username')?.focus();
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
            saveButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';
        }

        try {
            const response = await fetch(`/sales-returns/${returnId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    customer_id: customerId,
                    username: username,
                    return_date: document.getElementById('returnDate')?.value || '',
                    reason: document.getElementById('reason')?.value || '',
                    items: items
                })
            });

            const data = await response.json();
            
            if (!response.ok) {
                throw new Error(data.message || 'Failed to update sales return');
            }

            // Show success message and redirect
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'Sales return updated successfully!',
                showConfirmButton: false,
                timer: 1500
            }).then(() => {
                window.location.href = data.redirect_url || '/sales-returns';
            });
            
        } catch (error) {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to update sales return: ' + error.message
            });
        } finally {
            isSaving = false;
            if (saveButton) {
                saveButton.disabled = false;
                saveButton.innerHTML = '<i class="fas fa-save"></i> Update';
            }
        }
    }

    async function saveAndPrint(returnId) {
        await updateReturn(returnId);
        // Printing logic can be added here
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', () => {
        const saveButton = document.getElementById('saveButton');
        if (saveButton) {
            saveButton.addEventListener('click', () => updateReturn({{ $salesReturn->id }}));
        }

        const firstRow = document.querySelector('#returnsTable tbody tr');
        if (firstRow) {
            attachRowEventListeners(firstRow);
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
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
</style>
@endsection