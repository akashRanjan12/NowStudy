<?php
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

function formatPrice($price) {
    return '₹' . number_format($price, 2);
}

function generateNoteSlug($title) {
    return preg_replace('/[^a-z0-9]+/', '-', strtolower($title));
}

function handleFileUpload($file, $type = 'note') {
    $uploadDir = UPLOAD_PATH . ($type === 'note' ? 'notes/' : 'previews/');
    $fileName = time() . '_' . basename($file['name']);
    $targetPath = $uploadDir . $fileName;
    
    if(move_uploaded_file($file['tmp_name'], $targetPath)) {
        return $fileName;
    }
    return false;
}
?>