<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header('Location: pages/home.php');
    exit();
}
require_once 'config/constants.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NoteShare - Marketplace for Academic Notes</title>
    <link rel="stylesheet" href="assets/css/indexstyle.css">
</head>
<body>
    <!-- Header -->
    <header class="glass">
        <div class="logo">NoteShare</div>
        
        <!-- Desktop Navigation -->
        <nav class="nav-links">
            <a href="#features">Features</a>
            <a href="pages/login.php">Browse Notes</a>
            <a href="#testimonials">Testimonials</a>
            <a href="#about">About</a>
        </nav>
        
        <div class="auth-buttons">
            <button class="btn btn-secondary" onclick="location.href='pages/login.php'">Login</button>
            <button class="btn btn-primary" onclick="location.href='pages/login.php'">Sign Up Free</button>
        </div>

        <!-- Hamburger Menu -->
        <div class="hamburger" id="hamburger">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </header>

    <!-- Mobile Navigation -->
    <div class="mobile-nav" id="mobileNav">
        <div class="mobile-nav-links">
            <a href="#features" onclick="closeMobileNav()">Features</a>
            <a href="pages/login.php" onclick="closeMobileNav()">Browse Notes</a>
            <a href="#testimonials" onclick="closeMobileNav()">Testimonials</a>
            <a href="#about" onclick="closeMobileNav()">About</a>
        </div>
        <div class="mobile-auth-buttons">
            <button class="btn btn-primary" onclick="location.href='pages/login.php'; closeMobileNav();">Sign Up Free</button>
            <button class="btn btn-secondary" onclick="location.href='pages/login.php'; closeMobileNav();">Login</button>
        </div>
    </div>

    <!-- Overlay -->
    <div class="overlay" id="overlay" onclick="closeMobileNav()"></div>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1>Share Knowledge, Earn Rewards</h1>
            <p>The ultimate marketplace for academic notes. Buy, sell, and share quality study materials with students worldwide. Join thousands of students already earning from their notes.</p>
            <div class="hero-buttons">
                <button class="btn btn-primary" onclick="location.href='pages/login.php'">Start Selling Notes</button>
                <button class="btn btn-secondary" onclick="location.href='pages/browse_notes.php'">Browse Popular Notes</button>
            </div>
        </div>
        <div class="floating-notes">
            <div class="note-card glass">
                <h4>🚀 Computer Science</h4>
                <p>Advanced Algorithms & Data Structures Complete Guide</p>
                <div class="price">$12.99</div>
            </div>
            <div class="note-card glass">
                <h4>🏥 Medicine</h4>
                <p>Human Anatomy & Physiology Master Notes</p>
                <div class="price">$15.99</div>
            </div>
            <div class="note-card glass">
                <h4>💼 Business</h4>
                <p>Digital Marketing Strategies & Case Studies</p>
                <div class="price">$9.99</div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="features">
        <h2 class="section-title animate-on-scroll">Why Choose NoteShare?</h2>
        <div class="features-grid">
            <div class="feature-card glass animate-on-scroll">
                <h3>💰 Earn Money</h3>
                <p>Turn your quality notes into passive income. Get paid for helping others succeed in their studies.</p>
            </div>
            <div class="feature-card glass animate-on-scroll">
                <h3>📚 Quality Content</h3>
                <p>Access verified, high-quality notes from top students and professional educators worldwide.</p>
            </div>
            <div class="feature-card glass animate-on-scroll">
                <h3>⚡ Fast Downloads</h3>
                <p>Instant access to purchased notes. Study anytime, anywhere with our mobile-friendly platform.</p>
            </div>
            <div class="feature-card glass animate-on-scroll">
                <h3>🛡️ Secure Payments</h3>
                <p>Safe and secure payment processing with buyer protection and guaranteed refund policy.</p>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats">
        <h2 class="section-title animate-on-scroll">Our Growing Community</h2>
        <div class="stats-grid">
            <div class="stat-item glass animate-on-scroll">
                <span class="stat-number" id="userCount">0</span>
                <p>Active Users</p>
            </div>
            <div class="stat-item glass animate-on-scroll">
                <span class="stat-number" id="noteCount">0</span>
                <p>Notes Shared</p>
            </div>
            <div class="stat-item glass animate-on-scroll">
                <span class="stat-number" id="saleCount">0</span>
                <p>Successful Sales</p>
            </div>
            <div class="stat-item glass animate-on-scroll">
                <span class="stat-number" id="earningCount">$0</span>
                <p>Total Earned</p>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section id="testimonials" class="testimonials">
        <h2 class="section-title animate-on-scroll">What Our Users Say</h2>
        <div class="testimonial-grid">
            <div class="testimonial-card glass animate-on-scroll">
                <p>"NoteShare helped me earn over $500 while in college. The platform is easy to use and payments are always on time. Best side hustle for students!"</p>
                <h4>- Sarah Chen, Computer Science</h4>
            </div>
            <div class="testimonial-card glass animate-on-scroll">
                <p>"The quality of notes here is exceptional. Found perfect study materials for my medical exams that saved me during finals week. Highly recommended!"</p>
                <h4>- Mike Rodriguez, Medical Student</h4>
            </div>
            <div class="testimonial-card glass animate-on-scroll">
                <p>"As a business major, I've both bought and sold notes. The platform is intuitive, and I've earned enough to cover my textbook costs each semester!"</p>
                <h4>- Jessica Williams, Business Major</h4>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <div class="footer-section">
                <h3>NoteShare</h3>
                <p>Connecting students worldwide through knowledge sharing and earning opportunities. Your success is our mission.</p>
                <div class="social-links">
                    <a href="#">📘</a>
                    <a href="#">🐦</a>
                    <a href="#">📷</a>
                    <a href="#">💼</a>
                </div>
            </div>
            <div class="footer-section">
                <h3>Quick Links</h3>
                <a href="#features">Features</a>
                <a href="pages/login.php">Browse Notes</a>
                <a href="#testimonials">Testimonials</a>
                <a href="pages/login.php">Sell Notes</a>
            </div>
            <div class="footer-section">
                <h3>Support</h3>
                <a href="#">Help Center</a>
                <a href="#">Contact Us</a>
                <a href="#">Privacy Policy</a>
                <a href="#">Terms of Service</a>
            </div>
            <div class="footer-section">
                <h3>Contact Info</h3>
                <p>📧 akash12ranjan@gmail.com</p>
                <p>📞 +91 9508009054</p>
                <p>📍 Jaipur, Rajasthan, India</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2024 NoteShare. All rights reserved. | Empowering Students Worldwide</p>
        </div>
    </footer>

    <script>
        // Mobile Navigation Toggle
        const hamburger = document.getElementById('hamburger');
        const mobileNav = document.getElementById('mobileNav');
        const overlay = document.getElementById('overlay');

        function toggleMobileNav() {
            hamburger.classList.toggle('active');
            mobileNav.classList.toggle('active');
            overlay.classList.toggle('active');
            document.body.style.overflow = mobileNav.classList.contains('active') ? 'hidden' : '';
        }

        function closeMobileNav() {
            hamburger.classList.remove('active');
            mobileNav.classList.remove('active');
            overlay.classList.remove('active');
            document.body.style.overflow = '';
        }

        hamburger.addEventListener('click', toggleMobileNav);

        // Close mobile nav when clicking on overlay
        overlay.addEventListener('click', closeMobileNav);

        // Close mobile nav when window is resized above 968px
        window.addEventListener('resize', function() {
            if (window.innerWidth > 968) {
                closeMobileNav();
            }
        });

        // Scroll Animation
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animated');
                }
            });
        }, { threshold: 0.1 });

        document.querySelectorAll('.animate-on-scroll').forEach((el) => {
            observer.observe(el);
        });

        // Animated Counter
        function animateCounter(element, start, end, duration, prefix = '') {
            let startTime = null;
            const step = (timestamp) => {
                if (!startTime) startTime = timestamp;
                const progress = Math.min((timestamp - startTime) / duration, 1);
                const value = Math.floor(progress * (end - start) + start);
                element.textContent = prefix + value.toLocaleString();
                if (progress < 1) {
                    window.requestAnimationFrame(step);
                }
            };
            window.requestAnimationFrame(step);
        }

        // Initialize counters when stats section is visible
        const statsObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    animateCounter(document.getElementById('userCount'), 0, 12500, 2000);
                    animateCounter(document.getElementById('noteCount'), 0, 8500, 2000);
                    animateCounter(document.getElementById('saleCount'), 0, 3200, 2000);
                    animateCounter(document.getElementById('earningCount'), 0, 45000, 2000, '$');
                    statsObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });

        statsObserver.observe(document.querySelector('.stats'));

        // Add loading animation for images
        window.addEventListener('load', function() {
            document.body.classList.add('loaded');
        });
    </script>
</body>
</html>