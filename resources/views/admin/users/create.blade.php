@extends('layouts.app')

@section('header', 'Create New User')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Create New User</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back to Users
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">User Information</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.users.store') }}">
                    @csrf
                    
                    <!-- Name -->
                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name *</label>
                        <input type="text" class="form-control" id="name" name="name" 
                               value="{{ old('name') }}" required autofocus>
                        @error('name')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address *</label>
                        <input type="email" class="form-control" id="email" name="email" 
                               value="{{ old('email') }}" required>
                        @error('email')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="password" class="form-label">Password *</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                            @error('password')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label">Confirm Password *</label>
                            <input type="password" class="form-control" id="password_confirmation" 
                                   name="password_confirmation" required>
                        </div>
                    </div>

                    <!-- Role -->
                    <div class="mb-3">
                        <label for="role" class="form-label">User Role *</label>
                        <select class="form-select" id="role" name="role" required>
                            <option value="">Select a role...</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="cashier" {{ old('role') == 'cashier' ? 'selected' : '' }}>Cashier</option>
                            <option value="inventory_staff" {{ old('role') == 'inventory_staff' ? 'selected' : '' }}>Inventory Staff</option>
                        </select>
                        @error('role')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div class="mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                   value="1" {{ old('is_active', '1') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Active User
                            </label>
                        </div>
                        <small class="text-muted">Uncheck to create an inactive user account</small>
                    </div>

                    <!-- Submit Button -->
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-1"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Create User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Role Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Role Permissions</h5>
            </div>
            <div class="card-body">
                <div class="role-info">
                    <div class="mb-3">
                        <h6 class="text-danger"><i class="fas fa-user-shield me-2"></i>Admin</h6>
                        <ul class="small text-muted">
                            <li>Full system access</li>
                            <li>User management</li>
                            <li>All sales and inventory</li>
                            <li>System settings</li>
                        </ul>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="text-success"><i class="fas fa-cash-register me-2"></i>Cashier</h6>
                        <ul class="small text-muted">
                            <li>Create and manage sales</li>
                            <li>View sales reports</li>
                            <li>Process transactions</li>
                            <li>No inventory access</li>
                        </ul>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="text-warning"><i class="fas fa-boxes me-2"></i>Inventory Staff</h6>
                        <ul class="small text-muted">
                            <li>Product management</li>
                            <li>Inventory tracking</li>
                            <li>Stock movements</li>
                            <li>No sales access</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Password Requirements -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Password Requirements</h5>
            </div>
            <div class="card-body">
                <ul class="small text-muted mb-0">
                    <li>At least 8 characters</li>
                    <li>Contains at least one uppercase letter</li>
                    <li>Contains at least one lowercase letter</li>
                    <li>Contains at least one number</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
