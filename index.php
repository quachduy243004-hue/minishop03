<?php

// ========================================
// LOAD CONTROLLER
// ========================================

require_once __DIR__ . "/controllers/admin/ProductController.php";

// Sau này sẽ thêm:
// require_once __DIR__ . "/controllers/admin/CategoryController.php";
// require_once __DIR__ . "/controllers/admin/BrandController.php";
// require_once __DIR__ . "/controllers/admin/AuthController.php";


// ========================================
// NHẬN REQUEST
// ========================================

$controller = $_GET["controller"] ?? "product";

$action = $_GET["action"] ?? "index";


// ========================================
// XÁC ĐỊNH CONTROLLER
// ========================================

$controllerClass = ucfirst($controller) . "Controller";


// ========================================
// KIỂM TRA CONTROLLER
// ========================================

if (!class_exists($controllerClass)) {

    die("Controller không tồn tại: " . htmlspecialchars($controllerClass));
}


// ========================================
// TẠO CONTROLLER
// ========================================

$controllerObject = new $controllerClass();


// ========================================
// KIỂM TRA ACTION
// ========================================

if (!method_exists($controllerObject, $action)) {

    die(
        "Action không tồn tại: "
        . htmlspecialchars($action)
        . " trong "
        . htmlspecialchars($controllerClass)
    );
} 


// ========================================
// GỌI ACTION
// ========================================

$controllerObject->$action(); 