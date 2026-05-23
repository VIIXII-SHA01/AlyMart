<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Alymart') }} - {{ $title ?? 'Sales & Inventory System' }}</title>

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        
        <style>
            .sidebar {
                position: fixed;
                top: 0;
                left: 0;
                bottom: 0;
                width: 240px;
                z-index: 100;
                padding: 48px 0 0;
                background-color: #343a40;
                box-shadow: inset -1px 0 0 rgba(0, 0, 0, .1);
                overflow-y: auto;
            }
            .sidebar .nav-link {
                color: #fff;
            }
            .sidebar .nav-link:hover {
                background-color: #495057;
            }
            .sidebar .nav-link.active {
                background-color: #007bff;
            }
            .main-content {
                margin-left: 240px;
                min-height: 100vh;
                overflow-y: auto;
                transition: margin-left 0.3s ease;
            }
            @media (max-width: 767.98px) {
                .sidebar {
                    position: relative;
                    height: auto;
                    padding: 0;
                }
                .main-content {
                    margin-left: 0;
                }
            }
            
            /* Float alerts on products page */
            .main-content:has(.products-page) > .alert {
                position: fixed !important;
                top: 20px !important;
                right: 20px !important;
                left: auto !important;
                z-index: 1050 !important;
                max-width: 400px !important;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
                margin: 0 !important;
                width: auto !important;
                display: block !important;
                padding-bottom: calc(1rem + 3px) !important;
            }
            
            .main-content:has(.products-page) > .alert .countdown-bar {
                position: absolute;
                bottom: 0;
                left: 0;
                right: 0;
                height: 3px;
                background: linear-gradient(90deg, #198754, #dc3545);
                animation: countdown 3s linear forwards;
                border-radius: 0 0 0.375rem 0.375rem;
            }
            
            @keyframes countdown {
                from {
                    width: 100%;
                }
                to {
                    width: 0%;
                }
            }
            
            @media (max-width: 767.98px) {
                .main-content:has(.products-page) > .alert {
                    top: 10px !important;
                    right: 10px !important;
                    left: 10px !important;
                    max-width: none !important;
                    width: auto !important;
                }
            }
        </style>
    </head>
    <body>
        <div class="container-fluid">
            <!-- Sidebar -->
            @auth
            <nav class="sidebar">
                <div class="pt-3">
                        <div class="text-center mb-4">
                            <h5 class="text-white">Alymart</h5>
                            <small class="text-muted">Sales & Inventory</small>
                        </div>
                        
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                                    <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                                </a>
                            </li>
                            
                            @if(auth()->user()->isAdmin() || auth()->user()->isInventoryStaff())
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}" href="{{ route('products.index') }}">
                                    <i class="fas fa-box me-2"></i> Products
                                </a>
                            </li>
                            @endif
                            
                            @if(auth()->user()->isAdmin() || auth()->user()->isCashier())
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('sales.*') ? 'active' : '' }}" href="{{ route('sales.index') }}">
                                    <i class="fas fa-shopping-cart me-2"></i> Sales
                                </a>
                            </li>
                            @endif
                            
                            @if(auth()->user()->isAdmin() || auth()->user()->isInventoryStaff())
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('inventory.*') ? 'active' : '' }}" href="{{ route('inventory.index') }}">
                                    <i class="fas fa-warehouse me-2"></i> Inventory
                                </a>
                            </li>
                            @endif
                            
                            @if(auth()->user()->isAdmin())
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                                    <i class="fas fa-users me-2"></i> Users
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('system.*') ? 'active' : '' }}" href="{{ route('system.maintenance') }}">
                                    <i class="fas fa-cogs me-2"></i> System
                                </a>
                            </li>
                            @endif
                        </ul>
                        
                        <hr class="text-white">
                        
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('profile.edit') }}">
                                    <i class="fas fa-user me-2"></i> Profile
                                </a>
                            </li>
                            <li class="nav-item">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="nav-link btn btn-link text-white">
                                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </nav>
                @endauth

                <!-- Main Content -->
                <main class="main-content px-md-4">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                            <strong>Whoops!</strong> There were some problems with your input.<br><br>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @isset($header)
                        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                            <h1 class="h2">{{ $header }}</h1>
                            
                            <!-- Notifications Dropdown -->
                            @if(isset($unreadNotifications))
                            <div class="dropdown">
                                <button class="btn btn-outline-secondary position-relative" type="button" id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-bell"></i>
                                    @if($unreadNotifications->count() > 0)
                                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                            {{ $unreadNotifications->count() }}
                                            <span class="visually-hidden">unread notifications</span>
                                        </span>
                                    @endif
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationDropdown" style="width: 350px; max-height: 400px; overflow-y: auto;">
                                    <li class="dropdown-header d-flex justify-content-between align-items-center">
                                        <span>Notifications</span>
                                        <a href="{{ route('notifications.markAllRead') }}" class="text-decoration-none text-muted small">Mark all as read</a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    @if($unreadNotifications->count() > 0)
                                        @foreach($unreadNotifications as $notification)
                                            <li class="dropdown-item notification-item {{ $notification->is_read ? 'read' : 'unread' }} {{ $notification->type === 'out_of_stock' ? 'notification-out-of-stock' : '' }}" style="white-space: normal; padding: 12px 16px;">
                                                <div class="d-flex">
                                                    <div class="flex-grow-1">
                                                        <div class="d-flex justify-content-between align-items-start">
                                                            <h6 class="mb-1 small {{ $notification->is_read ? 'fw-normal' : 'fw-bold' }} {{ $notification->type === 'out_of_stock' ? 'text-danger' : '' }}">
                                                                {{ $notification->title }}
                                                                @if($notification->type === 'out_of_stock')
                                                                    <i class="fas fa-exclamation-triangle ms-2"></i>
                                                                @endif
                                                            </h6>
                                                            <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                                        </div>
                                                        <p class="mb-1 small {{ $notification->type === 'out_of_stock' ? 'text-danger fw-bold' : '' }}">
                                                            {{ $notification->message }}
                                                            @if($notification->type === 'out_of_stock')
                                                                <div class="mt-2">
                                                                    <small class="text-muted">
                                                                        <i class="fas fa-clock me-1"></i>
                                                                        Action required: Restock immediately
                                                                    </small>
                                                                </div>
                                                            @endif
                                                        </p>
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            @php
                                                            $badgeClass = 'secondary';
                                                            switch($notification->type) {
                                                                case 'low_stock':
                                                                    $badgeClass = 'warning';
                                                                    break;
                                                                case 'out_of_stock':
                                                                    $badgeClass = 'danger';
                                                                    break;
                                                                case 'system':
                                                                    $badgeClass = 'info';
                                                                    break;
                                                                case 'success':
                                                                    $badgeClass = 'success';
                                                                    break;
                                                                case 'warning':
                                                                    $badgeClass = 'warning';
                                                                    break;
                                                            }
                                                            @endphp
                                                            <span class="badge {{ $notification->type === 'out_of_stock' ? 'bg-danger text-white fs-6 fw-bold' : 'bg-' . $badgeClass }}" style="background-color: #dc3545 !important; color: white !important; border: 2px solid #dc3545 !important;">
                                                                {{ ucfirst($notification->type) }}
                                                            </span>
                                                            <span class="badge bg-{{ $notification->is_read ? 'secondary' : 'primary' }} ms-2">
                                                                {{ $notification->is_read ? 'Read' : 'New' }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="ms-2">
                                                        <a href="{{ route('notifications.markRead', $notification->id) }}" class="btn btn-sm btn-outline-primary">Mark as read</a>
                                                        <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger ms-1">Delete</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                        @endforeach
                                    @else
                                        <li class="dropdown-item text-center text-muted py-3">
                                            <i class="fas fa-bell-slash fa-2x mb-2"></i>
                                            <p class="mb-0">No unread notifications</p>
                                        </li>
                                    @endif
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item text-center" href="{{ route('notifications.index') }}">
                                            <i class="fas fa-cog me-1"></i> View All Notifications
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            @endif
                        </div>
                    @endisset

                    @yield('content')
                </main>
        </div>

        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        
        <script>
        // Handle floating alerts with countdown for products page
        document.addEventListener('DOMContentLoaded', function() {
            // Check if we're on the products page
            const isProductsPage = document.querySelector('.products-page') !== null;
            
            if (isProductsPage) {
                const alerts = document.querySelectorAll('.main-content > .alert-dismissible');
                
                alerts.forEach(alert => {
                    // Add countdown bar
                    const countdownBar = document.createElement('div');
                    countdownBar.className = 'countdown-bar';
                    alert.appendChild(countdownBar);
                    
                    // Auto-dismiss after 3 seconds
                    setTimeout(function() {
                        const bsAlert = new bootstrap.Alert(alert);
                        bsAlert.close();
                    }, 3000);
                });
            }
        });
        </script>
        
        @stack('scripts')
    </body>
</html>
