@extends('layouts.app')

@section('header', 'Create New Sale')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">New Sale</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('sales.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back to Sales
        </a>
    </div>
</div>

<style>
    .sales-container-wrapper {
        height: 90vh;
        display: flex;
        overflow: hidden;
        gap: 1.5rem;
    }
    
    .sales-form-column {
        flex: 1;
        min-width: 0;
        padding-right: 0.5rem;
    }
    
    .sales-summary-column {
        width: 400px;
        overflow-y: auto;
    }
    
    .sales-summary-column::-webkit-scrollbar {
        width: 8px;
    }
    
    .sales-summary-column::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    
    .sales-summary-column::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 10px;
    }
    
    .sales-summary-column::-webkit-scrollbar-thumb:hover {
        background: #555;
    }
    
    .card-body {
        overflow: visible;
    }
    
    .form-section {
        flex-shrink: 0;
    }
    
    #itemsTable {
        min-height: 300px;
    }
    
    .table-responsive {
        display: block !important;
        width: 100%;
        overflow-x: auto !important;
        overflow-y: auto !important;
        max-height: 500px !important;
        border: 1px solid #dee2e6;
        border-radius: 0.375rem;
        margin-bottom: 1rem;
    }
    
    .table-responsive table {
        width: 100%;
        margin-bottom: 0 !important;
    }
    
    .table-responsive thead {
        position: sticky !important;
        top: 0 !important;
        background-color: #f8f9fa !important;
        z-index: 10;
    }
    
    .table-responsive thead th {
        background-color: #f8f9fa !important;
        border-bottom: 2px solid #dee2e6 !important;
    }
    
    .table-responsive::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }
    
    .table-responsive::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    
    .table-responsive::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 10px;
    }
    
    .table-responsive::-webkit-scrollbar-thumb:hover {
        background: #555;
    }
    
    @media (max-width: 768px) {
        .sales-container-wrapper {
            height: auto;
            display: block;
            overflow: visible;
        }
        
        .sales-form-column {
            margin-bottom: 2rem;
            padding-right: 0;
            overflow: visible;
        }
        
        .sales-summary-column {
            width: 100%;
            overflow: visible;
        }
    }
    
    /* Barcode Scanner Styles */
    #barcodeInput {
        border: 2px solid #007bff;
        background-color: #f0f8ff;
        font-size: 1.1rem;
        padding: 0.75rem 1rem;
    }
    
    #barcodeInput:focus {
        border-color: #0056b3;
        box-shadow: 0 0 0 0.2rem rgba(0, 86, 179, 0.25);
        background-color: #fff;
    }
    
    #barcodeInput::placeholder {
        color: #6c757d;
        font-style: italic;
    }

</style>

