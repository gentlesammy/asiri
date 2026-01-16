document.addEventListener('DOMContentLoaded', () => {

    // Navbar Blur Effect on Scroll
    const navbar = document.querySelector('.navbar');

    window.addEventListener('scroll', () => {
        if (window.scrollY > 50) {
            navbar.style.background = 'rgba(5, 5, 10, 0.9)';
            navbar.style.padding = '0.5rem 0';
            navbar.classList.add('shadow-sm');
        } else {
            navbar.style.background = 'rgba(5, 5, 10, 0.7)';
            navbar.style.padding = '1rem 0';
            navbar.classList.remove('shadow-sm');
        }
    });

    // Smooth Scrolling for Anchors
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth'
                });
            }
        });
    });

    // Simple entrance animation for cards
    const observerOptions = {
        threshold: 0.1
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    document.querySelectorAll('.feature-card, .step-card').forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(20px)';
        el.style.transition = 'opacity 0.6s ease-out, transform 0.6s ease-out';
        observer.observe(el);
    });

    // Form Validation for Register Page
    const registerForm = document.getElementById('registerForm');
    if (registerForm) {
        registerForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirmPassword').value;
            const passwordError = document.getElementById('passwordError');

            if (password !== confirmPassword) {
                passwordError.classList.remove('d-none');
                return;
            } else {
                passwordError.classList.add('d-none');
                // Proceed with registration handling (e.g. API call)
                // alert('Account created successfully! (Mock)');
                // Redirect to Dashboard
                window.location.href = 'messages.html';
            }
        });
    }

    // Login Form Handling
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', (e) => {
            e.preventDefault();
            // alert('Logged in successfully! (Mock)');
            window.location.href = 'messages.html';
        });
    }

    // Message Filtering Logic
    const filterBtns = document.querySelectorAll('.filter-btn');
    const messageItems = document.querySelectorAll('.message-item');

    filterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            // Remove active class from all
            filterBtns.forEach(b => b.classList.remove('active'));
            // Add active to clicked
            btn.classList.add('active');

            const filter = btn.getAttribute('data-filter');

            messageItems.forEach(item => {
                if (filter === 'all' || item.getAttribute('data-status') === filter) {
                    item.style.display = 'block';
                    // Re-trigger animation
                    item.style.opacity = '0';
                    item.style.transform = 'translateY(20px)';
                    setTimeout(() => {
                        item.style.opacity = '1';
                        item.style.transform = 'translateY(0)';
                    }, 50);
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });

    // Profile Avatar Preview - Logic moved to updateAvatar function to prevent conflicts




    // Send Message Form Handling
    const sendMessageForm = document.getElementById('sendMessageForm');
    if (sendMessageForm) {
        sendMessageForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const btn = sendMessageForm.querySelector('button[type="submit"]');
            const originalText = btn.innerText;
            btn.innerText = 'Sending...';
            btn.disabled = true;

            setTimeout(() => {
                btn.innerText = 'Sent Successfully! 🚀';
                btn.classList.add('btn-success');
                btn.classList.remove('btn-premium');

                // Reset form
                sendMessageForm.reset();

                setTimeout(() => {
                    btn.innerText = originalText;
                    btn.disabled = false;
                    btn.classList.remove('btn-success');
                    btn.classList.add('btn-premium');
                }, 3000);
            }, 1500);
        });
    }

    // Admin Dashboard Logic
    // Admin Dashboard Logic
    const menuToggle = document.getElementById("menu-toggle");
    const wrapper = document.getElementById("wrapper");
    const pageContentWrapper = document.getElementById("page-content-wrapper");

    if (menuToggle && wrapper) {
        // Toggle click
        menuToggle.addEventListener("click", (e) => {
            e.preventDefault();
            e.stopPropagation(); // Prevent bubbling to document
            wrapper.classList.toggle("toggled");
        });

        // Close when clicking outside sidebar on mobile
        if (pageContentWrapper) {
            pageContentWrapper.addEventListener('click', (e) => {
                // If screen is small (mobile behavior where sidebar overlays)
                if (window.innerWidth <= 768) {
                    // If sidebar is shown (which means wrapper has toggled class in our CSS logic for mobile? 
                    // Wait, CSS says: Mobile: Sidebar Hidden by Default (-250px). Toggled: Margin 0 (Shown).
                    if (wrapper.classList.contains("toggled")) {
                        // Clicked outside sidebar?
                        // The pageContentWrapper is distinct from sidebar.
                        wrapper.classList.remove("toggled");
                    }
                }
            });
        }
    }

    // Admin: Block User Toggle
    const blockButtons = document.querySelectorAll('.btn-block-user');
    blockButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            if (btn.classList.contains('btn-outline-danger')) {
                // Block Action
                if (confirm('Are you sure you want to block this user?')) {
                    btn.classList.remove('btn-outline-danger');
                    btn.classList.add('btn-outline-success');
                    btn.innerText = 'Unblock';
                    // Update status badge (mock)
                    const row = btn.closest('tr');
                    const badge = row.querySelector('.badge');
                    if (badge) {
                        badge.classList.remove('bg-success');
                        badge.classList.add('bg-danger');
                        badge.innerText = 'Blocked';
                    }
                }
            } else {
                // Unblock Action
                btn.classList.remove('btn-outline-success');
                btn.classList.add('btn-outline-danger');
                btn.innerText = 'Block';
                // Update status badge (mock)
                const row = btn.closest('tr');
                const badge = row.querySelector('.badge');
                if (badge) {
                    badge.classList.remove('bg-danger');
                    badge.classList.add('bg-success');
                    badge.innerText = 'Active';
                }
            }
        });
    });

    // Admin: Delete Category
    const deleteCatButtons = document.querySelectorAll('.btn-delete-cat');
    deleteCatButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            if (confirm('Delete this category?')) {
                const item = btn.closest('li');
                item.style.opacity = '0';
                setTimeout(() => item.remove(), 300);
            }
        });
    });

    // Admin: Add Category Mock
    const addCategoryForm = document.getElementById('addCategoryForm');
    if (addCategoryForm) {
        addCategoryForm.addEventListener('submit', (e) => {
            e.preventDefault();
            alert('Category added successfully! (Mock)');
            addCategoryForm.reset();
        });
    }

    // Messages Page Redesign Logic
    window.selectMessage = function (element) {
        // Remove active class from all items
        document.querySelectorAll('.message-list-item').forEach(item => {
            item.classList.remove('active');
        });

        // Add active class to clicked item
        element.classList.add('active');

        // Update Reading Pane
        const content = element.getAttribute('data-content');
        const time = element.getAttribute('data-time');

        const displayContent = document.getElementById('displayContent');
        const displayTime = document.getElementById('displayTime');

        if (displayContent) displayContent.innerText = `"${content}"`;
        if (displayTime) displayTime.innerText = `For: @anonymous_user • ${time}`;
    };

    window.reportMessage = function () {
        if (confirm('Alert: The message content will be shared with the admin for review. Continue?')) {
            // Mock API call
            setTimeout(() => {
                alert('Message reported successfully. We will review it shortly.');
            }, 500);
        }
    };

    window.shareMessage = function () {
        const captureArea = document.getElementById('captureArea');
        if (!captureArea) return;

        // Use html2canvas
        if (typeof html2canvas !== 'undefined') {
            html2canvas(captureArea, {
                backgroundColor: null,
                scale: 2
            }).then(canvas => {
                const link = document.createElement('a');
                link.download = 'asiri-message.png';
                link.href = canvas.toDataURL();
                link.click();
            });
        } else {
            alert('Image generation library not loaded yet. Please try again in a moment.');
        }
    };

    // Admin Reports Logic
    window.blockIP = function (ip) {
        if (confirm(`Are you sure you want to block IP address: ${ip}? This will prevent any further messages from this source.`)) {
            // Mock API
            setTimeout(() => {
                alert(`IP Address ${ip} has been successfully blacklisted.`);
            }, 300);
        }
    };

    window.updateReportStatus = function () {
        const select = document.getElementById('reportStatus');
        const status = select.value;
        // Mock API
        setTimeout(() => {
            alert(`Report status has been updated to: ${status}`);
        }, 300);
    };



    window.copyLink = function () {
        const link = document.getElementById('personal-link');
        link.select();
        document.execCommand('copy');
        document.getElementById('copy-success').innerText = 'Link copied to clipboard!';
        setTimeout(() => document.getElementById('copy-success').innerText = '', 3000);
    };

    window.copyLinkError = function () {
        document.getElementById('copy-error').innerText = 'Failed to copy link.';
        setTimeout(() => document.getElementById('copy-error').innerText = '', 3000);
    };



});
