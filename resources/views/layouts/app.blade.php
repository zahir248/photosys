<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Media Management System')</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    
    @stack('head')
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        body {
            background-color: #ffffff;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            color: #212529;
        }
        
        .photo-thumbnail {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 12px;
        }
        
        .photo-card {
            transition: all 0.2s ease;
            border: 1px solid #e9ecef;
            border-radius: 12px;
            overflow: hidden;
        }
        
        .photo-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }
        
        .navbar {
            background: #ffffff !important;
            border-bottom: 1px solid #e9ecef;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            padding: 1rem 0;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            transition: all 0.3s ease;
            transform: translateY(0);
        }

        .navbar.navbar-hidden {
            transform: translateY(-100%);
        }
        
        .brand-icon {
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, #007bff, #0056b3);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
        }
        
        .brand-text {
            color: #212529;
            font-size: 1.4rem;
            letter-spacing: -0.5px;
        }
        
        .navbar-nav .nav-link {
            color: #6c757d !important;
            font-weight: 500;
            padding: 12px 20px;
            border-radius: 8px;
            transition: all 0.2s ease;
            position: relative;
            margin: 0 4px;
        }
        
        .navbar-nav .nav-link:hover {
            color: #007bff !important;
            background-color: #f8f9fa;
            transform: translateY(-1px);
        }
        
        .navbar-nav .nav-link.active {
            color: #007bff !important;
            background-color: #e3f2fd;
            font-weight: 600;
        }
        
        .user-avatar {
            width: 32px;
            height: 32px;
            background: #f8f9fa;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6c757d;
            font-size: 1.2rem;
        }
        
        .user-name {
            font-weight: 500;
            color: #212529;
        }
        
        .dropdown-menu {
            border: 1px solid #e9ecef;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            margin-top: 8px;
        }
        
        .dropdown-item {
            padding: 10px 16px;
            font-weight: 500;
            transition: all 0.2s;
        }
        
        .dropdown-item:hover {
            background-color: #f8f9fa;
            color: #007bff;
        }
        
        .btn-outline-primary {
            border-color: #007bff;
            color: #007bff;
            font-weight: 500;
            padding: 8px 16px;
            border-radius: 6px;
        }
        
        .btn-outline-primary:hover {
            background-color: #007bff;
            border-color: #007bff;
            transform: translateY(-1px);
        }
        
        
        
        .main-content {
            min-height: calc(100vh - 80px);
            background-color: #ffffff;
            padding: 2rem;
            margin-top: 80px;
        }

        .dashboard-content {
            margin-top: 20px;
        }
        
        .page-header {
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #e9ecef;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .navbar-nav .nav-link {
                padding: 10px 16px;
                margin: 0 2px;
            }
        }
        
        .page-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: #212529;
            margin: 0;
        }
        
        .page-subtitle {
            color: #6c757d;
            font-size: 1rem;
            margin: 0.5rem 0 0 0;
        }
        
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            font-weight: 500;
            border-radius: 8px;
            padding: 10px 20px;
        }
        
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
        
        .card {
            border: 1px solid #e9ecef;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            transition: box-shadow 0.2s;
        }
        
        .card:hover {
            box-shadow: 0 4px 16px rgba(0,0,0,0.1);
        }
        
        .form-control {
            border-radius: 8px;
            border: 1px solid #e9ecef;
            padding: 12px 16px;
            font-size: 14px;
        }
        
        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }
        
        .alert {
            border-radius: 8px;
            border: none;
            font-weight: 500;
        }
        
        .table {
            background: white;
            border-radius: 8px;
            overflow: hidden;
        }
        
        .table th {
            border-top: none;
            border-bottom: 2px solid #e9ecef;
            font-weight: 600;
            color: #495057;
            background-color: #f8f9fa;
        }
        
        .badge {
            font-weight: 500;
            padding: 6px 12px;
            border-radius: 6px;
        }
        
        .text-primary {
            color: #007bff !important;
        }
        
        .bg-primary {
            background-color: #007bff !important;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
        <div class="container">
            <!-- Brand -->
            <a class="navbar-brand d-flex align-items-center" href="{{ route('dashboard') }}">
                <img src="{{ asset('images/plain logo.png') }}" alt="PhotoSys Logo" style="height: 3.5rem; width: auto; object-fit: contain;">
            </a>
            
            <!-- Mobile Toggle -->
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                @auth
                    <!-- Main Navigation -->
                    <ul class="navbar-nav mx-auto">
                        <li class="nav-item">
                            <a class="nav-link d-flex align-items-center px-3 {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                                <i class="bi bi-house me-2"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link d-flex align-items-center px-3 {{ request()->routeIs('media.*') ? 'active' : '' }}" href="{{ route('media.index') }}">
                                <i class="bi bi-collection me-2"></i>
                                <span>Media</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link d-flex align-items-center px-3 {{ request()->routeIs('albums.*') && !request()->has('from') ? 'active' : '' }}" href="{{ route('albums.index') }}">
                                <i class="bi bi-folder me-2"></i>
                                <span>Albums</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link d-flex align-items-center px-3 {{ request()->routeIs('organizations.*') || (request()->routeIs('albums.*') && request()->has('from')) ? 'active' : '' }}" href="{{ route('organizations.index') }}">
                                <i class="bi bi-people me-2"></i>
                                <span>Organizations</span>
                            </a>
                        </li>
                    </ul>
                    
                    <!-- User Menu -->
                    <div class="navbar-nav">
                        <div class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                                <div class="user-avatar me-2">
                                    <i class="bi bi-person-circle"></i>
                                </div>
                                <span class="user-name">{{ Auth::user()->name }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item d-flex align-items-center {{ request()->routeIs('profile') ? 'active' : '' }}" href="{{ route('profile') }}">
                                    <i class="bi bi-person me-2"></i> Profile
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item d-flex align-items-center" href="#" 
                                       onclick="event.preventDefault(); showLogoutModal();">
                                    <i class="bi bi-box-arrow-right me-2"></i> Logout
                                </a></li>
                            </ul>
                        </div>
                    </div>
                @else
                    <!-- Guest Navigation -->
                    <ul class="navbar-nav mx-auto">
                    </ul>
                    
                    <!-- Auth Links -->
                    <div class="navbar-nav">
                        <a class="btn btn-outline-primary me-2" href="{{ route('login') }}">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Login
                        </a>
                        <a class="btn btn-primary" href="{{ route('register') }}">
                            <i class="bi bi-person-plus me-1"></i> Register
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Logout Form -->
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    <!-- Logout Confirmation Modal -->
    <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title" id="logoutModalLabel">
                        <i class="bi bi-exclamation-triangle text-warning me-2"></i>Confirm Logout
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-0">
                    <p class="mb-0">Are you sure you want to logout? You will need to sign in again to access your account.</p>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>Cancel
                    </button>
                    <button type="button" class="btn btn-danger" onclick="confirmLogout()">
                        <i class="bi bi-box-arrow-right me-1"></i>Logout
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            @auth
                <!-- Main Content (Full Width for Authenticated Users) -->
                <div class="col-12 main-content p-4">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @yield('content')
                </div>
            @else
                <!-- Main Content (Full Width for Guests) -->
                <div class="col-12 main-content p-4">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @yield('content')
                </div>
            @endauth
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
    // Logout Modal Functions
    function showLogoutModal() {
        const logoutModal = new bootstrap.Modal(document.getElementById('logoutModal'));
        logoutModal.show();
    }

    function confirmLogout() {
        // Close the modal first
        const logoutModal = bootstrap.Modal.getInstance(document.getElementById('logoutModal'));
        logoutModal.hide();
        
        // Submit the logout form
        document.getElementById('logout-form').submit();
    }
    </script>
    
    <!-- Navbar Scroll Hide/Show Script -->
    <script>
        let lastScrollTop = 0;
        const navbar = document.querySelector('.navbar');
        const scrollThreshold = 100; // Minimum scroll distance to trigger hide/show
        
        window.addEventListener('scroll', function() {
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            
            if (scrollTop > lastScrollTop && scrollTop > scrollThreshold) {
                // Scrolling down - hide navbar
                navbar.classList.add('navbar-hidden');
            } else {
                // Scrolling up - show navbar
                navbar.classList.remove('navbar-hidden');
            }
            
            lastScrollTop = scrollTop;
        });
    </script>
    
    @stack('scripts')
</body>
</html>
