$(document).ready(function() {
            // Sample product data
            const products = [
                { id: 1, name: 'Coca-Cola 1.5L', category: 'beverages', price: 65, stock: 45, icon: '🥤' },
                { id: 2, name: 'Sprite 1.5L', category: 'beverages', price: 60, stock: 38, icon: '🥤' },
                { id: 3, name: 'Royal 1.5L', category: 'beverages', price: 55, stock: 32, icon: '🥤' },
                { id: 4, name: 'Milo 1kg', category: 'beverages', price: 385, stock: 12, icon: '☕' },
                { id: 5, name: 'Lucky Me Pancit Canton', category: 'noodles', price: 12.50, stock: 150, icon: '🍜' },
                { id: 6, name: 'Payless Pancit Canton', category: 'noodles', price: 11, stock: 120, icon: '🍜' },
                { id: 7, name: 'Nissin Cup Noodles', category: 'noodles', price: 25, stock: 85, icon: '🍜' },
                { id: 8, name: 'Piattos Cheese', category: 'snacks', price: 28, stock: 65, icon: '🥨' },
                { id: 9, name: 'Nova', category: 'snacks', price: 8, stock: 200, icon: '🥨' },
                { id: 10, name: 'Chippy', category: 'snacks', price: 8, stock: 180, icon: '🥨' },
                { id: 11, name: 'Alaska Evap Milk', category: 'dairy', price: 28, stock: 95, icon: '🥛' },
                { id: 12, name: 'Bear Brand', category: 'dairy', price: 35, stock: 78, icon: '🥛' },
                { id: 13, name: 'Argentina Corned Beef', category: 'canned', price: 45, stock: 55, icon: '🥫' },
                { id: 14, name: 'Century Tuna', category: 'canned', price: 38, stock: 68, icon: '🥫' },
                { id: 15, name: 'Ligo Sardines', category: 'canned', price: 22, stock: 90, icon: '🥫' }
            ];

            let cart = [];
            let dailySales = 8450;
            let dailyTransactions = 32;

            // Check for saved theme
            const currentTheme = localStorage.getItem('theme') || 'light';
            if (currentTheme === 'dark') {
                $('body').addClass('dark-mode');
                $('#themeToggle').prop('checked', true);
            }

            // Theme toggle
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
                const autoPrint = $('#autoPrint').is(':checked');
                const soundEffects = $('#soundEffects').is(':checked');
                
                localStorage.setItem('autoPrint', autoPrint);
                localStorage.setItem('soundEffects', soundEffects);
                
                $('#settingsModal').modal('hide');
                showNotification('Settings saved successfully!', 'success');
            });

            // Load saved settings
            if (localStorage.getItem('autoPrint') !== null) {
                $('#autoPrint').prop('checked', localStorage.getItem('autoPrint') === 'true');
            }
            if (localStorage.getItem('soundEffects') !== null) {
                $('#soundEffects').prop('checked', localStorage.getItem('soundEffects') === 'true');
            }

            // Load products
            function loadProducts(category = 'all', searchTerm = '') {
                let filteredProducts = products;
                
                if (category !== 'all') {
                    filteredProducts = filteredProducts.filter(p => p.category === category);
                }
                
                if (searchTerm) {
                    filteredProducts = filteredProducts.filter(p => 
                        p.name.toLowerCase().includes(searchTerm.toLowerCase())
                    );
                }

                $('#productsGrid').html('');
                filteredProducts.forEach(product => {
                    const isOutOfStock = product.stock === 0;
                    const stockClass = isOutOfStock ? 'out-of-stock' : '';
                    
                    const productCard = `
                        <div class="product-card ${stockClass}" data-product-id="${product.id}">
                            <div class="icon">${product.icon}</div>
                            <h6>${product.name}</h6>
                            <div class="price">₱${product.price.toFixed(2)}</div>
                            <div class="stock">${isOutOfStock ? 'Out of Stock' : `Stock: ${product.stock}`}</div>
                        </div>
                    `;
                    $('#productsGrid').append(productCard);
                });
            }

            // Initial load
            loadProducts();

            // Category filter
            $('.category-btn').click(function() {
                $('.category-btn').removeClass('active');
                $(this).addClass('active');
                const category = $(this).data('category');
                loadProducts(category, $('#productSearch').val());
            });

            // Search
            $('#productSearch').on('input', function() {
                const searchTerm = $(this).val();
                const activeCategory = $('.category-btn.active').data('category');
                loadProducts(activeCategory, searchTerm);
            });

            // Add to cart
            $(document).on('click', '.product-card:not(.out-of-stock)', function() {
                const productId = $(this).data('product-id');
                const product = products.find(p => p.id === productId);
                
                const existingItem = cart.find(item => item.id === productId);
                if (existingItem) {
                    if (existingItem.quantity < product.stock) {
                        existingItem.quantity++;
                    } else {
                        showNotification('Maximum stock reached', 'warning');
                        return;
                    }
                } else {
                    cart.push({
                        id: product.id,
                        name: product.name,
                        price: product.price,
                        quantity: 1,
                        maxStock: product.stock
                    });
                }
                
                updateCart();
                playSound();
            });

            // Update cart display
            function updateCart() {
                if (cart.length === 0) {
                    $('#cartItems').html(`
                        <div class="text-center text-muted py-5">
                            <i class="fas fa-shopping-cart fa-3x mb-3"></i>
                            <p>Cart is empty<br>Start adding products</p>
                        </div>
                    `);
                    $('#checkoutBtn, #holdBtn').prop('disabled', true);
                } else {
                    let cartHtml = '';
                    cart.forEach(item => {
                        cartHtml += `
                            <div class="cart-item">
                                <div class="cart-item-info">
                                    <div class="cart-item-name">${item.name}</div>
                                    <div class="cart-item-price">₱${item.price.toFixed(2)} each</div>
                                </div>
                                <div class="cart-item-qty">
                                    <button class="qty-btn minus-qty" data-id="${item.id}">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <span style="min-width: 30px; text-align: center;">${item.quantity}</span>
                                    <button class="qty-btn plus-qty" data-id="${item.id}">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                                <strong style="min-width: 70px; text-align: right;">₱${(item.price * item.quantity).toFixed(2)}</strong>
                                <i class="fas fa-times remove-btn" data-id="${item.id}"></i>
                            </div>
                        `;
                    });
                    $('#cartItems').html(cartHtml);
                    $('#checkoutBtn, #holdBtn').prop('disabled', false);
                }
                
                // Calculate totals
                const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
                const discount = 0;
                const total = subtotal - discount;
                
                $('#subtotal').text('₱' + subtotal.toFixed(2));
                $('#discount').text('₱' + discount.toFixed(2));
                $('#total').text('₱' + total.toFixed(2));
            }

            // Increase quantity
            $(document).on('click', '.plus-qty', function() {
                const itemId = $(this).data('id');
                const cartItem = cart.find(item => item.id === itemId);
                if (cartItem && cartItem.quantity < cartItem.maxStock) {
                    cartItem.quantity++;
                    updateCart();
                } else {
                    showNotification('Maximum stock reached', 'warning');
                }
            });

            // Decrease quantity
            $(document).on('click', '.minus-qty', function() {
                const itemId = $(this).data('id');
                const cartItem = cart.find(item => item.id === itemId);
                if (cartItem) {
                    if (cartItem.quantity > 1) {
                        cartItem.quantity--;
                        updateCart();
                    } else {
                        cart = cart.filter(item => item.id !== itemId);
                        updateCart();
                    }
                }
            });

            // Remove item
            $(document).on('click', '.remove-btn', function() {
                const itemId = $(this).data('id');
                cart = cart.filter(item => item.id !== itemId);
                updateCart();
                showNotification('Item removed from cart', 'info');
            });

            // Clear cart
            $('#clearCart').click(function() {
                if (cart.length > 0 && confirm('Are you sure you want to clear the cart?')) {
                    cart = [];
                    updateCart();
                    showNotification('Cart cleared', 'info');
                }
            });

            // Checkout button
            $('#checkoutBtn').click(function() {
                const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
                $('#checkoutTotal').text('₱' + total.toFixed(2));
                $('#amountReceived').val('');
                $('#change').text('₱0.00');
                $('#customerName').val('');
                $('#completePayment').prop('disabled', true);
                $('#checkoutModal').modal('show');
            });

            // Payment method change
            $('#paymentMethod').change(function() {
                if ($(this).val() === 'cash') {
                    $('#cashPaymentFields').show();
                    $('#completePayment').prop('disabled', true);
                } else {
                    $('#cashPaymentFields').hide();
                    $('#completePayment').prop('disabled', false);
                }
            });

            // Calculate change
            $('#amountReceived').on('input', function() {
                const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
                const received = parseFloat($(this).val()) || 0;
                const change = received - total;
                
                if (change >= 0) {
                    $('#change').text('₱' + change.toFixed(2));
                    $('#change').removeClass('text-danger').addClass('text-success');
                    $('#completePayment').prop('disabled', false);
                } else {
                    $('#change').text('Insufficient amount');
                    $('#change').removeClass('text-success').addClass('text-danger');
                    $('#completePayment').prop('disabled', true);
                }
            });

            // Complete payment
            $('#completePayment').click(function() {
                const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
                const paymentMethod = $('#paymentMethod').val();
                const customerName = $('#customerName').val();
                
                // Update daily stats
                dailySales += total;
                dailyTransactions++;
                $('#todaySales').text('₱' + dailySales.toLocaleString());
                $('#todayTransactions').text(dailyTransactions);
                
                // Update product stock
                cart.forEach(cartItem => {
                    const product = products.find(p => p.id === cartItem.id);
                    if (product) {
                        product.stock -= cartItem.quantity;
                    }
                });
                
                // Clear cart
                cart = [];
                updateCart();
                
                // Close modal
                $('#checkoutModal').modal('hide');
                
                // Show success message
                showNotification('Payment successful! Transaction completed.', 'success');
                
                // Reload products to show updated stock
                loadProducts();
                
                // Simulate printing if enabled
                if (localStorage.getItem('autoPrint') === 'true') {
                    setTimeout(() => {
                        showNotification('Receipt printed', 'info');
                    }, 1000);
                }
            });

            // Hold transaction
            $('#holdBtn').click(function() {
                if (cart.length > 0) {
                    const heldTransactions = JSON.parse(localStorage.getItem('heldTransactions') || '[]');
                    heldTransactions.push({
                        id: Date.now(),
                        cart: [...cart],
                        date: new Date().toISOString()
                    });
                    localStorage.setItem('heldTransactions', JSON.stringify(heldTransactions));
                    
                    cart = [];
                    updateCart();
                    showNotification('Transaction held successfully', 'success');
                }
            });

            // Play sound effect
            function playSound() {
                if (localStorage.getItem('soundEffects') === 'true') {
                    const audio = new Audio('data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmwhBTGH0fPTgjMGHm7A7+OZUQ4PVqzn77BdGAk+ltryxnMpBSl+zPLaizsIGGS57OihUxELTKXh8bllHAU2jdXzyn0vBSd7yfDdki8IFmW67OqjUxELTKPj8bllHAU2jdXzyn0vBSd7yfDdki8IFmW67OqjUxELTKPj8bllHAU2jdXzyn0vBSd7yfDdki8IFmW67OqjUxELTKPj8bllHAU2jdXzyn0vBSd7yfDdki8IFmW67OqjUxELTKPj8bllHAU2jdXzyn0vBSd7yfDdki8IFmW67OqjUxELTKPj8bllHAU2jdXzyn0vBSd7yfDdki8IFmW67OqjUxELTKPj8bllHAU2jdXzyn0vBSd7yfDdki8IFmW67OqjUxELTKPj8bllHAU2jdXzyn0vBSd7yfDdki8IFmW67OqjUxELTKPj8bllHAU2jdXzyn0vBSd7yfDdki8IFmW67OqjUxEL');
                    audio.play().catch(e => console.log('Audio play failed:', e));
                }
            }

            // Show notification
            function showNotification(message, type) {
                const alertClass = type === 'success' ? 'alert-success' : 
                                 type === 'warning' ? 'alert-warning' :
                                 type === 'info' ? 'alert-info' : 'alert-primary';
                const alertHtml = `
                    <div class="alert ${alertClass} alert-dismissible fade show position-fixed" 
                         style="top: 20px; right: 20px; z-index: 9999; min-width: 300px;" role="alert">
                        <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'warning' ? 'exclamation-triangle' : 'info-circle'}"></i> ${message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `;
                $('body').append(alertHtml);
                setTimeout(function() {
                    $('.alert').alert('close');
                }, 3000);
            }
        });