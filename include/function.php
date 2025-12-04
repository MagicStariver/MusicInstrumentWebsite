<?php
require_once 'db.php';

/**
 * 根据ID获取用户信息
 */
function getUserById($userId) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT id, username, email, phone, address, full_name, birthday FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetch();
    } catch (PDOException $e) {
        error_log("getUserById error: " . $e->getMessage());
        return false;
    }
}

/**
 * 添加到购物车
 */
function addToCart($userId, $productId, $quantity = 1) {
    global $pdo;
    try {
        // 检查是否已存在
        $stmt = $pdo->prepare("SELECT id, quantity FROM cart_items WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$userId, $productId]);
        $existing = $stmt->fetch();
        
        if ($existing) {
            // 更新数量
            $stmt = $pdo->prepare("UPDATE cart_items SET quantity = quantity + ? WHERE id = ?");
            return $stmt->execute([$quantity, $existing['id']]);
        } else {
            // 新增
            $stmt = $pdo->prepare("INSERT INTO cart_items (user_id, product_id, quantity) VALUES (?, ?, ?)");
            return $stmt->execute([$userId, $productId, $quantity]);
        }
    } catch (PDOException $e) {
        error_log("addToCart error: " . $e->getMessage());
        return false;
    }
}

/**
 * 根据用户名获取用户
 */
function getUserByUsername($username) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT id, username, email, password, phone, address FROM users WHERE username = ?");
        $stmt->execute([$username]);
        return $stmt->fetch();
    } catch (PDOException $e) {
        error_log("getUserByUsername error: " . $e->getMessage());
        return false;
    }
}

/**
 * 创建新用户
 */
function createUser($userData) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password, phone, address) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([
            $userData['username'],
            $userData['email'],
            password_hash($userData['password'], PASSWORD_DEFAULT),
            $userData['phone'],
            $userData['address']
        ]);
    } catch (PDOException $e) {
        error_log("createUser error: " . $e->getMessage());
        return false;
    }
}
?>