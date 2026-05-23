@extends('layouts.app')

@section('header', 'Out of Stock Products')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Out of Stock Products</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('inventory.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back to Inventory
        </a>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <div class="alert alert-danger">
            <i class="fas fa-times-circle me-2"></i>
            <strong>Critical:</strong> These products are completely out of stock and cannot be sold. 
            Immediate restocking is required to continue sales.
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
                        <th>Min Level</th>
                        <th>Cost Price</th>
                        <th>Selling Price</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($outOfStockProducts as $product)
                        <tr>
                            <td>
                                <strong>{{ $product->name }}</strong>
                                @if(!$product->is_active)
                                    <span class="badge bg-secondary ms-2">Inactive</span>
                                @endif
                            </td>
                            <td>{{ $product->sku }}</td>
                            <td>{{ $product->category->name ?? 'Uncategorized' }}</td>
                            <td>{{ $product->min_stock_level }} {{ $product->unit }}</td>
                            <td>₱{{ number_format($product->cost_price, 2) }}</td>
                            <td>₱{{ number_format($product->selling_price, 2) }}</td>
                            <td>
                                <span class="badge bg-danger">Out of Stock</span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('inventory.create') }}?product_id={{ $product->id }}" 
                                       class="btn btn-success" title="Restock Now">
                                        <i class="fas fa-plus"></i> Restock
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
                            <td colspan="8" class="text-center py-4">
                                <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                                <p class="text-muted">Excellent! All products are currently in stock.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($outOfStockProducts->hasPages())
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-muted">
                    Showing {{ $outOfStockProducts->firstItem() }} to {{ $outOfStockProducts->lastItem() }} of {{ $outOfStockProducts->total() }} products
                </div>
                {{ $outOfStockProducts->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
