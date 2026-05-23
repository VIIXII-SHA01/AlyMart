@extends('layouts.app')

@section('header', 'Low Stock Products')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Low Stock Products</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('inventory.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back to Inventory
        </a>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>Low Stock Alert:</strong> Showing products that are at or below their minimum stock level. 
            Consider restocking these items soon to avoid running out of stock.
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>SKU</th>
                        <th>Category</th>
                        <th>Current Stock</th>
                        <th>Min Level</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($lowStockProducts as $product)
                        <tr>
                            <td>
                                <strong>{{ $product->name }}</strong>
                                @if(!$product->is_active)
                                    <span class="badge bg-secondary ms-2">Inactive</span>
                                @endif
                            </td>
                            <td>{{ $product->sku }}</td>
                            <td>{{ $product->category->name ?? 'Uncategorized' }}</td>
                            <td>
                                <span class="badge 
                                    @if($product->quantity == 0) bg-danger
                                    @else bg-warning @endif">
                                    {{ $product->quantity }} {{ $product->unit }}
                                </span>
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
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('inventory.create') }}?product_id={{ $product->id }}" 
                                       class="btn btn-primary" title="Restock">
                                        <i class="fas fa-plus"></i>
                                    </a>
                                    <a href="{{ route('products.show', $product) }}" 
                                       class="btn btn-outline-info" title="View Product">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('inventory.index') }}?product_id={{ $product->id }}" 
                                       class="btn btn-outline-secondary" title="View History">
                                        <i class="fas fa-history"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                                <p class="text-muted">Great! No products are currently low on stock.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($lowStockProducts->hasPages())
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-muted">
                    Showing {{ $lowStockProducts->firstItem() }} to {{ $lowStockProducts->lastItem() }} of {{ $lowStockProducts->total() }} products
                </div>
                {{ $lowStockProducts->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
