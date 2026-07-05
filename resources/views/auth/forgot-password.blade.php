@extends('layouts.guest')

@section('title', 'Forgot Password - Alymart Inventory System')

@section('content')
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'Alymart') }} - Forgot Password</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Oxygen', 'Ubuntu', 'Cantarell', sans-serif;
        }
        
        .forgot-wrapper {
            width: 100%;
            padding: 20px;
        }
        
        .forgot-container {
            max-width: 420px;
            margin: 0 auto;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
            overflow: hidden;
        }
        
        .forgot-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 30px;
            text-align: center;
            color: #fff;
        }
        
        .forgot-header-icon {
            font-size: 48px;
            margin-bottom: 15px;
            opacity: 0.95;
        }
        
        .forgot-header h1 {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }
        
        .forgot-header p {
            font-size: 14px;
            opacity: 0.9;
            margin-bottom: 0;
            line-height: 1.6;
        }
        
        .forgot-body {
            padding: 40px 30px;
        }
        
        .form-group {
            margin-bottom: 24px;
        }
        
        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #e0e4e8;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
            background-color: #f8f9fb;
        }
        
        .form-control:focus {
            outline: none;
            border-color: #667eea;
            background-color: #fff;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .form-control::placeholder {
            color: #a8b4c4;
        }
        
        .form-control.is-invalid {
            border-color: #dc3545;
        }
        
        .invalid-feedback {
            display: block;
            font-size: 13px;
            color: #dc3545;
            margin-top: 6px;
        }
        
        .btn-submit {
            width: 100%;
            padding: 14px 16px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            letter-spacing: 0.5px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }
        
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }
        
        .btn-submit:active {
            transform: translateY(0);
        }
        
        .alert {
            border-radius: 8px;
            border: none;
            padding: 14px 16px;
            margin-bottom: 24px;
            font-size: 14px;
            display: flex;
            gap: 12px;
        }
        
        .alert-success {
            background-color: #f0fdf4;
            color: #166534;
        }
        
        .alert-danger {
            background-color: #fef2f2;
            color: #991b1b;
        }
        
        .alert-icon {
            flex-shrink: 0;
            font-size: 16px;
        }
        
        .btn-close {
            opacity: 0.6;
            transition: opacity 0.3s ease;
        }
        
        .btn-close:hover {
            opacity: 1;
        }
        
        .forgot-footer {
            padding: 20px 30px;
            border-top: 1px solid #e0e4e8;
            text-align: center;
        }
        
        .forgot-footer a {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .forgot-footer a:hover {
            color: #764ba2;
            text-decoration: underline;
        }
        
        .info-box {
            background-color: #f0f4ff;
            border-left: 4px solid #667eea;
            padding: 12px 15px;
            margin-bottom: 24px;
            border-radius: 4px;
            font-size: 13px;
            color: #4a5568;
            line-height: 1.6;
        }
        
        @media (max-width: 480px) {
            .forgot-header {
                padding: 30px 20px;
            }
            
            .forgot-header h1 {
                font-size: 22px;
            }
            
            .forgot-body {
                padding: 30px 20px;
            }
            
            .forgot-container {
                max-width: 100%;
                border-radius: 0;
            }
            
            .forgot-footer {
                padding: 15px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="forgot-wrapper">
        <div class="forgot-container">
            <!-- Header -->
            <div class="forgot-header">
                <div class="forgot-header-icon">
                    <i class="fas fa-key"></i>
                </div>
                <h1>Reset Password</h1>
                <p>Enter your email address and we'll send you a link to reset your password</p>
            </div>
            
            <!-- Body -->
            <div class="forgot-body">
                @if (session('status'))
                    <div class="alert alert-success">
                        <span class="alert-icon"><i class="fas fa-check-circle"></i></span>
                        <div>{{ session('status') }}</div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <span class="alert-icon"><i class="fas fa-exclamation-circle"></i></span>
                        <div>
                            <strong>Error!</strong>
                            <ul style="margin: 8px 0 0 0; padding-left: 20px;">
                                @foreach ($errors->all() as $error)
                                    <li style="margin-bottom: 4px;">{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                <div class="info-box">
                    <i class="fas fa-info-circle me-2"></i>
                    We'll send a password reset link to your email. The link will expire in 60 minutes.
                </div>
                
                <form method="POST" action="{{ route('password.email') }}">
                    @csrf
                    
                    <div class="form-group">
                        <label for="email" class="form-label">Email Address</label>
                        <input id="email" name="email" type="email" required 
                               class="form-control @error('email') is-invalid @enderror" 
                               value="{{ old('email') }}"
                               placeholder="your.email@example.com"
                               autocomplete="email">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-envelope me-2"></i>Send Reset Link
                    </button>
                </form>
            </div>
            
            <!-- Footer -->
            <div class="forgot-footer">
                <span>Remember your password? <a href="{{ route('login') }}">Back to Login</a></span>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-dismiss success alerts
        setTimeout(function() {
            const successAlerts = document.querySelectorAll('.alert-success');
            successAlerts.forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 4000);
    </script>
</body>
</html>
@endsection