<div class="sales-container-wrapper">
    <!-- Sale Form -->
    <div class="sales-form-column">
        <form id="saleForm">
            @csrf
            
            <!-- Customer Information -->
            <div class="mb-4">
                <h6 class="text-muted small text-uppercase fw-bold mb-3">Customer Information</h6>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="customer_name" class="form-label">Customer Name (Optional)</label>
                        <input type="text" class="form-control" id="customer_name" name="customer_name" 
                               placeholder="Walk-in customer">
                    </div>
                    <div class="col-md-6">
                        <label for="payment_method" class="form-label">Payment Method</label>
                        <select class="form-select" id="payment_method" name="payment_method" required>
                            <option value="">Select Payment Method</option>
                            <option value="cash" selected>Cash</option>
                            <option value="card">Card</option>
                            <option value="gcash">GCash</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Products Selection -->
            <div class="mb-4">
                <h6 class="text-muted small text-uppercase fw-bold mb-3">Select Products</h6>
                
                <!-- Barcode Scanner Input -->
                <div class="mb-3">
                    <label for="barcodeInput" class="form-label">
                        <i class="fas fa-barcode me-2"></i>Barcode Scanner
                    </label>
                    <input type="text" class="form-control form-control-lg" id="barcodeInput" 
                           placeholder="Scan barcode here or use dropdown below..." 
                           autocomplete="off" autofocus>
                    <small class="text-muted d-block mt-1">
                        <i class="fas fa-info-circle me-1"></i>Scan a product barcode and press Enter to add to cart
                    </small>
                </div>

                <!-- Manual Selection Dropdown -->
                <div class="d-flex gap-2">
                    <select class="form-select" id="productSelect" style="flex: 1;">
                        <option value="">Select a product manually...</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" 
                                    data-price="{{ $product->price }}"
                                    data-quantity="{{ $product->quantity }}"
                                    data-unit="{{ $product->unit }}"
                                    data-barcode="{{ $product->barcode }}"
                                    data-name="{{ $product->name }}">
                                {{ $product->name }} ({{ $product->quantity }} {{ $product->unit }} available) - ₱{{ number_format($product->price, 2) }}
                            </option>
                        @endforeach
                    </select>
                    <button type="button" class="btn btn-primary" onclick="addProduct()">
                        <i class="fas fa-plus"></i> Add
                    </button>
                </div>
            </div>

            <!-- Sale Items -->
            <div class="mb-4">
                <h6 class="text-muted small text-uppercase fw-bold mb-3">Sale Items</h6>
                <!-- Items Table -->
                <div class="table-responsive mb-3">
                    <table class="table table-bordered" id="itemsTable">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="itemsTableBody">
                            <!-- Items will be added here dynamically -->
                        </tbody>
                    </table>
                    @if(!empty($itemsTableBody))
                        <p class="text-muted text-center py-3">No items added yet. Select a product above to add items.</p>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <!-- Summary -->
    <div class="sales-summary-column">
        <div class="card sticky-top" style="top: 0;">
            <div class="card-header">
                <h5 class="mb-0">Sale Summary</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <span id="subtotal">₱0.00</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Items:</span>
                        <span id="totalItems">0</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <strong>Total:</strong>
                        <strong id="totalAmount" class="text-primary">₱0.00</strong>
                    </div>
                </div>

                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <small>
                        Make sure you have enough stock before completing the sale. 
                        Inventory will be automatically updated.
                    </small>
                </div>

                <hr>

                <!-- Notes -->
                <div class="mb-3">
                    <label for="notes" class="form-label">Notes (Optional)</label>
                    <textarea class="form-control" id="notes" name="notes" rows="2" 
                              placeholder="Add any notes about this sale..."></textarea>
                </div>

                <!-- Stock Warning Container -->
                <div id="stockWarning" class="mb-3"></div>

                <!-- Submit Buttons -->
                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-success btn-lg" id="submitBtn" onclick="completeSale(event)">
                        <i class="fas fa-check me-1"></i> Complete Sale
                    </button>
                    <a href="{{ route('sales.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-1"></i> Cancel
                    </a>
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
                <h5>Processing Sale...</h5>
                <p class="text-muted">Please wait while we process your sale.</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let saleItems = [];
let itemIdCounter = 0;

// Product data for barcode lookup - create as array first then convert to object
const productsArray = {!! json_encode($products->map(function($p) { 
    return [
        'id' => $p->id, 
        'name' => $p->name, 
        'price' => $p->price, 
        'quantity' => $p->quantity, 
        'unit' => $p->unit,
        'barcode' => $p->barcode
    ]; 
})) !!};

// Create a proper barcode index
const productsData = {};
productsArray.forEach(product => {
    if (product.barcode && product.barcode.trim()) {
        productsData[product.barcode.trim().toUpperCase()] = product;
    }
});

console.log('Products Data:', productsData);
console.log('Available barcodes:', Object.keys(productsData));

// Barcode scanner handler
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM Loaded - initializing barcode scanner');
    const barcodeInput = document.getElementById('barcodeInput');
    
    if (barcodeInput) {
        console.log('Barcode input field found');
        barcodeInput.addEventListener('keypress', function(e) {
            console.log('Key pressed:', e.key);
            if (e.key === 'Enter') {
                e.preventDefault();
                const barcode = this.value.trim().toUpperCase();
                console.log('Barcode scanned:', barcode);
                
                if (!barcode) {
                    alert('Please scan or enter a barcode.');
                    return;
                }
                
                addProductByBarcode(barcode);
                this.value = '';
                this.focus();
            }
        });
        // Set focus to barcode input
        barcodeInput.focus();
    } else {
        console.error('Barcode input field NOT found');
    }
});

