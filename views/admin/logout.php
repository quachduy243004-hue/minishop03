<?php

// Load User trước session
require_once __DIR__ . "/../../models/User.php";

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Xóa toàn bộ session
$_SESSION = [];

// Xóa session
session_unset();

session_destroy();

// Xóa Cookie PHPSESSID
if (ini_get("session.use_cookies")) {

    $params = session_get_cookie_params();

    setcookie(
        session_name(),
        "",
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// Về Login
header("Location: login.php");
exit; 