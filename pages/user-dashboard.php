<?php
session_start();
require_once '../config/database.php';

// Demo session data
$_SESSION['user_id'] = 1;
$_SESSION['user_email'] = 'john@example.com';

// Demo user data
$user_data = [
    'id' => 1,
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'avatar' => '👨‍🎓',
    'join_date' => '2024-01-15',
    'bio' => 'Computer Science Student passionate about sharing knowledge and helping others learn.',
    'phone' => '+1 (555) 123-4567',
    'location' => 'New York, USA',
    'balance' => 45.50
];

// Demo dashboard stats (Seller + Buyer)
$dashboard_stats = [
    // Seller Stats
    'total_notes' => 15,
    'total_sales' => 47,
    'total_earnings' => 625.50,
    'this_month_earnings' => 125.25,
    'total_views' => 1247,
    'avg_rating' => 4.7,
    
    // Buyer Stats
    'purchased_notes' => 8,
    'total_spent' => 89.99,
    'wishlist_items' => 3,
    'downloads' => 12
];

// Demo user notes (Seller)
$user_notes = [
    [
        'id' => 1,
        'title' => 'Advanced Algorithms & Data Structures',
        'subject' => 'Computer Science',
        'price' => 12.99,
        'sales' => 23,
        'rating' => 4.8,
        'status' => 'published',
        'upload_date' => '2024-02-15',
        'thumbnail' => '📊',
        'description' => 'Complete guide to algorithms and data structures with code examples',
        'pages' => 45,
        'file_size' => '2.4 MB'
    ],
    [
        'id' => 2,
        'title' => 'Data Structures Master Guide',
        'subject' => 'Computer Science',
        'price' => 9.99,
        'sales' => 18,
        'rating' => 4.6,
        'status' => 'published',
        'upload_date' => '2024-02-10',
        'thumbnail' => '🔢',
        'description' => 'Comprehensive data structures tutorial with implementations',
        'pages' => 32,
        'file_size' => '1.8 MB'
    ]
];

// Demo purchased notes (Buyer)
$purchased_notes = [
    [
        'id' => 101,
        'title' => 'Machine Learning Fundamentals',
        'seller' => 'AI Expert',
        'price' => 14.99,
        'purchase_date' => '2024-02-28',
        'downloads_left' => 3,
        'thumbnail' => '🤖',
        'subject' => 'AI & ML',
        'rating' => 4.9,
        'file_size' => '3.1 MB'
    ],
    [
        'id' => 102,
        'title' => 'Web Development Bootcamp',
        'seller' => 'Web Dev Pro',
        'price' => 11.99,
        'purchase_date' => '2024-02-25',
        'downloads_left' => 2,
        'thumbnail' => '🌐',
        'subject' => 'Web Development',
        'rating' => 4.7,
        'file_size' => '4.2 MB'
    ],
    [
        'id' => 103,
        'title' => 'Database Management Systems',
        'seller' => 'DB Master',
        'price' => 10.99,
        'purchase_date' => '2024-02-20',
        'downloads_left' => 5,
        'thumbnail' => '🗄️',
        'subject' => 'Database',
        'rating' => 4.5,
        'file_size' => '2.7 MB'
    ]
];

// Demo recent activity (Mixed - Sales + Purchases)
$recent_activity = [
    ['type' => 'sale', 'note' => 'Advanced Algorithms', 'user' => 'Alice Johnson', 'amount' => 12.99, 'date' => '2024-02-28', 'time' => '14:30'],
    ['type' => 'purchase', 'note' => 'Machine Learning', 'user' => 'AI Expert', 'amount' => -14.99, 'date' => '2024-02-28', 'time' => '11:15'],
    ['type' => 'sale', 'note' => 'Data Structures Guide', 'user' => 'Bob Smith', 'amount' => 9.99, 'date' => '2024-02-27', 'time' => '16:45'],
    ['type' => 'sale', 'note' => 'Web Development', 'user' => 'Carol Davis', 'amount' => 11.99, 'date' => '2024-02-26', 'time' => '09:20'],
    ['type' => 'purchase', 'note' => 'Database Systems', 'user' => 'DB Master', 'amount' => -10.99, 'date' => '2024-02-25', 'time' => '13:10']
];

