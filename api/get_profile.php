<?php
require_once '../includes/session.php';
require_once '../includes/functions.php';

if (!isLoggedIn()) {
    echo json_encode([
        'success' => false, 
        'message' => 'Not logged in',
        'redirect' => 'login.php'
    ]);
    exit();
}

try {
    $userId = $_SESSION['user_id'];
    
    // Retrieve user information from the database
    $stmt = $pdo->prepare("SELECT username, email, phone, address, full_name, birthday FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch();
    
    if ($user) {
        echo json_encode([
            'success' => true,
            'data' => $user
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'User not found'
        ]);
    }
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]);
}
?>