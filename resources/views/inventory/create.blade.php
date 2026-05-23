@extends('layouts.app')

@section('header', 'Record Inventory Movement')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Record Inventory Movement</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('inventory.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back to Inventory
        </a>
    </div>
</div>

<div class="row">
    <!-- Movement Form -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Movement Details</h5>
            </div>
            <div class="card-body">
                <form id="movementForm">
                    @csrf
                    
                    <!-- Product Selection -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="product_id" class="form-label">Product *</label>
                            <select class="form-select" id="product_id" name="product_id" required>
                                <option value="">Select a product...</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" 
                                            data-quantity="{{ $product->quantity }}"
                                            data-min-stock="{{ $product->min_stock_level }}"
                                            data-unit="{{ $product->unit }}"
                                            data-cost="{{ $product->cost_price }}"
                                            {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                        {{ $product->name }} ({{ $product->quantity }} {{ $product->unit }} available)
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="type" class="form-label">Movement Type *</label>
                            <select class="form-select" id="type" name="type" required onchange="toggleAdjustmentType()">
                                <option value="">Select movement type...</option>
                                <option value="stock_in">Stock In</option>
                                <option value="stock_out">Stock Out</option>
                                <option value="adjustment">Adjustment</option>
                                <option value="stock_return">Stock Return</option>
                            </select>
                        </div>
                    </div>

                    <!-- Quantity and Adjustment Type -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="quantity" class="form-label">Quantity *</label>
                            <input type="number" class="form-control" id="quantity" name="quantity" 
                                   min="1" required placeholder="Enter quantity">
                        </div>
                        <div class="col-md-6" id="adjustmentTypeDiv" style="display: none;">
                            <label for="adjustment_type" class="form-label">Adjustment Type *</label>
                            <select class="form-select" id="adjustment_type" name="adjustment_type">
                                <option value="increase">Increase Stock</option>
                                <option value="decrease">Decrease Stock</option>
                            </select>
                        </div>
                    </div>

                    <!-- Unit Cost (for stock in) -->
                    <div class="row mb-3" id="unitCostDiv" style="display: none;">
                        <div class="col-md-6">
                            <label for="unit_cost" class="form-label">Unit Cost (Optional)</label>
                            <input type="number" class="form-control" id="unit_cost" name="unit_cost" 
                                   step="0.01" min="0" placeholder="Enter unit cost">
                        </div>
                    </div>

                    <!-- Current Stock Display -->
                    <div class="row mb-3" id="currentStockDiv" style="display: none;">
                        <div class="col-12">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Current Stock:</strong> <span id="currentStock">0</span> <span id="currentUnit"></span>
                                <br>
                                <strong>Min Stock Level:</strong> <span id="minStockLevel">0</span> <span id="minUnit"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Reason -->
                    <div class="mb-3">
                        <label for="reason" class="form-label">Reason *</label>
                        <input type="text" class="form-control" id="reason" name="reason" 
                               required placeholder="Enter reason for this movement">
                    </div>

                    <!-- Notes -->
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes (Optional)</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3" 
                                  placeholder="Add any additional notes..."></textarea>
                    </div>

                    <!-- Submit Button -->
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('inventory.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-1"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <i class="fas fa-save me-1"></i> Record Movement
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Preview & Summary -->
    <div class="col-md-4">
        <div class="card sticky-top" style="top: 20px;">
            <div class="card-header">
                <h5 class="mb-0">Movement Preview</h5>
            </div>
            <div class="card-body">
                <div id="previewContent">
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-eye fa-2x mb-2"></i>
                        <p>Select a product and movement type to see preview</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Loading Modal -->
<div class="modal fade" id="loadingModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center py-4">
                <div class="spinner-border text-primary mb-3" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <h5>Recording Movement...</h5>
                <p class="text-muted">Please wait while we record this inventory movement.</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function toggleAdjustmentType() {
    const type = document.getElementById('type').value;
    const adjustmentDiv = document.getElementById('adjustmentTypeDiv');
    const unitCostDiv = document.getElementById('unitCostDiv');
    
    if (type === 'adjustment') {
        adjustmentDiv.style.display = 'block';
        unitCostDiv.style.display = 'none';
    } else if (type === 'stock_in') {
        adjustmentDiv.style.display = 'none';
        unitCostDiv.style.display = 'block';
    } else {
        adjustmentDiv.style.display = 'none';
        unitCostDiv.style.display = 'none';
    }
    
    updatePreview();
}

function updateProductInfo() {
    const select = document.getElementById('product_id');
    const selectedOption = select.options[select.selectedIndex];
    
    if (select.value) {
        const currentStock = selectedOption.dataset.quantity;
        const minStockLevel = selectedOption.dataset.minStockLevel;
        const unit = selectedOption.dataset.unit;
        const cost = selectedOption.dataset.cost;
        
        document.getElementById('currentStock').textContent = currentStock;
        document.getElementById('currentUnit').textContent = unit;
        document.getElementById('minStockLevel').textContent = minStockLevel;
        document.getElementById('minUnit').textContent = unit;
        document.getElementById('currentStockDiv').style.display = 'block';
        
        if (cost) {
            document.getElementById('unit_cost').value = cost;
        }
    } else {
        document.getElementById('currentStockDiv').style.display = 'none';
    }
    
    updatePreview();
}

function validateStockQuantity() {
    const productId = document.getElementById('product_id').value;
    const type = document.getElementById('type').value;
    const quantity = parseInt(document.getElementById('quantity').value) || 0;
    const submitBtn = document.getElementById('submitBtn');
    const currentStockSpan = document.getElementById('currentStock');
    
    // More robust way to get current stock
    let currentStock = 0;
    if (currentStockSpan) {
        const stockText = currentStockSpan.textContent.trim();
        currentStock = parseInt(stockText) || 0;
    }
    
    // Debug logging (remove in production)
    console.log('Validation Debug:', {
        productId,
        type,
        quantity,
        currentStock,
        stockText: currentStockSpan?.textContent
    });
    
    let isValid = true;
    let errorMessage = '';
    
    // Only validate if we have a product selected and it's a stock-out movement
    if (productId && type === 'stock_out') {
        // Check if quantity exceeds current stock (but allow equal to current stock for 0 result)
        if (quantity > currentStock) {
            isValid = false;
            errorMessage = `Cannot stock out ${quantity} units. Only ${currentStock} units available. You can stock out up to ${currentStock} to empty the stock.`;
        }
    }
    
    // Enable/disable submit button
    submitBtn.disabled = !isValid;
    
    // Show/hide error message
    let errorDiv = document.getElementById('stockError');
    if (!isValid) {
        if (!errorDiv) {
            errorDiv = document.createElement('div');
            errorDiv.id = 'stockError';
            errorDiv.className = 'alert alert-danger mt-2';
            document.getElementById('quantity').parentNode.appendChild(errorDiv);
        }
        errorDiv.innerHTML = `<i class="fas fa-exclamation-triangle me-2"></i>${errorMessage}`;
    } else if (errorDiv) {
        errorDiv.remove();
    }
}

function updatePreview() {
    const productId = document.getElementById('product_id').value;
    const type = document.getElementById('type').value;
    const quantity = document.getElementById('quantity').value;
    const adjustmentType = document.getElementById('adjustment_type')?.value;
    
    const previewDiv = document.getElementById('previewContent');
    
    if (!productId || !type || !quantity) {
        previewDiv.innerHTML = `
            <div class="text-center text-muted py-4">
                <i class="fas fa-eye fa-2x mb-2"></i>
                <p>Select a product and movement type to see preview</p>
            </div>
        `;
        return;
    }
    
    const select = document.getElementById('product_id');
    const selectedOption = select.options[select.selectedIndex];
    const productName = selectedOption.text.split(' (')[0];
    const currentStock = parseInt(selectedOption.dataset.quantity);
    const unit = selectedOption.dataset.unit;
    
    let newStock = currentStock;
    let movementText = '';
    let movementClass = '';
    
    if (type === 'adjustment') {
        if (adjustmentType === 'increase') {
            newStock += parseInt(quantity);
            movementText = `+${quantity}`;
            movementClass = 'text-success';
        } else {
            newStock -= parseInt(quantity);
            movementText = `-${quantity}`;
            movementClass = 'text-danger';
        }
    } else if (type === 'stock_in' || type === 'stock_return') {
        newStock += parseInt(quantity);
        movementText = `+${quantity}`;
        movementClass = 'text-success';
    } else {
        newStock -= parseInt(quantity);
        movementText = `-${quantity}`;
        movementClass = 'text-danger';
    }
    
    previewDiv.innerHTML = `
        <div class="mb-3">
            <h6>Product</h6>
            <p class="mb-1"><strong>${productName}</strong></p>
        </div>
        
        <div class="mb-3">
            <h6>Movement</h6>
            <p class="mb-1">
                <span class="badge 
                    ${type === 'stock_in' ? 'bg-success' : 
                      type === 'stock_out' ? 'bg-danger' : 
                      type === 'sale' ? 'bg-primary' : 
                      type === 'adjustment' ? 'bg-warning' : 'bg-info'}">
                    ${type.replace('_', ' ').toUpperCase()}
                </span>
                <span class="${movementClass} ms-2">${movementText} ${unit}</span>
            </p>
        </div>
        
        <div class="mb-3">
            <h6>Stock Change</h6>
            <p class="mb-1">
                <span class="text-muted">From:</span> ${currentStock} ${unit}<br>
                <span class="text-muted">To:</span> <strong>${newStock} ${unit}</strong>
            </p>
        </div>
        
        ${newStock < 0 ? `
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>Warning:</strong> This will result in negative stock!
            </div>
        ` : ''}
        
        ${newStock <= parseInt(selectedOption.dataset.minStockLevel) ? `
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>Low Stock Alert:</strong> Stock will be below minimum level!
            </div>
        ` : ''}
    `;
}

// Event listeners
document.getElementById('product_id').addEventListener('change', function() {
    updateProductInfo();
    validateStockQuantity();
});
document.getElementById('type').addEventListener('change', function() {
    toggleAdjustmentType();
    updatePreview();
    validateStockQuantity();
});
document.getElementById('quantity').addEventListener('input', function() {
    updatePreview();
    validateStockQuantity();
});
document.getElementById('adjustment_type')?.addEventListener('change', updatePreview);

// Initial validation when page loads
document.addEventListener('DOMContentLoaded', function() {
    // If there's a pre-selected product, update info and validate
    if (document.getElementById('product_id').value) {
        updateProductInfo();
        validateStockQuantity();
    }
});

// Form submission
document.getElementById('movementForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const productId = document.getElementById('product_id').value;
    const type = document.getElementById('type').value;
    const quantity = document.getElementById('quantity').value;
    const reason = document.getElementById('reason').value;

    if (!productId || !type || !quantity || !reason) {
        alert('Please fill in all required fields.');
        return;
    }

    // Additional validation for stock-out (allow equal to current stock for 0 result)
    if (type === 'stock_out') {
        const currentStock = parseInt(document.getElementById('currentStock').textContent) || 0;
        const quantityNum = parseInt(quantity);
        
        if (quantityNum > currentStock) {
            alert(`Cannot stock out ${quantityNum} units. Only ${currentStock} units available. You can stock out up to ${currentStock} to empty the stock.`);
            return;
        }
    }

    // Show loading modal
    const modal = new bootstrap.Modal(document.getElementById('loadingModal'));
    modal.show();

    // Prepare form data
    const formData = {
        product_id: productId,
        type: type,
        quantity: parseInt(quantity),
        reason: reason,
        unit_cost: document.getElementById('unit_cost').value,
        notes: document.getElementById('notes').value
    };

    if (type === 'adjustment') {
        formData.adjustment_type = document.getElementById('adjustment_type').value;
    }

    // Submit movement
    fetch('{{ route("inventory.store") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        modal.hide();
        
        if (data.success) {
            alert('Inventory movement recorded successfully!');
            window.location.href = '{{ route("inventory.index") }}';
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        modal.hide();
        alert('Error recording movement: ' + error.message);
    });
});

// Initialize
updatePreview();
</script>
@endpush
