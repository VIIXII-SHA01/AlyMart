@extends('layouts.guest')

@section('title', 'Login - Alymart Inventory System')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-500 to-blue-600">
    <div class="max-w-md w-full space-y-8">
        <div class="bg-white rounded-lg shadow-xl p-8">
            <div class="text-center">
                <div class="mx-auto h-16 w-16 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-store text-blue-600 text-2xl"></i>
                </div>
                <h2 class="mt-6 text-3xl font-bold text-gray-900">Sign In</h2>
                <p class="mt-2 text-sm text-gray-600">Access your Alymart account</p>
            </div>
            
            @if (session('status'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    <i class="fas fa-check-circle mr-2"></i>{{ session('status') }}
                </div>
            @endif
            
            <form class="mt-8 space-y-6" method="POST" action="{{ route('login') }}">
                @csrf
                
                <div>
                    <label for="email" class="form-label">Email Address</label>
                    <input id="email" name="email" type="email" required 
                           class="form-input" 
                           value="{{ old('email') }}"
                           placeholder="Enter your email address"
                           autocomplete="email">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="password" class="form-label">Password</label>
                    <input id="password" name="password" type="password" required 
                           class="form-input" 
                           placeholder="Enter your password"
                           autocomplete="current-password">
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember" name="remember" type="checkbox" 
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="remember" class="ml-2 block text-sm text-gray-900">
                            Remember me
                        </label>
                    </div>
                    
                                    </div>
                
                <div>
                    <button type="submit" class="btn btn-primary w-full">
                        <i class="fas fa-sign-in-alt mr-2"></i>Sign In
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
