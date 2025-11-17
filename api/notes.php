<?php
session_start();
header('Content-Type: application/json');
require_once '../config/database.php';
require_once '../config/constants.php';
require_once '../includes/functions.php';

$database = new Database();
$db = $database->getConnection();
$action = $_GET['action'] ?? '';

switch($action) {
    case 'getFeatured':
        getFeaturedNotes($db);
        break;
    case 'getAll':
        getAllNotes($db);
        break;
    case 'upload':
        uploadNote($db);
        break;
    case 'getUserNotes':
        getUserNotes($db);
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
}

function getFeaturedNotes($db) {
    $query = "SELECT n.*, u.name as seller_name 
              FROM notes n 
              JOIN users u ON n.seller_id = u.id 
              WHERE n.status = 'approved' 
              AND n.featured = 1 
              ORDER BY n.created_at DESC 
              LIMIT 6";
    
    $stmt = $db->prepare($query);
    $stmt->execute();
    
    $notes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($notes);
}

function getAllNotes($db) {
    $page = $_GET['page'] ?? 1;
    $limit = 6;
    $offset = ($page - 1) * $limit;
    
    $whereConditions = ["n.status = 'approved'"];
    $params = [];
    
    if (!empty($_GET['category'])) {
        $whereConditions[] = "n.category = :category";
        $params[':category'] = $_GET['category'];
    }
    
    if (!empty($_GET['price_range'])) {
        $priceRange = explode('-', $_GET['price_range']);
        $whereConditions[] = "n.price BETWEEN :min_price AND :max_price";
        $params[':min_price'] = $priceRange[0];
        $params[':max_price'] = $priceRange[1];
    }
    
    if (!empty($_GET['search'])) {
        $whereConditions[] = "(n.title LIKE :search OR n.description LIKE :search)";
        $params[':search'] = '%' . $_GET['search'] . '%';
    }
    
    $whereClause = implode(' AND ', $whereConditions);
    
    // Get total count
    $countQuery = "SELECT COUNT(*) FROM notes n WHERE $whereClause";
    $countStmt = $db->prepare($countQuery);
    foreach($params as $key => $value) {
        $countStmt->bindValue($key, $value);
    }
    $countStmt->execute();
    $totalNotes = $countStmt->fetchColumn();
    
    // Get notes
    $query = "SELECT n.*, u.name as seller_name 
              FROM notes n 
              JOIN users u ON n.seller_id = u.id 
              WHERE $whereClause 
              ORDER BY n.created_at DESC 
              LIMIT :limit OFFSET :offset";
    
    $stmt = $db->prepare($query);
    foreach($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    
    $notes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $hasMore = ($totalNotes > ($offset + $limit));
    
    echo json_encode([
        'notes' => $notes,
        'hasMore' => $hasMore,
        'total' => $totalNotes
    ]);
}

function uploadNote($db) {
    if (!isLoggedIn()) {
        echo json_encode(['success' => false, 'message' => 'Please login to upload notes']);
        return;
    }
    
    $title = $_POST['title'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $seller_id = $_SESSION['user_id'];
    
    // Handle file upload
    $note_file = handleFileUpload($_FILES['note_file'], 'note');
    $preview_file = handleFileUpload($_FILES['preview_file'], 'preview');
    
    if (!$note_file || !$preview_file) {
        echo json_encode(['success' => false, 'message' => 'File upload failed']);
        return;
    }
    
    $query = "INSERT INTO notes (title, description, category, price, note_file, preview_file, seller_id, status) 
              VALUES (:title, :description, :category, :price, :note_file, :preview_file, :seller_id, 'pending')";
    
    $stmt = $db->prepare($query);
    $result = $stmt->execute([
        ':title' => $title,
        ':description' => $description,
        ':category' => $category,
        ':price' => $price,
        ':note_file' => $note_file,
        ':preview_file' => $preview_file,
        ':seller_id' => $seller_id
    ]);
    
    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Note uploaded successfully! Waiting for admin approval.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to upload note']);
    }
}
?>