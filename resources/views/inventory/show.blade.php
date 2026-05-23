@extends('layouts.app')

@section('header', 'Inventory Movement Details')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Movement Details</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('inventory.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back to Inventory
        </a>
    </div>
</div>

<div class="row">
    <!-- Movement Information -->
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-exchange-alt me-2"></i>
                    Inventory Movement #{{ $movement->id }}
                </h5>
                <span class="badge 
                    @if($movement->movement_type == 'stock_in') bg-success
                    @elseif($movement->movement_type == 'stock_out') bg-danger
                    @elseif($movement->movement_type == 'sale') bg-primary
                    @elseif($movement->movement_type == 'adjustment') bg-warning
                    @else bg-info @endif">
                    {{ ucfirst(str_replace('_', ' ', $movement->movement_type)) }}
                </span>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Date & Time:</strong><br>{{ $movement->created_at->format('F d, Y h:i:s A') }}</p>
                        <p><strong>Product:</strong><br>
                            <strong>{{ $movement->product->name }}</strong>
                            <br><small class="text-muted">SKU: {{ $movement->product->sku }}</small>
                        </p>
                        <p><strong>Movement Type:</strong><br>
                            <span class="badge 
                                @if($movement->movement_type == 'stock_in') bg-success
                                @elseif($movement->movement_type == 'stock_out') bg-danger
                                @elseif($movement->movement_type == 'sale') bg-primary
                                @elseif($movement->movement_type == 'adjustment') bg-warning
                                @else bg-info @endif">
                                {{ ucfirst(str_replace('_', ' ', $movement->movement_type)) }}
                            </span>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Quantity Moved:</strong><br>
                            @if(in_array($movement->movement_type, ['stock_in', 'return']))
                                <span class="text-success fs-5">+{{ $movement->quantity }} {{ $movement->product->unit }}</span>
                            @else
                                <span class="text-danger fs-5">-{{ $movement->quantity }} {{ $movement->product->unit }}</span>
                            @endif
                        </p>
                        <p><strong>Stock Change:</strong><br>
                            <span class="text-muted">From:</span> {{ $movement->previous_quantity }} {{ $movement->product->unit }}<br>
                            <span class="text-muted">To:</span> <strong>{{ $movement->new_quantity }} {{ $movement->product->unit }}</strong>
                        </p>
                        <p><strong>Unit Cost:</strong><br>₱{{ number_format($movement->unit_cost, 2) }}</p>
                    </div>
                </div>
                
                <div class="row mt-3">
                    <div class="col-md-6">
                        <p><strong>Recorded By:</strong><br>{{ $movement->user->name }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Reason:</strong><br>{{ $movement->reason }}</p>
                    </div>
                </div>
                
                @if($movement->notes)
                    <div class="mt-3">
                        <strong>Additional Notes:</strong>
                        <p class="mb-0">{{ $movement->notes }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Product Details -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-box me-2"></i>
                    Product Information
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Product Name:</strong><br>{{ $movement->product->name }}</p>
                        <p><strong>SKU:</strong><br>{{ $movement->product->sku }}</p>
                        <p><strong>Category:</strong><br>{{ $movement->product->category->name ?? 'Uncategorized' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Current Stock:</strong><br>
                            <span class="fs-5 fw-bold 
                                @if($movement->product->quantity == 0) text-danger
                                @elseif($movement->product->quantity <= $movement->product->min_stock_level) text-warning
                                @else text-success @endif">
                                {{ $movement->product->quantity }} {{ $movement->product->unit }}
                            </span>
                        </p>
                        <p><strong>Min Stock Level:</strong><br>{{ $movement->product->min_stock_level }} {{ $movement->product->unit }}</p>
                        <p><strong>Status:</strong><br>
                            @if($movement->product->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </p>
                    </div>
                </div>
                
                <div class="row mt-3">
                    <div class="col-md-6">
                        <p><strong>Cost Price:</strong><br>₱{{ number_format($movement->product->cost_price, 2) }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Selling Price:</strong><br>₱{{ number_format($movement->product->selling_price, 2) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions & Summary -->
    <div class="col-md-4">
        <!-- Movement Impact -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Movement Impact</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Previous Stock:</span>
                        <span>{{ $movement->previous_quantity }} {{ $movement->product->unit }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Quantity Moved:</span>
                        <span>
                            @if(in_array($movement->movement_type, ['stock_in', 'return']))
                                <span class="text-success">+{{ $movement->quantity }}</span>
                            @else
                                <span class="text-danger">-{{ $movement->quantity }}</span>
                            @endif
                        </span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>New Stock:</span>
                        <strong>{{ $movement->new_quantity }} {{ $movement->product->unit }}</strong>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <strong>Net Change:</strong>
                        <strong class="
                            @if($movement->new_quantity > $movement->previous_quantity) text-success
                            @elseif($movement->new_quantity < $movement->previous_quantity) text-danger
                            @else text-muted @endif">
                            {{ $movement->new_quantity - $movement->previous_quantity > 0 ? '+' : '' }}{{ $movement->new_quantity - $movement->previous_quantity }}
                        </strong>
                    </div>
                </div>
                
                @if($movement->new_quantity <= $movement->product->min_stock_level)
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Low Stock Alert</strong><br>
                        <small>Stock is at or below minimum level!</small>
                    </div>
                @endif
                
                @if($movement->new_quantity == 0)
                    <div class="alert alert-danger">
                        <i class="fas fa-times-circle me-2"></i>
                        <strong>Out of Stock</strong><br>
                        <small>This product is now out of stock!</small>
                    </div>
                @endif
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('inventory.create') }}?product_id={{ $movement->product_id }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i> Record Another Movement
                    </a>
                    <a href="{{ route('products.show', $movement->product) }}" class="btn btn-outline-info">
                        <i class="fas fa-box me-2"></i> View Product
                    </a>
                    <a href="{{ route('inventory.index') }}?product_id={{ $movement->product_id }}" class="btn btn-outline-secondary">
                        <i class="fas fa-history me-2"></i> View Product History
                    </a>
                </div>
            </div>
        </div>

        <!-- Movement Info -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Movement Information</h5>
            </div>
            <div class="card-body">
                <p class="mb-2"><strong>Movement ID:</strong><br>#{{ $movement->id }}</p>
                <p class="mb-2"><strong>Recorded By:</strong><br>{{ $movement->user->name }}</p>
                <p class="mb-2"><strong>Date:</strong><br>{{ $movement->created_at->format('M d, Y h:i A') }}</p>
                <p class="mb-0"><strong>Reason:</strong><br>{{ $movement->reason }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
