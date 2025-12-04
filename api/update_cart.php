<?php
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/functions.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit();
}

// 获取输入数据
$input = json_decode(file_get_contents('php://input'), true);
$itemId = $input['item_id'] ?? 0;
$change = $input['change'] ?? 0; // +1 增加, -1 减少
$userId = $_SESSION['user_id'];

// 验证
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
        // 增加数量
        $stmt = $pdo->prepare("UPDATE cart_items SET quantity = quantity + 1 WHERE id = ? AND user_id = ?");
        $stmt->execute([$itemId, $userId]);
    } else {
        // 减少数量
        // 先获取当前数量
        $stmt = $pdo->prepare("SELECT quantity FROM cart_items WHERE id = ? AND user_id = ?");
        $stmt->execute([$itemId, $userId]);
        $item = $stmt->fetch();
        
        if ($item && $item['quantity'] > 1) {
            // 数量大于1，减少
            $stmt = $pdo->prepare("UPDATE cart_items SET quantity = quantity - 1 WHERE id = ? AND user_id = ?");
            $stmt->execute([$itemId, $userId]);
        } else {
            // 数量为1，删除该项
            $stmt = $pdo->prepare("DELETE FROM cart_items WHERE id = ? AND user_id = ?");
            $stmt->execute([$itemId, $userId]);
        }
    }
    
    echo json_encode(['success' => true, 'message' => 'Cart updated']);
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>