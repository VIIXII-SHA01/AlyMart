<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Alymart - Sales & Inventory System</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .welcome-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
        }
    </style>
</head>
<body>
    <div class="container-fluid vh-100 d-flex align-items-center justify-content-center">
        <div class="row w-100">
            <div class="col-md-8 col-lg-6 mx-auto">
                <div class="card welcome-container shadow-lg">
                    <div class="card-body p-5">
                        <!-- Logo and Title -->
                        <div class="text-center mb-4">
                            <h1 class="fw-bold text-primary mb-3">
                                <i class="fas fa-store me-2"></i>Alymart
                            </h1>
                            <p class="lead text-muted">Web-Based Sales & Inventory System</p>
                            <p class="text-muted">for Alymart Minimart, General Santos City</p>
                        </div>

                        <!-- Login Button -->
                        <div class="text-center">
                            @auth
                                <a href="{{ route('dashboard') }}" class="btn btn-primary btn-lg px-5">
                                    <i class="fas fa-tachometer-alt me-2"></i> Go to Dashboard
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="btn btn-primary btn-lg px-5">
                                    <i class="fas fa-sign-in-alt me-2"></i> Login to System
                                </a>
                            @endif
                        </div>

                        <!-- Features -->
                        <div class="row mt-5">
                            <div class="col-md-4 text-center mb-3">
                                <div class="feature-box p-3">
                                    <i class="fas fa-box fa-2x text-primary mb-3"></i>
                                    <h6>Product Management</h6>
                                    <small class="text-muted">Track inventory & manage products</small>
                                </div>
                            </div>
                            <div class="col-md-4 text-center mb-3">
                                <div class="feature-box p-3">
                                    <i class="fas fa-shopping-cart fa-2x text-success mb-3"></i>
                                    <h6>Sales Recording</h6>
                                    <small class="text-muted">Process transactions efficiently</small>
                                </div>
                            </div>
                            <div class="col-md-4 text-center mb-3">
                                <div class="feature-box p-3">
                                    <i class="fas fa-chart-line fa-2x text-info mb-3"></i>
                                    <h6>Real-time Reports</h6>
                                    <small class="text-muted">Monitor business performance</small>
                                </div>
                            </div>
                        </div>

                        <!-- Demo Info -->
                        <div class="mt-4 pt-4 border-top">
                            <h6 class="text-center text-muted mb-3">Demo Accounts</h6>
                            <div class="row text-center">
                                <div class="col-md-4 mb-2">
                                    <div class="card">
                                        <div class="card-body p-3">
                                            <strong class="text-primary">Admin</strong><br>
                                            <small>Full Access</small><br>
                                            <code>admin@alymart.com</code><br>
                                            <code>password</code>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <div class="card">
                                            <div class="card-body p-3">
                                                <strong class="text-success">Cashier</strong><br>
                                                <small>Sales Access</small><br>
                                                <code>cashier@alymart.com</code><br>
                                                <code>password</code>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <div class="card">
                                            <div class="card-body p-3">
                                                <strong class="text-warning">Inventory</strong><br>
                                                <small>Stock Access</small><br>
                                                <code>inventory@alymart.com</code><br>
                                                <code>password</code>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Font Awesome -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
</body>
</html>
