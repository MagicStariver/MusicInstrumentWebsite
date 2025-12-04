<?php
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/functions.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit();
}

// 确保 $pdo 可用
global $pdo;

$userId = $_SESSION['user_id'];
$input = json_decode(file_get_contents('php://input'), true);

// 验证输入数据
$shippingMethod = $input['shipping_method'] ?? '';
$paymentMethod = $input['payment_method'] ?? '';
$cartItems = $input['cart_items'] ?? [];

// 验证配送方式
$allowedShipping = ['j&t', 'dhl', 'poslaju', 'fedex'];
if (!in_array($shippingMethod, $allowedShipping)) {
    echo json_encode(['success' => false, 'message' => 'Invalid shipping method']);
    exit();
}

// 验证支付方式
$allowedPayment = ['credit-card', 'paypal', 'bank-transfer', 'cash-on-delivery'];
if (!in_array($paymentMethod, $allowedPayment)) {
    echo json_encode(['success' => false, 'message' => 'Invalid payment method']);
    exit();
}

if (empty($cartItems)) {
    echo json_encode(['success' => false, 'message' => 'Cart is empty']);
    exit();
}

try {
    // 开始事务
    $pdo->beginTransaction();
    
    $totalAmount = 0;
    
    // 1. 检查库存并计算总金额
    foreach ($cartItems as $item) {
        // 检查库存
        $stmt = $pdo->prepare("SELECT product_name, price, stock_quantity FROM products WHERE id = ?");
        $stmt->execute([$item['product_id']]);
        $product = $stmt->fetch();
        
        if (!$product) {
            throw new Exception("Product not found: " . $item['product_id']);
        }
        
        if ($product['stock_quantity'] < $item['quantity']) {
            throw new Exception("Insufficient stock for: " . $product['product_name']);
        }
        
        // 计算金额
        $itemTotal = $product['price'] * $item['quantity'];
        $totalAmount += $itemTotal;
        
        // 更新购物车项目中的价格和名称（确保一致）
        $item['product_name'] = $product['product_name'];
        $item['price'] = $product['price'];
    }
    
    // 2. 计算运费
    $shippingRates = [
        'j&t' => 4.90,
        'dhl' => 5.90,
        'poslaju' => 6.90,
        'fedex' => 3.90
    ];
    $shippingFee = $shippingRates[$shippingMethod] ?? 4.90;
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
    
    // 5. 添加订单项目并更新库存
    foreach ($cartItems as $item) {
        // 添加订单项目
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
        
        // 更新产品库存
        $stmt = $pdo->prepare("
            UPDATE products SET stock_quantity = stock_quantity - ? WHERE id = ?
        ");
        $stmt->execute([$item['quantity'], $item['product_id']]);
    }
    
    // 6. 清空购物车
    $stmt = $pdo->prepare("DELETE FROM cart_items WHERE user_id = ?");
    $stmt->execute([$userId]);
    
    // 提交事务
    $pdo->commit();
    
    echo json_encode([
        'success' => true,
        'message' => 'Order placed successfully',
        'order_id' => $orderId,
        'order_number' => $orderNumber,
        'total_amount' => $totalAmount
    ]);
    
} catch (Exception $e) {
    // 回滚事务
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>