@extends('layouts.app')

@section('header', 'My Profile')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">My Profile</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('dashboard') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
        </a>
    </div>
</div>

<div class="row">
    <!-- Profile Information -->
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-user me-2"></i>
                    Profile Information
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Full Name:</strong><br>{{ $user->name }}</p>
                        <p><strong>Email Address:</strong><br>{{ $user->email }}</p>
                        <p><strong>Phone Number:</strong><br>{{ $user->phone ?? 'Not provided' }}</p>
                        <p><strong>Address:</strong><br>{{ $user->address ?? 'Not provided' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>User Role:</strong><br>
                            <span class="badge 
                                @if($user->role == 'admin') bg-danger
                                @elseif($user->role == 'cashier') bg-success
                                @else bg-warning @endif">
                                {{ ucfirst($user->role) }}
                            </span>
                        </p>
                        <p><strong>Account Status:</strong><br>
                            <span class="badge 
                                @if($user->is_active) bg-success
                                @else bg-secondary @endif">
                                {{ $user->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </p>
                        <p><strong>Member Since:</strong><br>{{ $user->created_at->format('F d, Y') }}</p>
                        <p><strong>Last Login:</strong><br>
                            @if($user->last_login_at)
                                {{ $user->last_login_at->format('F d, Y h:i A') }}
                            @else
                                <span class="text-muted">First time login</span>
                            @endif
                        </p>
                    </div>
                </div>

                <div class="text-end mt-3">
                    <a href="{{ route('profile.edit') }}" class="btn btn-primary">
                        <i class="fas fa-edit me-1"></i> Edit Profile
                    </a>
                </div>
            </div>
        </div>

        <!-- Activity Statistics -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-bar me-2"></i>
                    Activity Statistics
                </h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-3">
                        <h5 class="text-primary">{{ $user->sales->count() }}</h5>
                        <small class="text-muted">Sales Processed</small>
                    </div>
                    <div class="col-md-3">
                        <h5 class="text-success">{{ $user->inventoryMovements->count() }}</h5>
                        <small class="text-muted">Inventory Movements</small>
                    </div>
                    <div class="col-md-3">
                        <h5 class="text-info">₱{{ number_format($user->sales->sum('total_amount'), 2) }}</h5>
                        <small class="text-muted">Total Sales Value</small>
                    </div>
                    <div class="col-md-3">
                        <h5 class="text-warning">{{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never' }}</h5>
                        <small class="text-muted">Last Activity</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="col-md-4">
        <!-- Account Actions -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Account Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('profile.edit') }}" class="btn btn-primary">
                        <i class="fas fa-edit me-2"></i> Edit Profile
                    </a>
                    <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                        <i class="fas fa-key me-2"></i> Change Password
                    </button>
                    <a href="{{ route('dashboard') }}" class="btn btn-info">
                        <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                    </a>
                </div>
            </div>
        </div>

        <!-- Role Information -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Role Information</h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <div class="avatar-upload position-relative d-inline-block">
                        @if($user->avatar)
                            <img src="/alymart/public/avatars/{{ $user->avatar }}" 
                                 alt="{{ $user->name }}" class="avatar-lg rounded-circle" 
                                 style="width: 120px; height: 120px; object-fit: cover;"
                                 onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=random&color=6366f1';">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=random&color=6366f1" 
                                 alt="{{ $user->name }}" class="avatar-lg rounded-circle" 
                                 style="width: 120px; height: 120px; object-fit: cover;">
                        @endif
                        @if(auth()->user()->id === $user->id)
                            <button type="button" class="btn btn-sm btn-primary position-absolute bottom-0 end-0 m-1" 
                                    data-bs-toggle="modal" data-bs-target="#changeAvatarModal">
                                <i class="fas fa-camera"></i>
                            </button>
                        @endif
                    </div>
                </div>
                    <h6 class="mb-1">{{ $user->name }}</h6>
                    <span class="badge 
                        @if($user->role == 'admin') bg-danger
                        @elseif($user->role == 'cashier') bg-success
                        @else bg-warning @endif">
                        {{ ucfirst($user->role) }}
                    </span>
                </div>
                
                <div class="role-permissions">
                    @if($user->role == 'admin')
                        <p class="text-muted small mb-0">
                            <strong>Admin Access:</strong> Full system control including user management, all sales data, inventory management, and system settings.
                        </p>
                    @elseif($user->role == 'cashier')
                        <p class="text-muted small mb-0">
                            <strong>Cashier Access:</strong> Sales processing, transaction management, and sales reporting. No inventory or user management access.
                        </p>
                    @else
                        <p class="text-muted small mb-0">
                            <strong>Inventory Staff Access:</strong> Product management, inventory tracking, stock movements, and inventory reporting. No sales access.
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Change Password Modal -->
<div class="modal fade" id="changePasswordModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Change Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PATCH')
                    
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Current Password *</label>
                        <input type="password" class="form-control" id="current_password" name="current_password" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">New Password *</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirm New Password *</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                    </div>
                    
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">Change Password</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
    </div>
</div>

<!-- Change Avatar Modal -->
<div class="modal fade" id="changeAvatarModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Change Avatar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('profile.avatar.update') }}" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="avatar" class="form-label">Choose Avatar Image</label>
                        <input type="file" class="form-control" id="avatar" name="avatar" accept="image/*" required>
                        <small class="text-muted">Allowed: JPEG, PNG, GIF. Max size: 2MB</small>
                    </div>
                    
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">Upload Avatar</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
