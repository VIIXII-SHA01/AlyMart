@extends('layouts.app')

@section('header', 'Edit User - ' . $user->name)

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Edit User</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.users.show', $user) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back to User
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Edit User Information</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.users.update', $user) }}">
                    @csrf
                    @method('PUT')
                    
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

                    <!-- Role -->
                    <div class="mb-3">
                        <label for="role" class="form-label">User Role *</label>
                        <select class="form-select" id="role" name="role" required>
                            <option value="">Select a role...</option>
                            <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="cashier" {{ old('role', $user->role) == 'cashier' ? 'selected' : '' }}>Cashier</option>
                            <option value="inventory_staff" {{ old('role', $user->role) == 'inventory_staff' ? 'selected' : '' }}>Inventory Staff</option>
                        </select>
                        @error('role')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div class="mb-4">
                        <div class="form-check">
                            <input type="hidden" name="is_active" value="0">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                   value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Active User
                            </label>
                        </div>
                        <small class="text-muted">Uncheck to deactivate this user account</small>
                    </div>

                    <!-- Password Update -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0">Update Password (Optional)</h6>
                        </div>
                        <div class="card-body">
                            <p class="text-muted small mb-3">Leave blank to keep current password</p>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="password" class="form-label">New Password</label>
                                    <input type="password" class="form-control" id="password" name="password">
                                    @error('password')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                                    <input type="password" class="form-control" id="password_confirmation" 
                                           name="password_confirmation">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('admin.users.show', $user) }}" class="btn btn-secondary">
                            <i class="fas fa-times me-1"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Update User
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
                    <div class="avatar-lg bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2" 
                         style="width: 80px; height: 80px; font-size: 2rem;">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
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

        <!-- Warning Messages -->
        @if($user->id === auth()->id())
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>Editing Your Account</strong><br>
                <small>You are editing your own account. Be careful when changing your role or deactivating your account.</small>
            </div>
        @endif

        @if($user->sales->count() > 0 || $user->inventoryMovements->count() > 0)
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                <strong>User Has Activity</strong><br>
                <small>This user has {{ $user->sales->count() }} sales and {{ $user->inventoryMovements->count() }} inventory movements. Consider deactivating instead of deleting.</small>
            </div>
        @endif

        <!-- Role Information -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Role Permissions</h5>
            </div>
            <div class="card-body">
                <div class="role-info">
                    @if(old('role', $user->role) == 'admin')
                        <div class="alert alert-danger">
                            <h6><i class="fas fa-user-shield me-2"></i>Admin</h6>
                            <ul class="small mb-0">
                                <li>Full system access</li>
                                <li>User management</li>
                                <li>All sales and inventory</li>
                                <li>System settings</li>
                            </ul>
                        </div>
                    @elseif(old('role', $user->role) == 'cashier')
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
