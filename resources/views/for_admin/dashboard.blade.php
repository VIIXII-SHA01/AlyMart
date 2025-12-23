<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alymart Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/admin-dashboard.css">
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h4><i class="fas fa-store"></i> ALYMART</h4>
            <small>Sales & Inventory System</small>
        </div>
        <div class="sidebar-menu">
            <a href="#" class="active"><i class="fas fa-chart-line"></i> Dashboard</a>
            <a href="#"><i class="fas fa-shopping-cart"></i> Sales</a>
            <a href="#"><i class="fas fa-boxes"></i> Inventory</a>
            <a href="#"><i class="fas fa-clipboard-list"></i> Products</a>
            <a href="#"><i class="fas fa-file-alt"></i> Reports</a>
            <a href="#"><i class="fas fa-bell"></i> Notifications</a>
            <a href="#"><i class="fas fa-users"></i> User Management</a>
            <a href="#" data-bs-toggle="modal" data-bs-target="#settingsModal"><i class="fas fa-cog"></i> Settings</a>
            <a href="#"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Navbar -->
        <div class="top-navbar">
            <div>
                <h5 class="mb-0">Dashboard Overview</h5>
                <small class="text-muted">Welcome back, Admin</small>
            </div>
            <div class="d-flex align-items-center gap-3">
                <div class="notification-badge">
                    <i class="fas fa-bell fa-lg text-secondary"></i>
                    <span class="badge bg-danger">5</span>
                </div>
                <div class="dropdown">
                    <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle"></i> Admin
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#"><i class="fas fa-user"></i> Profile</a></li>
                        <li><a class="dropdown-item" href="#"><i class="fas fa-cog"></i> Settings</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="stat-card">
                    <div class="icon" style="background: #dbeafe; color: var(--primary-color);">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <h3 id="totalSales">₱45,320</h3>
                    <p>Today's Sales</p>
                    <small class="text-success"><i class="fas fa-arrow-up"></i> 12% vs yesterday</small>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-card">
                    <div class="icon" style="background: #dcfce7; color: var(--success-color);">
                        <i class="fas fa-receipt"></i>
                    </div>
                    <h3 id="totalTransactions">248</h3>
                    <p>Total Transactions</p>
                    <small class="text-success"><i class="fas fa-arrow-up"></i> 8% increase</small>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-card">
                    <div class="icon" style="background: #fed7aa; color: var(--warning-color);">
                        <i class="fas fa-box"></i>
                    </div>
                    <h3 id="totalProducts">1,245</h3>
                    <p>Products in Stock</p>
                    <small class="text-warning"><i class="fas fa-exclamation-triangle"></i> 15 low stock</small>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-card">
                    <div class="icon" style="background: #fecaca; color: var(--danger-color);">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                    <h3 id="criticalItems">8</h3>
                    <p>Critical Stock Items</p>
                    <small class="text-danger"><i class="fas fa-arrow-down"></i> Needs attention</small>
                </div>
            </div>
        </div>

        <!-- Charts and Alerts Row -->
        <div class="row mb-4">
            <div class="col-lg-8 mb-3">
                <div class="chart-container">
                    <h5 class="mb-3">Sales Overview</h5>
                    <canvas id="salesChart" height="80"></canvas>
                </div>
            </div>
            <div class="col-lg-4 mb-3">
                <div class="chart-container">
                    <h5 class="mb-3">Inventory Alerts <span class="badge bg-danger">5</span></h5>
                    <div class="alert-item critical">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <strong>Out of Stock</strong>
                                <p class="mb-0 small">Coca-Cola 1.5L</p>
                            </div>
                            <span class="badge bg-danger">Critical</span>
                        </div>
                    </div>
                    <div class="alert-item">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <strong>Low Stock</strong>
                                <p class="mb-0 small">Lucky Me Pancit Canton (5 left)</p>
                            </div>
                            <span class="badge bg-warning">Warning</span>
                        </div>
                    </div>
                    <div class="alert-item">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <strong>Low Stock</strong>
                                <p class="mb-0 small">Milo 1kg Pack (3 left)</p>
                            </div>
                            <span class="badge bg-warning">Warning</span>
                        </div>
                    </div>
                    <button class="btn btn-outline-primary btn-sm w-100 mt-2">View All Alerts</button>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="chart-container">
                    <h5 class="mb-3">Quick Actions</h5>
                    <div class="row">
                        <div class="col-md-3 mb-2">
                            <button class="quick-action-btn">
                                <i class="fas fa-plus-circle fa-2x mb-2"></i><br>
                                New Sale
                            </button>
                        </div>
                        <div class="col-md-3 mb-2">
                            <button class="quick-action-btn">
                                <i class="fas fa-box-open fa-2x mb-2"></i><br>
                                Stock In
                            </button>
                        </div>
                        <div class="col-md-3 mb-2">
                            <button class="quick-action-btn">
                                <i class="fas fa-file-download fa-2x mb-2"></i><br>
                                Generate Report
                            </button>
                        </div>
                        <div class="col-md-3 mb-2">
                            <button class="quick-action-btn">
                                <i class="fas fa-user-plus fa-2x mb-2"></i><br>
                                Add User
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Products Table -->
        <div class="product-table">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Top Selling Products</h5>
                <button class="btn btn-outline-primary btn-sm">View All</button>
            </div>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>Category</th>
                            <th>Stock</th>
                            <th>Price</th>
                            <th>Sales Today</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="productsTable">
                        <tr>
                            <td><strong>Coca-Cola 1.5L</strong></td>
                            <td>Beverages</td>
                            <td><span class="badge bg-danger">0</span></td>
                            <td>₱65.00</td>
                            <td>45 units</td>
                            <td><span class="badge badge-status bg-danger">Out of Stock</span></td>
                        </tr>
                        <tr>
                            <td><strong>Lucky Me Pancit Canton</strong></td>
                            <td>Instant Noodles</td>
                            <td><span class="badge bg-warning text-dark">5</span></td>
                            <td>₱12.50</td>
                            <td>38 units</td>
                            <td><span class="badge badge-status bg-warning text-dark">Low Stock</span></td>
                        </tr>
                        <tr>
                            <td><strong>Alaska Evap Milk</strong></td>
                            <td>Dairy</td>
                            <td><span class="badge bg-success">125</span></td>
                            <td>₱28.00</td>
                            <td>32 units</td>
                            <td><span class="badge badge-status bg-success">In Stock</span></td>
                        </tr>
                        <tr>
                            <td><strong>Royal Softdrinks 1.5L</strong></td>
                            <td>Beverages</td>
                            <td><span class="badge bg-success">78</span></td>
                            <td>₱55.00</td>
                            <td>28 units</td>
                            <td><span class="badge badge-status bg-success">In Stock</span></td>
                        </tr>
                        <tr>
                            <td><strong>Milo 1kg Pack</strong></td>
                            <td>Beverages</td>
                            <td><span class="badge bg-warning text-dark">3</span></td>
                            <td>₱385.00</td>
                            <td>25 units</td>
                            <td><span class="badge badge-status bg-warning text-dark">Low Stock</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Settings Modal -->
    <div class="modal fade" id="settingsModal" tabindex="-1" aria-labelledby="settingsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="settingsModalLabel">
                        <i class="fas fa-cog"></i> Settings
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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

                    <div class="mb-4">
                        <h6 class="mb-3"><i class="fas fa-bell"></i> Notifications</h6>
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" id="lowStockNotif" checked>
                            <label class="form-check-label" for="lowStockNotif">
                                Low Stock Alerts
                            </label>
                        </div>
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" id="salesNotif" checked>
                            <label class="form-check-label" for="salesNotif">
                                Sales Notifications
                            </label>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="reportNotif" checked>
                            <label class="form-check-label" for="reportNotif">
                                Report Reminders
                            </label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <h6 class="mb-3"><i class="fas fa-chart-bar"></i> Display Preferences</h6>
                        <div class="mb-3">
                            <label for="chartType" class="form-label">Default Chart Type</label>
                            <select class="form-select" id="chartType">
                                <option value="line" selected>Line Chart</option>
                                <option value="bar">Bar Chart</option>
                                <option value="area">Area Chart</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="dashboardLayout" class="form-label">Dashboard Layout</label>
                            <select class="form-select" id="dashboardLayout">
                                <option value="default" selected>Default</option>
                                <option value="compact">Compact</option>
                                <option value="expanded">Expanded</option>
                            </select>
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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="js/admin-dashboard.js"></script>
</body>
</html>