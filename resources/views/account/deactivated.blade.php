<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Deactivated - Alymart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .deactivated-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
            max-width: 800px;
            width: 100%;
            margin: 20px;
        }
        .header-section {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            padding: 40px;
            text-align: center;
        }
        .content-section {
            padding: 40px;
        }
        .icon-large {
            font-size: 4rem;
            margin-bottom: 20px;
            opacity: 0.9;
        }
        .suggestion-card {
            border: none;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 20px;
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
        }
        .suggestion-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        .suggestion-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-left-color: #667eea;
        }
        .suggestion-success {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            color: white;
            border-left-color: #11998e;
        }
        .suggestion-info {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
            border-left-color: #4facfe;
        }
        .suggestion-warning {
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            color: white;
            border-left-color: #fa709a;
        }
        .btn-custom {
            border-radius: 25px;
            padding: 12px 30px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
            border: none;
            margin: 5px;
        }
        .btn-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            color: white;
        }
        .step-indicator {
            display: flex;
            justify-content: center;
            margin-bottom: 30px;
        }
        .step {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 10px;
            font-weight: bold;
            color: #6c757d;
        }
        .step.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .contact-info {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 25px;
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <div class="deactivated-container">
        <!-- Header Section -->
        <div class="header-section">
            <i class="fas fa-user-slash icon-large"></i>
            <h1 class="mb-3">Account Temporarily Deactivated</h1>
            <p class="lead mb-0">Your account has been temporarily deactivated for security and maintenance purposes.</p>
        </div>

        <!-- Content Section -->
        <div class="content-section">
            <!-- Step Indicators -->
            <div class="step-indicator">
                <div class="step active">1</div>
                <div class="step active">2</div>
                <div class="step">3</div>
            </div>

            <!-- Suggestions -->
            <h3 class="text-center mb-4">What You Can Do Next</h3>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="suggestion-card suggestion-primary">
                        <h5><i class="fas fa-phone me-2"></i>Contact Administrator</h5>
                        <p>Reach out to your system administrator to understand why your account was deactivated and request reactivation.</p>
                        <a href="{{ route('account.contact-support') }}" class="btn btn-light btn-custom">
                            <i class="fas fa-envelope me-2"></i>Contact Support
                        </a>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="suggestion-card suggestion-success">
                        <h5><i class="fas fa-clock me-2"></i>Wait for Reactivation</h5>
                        <p>If this is a temporary deactivation, your account may be automatically reactivated soon. Check back later.</p>
                        <button onclick="location.reload()" class="btn btn-light btn-custom">
                            <i class="fas fa-sync me-2"></i>Check Status
                        </button>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-6">
                    <div class="suggestion-card suggestion-info">
                        <h5><i class="fas fa-question-circle me-2"></i>Review Guidelines</h5>
                        <p>Review our terms of service and usage guidelines to understand what might have caused the deactivation.</p>
                        <a href="#" class="btn btn-light btn-custom">
                            <i class="fas fa-book me-2"></i>View Guidelines
                        </a>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="suggestion-card suggestion-warning">
                        <h5><i class="fas fa-shield-alt me-2"></i>Security Check</h5>
                        <p>Ensure your account security is up to date. Update your password and enable two-factor authentication.</p>
                        <a href="#" class="btn btn-light btn-custom">
                            <i class="fas fa-lock me-2"></i>Security Tips
                        </a>
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="contact-info">
                <h4 class="mb-3"><i class="fas fa-info-circle me-2"></i>Need Immediate Assistance?</h4>
                <div class="row">
                    <div class="col-md-4">
                        <strong><i class="fas fa-envelope me-2"></i>Email:</strong><br>
                        support@alymart.com
                    </div>
                    <div class="col-md-4">
                        <strong><i class="fas fa-phone me-2"></i>Phone:</strong><br>
                        +1 (555) 123-4567
                    </div>
                    <div class="col-md-4">
                        <strong><i class="fas fa-clock me-2"></i>Hours:</strong><br>
                        Mon-Fri, 9AM-6PM
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="text-center mt-4">
                <a href="{{ route('login') }}" class="btn btn-outline-primary btn-custom">
                    <i class="fas fa-sign-in-alt me-2"></i>Try Login Again
                </a>
                <button onclick="window.close()" class="btn btn-outline-secondary btn-custom">
                    <i class="fas fa-times me-2"></i>Close Window
                </button>
            </div>

            <!-- Important Notice -->
            <div class="alert alert-warning mt-4" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>Important:</strong> For security reasons, you will not be able to access any system features until your account is reactivated by an administrator.
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
