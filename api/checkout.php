<?php
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/functions.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit();
}

$userId = $_SESSION['user_id'];
$input = json_decode(file_get_contents('php://input'), true);

// 获取结账数据
$shippingMethod = $input['shipping_method'] ?? '';
$paymentMethod = $input['payment_method'] ?? '';
$cartItems = $input['cart_items'] ?? [];

if (empty($cartItems)) {
    echo json_encode(['success' => false, 'message' => 'Cart is empty']);
    exit();
}

try {
    // 1. 计算总金额
    $totalAmount = 0;
    foreach ($cartItems as $item) {
        $totalAmount += $item['price'] * $item['quantity'];
    }
    
    // 2. 添加运费（简单示例）
    $shippingFee = 4.90; // 固定运费
    $totalAmount += $shippingFee;
    
    // 3. 生成订单号
    $orderNumber = 'ORD' . date('YmdHis') . rand(100, 999);
    
    // 4. 创建订单
    $stmt = $pdo->prepare("
        INSERT INTO orders (user_id, order_number, total_amount, shipping_method, payment_method, status) 
        VALUES (?, ?, ?, ?, ?, 'payment')
    ");
    $stmt->execute([$userId, $orderNumber, $totalAmount, $shippingMethod, $paymentMethod]);
    $orderId = $pdo->lastInsertId();
    
    // 5. 添加订单项目
    foreach ($cartItems as $item) {
        $stmt = $pdo->prepare("
            INSERT INTO order_items (order_id, product_id, product_name, product_price, quantity) 
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $orderId,
            $item['product_id'],
            $item['product_name'],
            $item['price'],
            $item['quantity']
        ]);
    }
    
    // 6. 清空购物车（可选）
    $stmt = $pdo->prepare("DELETE FROM cart_items WHERE user_id = ?");
    $stmt->execute([$userId]);
    
    echo json_encode([
        'success' => true,
        'message' => 'Order placed successfully',
        'order_id' => $orderId,
        'order_number' => $orderNumber
    ]);
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>