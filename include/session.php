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