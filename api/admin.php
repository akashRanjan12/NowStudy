<?php
session_start();
header('Content-Type: application/json');
require_once '../config/database.php';
require_once '../includes/functions.php';

if(!isAdmin()) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

$database = new Database();
$db = $database->getConnection();

$input = json_decode(file_get_contents('php://input'), true);
$action = $_GET['action'] ?? $input['action'] ?? '';

switch($action) {
    case 'getStats':
        getAdminStats($db);
        break;
    case 'getPendingNotes':
        getPendingNotes($db);
        break;
    case 'approveNote':
        approveNote($db, $input);
        break;
    case 'rejectNote':
        rejectNote($db, $input);
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
}

function getAdminStats($db) {
    // Total users
    $usersQuery = "SELECT COUNT(*) FROM users";
    $total_users = $db->query($usersQuery)->fetchColumn();
    
    // Total notes
    $notesQuery = "SELECT COUNT(*) FROM notes";
    $total_notes = $db->query($notesQuery)->fetchColumn();
    
    // Pending approvals
    $pendingQuery = "SELECT COUNT(*) FROM notes WHERE status = 'pending'";
    $pending_approvals = $db->query($pendingQuery)->fetchColumn();
    
    // Total revenue
    $revenueQuery = "SELECT COALESCE(SUM(commission), 0) FROM orders WHERE status = 'completed'";
    $total_revenue = $db->query($revenueQuery)->fetchColumn();
    
    echo json_encode([
        'success' => true,
        'total_users' => $total_users,
        'total_notes' => $total_notes,
        'pending_approvals' => $pending_approvals,
        'total_revenue' => $total_revenue
    ]);
}

function getPendingNotes($db) {
    $query = "SELECT n.*, u.name as seller_name 
              FROM notes n 
              JOIN users u ON n.seller_id = u.id 
              WHERE n.status = 'pending' 
              ORDER BY n.created_at DESC";
    
    $stmt = $db->prepare($query);
    $stmt->execute();
    $notes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($notes);
}

function approveNote($db, $data) {
    $note_id = $data['note_id'] ?? 0;
    
    $query = "UPDATE notes SET status = 'approved' WHERE id = :id";
    $stmt = $db->prepare($query);
    $result = $stmt->execute([':id' => $note_id]);
    
    if($result) {
        echo json_encode(['success' => true, 'message' => 'Note approved successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to approve note']);
    }
}

function rejectNote($db, $data) {
    $note_id = $data['note_id'] ?? 0;
    $reason = $data['reason'] ?? '';
    
    $query = "UPDATE notes SET status = 'rejected' WHERE id = :id";
    $stmt = $db->prepare($query);
    $result = $stmt->execute([':id' => $note_id]);
    
    if($result) {
        // Here you might want to send an email to the seller with the reason
        echo json_encode(['success' => true, 'message' => 'Note rejected successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to reject note']);
    }
}
?>