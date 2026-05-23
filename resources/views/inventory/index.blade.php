@extends('layouts.app')

@section('header', 'Inventory Management')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Inventory Management</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('inventory.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Stock Movement
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
                        <h4 class="mb-0">{{ $totalProducts }}</h4>
                        <small>Total Products</small>
                    </div>
                    <i class="fas fa-box fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ $activeProducts }}</h4>
                        <small>Active Products</small>
                    </div>
                    <i class="fas fa-check-circle fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ $lowStockProducts }}</h4>
                        <small>Low Stock</small>
                    </div>
                    <i class="fas fa-exclamation-triangle fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-danger text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ $outOfStockProducts }}</h4>
                        <small>Out of Stock</small>
                    </div>
                    <i class="fas fa-times-circle fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Today's Activity -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-line me-2"></i>
                    Today's Activity
                </h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-3">
                        <h5 class="text-primary">{{ $todayMovements }}</h5>
                        <small class="text-muted">Total Movements</small>
                    </div>
                    <div class="col-md-3">
                        <h5 class="text-success">+{{ $stockInToday }}</h5>
                        <small class="text-muted">Stock In</small>
                    </div>
                    <div class="col-md-3">
                        <h5 class="text-danger">-{{ $stockOutToday }}</h5>
                        <small class="text-muted">Stock Out</small>
                    </div>
                    <div class="col-md-3">
                        <h5 class="text-info">{{ $stockInToday - $stockOutToday }}</h5>
                        <small class="text-muted">Net Change</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Low Stock Alerts (Showing max 5 items) -->
@if($lowStockAlerts->count() > 0)
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card border-warning">
            <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Low Stock Alerts
                </h5>
                <span class="badge bg-dark text-white">{{ $lowStockAlerts->count() }} / 5</span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Current Stock</th>
                                <th>Min Level</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($lowStockAlerts as $product)
                                <tr>
                                    <td>
                                        <strong>{{ $product->name }}</strong>
                                        <br><small class="text-muted">{{ $product->sku }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-warning">{{ $product->quantity }} {{ $product->unit }}</span>
                                    </td>
                                    <td>{{ $product->min_stock_level }} {{ $product->unit }}</td>
                                    <td>
                                        @if($product->quantity == 0)
                                            <span class="badge bg-danger">Out of Stock</span>
                                        @else
                                            <span class="badge bg-warning">Low Stock</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('inventory.create') }}?product_id={{ $product->id }}" 
                                           class="btn btn-sm btn-primary">
                                            <i class="fas fa-plus"></i> Restock
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="text-end mt-2">
                    <a href="{{ route('inventory.low-stock') }}" class="btn btn-warning btn-sm">
                        View All Low Stock Products
                        <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Out of Stock Alerts (Showing max 5 items) -->
