<?php

require_once __DIR__ . "/autoload.php";


/*
|--------------------------------------------------------------------------
| SESSION
|--------------------------------------------------------------------------
*/

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


/*
|--------------------------------------------------------------------------
| REQUEST
|--------------------------------------------------------------------------
*/

$area = $_GET["area"] ?? "client";

$controller = strtolower(
    $_GET["controller"] ?? "home"
);

$action = strtolower(
    $_GET["action"] ?? "index"
);


/*
|--------------------------------------------------------------------------
| ADMIN AUTH
|--------------------------------------------------------------------------
*/

if (
    $area === "admin"
    && $controller !== "auth"
) {
    \Middleware\AuthMiddleware::handle();
}


/*
|--------------------------------------------------------------------------
| GUEST
|--------------------------------------------------------------------------
*/

if (
    $area === "admin"
    && $controller === "auth"
    && $action === "login"
) {
    \Middleware\GuestMiddleware::handle();
}


/*
|--------------------------------------------------------------------------
| CONTROLLER CLASS
|--------------------------------------------------------------------------
*/

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


/*
|--------------------------------------------------------------------------
| CHECK CONTROLLER
|--------------------------------------------------------------------------
*/

if (!class_exists($controllerClass)) {

    die(
        "Controller không tồn tại: "
        . htmlspecialchars($controllerClass)
    );
}


/*
|--------------------------------------------------------------------------
| CREATE CONTROLLER
|--------------------------------------------------------------------------
*/

$controllerObject = new $controllerClass();


/*
|--------------------------------------------------------------------------
| CHECK ACTION
|--------------------------------------------------------------------------
*/

if (!method_exists($controllerObject, $action)) {

    die(
        "Action không tồn tại: "
        . htmlspecialchars($action)
    );
}


/*
|--------------------------------------------------------------------------
| CALL ACTION
|--------------------------------------------------------------------------
*/

$controllerObject->$action();