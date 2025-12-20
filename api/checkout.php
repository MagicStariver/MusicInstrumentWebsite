<?php
require_once __DIR__ . '/../include/session.php';
require_once __DIR__ . '/../include/functions.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit();
}

// Ensure $pdo is availableglobal $pdo;

$userId = $_SESSION['user_id'];
$input = json_decode(file_get_contents('php://input'), true);

// Validate input data
$shippingMethod = $input['shipping_method'] ?? '';
$paymentMethod = $input['payment_method'] ?? '';
$cartItems = $input['cart_items'] ?? [];

// Verify delivery method
$allowedShipping = ['j&t', 'dhl', 'poslaju', 'fedex'];
if (!in_array($shippingMethod, $allowedShipping)) {
    echo json_encode(['success' => false, 'message' => 'Invalid shipping method']);
    exit();
}

// Verify payment method
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
// Start transaction
    $pdo->beginTransaction();
    
    $totalAmount = 0;
    
   // 1. Check inventory and calculate total amount
    foreach ($cartItems as $item) {
        // Check inventory
        $stmt = $pdo->prepare("SELECT product_name, price, stock_quantity FROM products WHERE id = ?");
        $stmt->execute([$item['product_id']]);
        $product = $stmt->fetch();
        
        if (!$product) {
            throw new Exception("Product not found: " . $item['product_id']);
        }
        
        if ($product['stock_quantity'] < $item['quantity']) {
            throw new Exception("Insufficient stock for: " . $product['product_name']);
        }
            
        // Calculate the amount
        $itemTotal = $product['price'] * $item['quantity'];
        $totalAmount += $itemTotal;
        
        // Update the prices and names of items in the shopping cart (make sure they match).        
        $item['product_name'] = $product['product_name'];
        $item['price'] = $product['price'];
    }
    
    // 2. Calculate shipping costs
    $shippingRates = [
        'j&t' => 4.90,
        'dhl' => 5.90,
        'poslaju' => 6.90,
        'fedex' => 3.90
    ];
    $shippingFee = $shippingRates[$shippingMethod] ?? 4.90;
    $totalAmount += $shippingFee;
    
    // 3. Generate order number
    $orderNumber = 'ORD' . date('YmdHis') . rand(100, 999);
    
    // 4. Create an order
    $stmt = $pdo->prepare("
        INSERT INTO orders (user_id, order_number, total_amount, shipping_method, payment_method, status) 
        VALUES (?, ?, ?, ?, ?, 'payment')
    ");
    $stmt->execute([$userId, $orderNumber, $totalAmount, $shippingMethod, $paymentMethod]);
    $orderId = $pdo->lastInsertId();
    
    // 5. Add order items and update inventory
    foreach ($cartItems as $item) {
        // Add order item
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
        
        // Update product inventory
        $stmt = $pdo->prepare("
            UPDATE products SET stock_quantity = stock_quantity - ? WHERE id = ?
        ");
        $stmt->execute([$item['quantity'], $item['product_id']]);
    }
    
    // 6. Empty your shopping cart
    $stmt = $pdo->prepare("DELETE FROM cart_items WHERE user_id = ?");
    $stmt->execute([$userId]);
    
    // Commit transaction
    $pdo->commit();
    
    echo json_encode([
        'success' => true,
        'message' => 'Order placed successfully',
        'order_id' => $orderId,
        'order_number' => $orderNumber,
        'total_amount' => $totalAmount
    ]);
    
} catch (Exception $e) {
    // Rollback transaction
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>