<?php
require_once __DIR__ . '/../include/session.php';
require_once __DIR__ . '/../include/functions.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit();
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);
$itemId = $input['item_id'] ?? 0;
$change = $input['change'] ?? 0; // +1 to increase, -1 to decrease
$userId = $_SESSION['user_id'];

// Validation
if ($itemId <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid item ID']);
    exit();
}

if ($change === 0) {
    echo json_encode(['success' => false, 'message' => 'No change specified']);
    exit();
}

try {
    if ($change > 0) {
        // increase quantity
        $stmt = $pdo->prepare("UPDATE cart_items SET quantity = quantity + 1 WHERE id = ? AND user_id = ?");
        $stmt->execute([$itemId, $userId]);
    } else {
        // reduce quantity
        // First, check current quantity
        $stmt = $pdo->prepare("SELECT quantity FROM cart_items WHERE id = ? AND user_id = ?");
        $stmt->execute([$itemId, $userId]);
        $item = $stmt->fetch();
        
        if ($item && $item['quantity'] > 1) {
            // quantity greater than 1, just decrease
            $stmt = $pdo->prepare("UPDATE cart_items SET quantity = quantity - 1 WHERE id = ? AND user_id = ?");
            $stmt->execute([$itemId, $userId]);
        } else {
            // quantity is 1, remove item from cart
            $stmt = $pdo->prepare("DELETE FROM cart_items WHERE id = ? AND user_id = ?");
            $stmt->execute([$itemId, $userId]);
        }
    }
    
    echo json_encode(['success' => true, 'message' => 'Cart updated']);
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>