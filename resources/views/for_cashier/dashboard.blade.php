<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alymart Cashier Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/cashier-dashboard.css">
    </head>
<body>
    <!-- Sidebar -->
      @include('headers.cashier-navigation')
    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Navbar -->
        <div class="top-navbar">
            <div>
                <h5 class="mb-0">Point of Sale</h5>
                <small class="text-muted">Welcome, Cashier Maria</small>
            </div>
            <div class="d-flex align-items-center gap-3">
                <div class="d-flex gap-3">
                    <div class="text-center">
                        <small class="text-muted d-block">Today's Sales</small>
                        <strong id="todaySales">₱8,450</strong>
                    </div>
                    <div class="text-center">
                        <small class="text-muted d-block">Transactions</small>
                        <strong id="todayTransactions">32</strong>
                    </div>
                </div>
                <div class="dropdown">
                    <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle"></i> Maria
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#"><i class="fas fa-user"></i> Profile</a></li>
                        <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#settingsModal"><i class="fas fa-cog"></i> Settings</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- POS Container -->
        <div class="pos-container">
            <!-- Products Section -->
            <div class="products-section">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" id="productSearch" placeholder="Search products by name or barcode...">
                </div>

                <div class="category-filter">
                    <button class="category-btn active" data-category="all">All</button>
                    <button class="category-btn" data-category="beverages">Beverages</button>
                    <button class="category-btn" data-category="snacks">Snacks</button>
                    <button class="category-btn" data-category="noodles">Noodles</button>
                    <button class="category-btn" data-category="dairy">Dairy</button>
                    <button class="category-btn" data-category="canned">Canned Goods</button>
                </div>

                <div class="products-grid" id="productsGrid">
                    <!-- Products will be loaded here -->
                </div>
            </div>

            <!-- Cart Section -->
            <div class="cart-section">
                <div class="cart-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-shopping-cart"></i> Current Sale</h5>
                        <button class="btn btn-sm btn-outline-danger" id="clearCart">
                            <i class="fas fa-trash"></i> Clear
                        </button>
                    </div>
                </div>

                <div class="cart-items" id="cartItems">
                    <div class="text-center text-muted py-5">
                        <i class="fas fa-shopping-cart fa-3x mb-3"></i>
                        <p>Cart is empty<br>Start adding products</p>
                    </div>
                </div>

                <div class="cart-summary">
                    <div class="summary-row">
                        <span>Subtotal:</span>
                        <strong id="subtotal">₱0.00</strong>
                    </div>
                    <div class="summary-row">
                        <span>Discount:</span>
                        <strong id="discount">₱0.00</strong>
                    </div>
                    <div class="summary-row total">
                        <span>Total:</span>
                        <span id="total">₱0.00</span>
                    </div>
                </div>

                <div class="checkout-actions">
                    <button class="btn btn-primary btn-checkout" id="checkoutBtn" disabled>
                        <i class="fas fa-credit-card"></i> Checkout
                    </button>
                    <button class="btn btn-outline-primary" id="holdBtn" disabled>
                        <i class="fas fa-pause"></i> Hold Transaction
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Settings Modal -->
    <div class="modal fade" id="settingsModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-cog"></i> Settings</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-4">
                        <h6 class="mb-3"><i class="fas fa-palette"></i> Appearance</h6>
                        <div class="d-flex justify-content-between align-items-center p-3" style="background: rgba(0,0,0,0.05); border-radius: 8px;">
                            <div>
                                <strong>Dark Mode</strong>
                                <p class="mb-0 small text-muted">Switch between light and dark theme</p>
                            </div>
                            <div class="theme-switch-wrapper">
                                <label class="theme-switch">
                                    <input type="checkbox" id="themeToggle">
                                    <span class="slider"></span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <h6 class="mb-3"><i class="fas fa-receipt"></i> Receipt Preferences</h6>
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" id="autoPrint" checked>
                            <label class="form-check-label" for="autoPrint">Auto-print receipt</label>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="soundEffects" checked>
                            <label class="form-check-label" for="soundEffects">Sound effects</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveSettings">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Checkout Modal -->
    <div class="modal fade" id="checkoutModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-credit-card"></i> Payment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <h3 class="text-center mb-3">Total Amount</h3>
                        <h2 class="text-center text-primary" id="checkoutTotal">₱0.00</h2>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Payment Method</label>
                        <select class="form-select" id="paymentMethod">
                            <option value="cash">Cash</option>
                            <option value="card">Card</option>
                            <option value="gcash">GCash</option>
                        </select>
                    </div>
                    <div class="mb-3" id="cashPaymentFields">
                        <label class="form-label">Amount Received</label>
                        <input type="number" class="form-control form-control-lg" id="amountReceived" placeholder="0.00" step="0.01">
                        <div class="mt-3">
                            <strong>Change:</strong>
                            <h4 class="text-success" id="change">₱0.00</h4>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Customer Name (Optional)</label>
                        <input type="text" class="form-control" id="customerName" placeholder="Enter customer name">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success btn-lg" id="completePayment" disabled>
                        <i class="fas fa-check"></i> Complete Payment
                    </button>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/cashier-dashboard.js"></script>
    </body>
</html>