// Demo payment history
$payment_history = [
    ['type' => 'payout', 'amount' => 45.50, 'status' => 'completed', 'date' => '2024-02-25', 'description' => 'Monthly earnings payout'],
    ['type' => 'purchase', 'amount' => -14.99, 'status' => 'completed', 'date' => '2024-02-28', 'description' => 'Machine Learning Fundamentals'],
    ['type' => 'sale', 'amount' => 12.99, 'status' => 'pending', 'date' => '2024-02-28', 'description' => 'Advanced Algorithms sale'],
    ['type' => 'purchase', 'amount' => -11.99, 'status' => 'completed', 'date' => '2024-02-25', 'description' => 'Web Development Bootcamp']
];

// Handle actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $user_data['name'] = $_POST['name'];
    $user_data['email'] = $_POST['email'];
    $user_data['bio'] = $_POST['bio'];
    $user_data['phone'] = $_POST['phone'];
    $user_data['location'] = $_POST['location'];
    $update_success = true;
}

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'download_note':
            $note_id = $_GET['id'];
            // Demo download - reduce downloads left
            foreach ($purchased_notes as &$note) {
                if ($note['id'] == $note_id && $note['downloads_left'] > 0) {
                    $note['downloads_left']--;
                    break;
                }
            }
            break;
        case 'delete_note':
            $note_id = $_GET['id'];
            $user_notes = array_filter($user_notes, function($note) use ($note_id) {
                return $note['id'] != $note_id;
            });
            break;
        case 'toggle_status':
            $note_id = $_GET['id'];
            foreach ($user_notes as &$note) {
                if ($note['id'] == $note_id) {
                    $note['status'] = $note['status'] === 'published' ? 'draft' : 'published';
                    break;
                }
            }
            break;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - NoteShare</title>
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
            min-height: 100vh;
        }

        .glass {
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            border: 1px solid var(--glass-border);
            border-radius: 15px;
        }

        /* Header */
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
        }

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
        }

        .nav-links a:hover {
            color: var(--primary-purple);
        }

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
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(45deg, var(--primary-purple), var(--primary-green));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
        }

        /* Dashboard Layout */
        .dashboard-container {
            padding: 100px 5% 2rem;
            max-width: 1400px;
            margin: 0 auto;
        }

        /* Hero Section */
        .dashboard-hero {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 3rem;
            padding: 2rem;
            position: relative;
            overflow: hidden;
        }

        .hero-content h1 {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
            background: linear-gradient(45deg, var(--primary-purple), var(--primary-green));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-content p {
            color: #ccc;
            font-size: 1.1rem;
        }

        .user-stats {
            display: flex;
            gap: 2rem;
            margin-top: 1rem;
        }

        .user-stat {
            text-align: center;
        }

        .stat-value {
            font-size: 1.2rem;
            font-weight: bold;
            color: var(--primary-purple);
        }

        .stat-label {
            font-size: 0.9rem;
            color: #ccc;
        }

        .edit-profile-btn {
            background: linear-gradient(45deg, var(--primary-purple), var(--primary-green));
            color: white;
            border: none;
            padding: 0.8rem 1.5rem;
            border-radius: 25px;
            cursor: pointer;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .edit-profile-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(107, 70, 193, 0.4);
        }

        /* Stats Grid - Combined Seller/Buyer */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 3rem;
        }

        .stat-card {
            padding: 1.5rem;
            text-align: center;
            transition: all 0.3s ease;
            border: 2px solid transparent;
            position: relative;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            border-color: var(--primary-purple);
        }

        .stat-badge {
            position: absolute;
            top: 0.5rem;
            right: 0.5rem;
            padding: 0.2rem 0.5rem;
            border-radius: 10px;
            font-size: 0.7rem;
            font-weight: bold;
        }

        .badge-seller {
            background: var(--primary-purple);
        }

        .badge-buyer {
            background: var(--primary-green);
        }

        .stat-icon {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            background: linear-gradient(45deg, var(--primary-purple), var(--primary-green));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            display: block;
        }

        .stat-label {
            color: #ccc;
            font-size: 0.9rem;
        }

        /* Tabs Navigation */
        .tabs-navigation {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
            border-bottom: 1px solid var(--glass-border);
            padding-bottom: 1rem;
        }

        .tab-btn {
            background: transparent;
            border: none;
            color: #ccc;
            padding: 0.8rem 1.5rem;
            border-radius: 25px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 1rem;
        }

        .tab-btn.active {
            background: var(--primary-purple);
            color: white;
        }

        .tab-btn:hover:not(.active) {
            background: var(--glass-bg);
            color: white;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        /* Two Column Layout */
        .dashboard-content {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 2rem;
            margin-bottom: 3rem;
        }

        @media (max-width: 968px) {
            .dashboard-content {
                grid-template-columns: 1fr;
            }
        }

        /* Notes Grid Common */
        .section-title {
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .section-title a {
            background: var(--primary-purple);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 10px;
            text-decoration: none;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .section-title a:hover {
            background: var(--dark-purple);
            transform: translateY(-2px);
        }

        .notes-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .note-card {
            padding: 1.5rem;
            transition: all 0.3s ease;
            border: 2px solid transparent;
            position: relative;
        }

        .note-card:hover {
            transform: translateY(-5px);
            border-color: var(--primary-purple);
            box-shadow: 0 15px 30px rgba(0,0,0,0.3);
        }

        .note-status {
            position: absolute;
            top: 1rem;
            right: 1rem;
            padding: 0.3rem 0.8rem;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: bold;
        }

        .status-published {
            background: var(--primary-green);
            color: white;
        }

        .status-draft {
            background: #666;
            color: white;
        }

        .status-purchased {
            background: var(--primary-purple);
            color: white;
        }

        .note-header {
            display: flex;
            align-items: flex-start;
            margin-bottom: 1rem;
        }

        .note-thumbnail {
            width: 60px;
            height: 80px;
            background: linear-gradient(45deg, var(--primary-purple), var(--primary-green));
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            margin-right: 1rem;
        }

        .note-info {
            flex: 1;
        }

        .note-title {
            font-size: 1.2rem;
            font-weight: bold;
            margin-bottom: 0.3rem;
        }

        .note-subject {
            color: var(--primary-purple);
            font-weight: bold;
            margin-bottom: 0.3rem;
        }

        .note-seller {
            color: #ccc;
            font-size: 0.9rem;
            margin-bottom: 0.3rem;
        }

        .note-price {
            font-size: 1.3rem;
            font-weight: bold;
            color: var(--primary-green);
        }

        .note-description {
            color: #ccc;
            font-size: 0.9rem;
            margin-bottom: 1rem;
            line-height: 1.4;
        }

        .note-meta {
            display: flex;
            justify-content: space-between;
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid var(--glass-border);
        }

        .note-sales, .note-rating, .note-downloads {
            color: #ccc;
            font-size: 0.9rem;
        }

        .note-actions {
            display: flex;
            gap: 0.5rem;
            margin-top: 1rem;
            flex-wrap: wrap;
        }

        .action-btn {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.8rem;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-edit {
            background: var(--primary-purple);
            color: white;
        }

        .btn-delete {
            background: transparent;
            border: 1px solid #ff4444;
            color: #ff4444;
        }

        .btn-preview {
            background: transparent;
            border: 1px solid var(--primary-green);
            color: var(--primary-green);
        }

        .btn-status {
            background: transparent;
            border: 1px solid #ffa500;
            color: #ffa500;
        }

        .btn-download {
            background: var(--primary-green);
            color: white;
        }

        .btn-view {
            background: transparent;
            border: 1px solid var(--primary-purple);
            color: var(--primary-purple);
        }

        .action-btn:hover {
            transform: translateY(-2px);
        }

        /* Activity List */
        .activity-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .activity-item {
            padding: 1rem;
            background: rgba(0,0,0,0.3);
            border-radius: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-left: 4px solid transparent;
        }

        .activity-sale {
            border-left-color: var(--primary-green);
        }

        .activity-purchase {
            border-left-color: var(--primary-purple);
        }

        .activity-info h4 {
            margin-bottom: 0.3rem;
        }

        .activity-amount {
            font-weight: bold;
        }

        .amount-positive {
            color: var(--primary-green);
        }

        .amount-negative {
            color: #ff4444;
        }

        /* Payment Section */
        .balance-card {
            padding: 1.5rem;
            text-align: center;
            margin-bottom: 2rem;
        }

        .balance-amount {
            font-size: 2.5rem;
            font-weight: bold;
            color: var(--primary-green);
            margin: 1rem 0;
        }

        .withdraw-btn {
            background: var(--primary-purple);
            color: white;
            border: none;
            padding: 0.8rem 1.5rem;
            border-radius: 25px;
            cursor: pointer;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .withdraw-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(107, 70, 193, 0.4);
        }

        /* Profile Edit Modal */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(5px);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 2000;
        }

        .modal-overlay.active {
            display: flex;
        }

        .profile-modal {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            padding: 2.5rem;
            width: 90%;
            max-width: 500px;
            transform: scale(0.8);
            opacity: 0;
            transition: all 0.3s ease;
            max-height: 90vh;
            overflow-y: auto;
        }

        .modal-overlay.active .profile-modal {
            transform: scale(1);
            opacity: 1;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .modal-title {
            font-size: 1.5rem;
            color: white;
        }

        .close-modal {
            background: none;
            border: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
            padding: 0.5rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            color: #ccc;
        }

        .form-input {
            width: 100%;
            padding: 1rem;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid var(--glass-border);
            border-radius: 10px;
            color: white;
            font-size: 1rem;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary-purple);
        }

        .modal-actions {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }

        .btn-save {
            background: var(--primary-purple);
            color: white;
            border: none;
            padding: 1rem 2rem;
            border-radius: 10px;
            cursor: pointer;
            flex: 1;
        }

        .btn-cancel {
            background: transparent;
            border: 1px solid var(--glass-border);
            color: white;
            padding: 1rem 2rem;
            border-radius: 10px;
            cursor: pointer;
            flex: 1;
        }

        /* Success Message */
        .success-message {
            background: rgba(45, 80, 22, 0.3);
            border: 1px solid var(--primary-green);
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1rem;
            text-align: center;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .dashboard-hero {
                flex-direction: column;
                text-align: center;
                gap: 1rem;
            }

            .user-stats {
                justify-content: center;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .notes-grid {
                grid-template-columns: 1fr;
            }

            .tabs-navigation {
                flex-wrap: wrap;
            }

            .nav-links {
                display: none;
            }
        }

        @media (max-width: 480px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .note-actions {
                flex-direction: column;
            }
            
            .action-btn {
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="glass">
        <div class="logo">NoteShare</div>
        <nav class="nav-links">
            <a href="../index.php">Home</a>
            <a href="browse_notes.php">Browse Notes</a>
            <a href="upload_note.php">Upload Notes</a>
            <div class="user-menu">
                <div class="user-avatar"><?php echo $user_data['avatar']; ?></div>
                <span><?php echo explode(' ', $user_data['name'])[0]; ?></span>
            </div>
        </nav>
    </header>

    <!-- Dashboard Container -->
    <div class="dashboard-container">
        <!-- Success Message -->
        <?php if (isset($update_success) && $update_success): ?>
            <div class="success-message glass">
                ✅ Profile updated successfully!
            </div>
        <?php endif; ?>

        <!-- Hero Section -->
        <section class="dashboard-hero glass">
            <div class="hero-content">
                <h1>Welcome back, <?php echo explode(' ', $user_data['name'])[0]; ?>! 👋</h1>
                <p><?php echo $user_data['bio']; ?></p>
                <div class="user-stats">
                    <div class="user-stat">
                        <div class="stat-value"><?php echo $user_data['join_date']; ?></div>
                        <div class="stat-label">Member Since</div>
                    </div>
                    <div class="user-stat">
                        <div class="stat-value"><?php echo $dashboard_stats['avg_rating']; ?>/5</div>
                        <div class="stat-label">Seller Rating</div>
                    </div>
                    <div class="user-stat">
                        <div class="stat-value"><?php echo $dashboard_stats['purchased_notes']; ?></div>
                        <div class="stat-label">Notes Bought</div>
                    </div>
                </div>
            </div>
            <button class="edit-profile-btn" onclick="openProfileModal()">Edit Profile</button>
        </section>

        <!-- Combined Stats Grid -->
        <section class="stats-grid">
            <!-- Seller Stats -->
            <div class="stat-card glass">
                <div class="stat-badge badge-seller">SELLER</div>
                <div class="stat-icon">📚</div>
                <span class="stat-number"><?php echo $dashboard_stats['total_notes']; ?></span>
                <div class="stat-label">Notes Uploaded</div>
            </div>
            <div class="stat-card glass">
                <div class="stat-badge badge-seller">SELLER</div>
                <div class="stat-icon">💰</div>
                <span class="stat-number"><?php echo $dashboard_stats['total_sales']; ?></span>
                <div class="stat-label">Total Sales</div>
            </div>
            <div class="stat-card glass">
                <div class="stat-badge badge-seller">SELLER</div>
                <div class="stat-icon">💵</div>
                <span class="stat-number">$<?php echo $dashboard_stats['total_earnings']; ?></span>
                <div class="stat-label">Total Earnings</div>
            </div>
            
            <!-- Buyer Stats -->
            <div class="stat-card glass">
                <div class="stat-badge badge-buyer">BUYER</div>
                <div class="stat-icon">🛒</div>
                <span class="stat-number"><?php echo $dashboard_stats['purchased_notes']; ?></span>
                <div class="stat-label">Notes Purchased</div>
            </div>
            <div class="stat-card glass">
                <div class="stat-badge badge-buyer">BUYER</div>
                <div class="stat-icon">💳</div>
                <span class="stat-number">$<?php echo $dashboard_stats['total_spent']; ?></span>
                <div class="stat-label">Total Spent</div>
            </div>
            <div class="stat-card glass">
                <div class="stat-badge badge-buyer">BUYER</div>
                <div class="stat-icon">⭐</div>
                <span class="stat-number"><?php echo $dashboard_stats['wishlist_items']; ?></span>
                <div class="stat-label">Wishlist Items</div>
            </div>
        </section>

        <!-- Tabs Navigation -->
        <div class="tabs-navigation">
            <button class="tab-btn active" onclick="switchTab('overview')">📊 Overview</button>
            <button class="tab-btn" onclick="switchTab('my-notes')">📝 My Notes</button>
            <button class="tab-btn" onclick="switchTab('purchased')">🛒 Purchased</button>
            <button class="tab-btn" onclick="switchTab('payments')">💰 Payments</button>
        </div>

        <!-- Overview Tab -->
        <div id="overview" class="tab-content active">
            <div class="dashboard-content">
                <!-- Left Column -->
                <div class="left-column">
                    <!-- Recent Activity -->
                    <section class="recent-activity glass" style="padding: 1.5rem; margin-bottom: 2rem;">
                        <h3 class="section-title">📈 Recent Activity</h3>
                        <div class="activity-list">
                            <?php foreach ($recent_activity as $activity): ?>
                            <div class="activity-item <?php echo 'activity-' . $activity['type']; ?>">
                                <div class="activity-info">
                                    <h4>
                                        <?php if ($activity['type'] === 'sale'): ?>
                                            🎉 Sold "<?php echo $activity['note']; ?>"
                                        <?php else: ?>
                                            🛒 Purchased "<?php echo $activity['note']; ?>"
                                        <?php endif; ?>
                                    </h4>
                                    <small>
                                        <?php echo $activity['type'] === 'sale' ? 'To' : 'From'; ?> 
                                        <?php echo $activity['user']; ?> • 
                                        <?php echo $activity['date']; ?> at <?php echo $activity['time']; ?>
                                    </small>
                                </div>
                                <div class="activity-amount <?php echo $activity['amount'] > 0 ? 'amount-positive' : 'amount-negative'; ?>">
                                    <?php echo $activity['amount'] > 0 ? '+' : ''; ?>$<?php echo abs($activity['amount']); ?>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </section>

                    <!-- Quick Actions -->
                    <section class="quick-actions glass" style="padding: 1.5rem;">
                        <h3 class="section-title">⚡ Quick Actions</h3>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                            <button class="upload-btn" onclick="location.href='upload_note.php'" style="margin: 0;">📤 Upload Note</button>
                            <button class="upload-btn" onclick="location.href='browse_notes.php'" style="margin: 0; background: var(--primary-purple);">🔍 Browse Notes</button>
                        </div>
                    </section>
                </div>

                <!-- Right Column -->
                <div class="right-column">
                    <!-- Balance Card -->
                    <section class="balance-card glass">
                        <h3>💰 Available Balance</h3>
                        <div class="balance-amount">$<?php echo $user_data['balance']; ?></div>
                        <button class="withdraw-btn">Withdraw Funds</button>
                        <p style="margin-top: 1rem; font-size: 0.9rem; color: #ccc;">
                            Next payout: March 1, 2024
                        </p>
                    </section>

                    <!-- Performance -->
                    <section class="performance glass" style="padding: 1.5rem;">
                        <h3 class="section-title">📊 Performance</h3>
                        <div style="display: flex; flex-direction: column; gap: 1rem;">
                            <div>
                                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                                    <span>Seller Rating</span>
                                    <span><?php echo $dashboard_stats['avg_rating']; ?>/5 ⭐</span>
                                </div>
                            </div>
                            <div>
                                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                                    <span>Notes Downloaded</span>
                                    <span><?php echo $dashboard_stats['downloads']; ?></span>
                                </div>
                            </div>
                            <div>
                                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                                    <span>Total Views</span>
                                    <span><?php echo $dashboard_stats['total_views']; ?></span>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>

        <!-- My Notes Tab -->
        <div id="my-notes" class="tab-content">
            <section class="notes-section">
                <div class="section-title">
                    <h2>📝 My Notes (<?php echo count($user_notes); ?>)</h2>
                    <a href="upload_note.php">+ Upload New Note</a>
                </div>
                <div class="notes-grid">
                    <?php foreach ($user_notes as $note): ?>
                    <div class="note-card glass">
                        <div class="note-status <?php echo 'status-' . $note['status']; ?>">
                            <?php echo ucfirst($note['status']); ?>
                        </div>
                        <div class="note-header">
                            <div class="note-thumbnail">
                                <?php echo $note['thumbnail']; ?>
                            </div>
                            <div class="note-info">
                                <div class="note-title"><?php echo $note['title']; ?></div>
                                <div class="note-subject"><?php echo $note['subject']; ?></div>
                                <div class="note-price">$<?php echo $note['price']; ?></div>
                            </div>
                        </div>
                        <div class="note-description">
                            <?php echo $note['description']; ?>
                        </div>
                        <div class="note-meta">
                            <div class="note-sales"><?php echo $note['sales']; ?> sales</div>
                            <div class="note-rating">⭐ <?php echo $note['rating']; ?>/5</div>
                        </div>
                        <div class="note-details">
                            <small>Pages: <?php echo $note['pages']; ?> • Size: <?php echo $note['file_size']; ?></small>
                        </div>
                        <div class="note-actions">
                            <a href="?action=toggle_status&id=<?php echo $note['id']; ?>" class="action-btn btn-status">
                                <?php echo $note['status'] === 'published' ? 'Unpublish' : 'Publish'; ?>
                            </a>
                            <a href="#" class="action-btn btn-preview">Preview</a>
                            <a href="#" class="action-btn btn-edit">Edit</a>
                            <a href="?action=delete_note&id=<?php echo $note['id']; ?>" class="action-btn btn-delete" onclick="return confirm('Are you sure?')">Delete</a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </section>
        </div>

        <!-- Purchased Notes Tab -->
        <div id="purchased" class="tab-content">
            <section class="notes-section">
                <div class="section-title">
                    <h2>🛒 Purchased Notes (<?php echo count($purchased_notes); ?>)</h2>
                    <a href="browse_notes.php">Browse More Notes</a>
                </div>
                <div class="notes-grid">
                    <?php foreach ($purchased_notes as $note): ?>
                    <div class="note-card glass">
                        <div class="note-status status-purchased">
                            Purchased
                        </div>
                        <div class="note-header">
                            <div class="note-thumbnail">
                                <?php echo $note['thumbnail']; ?>
                            </div>
                            <div class="note-info">
                                <div class="note-title"><?php echo $note['title']; ?></div>
                                <div class="note-seller">By <?php echo $note['seller']; ?></div>
                                <div class="note-subject"><?php echo $note['subject']; ?></div>
                                <div class="note-price">$<?php echo $note['price']; ?></div>
                            </div>
                        </div>
                        <div class="note-meta">
                            <div class="note-downloads">Downloads left: <?php echo $note['downloads_left']; ?></div>
                            <div class="note-rating">⭐ <?php echo $note['rating']; ?>/5</div>
                        </div>
                        <div class="note-details">
                            <small>Purchased: <?php echo $note['purchase_date']; ?> • Size: <?php echo $note['file_size']; ?></small>
                        </div>
                        <div class="note-actions">
                            <a href="?action=download_note&id=<?php echo $note['id']; ?>" class="action-btn btn-download">
                                📥 Download (<?php echo $note['downloads_left']; ?> left)
                            </a>
                            <a href="#" class="action-btn btn-view">Preview</a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </section>
        </div>

        <!-- Payments Tab -->
        <div id="payments" class="tab-content">
            <div class="dashboard-content">
                <div class="left-column">
                    <section class="payment-history glass" style="padding: 1.5rem;">
                        <h3 class="section-title">💰 Payment History</h3>
                        <div class="activity-list">
                            <?php foreach ($payment_history as $payment): ?>
                            <div class="activity-item <?php echo $payment['amount'] > 0 ? 'activity-sale' : 'activity-purchase'; ?>">
                                <div class="activity-info">
                                    <h4><?php echo $payment['description']; ?></h4>
                                    <small>
                                        <?php echo $payment['date']; ?> • 
                                        <span style="color: <?php echo $payment['status'] === 'completed' ? 'var(--primary-green)' : '#ffa500'; ?>">
                                            <?php echo ucfirst($payment['status']); ?>
                                        </span>
                                    </small>
                                </div>
                                <div class="activity-amount <?php echo $payment['amount'] > 0 ? 'amount-positive' : 'amount-negative'; ?>">
                                    <?php echo $payment['amount'] > 0 ? '+' : ''; ?>$<?php echo abs($payment['amount']); ?>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </section>
                </div>
                <div class="right-column">
                    <section class="payment-settings glass" style="padding: 1.5rem;">
                        <h3 class="section-title">⚙️ Payment Settings</h3>
                        <div style="display: flex; flex-direction: column; gap: 1rem;">
                            <div>
                                <strong>Payout Method</strong>
                                <p style="color: #ccc; margin-top: 0.5rem;">Stripe Connected ✅</p>
                            </div>
                            <div>
                                <strong>Commission Rate</strong>
                                <p style="color: #ccc; margin-top: 0.5rem;">15% platform fee</p>
                            </div>
                            <div>
                                <strong>Next Payout</strong>
                                <p style="color: #ccc; margin-top: 0.5rem;">March 1, 2024</p>
                            </div>
                            <button class="withdraw-btn" style="width: 100%;">Manage Payment Methods</button>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>

    <!-- Profile Edit Modal -->
    <div class="modal-overlay" id="profileModal">
        <div class="profile-modal glass">
            <div class="modal-header">
                <h3 class="modal-title">Edit Profile</h3>
                <button class="close-modal" onclick="closeProfileModal()">×</button>
            </div>
            <form method="POST" action="">
                <div class="form-group">
                    <label class="form-label">Full Name</label>
                    <input type="text" class="form-input" name="name" value="<?php echo $user_data['name']; ?>" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <input type="email" class="form-input" name="email" value="<?php echo $user_data['email']; ?>" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Phone Number</label>
                    <input type="tel" class="form-input" name="phone" value="<?php echo $user_data['phone']; ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Location</label>
                    <input type="text" class="form-input" name="location" value="<?php echo $user_data['location']; ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Bio</label>
                    <textarea class="form-input" name="bio" rows="4" placeholder="Tell us about yourself..."><?php echo $user_data['bio']; ?></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Profile Picture</label>
                    <input type="file" class="form-input" accept="image/*">
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn-cancel" onclick="closeProfileModal()">Cancel</button>
                    <button type="submit" name="update_profile" class="btn-save">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Tab Switching
        function switchTab(tabName) {
            // Hide all tabs
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.remove('active');
            });
            
            // Remove active class from all buttons
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            
            // Show selected tab
            document.getElementById(tabName).classList.add('active');
            
            // Activate selected button
            event.target.classList.add('active');
        }

        // Profile Modal Functions
        function openProfileModal() {
            document.getElementById('profileModal').classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeProfileModal() {
            document.getElementById('profileModal').classList.remove('active');
            document.body.style.overflow = '';
        }

        // Close modal when clicking outside
        document.getElementById('profileModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeProfileModal();
            }
        });

        // Add animations
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.stat-card, .note-card');
            cards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.1}s`;
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                
                setTimeout(() => {
                    card.style.transition = 'all 0.6s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });

        // Auto-hide success message
        <?php if (isset($update_success) && $update_success): ?>
        setTimeout(() => {
            const successMsg = document.querySelector('.success-message');
            if (successMsg) {
                successMsg.style.transition = 'opacity 0.5s ease';
                successMsg.style.opacity = '0';
                setTimeout(() => successMsg.remove(), 500);
            }
        }, 5000);
        <?php endif; ?>
    </script>
</body>
</html>