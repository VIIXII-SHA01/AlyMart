@extends('layouts.app')

@section('header', 'System Maintenance')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">System Maintenance</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('dashboard') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
        </a>
    </div>
</div>

<!-- System Statistics -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h5 class="card-title">Total Sales</h5>
                <h3 id="total-sales">Loading...</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h5 class="card-title">Total Movements</h5>
                <h3 id="total-movements">Loading...</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <h5 class="card-title">Old Records</h5>
                <h3 id="old-records">Loading...</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h5 class="card-title">Notifications</h5>
                <h3 id="total-notifications">Loading...</h3>
            </div>
        </div>
    </div>
</div>

<!-- Cleanup Controls -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-broom me-2"></i>
            Database Cleanup
        </h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="days" class="form-label">Delete records older than (days):</label>
                    <div class="input-group">
                        <input type="number" class="form-control" id="days" value="37" min="1" max="365">
                        <span class="input-group-text">days</span>
                    </div>
                    <small class="form-text text-muted">
                        Default: 37 days (1 month + 7 days). Records older than this will be permanently deleted.
                    </small>
                </div>
                
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Warning:</strong> This action cannot be undone. Old sales, inventory movements, and notifications will be permanently deleted from the database.
                </div>
                
                <button type="button" class="btn btn-danger" onclick="runCleanup()">
                    <i class="fas fa-trash me-1"></i> Run Cleanup
                </button>
                
                <button type="button" class="btn btn-outline-primary ms-2" onclick="refreshStats()">
                    <i class="fas fa-sync me-1"></i> Refresh Statistics
                </button>
            </div>
            
            <div class="col-md-6">
                <div class="card bg-light">
                    <div class="card-body">
                        <h6 class="card-title">
                            <i class="fas fa-info-circle me-2"></i>
                            What will be cleaned?
                        </h6>
                        <ul class="mb-0">
                            <li>Sales and sale items older than specified days</li>
                            <li>Inventory movements older than specified days</li>
                            <li>Notifications older than specified days</li>
                            <li>Related database records and logs</li>
                        </ul>
                        
                        <hr>
                        
                        <h6 class="card-title">
                            <i class="fas fa-clock me-2"></i>
                            Automatic Schedule
                        </h6>
                        <p class="mb-0">
                            Cleanup runs automatically every day at 2:00 AM to keep the database optimized.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Cleanup Output -->
<div class="card" id="output-card" style="display: none;">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-terminal me-2"></i>
            Cleanup Output
        </h5>
    </div>
    <div class="card-body">
        <pre id="cleanup-output" class="bg-dark text-white p-3 rounded" style="max-height: 400px; overflow-y: auto;"></pre>
    </div>
</div>

@endsection

@push('scripts')
<script>
function loadStats() {
    fetch('./statistics')
        .then(response => response.json())
        .then(data => {
            document.getElementById('total-sales').textContent = data.total_sales.toLocaleString();
            document.getElementById('total-movements').textContent = data.total_movements.toLocaleString();
            document.getElementById('total-notifications').textContent = data.total_notifications.toLocaleString();
            
            const oldTotal = data.old_sales + data.old_movements + data.old_notifications;
            document.getElementById('old-records').textContent = oldTotal.toLocaleString();
        })
        .catch(error => {
            console.error('Error loading statistics:', error);
        });
}

function refreshStats() {
    loadStats();
}

function runCleanup() {
    const days = document.getElementById('days').value;
    
    if (!confirm(`Are you sure you want to delete records older than ${days} days? This action cannot be undone.`)) {
        return;
    }
    
    const outputCard = document.getElementById('output-card');
    const outputElement = document.getElementById('cleanup-output');
    
    outputCard.style.display = 'block';
    outputElement.textContent = 'Starting cleanup...';
    
    fetch('./cleanup', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            days: days
        }),
        redirect: 'manual'
    })
    .then(response => {
        console.log('Response status:', response.status);
        console.log('Response type:', response.type);
        console.log('Response headers:', response.headers.get('content-type'));
        
        if (response.status === 401 || response.status === 403) {
            throw new Error(`Unauthorized (HTTP ${response.status}). Please check your permissions.`);
        }
        
        if (response.type === 'opaqueredirect' || (response.status >= 300 && response.status < 400)) {
            return response.text().then(text => {
                throw new Error(`Redirect detected (HTTP ${response.status}). Session may have expired. Response: ${text.substring(0, 100)}`);
            });
        }
        
        if (!response.ok) {
            return response.text().then(text => {
                console.log('Error response:', text);
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            });
        }
        
        return response.text().then(text => {
            console.log('Response text:', text);
            try {
                return JSON.parse(text);
            } catch (e) {
                console.error('Failed to parse JSON. Response was:', text.substring(0, 500));
                throw new Error('Invalid JSON response from server');
            }
        });
    })
    .then(data => {
        if (data.output) {
            outputElement.textContent = data.output;
        } else {
            outputElement.textContent = data.message || 'Cleanup completed';
        }
        
        if (data.success) {
            setTimeout(loadStats, 1000);
        }
    })
    .catch(error => {
        outputElement.textContent = 'Error: ' + error.message;
        console.error('Cleanup error:', error);
    });
}

// Load statistics when page loads
document.addEventListener('DOMContentLoaded', function() {
    loadStats();
});
</script>
@endpush
