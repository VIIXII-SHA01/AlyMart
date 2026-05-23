@extends('layouts.app')

@section('header', $product->name)

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">{{ $product->name }}</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('products.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back to Products
        </a>
    </div>
</div>

<div class="row">
    <!-- Product Details -->
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-box me-2"></i>
                    Product Information
                </h5>
                <span class="badge 
                    @if($product->is_active) bg-success
                    @else bg-secondary @endif">
                    {{ $product->is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Product Name:</strong><br>{{ $product->name }}</p>
                        <p><strong>SKU:</strong><br>{{ $product->sku }}</p>
                        <p><strong>Category:</strong><br>{{ $product->category->name ?? 'Uncategorized' }}</p>
                        <p><strong>Unit:</strong><br>{{ $product->unit }}</p>
                        <p><strong>Barcode:</strong><br>{{ $product->barcode ?? 'Not set' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Selling Price:</strong><br> <span class="text-success fs-5">P{{ number_format($product->price, 2) }}</span></p>
                        <p><strong>Cost Price:</strong><br> <span class="text-info fs-5">P{{ number_format($product->cost_price, 2) }}</span></p>
                        <p><strong>Current Stock:</strong><br>
                            <span class="badge 
                                @if($product->quantity == 0) bg-danger
                                @elseif($product->quantity <= $product->min_stock_level) bg-warning
                                @else bg-success @endif">
                                {{ $product->quantity }} {{ $product->unit }}
                            </span>
                        </p>
                        <p><strong>Stock Levels:</strong><br>
                            Min: {{ $product->min_stock_level }} {{ $product->unit }} | 
                            Max: {{ $product->max_stock_level }} {{ $product->unit }}
                        </p>
                    </div>
                </div>
                
                @if($product->description)
                    <div class="mt-3">
                        <p><strong>Description:</strong><br>{{ $product->description }}</p>
                    </div>
                @endif

                <div class="text-end mt-3">
                    @if(auth()->user()->isAdmin() || auth()->user()->isInventoryStaff())
                        <a href="{{ route('products.edit', $product) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-1"></i> Edit Product
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Inventory Movements -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-exchange-alt me-2"></i>
                    Recent Inventory Movements
                </h5>
            </div>
            <div class="card-body">
                @if($product->inventoryMovements->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Quantity</th>
                                    <th>Previous</th>
                                    <th>New</th>
                                    <th>User</th>
                                    <th>Reason</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($product->inventoryMovements as $movement)
                                    <tr>
                                        <td>{{ $movement->created_at->format('M d, Y h:i A') }}</td>
                                        <td>
                                            <span class="badge 
                                                @if($movement->movement_type == 'stock_in') bg-success
                                                @elseif($movement->movement_type == 'stock_out') bg-danger
                                                @elseif($movement->movement_type == 'sale') bg-primary
                                                @elseif($movement->movement_type == 'adjustment') bg-warning
                                                @else bg-info @endif">
                                                {{ ucfirst(str_replace('_', ' ', $movement->movement_type)) }}
                                            </span>
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
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="text-end mt-3">
                        <a href="{{ route('inventory.index') }}?product_id={{ $product->id }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-history me-1"></i> View Full History
                        </a>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-exchange-alt fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No inventory movements recorded for this product.</p>
                        @if(auth()->user()->isAdmin() || auth()->user()->isInventoryStaff())
                            <a href="{{ route('inventory.create') }}?product_id={{ $product->id }}" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i> Record Movement
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Stock Status & Actions -->
    <div class="col-md-4">
        <!-- Stock Status -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Stock Status</h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <div class="stock-indicator mx-auto mb-2" style="width: 100px; height: 100px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2rem; font-weight: bold; color: white;
                        @if($product->quantity == 0) background-color: #dc3545;
                        @elseif($product->quantity <= $product->min_stock_level) background-color: #ffc107;
                        @else background-color: #28a745; @endif">
                        {{ $product->quantity }}
                    </div>
                    <h6 class="mb-1">{{ $product->name }}</h6>
                    <span class="badge 
                        @if($product->quantity == 0) bg-danger
                        @elseif($product->quantity <= $product->min_stock_level) bg-warning
                        @else bg-success @endif">
                        @if($product->quantity == 0) Out of Stock
                        @elseif($product->quantity <= $product->min_stock_level) Low Stock
                        @else In Stock @endif
                    </span>
                </div>
                
                <div class="mb-3">
                    <p class="mb-1"><strong>Current Quantity:</strong><br>{{ $product->quantity }} {{ $product->unit }}</p>
                    <p class="mb-1"><strong>Minimum Level:</strong><br>{{ $product->min_stock_level }} {{ $product->unit }}</p>
                    <p class="mb-1"><strong>Maximum Level:</strong><br>{{ $product->max_stock_level }} {{ $product->unit }}</p>
                    <p class="mb-0"><strong>Status:</strong><br>
                        @if($product->quantity == 0)
                            <span class="text-danger">Out of Stock - Immediate restocking required</span>
                        @elseif($product->quantity <= $product->min_stock_level)
                            <span class="text-warning">Low Stock - Consider restocking soon</span>
                        @else
                            <span class="text-success">Adequate Stock - No action needed</span>
                        @endif
                    </p>
                </div>

                @if(auth()->user()->isAdmin() || auth()->user()->isInventoryStaff())
                    <div class="d-grid gap-2">
                        <a href="{{ route('inventory.create') }}?product_id={{ $product->id }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i> Record Movement
                        </a>
                        <a href="{{ route('inventory.index') }}?product_id={{ $product->id }}" class="btn btn-outline-info">
                            <i class="fas fa-history me-2"></i> View History
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Pricing Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Pricing Information</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <p class="mb-1"><strong>Selling Price:</strong><br>
                        <span class="text-success fs-4">P{{ number_format($product->price, 2) }}</span>
                    </p>
                    <p class="mb-1"><strong>Cost Price:</strong><br>
                        <span class="text-info fs-4">P{{ number_format($product->cost_price, 2) }}</span>
                    </p>
                    <p class="mb-0"><strong>Profit Margin:</strong><br>
                        <span class="text-primary fs-4">
                            {{ number_format((($product->price - $product->cost_price) / $product->price) * 100, 1) }}%
                        </span>
                    </p>
                </div>

                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <small>
                        <strong>Profit per Unit:</strong> P{{ number_format($product->price - $product->cost_price, 2) }}<br>
                        <strong>Total Value:</strong> P{{ number_format($product->price * $product->quantity, 2) }}
                    </small>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    @if(auth()->user()->isAdmin() || auth()->user()->isInventoryStaff())
                        <a href="{{ route('products.edit', $product) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-2"></i> Edit Product
                        </a>
                        <a href="{{ route('inventory.create') }}?product_id={{ $product->id }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i> Add Stock
                        </a>
                    @endif
                    
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('inventory.create') }}?product_id={{ $product->id }}&type=stock_out" class="btn btn-danger">
                            <i class="fas fa-minus me-2"></i> Remove Stock
                        </a>
                    @endif
                    
                    <a href="{{ route('products.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i> Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
