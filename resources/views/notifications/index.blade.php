@extends('layouts.app')

@section('header', 'Notifications')

@push('styles')
<style>
.notification-out-of-stock {
    background-color: #f8d7da !important;
    border-left: 4px solid #dc3545 !important;
}

.notification-out-of-stock .badge,
.notification-out-of-stock .bg-danger {
    background-color: #dc3545 !important;
    color: white !important;
    font-weight: bold !important;
    border: 2px solid #dc3545 !important;
}

.notification-out-of-stock .text-danger,
.notification-out-of-stock .text-white {
    color: #dc3545 !important;
    font-weight: bold !important;
}

.notification-out-of-stock strong {
    color: #dc3545 !important;
    font-weight: bold !important;
}

.notification-out-of-stock i.fa-envelope,
.notification-out-of-stock i.fa-exclamation-triangle {
    color: #dc3545 !important;
}

/* Force red for any element with out-of-stock */
.notification-out-of-stock * {
    color: #dc3545 !important;
}

/* TEST: Force red background */
.table-danger {
    background-color: #dc3545 !important;
}

.notification-out-of-stock .badge,
.notification-out-of-stock .bg-danger,
.notification-out-of-stock .text-white {
    background: #dc3545 !important;
    color: white !important;
}

/* TEST: Force red text */
.text-danger {
    color: #dc3545 !important;
}

/* TEST: Force all text red */
.notification-out-of-stock td {
    color: #dc3545 !important;
}

.notification-out-of-stock .badge,
.notification-out-of-stock .bg-danger,
.notification-out-of-stock .text-white {
    background: #dc3545 !important;
    color: white !important;
}
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Notifications</h1>
                <div>
                    <a href="{{ route('notifications.markAllRead') }}" class="btn btn-outline-secondary btn-sm me-2">
                        <i class="fas fa-check-double me-1"></i> Mark All as Read
                    </a>
                    <button class="btn btn-outline-danger btn-sm" onclick="deleteReadNotifications()">
                        <i class="fas fa-trash me-1"></i> Delete Read
                    </button>
                </div>
            </div>
            
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            <div class="card">
                <div class="card-body">
                    @if($notifications->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th width="5%">Status</th>
                                        <th width="10%">Type</th>
                                        <th width="20%">Title</th>
                                        <th width="35%">Message</th>
                                        <th width="15%">Date</th>
                                        <th width="15%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($notifications as $notification)
                                        <tr class="{{ $notification->is_read ? 'table-light' : ($notification->type === 'out_of_stock' ? 'notification-out-of-stock' : '') }}">
                                            <td>
                                                @if($notification->is_read)
                                                    <i class="fas fa-envelope-open text-muted"></i>
                                                @else
                                                    <i class="fas fa-envelope {{ $notification->type === 'out_of_stock' ? 'text-danger' : 'text-primary' }}"></i>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge {{ $notification->type === 'out_of_stock' ? 'bg-danger text-white fs-6 fw-bold' : ($notification->type === 'low_stock' ? 'bg-warning' : ($notification->type === 'system' ? 'bg-info' : ($notification->type === 'success' ? 'bg-success' : ($notification->type === 'warning' ? 'bg-warning' : 'bg-secondary'))) }}" style="background-color: #dc3545 !important; color: white !important; border: 2px solid #dc3545 !important;">
                                                    {{ ucfirst($notification->type) }}
                                                </span>
                                            </td>
                                            <td>
                                                <strong class="{{ $notification->type === 'out_of_stock' ? 'text-danger' : '' }}">
                                                    {{ $notification->title }}
                                                    @if($notification->type === 'out_of_stock')
                                                        <i class="fas fa-exclamation-triangle ms-2"></i>
                                                    @endif
                                                </strong>
                                                @if(!$notification->is_read)
                                                    <span class="badge bg-primary ms-2">New</span>
                                                @endif
                                            </td>
                                            <td class="{{ $notification->type === 'out_of_stock' ? 'text-danger fw-bold' : '' }}">
                                                {{ $notification->message }}
                                                @if($notification->type === 'out_of_stock')
                                                    <div class="mt-2">
                                                        <small class="text-muted">
                                                            <i class="fas fa-clock me-1"></i>
                                                            Action required: Restock product immediately
                                                        </small>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                <small>{{ $notification->created_at->format('M d, Y h:i A') }}</small><br>
                                                <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    @if(!$notification->is_read)
                                                        <a href="{{ route('notifications.markRead', $notification->id) }}" class="btn btn-outline-primary" title="Mark as read">
                                                            <i class="fas fa-check"></i>
                                                        </a>
                                                    @endif
                                                    <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this notification?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-outline-danger" title="Delete">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <div class="text-muted">
                                Showing {{ $notifications->firstItem() }} to {{ $notifications->lastItem() }} of {{ $notifications->total() }} notifications
                            </div>
                            {{ $notifications->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
                            <h4 class="text-muted">No Notifications</h4>
                            <p class="text-muted">You don't have any notifications yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function deleteReadNotifications() {
    if (confirm('Are you sure you want to delete all read notifications?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("notifications.delete-read") }}';
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        form.appendChild(methodField);
        
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endsection
