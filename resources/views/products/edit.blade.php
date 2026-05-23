@extends('layouts.app')

@section('header', 'Edit Product - ' . $product->name)

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Edit Product</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('products.show', $product) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back to Product
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Edit Product Information</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('products.update', $product) }}">
                    @csrf
                    @method('PUT')
                    
                    <!-- Basic Information -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="mb-3">Basic Information</h6>
                            
                            <!-- Name -->
                            <div class="mb-3">
                                <label for="name" class="form-label">Product Name *</label>
                                <input type="text" class="form-control" id="name" name="name" 
                                       value="{{ old('name', $product->name) }}" required autofocus>
                                @error('name')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- SKU -->
                            <div class="mb-3">
                                <label for="sku" class="form-label">SKU *</label>
                                <input type="text" class="form-control" id="sku" name="sku" 
                                       value="{{ old('sku', $product->sku) }}" required>
                                @error('sku')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Category -->
                            <div class="mb-3">
                                <label for="category_id" class="form-label">Category *</label>
                                <select class="form-select" id="category_id" name="category_id" required>
                                    <option value="">Select a category...</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" 
                                                {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Unit -->
                            <div class="mb-3">
                                <label for="unit" class="form-label">Unit *</label>
                                <select class="form-select" id="unit" name="unit" required>
                                    <option value="">Select unit...</option>
                                    <option value="pieces" {{ old('unit', $product->unit) == 'pieces' ? 'selected' : '' }}>Pieces</option>
                                    <option value="box" {{ old('unit', $product->unit) == 'box' ? 'selected' : '' }}>Box</option>
                                    <option value="carton" {{ old('unit', $product->unit) == 'carton' ? 'selected' : '' }}>Carton</option>
                                    <option value="bottle" {{ old('unit', $product->unit) == 'bottle' ? 'selected' : '' }}>Bottle</option>
                                    <option value="pack" {{ old('unit', $product->unit) == 'pack' ? 'selected' : '' }}>Pack</option>
                                    <option value="kg" {{ old('unit', $product->unit) == 'kg' ? 'selected' : '' }}>Kilogram</option>
                                    <option value="liter" {{ old('unit', $product->unit) == 'liter' ? 'selected' : '' }}>Liter</option>
                                    <option value="meter" {{ old('unit', $product->unit) == 'meter' ? 'selected' : '' }}>Meter</option>
                                </select>
                                @error('unit')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <h6 class="mb-3">Pricing Information</h6>
                            
                            <!-- Selling Price -->
                            <div class="mb-3">
                                <label for="price" class="form-label">Selling Price (P) *</label>
                                <input type="number" class="form-control" id="price" name="price" 
                                       value="{{ old('price', $product->price) }}" step="0.01" min="0" required>
                                @error('price')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Cost Price -->
                            <div class="mb-3">
                                <label for="cost_price" class="form-label">Cost Price (P) *</label>
                                <input type="number" class="form-control" id="cost_price" name="cost_price" 
                                       value="{{ old('cost_price', $product->cost_price) }}" step="0.01" min="0" required>
                                @error('cost_price')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Barcode -->
                            <div class="mb-3">
                                <label for="barcode" class="form-label">Barcode</label>
                                <input type="text" class="form-control" id="barcode" name="barcode" 
                                       value="{{ old('barcode', $product->barcode) }}" placeholder="Optional">
                                @error('barcode')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                           value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        Active Product
                                    </label>
                                </div>
                                <small class="text-muted">Uncheck to deactivate this product</small>
                            </div>
                        </div>
                    </div>

                    <!-- Stock Information -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h6 class="mb-3">Stock Information</h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="quantity" class="form-label">Current Quantity *</label>
                                        <input type="number" class="form-control" id="quantity" name="quantity" 
                                               value="{{ old('quantity', $product->quantity) }}" min="0" required>
                                        @error('quantity')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="min_stock_level" class="form-label">Min Stock Level *</label>
                                        <input type="number" class="form-control" id="min_stock_level" name="min_stock_level" 
                                               value="{{ old('min_stock_level', $product->min_stock_level) }}" min="0" required>
                                        @error('min_stock_level')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="max_stock_level" class="form-label">Max Stock Level *</label>
                                        <input type="number" class="form-control" id="max_stock_level" name="max_stock_level" 
                                               value="{{ old('max_stock_level', $product->max_stock_level) }}" min="0" required>
                                        @error('max_stock_level')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mb-4">
                        <h6 class="mb-3">Product Description</h6>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="4" 
                                      placeholder="Enter product description">{{ old('description', $product->description) }}</textarea>
                            @error('description')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('products.show', $product) }}" class="btn btn-secondary">
                            <i class="fas fa-times me-1"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Update Product
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Current Product Info -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Current Information</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <p class="mb-1"><strong>Name:</strong><br>{{ $product->name }}</p>
                    <p class="mb-1"><strong>SKU:</strong><br>{{ $product->sku }}</p>
                    <p class="mb-1"><strong>Category:</strong><br>{{ $product->category->name ?? 'Uncategorized' }}</p>
                    <p class="mb-1"><strong>Unit:</strong><br>{{ $product->unit }}</p>
                    <p class="mb-1"><strong>Barcode:</strong><br>{{ $product->barcode ?? 'Not set' }}</p>
                    <p class="mb-1"><strong>Status:</strong><br>
                        <span class="badge 
                            @if($product->is_active) bg-success
                            @else bg-secondary @endif">
                            {{ $product->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </p>
                </div>
            </div>
        </div>

        <!-- Stock Status -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Stock Status</h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <div class="stock-indicator mx-auto mb-2" style="width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; font-weight: bold; color: white;
                        @if($product->quantity == 0) background-color: #dc3545;
                        @elseif($product->quantity <= $product->min_stock_level) background-color: #ffc107;
                        @else background-color: #28a745; @endif">
                        {{ $product->quantity }}
                    </div>
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
                </div>

                <div class="d-grid gap-2">
                    <a href="{{ route('inventory.create') }}?product_id={{ $product->id }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus me-1"></i> Add Stock
                    </a>
                    <a href="{{ route('inventory.index') }}?product_id={{ $product->id }}" class="btn btn-outline-info btn-sm">
                        <i class="fas fa-history me-1"></i> View History
                    </a>
                </div>
            </div>
        </div>

        <!-- Pricing Information -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Current Pricing</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <p class="mb-1"><strong>Selling Price:</strong><br>
                        <span class="text-success fs-5">P{{ number_format($product->price, 2) }}</span>
                    </p>
                    <p class="mb-1"><strong>Cost Price:</strong><br>
                        <span class="text-info fs-5">P{{ number_format($product->cost_price, 2) }}</span>
                    </p>
                    <p class="mb-0"><strong>Profit Margin:</strong><br>
                        <span class="text-primary fs-5">
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
    </div>
</div>
@endsection
