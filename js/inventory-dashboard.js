 $(document).ready(function() {
            // Sample product data
            let products = [
                { id: 1, name: 'Coca-Cola 1.5L', category: 'beverages', unit: 'bottle', price: 65, stock: 0 },
                { id: 2, name: 'Sprite 1.5L', category: 'beverages', unit: 'bottle', price: 60, stock: 0 },
                { id: 3, name: 'Royal 1.5L', category: 'beverages', unit: 'bottle', price: 55, stock: 32 },
                { id: 4, name: 'Milo 1kg', category: 'beverages', unit: 'pack', price: 385, stock: 3 },
                { id: 5, name: 'Lucky Me Pancit Canton', category: 'noodles', unit: 'pack', price: 12.50, stock: 5 },
                { id: 6, name: 'Payless Pancit Canton', category: 'noodles', unit: 'pack', price: 11, stock: 120 },
                { id: 7, name: 'Nissin Cup Noodles', category: 'noodles', unit: 'cup', price: 25, stock: 85 },
                { id: 8, name: 'Piattos Cheese', category: 'snacks', unit: 'pack', price: 28, stock: 65 },
                { id: 9, name: 'Nova', category: 'snacks', unit: 'pack', price: 8, stock: 200 },
                { id: 10, name: 'Chippy', category: 'snacks', unit: 'pack', price: 8, stock: 180 },
                { id: 11, name: 'Alaska Evap Milk', category: 'dairy', unit: 'can', price: 28, stock: 95 },
                { id: 12, name: 'Bear Brand', category: 'dairy', unit: 'can', price: 35, stock: 78 },
                { id: 13, name: 'Argentina Corned Beef', category: 'canned', unit: 'can', price: 45, stock: 55 },
                { id: 14, name: 'Century Tuna', category: 'canned', unit: 'can', price: 38, stock: 68 },
                { id: 15, name: 'Ligo Sardines', category: 'canned', unit: 'can', price: 22, stock: 90 }
            ];

            let stockHistory = [
                { id: 1, date: '2024-12-23 10:30', product: 'Coca-Cola 1.5L', type: 'out', quantity: 45, staff: 'Juan', notes: 'All stock sold' },
                { id: 2, date: '2024-12-23 09:15', product: 'Lucky Me Pancit Canton', type: 'in', quantity: 100, staff: 'Juan', notes: 'New delivery from supplier' },
                { id: 3, date: '2024-12-22 16:45', product: 'Milo 1kg', type: 'out', quantity: 5, staff: 'Juan', notes: 'Damaged items' },
                { id: 4, date: '2024-12-22 14:20', product: 'Alaska Evap Milk', type: 'in', quantity: 50, staff: 'Juan', notes: 'Weekly restock' }
            ];

            // Theme management
            const currentTheme = localStorage.getItem('theme') || 'light';
            if (currentTheme === 'dark') {
                $('body').addClass('dark-mode');
                $('#themeToggle').prop('checked', true);
            }

            $('#themeToggle').change(function() {
                if ($(this).is(':checked')) {
                    $('body').addClass('dark-mode');
                    localStorage.setItem('theme', 'dark');
                    showNotification('Dark mode enabled', 'success');
                } else {
                    $('body').removeClass('dark-mode');
                    localStorage.setItem('theme', 'light');
                    showNotification('Light mode enabled', 'success');
                }
            });

            // Save settings
            $('#saveSettings').click(function() {
                const lowStockThreshold = $('#lowStockThreshold').val();
                const emailAlerts = $('#emailAlerts').is(':checked');
                
                localStorage.setItem('lowStockThreshold', lowStockThreshold);
                localStorage.setItem('emailAlerts', emailAlerts);
                
                $('#settingsModal').modal('hide');
                showNotification('Settings saved successfully!', 'success');
                updateDashboardStats();
            });

            // Load saved settings
            if (localStorage.getItem('lowStockThreshold')) {
                $('#lowStockThreshold').val(localStorage.getItem('lowStockThreshold'));
            }
            if (localStorage.getItem('emailAlerts') !== null) {
                $('#emailAlerts').prop('checked', localStorage.getItem('emailAlerts') === 'true');
            }

            // Navigation
            $('.sidebar-menu a').click(function(e) {
                if (!$(this).data('bs-toggle')) {
                    e.preventDefault();
                    $('.sidebar-menu a').removeClass('active');
                    $(this).addClass('active');
                }
            });

            $('#viewInventoryLink').click(function() {
                showView('inventory');
                $('#pageTitle').text('Product Inventory');
                loadInventoryTable();
            });

            $('#stockInLink, .action-card[data-action="stockIn"]').click(function(e) {
                e.preventDefault();
                loadProductSelect('stockIn');
                $('#stockInModal').modal('show');
            });

            $('#stockOutLink, .action-card[data-action="stockOut"]').click(function(e) {
                e.preventDefault();
                loadProductSelect('stockOut');
                $('#stockOutModal').modal('show');
            });

            $('#stockHistoryLink').click(function() {
                showView('stockHistory');
                $('#pageTitle').text('Stock Movement History');
                loadStockHistory();
            });

            $('#alertsLink').click(function() {
                showView('dashboard');
                $('#pageTitle').text('Inventory Dashboard');
                setTimeout(() => {
                    $('html, body').animate({
                        scrollTop: $('.alert-box').offset().top - 100
                    }, 500);
                }, 100);
            });

            $('.action-card[data-action="viewInventory"]').click(function() {
                $('#viewInventoryLink').click();
            });

            function showView(view) {
                $('#dashboardView, #inventoryView, #stockHistoryView').hide();
                if (view === 'dashboard') {
                    $('#dashboardView').show();
                } else if (view === 'inventory') {
                    $('#inventoryView').show();
                } else if (view === 'stockHistory') {
                    $('#stockHistoryView').show();
                }
            }

            // Load inventory table
            function loadInventoryTable(category = 'all', search = '') {
                let filtered = products;
                
                if (category !== 'all') {
                    filtered = filtered.filter(p => p.category === category);
                }
                
                if (search) {
                    filtered = filtered.filter(p => 
                        p.name.toLowerCase().includes(search.toLowerCase())
                    );
                }

                $('#inventoryTableBody').html('');
                filtered.forEach(product => {
                    const status = product.stock === 0 ? 'Out of Stock' : 
                                 product.stock <= 10 ? 'Low Stock' : 'In Stock';
                    const statusClass = product.stock === 0 ? 'bg-danger' : 
                                      product.stock <= 10 ? 'bg-warning text-dark' : 'bg-success';
                    
                    const row = `
                        <tr>
                            <td><strong>${product.name}</strong></td>
                            <td><span class="badge bg-secondary">${product.category}</span></td>
                            <td><strong>${product.stock}</strong></td>
                            <td>${product.unit}</td>
                            <td>₱${product.price.toFixed(2)}</td>
                            <td><span class="badge badge-status ${statusClass}">${status}</span></td>
                            <td>
                                <button class="btn btn-sm btn-primary update-stock-btn" data-id="${product.id}">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                    $('#inventoryTableBody').append(row);
                });
            }

            // Search and filter inventory
            $('#searchInventory').on('input', function() {
                const search = $(this).val();
                const category = $('#filterCategory').val();
                loadInventoryTable(category, search);
            });

            $('#filterCategory').change(function() {
                const category = $(this).val();
                const search = $('#searchInventory').val();
                loadInventoryTable(category, search);
            });

            // Update stock button
            $(document).on('click', '.update-stock-btn', function() {
                const productId = $(this).data('id');
                loadProductSelect('stockIn');
                $('#stockInProduct').val(productId);
                $('#stockInModal').modal('show');
            });

            // Load product select options
            function loadProductSelect(type) {
                const selectId = type === 'stockIn' ? '#stockInProduct' : '#stockOutProduct';
                $(selectId).html('<option value="">Choose a product</option>');
                products.forEach(product => {
                    $(selectId).append(`<option value="${product.id}">${product.name} (Current: ${product.stock})</option>`);
                });
            }

            // Stock out product change
            $('#stockOutProduct').change(function() {
                const productId = parseInt($(this).val());
                const product = products.find(p => p.id === productId);
                if (product) {
                    $('#currentStock').text(product.stock);
                } else {
                    $('#currentStock').text('0');
                }
            });

            // Add new product
            $('#saveNewProduct').click(function() {
                const name = $('#newProductName').val();
                const category = $('#newProductCategory').val();
                const unit = $('#newProductUnit').val();
                const price = parseFloat($('#newProductPrice').val());
                const stock = parseInt($('#newProductStock').val());

                if (!name || !category || !unit || !price || isNaN(stock)) {
                    showNotification('Please fill all fields correctly', 'warning');
                    return;
                }

                const newId = Math.max(...products.map(p => p.id)) + 1;
                products.push({
                    id: newId,
                    name: name,
                    category: category,
                    unit: unit,
                    price: price,
                    stock: stock
                });

                $('#addProductModal').modal('hide');
                $('#newProductName, #newProductUnit, #newProductPrice, #newProductStock').val('');
                $('#newProductCategory').val('');
                
                showNotification('Product added successfully!', 'success');
                updateDashboardStats();
                loadInventoryTable();
            });

            // Confirm stock in
            $('#confirmStockIn').click(function() {
                const productId = parseInt($('#stockInProduct').val());
                const quantity = parseInt($('#stockInQuantity').val());
                const supplier = $('#stockInSupplier').val();
                const notes = $('#stockInNotes').val();

                if (!productId || !quantity || quantity <= 0) {
                    showNotification('Please select a product and enter valid quantity', 'warning');
                    return;
                }

                const product = products.find(p => p.id === productId);
                if (product) {
                    product.stock += quantity;
                    
                    // Add to history
                    stockHistory.unshift({
                        id: stockHistory.length + 1,
                        date: new Date().toLocaleString(),
                        product: product.name,
                        type: 'in',
                        quantity: quantity,
                        staff: 'Juan',
                        notes: notes || `Supplier: ${supplier || 'N/A'}`
                    });

                    $('#stockInModal').modal('hide');
                    $('#stockInProduct').val('');
                    $('#stockInQuantity, #stockInSupplier, #stockInNotes').val('');
                    
                    showNotification(`Stock updated! ${product.name} +${quantity}`, 'success');
                    updateDashboardStats();
                    loadInventoryTable();
                }
            });

            // Confirm stock out
            $('#confirmStockOut').click(function() {
                const productId = parseInt($('#stockOutProduct').val());
                const quantity = parseInt($('#stockOutQuantity').val());
                const reason = $('#stockOutReason').val();
                const notes = $('#stockOutNotes').val();

                if (!productId || !quantity || quantity <= 0 || !reason) {
                    showNotification('Please fill all required fields', 'warning');
                    return;
                }

                const product = products.find(p => p.id === productId);
                if (product) {
                    if (quantity > product.stock) {
                        showNotification('Quantity exceeds current stock!', 'danger');
                        return;
                    }

                    product.stock -= quantity;
                    
                    // Add to history
                    stockHistory.unshift({
                        id: stockHistory.length + 1,
                        date: new Date().toLocaleString(),
                        product: product.name,
                        type: 'out',
                        quantity: quantity,
                        staff: 'Juan',
                        notes: `${reason}: ${notes}`
                    });

                    $('#stockOutModal').modal('hide');
                    $('#stockOutProduct').val('');
                    $('#stockOutQuantity, #stockOutNotes').val('');
                    $('#stockOutReason').val('');
                    
                    showNotification(`Stock updated! ${product.name} -${quantity}`, 'success');
                    updateDashboardStats();
                    loadInventoryTable();
                }
            });

            // Load stock history
            function loadStockHistory(type = 'all', date = '') {
                let filtered = stockHistory;
                
                if (type !== 'all') {
                    filtered = filtered.filter(h => h.type === type);
                }
                
                if (date) {
                    filtered = filtered.filter(h => h.date.startsWith(date));
                }

                $('#historyTableBody').html('');
                filtered.forEach(history => {
                    const typeClass = history.type === 'in' ? 'success' : 'warning';
                    const typeIcon = history.type === 'in' ? 'arrow-up' : 'arrow-down';
                    
                    const row = `
                        <tr>
                            <td>${history.date}</td>
                            <td><strong>${history.product}</strong></td>
                            <td><span class="badge bg-${typeClass}"><i class="fas fa-${typeIcon}"></i> Stock ${history.type === 'in' ? 'In' : 'Out'}</span></td>
                            <td><strong>${history.quantity}</strong></td>
                            <td>${history.staff}</td>
                            <td>${history.notes}</td>
                        </tr>
                    `;
                    $('#historyTableBody').append(row);
                });
            }

            // Filter stock history
            $('#filterHistoryType').change(function() {
                loadStockHistory($(this).val(), $('#filterDate').val());
            });

            $('#filterDate').change(function() {
                loadStockHistory($('#filterHistoryType').val(), $(this).val());
            });

            // Update dashboard stats
            function updateDashboardStats() {
                const threshold = parseInt(localStorage.getItem('lowStockThreshold') || 10);
                const totalProducts = products.reduce((sum, p) => sum + p.stock, 0);
                const lowStockItems = products.filter(p => p.stock > 0 && p.stock <= threshold).length;
                const outOfStockItems = products.filter(p => p.stock === 0).length;
                const stockInToday = stockHistory.filter(h => 
                    h.type === 'in' && h.date.includes(new Date().toLocaleDateString())
                ).length;

                $('#totalProducts').text(totalProducts.toLocaleString());
                $('#lowStockItems').text(lowStockItems);
                $('#outOfStockItems').text(outOfStockItems);
                $('#stockInToday').text(stockInToday);

                // Update alerts
                updateAlerts(threshold);
            }

            function updateAlerts(threshold) {
                const outOfStock = products.filter(p => p.stock === 0);
                const lowStock = products.filter(p => p.stock > 0 && p.stock <= threshold);

                $('#criticalAlerts').html('');
                
                outOfStock.forEach(product => {
                    $('#criticalAlerts').append(`
                        <div class="alert-item critical">
                            <strong><i class="fas fa-times-circle"></i> Out of Stock</strong>
                            <p class="mb-0">${product.name} - Immediate restocking required</p>
                        </div>
                    `);
                });

                lowStock.forEach(product => {
                    $('#criticalAlerts').append(`
                        <div class="alert-item">
                            <strong><i class="fas fa-exclamation-triangle"></i> Low Stock</strong>
                            <p class="mb-0">${product.name} - Only ${product.stock} units remaining</p>
                        </div>
                    `);
                });

                if (outOfStock.length === 0 && lowStock.length === 0) {
                    $('#criticalAlerts').html('<p class="text-muted">No critical alerts at this time</p>');
                }
            }

            // Show notification
            function showNotification(message, type) {
                const alertClass = type === 'success' ? 'alert-success' : 
                                 type === 'warning' ? 'alert-warning' :
                                 type === 'danger' ? 'alert-danger' : 'alert-info';
                const iconClass = type === 'success' ? 'check-circle' : 
                                type === 'warning' ? 'exclamation-triangle' :
                                type === 'danger' ? 'times-circle' : 'info-circle';
                                
                const alertHtml = `
                    <div class="alert ${alertClass} alert-dismissible fade show position-fixed" 
                         style="top: 20px; right: 20px; z-index: 9999; min-width: 300px;" role="alert">
                        <i class="fas fa-${iconClass}"></i> ${message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `;
                $('body').append(alertHtml);
                setTimeout(function() {
                    $('.alert').alert('close');
                }, 3000);
            }

            // Initialize
            updateDashboardStats();
            loadInventoryTable();
        });