// Add product by barcode
function addProductByBarcode(barcode) {
    console.log('Scanning barcode:', barcode);
    console.log('Product data keys:', Object.keys(productsData));
    
    // Search for product by barcode
    const product = productsData[barcode];
    
    if (!product) {
        console.error('Product not found for barcode:', barcode);
        alert('Product with barcode "' + barcode + '" not found. Available barcodes: ' + Object.keys(productsData).join(', '));
        return;
    }
    
    console.log('Product found:', product);
    
    // Check if product is already in cart
    const existingItem = saleItems.find(item => item.product_id === product.id);
    
    if (existingItem) {
        // If product already exists, increment quantity
        if (existingItem.quantity < product.quantity) {
            existingItem.quantity += 1;
            console.log('Incremented quantity for:', product.name);
        } else {
            alert('Cannot add more of "' + product.name + '". Insufficient stock available.');
            return;
        }
    } else {
        // Add new product
        const itemId = ++itemIdCounter;
        
        saleItems.push({
            id: itemId,
            product_id: product.id,
            name: product.name,
            price: product.price,
            quantity: 1,
            unit: product.unit,
            available_quantity: product.quantity
        });
        
        console.log('Product added:', product.name);
    }
    
    renderItemsTable();
    updateSummary();
    
    // Show visual feedback
    showBarcodeSuccess(product.name);
}


// Show success feedback when product is added
function showBarcodeSuccess(productName) {
    const itemsTable = document.getElementById('itemsTable');
    const barcodeInput = document.getElementById('barcodeInput');
    
    // Visual feedback on table
    itemsTable.style.backgroundColor = '#d4edda';
    itemsTable.style.transition = 'background-color 0.3s ease';
    
    setTimeout(() => {
        itemsTable.style.backgroundColor = '';
    }, 500);
    
    // Show toast notification
    showToast(productName + ' added to cart!', 'success');
    
    // Return focus to barcode scanner for next scan
    if (barcodeInput) {
        barcodeInput.focus();
        barcodeInput.select();
    }
}

// Show toast notification
function showToast(message, type = 'info') {
    const toastHTML = `
        <div class="toast align-items-center text-white bg-${type} border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    `;
    
    const toastContainer = document.getElementById('toastContainer');
    if (!toastContainer) {
        const container = document.createElement('div');
        container.id = 'toastContainer';
        container.style.position = 'fixed';
        container.style.top = '20px';
        container.style.right = '20px';
        container.style.zIndex = '9999';
        document.body.appendChild(container);
    }
    
    const container = document.getElementById('toastContainer');
    const tempDiv = document.createElement('div');
    tempDiv.innerHTML = toastHTML;
    container.appendChild(tempDiv.firstElementChild);
    
    const toast = new bootstrap.Toast(container.lastElementChild);
    toast.show();
}

function addProduct() {
    const select = document.getElementById('productSelect');
    const selectedOption = select.options[select.selectedIndex];
    
    if (!select.value) {
        alert('Please select a product first.');
        return;
    }

    const productId = select.value;
    const productName = selectedOption.text.split(' (')[0];
    const price = parseFloat(selectedOption.dataset.price);
    const availableQuantity = parseInt(selectedOption.dataset.quantity);
    const unit = selectedOption.dataset.unit;

    // Check if product already added
    if (saleItems.find(item => item.product_id === productId)) {
        alert('This product is already added. You can edit the quantity in the table.');
        return;
    }

    const itemId = ++itemIdCounter;
    
    saleItems.push({
        id: itemId,
        product_id: productId,
        name: productName,
        price: price,
        quantity: 1,
        unit: unit,
        available_quantity: availableQuantity
    });

    renderItemsTable();
    updateSummary();
    select.value = '';
}

function removeItem(itemId) {
    saleItems = saleItems.filter(item => item.id !== itemId);
    renderItemsTable();
    updateSummary();
}

function updateQuantity(itemId, newQuantity) {
    const item = saleItems.find(item => item.id === itemId);
    if (item) {
        const quantity = parseInt(newQuantity) || 0;
        if (quantity > 0) {
            item.quantity = quantity;
            updateSummary();
        } else if (quantity === 0) {
            // If quantity is 0, remove the item
            removeItem(itemId);
        }
    }
}

function renderItemsTable() {
    const tbody = document.getElementById('itemsTableBody');
    
    if (saleItems.length === 0) {
        tbody.innerHTML = '<tr><td colspan="5" class="text-center text-muted py-3">No items added yet.</td></tr>';
        return;
    }

    tbody.innerHTML = saleItems.map(item => `
        <tr>
            <td>${item.name}</td>
            <td>₱${item.price.toFixed(2)}</td>
            <td>
                <input type="number" class="form-control form-control-sm" 
                       id="quantity_${item.id}" 
                       value="${item.quantity}" 
                       min="1" 
                       max="${item.available_quantity}"
                       onchange="updateQuantity(${item.id}, this.value)"
                       style="width: 80px;">
            </td>
            <td>₱${(item.price * item.quantity).toFixed(2)}</td>
            <td>
                <button type="button" class="btn btn-sm btn-danger" onclick="removeItem(${item.id})">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>
    `).join('');
}

