<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - AlyMart Inventory Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
    <div class="hero-section">
        <div class="animated-bg">
            <div class="circle"></div>
            <div class="circle"></div>
            <div class="circle"></div>
            <div class="circle"></div>
        </div>

        <nav class="navbar navbar-expand-lg">
            <div class="container">
                <a class="navbar-brand" href="localhost/AlyMart">
                    <i class="fas white fa-boxes" style="color: white;"></i> AlyMart
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="#features">Features</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#about">About</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#contact">Contact</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="login-container">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-6 col-md-8 col-sm-10">
                        <div class="login-card">
                            <div class="login-header">
                                <h2><i class="fas fa-user-circle mb-3" style="font-size: 3rem; color: #667eea;"></i></h2>
                                <h2>Welcome Back</h2>
                                <p>Sign in to your AlyMart account</p>
                            </div>

                            <form id="loginForm">
                                <div class="form-floating">
                                    <input type="email" class="form-control" id="email" placeholder="name@example.com" required>
                                    <label for="email"><i class="fas fa-envelope me-2"></i>Email address</label>
                                </div>

                                <div class="form-floating">
                                    <input type="password" class="form-control" id="password" placeholder="Password" required>
                                    <label for="password"><i class="fas fa-lock me-2"></i>Password</label>
                                </div>

                                <div class="remember-me">
                                    <input class="form-check-input" type="checkbox" id="rememberMe">
                                    <label class="form-check-label" for="rememberMe">
                                        Remember me
                                    </label>
                                </div>

                                <button type="submit" class="btn btn-login">
                                    <i class="fas fa-sign-in-alt me-2"></i>Sign In
                                </button>

                                <div class="forgot-password">
                                    <a href="#" onclick="forgotPassword()">Forgot your password?</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
     <script src="js/login.js"></script>
</body>
</html>