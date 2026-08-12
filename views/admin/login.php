<?php

session_start();

require_once __DIR__ . "/../../models/User.php";
require_once __DIR__ . "/../../dao/UserDAO.php";
require_once __DIR__ . "/../../middleware/GuestMiddleware.php";
require_once __DIR__ . "/../../middleware/CsrfMiddleware.php";

// Nếu đã đăng nhập thì không cho vào login
GuestMiddleware::handle();

// Tạo CSRF Token
CsrfMiddleware::generateToken();

$username = "";
$password = "";
$errors = [];

// =========================
// XỬ LÝ ĐĂNG NHẬP
// =========================

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Kiểm tra CSRF
    CsrfMiddleware::verify();

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

    // =========================
    // TÌM USER
    // =========================

    if (empty($errors)) {

        $userDAO = new UserDAO();

        $user = $userDAO->findByUsername($username);

        if (!$user) {

            $errors["username"] = "Tên đăng nhập không tồn tại.";

        } elseif (!password_verify($password, $user->password)) {

            $errors["password"] = "Mật khẩu không chính xác.";

        } elseif ((int)$user->status !== 1) {

            $errors["username"] = "Tài khoản đã bị khóa.";

        } else {

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
            // CHUYỂN ĐẾN DASHBOARD
            // =========================

            header("Location: dashboard.php");
            exit;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Đăng nhập - MiniShop</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

</head>

<body class="bg-light">

<!-- HEADER -->

<div class="bg-white border-bottom">

    <div class="container-fluid px-4">

        <div
            class="d-flex justify-content-between align-items-center"
            style="height: 60px;">

            <div class="text-danger fw-bold fs-5">

                <i class="fa-solid fa-dragon"></i>

                MiniShop

            </div>

            <div class="text-secondary fs-5">

                <i class="fa-solid fa-globe"></i>

            </div>

        </div>

    </div>

</div>


<!-- LOGIN -->

<div class="container">

    <div class="row justify-content-center">

        <div class="col-12 col-sm-8 col-md-5 col-lg-4">

            <div class="mt-5">

                <h4 class="text-center fw-bold mb-4">
                    Đăng nhập
                </h4>

                <form
                    action="login.php"
                    method="POST">

                    <!-- CSRF -->

                    <input
                        type="hidden"
                        name="csrf_token"
                        value="<?= htmlspecialchars($_SESSION["csrf_token"] ?? "") ?>">


                    <!-- USERNAME -->

                    <div class="mb-3">

                        <input
                            type="text"
                            name="username"
                            value="<?= htmlspecialchars($username) ?>"
                            class="form-control <?= isset($errors["username"]) ? "is-invalid" : "" ?>"
                            placeholder="Tài khoản, Email hoặc số điện thoại">

                        <?php if (isset($errors["username"])): ?>

                            <div class="invalid-feedback">
                                <?= htmlspecialchars($errors["username"]) ?>
                            </div>

                        <?php endif; ?>

                    </div>


                    <!-- PASSWORD -->

                    <div class="mb-2">

                        <input
                            type="password"
                            name="password"
                            class="form-control <?= isset($errors["password"]) ? "is-invalid" : "" ?>"
                            placeholder="Mật khẩu">

                        <?php if (isset($errors["password"])): ?>

                            <div class="invalid-feedback">
                                <?= htmlspecialchars($errors["password"]) ?>
                            </div>

                        <?php endif; ?>

                    </div>


                    <!-- REMEMBER -->

                    <div class="mb-3 form-check">

                        <input
                            type="checkbox"
                            name="remember"
                            class="form-check-input"
                            id="remember">

                        <label
                            class="form-check-label"
                            for="remember">

                            Ghi nhớ đăng nhập

                        </label>

                    </div>


                    <!-- QUÊN MẬT KHẨU -->

                    <div class="text-end mb-4">

                        <a
                            href="#"
                            class="text-primary text-decoration-none small">

                            Quên mật khẩu?

                        </a>

                    </div>


                    <!-- LOGIN -->

                    <div class="d-grid">

                        <button
                            type="submit"
                            class="btn btn-danger py-3">

                            Đăng nhập

                        </button>

                    </div>


                    <!-- REGISTER -->

                    <div class="d-grid mt-2">

                        <a
                            href="#"
                            class="btn btn-light border py-3">

                            Tạo tài khoản mới

                        </a>

                    </div>


                    <!-- TERMS -->

                    <div class="text-center mt-3">

                        <small class="text-secondary">

                            <a
                                href="#"
                                class="text-primary text-decoration-none">

                                Điều Khoản Dịch Vụ

                            </a>

                            và

                            <a
                                href="#"
                                class="text-primary text-decoration-none">

                                Chính Sách Bảo Mật

                            </a>

                        </small>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>

</body>

</html>