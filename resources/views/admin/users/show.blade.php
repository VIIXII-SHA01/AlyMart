@extends('layouts.app')

@section('header', 'User Details - ' . $user->name)

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">User Details</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back to Users
        </a>
    </div>
</div>

<div class="row">
    <!-- User Information -->
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-user me-2"></i>
                    User Profile
                </h5>
                <span class="badge 
                    @if($user->is_active) bg-success
                    @else bg-secondary @endif">
                    {{ $user->is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Full Name:</strong><br>{{ $user->name }}</p>
                        <p><strong>Email Address:</strong><br>{{ $user->email }}</p>
                        <p><strong>User Role:</strong><br>
                            <span class="badge 
                                @if($user->role == 'admin') bg-danger
                                @elseif($user->role == 'cashier') bg-success
                                @else bg-warning @endif">
                                {{ ucfirst($user->role) }}
                            </span>
                        </p>
                    </div>
                    <div class="col-md-6">
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
                                <span class="text-muted">Never logged in</span>
                            @endif
                        </p>
                    </div>
                </div>
                
                @if($user->id === auth()->id())
                    <div class="alert alert-info mt-3">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>This is your account.</strong> You cannot deactivate or delete your own account.
                    </div>
                @endif
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

    <!-- Actions & Management -->
    <div class="col-md-4">
        <!-- Quick Actions -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-2"></i> Edit User
                    </a>
                    
                    @if($user->id !== auth()->id())
                        <button type="button" class="btn btn-info" 
                                onclick="toggleUserStatus({{ $user->id }})">
                            <i class="fas fa-{{ $user->is_active ? 'ban' : 'check' }} me-2"></i>
                            {{ $user->is_active ? 'Deactivate User' : 'Activate User' }}
                        </button>
                        
                        <button type="button" class="btn btn-outline-primary" 
                                onclick="showPasswordResetForm()">
                            <i class="fas fa-key me-2"></i> Reset Password
                        </button>
                        
                        @if($user->sales->count() == 0 && $user->inventoryMovements->count() == 0)
                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" 
                                        onclick="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                                    <i class="fas fa-trash me-2"></i> Delete User
                                </button>
                            </form>
                        @endif
                    @endif
                </div>
            </div>
        </div>

        <!-- Password Reset Form (Hidden by default) -->
        <div class="card mb-4" id="passwordResetCard" style="display: none;">
            <div class="card-header">
                <h5 class="mb-0">Reset Password</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.users.reset-password', $user) }}">
                    @csrf
                    <div class="mb-3">
                        <label for="reset_password" class="form-label">New Password *</label>
                        <input type="password" class="form-control" id="reset_password" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="reset_password_confirmation" class="form-label">Confirm Password *</label>
                        <input type="password" class="form-control" id="reset_password_confirmation" 
                               name="password_confirmation" required>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Reset Password</button>
                        <button type="button" class="btn btn-secondary" onclick="hidePasswordResetForm()">Cancel</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Role Information -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Role Information</h5>
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
@endsection

@push('scripts')
<script>
function toggleUserStatus(userId) {
    if (!confirm('Are you sure you want to toggle this user\'s status?')) {
        return;
    }

    fetch(`{{ route('admin.users.toggle-status', ':user') }}`.replace(':user', userId), {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        alert('Error: ' + error.message);
    });
}

function showPasswordResetForm() {
    document.getElementById('passwordResetCard').style.display = 'block';
}

function hidePasswordResetForm() {
    document.getElementById('passwordResetCard').style.display = 'none';
}
</script>
@endpush
