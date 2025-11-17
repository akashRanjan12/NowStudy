<?php
session_start();
// require_once 'config/constants.php';

// Demo user data (replace with actual session data)
$current_user = [
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'avatar' => '👤'
];

// Demo notes data
$popular_notes = [
    ['id' => 1, 'title' => 'physics', 'subject' => 'Computer Science', 'price' => 12.99, 'rating' => 4.8, 'preview' => 'algorithms.jpg'],
    ['id' => 2, 'title' => 'Human Anatomy Guide', 'subject' => 'Medicine', 'price' => 15.99, 'rating' => 4.9, 'preview' => 'anatomy.jpg'],
    ['id' => 3, 'title' => 'Marketing Strategies', 'subject' => 'Business', 'price' => 9.99, 'rating' => 4.5, 'preview' => 'marketing.jpg'],
    ['id' => 4, 'title' => 'Organic Chemistry', 'subject' => 'Chemistry', 'price' => 11.99, 'rating' => 4.7, 'preview' => 'chemistry.jpg'],
    ['id' => 5, 'title' => 'Calculus Made Easy', 'subject' => 'Mathematics', 'price' => 8.99, 'rating' => 4.6, 'preview' => 'calculus.jpg'],
    ['id' => 6, 'title' => 'Fundamentals', 'subject' => 'Physics', 'price' => 13.99, 'rating' => 4.8, 'preview' => 'physics.jpg']
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NoteShare - Marketplace for Academic Notes</title>
    <style>
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

        /* Glass Morphism */
        .glass {
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            border: 1px solid var(--glass-border);
            border-radius: 15px;
        }

        /* Header Styles */
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

        /* User Dropdown */
        .user-menu {
            position: relative;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            padding: 0.5rem 1rem;
            border-radius: 25px;
            transition: all 0.3s ease;
        }

        .user-menu:hover {
            background: var(--glass-bg);
        }

        .user-avatar {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: linear-gradient(45deg, var(--primary-purple), var(--primary-green));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }

        .user-name {
            font-weight: bold;
        }

        .dropdown-menu {
            position: absolute;
            top: 100%;
            right: 0;
            width: 200px;
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 15px;
            padding: 1rem 0;
            margin-top: 0.5rem;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s ease;
            z-index: 1002;
        }

        .dropdown-menu.active {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .dropdown-menu a {
            display: block;
            padding: 0.8rem 1.5rem;
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .dropdown-menu a:hover {
            background: rgba(107, 70, 193, 0.2);
            color: var(--primary-purple);
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
            padding: 100px 5% 5%;
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

        .explore-btn {
            background: linear-gradient(45deg, var(--primary-purple), var(--primary-green));
            color: white;
            padding: 1rem 2rem;
            border: none;
            border-radius: 50px;
            font-size: 1.2rem;
            font-weight: bold;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        .explore-btn::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(255,255,255,0.3), transparent);
            transform: rotate(45deg);
            transition: all 0.6s ease;
        }

        .explore-btn:hover::before {
            transform: rotate(45deg) translate(50%, 50%);
        }

        .explore-btn:hover {
            transform: translateY(-5px) scale(1.05);
            box-shadow: 0 20px 40px rgba(107, 70, 193, 0.4);
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

        /* Search Section */
        .search-section {
            padding: 4rem 5%;
            text-align: center;
        }

        .search-container {
            max-width: 100%;
            margin: 0 auto 3rem;
        }

        .search-box {
            position: relative;
            margin-bottom: 2rem;
        }

        .search-input {
            width: 100%;
            padding: 1.2rem 1.2rem 1.2rem 3.5rem;
            background: var(--glass-bg);
            border: 2px solid var(--glass-border);
            border-radius: 50px;
            color: white;
            font-size: 1.1rem;
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }

        .search-input:focus {
            outline: none;
            border-color: var(--primary-purple);
            box-shadow: 0 10px 30px rgba(107, 70, 193, 0.3);
            transform: translateY(-2px);
        }

        .search-icon {
            position: absolute;
            left: 1.5rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--primary-purple);
            font-size: 1.3rem;
        }

        .notes-grid-container {
            height: 65vh;
            overflow-y: auto;
            padding: 1rem;
            background: var(--glass-bg);
            border-radius: 20px;
            border: 1px solid var(--glass-border);
        }

        .notes-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            padding: 1rem;
        }

        .note-item {
            background: rgba(0, 0, 0, 0.3);
            border-radius: 15px;
            padding: 1.5rem;
            transition: all 0.3s ease;
            border: 1px solid transparent;
            opacity: 0;
            transform: translateY(50px);
        }

        .note-item.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .note-item:hover {
            border-color: var(--primary-purple);
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.3);
        }

        .note-preview {
            width: 100%;
            height: 120px;
            background: linear-gradient(45deg, var(--primary-purple), var(--primary-green));
            border-radius: 10px;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
        }

        .note-title {
            font-size: 1.2rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
            color: white;
        }

        .note-subject {
            color: var(--primary-purple);
            margin-bottom: 0.5rem;
            font-weight: bold;
        }

        .note-price {
            font-size: 1.3rem;
            font-weight: bold;
            color: var(--primary-green);
            margin-bottom: 0.5rem;
        }

        .note-rating {
            color: #ffd700;
            font-size: 0.9rem;
        }

        /* Why Choose Us Section */
        .why-choose {
            padding: 5rem 5%;
            background: var(--glass-bg);
        }

        .section-title {
            font-size: clamp(2rem, 4vw, 3rem);
            margin-bottom: 3rem;
            text-align: center;
            opacity: 0;
            transform: translateY(50px);
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
        }

        .feature-card {
            padding: 2.5rem 2rem;
            background: rgba(0,0,0,0.3);
            border-radius: 20px;
            text-align: center;
            transform: translateY(50px);
            opacity: 0;
            transition: all 0.3s ease;
            border: 1px solid transparent;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            border-color: var(--primary-purple);
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
        }

        .feature-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        .feature-card h3 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
            color: var(--primary-purple);
        }

        /* Reach Us Section */
        .reach-us {
            padding: 5rem 5%;
            text-align: center;
        }

        .contact-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }

        .contact-card {
            padding: 2rem;
            background: var(--glass-bg);
            border-radius: 15px;
            transform: translateY(50px);
            opacity: 0;
            transition: all 0.3s ease;
        }

        .contact-card:hover {
            transform: translateY(-5px);
        }

        .contact-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: var(--primary-purple);
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
            .nav-links {
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
                padding: 120px 5% 5%;
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

            .notes-grid {
                grid-template-columns: 1fr;
            }

            .features-grid {
                grid-template-columns: 1fr;
            }

            .contact-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 480px) {
            .user-name {
                display: none;
            }

            .hero-buttons .btn {
                width: 100%;
                text-align: center;
            }

            .footer-content {
                grid-template-columns: 1fr;
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
            <a href="#search">Browse Notes</a>
            <a href="#why-choose">Why Choose Us</a>
            <a href="#reach-us">Reach Us</a>
            
            <!-- User Menu -->
            <div class="user-menu" id="userMenu">
                <div class="user-avatar"><?php echo $current_user['avatar']; ?></div>
                <span class="user-name"><?php echo explode(' ', $current_user['name'])[0]; ?></span>
                <div class="dropdown-menu" id="dropdownMenu">
                    <a href="pages/dashboard.php">📊 Dashboard</a>
                    <a href="pages/upload_note.php">📤 Upload Notes</a>
                    <a href="pages/profile.php">👤 My Profile</a>
                    <a href="pages/logout.php">🚪 Logout</a>
                </div>
            </div>
        </nav>

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
            <a href="#search" onclick="closeMobileNav()">Browse Notes</a>
            <a href="#why-choose" onclick="closeMobileNav()">Why Choose Us</a>
            <a href="#reach-us" onclick="closeMobileNav()">Reach Us</a>
            <a href="pages/user-dashboard.php" onclick="closeMobileNav()">📊 Dashboard</a>
            <a href="pages/upload_note.php" onclick="closeMobileNav()">📤 Upload Notes</a>
            <a href="pages/profile.php" onclick="closeMobileNav()">👤 My Profile</a>
            <a href="pages/logout.php" onclick="closeMobileNav()">🚪 Logout</a>
        </div>
    </div>

    <!-- Overlay -->
    <div class="overlay" id="overlay" onclick="closeMobileNav()"></div>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1>Discover & Share Knowledge</h1>
            <p>Join thousands of students buying and selling quality academic notes. Find the perfect study materials for your courses or start earning from your own notes.</p>
            <div class="hero-buttons">
                <button class="explore-btn" onclick="scrollToSearch()">🚀 Explore Notes</button>
                <button class="btn btn-secondary" onclick="location.href='pages/upload_note.php'">Start Selling</button>
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

    <!-- Search Section -->
    <section id="search" class="search-section">
        <div class="search-container">
            <h2 class="section-title animate-on-scroll">Find Perfect Study Materials</h2>
            <div class="search-box">
                <span class="search-icon">🔍</span>
                <input type="text" class="search-input" id="searchInput" placeholder="Search for notes by subject, topic, or course...">
            </div>
            
            <div class="notes-grid-container">
                <div class="notes-grid" id="notesGrid">
                    <?php foreach ($popular_notes as $note): ?>
                    <div class="note-item glass" data-title="<?php echo strtolower($note['title']); ?>" data-subject="<?php echo strtolower($note['subject']); ?>">
                        <div class="note-preview">
                            <?php echo substr($note['subject'], 0, 2); ?>
                        </div>
                        <h3 class="note-title"><?php echo $note['title']; ?></h3>
                        <p class="note-subject"><?php echo $note['subject']; ?></p>
                        <div class="note-price">$<?php echo $note['price']; ?></div>
                        <div class="note-rating">⭐ <?php echo $note['rating']; ?>/5</div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Choose Us Section -->
    <section id="why-choose" class="why-choose">
        <h2 class="section-title animate-on-scroll">Why Choose NoteShare?</h2>
        <div class="features-grid">
            <div class="feature-card glass animate-on-scroll">
                <div class="feature-icon">💰</div>
                <h3>Earn Money</h3>
                <p>Turn your quality notes into passive income. Get paid for helping others succeed.</p>
            </div>
            <div class="feature-card glass animate-on-scroll">
                <div class="feature-icon">📚</div>
                <h3>Quality Content</h3>
                <p>Verified, high-quality notes from top students and educators worldwide.</p>
            </div>
            <div class="feature-card glass animate-on-scroll">
                <div class="feature-icon">⚡</div>
                <h3>Instant Access</h3>
                <p>Download purchased notes immediately. Study anytime, anywhere.</p>
            </div>
            <div class="feature-card glass animate-on-scroll">
                <div class="feature-icon">🛡️</div>
                <h3>Secure Platform</h3>
                <p>Safe payments and buyer protection with guaranteed refund policy.</p>
            </div>
        </div>
    </section>

    <!-- Reach Us Section -->
    <section id="reach-us" class="reach-us">
        <h2 class="section-title animate-on-scroll">Get In Touch</h2>
        <div class="contact-grid">
            <div class="contact-card glass animate-on-scroll">
                <div class="contact-icon">📧</div>
                <h3>Email Us</h3>
                <p>support@noteshare.com</p>
            </div>
            <div class="contact-card glass animate-on-scroll">
                <div class="contact-icon">📞</div>
                <h3>Call Us</h3>
                <p>+1 (555) 123-4567</p>
            </div>
            <div class="contact-card glass animate-on-scroll">
                <div class="contact-icon">💬</div>
                <h3>Live Chat</h3>
                <p>24/7 Support Available</p>
            </div>
            <div class="contact-card glass animate-on-scroll">
                <div class="contact-icon">📍</div>
                <h3>Visit Us</h3>
                <p>123 Education Street, Learn City</p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <div class="footer-section">
                <h3>NoteShare</h3>
                <p>Connecting students worldwide through knowledge sharing and earning opportunities.</p>
                <div class="social-links">
                    <a href="#">📘</a>
                    <a href="#">🐦</a>
                    <a href="#">📷</a>
                    <a href="#">💼</a>
                </div>
            </div>
            <div class="footer-section">
                <h3>Quick Links</h3>
                <a href="#search">Browse Notes</a>
                <a href="pages/upload_note.php">Sell Notes</a>
                <a href="#why-choose">Why Choose Us</a>
                <a href="#reach-us">Contact</a>
            </div>
            <div class="footer-section">
                <h3>Support</h3>
                <a href="#">Help Center</a>
                <a href="#">Privacy Policy</a>
                <a href="#">Terms of Service</a>
                <a href="#">FAQ</a>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2024 NoteShare. All rights reserved. | Empowering Students Worldwide</p>
        </div>
    </footer>

    <script>
        // Mobile Navigation
        const hamburger = document.getElementById('hamburger');
        const mobileNav = document.getElementById('mobileNav');
        const overlay = document.getElementById('overlay');
        const userMenu = document.getElementById('userMenu');
        const dropdownMenu = document.getElementById('dropdownMenu');

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
        overlay.addEventListener('click', closeMobileNav);

        // User Dropdown
        userMenu.addEventListener('click', (e) => {
            e.stopPropagation();
            dropdownMenu.classList.toggle('active');
        });

        document.addEventListener('click', () => {
            dropdownMenu.classList.remove('active');
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

        // Note Items Animation
        const noteObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, { threshold: 0.1 });

        document.querySelectorAll('.note-item').forEach((el) => {
            noteObserver.observe(el);
        });

        // Search Functionality
        const searchInput = document.getElementById('searchInput');
        const noteItems = document.querySelectorAll('.note-item');

        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            
            noteItems.forEach(item => {
                const title = item.getAttribute('data-title');
                const subject = item.getAttribute('data-subject');
                
                if (title.includes(searchTerm) || subject.includes(searchTerm)) {
                    item.style.display = 'block';
                    setTimeout(() => {
                        item.style.opacity = '1';
                        item.style.transform = 'translateY(0)';
                    }, 100);
                } else {
                    item.style.opacity = '0';
                    item.style.transform = 'translateY(20px)';
                    setTimeout(() => {
                        item.style.display = 'none';
                    }, 300);
                }
            });
        });

        // Explore Button Scroll
        function scrollToSearch() {
            document.getElementById('search').scrollIntoView({ 
                behavior: 'smooth' 
            });
        }

        // Responsive behavior
        window.addEventListener('resize', function() {
            if (window.innerWidth > 968) {
                closeMobileNav();
            }
        });
    </script>
</body>
</html>