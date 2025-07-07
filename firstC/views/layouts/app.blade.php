<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sigma POS - @yield('title')</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet">

    <!-- Add SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

    <!-- Custom Styles -->
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            padding-top: 60px; /* Added for fixed navbar */
        }
        .navbar {
            background-color: #343a40;
            color: white;
            height: 60px;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            display: flex;
            align-items: center;
            padding: 0 20px;
        }
        .sidebar {
            background-color: #343a40;
            color: white;
            width: 200px;
            height: calc(100vh - 60px);
            position: fixed;
            top: 60px;
            left: 0;
            overflow-y: auto;
            padding-top: 20px;
            transition: all 0.3s;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            display: block;
            padding: 10px 15px;
            transition: all 0.3s;
            border-left: 3px solid transparent;
        }
        .sidebar a:hover {
            background-color: #495057;
            border-left: 3px solid #fff;
        }
        .sidebar a i {
            width: 20px;
            text-align: center;
        }
        .submenu {
            padding-left: 20px;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out;
        }
        .submenu.show {
            max-height: 500px; /* Adjust based on your content */
        }
        .submenu a {
            font-size: 0.9rem;
            padding: 8px 15px 8px 25px;
        }
        .content {
            margin-left: 200px;
            padding: 20px;
            transition: margin-left 0.3s;
        }
        .sidebar.collapsed {
            width: 60px;
        }
        .sidebar.collapsed .sidebar-text,
        .sidebar.collapsed .submenu {
            display: none;
        }
        .sidebar.collapsed a {
            text-align: center;
            padding: 10px 5px;
        }
        .sidebar.collapsed i {
            margin-right: 0;
        }
        .content.expanded {
            margin-left: 60px;
        }
        /* Compact Form Styling */
        .compact-form .form-control {
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
            height: calc(1.6em + 0.75rem);
        }
        .compact-form .form-label {
            margin-bottom: 0.25rem;
            font-size: 0.875rem;
            font-weight: 500;
        }
        /* Select2 Customization */
        .select2-container .select2-selection--single {
            height: 38px !important;
            padding: 5px 10px;
        }
        .select2-container--bootstrap-5 .select2-dropdown {
            border-radius: 0.375rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
        .select2-container--bootstrap-5 .select2-selection--focus {
            border-color: #86b7fe;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }
        /* Navbar dropdown fixes */
        .navbar-nav .dropdown-menu {
            position: absolute;
        }
        /* Active menu item */
        .sidebar a.active {
            background-color: #495057;
            border-left: 3px solid #fff;
        }
    </style>
    
</head>
<body>
    <!-- Include Header -->
    @include('components.header')

    <!-- Include Sidebar -->
    @include('components.sidebar')

    <!-- Main Content -->
    <div class="content">
        @yield('content')
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- Global Scripts -->
    <script>
        // Initialize Select2 for all elements with 'select2' class
        $(document).ready(function() {
            $('.select2').select2({
                theme: 'bootstrap-5',
                placeholder: 'Select an option',
                allowClear: true,
                width: '100%'
            });

            // Reinitialize Select2 when modals are shown
            $('.modal').on('shown.bs.modal', function () {
                $(this).find('.select2').select2({
                    theme: 'bootstrap-5',
                    dropdownParent: $(this)
                });
            });

            // Initialize all Bootstrap tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });

            // Initialize all Bootstrap popovers
            var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
            var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
                return new bootstrap.Popover(popoverTriggerEl)
            });
        });

        // Sidebar submenu toggle function
        function toggleSubmenu(menuId) {
            event.preventDefault();
            const submenu = document.getElementById(menuId);
            submenu.classList.toggle('show');
            
            // Rotate icon if present
            const icon = event.currentTarget.querySelector('.fa-chevron-down, .fa-chevron-right');
            if (icon) {
                icon.classList.toggle('fa-chevron-down');
                icon.classList.toggle('fa-chevron-right');
            }
        }

        // Toggle sidebar collapse
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            const content = document.querySelector('.content');
            sidebar.classList.toggle('collapsed');
            content.classList.toggle('expanded');
        }
    </script>
    <!-- Add this before your closing </body> tag -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Page-specific Scripts -->
    @yield('scripts')
</body>
</html>