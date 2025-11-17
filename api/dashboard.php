<?php
session_start();
header('Content-Type: application/json');
require_once '../config/database.php';
require_once '../includes/functions.php';

if(!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

$database = new Database();
$db = $database->getConnection();
$action = $_GET['action'] ?? '';

switch($action) {
    case 'getStats':
        getDashboardStats($db);
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
}

function getDashboardStats($db) {
    $user_id = $_SESSION['user_id'];
    
    // Total notes
    $notesQuery = "SELECT COUNT(*) FROM notes WHERE seller_id = :user_id";
    $notesStmt = $db->prepare($notesQuery);
    $notesStmt->execute([':user_id' => $user_id]);
    $total_notes = $notesStmt->fetchColumn();
    
    // Total sales and earnings
    $salesQuery = "SELECT COUNT(*) as total_sales, COALESCE(SUM(seller_earning), 0) as total_earnings 
                   FROM orders 
                   WHERE note_id IN (SELECT id FROM notes WHERE seller_id = :user_id) 
                   AND status = 'completed'";
    $salesStmt = $db->prepare($salesQuery);
    $salesStmt->execute([':user_id' => $user_id]);
    $salesData = $salesStmt->fetch(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true,
        'total_notes' => $total_notes,
        'total_sales' => $salesData['total_sales'],
        'total_earnings' => $salesData['total_earnings']
    ]);
}
?>