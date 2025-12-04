<?php
session_start();

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function getCurrentUser() {
    return $_SESSION['username'] ?? null;
}

function redirectToLogin() {
    header("Location: login.php");
    exit();
}

// 自动检查登录状态
if (!isLoggedIn() && basename($_SERVER['PHP_SELF']) != 'login.php' && basename($_SERVER['PHP_SELF']) != 'register.php') {
    // 对于需要登录的页面进行重定向
    $protected_pages = ['profile.php', 'cart.php', 'trackOrder.php', 'check_out.php'];
    if (in_array(basename($_SERVER['PHP_SELF']), $protected_pages)) {
        redirectToLogin();
    }
}
?>

// 基本会话配置
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);

// 如果使用 HTTPS
// ini_set('session.cookie_secure', 1);

// 设置会话过期时间（8小时）
$timeout = 8 * 60 * 60; // 8小时
ini_set('session.gc_maxlifetime', $timeout);

// 自定义会话处理（如果需要）
function checkSession() {
    if (!isset($_SESSION['user_id'])) {
        // 用户未登录
        return false;
    }
    return true;
}

// 检查是否是登录页面，避免重定向循环
$current_page = basename($_SERVER['PHP_SELF']);
$login_pages = ['login.php', 'register.php', 'index.php'];

// 如果不是登录页面且用户未登录，重定向到登录页
if (!in_array($current_page, $login_pages) && !checkSession()) {
    header('Location: login.php');
    exit();
}
?>