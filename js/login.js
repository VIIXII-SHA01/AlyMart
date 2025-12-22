
        // Login form submission
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const rememberMe = document.getElementById('rememberMe').checked;
            
            // Add loading state
            const submitBtn = document.querySelector('.btn-login');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Signing in...';
            submitBtn.disabled = true;
            
            // Simulate login process
            setTimeout(() => {
                if (email && password) {
                    // Store user session (in real app, this would be handled by your backend)
                    if (rememberMe) {
                        localStorage.setItem('userEmail', email);
                    }
                    
                    // Show success message
                    showMessage('Login successful! Redirecting...', 'success');
                    
                    // Redirect to dashboard (replace with your actual dashboard URL)
                    setTimeout(() => {
                        window.location.href = 'dashboard.html';
                    }, 1500);
                } else {
                    showMessage('Please enter valid credentials', 'error');
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }
            }, 2000);
        });
        
        // Forgot password function
        function forgotPassword() {
            showMessage('Password reset link will be sent to your email', 'info');
        }
        
        // Sign up function
        function signUp() {
            window.location.href = 'signup.html';
        }
        
        // Show message function
        function showMessage(message, type) {
            // Create toast message
            const toast = document.createElement('div');
            toast.className = `alert alert-${type === 'success' ? 'success' : type === 'error' ? 'danger' : 'info'} alert-dismissible fade show`;
            toast.style.position = 'fixed';
            toast.style.top = '20px';
            toast.style.right = '20px';
            toast.style.zIndex = '9999';
            toast.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            
            document.body.appendChild(toast);
            
            // Auto remove after 3 seconds
            setTimeout(() => {
                if (toast.parentNode) {
                    toast.remove();
                }
            }, 3000);
        }
        
        // Check for remembered email on page load
        window.addEventListener('load', function() {
            const rememberedEmail = localStorage.getItem('userEmail');
            if (rememberedEmail) {
                document.getElementById('email').value = rememberedEmail;
                document.getElementById('rememberMe').checked = true;
            }
        });
        
        // Add some interactive effects
        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'translateY(-2px)';
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'translateY(0)';
            });
        });