@if($outOfStockAlerts->count() > 0)
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card border-danger">
            <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-times-circle me-2"></i>
                    Out of Stock Products
                </h5>
                <span class="badge bg-light text-dark">{{ $outOfStockAlerts->count() }} / 5</span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Current Stock</th>
                                <th>Min Level</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($outOfStockAlerts as $product)
                                <tr>
                                    <td>
                                        <strong>{{ $product->name }}</strong>
                                        <br><small class="text-muted">{{ $product->sku }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-danger">{{ $product->quantity }}</span>
                                    </td>
                                    <td>{{ $product->min_stock_level }}</td>
                                    <td>
                                        <span class="badge bg-danger">Out of Stock</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('inventory.create') }}?product_id={{ $product->id }}" 
                                           class="btn btn-sm btn-primary">
                                            <i class="fas fa-plus"></i> Restock
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="text-end mt-2">
                    <a href="{{ route('inventory.out-of-stock') }}" class="btn btn-danger btn-sm">
                        View All Out of Stock Products
                        <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-3">
                <label for="liveSearch" class="form-label">Search Movements</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text" class="form-control" id="liveSearch" 
                           placeholder="Search by product or user..." autocomplete="off">
                </div>
                <small class="text-muted">Type to search movements in real-time</small>
            </div>
            <div class="col-md-3">
                <label for="typeFilter" class="form-label">Movement Type</label>
                <select class="form-select" id="typeFilter">
                    <option value="">All Types</option>
                    <option value="stock_in">Stock In</option>
                    <option value="stock_out">Stock Out</option>
                    <option value="sale">Sale</option>
                    <option value="adjustment">Adjustment</option>
                    <option value="return">Stock Return</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="productFilter" class="form-label">Product</label>
                <select class="form-select" id="productFilter">
                    <option value="">All Products</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">&nbsp;</label><br>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-secondary" id="clearFilters">
                        <i class="fas fa-times me-1"></i> Clear All
                    </button>
                    <a href="{{ route('inventory.reports') }}" class="btn btn-outline-info">
                        <i class="fas fa-chart-bar me-1"></i> Reports
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Inventory Movements Table -->
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Date & Time</th>
                        <th>Product</th>
                        <th>Type</th>
                        <th>Quantity</th>
                        <th>Previous</th>
                        <th>New</th>
                        <th>User</th>
                        <th>Reason</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($movements as $movement)
                        <tr>
                            <td>{{ $movement->created_at->format('M d, Y h:i A') }}</td>
                            <td>
                                <strong>{{ $movement->product->name }}</strong>
                                <br><small class="text-muted">{{ $movement->product->sku }}</small>
                            </td>
                            <td>
                                @if($movement->movement_type == 'stock_in')
                                    <span class="badge bg-success">Stock In</span>
                                @elseif($movement->movement_type == 'stock_out')
                                    <span class="badge bg-danger">Stock Out</span>
                                @elseif($movement->movement_type == 'sale')
                                    <span class="badge bg-primary">Sale</span>
                                @elseif($movement->movement_type == 'adjustment')
                                    <span class="badge bg-warning">Adjustment</span>
                                @else
                                    <span class="badge bg-info">Return</span>
                                @endif
                            </td>
                            <td>
                                @if(in_array($movement->movement_type, ['stock_in', 'return']))
                                    <span class="text-success">+{{ $movement->quantity }}</span>
                                @else
                                    <span class="text-danger">-{{ $movement->quantity }}</span>
                                @endif
                            </td>
                            <td>{{ $movement->previous_quantity }}</td>
                            <td class="fw-bold">{{ $movement->new_quantity }}</td>
                            <td>{{ $movement->user->name }}</td>
                            <td>{{ $movement->reason }}</td>
                            <td>
                                <a href="{{ route('inventory.show', $movement) }}" class="btn btn-sm btn-outline-primary" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                <i class="fas fa-box fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No inventory movements found.</p>
                                <a href="{{ route('inventory.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-1"></i> Record First Movement
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

            </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Real-time search functionality
    const liveSearch = document.getElementById('liveSearch');
    const typeFilter = document.getElementById('typeFilter');
    const productFilter = document.getElementById('productFilter');
    const clearFilters = document.getElementById('clearFilters');
    const movementRows = document.querySelectorAll('tbody tr');
    const noResultsMessage = document.createElement('tr');
    noResultsMessage.innerHTML = `
        <td colspan="8" class="text-center py-4">
            <i class="fas fa-search fa-3x text-muted mb-3"></i>
            <p class="text-muted">No inventory movements found matching your search criteria.</p>
        </td>
    `;

    function filterMovements() {
        const searchTerm = liveSearch.value.toLowerCase();
        const typeValue = typeFilter.value;
        const productValue = productFilter.value;
        let visibleCount = 0;

        movementRows.forEach(row => {
            const productName = row.querySelector('td:nth-child(2)')?.textContent.toLowerCase() || '';
            const movementType = row.querySelector('td:nth-child(3)')?.textContent.toLowerCase() || '';
            const quantity = row.querySelector('td:nth-child(4)')?.textContent.toLowerCase() || '';
            const userName = row.querySelector('td:nth-child(6)')?.textContent.toLowerCase() || '';
            
            // Check if movement matches search criteria
            const matchesSearch = searchTerm === '' || 
                                productName.includes(searchTerm) || 
                                userName.includes(searchTerm);
            
            // Fix type matching - check if type is selected and if it matches
            let matchesType = true;
            if (typeValue !== '') {
                const selectedTypeText = typeFilter.options[typeFilter.selectedIndex]?.text.toLowerCase() || '';
                matchesType = movementType.includes(selectedTypeText);
            }
            
            // Fix product matching - check if product is selected and if it matches
            let matchesProduct = true;
            if (productValue !== '') {
                const selectedProductText = productFilter.options[productFilter.selectedIndex]?.text.toLowerCase() || '';
                matchesProduct = productName.includes(selectedProductText);
            }
            
            if (matchesSearch && matchesType && matchesProduct) {
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

        // Update showing count - disabled to prevent pagination interference
        // updateShowingCount(visibleCount);
    }

    function updateShowingCount(visibleCount) {
        const totalMovements = movementRows.length;
        // Look for the pagination info div within the inventory table card
        const showingElement = document.querySelector('.table-responsive + .d-flex .text-muted');
        
        if (showingElement) {
            if (visibleCount === totalMovements) {
                showingElement.textContent = `Showing all ${totalMovements} movements`;
            } else {
                showingElement.textContent = `Showing ${visibleCount} of ${totalMovements} movements`;
            }
        }
    }

    // Event listeners
    liveSearch.addEventListener('input', filterMovements);
    typeFilter.addEventListener('change', filterMovements);
    productFilter.addEventListener('change', filterMovements);

    clearFilters.addEventListener('click', function() {
        liveSearch.value = '';
        typeFilter.value = '';
        productFilter.value = '';
        filterMovements();
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
