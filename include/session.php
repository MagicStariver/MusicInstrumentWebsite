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

// auto-redirect to login page if not logged in
if (!isLoggedIn() && basename($_SERVER['PHP_SELF']) != 'login.php' && basename($_SERVER['PHP_SELF']) != 'register.php') {
    // for pages that require authentication
    $protected_pages = ['profile.php', 'cart.php', 'trackOrder.php', 'check_out.php'];
    if (in_array(basename($_SERVER['PHP_SELF']), $protected_pages)) {
        redirectToLogin();
    }
}
?>

