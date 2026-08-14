<?php

namespace Controllers\Admin;

use DAO\UserDAO;
use Middleware\CsrfMiddleware;

class AuthController
{
    public function login()
    {
        $errors = [];
        $username = "";

        // =========================
        // HIỂN THỊ FORM ĐĂNG NHẬP
        // =========================

        if ($_SERVER["REQUEST_METHOD"] === "GET") {

            // Tạo CSRF token
            CsrfMiddleware::generateToken();

            require_once __DIR__ . "/../../views/admin/login.php";
            return;
        }

        // =========================
        // KIỂM TRA CSRF
        // =========================

        CsrfMiddleware::verify();

        // =========================
        // NHẬN DỮ LIỆU
        // =========================

        $username = trim($_POST["username"] ?? "");
        $password = $_POST["password"] ?? "";

        // =========================
        // VALIDATE
        // =========================

        if ($username === "") {
            $errors["username"] = "Vui lòng nhập tên đăng nhập.";
        }

        if ($password === "") {
            $errors["password"] = "Vui lòng nhập mật khẩu.";
        }

        // Nếu có lỗi
        if (!empty($errors)) {

            require_once __DIR__ . "/../../views/admin/login.php";
            return;
        }

        // =========================
        // TÌM USER
        // =========================

        $userDAO = new UserDAO();

        $user = $userDAO->findByUsername($username);

        // Không tìm thấy tài khoản
        if (!$user) {

            $errors["username"] = "Tên đăng nhập không tồn tại.";

            require_once __DIR__ . "/../../views/admin/login.php";
            return;
        }

        // =========================
        // KIỂM TRA PASSWORD
        // =========================

        if (!password_verify($password, $user->password)) {

            $errors["password"] = "Mật khẩu không chính xác.";

            require_once __DIR__ . "/../../views/admin/login.php";
            return;
        }

        // =========================
        // KIỂM TRA STATUS
        // =========================

        if ((int)$user->status !== 1) {

            $errors["username"] = "Tài khoản đã bị khóa.";

            require_once __DIR__ . "/../../views/admin/login.php";
            return;
        }

        // =========================
        // ĐĂNG NHẬP THÀNH CÔNG
        // =========================

        session_regenerate_id(true);

        $_SESSION["user"] = $user;

        // =========================
        // REMEMBER ME
        // =========================

        if (isset($_POST["remember"])) {

            setcookie(
                "remember_username",
                $user->username,
                time() + (30 * 24 * 60 * 60),
                "/"
            );

        } else {

            setcookie(
                "remember_username",
                "",
                time() - 3600,
                "/"
            );
        }

        // =========================
        // CHUYỂN TRANG
        // =========================

        header(
            "Location: index.php?area=admin&controller=product&action=index"
        );

        exit;
    }


    // =========================
    // ĐĂNG XUẤT
    // =========================

    public function logout()
    {
        session_unset();
        session_destroy();

        header(
            "Location: index.php?area=admin&controller=auth&action=login"
        );

        exit;
    }
}