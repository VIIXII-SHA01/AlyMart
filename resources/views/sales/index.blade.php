@extends('layouts.app')

@section('header', 'Sales Management')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Sales</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('sales.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> New Sale
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
                        <h4 class="mb-0">₱{{ number_format($todaySales, 2) }}</h4>
                        <small>Today's Sales</small>
                    </div>
                    <i class="fas fa-calendar-day fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">₱{{ number_format($weekSales, 2) }}</h4>
                        <small>This Week</small>
                    </div>
                    <i class="fas fa-calendar-week fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">₱{{ number_format($monthSales, 2) }}</h4>
                        <small>This Month</small>
                    </div>
                    <i class="fas fa-calendar-alt fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ $todayTransactions }}</h4>
                        <small>Today's Transactions</small>
                    </div>
                    <i class="fas fa-shopping-cart fa-2x opacity-75"></i>
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
                <label for="liveSearch" class="form-label">Search Sales</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text" class="form-control" id="liveSearch" 
                           placeholder="Search by transaction ID or customer..." autocomplete="off">
                </div>
                <small class="text-muted">Type to search sales in real-time</small>
            </div>
            <div class="col-md-3">
                <label for="statusFilter" class="form-label">Status</label>
                <select class="form-select" id="statusFilter">
                    <option value="">All Status</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                    <option value="refunded">Refunded</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="cashierFilter" class="form-label">Cashier</label>
                <select class="form-select" id="cashierFilter">
                    <option value="">All Cashiers</option>
                    @foreach($cashiers ?? [] as $cashier)
                        <option value="{{ $cashier->id }}">{{ $cashier->name }}</option>
                    @endforeach
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

<!-- Sales Table -->
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Transaction #</th>
                        <th>Date & Time</th>
                        <th>Cashier</th>
                        <th>Customer</th>
                        <th>Items</th>
                        <th>Total Amount</th>
                        <th>Payment</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sales as $sale)
                        <tr data-cashier-id="{{ $sale->user_id }}">
                            <td>
                                <strong>{{ $sale->transaction_number }}</strong>
                            </td>
                            <td>{{ $sale->created_at->format('M d, Y h:i A') }}</td>
                            <td>{{ $sale->user->name }}</td>
                            <td>{{ $sale->customer_name ?? 'Walk-in' }}</td>
                            <td>{{ $sale->items_count }} items</td>
                            <td class="fw-bold">₱{{ number_format($sale->total_amount, 2) }}</td>
                            <td>
                                <span class="badge bg-light text-dark">
                                    {{ ucfirst($sale->payment_method) }}
                                </span>
                            </td>
                            <td>
                                @if($sale->status == 'completed')
                                    <span class="badge bg-success">Completed</span>
                                @elseif($sale->status == 'cancelled')
                                    <span class="badge bg-danger">Cancelled</span>
                                @elseif($sale->status == 'refunded')
                                    <span class="badge bg-warning">Refunded</span>
                                @else
                                    <span class="badge bg-secondary">{{ ucfirst($sale->status) }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('sales.show', $sale) }}" class="btn btn-outline-primary" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('sales.receipt', $sale) }}" class="btn btn-outline-info" title="Receipt">
                                        <i class="fas fa-receipt"></i>
                                    </a>
                                    @if($sale->status == 'pending')
                                        <a href="{{ route('sales.edit', $sale) }}" class="btn btn-outline-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('sales.destroy', $sale) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" title="Delete" 
                                                    onclick="return confirm('Are you sure you want to delete this sale?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No sales found.</p>
                                <a href="{{ route('sales.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-1"></i> Create First Sale
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($sales->hasPages())
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-muted">
                    Showing {{ $sales->firstItem() }} to {{ $sales->lastItem() }} of {{ $sales->total() }} entries
                </div>
                {{ $sales->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const liveSearch = document.getElementById('liveSearch');
    const statusFilter = document.getElementById('statusFilter');
    const cashierFilter = document.getElementById('cashierFilter');
    const clearFilters = document.getElementById('clearFilters');
    const salesRows = document.querySelectorAll('tbody tr');
    const noResultsMessage = document.createElement('tr');
    noResultsMessage.innerHTML = `
        <td colspan="7" class="text-center py-4">
            <i class="fas fa-search fa-3x text-muted mb-3"></i>
            <p class="text-muted">No sales found matching your search criteria.</p>
        </td>
    `;

    function filterSales() {
        const searchTerm = liveSearch.value.toLowerCase();
        const statusValue = statusFilter.value;
        const cashierValue = cashierFilter.value;
        let visibleCount = 0;

        salesRows.forEach(row => {
            const transactionId = row.querySelector('td:nth-child(1)')?.textContent.toLowerCase() || '';
            const customer = row.querySelector('td:nth-child(4)')?.textContent.toLowerCase() || '';
            const cashier = row.querySelector('td:nth-child(3)')?.textContent.toLowerCase() || '';
            
            // Get status from badge text
            const statusBadge = row.querySelector('td:nth-child(8) .badge');
            const status = statusBadge ? statusBadge.textContent.toLowerCase() : '';
            
            // Check if sale matches search criteria
            const matchesSearch = searchTerm === '' || 
                                transactionId.includes(searchTerm) || 
                                customer.includes(searchTerm);
            
            // Fix status matching - use dropdown value directly
            let matchesStatus = true;
            if (statusValue !== '') {
                matchesStatus = status === statusValue.toLowerCase();
            }
            
            // Fix cashier matching - use dropdown value directly
            let matchesCashier = true;
            if (cashierValue !== '') {
                matchesCashier = row.dataset.cashierId === cashierValue;
            }
            
            if (matchesSearch && matchesStatus && matchesCashier) {
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
        const totalSales = salesRows.length;
        const showingElement = document.querySelector('.text-muted');
        
        if (showingElement) {
            if (visibleCount === totalSales) {
                showingElement.textContent = `Showing all ${totalSales} sales`;
            } else {
                showingElement.textContent = `Showing ${visibleCount} of ${totalSales} sales`;
            }
        }
    }

    // Event listeners
    liveSearch.addEventListener('input', filterSales);
    statusFilter.addEventListener('change', filterSales);
    cashierFilter.addEventListener('change', filterSales);

    clearFilters.addEventListener('click', function() {
        liveSearch.value = '';
        statusFilter.value = '';
        cashierFilter.value = '';
        filterSales();
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
