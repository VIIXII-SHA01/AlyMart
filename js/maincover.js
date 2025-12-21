 function getStarted() {
            alert('Welcome! Redirecting to dashboard...');
            // Add your redirect logic here
            // window.location.href = 'dashboard.html';
        }

        function learnMore() {
            document.querySelector('#features')?.scrollIntoView({ behavior: 'smooth' });
        }

        // Add scroll effect to navbar
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.style.background = 'rgba(102, 126, 234, 0.95)';
            } else {
                navbar.style.background = 'rgba(255, 255, 255, 0.1)';
            }
        });

        // Add entrance animation to feature cards
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -100px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        document.querySelectorAll('.feature-card').forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px)';
            card.style.transition = 'all 0.6s ease-out';
            observer.observe(card);
        });