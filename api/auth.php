<?php
session_start();
header('Content-Type: application/json');
require_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();

$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch($action) {
    case 'register':
        registerUser($db);
        break;
    case 'login':
        loginUser($db);
        break;
    case 'logout':
        logoutUser();
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
}

function registerUser($db) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $is_seller = isset($_POST['is_seller']) ? 1 : 0;

    // Validate inputs
    if(empty($name) || empty($email) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'All fields are required']);
        return;
    }

    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Invalid email format']);
        return;
    }

    if(strlen($password) < 6) {
        echo json_encode(['success' => false, 'message' => 'Password must be at least 6 characters']);
        return;
    }

    // Check if email already exists
    $checkQuery = "SELECT id FROM users WHERE email = :email";
    $checkStmt = $db->prepare($checkQuery);
    $checkStmt->execute([':email' => $email]);
    
    if($checkStmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'Email already registered']);
        return;
    }

    // Insert new user
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $query = "INSERT INTO users (name, email, password) VALUES (:name, :email, :password)";
    
    $stmt = $db->prepare($query);
    $result = $stmt->execute([
        ':name' => $name,
        ':email' => $email,
        ':password' => $hashedPassword
    ]);

    if($result) {
        echo json_encode(['success' => true, 'message' => 'Registration successful! You can now login.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Registration failed. Please try again.']);
    }
}

function loginUser($db) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if(empty($email) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'Email and password are required']);
        return;
    }

    $query = "SELECT * FROM users WHERE email = :email";
    $stmt = $db->prepare($query);
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_role'] = $user['role'];
        
        echo json_encode(['success' => true, 'message' => 'Login successful!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid email or password']);
    }
}

function logoutUser() {
    session_destroy();
    echo json_encode(['success' => true, 'message' => 'Logged out successfully']);
}
?>