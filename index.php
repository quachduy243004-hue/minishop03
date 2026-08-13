<?php

require_once __DIR__ . '/autoload.php';

session_start();


// ========================================
// NHẬN REQUEST
// ========================================

$area = $_GET["area"] ?? "admin";
$controller = $_GET["controller"] ?? "product";
$action = $_GET["action"] ?? "index";


// ========================================
// AUTHENTICATION ADMIN
// ========================================

if ($area === "admin" && $controller !== "auth") {

    \Middleware\AuthMiddleware::handle();
}


// ========================================
// GUEST MIDDLEWARE
// ========================================

if (
    $area === "admin" &&
    $controller === "auth" &&
    $action === "login"
) {

    \Middleware\GuestMiddleware::handle();
}


// ========================================
// CSRF TOKEN
// ========================================

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    \Middleware\CsrfMiddleware::generateToken();
}


// ========================================
// XÁC ĐỊNH CONTROLLER
// ========================================

if ($area === "admin") {

    $controllerClass =
        "Controllers\\Admin\\"
        . ucfirst($controller)
        . "Controller";

} else {

    $controllerClass =
        "Controllers\\Client\\"
        . ucfirst($controller)
        . "Controller";
}


// ========================================
// KIỂM TRA CONTROLLER
// ========================================

if (!class_exists($controllerClass)) {

    die("Controller không tồn tại: " . $controllerClass);
}


// ========================================
// TẠO CONTROLLER
// ========================================

$controllerObject = new $controllerClass();


// ========================================
// KIỂM TRA ACTION
// ========================================

if (!method_exists($controllerObject, $action)) {

    die("Action không tồn tại: " . $action);
}


// ========================================
// GỌI ACTION
// ========================================

$controllerObject->$action();