<?php
session_start();
require_once '../../config/database.php';

// Check if user is admin (demo - replace with actual admin check)
if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] != 1) {
    header('Location: ../login.php');
    exit();
}

// Demo admin data
$admin_data = [
    'name' => 'Admin User',
    'email' => 'admin@noteshare.com',
    'avatar' => '👑'
];

// Demo admin stats
$admin_stats = [
    'total_users' => 12547,
    'total_notes' => 8563,
    'total_sales' => 32478,
    'total_revenue' => 156234.89,
    'total_commission' => 23435.23,
    'pending_notes' => 23,
    'reported_notes' => 7,
    'active_ads' => 5
];

// Demo users data
$users = [
    [
        'id' => 1,
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'join_date' => '2024-01-15',
        'status' => 'active',
        'notes_count' => 15,
        'sales_count' => 47,
        'earnings' => 625.50,
        'last_login' => '2024-02-28 14:30'
    ],
    [
        'id' => 2,
        'name' => 'Alice Johnson',
        'email' => 'alice@example.com',
        'join_date' => '2024-01-20',
        'status' => 'active',
        'notes_count' => 8,
        'sales_count' => 23,
        'earnings' => 289.75,
        'last_login' => '2024-02-28 10:15'
    ],
    [
        'id' => 3,
        'name' => 'Bob Smith',
        'email' => 'bob@example.com',
        'join_date' => '2024-02-01',
        'status' => 'suspended',
        'notes_count' => 3,
        'sales_count' => 0,
        'earnings' => 0.00,
        'last_login' => '2024-02-25 16:45'
    ],
    [
        'id' => 4,
        'name' => 'Carol Davis',
        'email' => 'carol@example.com',
        'join_date' => '2024-01-28',
        'status' => 'active',
        'notes_count' => 12,
        'sales_count' => 34,
        'earnings' => 412.30,
        'last_login' => '2024-02-28 09:20'
    ]
];

// Demo notes for review
$pending_notes = [
    [
        'id' => 101,
        'title' => 'Advanced Physics Concepts',
        'user' => 'Mike Wilson',
        'subject' => 'Physics',
        'price' => 14.99,
        'upload_date' => '2024-02-28',
        'file_size' => '3.2 MB',
        'status' => 'pending'
    ],
    [
        'id' => 102,
        'title' => 'Chemistry Lab Reports',
        'user' => 'Sarah Brown',
        'subject' => 'Chemistry',
        'price' => 9.99,
        'upload_date' => '2024-02-27',
        'file_size' => '2.1 MB',
        'status' => 'pending'
    ]
];

// Demo reported notes
$reported_notes = [
    [
        'id' => 201,
        'title' => 'Mathematics Basics',
        'user' => 'Tom Harris',
        'reporter' => 'User123',
        'reason' => 'Plagiarized content',
        'report_date' => '2024-02-28',
        'status' => 'under_review'
    ],
    [
        'id' => 202,
        'title' => 'Biology Notes',
        'user' => 'Lisa Green',
        'reporter' => 'User456',
        'reason' => 'Inappropriate content',
        'report_date' => '2024-02-27',
        'status' => 'under_review'
    ]
];

// Demo advertisements
$advertisements = [
    [
        'id' => 1,
        'title' => 'Featured Computer Science Notes',
        'type' => 'featured',
        'notes' => [1, 2, 3],
        'status' => 'active',
        'start_date' => '2024-02-01',
        'end_date' => '2024-03-01',
        'budget' => 500.00,
        'clicks' => 1247,
        'impressions' => 15689
    ],
    [
        'id' => 2,
        'title' => 'New User Promotion',
        'type' => 'banner',
        'notes' => [],
        'status' => 'active',
        'start_date' => '2024-02-15',
        'end_date' => '2024-03-15',
        'budget' => 300.00,
        'clicks' => 856,
        'impressions' => 23456
    ]
];

// Demo commission data
$commission_data = [
    'total_commission' => 23435.23,
    'this_month' => 2456.78,
    'last_month' => 2189.45,
    'commission_rate' => 15,
    'pending_payouts' => 1245.67
];

