<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Support - Alymart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .support-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            margin: 40px auto;
            max-width: 900px;
            overflow: hidden;
        }
        .header-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px;
            text-align: center;
        }
        .content-section {
            padding: 40px;
        }
        .contact-method {
            border: none;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 20px;
            transition: all 0.3s ease;
            text-align: center;
        }
        .contact-method:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        .contact-email {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .contact-phone {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            color: white;
        }
        .contact-form {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
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
        .icon-large {
            font-size: 3rem;
            margin-bottom: 15px;
        }
        .form-control, .form-select {
            border-radius: 10px;
            border: 2px solid #e9ecef;
            padding: 12px;
        }
        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
    </style>
</head>
<body>
    <div class="support-container">
        <!-- Header Section -->
        <div class="header-section">
            <i class="fas fa-headset icon-large"></i>
            <h1 class="mb-3">Contact Support</h1>
            <p class="lead mb-0">We're here to help you reactivate your account and resolve any issues</p>
        </div>

        <!-- Content Section -->
        <div class="content-section">
            <div class="row">
                <!-- Email Contact -->
                <div class="col-md-4">
                    <div class="contact-method contact-email">
                        <i class="fas fa-envelope icon-large"></i>
                        <h4>Email Support</h4>
                        <p>Send us a detailed message about your account deactivation</p>
                        <a href="mailto:support@alymart.com" class="btn btn-light btn-custom">
                            <i class="fas fa-paper-plane me-2"></i>Send Email
                        </a>
                    </div>
                </div>

                <!-- Phone Contact -->
                <div class="col-md-4">
                    <div class="contact-method contact-phone">
                        <i class="fas fa-phone icon-large"></i>
                        <h4>Phone Support</h4>
                        <p>Speak directly with our support team</p>
                        <a href="tel:+15551234567" class="btn btn-light btn-custom">
                            <i class="fas fa-phone me-2"></i>Call Now
                        </a>
                    </div>
                </div>

                <!-- Contact Form -->
                <div class="col-md-4">
                    <div class="contact-method contact-form">
                        <i class="fas fa-comment-dots icon-large"></i>
                        <h4>Quick Message</h4>
                        <p>Fill out our quick contact form</p>
                        <button type="button" class="btn btn-light btn-custom" data-bs-toggle="modal" data-bs-target="#contactModal">
                            <i class="fas fa-edit me-2"></i>Fill Form
                        </button>
                    </div>
                </div>
            </div>

            <!-- Support Information -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card border-0 bg-light">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fas fa-info-circle me-2"></i>What to Include in Your Message</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <ul class="list-unstyled">
                                        <li><i class="fas fa-check text-success me-2"></i>Your full name</li>
                                        <li><i class="fas fa-check text-success me-2"></i>Email address associated with account</li>
                                        <li><i class="fas fa-check text-success me-2"></i>Username/Employee ID</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <ul class="list-unstyled">
                                        <li><i class="fas fa-check text-success me-2"></i>When you last accessed the account</li>
                                        <li><i class="fas fa-check text-success me-2"></i>Reason for deactivation (if known)</li>
                                        <li><i class="fas fa-check text-success me-2"></i>Any error messages received</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Response Times -->
            <div class="row mt-4">
                <div class="col-md-3">
                    <div class="text-center">
                        <i class="fas fa-envelope text-primary" style="font-size: 2rem;"></i>
                        <h6>Email Response</h6>
                        <p class="text-muted">Within 24 hours</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="text-center">
                        <i class="fas fa-phone text-success" style="font-size: 2rem;"></i>
                        <h6>Phone Support</h6>
                        <p class="text-muted">Mon-Fri, 9AM-6PM</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="text-center">
                        <i class="fas fa-clock text-warning" style="font-size: 2rem;"></i>
                        <h6>Urgent Issues</h6>
                        <p class="text-muted">Within 4 hours</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="text-center">
                        <i class="fas fa-shield-alt text-info" style="font-size: 2rem;"></i>
                        <h6>Security Issues</h6>
                        <p class="text-muted">Immediate response</p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="text-center mt-4">
                <a href="{{ route('account.deactivated') }}" class="btn btn-outline-primary btn-custom">
                    <i class="fas fa-arrow-left me-2"></i>Back to Account Status
                </a>
                <a href="{{ route('login') }}" class="btn btn-outline-secondary btn-custom">
                    <i class="fas fa-sign-in-alt me-2"></i>Try Login Again
                </a>
            </div>
        </div>
    </div>

    <!-- Contact Modal -->
    <div class="modal fade" id="contactModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Send Support Request</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Full Name</label>
                                <input type="text" class="form-control" placeholder="Enter your full name">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email Address</label>
                                <input type="email" class="form-control" placeholder="your.email@example.com">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Username/Employee ID</label>
                                <input type="text" class="form-control" placeholder="Enter username or ID">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Issue Type</label>
                                <select class="form-select">
                                    <option>Account Deactivated</option>
                                    <option>Cannot Login</option>
                                    <option>Security Issue</option>
                                    <option>Other</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Message</label>
                            <textarea class="form-control" rows="5" placeholder="Please describe your issue in detail..."></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Priority</label>
                            <select class="form-select">
                                <option>Normal</option>
                                <option>High</option>
                                <option>Urgent</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary">
                        <i class="fas fa-paper-plane me-2"></i>Send Message
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
