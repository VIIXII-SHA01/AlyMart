<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alymart Inventory Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/inventory-dashboard.css">
    </head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h4><i class="fas fa-store"></i> ALYMART</h4>
            <small>Inventory Management</small>
        </div>
        <div class="sidebar-menu">
            <a href="#" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
            <a href="#" id="viewInventoryLink"><i class="fas fa-boxes"></i> View Inventory</a>
            <a href="#" id="stockInLink"><i class="fas fa-box-open"></i> Stock In</a>
            <a href="#" id="stockOutLink"><i class="fas fa-dolly"></i> Stock Out</a>
            <a href="#" id="stockHistoryLink"><i class="fas fa-history"></i> Stock History</a>
            <a href="#" id="alertsLink"><i class="fas fa-exclamation-triangle"></i> Alerts</a>
            <a href="#" data-bs-toggle="modal" data-bs-target="#settingsModal"><i class="fas fa-cog"></i> Settings</a>
            <a href="#"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Navbar -->
        <div class="top-navbar">
            <div>
                <h5 class="mb-0" id="pageTitle">Inventory Dashboard</h5>
                <small class="text-muted">Welcome, Inventory Staff Juan</small>
            </div>
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addProductModal">
                    <i class="fas fa-plus"></i> Add New Product
                </button>
                <div class="dropdown">
                    <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle"></i> Juan
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

        <!-- Dashboard View -->
        <div id="dashboardView">
            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-md-3 mb-3">
                    <div class="stat-card">
                        <div class="icon" style="background: #dbeafe; color: var(--primary-color);">
                            <i class="fas fa-boxes"></i>
                        </div>
                        <h3 id="totalProducts">1,245</h3>
                        <p>Total Products</p>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="stat-card">
                        <div class="icon" style="background: #fed7aa; color: var(--warning-color);">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <h3 id="lowStockItems">15</h3>
                        <p>Low Stock Items</p>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="stat-card">
                        <div class="icon" style="background: #fecaca; color: var(--danger-color);">
                            <i class="fas fa-times-circle"></i>
                        </div>
                        <h3 id="outOfStockItems">8</h3>
                        <p>Out of Stock</p>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="stat-card">
                        <div class="icon" style="background: #dcfce7; color: var(--success-color);">
                            <i class="fas fa-arrow-up"></i>
                        </div>
                        <h3 id="stockInToday">24</h3>
                        <p>Stock In Today</p>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="row mb-4">
                <div class="col-12">
                    <h5 class="mb-3">Quick Actions</h5>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="action-card" data-action="stockIn">
                        <i class="fas fa-box-open"></i>
                        <h5>Stock In</h5>
                        <p>Add new stock to inventory</p>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="action-card" data-action="stockOut">
                        <i class="fas fa-dolly"></i>
                        <h5>Stock Out</h5>
                        <p>Record product withdrawal</p>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="action-card" data-action="viewInventory">
                        <i class="fas fa-clipboard-list"></i>
                        <h5>View Inventory</h5>
                        <p>Check all product stock levels</p>
                    </div>
                </div>
            </div>

            <!-- Critical Alerts -->
            <div class="alert-box">
                <h5 class="mb-3"><i class="fas fa-bell"></i> Critical Alerts</h5>
                <div id="criticalAlerts">
                    <div class="alert-item critical">
                        <strong><i class="fas fa-times-circle"></i> Out of Stock</strong>
                        <p class="mb-0">Coca-Cola 1.5L - Immediate restocking required</p>
                    </div>
                    <div class="alert-item critical">
                        <strong><i class="fas fa-times-circle"></i> Out of Stock</strong>
                        <p class="mb-0">Sprite 1.5L - Immediate restocking required</p>
                    </div>
                    <div class="alert-item">
                        <strong><i class="fas fa-exclamation-triangle"></i> Low Stock</strong>
                        <p class="mb-0">Lucky Me Pancit Canton - Only 5 units remaining</p>
                    </div>
                    <div class="alert-item">
                        <strong><i class="fas fa-exclamation-triangle"></i> Low Stock</strong>
                        <p class="mb-0">Milo 1kg Pack - Only 3 units remaining</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Inventory View -->
        <div id="inventoryView" style="display: none;">
            <div class="inventory-table">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h5 class="mb-0">Product Inventory</h5>
                        <small class="text-muted">Real-time stock levels</small>
                    </div>
                    <div class="d-flex gap-2">
                        <input type="text" class="form-control" id="searchInventory" placeholder="Search products...">
                        <select class="form-select" id="filterCategory">
                            <option value="all">All Categories</option>
                            <option value="beverages">Beverages</option>
                            <option value="snacks">Snacks</option>
                            <option value="noodles">Noodles</option>
                            <option value="dairy">Dairy</option>
                            <option value="canned">Canned Goods</option>
                        </select>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Product Name</th>
                                <th>Category</th>
                                <th>Current Stock</th>
                                <th>Unit</th>
                                <th>Price</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="inventoryTableBody">
                            <!-- Products will be loaded here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Stock History View -->
        <div id="stockHistoryView" style="display: none;">
            <div class="inventory-table">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Stock Movement History</h5>
                    <div class="d-flex gap-2">
                        <select class="form-select" id="filterHistoryType">
                            <option value="all">All Transactions</option>
                            <option value="in">Stock In</option>
                            <option value="out">Stock Out</option>
                        </select>
                        <input type="date" class="form-control" id="filterDate">
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Date & Time</th>
                                <th>Product</th>
                                <th>Type</th>
                                <th>Quantity</th>
                                <th>Staff</th>
                                <th>Notes</th>
                            </tr>
                        </thead>
                        <tbody id="historyTableBody">
                            <!-- History will be loaded here -->
                        </tbody>
                    </table>
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
                        <h6 class="mb-3"><i class="fas fa-bell"></i> Alert Settings</h6>
                        <div class="mb-3">
                            <label class="form-label">Low Stock Threshold</label>
                            <input type="number" class="form-control" id="lowStockThreshold" value="10" min="1">
                            <small class="text-muted">Alert when stock falls below this number</small>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="emailAlerts" checked>
                            <label class="form-check-label" for="emailAlerts">Email notifications for critical alerts</label>
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

    <!-- Add Product Modal -->
    <div class="modal fade" id="addProductModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-plus"></i> Add New Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Product Name</label>
                        <input type="text" class="form-control" id="newProductName" placeholder="Enter product name">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Category</label>
                        <select class="form-select" id="newProductCategory">
                            <option value="">Select category</option>
                            <option value="beverages">Beverages</option>
                            <option value="snacks">Snacks</option>
                            <option value="noodles">Noodles</option>
                            <option value="dairy">Dairy</option>
                            <option value="canned">Canned Goods</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Unit</label>
                        <input type="text" class="form-control" id="newProductUnit" placeholder="e.g., pcs, kg, bottle">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Price</label>
                        <input type="number" class="form-control" id="newProductPrice" placeholder="0.00" step="0.01">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Initial Stock</label>
                        <input type="number" class="form-control" id="newProductStock" placeholder="0" min="0">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveNewProduct">
                        <i class="fas fa-save"></i> Add Product
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Stock In Modal -->
    <div class="modal fade" id="stockInModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-box-open"></i> Stock In</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Select Product</label>
                        <select class="form-select" id="stockInProduct">
                            <option value="">Choose a product</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Quantity to Add</label>
                        <input type="number" class="form-control" id="stockInQuantity" placeholder="0" min="1">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Supplier (Optional)</label>
                        <input type="text" class="form-control" id="stockInSupplier" placeholder="Enter supplier name">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes (Optional)</label>
                        <textarea class="form-control" id="stockInNotes" rows="3" placeholder="Add any notes about this stock in"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success" id="confirmStockIn">
                        <i class="fas fa-check"></i> Confirm Stock In
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Stock Out Modal -->
    <div class="modal fade" id="stockOutModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-dolly"></i> Stock Out</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Select Product</label>
                        <select class="form-select" id="stockOutProduct">
                            <option value="">Choose a product</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Current Stock: <strong id="currentStock">0</strong></label>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Quantity to Remove</label>
                        <input type="number" class="form-control" id="stockOutQuantity" placeholder="0" min="1">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Reason</label>
                        <select class="form-select" id="stockOutReason">
                            <option value="">Select reason</option>
                            <option value="damaged">Damaged/Expired</option>
                            <option value="returned">Returned to Supplier</option>
                            <option value="transfer">Transfer to Another Branch</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea class="form-control" id="stockOutNotes" rows="3" placeholder="Provide details about this stock out"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-warning" id="confirmStockOut">
                        <i class="fas fa-check"></i> Confirm Stock Out
                    </button>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/inventory-dashboard.js"></script>
</body>
</html>