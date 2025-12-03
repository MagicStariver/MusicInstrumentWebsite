<?php
require_once 'db.php';

// 用户相关函数
function getUserByUsername($username) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    return $stmt->fetch();
}

function createUser($userData) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO users (username, email, password, phone, address) VALUES (?, ?, ?, ?, ?)");
    return $stmt->execute([
        $userData['username'],
        $userData['email'],
        password_hash($userData['password'], PASSWORD_DEFAULT),
        $userData['phone'],
        $userData['address']
    ]);
}

// 购物车相关函数
function addToCart($userId, $productId, $quantity = 1) {
    global $pdo;
    
    // 检查是否已存在
    $stmt = $pdo->prepare("SELECT * FROM cart_items WHERE user_id = ? AND product_id = ?");
    $stmt->execute([$userId, $productId]);
    
    if ($stmt->fetch()) {
        // 更新数量
        $stmt = $pdo->prepare("UPDATE cart_items SET quantity = quantity + ? WHERE user_id = ? AND product_id = ?");
        return $stmt->execute([$quantity, $userId, $productId]);
    } else {
        // 新增
        $stmt = $pdo->prepare("INSERT INTO cart_items (user_id, product_id, quantity) VALUES (?, ?, ?)");
        return $stmt->execute([$userId, $productId, $quantity]);
    }
}

function getUserCart($userId) {
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT ci.*, p.product_name, p.price, p.image_source 
        FROM cart_items ci
        JOIN products p ON ci.product_id = p.id
        WHERE ci.user_id = ?
    ");
    $stmt->execute([$userId]);
    return $stmt->fetchAll();
}

// 产品相关函数
function getProductById($productId) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$productId]);
    return $stmt->fetch();
}

function getAllProducts() {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM products");
    $stmt->execute();
    return $stmt->fetchAll();
}

// 订单相关函数
function createOrder($userId, $cartItems, $shippingMethod, $paymentMethod) {
    global $pdo;
    
    try {
        $pdo->beginTransaction();
        
        // 计算总价
        $total = 0;
        foreach ($cartItems as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        
        // 创建订单
        $orderNumber = 'ORD' . date('YmdHis') . rand(1000, 9999);
        $stmt = $pdo->prepare("
            INSERT INTO orders (user_id, order_number, total_amount, shipping_method, payment_method) 
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([$userId, $orderNumber, $total, $shippingMethod, $paymentMethod]);
        $orderId = $pdo->lastInsertId();
        
        // 添加订单项
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
        
        // 清空购物车
        $stmt = $pdo->prepare("DELETE FROM cart_items WHERE user_id = ?");
        $stmt->execute([$userId]);
        
        $pdo->commit();
        return $orderId;
        
    } catch (Exception $e) {
        $pdo->rollBack();
        throw $e;
    }
}
?>