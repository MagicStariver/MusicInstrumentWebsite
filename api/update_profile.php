<?php
require_once '../includes/session.php';
require_once '../includes/db.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
    echo json_encode([
        'success' => false, 
        'message' => 'Not logged in',
        'redirect' => 'login.php'
    ]);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false, 
        'message' => 'Invalid request method'
    ]);
    exit();
}

$user_id = $_SESSION['user_id'];

// 获取并清理数据
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$fullName = filter_input(INPUT_POST, 'fullName', FILTER_SANITIZE_STRING);
$birthday = $_POST['birthday'] ?? null;
$address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_STRING);

// 验证
if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Invalid email']);
    exit();
}

if (!$address) {
    echo json_encode(['success' => false, 'message' => 'Address is required']);
    exit();
}

try {
    // 检查邮箱是否被其他用户使用
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
    $stmt->execute([$email, $user_id]);
    if ($stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'Email already used by another user']);
        exit();
    }
    
    // 更新用户信息
    $stmt = $pdo->prepare("UPDATE users SET email = ?, full_name = ?, birthday = ?, address = ? WHERE id = ?");
    $stmt->execute([$email, $fullName, $birthday, $address, $user_id]);
    
    // 更新会话中的邮箱
    $_SESSION['email'] = $email;
    
    // 获取更新后的用户信息
    $stmt = $pdo->prepare("SELECT username, email FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();
    
    echo json_encode([
        'success' => true,
        'message' => 'Profile updated successfully',
        'data' => $user
    ]);
    
} catch (PDOException $e) {
    echo json_encode([
        'success' => false, 
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
?>