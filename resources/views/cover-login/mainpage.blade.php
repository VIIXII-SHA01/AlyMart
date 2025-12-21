<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Sales Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/maincover.css">
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
                <a class="navbar-brand" href="#">
                    <i class="fas fa-boxes"></i> AlyMart
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

        <div class="content-wrapper">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 mx-auto">
                        <div class="hero-content text-center">
                            <h1 class="hero-title">Inventory Sales Management System</h1>
                            <p class="hero-subtitle">Streamline your business operations with our powerful, intuitive inventory and sales tracking solution</p>
                            
                            <div class="cta-buttons justify-content-center">
                                <button class="btn btn-primary-custom" onclick="getStarted()">
                                    Get Started <i class="fas fa-arrow-right ms-2"></i>
                                </button>
                                <button class="btn btn-outline-custom" onclick="learnMore()">
                                    Learn More
                                </button>
                            </div>

                            <div class="feature-cards">
                                <div class="feature-card">
                                    <div class="feature-icon">
                                        <i class="fas fa-chart-line"></i>
                                    </div>
                                    <h3 class="feature-title">Real-time Analytics</h3>
                                    <p class="feature-desc">Track sales and inventory metrics in real-time</p>
                                </div>
                                <div class="feature-card">
                                    <div class="feature-icon">
                                        <i class="fas fa-warehouse"></i>
                                    </div>
                                    <h3 class="feature-title">Stock Management</h3>
                                    <p class="feature-desc">Efficiently manage your inventory levels</p>
                                </div>
                                <div class="feature-card">
                                    <div class="feature-icon">
                                        <i class="fas fa-receipt"></i>
                                    </div>
                                    <h3 class="feature-title">Sales Tracking</h3>
                                    <p class="feature-desc">Monitor all transactions and generate reports</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/maincover.js"></script>
</body>
</html>