function updateSummary() {
    const subtotal = saleItems.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    const totalItems = saleItems.reduce((sum, item) => sum + item.quantity, 0);

    document.getElementById('subtotal').textContent = `₱${subtotal.toFixed(2)}`;
    document.getElementById('totalItems').textContent = totalItems;
    document.getElementById('totalAmount').textContent = `₱${subtotal.toFixed(2)}`;
    
    // Check stock availability and update submit button
    validateStockAvailability();
}

function validateStockAvailability() {
    const submitBtn = document.getElementById('submitBtn');
    const outOfStockItems = saleItems.filter(item => item.quantity > item.available_quantity);
    
    if (outOfStockItems.length > 0) {
        // Disable submit button
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-exclamation-triangle me-1"></i> Insufficient Stock';
        submitBtn.className = 'btn btn-warning btn-lg';
        
        // Show warning message
        const warningDiv = document.getElementById('stockWarning');
        const itemNames = outOfStockItems.map(item => 
            `${item.name} (Requested: ${item.quantity}, Available: ${item.available_quantity})`
        ).join('<br>');
        warningDiv.innerHTML = `
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <strong>Insufficient Stock!</strong><br>
                The following items have insufficient quantity:<br>
                <small>${itemNames}</small>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
    } else {
        // Enable submit button
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fas fa-check me-1"></i> Complete Sale';
        submitBtn.className = 'btn btn-success btn-lg';
        
        // Remove warning message
        const warningDiv = document.getElementById('stockWarning');
        if (warningDiv) {
            warningDiv.innerHTML = '';
        }
    }
}

// Complete sale function
function completeSale(event) {
    event.preventDefault();
    console.log('Complete Sale clicked', saleItems);

    if (saleItems.length === 0) {
        alert('Please add at least one product to the sale.');
        return;
    }

    const paymentMethod = document.getElementById('payment_method').value;
    if (!paymentMethod) {
        alert('Please select a payment method.');
        return;
    }

    // Check stock availability
    const outOfStockItems = saleItems.filter(item => item.quantity > item.available_quantity);
    if (outOfStockItems.length > 0) {
        alert('Some items have insufficient stock. Please update the quantities.');
        return;
    }

    // Show loading modal
    const modal = new bootstrap.Modal(document.getElementById('loadingModal'));
    modal.show();

    // Prepare form data
    const formData = {
        items: saleItems.map(item => ({
            product_id: item.product_id,
            quantity: item.quantity
        })),
        payment_method: paymentMethod,
        customer_name: document.getElementById('customer_name').value,
        notes: document.getElementById('notes').value
    };

    console.log('Sending sale data:', formData);

    // Get CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    
    if (!csrfToken) {
        modal.hide();
        alert('Error: CSRF token not found. Please refresh the page and try again.');
        console.error('CSRF token not found');
        return;
    }

    // Submit sale
    fetch('{{ route("sales.store") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify(formData)
    })
    .then(response => {
        console.log('Response received:', response.status, response.statusText);
        if (!response.ok) {
            return response.json().then(data => {
                console.error('API Error:', data);
                throw new Error(data.message || `HTTP error! status: ${response.status}`);
            });
        }
        return response.json();
    })
    .then(data => {
        console.log('Success response:', data);
        modal.hide();
        
        if (data.success) {
            alert('Sale completed successfully! Transaction: ' + data.transaction_number);
            window.location.href = '{{ route("sales.index") }}';
        } else {
            alert('Error: ' + (data.message || 'Unknown error occurred'));
        }
    })
    .catch(error => {
        modal.hide();
        console.error('Fetch error:', error);
        alert('Error processing sale: ' + error.message);
    });
}

// Form submission (keeping as fallback)
document.getElementById('saleForm').addEventListener('submit', function(e) {
    e.preventDefault();
    console.log('Form submitted via enter key');
    completeSale(e);
});

// Initialize
renderItemsTable();
updateSummary();
</script>
@endpush
