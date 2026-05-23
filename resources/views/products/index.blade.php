@extends('layouts.app')

@section('header', 'Products')

@section('content')
<style>
    .products-container {
        display: flex;
        flex-direction: column;
        height: calc(100vh - 100px);
        gap: 1rem;
    }
    
    .products-filters {
        flex-shrink: 0;
    }
    
    .products-table-wrapper {
        flex: 1;
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }
    
    .products-table-wrapper .card {
        display: flex;
        flex-direction: column;
        height: 100%;
    }
    
    .products-table-wrapper .card-body {
        display: flex;
        flex-direction: column;
        overflow: hidden;
        height: 100%;
    }
    
    .products-table-wrapper .table-responsive {
        flex: 1;
        overflow-y: auto;
    }
    
    .products-pagination {
        flex-shrink: 0;
        padding-top: 1rem;
        border-top: 1px solid #dee2e6;
    }
    
    .products-table-wrapper thead {
        position: sticky;
        top: 0;
        z-index: 10;
    }
    
    .products-table-wrapper thead th {
        background-color: #212529 !important;
        position: sticky;
        top: 0;
        z-index: 10;
    }
</style>

<div class="products-page">
<div class="products-container">
    <div>
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Products</h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                <a href="{{ route('products.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i> Add Product
                </a>
            </div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="card products-filters">
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <label for="liveSearch" class="form-label">Search Products</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text" class="form-control" id="liveSearch" 
                           placeholder="Search by name, SKU, or description..." autocomplete="off">
                </div>
                <small class="text-muted">Type to search products in real-time</small>
            </div>
            <div class="col-md-3">
                <label for="categoryFilter" class="form-label">Category</label>
                <select class="form-select" id="categoryFilter">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="stockFilter" class="form-label">Stock Status</label>
                <select class="form-select" id="stockFilter">
                    <option value="">All Status</option>
                    <option value="in_stock">In Stock</option>
                    <option value="low_stock">Low Stock</option>
                    <option value="out_of_stock">Out of Stock</option>
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

<!-- Products Table -->
<div class="products-table-wrapper">
    <div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Image</th>
                        <th>SKU</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td>
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="img-thumbnail" style="max-width: 50px; max-height: 50px;">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                        <i class="fas fa-box text-muted"></i>
                                    </div>
                                @endif
                            </td>
                            <td><code>{{ $product->sku }}</code></td>
                            <td>
                                <strong>{{ $product->name }}</strong>
                                @if($product->description)
                                    <br><small class="text-muted">{{ Str::limit($product->description, 50) }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge" style="background-color: {{ $product->category->color ?? '#6c757d' }}">
                                    {{ $product->category->name }}
                                </span>
                            </td>
                            <td>PHP {{ number_format($product->price, 2) }}</td>
                            <td>
                                @if($product->quantity <= 0)
                                    <span class="badge bg-danger">{{ $product->quantity }}</span>
                                @elseif($product->quantity <= $product->min_stock_level)
                                    <span class="badge bg-warning">{{ $product->quantity }}</span>
                                @else
                                    <span class="badge bg-success">{{ $product->quantity }}</span>
                                @endif
                                <br><small class="text-muted">{{ $product->unit }}</small>
                            </td>
                            <td>
                                @if($product->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('products.show', $product) }}" class="btn btn-outline-primary" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('products.edit', $product) }}" class="btn btn-outline-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if(auth()->user()->isAdmin())
                                        <form method="POST" action="{{ route('products.destroy', $product) }}" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this product?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                No products found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="products-pagination d-flex justify-content-between align-items-center">
            <div>
                Showing {{ $products->firstItem() }} to {{ $products->lastItem() }} of {{ $products->total() }} products
            </div>
            {{ $products->links() }}
        </div>
        
    </div>
</div>
</div>
</div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const liveSearch = document.getElementById('liveSearch');
    const categoryFilter = document.getElementById('categoryFilter');
    const stockFilter = document.getElementById('stockFilter');
    const clearFilters = document.getElementById('clearFilters');
    const productRows = document.querySelectorAll('tbody tr');
    const noResultsMessage = document.createElement('tr');
    noResultsMessage.innerHTML = `
        <td colspan="8" class="text-center py-4">
            <i class="fas fa-search fa-3x text-muted mb-3"></i>
            <p class="text-muted">No products found matching your search criteria.</p>
        </td>
    `;

    function filterProducts() {
        const searchTerm = liveSearch.value.toLowerCase();
        const categoryValue = categoryFilter.value;
        const stockValue = stockFilter.value;
        let visibleCount = 0;

        productRows.forEach(row => {
            const productName = row.querySelector('td:nth-child(3)')?.textContent.toLowerCase() || '';
            const productSku = row.querySelector('td:nth-child(2)')?.textContent.toLowerCase() || '';
            const productCategory = row.querySelector('td:nth-child(4)')?.textContent.toLowerCase() || '';
            const productStock = row.querySelector('td:nth-child(6)')?.textContent.toLowerCase() || '';
            
            // Check if product matches search criteria
            const matchesSearch = searchTerm === '' || 
                                productName.includes(searchTerm) || 
                                productSku.includes(searchTerm);
            
            // Fix category matching - check if category is selected and if it matches
            let matchesCategory = true;
            if (categoryValue !== '') {
                // Get the selected category text from the dropdown
                const selectedCategoryText = categoryFilter.options[categoryFilter.selectedIndex]?.text.toLowerCase() || '';
                matchesCategory = productCategory.includes(selectedCategoryText);
            }
            
            // Fix stock status filtering - check badge classes instead of text
            let matchesStock = true;
            if (stockValue !== '') {
                const stockCell = row.querySelector('td:nth-child(6)');
                const stockBadge = stockCell?.querySelector('.badge');
                const badgeClass = stockBadge?.className || '';
                
                if (stockValue === 'in_stock') {
                    matchesStock = badgeClass.includes('bg-success');
                } else if (stockValue === 'low_stock') {
                    matchesStock = badgeClass.includes('bg-warning');
                } else if (stockValue === 'out_of_stock') {
                    matchesStock = badgeClass.includes('bg-danger');
                }
            }
            
            if (matchesSearch && matchesCategory && matchesStock) {
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
        const totalProducts = productRows.length;
        const showingElement = document.querySelector('.d-flex.justify-content-between.align-items-center.mt-3 div');
        
        if (showingElement) {
            if (visibleCount === totalProducts) {
                showingElement.textContent = `Showing all ${totalProducts} products`;
            } else {
                showingElement.textContent = `Showing ${visibleCount} of ${totalProducts} products`;
            }
        }
    }

    // Event listeners
    liveSearch.addEventListener('input', filterProducts);
    categoryFilter.addEventListener('change', filterProducts);
    stockFilter.addEventListener('change', filterProducts);

    clearFilters.addEventListener('click', function() {
        liveSearch.value = '';
        categoryFilter.value = '';
        stockFilter.value = '';
        filterProducts();
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
