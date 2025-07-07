@extends('layouts.app')

@section('title', 'Edit Purchase Return')

@section('content')
<div class="container-fluid px-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white py-2">
            <h5 class="mb-0">
                <i class="fas fa-edit me-2"></i> Edit Purchase Return #{{ $purchaseReturn->return_number }}
            </h5>
        </div>
        <div class="card-body p-3">
            <form id="editReturnForm" method="POST" action="{{ route('purchase-returns.update', $purchaseReturn->id) }}">
                @csrf
                @method('PUT')
                
                <!-- Invoice Information Section -->
                <div class="row invoice-info mb-3">
                    <!-- Company Code Search -->
                    <div class="col-md-1 position-relative">
                        <label for="companyCode" class="small">Code</label>
                        <input type="text" id="companyCode" class="form-control form-control-sm" 
                               value="{{ $purchaseReturn->company->code ?? '' }}" 
                               placeholder="Search by Code..." 
                               oninput="debouncedSearchCompanyCode(this)" 
                               onkeydown="handleCompanyCodeSearchKeydown(event)">
                        <input type="hidden" id="companyId" name="company_id" value="{{ $purchaseReturn->company_id }}">
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
                        <input type="text" id="companyName" class="form-control form-control-sm" 
                               value="{{ $purchaseReturn->company->name ?? '' }}" readonly>
                    </div>

                    <!-- Reason -->
                    <div class="col-md-2">
                        <label for="reason" class="small">Reason</label>
                        <input type="text" name="reason" id="reason" class="form-control form-control-sm" 
                               value="{{ $purchaseReturn->reason }}" 
                               placeholder="Reason..." 
                               onkeydown="handleEnter(event, 'reason')">
                    </div>

                    <!-- Return Number -->
                    <div class="col-md-2">
                        <label for="returnNumber" class="small">Return #</label>
                        <input type="text" id="returnNumber" class="form-control form-control-sm" 
                               value="{{ $purchaseReturn->return_number }}" readonly>
                    </div>

                    <!-- Date -->
                    <div class="col-md-2">
                        <label for="returnDate" class="small">Date</label>
                        <input type="date" name="return_date" id="returnDate" class="form-control form-control-sm" 
                               value="{{ $purchaseReturn->return_date->format('Y-m-d') }}"
                               onkeydown="handleEnter(event, 'returnDate')">
                    </div>

                    <!-- Address -->
                    <div class="col-md-2">
                        <label for="address" class="small">Company Address</label>
                        <input type="text" id="address" class="form-control form-control-sm" 
                               value="{{ $purchaseReturn->company->address ?? '' }}" readonly>
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
                        @foreach($purchaseReturn->items as $index => $item)
                        <tr>
                            <td>
                                <div class="position-relative">
                                    <input type="text" name="items[{{ $index }}][code]" class="code form-control form-control-sm" 
                                           value="{{ $item->item->item_code ?? $item->item_id }}"
                                           placeholder="Search Item Code..." 
                                           oninput="debouncedSearchItemCode(this)" 
                                           onkeydown="handleItemCodeSearchKeydown(event, this)" 
                                           style="border: none;">
                                    <input type="hidden" name="items[{{ $index }}][item_id]" class="itemId" value="{{ $item->item_id }}">
                                    <input type="hidden" name="items[{{ $index }}][id]" value="{{ $item->id }}">
                                    <div class="itemCodeDropdown dropdown-menu" style="display: none;">
                                        <table class="table table-sm table-borderless mb-0">
                                            <thead>
                                                <tr>
                                                    <th class="small" style="width: 50%;">Code</th>
                                                    <th class="small" style="width: 50%;">Name</th>
                                                </tr>
                                            </thead>
                                            <tbody class="itemCodeDropdownBody small"></tbody>
                                        </table>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="position-relative">
                                    <input type="text" class="itemSearch form-control form-control-sm" 
                                           value="{{ $item->item->item_name ?? '' }}"
                                           placeholder="Search Item..." 
                                           oninput="debouncedSearchItems(this)" 
                                           onkeydown="handleItemSearchKeydown(event, this)" 
                                           style="border: none;">
                                    <div class="itemDropdown dropdown-menu" style="display: none;">
                                        <table class="table table-sm table-borderless mb-0">
                                            <thead>
                                                <tr>
                                                    <th class="small" style="width: 50%;">Item</th>
                                                    <th class="small" style="width: 50%;">Price</th>
                                                </tr>
                                            </thead>
                                            <tbody class="itemDropdownBody small"></tbody>
                                        </table>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <input type="text" name="items[{{ $index }}][batch_number]" class="batchNumber form-control form-control-sm" 
                                       value="{{ $item->batch_number }}" 
                                       style="border: none;"
                                       onkeydown="handleEnter(event, 'batchNumber')">
                            </td>
                            <td>
                                <input type="number" name="items[{{ $index }}][price]" class="price form-control form-control-sm" 
                                       value="{{ number_format($item->price, 2, '.', '') }}"
                                       step="0.01" min="0"
                                       onkeydown="handleEnter(event, 'price')"
                                       oninput="calculateTotal(this)" style="border: none;">
                            </td>
                            <td>
                                <input type="number" name="items[{{ $index }}][quantity]" class="quantity form-control form-control-sm" 
                                       value="{{ $item->quantity }}"
                                       step="1" min="1"
                                       onkeydown="handleEnter(event, 'quantity')"
                                       oninput="calculateTotal(this)" style="border: none;">
                            </td>
                            <td>
                                <input type="number" name="items[{{ $index }}][discount]" class="discount form-control form-control-sm" 
                                       value="{{ $item->discount }}"
                                       step="0.01" min="0" max="100"
                                       onkeydown="handleEnter(event, 'discount')"
                                       oninput="calculateTotal(this)" style="border: none;">
                            </td>
                            <td>
                                <input type="date" name="items[{{ $index }}][exp_date]" class="expDate form-control form-control-sm" 
                                       value="{{ $item->exp_date ? \Carbon\Carbon::parse($item->exp_date)->format('Y-m-d') : '' }}" 
                                       onkeydown="handleEnter(event, 'expDate')"
                                       style="border: none;">
                            </td>
                            <td>
                                <input type="number" name="items[{{ $index }}][total]" class="total form-control form-control-sm" 
                                       value="{{ number_format($item->total, 2, '.', '') }}" 
                                       readonly style="border: none;">
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger btn-sm" onclick="deleteRow(this)">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Buttons -->
                <div class="btn-group mt-3">
                    <button type="button" class="btn btn-primary btn-sm" id="updateButton">
                        <i class="fas fa-save"></i> Update
                    </button>
                    <button type="button" class="btn btn-warning btn-sm" onclick="addNewRow()">
                        <i class="fas fa-plus-circle"></i> New Item
                    </button>
                    <a href="{{ route('purchase-returns.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>

                <!-- Summary Section -->
                <div class="summary mt-3">
                    <strong>Return Details:</strong>
                    <p>Total Return: <span id="totalReturn">{{ number_format($purchaseReturn->total_return, 2) }}</span> USD</p>
                </div>

                <!-- Balance Section -->
                <div class="balance-section mt-3">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="netPayable" class="small">Net Payable</label>
                            <input type="number" id="netPayable" class="form-control form-control-sm" 
                                   value="{{ number_format($purchaseReturn->net_payable, 2, '.', '') }}" readonly>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Track the currently selected dropdown item
    let selectedIndex = -1;
    let currentActiveDropdown = null;
    let currentActiveDropdownBody = null;

    // Initialize all rows on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Calculate totals for all existing rows
        document.querySelectorAll('#returnsTable .price').forEach(input => {
            calculateTotal(input);
        });

        // Set up form submission
        document.getElementById('updateButton').addEventListener('click', function(e) {
            e.preventDefault();
            updateReturn();
        });

        // Initialize all rows with event listeners
        document.querySelectorAll('#returnsTable tbody tr').forEach(row => {
            attachRowEventListeners(row);
        });
    });

    // Debounce function to limit API calls
    function debounce(func, timeout = 300) {
        let timer;
        return (...args) => {
            clearTimeout(timer);
            timer = setTimeout(() => { func.apply(this, args); }, timeout);
        };
    }

    // Fetch companies by code
    async function searchCompanyCode(input) {
        if (!input) return;
        
        const query = input.value.trim();
        const dropdownBody = document.getElementById('companyCodeDropdownBody');
        if (!dropdownBody) return;
        
        dropdownBody.innerHTML = '';
        selectedIndex = -1;
        currentActiveDropdown = document.getElementById('companyCodeDropdown');
        currentActiveDropdownBody = dropdownBody;

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
                            setSelectedIndex(index, dropdownBody);
                        });
                        dropdownBody.appendChild(row);
                    });
                    currentActiveDropdown.style.display = 'block';
                } else {
                    currentActiveDropdown.style.display = 'none';
                }
            } catch (error) {
                console.error('Error fetching companies:', error);
                showError('Failed to fetch companies');
            }
        } else {
            currentActiveDropdown.style.display = 'none';
        }
    }

    const debouncedSearchCompanyCode = debounce(searchCompanyCode);

    function selectCompany(company) {
        if (!company) return;
        
        document.getElementById('companyCode').value = company.code || '';
        document.getElementById('companyName').value = company.name || '';
        document.getElementById('address').value = company.address || '';
        document.getElementById('companyId').value = company.id || '';
        currentActiveDropdown.style.display = 'none';
        
        document.getElementById('reason').focus();
    }

    // Fetch items by code
    async function searchItemCode(input) {
        if (!input) return;
        
        const query = input.value.trim();
        const row = input.closest('tr');
        if (!row) return;
        
        const dropdownBody = row.querySelector('.itemCodeDropdownBody');
        if (!dropdownBody) return;
        
        dropdownBody.innerHTML = '';
        selectedIndex = -1;
        currentActiveDropdown = row.querySelector('.itemCodeDropdown');
        currentActiveDropdownBody = dropdownBody;

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
                            <td>${item.item_code}</td>
                            <td>${item.item_name}</td>
                        `;
                        row.onclick = () => selectItemCode(item, input);
                        row.addEventListener('mouseover', () => {
                            setSelectedIndex(index, dropdownBody);
                        });
                        dropdownBody.appendChild(row);
                    });
                    currentActiveDropdown.style.display = 'block';
                } else {
                    currentActiveDropdown.style.display = 'none';
                }
            } catch (error) {
                console.error('Error fetching items:', error);
                showError('Failed to fetch items');
            }
        } else {
            if (currentActiveDropdown) {
                currentActiveDropdown.style.display = 'none';
            }
        }
    }

    const debouncedSearchItemCode = debounce(searchItemCode);

    function selectItemCode(item, input) {
        if (!item || !input) return;
        
        const row = input.closest('tr');
        if (!row) return;

        row.querySelector('.code').value = item.item_code || '';
        row.querySelector('.itemSearch').value = item.item_name || '';
        row.querySelector('.price').value = item.tp || 0;
        row.querySelector('.itemId').value = item.id;
        
        if (currentActiveDropdown) {
            currentActiveDropdown.style.display = 'none';
        }
        
        // Calculate total immediately
        calculateTotal(row.querySelector('.price'));
        row.querySelector('.batchNumber').focus();
    }

    // Fetch items by name
    async function searchItems(input) {
        if (!input) return;
        
        const query = input.value.trim();
        const row = input.closest('tr');
        if (!row) return;
        
        const dropdownBody = row.querySelector('.itemDropdownBody');
        if (!dropdownBody) return;
        
        dropdownBody.innerHTML = '';
        selectedIndex = -1;
        currentActiveDropdown = row.querySelector('.itemDropdown');
        currentActiveDropdownBody = dropdownBody;

        if (query.length > 0) {
            try {
                const response = await fetch(`/search-items-by-name?query=${query}`);
                if (!response.ok) throw new Error('Network response was not ok');
                const items = await response.json();

                if (items.length > 0) {
                    items.forEach((item, index) => {
                        const row = document.createElement('tr');
                        row.className = 'dropdown-item small';
                        row.innerHTML = `
                            <td>${item.item_name}</td>
                            <td>${item.tp}</td>
                        `;
                        row.onclick = () => selectItem(item, input);
                        row.addEventListener('mouseover', () => {
                            setSelectedIndex(index, dropdownBody);
                        });
                        dropdownBody.appendChild(row);
                    });
                    currentActiveDropdown.style.display = 'block';
                } else {
                    currentActiveDropdown.style.display = 'none';
                }
            } catch (error) {
                console.error('Error fetching items:', error);
                showError('Failed to fetch items');
            }
        } else {
            if (currentActiveDropdown) {
                currentActiveDropdown.style.display = 'none';
            }
        }
    }

    const debouncedSearchItems = debounce(searchItems);

    function selectItem(item, input) {
        if (!item || !input) return;
        
        const row = input.closest('tr');
        if (!row) return;

        row.querySelector('.code').value = item.item_code || '';
        row.querySelector('.itemSearch').value = item.item_name || '';
        row.querySelector('.price').value = item.tp || 0;
        row.querySelector('.itemId').value = item.id;
        
        if (currentActiveDropdown) {
            currentActiveDropdown.style.display = 'none';
        }
        
        // Calculate total immediately
        calculateTotal(row.querySelector('.price'));
        row.querySelector('.batchNumber').focus();
    }

    // Handle Enter key navigation
    function handleEnter(event, currentField) {
        if (event.key === 'Enter') {
            event.preventDefault();
            
            const navigationMap = {
                'reason': () => document.getElementById('returnDate').focus(),
                'returnDate': () => document.querySelector('#returnsTable tbody tr:first-child .code').focus(),
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

            if (currentField === 'reason' || currentField === 'returnDate') {
                navigationMap[currentField]();
            } else {
                const row = event.target.closest('tr');
                if (row && navigationMap[currentField]) {
                    navigationMap[currentField](row);
                }
            }
        }
    }

    function handleItemCodeSearchKeydown(event, input) {
        const row = input.closest('tr');
        const dropdownBody = row.querySelector('.itemCodeDropdownBody');
        
        if (event.key === 'ArrowDown' || event.key === 'ArrowUp' || event.key === 'Enter') {
            if (event.key === 'Enter') {
                if (selectedIndex >= 0 && dropdownBody && dropdownBody.children[selectedIndex]) {
                    // Select the highlighted item
                    dropdownBody.children[selectedIndex].click();
                    event.preventDefault();
                } else {
                    // Move to next field
                    handleEnter(event, 'code');
                }
            } else {
                handleDropdownNavigation(event, dropdownBody);
            }
        }
    }

    function handleItemSearchKeydown(event, input) {
        const row = input.closest('tr');
        const dropdownBody = row.querySelector('.itemDropdownBody');
        
        if (event.key === 'ArrowDown' || event.key === 'ArrowUp' || event.key === 'Enter') {
            if (event.key === 'Enter') {
                if (selectedIndex >= 0 && dropdownBody && dropdownBody.children[selectedIndex]) {
                    // Select the highlighted item
                    dropdownBody.children[selectedIndex].click();
                    event.preventDefault();
                } else {
                    // Move to next field
                    handleEnter(event, 'itemSearch');
                }
            } else {
                handleDropdownNavigation(event, dropdownBody);
            }
        }
    }

    function handleCompanyCodeSearchKeydown(event) {
        const dropdownBody = document.getElementById('companyCodeDropdownBody');
        
        if (event.key === 'ArrowDown' || event.key === 'ArrowUp' || event.key === 'Enter') {
            if (event.key === 'Enter') {
                if (selectedIndex >= 0 && dropdownBody && dropdownBody.children[selectedIndex]) {
                    // Select the highlighted item
                    dropdownBody.children[selectedIndex].click();
                    event.preventDefault();
                } else {
                    // Move to next field
                    handleEnter(event, 'companyCode');
                }
            } else {
                handleDropdownNavigation(event, dropdownBody);
            }
        }
    }

    function setSelectedIndex(index, dropdownBody) {
        if (!dropdownBody) return;
        
        const dropdownItems = dropdownBody.querySelectorAll('.dropdown-item');
        if (selectedIndex >= 0 && dropdownItems[selectedIndex]) {
            dropdownItems[selectedIndex].classList.remove('selected');
        }
        selectedIndex = index;
        if (selectedIndex >= 0 && dropdownItems[selectedIndex]) {
            dropdownItems[selectedIndex].classList.add('selected');
        }
    }

    function handleDropdownNavigation(event, dropdownBody) {
        if (!event || !dropdownBody) return;
        
        const dropdownItems = dropdownBody.querySelectorAll('.dropdown-item');
        if (dropdownItems.length === 0) return;

        if (event.key === 'ArrowDown') {
            event.preventDefault();
            setSelectedIndex((selectedIndex + 1) % dropdownItems.length, dropdownBody);
        } else if (event.key === 'ArrowUp') {
            event.preventDefault();
            setSelectedIndex((selectedIndex - 1 + dropdownItems.length) % dropdownItems.length, dropdownBody);
        }
    }

    function calculateTotal(input) {
        if (!input) return;
        
        const row = input.closest('tr');
        if (!row) return;

        // Get numeric values (remove any formatting)
        const price = parseFloat(row.querySelector('.price').value) || 0;
        const quantity = parseFloat(row.querySelector('.quantity').value) || 1;
        const discount = parseFloat(row.querySelector('.discount').value) || 0;
        
        const subtotal = price * quantity;
        const total = subtotal - (subtotal * (discount / 100));
        
        row.querySelector('.total').value = total.toFixed(2);
        updateTotalReturn();
    }

    function updateTotalReturn() {
        let total = 0;
        document.querySelectorAll('#returnsTable .total').forEach(input => {
            total += parseFloat(input.value) || 0;
        });
        
        // Display formatted number but store raw value
        document.getElementById('totalReturn').textContent = total.toLocaleString(undefined, {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
        
        document.getElementById('netPayable').value = total.toFixed(2);
    }

    function attachRowEventListeners(row) {
        if (!row) return;
        
        row.querySelectorAll('.code').forEach(codeInput => {
            codeInput.addEventListener('keydown', (event) => {
                handleItemCodeSearchKeydown(event, codeInput);
            });
        });

        row.querySelectorAll('.itemSearch').forEach(itemSearchInput => {
            itemSearchInput.addEventListener('keydown', (event) => {
                handleItemSearchKeydown(event, itemSearchInput);
            });
        });

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

        const rows = tbody.querySelectorAll('tr');
        const newIndex = rows.length;

        const newRow = document.createElement('tr');
        newRow.innerHTML = `
            <td>
                <div class="position-relative">
                    <input type="text" name="items[${newIndex}][code]" class="code form-control form-control-sm" 
                           placeholder="Search Item Code..." 
                           oninput="debouncedSearchItemCode(this)" 
                           onkeydown="handleItemCodeSearchKeydown(event, this)" 
                           style="border: none;">
                    <input type="hidden" name="items[${newIndex}][item_id]" class="itemId">
                    <div class="itemCodeDropdown dropdown-menu" style="display: none;">
                        <table class="table table-sm table-borderless mb-0">
                            <thead>
                                <tr>
                                    <th class="small" style="width: 50%;">Code</th>
                                    <th class="small" style="width: 50%;">Name</th>
                                </tr>
                            </thead>
                            <tbody class="itemCodeDropdownBody small"></tbody>
                        </table>
                    </div>
                </div>
            </td>
            <td>
                <div class="position-relative">
                    <input type="text" class="itemSearch form-control form-control-sm" 
                           placeholder="Search Item..." 
                           oninput="debouncedSearchItems(this)" 
                           onkeydown="handleItemSearchKeydown(event, this)" 
                           style="border: none;">
                    <div class="itemDropdown dropdown-menu" style="display: none;">
                        <table class="table table-sm table-borderless mb-0">
                            <thead>
                                <tr>
                                    <th class="small" style="width: 50%;">Item</th>
                                    <th class="small" style="width: 50%;">Price</th>
                                </tr>
                            </thead>
                            <tbody class="itemDropdownBody small"></tbody>
                        </table>
                    </div>
                </div>
            </td>
            <td>
                <input type="text" name="items[${newIndex}][batch_number]" class="batchNumber form-control form-control-sm" 
                       onkeydown="handleEnter(event, 'batchNumber')" style="border: none;">
            </td>
            <td>
                <input type="number" name="items[${newIndex}][price]" class="price form-control form-control-sm" 
                       step="0.01" min="0"
                       onkeydown="handleEnter(event, 'price')"
                       oninput="calculateTotal(this)" style="border: none;">
            </td>
            <td>
                <input type="number" name="items[${newIndex}][quantity]" class="quantity form-control form-control-sm" 
                       step="1" min="1"
                       onkeydown="handleEnter(event, 'quantity')"
                       oninput="calculateTotal(this)" style="border: none;">
            </td>
            <td>
                <input type="number" name="items[${newIndex}][discount]" class="discount form-control form-control-sm" 
                       step="0.01" min="0" max="100"
                       onkeydown="handleEnter(event, 'discount')"
                       oninput="calculateTotal(this)" style="border: none;">
            </td>
            <td>
                <input type="date" name="items[${newIndex}][exp_date]" class="expDate form-control form-control-sm" 
                       onkeydown="handleEnter(event, 'expDate')" style="border: none;">
            </td>
            <td>
                <input type="number" name="items[${newIndex}][total]" class="total form-control form-control-sm" 
                       value="0.00" readonly style="border: none;">
            </td>
            <td>
                <button type="button" class="btn btn-danger btn-sm" onclick="deleteRow(this)">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </td>
        `;
        
        tbody.appendChild(newRow);
        attachRowEventListeners(newRow);
        
        // Focus on code input of new row
        newRow.querySelector('.code').focus();
    }

    function deleteRow(button) {
        if (!button) return;
        
        const row = button.closest('tr');
        const rows = document.querySelectorAll('#returnsTable tbody tr');
        
        if (rows.length > 1) {
            row.remove();
            updateTotalReturn();
            
            // Reindex remaining rows
            document.querySelectorAll('#returnsTable tbody tr').forEach((row, index) => {
                row.querySelectorAll('input').forEach(input => {
                    const name = input.getAttribute('name');
                    if (name && name.includes('items')) {
                        input.setAttribute('name', name.replace(/\[\d+\]/, `[${index}]`));
                    }
                });
            });
        } else {
            showError('You must have at least one item in the return');
        }
    }

    function cleanEmptyRows() {
        const rows = document.querySelectorAll('#returnsTable tbody tr');
        rows.forEach(row => {
            const itemId = row.querySelector('.itemId')?.value;
            const quantity = row.querySelector('.quantity')?.value;
            const price = row.querySelector('.price')?.value;
            
            // If all fields are empty, remove the row
            if (!itemId && !quantity && !price) {
                row.remove();
            }
        });
        
        // Reindex remaining rows
        document.querySelectorAll('#returnsTable tbody tr').forEach((row, index) => {
            row.querySelectorAll('input').forEach(input => {
                const name = input.getAttribute('name');
                if (name && name.includes('items')) {
                    input.setAttribute('name', name.replace(/\[\d+\]/, `[${index}]`));
                }
            });
        });
    }

    function validateForm() {
        let isValid = true;
        const errors = [];
        const rows = document.querySelectorAll('#returnsTable tbody tr');
        
        // Check company is selected
        const companyId = document.getElementById('companyId');
        if (!companyId || !companyId.value) {
            errors.push('Please select a company');
            isValid = false;
        }
        
        // Track if we have at least one valid row
        let hasValidRows = false;
        
        rows.forEach((row, index) => {
            const itemIdInput = row.querySelector('.itemId');
            const quantityInput = row.querySelector('.quantity');
            const priceInput = row.querySelector('.price');
            
            // Reset row styling
            row.style.border = '';
            
            // Only validate rows that have at least one field filled
            const isRowEmpty = 
                (!itemIdInput || !itemIdInput.value) && 
                (!quantityInput || !quantityInput.value) && 
                (!priceInput || !priceInput.value);
            
            if (!isRowEmpty) {
                // This row has data, so validate it
                if (!itemIdInput || !itemIdInput.value) {
                    errors.push(`Row ${index + 1}: Item is required`);
                    isValid = false;
                    row.style.border = '1px solid red';
                }
                
                if (!quantityInput || !quantityInput.value || parseFloat(quantityInput.value) <= 0) {
                    errors.push(`Row ${index + 1}: Valid quantity is required (must be greater than 0)`);
                    isValid = false;
                    row.style.border = '1px solid red';
                }
                
                if (!priceInput || !priceInput.value || parseFloat(priceInput.value) <= 0) {
                    errors.push(`Row ${index + 1}: Valid price is required (must be greater than 0)`);
                    isValid = false;
                    row.style.border = '1px solid red';
                }
                
                hasValidRows = true;
            }
        });
        
        if (!hasValidRows) {
            errors.push('Please add at least one item');
            isValid = false;
        }
        
        if (!isValid) {
            showError(errors.join('<br>'));
        }
        
        return isValid;
    }

    async function updateReturn() {
        cleanEmptyRows();
        
        if (!validateForm()) return;
        
        const updateButton = document.getElementById('updateButton');
        const originalButtonText = updateButton.innerHTML;
        updateButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';
        updateButton.disabled = true;
        
        try {
            const form = document.getElementById('editReturnForm');
            const formData = new FormData(form);
            
            // Manually add items data since they're not in the form directly
            document.querySelectorAll('#returnsTable tbody tr').forEach((row, index) => {
                if (!row) return;
                
                const getValue = (selector) => {
                    const el = row.querySelector(selector);
                    return el ? el.value : '';
                };
                
                const getNumericValue = (selector) => {
                    const el = row.querySelector(selector);
                    if (!el) return 0;
                    return parseFloat(el.value) || 0;
                };
                
                // Only include rows that have an item_id
                const itemId = getValue('.itemId');
                if (itemId) {
                    formData.append(`items[${index}][id]`, getValue('input[name*="[id]"]'));
                    formData.append(`items[${index}][item_id]`, itemId);
                    formData.append(`items[${index}][batch_number]`, getValue('.batchNumber'));
                    formData.append(`items[${index}][price]`, getNumericValue('.price'));
                    formData.append(`items[${index}][quantity]`, getNumericValue('.quantity'));
                    formData.append(`items[${index}][discount]`, getNumericValue('.discount'));
                    formData.append(`items[${index}][exp_date]`, getValue('.expDate'));
                    formData.append(`items[${index}][total]`, getNumericValue('.total'));
                }
            });

            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            });

            // First check if the response is JSON
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                const text = await response.text();
                throw new Error(`Expected JSON response but got: ${text.substring(0, 100)}...`);
            }

            const data = await response.json();
            
            if (response.ok) {
                showSuccess(data.message || 'Purchase return updated successfully!');
                setTimeout(() => {
                    window.location.href = data.redirect_url || "{{ route('purchase-returns.index') }}";
                }, 1500);
            } else {
                if (data.errors) {
                    let errorMessages = [];
                    for (const [field, messages] of Object.entries(data.errors)) {
                        errorMessages.push(...messages);
                    }
                    showError('Validation errors:<br>' + errorMessages.join('<br>'));
                } else {
                    throw new Error(data.message || 'Failed to update purchase return');
                }
            }
        } catch (error) {
            console.error('Error updating purchase return:', error);
            showError('Error updating purchase return: ' + error.message);
        } finally {
            updateButton.innerHTML = originalButtonText;
            updateButton.disabled = false;
        }
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
    input[disabled] {
        background-color: #f8f9fa !important;
    }
    .form-control-sm {
        min-height: calc(1.5em + 0.5rem + 2px);
    }
    .table th, .table td {
        padding: 0.3rem;
    }
    tr[style*="border: 1px solid red"] {
        animation: blink 0.5s linear 3;
    }
    @keyframes blink {
        50% { border-color: transparent; }
    }
</style>
@endsection
@endsection