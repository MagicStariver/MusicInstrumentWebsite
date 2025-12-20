<?php
require_once __DIR__ . '/../include/session.php';
require_once __DIR__ . '/../include/functions.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit();
}

$userId = $_SESSION['user_id'];

try {
    // Get user's shopping cart items
    $stmt = $pdo->prepare("
        SELECT ci.id, ci.product_id, ci.quantity, 
               p.product_name, p.price, p.image_source
        FROM cart_items ci
        JOIN products p ON ci.product_id = p.id
        WHERE ci.user_id = ?
    ");
    $stmt->execute([$userId]);
    $cartItems = $stmt->fetchAll();
    
    echo json_encode([
        'success' => true,
        'data' => $cartItems
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]);
}
?>