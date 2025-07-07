<!-- resources/views/components/header.blade.php -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark" style="height: 60px;">
    <div class="container-fluid">
        <!-- Company Section -->
        <div class="d-flex align-items-center">
            <!-- Circular Logo Placeholder -->
            <div class="rounded-circle bg-light me-2" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-building text-dark"></i> <!-- Placeholder icon, replace with logo -->
            </div>
            <!-- Company Name -->
            <span class="navbar-brand mb-0 h1">Sigma POS</span>
        </div>

        <!-- Vertical Line Separator -->
        <div class="vr bg-white mx-3" style="height: 30px;"></div>

        <!-- Transaction Report Dropdown -->
        <ul class="navbar-nav me-auto">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Transaction Report</a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#">Sale</a></li>
                    <li><a class="dropdown-item" href="#">Purchase</a></li>
                    <li><a class="dropdown-item" href="#">Day Book</a></li>
                    <li><a class="dropdown-item" href="#">All Transaction</a></li>
                    <li><a class="dropdown-item" href="#">Profit And Loss</a></li>
                    <li><a class="dropdown-item" href="#">Bill Wise Report</a></li>
                    <li><a class="dropdown-item" href="#">Cash Flow</a></li>
                    <li><a class="dropdown-item" href="#">Balance Sheet</a></li>
                </ul>
            </li>
        </ul>

        <!-- Navbar Toggler for Mobile -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navbar Links -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <!-- Customer Report Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Customer Report</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">Customer Statement</a></li>
                        <li><a class="dropdown-item" href="#">Customer Wise Profit And Loss</a></li>
                        <li><a class="dropdown-item" href="#">All Customers</a></li>
                        <li><a class="dropdown-item" href="#">Customer Report By Item</a></li>
                        <li><a class="dropdown-item" href="#">Sale Purchase By Customer</a></li>
                        <li><a class="dropdown-item" href="#">Sale Purchase By Customer Group</a></li>
                        <li><a class="dropdown-item" href="#">Sale Purchase By HSN</a></li>
                    </ul>
                </li>
                <!-- Add other dropdowns similarly -->
            </ul>
            <span class="navbar-text">Welcome, User</span>
        </div>
    </div>
</nav>