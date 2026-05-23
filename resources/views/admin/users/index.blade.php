@extends('layouts.app')

@section('header', 'User Management')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">User Management</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Add User
        </a>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ App\Models\User::count() }}</h4>
                        <small>Total Users</small>
                    </div>
                    <i class="fas fa-users fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ App\Models\User::where('is_active', true)->count() }}</h4>
                        <small>Active Users</small>
                    </div>
                    <i class="fas fa-user-check fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ App\Models\User::where('is_active', false)->count() }}</h4>
                        <small>Inactive Users</small>
                    </div>
                    <i class="fas fa-user-times fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ App\Models\User::where('role', 'admin')->count() }}</h4>
                        <small>Admins</small>
                    </div>
                    <i class="fas fa-user-shield fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-4">
                <label for="liveSearch" class="form-label">Search Users</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text" class="form-control" id="liveSearch" 
                           placeholder="Search by name or email..." autocomplete="off">
                </div>
                <small class="text-muted">Type to search users in real-time</small>
            </div>
            <div class="col-md-3">
                <label for="roleFilter" class="form-label">Role</label>
                <select class="form-select" id="roleFilter">
                    <option value="">All Roles</option>
                    <option value="admin">Admin</option>
                    <option value="cashier">Cashier</option>
                    <option value="inventory_staff">Inventory Staff</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="statusFilter" class="form-label">Status</label>
                <select class="form-select" id="statusFilter">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">&nbsp;</label><br>
                <button type="button" class="btn btn-outline-secondary" id="clearFilters">
                    <i class="fas fa-times me-1"></i> Clear All
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Users Table -->
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Last Login</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <strong>{{ $user->name }}</strong>
                                        @if($user->id === auth()->id())
                                            <span class="badge bg-info ms-1">You</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <span class="badge 
                                    @if($user->role == 'admin') bg-danger
                                    @elseif($user->role == 'cashier') bg-success
                                    @else bg-warning @endif">
                                    @if($user->role == 'admin') Admin
                                    @elseif($user->role == 'cashier') Cashier
                                    @else Inventory Staff @endif
                                </span>
                            </td>
                            <td>
                                @if($user->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                            <td>{{ $user->created_at->format('M d, Y') }}</td>
                            <td>
                                @if($user->last_login_at)
                                    {{ $user->last_login_at->diffForHumans() }}
                                @else
                                    <span class="text-muted">Never</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.users.show', $user) }}" class="btn btn-outline-primary" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-outline-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if($user->id !== auth()->id())
                                        <button type="button" class="btn btn-outline-info" 
                                                onclick="toggleUserStatus({{ $user->id }})" 
                                                title="{{ $user->is_active ? 'Deactivate' : 'Activate' }}">
                                            <i class="fas fa-{{ $user->is_active ? 'ban' : 'check' }}"></i>
                                        </button>
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" title="Delete" 
                                                    onclick="return confirm('Are you sure you want to delete this user?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No users found.</p>
                                <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-1"></i> Create First User
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($users->hasPages())
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-muted">
                    Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} users
                </div>
                {{ $users->links() }}
            </div>
        @endif
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

// Real-time search functionality
document.addEventListener('DOMContentLoaded', function() {
    const liveSearch = document.getElementById('liveSearch');
    const roleFilter = document.getElementById('roleFilter');
    const statusFilter = document.getElementById('statusFilter');
    const clearFilters = document.getElementById('clearFilters');
    const userRows = document.querySelectorAll('tbody tr');
    const noResultsMessage = document.createElement('tr');
    noResultsMessage.innerHTML = `
        <td colspan="6" class="text-center py-4">
            <i class="fas fa-search fa-3x text-muted mb-3"></i>
            <p class="text-muted">No users found matching your search criteria.</p>
        </td>
    `;

    function filterUsers() {
        const searchTerm = liveSearch.value.toLowerCase();
        const roleValue = roleFilter.value;
        const statusValue = statusFilter.value;
        let visibleCount = 0;

        userRows.forEach(row => {
            const userName = row.querySelector('td:nth-child(1)')?.textContent.toLowerCase() || '';
            const userEmail = row.querySelector('td:nth-child(2)')?.textContent.toLowerCase() || '';
            const userRole = row.querySelector('td:nth-child(3)')?.textContent.toLowerCase() || '';
            const userStatus = row.querySelector('td:nth-child(4)')?.textContent.toLowerCase() || '';
            
            // Check if user matches search criteria
            const matchesSearch = searchTerm === '' || 
                                userName.includes(searchTerm) || 
                                userEmail.includes(searchTerm);
            
            // Fix role matching - check if role is selected and if it matches
            let matchesRole = true;
            if (roleValue !== '') {
                // Convert roleValue to match the display format in the table
                const roleMapping = {
                    'admin': 'admin',
                    'cashier': 'cashier', 
                    'inventory_staff': 'inventory staff'
                };
                const expectedRoleText = roleMapping[roleValue]?.toLowerCase() || '';
                matchesRole = userRole.includes(expectedRoleText);
            }
            
            // Fix status matching - check badge classes instead of text
            let matchesStatus = true;
            if (statusValue !== '') {
                const statusCell = row.querySelector('td:nth-child(4)');
                const statusBadge = statusCell?.querySelector('.badge');
                const badgeClass = statusBadge?.className || '';
                
                if (statusValue === 'active') {
                    matchesStatus = badgeClass.includes('bg-success');
                } else if (statusValue === 'inactive') {
                    matchesStatus = badgeClass.includes('bg-secondary');
                }
            }
            
            if (matchesSearch && matchesRole && matchesStatus) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        // Show/hide no results message
        const tbody = document.querySelector('tbody');
        const existingNoResults = tbody.querySelector('.no-results-message');
        
        if (visibleCount === 0) {
            if (!existingNoResults) {
                noResultsMessage.className = 'no-results-message';
                tbody.appendChild(noResultsMessage);
            }
        } else {
            if (existingNoResults) {
                existingNoResults.remove();
            }
        }

        // Update showing count
        updateShowingCount(visibleCount);
    }

    function updateShowingCount(visibleCount) {
        const totalUsers = userRows.length;
        const showingElement = document.querySelector('.pagination-info');
        
        if (showingElement) {
            if (visibleCount === totalUsers) {
                showingElement.textContent = `Showing all ${totalUsers} users`;
            } else {
                showingElement.textContent = `Showing ${visibleCount} of ${totalUsers} users`;
            }
        }
    }

    // Event listeners
    liveSearch.addEventListener('input', filterUsers);
    roleFilter.addEventListener('change', filterUsers);
    statusFilter.addEventListener('change', filterUsers);

    clearFilters.addEventListener('click', function() {
        liveSearch.value = '';
        roleFilter.value = '';
        statusFilter.value = '';
        filterUsers();
    });

    // Add search icon animation
    liveSearch.addEventListener('focus', function() {
        this.parentElement.querySelector('.input-group-text').style.color = '#007bff';
    });

    liveSearch.addEventListener('blur', function() {
        this.parentElement.querySelector('.input-group-text').style.color = '';
    });
});
</script>
@endpush
