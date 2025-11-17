<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
if (isset($_SESSION['user_id']) && isset($_SESSION['user_name']) && isset($_SESSION['user_email'])) {
    $current_user = [
        'name' => $_SESSION['user_name'],
        'email' => $_SESSION['user_email'],
        'avatar' => '👤' 
    ];
    
    $user_id = $_SESSION['user_id'];
    $is_logged_in = true;
} 
// else {
//     $current_user = [
//         'name' => 'Guest User',
//         'email' => 'guest@example.com',
//         'avatar' => '👤'
//     ];
//     $is_logged_in = false;
// }

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
    <link rel="stylesheet" href="homestyle.css">
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
                    <a href="logout.php" class="logout-btn" onclick="return confirm('Are you sure you want to logout?')">🚪 Logout</a>                </div>
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
            <a href="logout.php" class="logout-btn" onclick="return confirm('Are you sure you want to logout?')">🚪 Logout</a>        </div>
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
    <!-- <script>
        //logout script
        function logout() {
            
            window.location.href = 'login.php';            
        }
    </script> -->
</body>
</html>