// Handle admin actions
if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'approve_note':
            $note_id = $_GET['id'];
            // Demo approval
            break;
        case 'reject_note':
            $note_id = $_GET['id'];
            // Demo rejection
            break;
        case 'suspend_user':
            $user_id = $_GET['id'];
            // Demo user suspension
            break;
        case 'activate_user':
            $user_id = $_GET['id'];
            // Demo user activation
            break;
        case 'delete_ad':
            $ad_id = $_GET['id'];
            // Demo ad deletion
            break;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_commission'])) {
        $commission_data['commission_rate'] = $_POST['commission_rate'];
    }
    if (isset($_POST['create_ad'])) {
        // Demo ad creation
        $new_ad = [
            'id' => count($advertisements) + 1,
            'title' => $_POST['ad_title'],
            'type' => $_POST['ad_type'],
            'status' => 'active',
            'start_date' => $_POST['start_date'],
            'end_date' => $_POST['end_date'],
            'budget' => $_POST['budget'],
            'clicks' => 0,
            'impressions' => 0
        ];
        $advertisements[] = $new_ad;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - NoteShare</title>
    <style>
        :root {
            --primary-black: #0a0a0a;
            --primary-purple: #6b46c1;
            --primary-green: #2d5016;
            --dark-purple: #4c1d95;
            --dark-green: #1a3410;
            --glass-bg: rgba(255, 255, 255, 0.1);
            --glass-border: rgba(255, 255, 255, 0.2);
            --admin-red: #dc2626;
            --admin-orange: #ea580c;
            --admin-blue: #2563eb;
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
        .admin-header {
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

        .admin-nav {
            display: flex;
            gap: 2rem;
            align-items: center;
        }

        .admin-nav a {
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
            position: relative;
        }

        .admin-nav a:hover {
            color: var(--primary-purple);
        }

        .admin-user {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 25px;
            background: var(--glass-bg);
        }

        .admin-avatar {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: linear-gradient(45deg, var(--admin-red), var(--primary-purple));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }

        /* Admin Layout */
        .admin-container {
            padding: 100px 5% 2rem;
            max-width: 1800px;
            margin: 0 auto;
        }

        /* Admin Hero */
        .admin-hero {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 3rem;
            padding: 2rem;
        }

        .admin-hero h1 {
            font-size: 2.5rem;
            background: linear-gradient(45deg, var(--admin-red), var(--primary-purple));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .admin-hero p {
            color: #ccc;
            font-size: 1.1rem;
        }

        /* Stats Grid */
        .admin-stats {
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

        .stat-icon {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            display: block;
        }

        .stat-label {
            color: #ccc;
            font-size: 0.9rem;
        }

        /* Tabs Navigation */
        .admin-tabs {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
            border-bottom: 1px solid var(--glass-border);
            padding-bottom: 1rem;
            flex-wrap: wrap;
        }

        .admin-tab {
            background: transparent;
            border: none;
            color: #ccc;
            padding: 0.8rem 1.5rem;
            border-radius: 25px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 1rem;
        }

        .admin-tab.active {
            background: var(--admin-red);
            color: white;
        }

        .admin-tab:hover:not(.active) {
            background: var(--glass-bg);
            color: white;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        /* Tables */
        .admin-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 2rem;
        }

        .admin-table th,
        .admin-table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid var(--glass-border);
        }

        .admin-table th {
            background: rgba(0,0,0,0.3);
            color: var(--primary-purple);
            font-weight: bold;
        }

        .admin-table tr:hover {
            background: rgba(255,255,255,0.05);
        }

        /* Status Badges */
        .status-badge {
            padding: 0.3rem 0.8rem;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: bold;
        }

        .status-active {
            background: var(--primary-green);
            color: white;
        }

        .status-suspended {
            background: var(--admin-red);
            color: white;
        }

        .status-pending {
            background: var(--admin-orange);
            color: white;
        }

        .status-under-review {
            background: var(--admin-blue);
            color: white;
        }

        /* Action Buttons */
        .action-btns {
            display: flex;
            gap: 0.5rem;
        }

        .btn {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.8rem;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-approve {
            background: var(--primary-green);
            color: white;
        }

        .btn-reject {
            background: var(--admin-red);
            color: white;
        }

        .btn-suspend {
            background: var(--admin-orange);
            color: white;
        }

        .btn-activate {
            background: var(--primary-green);
            color: white;
        }

        .btn-delete {
            background: transparent;
            border: 1px solid var(--admin-red);
            color: var(--admin-red);
        }

        .btn-edit {
            background: var(--primary-purple);
            color: white;
        }

        .btn:hover {
            transform: translateY(-2px);
        }

        /* Two Column Layout */
        .admin-content {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 2rem;
            margin-bottom: 3rem;
        }

        @media (max-width: 1200px) {
            .admin-content {
                grid-template-columns: 1fr;
            }
        }

        /* Commission Section */
        .commission-card {
            padding: 1.5rem;
            text-align: center;
            margin-bottom: 2rem;
        }

        .commission-rate {
            font-size: 3rem;
            font-weight: bold;
            color: var(--primary-green);
            margin: 1rem 0;
        }

        /* Forms */
        .admin-form {
            padding: 1.5rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            color: #ccc;
            font-weight: bold;
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

        .form-select {
            width: 100%;
            padding: 1rem;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid var(--glass-border);
            border-radius: 10px;
            color: white;
            font-size: 1rem;
        }

        .form-submit {
            background: var(--primary-purple);
            color: white;
            border: none;
            padding: 1rem 2rem;
            border-radius: 10px;
            cursor: pointer;
            font-size: 1rem;
            transition: all 0.3s ease;
            width: 100%;
        }

        .form-submit:hover {
            background: var(--dark-purple);
            transform: translateY(-2px);
        }

        /* Charts Placeholder */
        .chart-placeholder {
            height: 200px;
            background: rgba(0,0,0,0.3);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ccc;
            margin-bottom: 2rem;
        }

        /* Quick Actions */
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .quick-action-btn {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            color: white;
            padding: 1rem;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
        }

        .quick-action-btn:hover {
            background: var(--primary-purple);
            transform: translateY(-2px);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .admin-hero {
                flex-direction: column;
                text-align: center;
                gap: 1rem;
            }

            .admin-stats {
                grid-template-columns: repeat(2, 1fr);
            }

            .admin-tabs {
                flex-direction: column;
            }

            .admin-table {
                font-size: 0.9rem;
            }

            .action-btns {
                flex-direction: column;
            }
        }

        @media (max-width: 480px) {
            .admin-stats {
                grid-template-columns: 1fr;
            }

            .quick-actions {
                grid-template-columns: 1fr;
            }
        }

        /* Modal */
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

        .admin-modal {
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
        }

        .modal-overlay.active .admin-modal {
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
    </style>
</head>
<body>
    <!-- Admin Header -->
    <header class="admin-header glass">
        <div class="logo">NoteShare Admin</div>
        <nav class="admin-nav">
            <a href="../dashboard.php">User Dashboard</a>
            <a href="../../index.php">View Site</a>
            <div class="admin-user">
                <div class="admin-avatar">👑</div>
                <span><?php echo $admin_data['name']; ?></span>
            </div>
        </nav>
    </header>

    <!-- Admin Container -->
    <div class="admin-container">
        <!-- Admin Hero -->
        <section class="admin-hero glass">
            <div>
                <h1>Admin Dashboard 👑</h1>
                <p>Manage users, notes, advertisements, and platform settings</p>
            </div>
            <div class="quick-actions">
                <button class="quick-action-btn" onclick="switchTab('users')">👥 Users</button>
                <button class="quick-action-btn" onclick="switchTab('notes')">📝 Notes</button>
                <button class="quick-action-btn" onclick="switchTab('ads')">📢 Ads</button>
                <button class="quick-action-btn" onclick="switchTab('commission')">💰 Commission</button>
            </div>
        </section>

        <!-- Admin Stats -->
        <section class="admin-stats">
            <div class="stat-card glass">
                <div class="stat-icon">👥</div>
                <span class="stat-number"><?php echo number_format($admin_stats['total_users']); ?></span>
                <div class="stat-label">Total Users</div>
            </div>
            <div class="stat-card glass">
                <div class="stat-icon">📚</div>
                <span class="stat-number"><?php echo number_format($admin_stats['total_notes']); ?></span>
                <div class="stat-label">Total Notes</div>
            </div>
            <div class="stat-card glass">
                <div class="stat-icon">💰</div>
                <span class="stat-number">$<?php echo number_format($admin_stats['total_revenue'], 2); ?></span>
                <div class="stat-label">Total Revenue</div>
            </div>
            <div class="stat-card glass">
                <div class="stat-icon">💵</div>
                <span class="stat-number">$<?php echo number_format($admin_stats['total_commission'], 2); ?></span>
                <div class="stat-label">Total Commission</div>
            </div>
            <div class="stat-card glass">
                <div class="stat-icon">⏳</div>
                <span class="stat-number"><?php echo $admin_stats['pending_notes']; ?></span>
                <div class="stat-label">Pending Notes</div>
            </div>
            <div class="stat-card glass">
                <div class="stat-icon">🚨</div>
                <span class="stat-number"><?php echo $admin_stats['reported_notes']; ?></span>
                <div class="stat-label">Reported Notes</div>
            </div>
        </section>

        <!-- Tabs Navigation -->
        <div class="admin-tabs">
            <button class="admin-tab active" onclick="switchTab('overview')">📊 Overview</button>
            <button class="admin-tab" onclick="switchTab('users')">👥 User Management</button>
            <button class="admin-tab" onclick="switchTab('notes')">📝 Content Moderation</button>
            <button class="admin-tab" onclick="switchTab('ads')">📢 Advertisement</button>
            <button class="admin-tab" onclick="switchTab('commission')">💰 Commission</button>
            <button class="admin-tab" onclick="switchTab('settings')">⚙️ Settings</button>
        </div>

        <!-- Overview Tab -->
        <div id="overview" class="tab-content active">
            <div class="admin-content">
                <div class="left-column">
                    <!-- Recent Activity -->
                    <section class="glass" style="padding: 1.5rem; margin-bottom: 2rem;">
                        <h3 style="margin-bottom: 1.5rem; color: var(--primary-purple);">Recent Activity</h3>
                        <div class="chart-placeholder">
                            Activity Chart - Last 30 Days
                        </div>
                    </section>

                    <!-- Quick Stats -->
                    <section class="glass" style="padding: 1.5rem;">
                        <h3 style="margin-bottom: 1.5rem; color: var(--primary-purple);">Quick Stats</h3>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                            <div style="text-align: center; padding: 1rem; background: rgba(0,0,0,0.3); border-radius: 10px;">
                                <div style="font-size: 1.5rem; font-weight: bold; color: var(--primary-green);">47</div>
                                <div style="color: #ccc; font-size: 0.9rem;">New Users Today</div>
                            </div>
                            <div style="text-align: center; padding: 1rem; background: rgba(0,0,0,0.3); border-radius: 10px;">
                                <div style="font-size: 1.5rem; font-weight: bold; color: var(--primary-purple);">124</div>
                                <div style="color: #ccc; font-size: 0.9rem;">Notes Uploaded</div>
                            </div>
                            <div style="text-align: center; padding: 1rem; background: rgba(0,0,0,0.3); border-radius: 10px;">
                                <div style="font-size: 1.5rem; font-weight: bold; color: var(--admin-blue);">$2,456</div>
                                <div style="color: #ccc; font-size: 0.9rem;">Revenue Today</div>
                            </div>
                            <div style="text-align: center; padding: 1rem; background: rgba(0,0,0,0.3); border-radius: 10px;">
                                <div style="font-size: 1.5rem; font-weight: bold; color: var(--admin-orange);">89</div>
                                <div style="color: #ccc; font-size: 0.9rem;">Pending Actions</div>
                            </div>
                        </div>
                    </section>
                </div>

                <div class="right-column">
                    <!-- System Alerts -->
                    <section class="glass" style="padding: 1.5rem; margin-bottom: 2rem;">
                        <h3 style="margin-bottom: 1.5rem; color: var(--primary-purple);">System Alerts</h3>
                        <div style="display: flex; flex-direction: column; gap: 1rem;">
                            <div style="padding: 1rem; background: rgba(234, 88, 12, 0.2); border-left: 4px solid var(--admin-orange); border-radius: 5px;">
                                <strong>⚠️ High Server Load</strong>
                                <p style="color: #ccc; margin-top: 0.5rem; font-size: 0.9rem;">CPU usage at 85% - Consider scaling</p>
                            </div>
                            <div style="padding: 1rem; background: rgba(37, 99, 235, 0.2); border-left: 4px solid var(--admin-blue); border-radius: 5px;">
                                <strong>ℹ️ Database Backup</strong>
                                <p style="color: #ccc; margin-top: 0.5rem; font-size: 0.9rem;">Last backup: 2 hours ago</p>
                            </div>
                            <div style="padding: 1rem; background: rgba(220, 38, 38, 0.2); border-left: 4px solid var(--admin-red); border-radius: 5px;">
                                <strong>🚨 Security Scan</strong>
                                <p style="color: #ccc; margin-top: 0.5rem; font-size: 0.9rem;">3 vulnerabilities detected</p>
                            </div>
                        </div>
                    </section>

                    <!-- Recent Reports -->
                    <section class="glass" style="padding: 1.5rem;">
                        <h3 style="margin-bottom: 1.5rem; color: var(--primary-purple);">Recent Reports</h3>
                        <div style="display: flex; flex-direction: column; gap: 1rem;">
                            <?php foreach (array_slice($reported_notes, 0, 3) as $report): ?>
                            <div style="padding: 1rem; background: rgba(0,0,0,0.3); border-radius: 10px;">
                                <strong><?php echo $report['title']; ?></strong>
                                <p style="color: #ccc; margin-top: 0.5rem; font-size: 0.9rem;">
                                    Reported for: <?php echo $report['reason']; ?>
                                </p>
                                <div style="display: flex; gap: 0.5rem; margin-top: 0.5rem;">
                                    <button class="btn btn-approve" style="padding: 0.3rem 0.8rem; font-size: 0.7rem;">Review</button>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </section>
                </div>
            </div>
        </div>

        <!-- User Management Tab -->
        <div id="users" class="tab-content">
            <section class="glass" style="padding: 1.5rem;">
                <h3 style="margin-bottom: 1.5rem; color: var(--primary-purple);">User Management</h3>
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Join Date</th>
                            <th>Notes</th>
                            <th>Sales</th>
                            <th>Earnings</th>
                            <th>Status</th>
                            <th>Last Login</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                        <tr>
                            <td>
                                <strong><?php echo $user['name']; ?></strong>
                                <br><small><?php echo $user['email']; ?></small>
                            </td>
                            <td><?php echo $user['join_date']; ?></td>
                            <td><?php echo $user['notes_count']; ?></td>
                            <td><?php echo $user['sales_count']; ?></td>
                            <td>$<?php echo $user['earnings']; ?></td>
                            <td>
                                <span class="status-badge <?php echo 'status-' . $user['status']; ?>">
                                    <?php echo ucfirst($user['status']); ?>
                                </span>
                            </td>
                            <td><?php echo $user['last_login']; ?></td>
                            <td>
                                <div class="action-btns">
                                    <?php if ($user['status'] === 'active'): ?>
                                        <a href="?action=suspend_user&id=<?php echo $user['id']; ?>" class="btn btn-suspend">Suspend</a>
                                    <?php else: ?>
                                        <a href="?action=activate_user&id=<?php echo $user['id']; ?>" class="btn btn-activate">Activate</a>
                                    <?php endif; ?>
                                    <button class="btn btn-edit">Edit</button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </section>
        </div>

        <!-- Content Moderation Tab -->
        <div id="notes" class="tab-content">
            <div class="admin-content">
                <div class="left-column">
                    <!-- Pending Notes -->
                    <section class="glass" style="padding: 1.5rem; margin-bottom: 2rem;">
                        <h3 style="margin-bottom: 1.5rem; color: var(--primary-purple);">Pending Review (<?php echo count($pending_notes); ?>)</h3>
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>Note Title</th>
                                    <th>User</th>
                                    <th>Subject</th>
                                    <th>Price</th>
                                    <th>Upload Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($pending_notes as $note): ?>
                                <tr>
                                    <td><?php echo $note['title']; ?></td>
                                    <td><?php echo $note['user']; ?></td>
                                    <td><?php echo $note['subject']; ?></td>
                                    <td>$<?php echo $note['price']; ?></td>
                                    <td><?php echo $note['upload_date']; ?></td>
                                    <td>
                                        <div class="action-btns">
                                            <a href="?action=approve_note&id=<?php echo $note['id']; ?>" class="btn btn-approve">Approve</a>
                                            <a href="?action=reject_note&id=<?php echo $note['id']; ?>" class="btn btn-reject">Reject</a>
                                            <button class="btn btn-edit">Preview</button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </section>
                </div>

                <div class="right-column">
                    <!-- Reported Notes -->
                    <section class="glass" style="padding: 1.5rem;">
                        <h3 style="margin-bottom: 1.5rem; color: var(--primary-purple);">Reported Notes</h3>
                        <div style="display: flex; flex-direction: column; gap: 1rem;">
                            <?php foreach ($reported_notes as $report): ?>
                            <div style="padding: 1rem; background: rgba(0,0,0,0.3); border-radius: 10px;">
                                <strong><?php echo $report['title']; ?></strong>
                                <p style="color: #ccc; margin-top: 0.5rem; font-size: 0.9rem;">
                                    By: <?php echo $report['user']; ?><br>
                                    Reported by: <?php echo $report['reporter']; ?><br>
                                    Reason: <?php echo $report['reason']; ?>
                                </p>
                                <div style="display: flex; gap: 0.5rem; margin-top: 0.5rem;">
                                    <button class="btn btn-approve" style="padding: 0.3rem 0.8rem; font-size: 0.7rem;">Approve</button>
                                    <button class="btn btn-reject" style="padding: 0.3rem 0.8rem; font-size: 0.7rem;">Remove</button>
                                    <button class="btn btn-edit" style="padding: 0.3rem 0.8rem; font-size: 0.7rem;">Review</button>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </section>
                </div>
            </div>
        </div>

        <!-- Advertisement Tab -->
        <div id="ads" class="tab-content">
            <div class="admin-content">
                <div class="left-column">
                    <!-- Active Ads -->
                    <section class="glass" style="padding: 1.5rem; margin-bottom: 2rem;">
                        <h3 style="margin-bottom: 1.5rem; color: var(--primary-purple);">Active Advertisements</h3>
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>Ad Title</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Period</th>
                                    <th>Budget</th>
                                    <th>Performance</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($advertisements as $ad): ?>
                                <tr>
                                    <td><?php echo $ad['title']; ?></td>
                                    <td><?php echo ucfirst($ad['type']); ?></td>
                                    <td>
                                        <span class="status-badge status-active">
                                            <?php echo ucfirst($ad['status']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo $ad['start_date']; ?> to <?php echo $ad['end_date']; ?></td>
                                    <td>$<?php echo $ad['budget']; ?></td>
                                    <td>
                                        <?php echo $ad['clicks']; ?> clicks<br>
                                        <?php echo $ad['impressions']; ?> impressions
                                    </td>
                                    <td>
                                        <div class="action-btns">
                                            <button class="btn btn-edit">Edit</button>
                                            <a href="?action=delete_ad&id=<?php echo $ad['id']; ?>" class="btn btn-delete">Delete</a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </section>
                </div>

                <div class="right-column">
                    <!-- Create New Ad -->
                    <section class="glass" style="padding: 1.5rem;">
                        <h3 style="margin-bottom: 1.5rem; color: var(--primary-purple);">Create New Advertisement</h3>
                        <form method="POST" action="" class="admin-form">
                            <div class="form-group">
                                <label class="form-label">Ad Title</label>
                                <input type="text" class="form-input" name="ad_title" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Ad Type</label>
                                <select class="form-select" name="ad_type" required>
                                    <option value="featured">Featured Notes</option>
                                    <option value="banner">Banner Ad</option>
                                    <option value="promotion">Promotion</option>
                                    <option value="sponsored">Sponsored Content</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Start Date</label>
                                <input type="date" class="form-input" name="start_date" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">End Date</label>
                                <input type="date" class="form-input" name="end_date" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Budget ($)</label>
                                <input type="number" class="form-input" name="budget" step="0.01" required>
                            </div>
                            <button type="submit" name="create_ad" class="form-submit">Create Advertisement</button>
                        </form>
                    </section>
                </div>
            </div>
        </div>

        <!-- Commission Tab -->
        <div id="commission" class="tab-content">
            <div class="admin-content">
                <div class="left-column">
                    <!-- Commission Overview -->
                    <section class="glass" style="padding: 1.5rem; margin-bottom: 2rem;">
                        <h3 style="margin-bottom: 1.5rem; color: var(--primary-purple);">Commission Overview</h3>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 2rem;">
                            <div class="commission-card glass">
                                <div>Total Commission</div>
                                <div class="commission-rate">$<?php echo number_format($commission_data['total_commission'], 2); ?></div>
                            </div>
                            <div class="commission-card glass">
                                <div>Current Rate</div>
                                <div class="commission-rate"><?php echo $commission_data['commission_rate']; ?>%</div>
                            </div>
                        </div>
                        <div class="chart-placeholder">
                            Commission Earnings Chart - Last 12 Months
                        </div>
                    </section>
                </div>

                <div class="right-column">
                    <!-- Commission Settings -->
                    <section class="glass" style="padding: 1.5rem;">
                        <h3 style="margin-bottom: 1.5rem; color: var(--primary-purple);">Commission Settings</h3>
                        <form method="POST" action="" class="admin-form">
                            <div class="form-group">
                                <label class="form-label">Commission Rate (%)</label>
                                <input type="number" class="form-input" name="commission_rate" 
                                       value="<?php echo $commission_data['commission_rate']; ?>" min="1" max="50" step="0.1" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Payout Schedule</label>
                                <select class="form-select" name="payout_schedule">
                                    <option value="weekly">Weekly</option>
                                    <option value="bi-weekly" selected>Bi-Weekly</option>
                                    <option value="monthly">Monthly</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Minimum Payout</label>
                                <input type="number" class="form-input" name="min_payout" value="20.00" step="0.01" required>
                            </div>
                            <button type="submit" name="update_commission" class="form-submit">Update Settings</button>
                        </form>
                    </section>

                    <!-- Payout Summary -->
                    <section class="glass" style="padding: 1.5rem; margin-top: 2rem;">
                        <h3 style="margin-bottom: 1.5rem; color: var(--primary-purple);">Payout Summary</h3>
                        <div style="display: flex; flex-direction: column; gap: 1rem;">
                            <div style="display: flex; justify-content: space-between;">
                                <span>This Month:</span>
                                <span style="color: var(--primary-green);">$<?php echo $commission_data['this_month']; ?></span>
                            </div>
                            <div style="display: flex; justify-content: space-between;">
                                <span>Last Month:</span>
                                <span style="color: var(--primary-green);">$<?php echo $commission_data['last_month']; ?></span>
                            </div>
                            <div style="display: flex; justify-content: space-between;">
                                <span>Pending Payouts:</span>
                                <span style="color: var(--admin-orange);">$<?php echo $commission_data['pending_payouts']; ?></span>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>

        <!-- Settings Tab -->
        <div id="settings" class="tab-content">
            <section class="glass" style="padding: 1.5rem;">
                <h3 style="margin-bottom: 1.5rem; color: var(--primary-purple);">Platform Settings</h3>
                <div class="admin-content">
                    <div class="left-column">
                        <!-- General Settings -->
                        <div class="admin-form">
                            <div class="form-group">
                                <label class="form-label">Site Name</label>
                                <input type="text" class="form-input" value="NoteShare" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Site Description</label>
                                <textarea class="form-input" rows="3">Marketplace for academic notes sharing and selling</textarea>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Admin Email</label>
                                <input type="email" class="form-input" value="admin@noteshare.com" required>
                            </div>
                            <button class="form-submit">Save General Settings</button>
                        </div>
                    </div>

                    <div class="right-column">
                        <!-- Security Settings -->
                        <div class="admin-form">
                            <div class="form-group">
                                <label class="form-label">Two-Factor Authentication</label>
                                <select class="form-select">
                                    <option value="required">Required for all users</option>
                                    <option value="optional" selected>Optional</option>
                                    <option value="disabled">Disabled</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Content Moderation</label>
                                <select class="form-select">
                                    <option value="auto">Auto + Manual Review</option>
                                    <option value="manual" selected>Manual Review Only</option>
                                    <option value="auto">Auto Review Only</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">File Upload Limit (MB)</label>
                                <input type="number" class="form-input" value="50" min="1" max="100">
                            </div>
                            <button class="form-submit">Save Security Settings</button>
                        </div>
                    </div>
                </div>
            </section>
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
            document.querySelectorAll('.admin-tab').forEach(btn => {
                btn.classList.remove('active');
            });
            
            // Show selected tab
            document.getElementById(tabName).classList.add('active');
            
            // Activate selected button
            event.target.classList.add('active');
        }

        // Add animations
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.stat-card');
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

        // Confirm destructive actions
        document.querySelectorAll('.btn-delete, .btn-reject, .btn-suspend').forEach(btn => {
            btn.addEventListener('click', function(e) {
                if (!confirm('Are you sure you want to perform this action?')) {
                    e.preventDefault();
                }
            });
        });
    </script>
</body>
</html>