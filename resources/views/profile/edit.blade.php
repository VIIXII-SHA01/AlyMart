@extends('layouts.app')

@section('header', 'Edit Profile')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Edit Profile</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('profile.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back to Profile
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Edit Profile Information</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PATCH')
                    
                    <!-- Name -->
                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name *</label>
                        <input type="text" class="form-control" id="name" name="name" 
                               value="{{ old('name', $user->name) }}" required autofocus>
                        @error('name')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address *</label>
                        <input type="email" class="form-control" id="email" name="email" 
                               value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="tel" class="form-control" id="phone" name="phone" 
                               value="{{ old('phone', $user->phone) }}" placeholder="Enter phone number">
                        @error('phone')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Address -->
                    <div class="mb-4">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control" id="address" name="address" rows="3" 
                                  placeholder="Enter your address">{{ old('address', $user->address) }}</textarea>
                        @error('address')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('profile.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-1"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Update Profile
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Current User Info -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Current Information</h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <div class="avatar-upload position-relative d-inline-block">
                        @if($user->avatar)
                            <img src="/alymart/public/avatars/{{ $user->avatar }}" 
                                 alt="{{ $user->name }}" class="avatar-lg rounded-circle" 
                                 style="width: 80px; height: 80px; object-fit: cover;"
                                 onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=random&color=6366f1';">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=random&color=6366f1" 
                                 alt="{{ $user->name }}" class="avatar-lg rounded-circle" 
                                 style="width: 80px; height: 80px; object-fit: cover;">
                        @endif
                    </div>
                    <h6 class="mb-1">{{ $user->name }}</h6>
                    <span class="badge 
                        @if($user->role == 'admin') bg-danger
                        @elseif($user->role == 'cashier') bg-success
                        @else bg-warning @endif">
                        {{ ucfirst($user->role) }}
                    </span>
                </div>
                
                <div class="mb-3">
                    <p class="mb-1"><strong>Email:</strong><br>{{ $user->email }}</p>
                    <p class="mb-1"><strong>Phone:</strong><br>{{ $user->phone ?? 'Not provided' }}</p>
                    <p class="mb-1"><strong>Address:</strong><br>{{ $user->address ?? 'Not provided' }}</p>
                    <p class="mb-1"><strong>Status:</strong><br>
                        <span class="badge 
                            @if($user->is_active) bg-success
                            @else bg-secondary @endif">
                            {{ $user->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </p>
                    <p class="mb-0"><strong>Member Since:</strong><br>{{ $user->created_at->format('F d, Y') }}</p>
                </div>
            </div>
        </div>

        <!-- Role Information -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Role Information</h5>
            </div>
            <div class="card-body">
                <div class="role-info">
                    @if($user->role == 'admin')
                        <div class="alert alert-danger">
                            <h6><i class="fas fa-user-shield me-2"></i>Admin</h6>
                            <ul class="small mb-0">
                                <li>Full system access</li>
                                <li>User management</li>
                                <li>All sales and inventory</li>
                                <li>System settings</li>
                            </ul>
                        </div>
                    @elseif($user->role == 'cashier')
                        <div class="alert alert-success">
                            <h6><i class="fas fa-cash-register me-2"></i>Cashier</h6>
                            <ul class="small mb-0">
                                <li>Create and manage sales</li>
                                <li>View sales reports</li>
                                <li>Process transactions</li>
                                <li>No inventory access</li>
                            </ul>
                        </div>
                    @else
                        <div class="alert alert-warning">
                            <h6><i class="fas fa-boxes me-2"></i>Inventory Staff</h6>
                            <ul class="small mb-0">
                                <li>Product management</li>
                                <li>Inventory tracking</li>
                                <li>Stock movements</li>
                                <li>No sales access</li>
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
