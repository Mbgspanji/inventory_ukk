<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Admin - @yield('title')</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            --primary-navy: #0A1930;
            --secondary-navy: #112240;
            --accent-glass: rgba(255, 255, 255, 0.05);
            --text-main: #E6F1FF;
            --text-muted: #8892B0;
        }

        /* Ambient Glow Effect */
        .ambient-glow {
            position: absolute;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(59, 130, 246, 0.15) 0%, rgba(0, 0, 0, 0) 70%);
            border-radius: 50%;
            top: -150px;
            left: -150px;
            z-index: -1;
            pointer-events: none;
        }

        .ambient-glow-right {
            position: absolute;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(139, 92, 246, 0.15) 0%, rgba(0, 0, 0, 0) 70%);
            border-radius: 50%;
            bottom: -100px;
            right: -100px;
            z-index: -1;
            pointer-events: none;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #050b14;
            color: #e2e8f0;
            margin: 0;
            display: flex;
            height: 100vh;
            overflow: hidden;
            position: relative;
        }

        .sidebar {
            width: 250px;
            background-color: rgba(3, 6, 10, 0.8);
            border-right: 1px solid rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            color: var(--text-main);
            display: flex;
            flex-direction: column;
            transition: all 0.3s;
            z-index: 20;
        }

        .sidebar-header {
            padding: 20px;
            text-align: center;
            font-size: 1.5rem;
            font-weight: 700;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.05), rgba(255, 255, 255, 0));
        }

        .nav-items {
            flex: 1;
            padding: 20px 0;
            overflow-y: auto;
        }

        .nav-item {
            padding: 12px 20px;
            display: block;
            color: var(--text-muted);
            text-decoration: none;
            transition: all 0.2s;
            font-weight: 500;
        }

        .nav-item:hover,
        .nav-item.active {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.05);
            border-left: 4px solid #3b82f6;
        }

        .nav-item i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            background-color: transparent;
            overflow-y: auto;
        }

        .topbar {
            height: 60px;
            background: rgba(255, 255, 255, 0.02);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            padding: 0 20px;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            z-index: 10;
        }

        .topbar .btn-light {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #fff;
        }

        .topbar .btn-light:hover {
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
        }

        .topbar .dropdown-menu {
            background: rgba(15, 21, 35, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .content-area {
            padding: 30px;
            flex: 1;
        }

        /* Card & Table Styling - Glassmorphism */
        .card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.05) !important;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background: rgba(255, 255, 255, 0.02) !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05) !important;
        }

        .table-responsive {
            background: transparent;
            border-radius: 12px;
            padding: 20px;
        }

        .table {
            border-collapse: collapse;
            color: #e2e8f0;
        }

        .table th {
            background-color: rgba(255, 255, 255, 0.02) !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05) !important;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
            color: #8892B0;
            font-weight: 600;
        }

        .table td {
            vertical-align: middle;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05) !important;
            color: #e2e8f0;
        }

        .table-striped>tbody>tr:nth-of-type(odd)>* {
            background-color: rgba(255, 255, 255, 0.01) !important;
            color: #e2e8f0;
        }

        .table-hover>tbody>tr:hover>* {
            background-color: rgba(255, 255, 255, 0.03) !important;
            color: #fff;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            color: #fff;
        }

        /* Select2 Dark/Glass Theme Overrides */
        .select2-container--bootstrap-5 .select2-selection {
            background-color: rgba(255, 255, 255, 0.05) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            color: #fff !important;
        }

        .select2-container--bootstrap-5 .select2-selection__rendered {
            color: #fff !important;
        }

        .select2-container--bootstrap-5 .select2-dropdown {
            background: rgba(15, 21, 35, 0.95) !important;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
        }

        .select2-container--bootstrap-5 .select2-results__option {
            color: #ccc !important;
            background-color: transparent !important;
        }

        .select2-container--bootstrap-5 .select2-results__option[aria-selected="true"] {
            background-color: rgba(59, 130, 246, 0.2) !important;
            color: #fff !important;
        }

        .select2-container--bootstrap-5 .select2-results__option--highlighted {
            background-color: rgba(255, 255, 255, 0.1) !important;
            color: #fff !important;
        }

        .select2-search__field {
            background-color: rgba(255, 255, 255, 0.05) !important;
            color: #fff !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
        }
    </style>
</head>

<body>
    <div class="ambient-glow"></div>
    <div class="ambient-glow-right"></div>
    @auth
        <div class="sidebar">
            <div class="sidebar-header">
                <i class="fa-solid fa-boxes-stacked"></i> INVENTORY
            </div>
            <div class="nav-items">
                <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-chart-pie"></i> Dashboard
                </a>
                <a href="{{ route('categories.index') }}"
                    class="nav-item {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-tags"></i> Categories
                </a>
                <a href="{{ route('items.index') }}" class="nav-item {{ request()->routeIs('items.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-box"></i> Items
                </a>
                @can('operator')
                    <a href="{{ route('lendings.index') }}"
                        class="nav-item {{ request()->routeIs('lendings.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-hand-holding-hand"></i> Lending
                    </a>
                @endcan

                @auth
                    <a href="{{ route('items.index') }}" class="nav-item {{ request()->routeIs('items.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-box"></i> Items
                    </a>

                    <div class="nav-item fw-bold mt-3"
                        style="color: #64ffda; font-size: 0.8rem; letter-spacing: 1px; pointer-events: none;">ADMINISTRATION
                    </div>
                    <a href="{{ route('users.index') }}" class="nav-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-users"></i> Users
                    </a>
                @endauth
            </div>
        </div>
    @endauth

    <div class="main-content" style="{{ !auth()->check() ? 'width: 100%;' : '' }}">
        @auth
            <div class="topbar">
                <div class="dropdown">
                    <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="fa-solid fa-user-circle"></i> {{ auth()->user()->name }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('profile') }}">Profile</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button class="dropdown-item text-danger" type="submit">Logout</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        @endauth

        <div class="content-area">
            @yield('content')
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                theme: 'bootstrap-5'
            });

            // SweetAlert2 Global Configuration
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });

            @if (session('success'))
                Toast.fire({
                    icon: 'success',
                    title: "{{ session('success') }}"
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: "{{ session('error') }}",
                    confirmButtonColor: '#0A1930'
                });
            @endif



            // Global Delete Confirmation Helper
            window.confirmDelete = function(formId, message = 'You won\'t be able to revert this!') {
                Swal.fire({
                    title: 'Are you sure?',
                    text: message,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#0A1930',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById(formId).submit();
                    }
                });
                return false;
            };

            // Global Generic Confirmation Helper
            window.confirmAction = function(formId, title, text, icon, confirmText) {
                Swal.fire({
                    title: title,
                    text: text,
                    icon: icon,
                    showCancelButton: true,
                    confirmButtonColor: '#198754',
                    cancelButtonColor: '#0A1930',
                    confirmButtonText: confirmText
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById(formId).submit();
                    }
                });
                return false;
            };
        });
    </script>
    @yield('scripts')
</body>

</html>
