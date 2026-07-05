@extends('layouts.guest')

@section('title', 'Reset Password - Alymart Inventory System')

@section('content')
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'Alymart') }} - Reset Password</title>
    
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
        
        .reset-wrapper {
            width: 100%;
            padding: 20px;
        }
        
        .reset-container {
            max-width: 420px;
            margin: 0 auto;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
            overflow: hidden;
        }
        
        .reset-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 30px;
            text-align: center;
            color: #fff;
        }
        
        .reset-header-icon {
            font-size: 48px;
            margin-bottom: 15px;
            opacity: 0.95;
        }
        
        .reset-header h1 {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }
        
        .reset-header p {
            font-size: 14px;
            opacity: 0.9;
            margin-bottom: 0;
        }
        
        .reset-body {
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
        
        .password-requirements {
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 12px 15px;
            margin-bottom: 24px;
            font-size: 12px;
            color: #6b7280;
        }
        
        .password-requirements ul {
            margin: 8px 0 0 0;
            padding-left: 20px;
            list-style-type: disc;
        }
        
        .password-requirements li {
            margin-bottom: 4px;
        }
        
        .password-strength {
            height: 4px;
            background-color: #e5e7eb;
            border-radius: 2px;
            margin-top: 6px;
            overflow: hidden;
        }
        
        .password-strength-bar {
            height: 100%;
            width: 0%;
            transition: all 0.3s ease;
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
        
        @media (max-width: 480px) {
            .reset-header {
                padding: 30px 20px;
            }
            
            .reset-header h1 {
                font-size: 22px;
            }
            
            .reset-body {
                padding: 30px 20px;
            }
            
            .reset-container {
                max-width: 100%;
                border-radius: 0;
            }
        }
    </style>
</head>
<body>
    <div class="reset-wrapper">
        <div class="reset-container">
            <!-- Header -->
            <div class="reset-header">
                <div class="reset-header-icon">
                    <i class="fas fa-lock"></i>
                </div>
                <h1>Create New Password</h1>
                <p>Enter your new password below</p>
            </div>
            
            <!-- Body -->
            <div class="reset-body">
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
                
                <form method="POST" action="{{ route('password.store') }}" id="resetForm">
                    @csrf
                    
                    <input type="hidden" name="token" value="{{ $request->route('token') }}">
                    <input type="hidden" name="email" value="{{ $request->email }}">
                    
                    <div class="form-group">
                        <label for="password" class="form-label">New Password</label>
                        <input id="password" name="password" type="password" required 
                               class="form-control @error('password') is-invalid @enderror" 
                               placeholder="••••••••••"
                               autocomplete="new-password"
                               onkeyup="checkPasswordStrength()">
                        <div class="password-strength">
                            <div class="password-strength-bar" id="strengthBar"></div>
                        </div>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" required 
                               class="form-control @error('password_confirmation') is-invalid @enderror" 
                               placeholder="••••••••••"
                               autocomplete="new-password">
                        @error('password_confirmation')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="password-requirements">
                        <strong style="display: block; margin-bottom: 8px; color: #111827;">Password Requirements:</strong>
                        <ul>
                            <li>At least 8 characters long</li>
                            <li>Mix of uppercase and lowercase letters</li>
                            <li>Include numbers and special characters for better security</li>
                        </ul>
                    </div>
                    
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-check me-2"></i>Reset Password
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function checkPasswordStrength() {
            const password = document.getElementById('password').value;
            const strengthBar = document.getElementById('strengthBar');
            let strength = 0;
            
            if (password.length >= 8) strength += 20;
            if (password.length >= 12) strength += 10;
            if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength += 30;
            if (/[0-9]/.test(password)) strength += 20;
            if (/[^a-zA-Z0-9]/.test(password)) strength += 20;
            
            strengthBar.style.width = strength + '%';
            
            if (strength < 40) {
                strengthBar.style.backgroundColor = '#ef4444';
            } else if (strength < 70) {
                strengthBar.style.backgroundColor = '#f59e0b';
            } else {
                strengthBar.style.backgroundColor = '#10b981';
            }
        }
    </script>
</body>
</html>
@endsection
