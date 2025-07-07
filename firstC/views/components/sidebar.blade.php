<div class="sidebar bg-dark text-white">
    <a href="#" class="d-flex justify-content-between align-items-center">
        <span class="sidebar-text">Home</span>
        <i class="fas fa-home"></i>
    </a>
    <a href="{{ route('items.index') }}" class="d-flex justify-content-between align-items-center">
        <span class="sidebar-text">Items</span>
        <i class="fas fa-list"></i>
    </a>
    <a href="#" onclick="toggleSubmenu('customerMenu')" class="d-flex justify-content-between align-items-center">
        <span class="sidebar-text">Customer</span>
        <i class="fas fa-chevron-right"></i>
    </a>
    <div class="submenu" id="customerMenu">
        <a href="{{ route('customers.index') }}" class="d-flex justify-content-between align-items-center">
            <span class="sidebar-text">New Customer</span>
            <i class="fas fa-plus"></i>
        </a>
        <a href="#" class="d-flex justify-content-between align-items-center">
            <span class="sidebar-text">Customer Voucher</span>
            <i class="fas fa-file-invoice"></i>
        </a>
    </div>
    <a href="#" onclick="toggleSubmenu('saleMenu')" class="d-flex justify-content-between align-items-center">
        <span class="sidebar-text">Sale</span>
        <i class="fas fa-chevron-right"></i>
    </a>
    <div class="submenu" id="saleMenu">
        <a href="{{ route('sales.index')}}" class="d-flex justify-content-between align-items-center">
            <span class="sidebar-text">New Invoice</span>
            <i class="fas fa-file-invoice-dollar"></i>
        </a>
        <a href="{{ route('sales-returns.index')}}" class="d-flex justify-content-between align-items-center">
            <span class="sidebar-text">Sale Return</span>
            <i class="fas fa-undo"></i>
        </a>
    </div>
    <a href="#" onclick="toggleSubmenu('purchaseMenu')" class="d-flex justify-content-between align-items-center">
        <span class="sidebar-text">Purchase</span>
        <i class="fas fa-chevron-right"></i>
    </a>
    <div class="submenu" id="purchaseMenu">
        <a href="{{ route('purchases.index')}}" class="d-flex justify-content-between align-items-center">
            <span class="sidebar-text">Add Stock</span>
            <i class="fas fa-plus"></i>
        </a>
        <a href="#" class="d-flex justify-content-between align-items-center">
            <span class="sidebar-text">Purchase Stock</span>
            <i class="fas fa-boxes"></i>
        </a>
        <a href="{{ route('purchase-returns.index')}}" class="d-flex justify-content-between align-items-center">
            <span class="sidebar-text">Purchase Return</span>
            <i class="fas fa-undo"></i>
        </a>
        <a href="#" class="d-flex justify-content-between align-items-center">
            <span class="sidebar-text">Payment Out</span>
            <i class="fas fa-money-bill-wave"></i>
        </a>
    </div>
    <a href="{{ route('employees.index') }}" class="d-flex justify-content-between align-items-center">
        <span class="sidebar-text">Employee</span>
        <i class="fas fa-users"></i>
    </a>
    <a href="#" class="d-flex justify-content-between align-items-center">
        <span class="sidebar-text">Expense</span>
        <i class="fas fa-dollar-sign"></i>
    </a>
    <a href="#" onclick="toggleSubmenu('ledgerMenu')" class="d-flex justify-content-between align-items-center">
        <span class="sidebar-text">Ledger</span>
        <i class="fas fa-chevron-right"></i>
    </a>
    <div class="submenu" id="ledgerMenu">
        <a href="#" class="d-flex justify-content-between align-items-center">
            <span class="sidebar-text">Stock Ledger</span>
            <i class="fas fa-book"></i>
        </a>
        <a href="#" class="d-flex justify-content-between align-items-center">
            <span class="sidebar-text">Customer Ledger</span>
            <i class="fas fa-book"></i>
        </a>
        <a href="#" class="d-flex justify-content-between align-items-center">
            <span class="sidebar-text">Company Ledger</span>
            <i class="fas fa-book"></i>
        </a>
    </div>
    
    <!-- Collapse button -->
    <div class="position-absolute bottom-0 start-0 p-3 w-100">
        <button class="btn btn-sm btn-outline-light w-100" onclick="toggleSidebar()">
            <i class="fas fa-chevron-left"></i> Collapse
        </button>
    </div>
</div>