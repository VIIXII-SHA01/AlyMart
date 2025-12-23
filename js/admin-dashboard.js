 $(document).ready(function() {
            // Check for saved theme preference or default to light mode
            const currentTheme = localStorage.getItem('theme') || 'light';
            if (currentTheme === 'dark') {
                $('body').addClass('dark-mode');
                $('#themeToggle').prop('checked', true);
            }

            // Theme Toggle Functionality
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

            // Save Settings Button
            $('#saveSettings').click(function() {
                const lowStockNotif = $('#lowStockNotif').is(':checked');
                const salesNotif = $('#salesNotif').is(':checked');
                const reportNotif = $('#reportNotif').is(':checked');
                const chartType = $('#chartType').val();
                const dashboardLayout = $('#dashboardLayout').val();

                // Save to localStorage
                localStorage.setItem('lowStockNotif', lowStockNotif);
                localStorage.setItem('salesNotif', salesNotif);
                localStorage.setItem('reportNotif', reportNotif);
                localStorage.setItem('chartType', chartType);
                localStorage.setItem('dashboardLayout', dashboardLayout);

                // Close modal
                $('#settingsModal').modal('hide');
                
                // Show success message
                showNotification('Settings saved successfully!', 'success');
            });

            // Load saved settings
            function loadSettings() {
                if (localStorage.getItem('lowStockNotif') !== null) {
                    $('#lowStockNotif').prop('checked', localStorage.getItem('lowStockNotif') === 'true');
                }
                if (localStorage.getItem('salesNotif') !== null) {
                    $('#salesNotif').prop('checked', localStorage.getItem('salesNotif') === 'true');
                }
                if (localStorage.getItem('reportNotif') !== null) {
                    $('#reportNotif').prop('checked', localStorage.getItem('reportNotif') === 'true');
                }
                if (localStorage.getItem('chartType')) {
                    $('#chartType').val(localStorage.getItem('chartType'));
                }
                if (localStorage.getItem('dashboardLayout')) {
                    $('#dashboardLayout').val(localStorage.getItem('dashboardLayout'));
                }
            }

            // Function to show notifications
            function showNotification(message, type) {
                const alertClass = type === 'success' ? 'alert-success' : 'alert-info';
                const alertHtml = `
                    <div class="alert ${alertClass} alert-dismissible fade show position-fixed" 
                         style="top: 20px; right: 20px; z-index: 9999; min-width: 300px;" role="alert">
                        <i class="fas fa-check-circle"></i> ${message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `;
                $('body').append(alertHtml);
                setTimeout(function() {
                    $('.alert').alert('close');
                }, 3000);
            }

            // Load settings on page load
            loadSettings();
            // Initialize Sales Chart
            const ctx = document.getElementById('salesChart').getContext('2d');
            const salesChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                    datasets: [{
                        label: 'Sales',
                        data: [32000, 38000, 35000, 42000, 48000, 52000, 45320],
                        borderColor: '#2563eb',
                        backgroundColor: 'rgba(37, 99, 235, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return '₱' + value.toLocaleString();
                                }
                            }
                        }
                    }
                }
            });

            // Sidebar menu interactions
            $('.sidebar-menu a').click(function(e) {
                e.preventDefault();
                $('.sidebar-menu a').removeClass('active');
                $(this).addClass('active');
            });

            // Notification badge interaction
            $('.notification-badge').click(function() {
                alert('5 new notifications:\n- 3 Low stock alerts\n- 1 Out of stock alert\n- 1 Daily report ready');
            });

            // Quick action buttons
            $('.quick-action-btn').click(function() {
                const action = $(this).text().trim();
                alert('Opening: ' + action);
            });

            // Animate numbers on load
            function animateValue(id, start, end, duration) {
                const obj = document.getElementById(id);
                if (!obj) return;
                
                const range = end - start;
                const increment = end > start ? 1 : -1;
                const stepTime = Math.abs(Math.floor(duration / range));
                let current = start;
                
                const timer = setInterval(function() {
                    current += increment;
                    if (id === 'totalSales') {
                        obj.innerHTML = '₱' + current.toLocaleString();
                    } else {
                        obj.innerHTML = current.toLocaleString();
                    }
                    if (current == end) {
                        clearInterval(timer);
                    }
                }, stepTime);
            }

            // Simulate real-time updates
            setInterval(function() {
                const currentSales = parseInt($('#totalSales').text().replace('₱', '').replace(',', ''));
                const newSales = currentSales + Math.floor(Math.random() * 500);
                $('#totalSales').text('₱' + newSales.toLocaleString());

                const currentTransactions = parseInt($('#totalTransactions').text());
                $('#totalTransactions').text(currentTransactions + 1);
            }, 10000);
        });