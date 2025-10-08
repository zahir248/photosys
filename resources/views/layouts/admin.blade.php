<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SuperAdmin Dashboard') - Photo Management System</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        body {
            background-color: #f8f9fa;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        }
        
        .admin-layout {
            display: flex;
            min-height: 100vh;
        }
        
        .sidebar {
            width: 250px;
            background: linear-gradient(135deg, #2c3e50, #34495e);
            color: white;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 1000;
            transition: all 0.3s ease;
        }
        
        
        .sidebar-nav {
            padding: 1rem 0;
        }
        
        .nav-item {
            margin: 0.25rem 0;
        }
        
        .nav-link {
            color: #bdc3c7;
            padding: 0.75rem 1.5rem;
            display: flex;
            align-items: center;
            text-decoration: none;
            transition: all 0.3s ease;
            border-radius: 0;
        }
        
        .nav-link:hover {
            color: white;
            background-color: rgba(255,255,255,0.1);
        }
        
        .nav-link.active {
            color: white;
            background-color: #007bff;
        }
        
        .nav-link i {
            width: 20px;
            margin-right: 0.75rem;
        }
        
        .main-content {
            flex: 1;
            margin-left: 250px;
            background-color: #f8f9fa;
            min-height: 100vh;
        }
        
        .top-navbar {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border-bottom: 1px solid #e9ecef;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            position: relative;
        }
        
        .top-navbar::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #007bff, #0056b3);
        }
        
        .content-area {
            padding: 2rem;
        }
        
        .page-header {
            margin-bottom: 2rem;
        }
        
        .page-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: #2c3e50;
            margin: 0;
        }
        
        .page-subtitle {
            color: #7f8c8d;
            font-size: 1rem;
            margin: 0.5rem 0 0 0;
        }
        
        .admin-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border: 1px solid #e9ecef;
        }
        
        .admin-card-header {
            padding: 1.5rem;
            border-bottom: 1px solid #e9ecef;
            background-color: #f8f9fa;
        }
        
        .admin-card-body {
            padding: 1.5rem;
        }
        
        .btn-admin {
            border-radius: 8px;
            font-weight: 500;
            padding: 0.5rem 1rem;
        }
        
        .btn-primary-admin {
            background-color: #007bff;
            border-color: #007bff;
        }
        
        .btn-primary-admin:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
        
        .navbar-left {
            display: flex;
            align-items: center;
        }
        
        .navbar-title {
            margin-left: 1rem;
        }
        
        .navbar-title h5 {
            margin: 0;
            font-weight: 700;
            color: #2c3e50;
            font-size: 1.1rem;
        }
        
        .navbar-title small {
            color: #7f8c8d;
            font-size: 0.85rem;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            color: #2c3e50;
            background: rgba(255,255,255,0.8);
            padding: 0.5rem 1rem;
            border-radius: 25px;
            border: 1px solid #e9ecef;
            transition: all 0.3s ease;
        }
        
        .user-info:hover {
            background: rgba(255,255,255,1);
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .user-avatar {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, #007bff, #0056b3);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 1.1rem;
            margin-right: 0.75rem;
            box-shadow: 0 2px 6px rgba(0, 123, 255, 0.3);
        }
        
        .user-details h6 {
            margin: 0;
            font-weight: 600;
            color: #2c3e50;
            font-size: 0.95rem;
        }
        
        .user-details small {
            color: #7f8c8d;
            font-size: 0.8rem;
        }
        
        .logout-btn {
            background: linear-gradient(135deg, #007bff, #0056b3);
            border: none;
            color: white;
            padding: 0.6rem;
            border-radius: 8px;
            transition: all 0.3s ease;
            margin-left: 0.75rem;
            box-shadow: 0 2px 4px rgba(0, 123, 255, 0.3);
        }
        
        .logout-btn:hover {
            background: linear-gradient(135deg, #0056b3, #004085);
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 123, 255, 0.4);
        }
        
        /* Logout Modal Styles */
        #logoutModal .modal-content {
            border: none;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        
        #logoutModal .modal-header {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            border-radius: 12px 12px 0 0;
        }
        
        #logoutModal .modal-title {
            font-weight: 600;
            color: #2c3e50;
        }
        
        #logoutModal .btn-danger {
            background: linear-gradient(135deg, #007bff, #0056b3);
            border: none;
            font-weight: 500;
            padding: 0.5rem 1.5rem;
        }
        
        #logoutModal .btn-danger:hover {
            background: linear-gradient(135deg, #0056b3, #004085);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 123, 255, 0.4);
        }
        
        #logoutModal .btn-secondary {
            background: #6c757d;
            border: none;
            font-weight: 500;
            padding: 0.5rem 1.5rem;
        }
        
        #logoutModal .btn-secondary:hover {
            background: #5a6268;
            transform: translateY(-1px);
        }
        
        .sidebar-footer {
            padding: 1rem 1.5rem;
            border-top: 1px solid rgba(255,255,255,0.1);
            margin-top: auto;
        }

        .organization-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #28a745, #6cbb6f);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.9rem;
        }
        
        .sidebar-footer a {
            color: #bdc3c7;
            text-decoration: none;
            font-size: 0.875rem;
        }
        
        .sidebar-footer a:hover {
            color: white;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <div class="admin-layout">
        <!-- Sidebar -->
        <nav class="sidebar">
            <div class="sidebar-nav">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('superadmin.dashboard') ? 'active' : '' }}" href="{{ route('superadmin.dashboard') }}">
                            <i class="bi bi-speedometer2"></i>
                            Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('superadmin.users.*') ? 'active' : '' }}" href="{{ route('superadmin.users.index') }}">
                            <i class="bi bi-people"></i>
                            Users
                        </a>
                    </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('superadmin.limits.*') ? 'active' : '' }}" href="{{ route('superadmin.limits.index') }}">
                    <i class="bi bi-shield-check"></i>
                    User Limits
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('superadmin.organization-limits.*') ? 'active' : '' }}" href="{{ route('superadmin.organization-limits.index') }}">
                    <i class="bi bi-building"></i>
                    Organization Limits
                </a>
            </li>
                </ul>
            </div>
            
        </nav>
        
        <!-- Main Content -->
        <div class="main-content">
            <!-- Top Navbar -->
            <div class="top-navbar">
                <div class="navbar-left">
                    <button class="btn btn-outline-secondary d-md-none me-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu">
                        <i class="bi bi-list"></i>
                    </button>
                    <div class="navbar-title">
                        <h5 class="mb-0">SuperAdmin Panel</h5>
                        <small class="text-muted">System Administration</small>
                    </div>
                </div>
                
                <div class="d-flex align-items-center">
                    <div class="user-info">
                        <div class="user-avatar">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                        <div class="user-details">
                            <h6 class="mb-0">{{ auth()->user()->name }}</h6>
                            <small>SuperAdmin</small>
                        </div>
                    </div>
                    
                    <button type="button" class="logout-btn" data-bs-toggle="modal" data-bs-target="#logoutModal" title="Logout">
                        <i class="bi bi-box-arrow-right"></i>
                    </button>
                </div>
            </div>
            
            <!-- Content Area -->
            <div class="content-area">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Mobile sidebar toggle
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.querySelector('[data-bs-toggle="offcanvas"]');
            const sidebar = document.querySelector('.sidebar');
            
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('show');
                });
            }
        });
    </script>
    
    @stack('scripts')
    
    <!-- Logout Modal -->
    <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title" id="logoutModalLabel">
                        <i class="bi bi-box-arrow-right text-danger me-2"></i>Confirm Logout
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-0">
                    <div class="text-center">
                        <div class="mb-3">
                            <i class="bi bi-exclamation-triangle text-warning" style="font-size: 3rem;"></i>
                        </div>
                        <p class="mb-0">Are you sure you want to logout from the SuperAdmin panel?</p>
                        <small class="text-muted">You will need to login again to access the admin features.</small>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>Cancel
                    </button>
                    <form method="POST" action="{{ route('superadmin.auth.logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-box-arrow-right me-1"></i>Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
