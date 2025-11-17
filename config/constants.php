<?php
define('SITE_URL', 'http://localhost/notes_marketplace');
define('UPLOAD_PATH', $_SERVER['DOCUMENT_ROOT'] . '/notes_marketplace/uploads/');
define('COMMISSION_RATE', 0.20); // 20% commission

// Payment gateway constants (Razorpay)
define('RAZORPAY_KEY_ID', 'your_razorpay_key_id');
define('RAZORPAY_KEY_SECRET', 'your_razorpay_key_secret');
?>