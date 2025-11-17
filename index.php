<?php
session_start();
require_once 'config/constants.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NoteShare - Marketplace for Academic Notes</title>
    <style>
        /* Base Styles & Color Variables */
        :root {
            --primary-black: #0a0a0a;
            --primary-purple: #6b46c1;
            --primary-green: #2d5016;
            --dark-purple: #4c1d95;
            --dark-green: #1a3410;
            --glass-bg: rgba(255, 255, 255, 0.1);
            --glass-border: rgba(255, 255, 255, 0.2);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Times New Roman', Times, serif;
        }

        body {
            background: linear-gradient(135deg, var(--primary-black) 0%, var(--dark-green) 50%, var(--dark-purple) 100%);
            color: white;
            overflow-x: hidden;
            min-height: 100vh;
        }

        /* Glass Morphism Effects */
        .glass {
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            border: 1px solid var(--glass-border);
            border-radius: 15px;
        }

        /* Header Styles - Responsive */
        header {
            position: fixed;
            top: 0;
            width: 100%;
            padding: 1rem 5%;
            background: var(--glass-bg);
            backdrop-filter: blur(15px);
            border-bottom: 1px solid var(--glass-border);
            z-index: 1000;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 2rem;
            font-weight: bold;
            background: linear-gradient(45deg, var(--primary-purple), var(--primary-green));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            z-index: 1001;
        }

        /* Desktop Navigation */
        .nav-links {
            display: flex;
            gap: 2rem;
            align-items: center;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
            position: relative;
            font-size: 1.1rem;
        }

        .nav-links a:hover {
            color: var(--primary-purple);
        }

        .nav-links a::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--primary-purple);
            transition: width 0.3s ease;
        }

        .nav-links a:hover::after {
            width: 100%;
        }

        .auth-buttons {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        /* Hamburger Menu */
        .hamburger {
            display: none;
            flex-direction: column;
            cursor: pointer;
            padding: 5px;
            z-index: 1001;
        }

        .hamburger span {
            width: 25px;
            height: 3px;
            background: white;
            margin: 3px 0;
            transition: 0.3s;
            border-radius: 2px;
        }

        .hamburger.active span:nth-child(1) {
            transform: rotate(-45deg) translate(-5px, 6px);
        }

        .hamburger.active span:nth-child(2) {
            opacity: 0;
        }

        .hamburger.active span:nth-child(3) {
            transform: rotate(45deg) translate(-5px, -6px);
        }

        /* Mobile Navigation */
        .mobile-nav {
            position: fixed;
            top: 0;
            right: -100%;
            width: 80%;
            max-width: 300px;
            height: 100vh;
            background: linear-gradient(135deg, var(--primary-black) 0%, var(--dark-purple) 100%);
            backdrop-filter: blur(20px);
            padding: 80px 2rem 2rem;
            transition: right 0.3s ease-in-out;
            z-index: 999;
            border-left: 1px solid var(--glass-border);
        }

        .mobile-nav.active {
            right: 0;
        }

        .mobile-nav-links {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .mobile-nav-links a {
            color: white;
            text-decoration: none;
            font-size: 1.2rem;
            padding: 0.8rem 1rem;
            border-radius: 10px;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }

        .mobile-nav-links a:hover {
            background: var(--glass-bg);
            border-left-color: var(--primary-purple);
            transform: translateX(5px);
        }

        .mobile-auth-buttons {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid var(--glass-border);
        }

        .mobile-auth-buttons .btn {
            width: 100%;
            text-align: center;
        }

        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(5px);
            z-index: 998;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .btn {
            padding: 0.7rem 1.5rem;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-family: 'Times New Roman', Times, serif;
            font-size: 1rem;
            font-weight: bold;
        }

        .btn-primary {
            background: linear-gradient(45deg, var(--primary-purple), var(--primary-green));
            color: white;
        }

        .btn-secondary {
            background: transparent;
            border: 2px solid var(--primary-purple);
            color: white;
        }

        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.3);
        }

        /* Hero Section */
        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 80px 5% 5%;
            position: relative;
            overflow: hidden;
            flex-wrap: wrap;
            gap: 2rem;
        }

        .hero-content {
            flex: 1;
            min-width: 300px;
            max-width: 600px;
            z-index: 2;
        }

        .hero h1 {
            font-size: clamp(2.5rem, 5vw, 4rem);
            margin-bottom: 1.5rem;
            line-height: 1.2;
            opacity: 0;
            transform: translateY(50px);
            animation: fadeInUp 1s ease forwards;
        }

        .hero p {
            font-size: clamp(1rem, 2vw, 1.3rem);
            margin-bottom: 2rem;
            line-height: 1.6;
            opacity: 0;
            transform: translateY(50px);
            animation: fadeInUp 1s ease 0.3s forwards;
        }

        .hero-buttons {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            opacity: 0;
            transform: translateY(50px);
            animation: fadeInUp 1s ease 0.6s forwards;
        }

        .floating-notes {
            flex: 1;
            min-width: 300px;
            max-width: 400px;
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            position: relative;
        }

        .note-card {
            padding: 1.5rem;
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            transform: translateX(100px);
            opacity: 0;
            animation: slideInRight 0.8s ease forwards;
            transition: all 0.3s ease;
        }

        .note-card:hover {
            transform: translateX(0) scale(1.05);
        }

        .note-card:nth-child(1) { animation-delay: 0.8s; }
        .note-card:nth-child(2) { animation-delay: 1s; }
        .note-card:nth-child(3) { animation-delay: 1.2s; }

        .note-card h4 {
            color: var(--primary-purple);
            margin-bottom: 0.5rem;
            font-size: 1.2rem;
        }

        .note-card p {
            color: #ccc;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }

        .price {
            font-size: 1.3rem;
            font-weight: bold;
            color: var(--primary-green);
        }

        /* Features Section */
        .features {
            padding: 5rem 5%;
            text-align: center;
        }

        .section-title {
            font-size: clamp(2rem, 4vw, 3rem);
            margin-bottom: 3rem;
            opacity: 0;
            transform: translateY(50px);
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }

        .feature-card {
            padding: 2.5rem 2rem;
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            transform: translateY(50px);
            opacity: 0;
            transition: all 0.3s ease;
            border: 1px solid transparent;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
            border-color: var(--primary-purple);
        }

        .feature-card h3 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
            color: var(--primary-purple);
        }

        .feature-card p {
            color: #ccc;
            line-height: 1.6;
        }

        /* Stats Section */
        .stats {
            padding: 5rem 5%;
            background: var(--glass-bg);
            text-align: center;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }

        .stat-item {
            padding: 2.5rem 1rem;
            background: rgba(0,0,0,0.3);
            border-radius: 15px;
            backdrop-filter: blur(10px);
        }

        .stat-number {
            font-size: clamp(2.5rem, 5vw, 3.5rem);
            font-weight: bold;
            background: linear-gradient(45deg, var(--primary-purple), var(--primary-green));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 0 0 30px rgba(107, 70, 193, 0.5);
            margin-bottom: 0.5rem;
            display: block;
        }

        .stat-item p {
            color: #ccc;
            font-size: 1.1rem;
        }

        /* Testimonials */
        .testimonials {
            padding: 5rem 5%;
        }

        .testimonial-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }

        .testimonial-card {
            padding: 2.5rem;
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            transform: translateY(50px);
            opacity: 0;
            border-left: 4px solid var(--primary-purple);
        }

        .testimonial-card p {
            font-style: italic;
            line-height: 1.6;
            margin-bottom: 1rem;
            color: #ccc;
        }

        .testimonial-card h4 {
            color: var(--primary-green);
            font-weight: bold;
        }

        /* Footer */
        footer {
            background: linear-gradient(135deg, var(--primary-black) 0%, var(--dark-purple) 100%);
            padding: 4rem 5% 2rem;
            margin-top: 5rem;
            border-top: 1px solid var(--glass-border);
        }

        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 3rem;
            margin-bottom: 3rem;
        }

        .footer-section h3 {
            margin-bottom: 1.5rem;
            color: var(--primary-purple);
            font-size: 1.3rem;
            position: relative;
            display: inline-block;
        }

        .footer-section h3::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 0;
            width: 50%;
            height: 2px;
            background: linear-gradient(45deg, var(--primary-purple), var(--primary-green));
            border-radius: 2px;
        }

        .footer-section p, .footer-section a {
            color: #ccc;
            line-height: 1.8;
            text-decoration: none;
            transition: all 0.3s ease;
            display: block;
            margin-bottom: 0.5rem;
        }

        .footer-section a:hover {
            color: var(--primary-purple);
            transform: translateX(5px);
        }

        .footer-bottom {
            text-align: center;
            padding-top: 2rem;
            border-top: 1px solid var(--glass-border);
            color: #999;
        }

        .footer-bottom p {
            font-size: 0.9rem;
        }

        /* Social Links */
        .social-links {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
        }

        .social-links a {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--glass-bg);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .social-links a:hover {
            background: var(--primary-purple);
            transform: translateY(-3px);
        }

        /* Animations */
        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInRight {
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* Scroll Animations */
        .animate-on-scroll {
            opacity: 0;
            transform: translateY(50px);
            transition: all 0.8s ease;
        }

        .animate-on-scroll.animated {
            opacity: 1;
            transform: translateY(0);
        }

        /* Responsive Design */
        @media (max-width: 968px) {
            .nav-links,
            .auth-buttons {
                display: none;
            }

            .hamburger {
                display: flex;
            }
        }

        @media (max-width: 768px) {
            .hero {
                flex-direction: column;
                text-align: center;
                padding: 100px 5% 5%;
            }

            .hero-content {
                max-width: 100%;
            }

            .hero-buttons {
                justify-content: center;
            }

            .floating-notes {
                max-width: 100%;
            }

            .features-grid {
                grid-template-columns: 1fr;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .testimonial-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 480px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .hero-buttons .btn {
                width: 100%;
                text-align: center;
            }
            
            .footer-content {
                grid-template-columns: 1fr;
            }

            .mobile-nav {
                width: 85%;
            }
        }
    </style>
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