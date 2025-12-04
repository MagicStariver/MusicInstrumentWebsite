<?php
require_once 'session.php';

// clear all session variables
$_SESSION = array();

// destroy the session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

session_destroy();

// be redirected to homepage
header("Location: index.php");
exit();
?>