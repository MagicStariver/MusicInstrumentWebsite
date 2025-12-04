<?php
require_once 'db.php';

global $pdo;
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


function addToCart($userId, $productId, $quantity = 1) {
    global $pdo;
    try {
        // check if item already in cart
        $stmt = $pdo->prepare("SELECT id, quantity FROM cart_items WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$userId, $productId]);
        $existing = $stmt->fetch();
        
        if ($existing) {
            // update quantity
            $stmt = $pdo->prepare("UPDATE cart_items SET quantity = quantity + ? WHERE id = ?");
            return $stmt->execute([$quantity, $existing['id']]);
        } else {
            // insert new item
            $stmt = $pdo->prepare("INSERT INTO cart_items (user_id, product_id, quantity) VALUES (?, ?, ?)");
            return $stmt->execute([$userId, $productId, $quantity]);
        }
    } catch (PDOException $e) {
        error_log("addToCart error: " . $e->getMessage());
        return false;
    }
}


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