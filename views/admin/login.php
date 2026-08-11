<?php

session_start();

require_once __DIR__ . "/../../dao/UserDAO.php";

$username = "";
$password = "";

$errors = [];

// Nếu đã đăng nhập thì chuyển thẳng vào dashboard
if (isset($_SESSION["user"])) {
    header("Location: index.php");
    exit;
}

// =========================
// XỬ LÝ ĐĂNG NHẬP
// =========================

if ($_SERVER["REQUEST_METHOD"] === "POST") {

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

        } else {

            // =========================
            // ĐĂNG NHẬP THÀNH CÔNG
            // =========================

            $_SESSION["user"] = $user;

            header("Location: index.php");
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

            <div class="mt-4">

                <h4 class="text-center fw-bold mb-4">
                    Đăng nhập
                </h4>

                <form
                    action="login.php"
                    method="POST">

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


                    <!-- QUÊN MẬT KHẨU -->

                    <div class="text-end mb-4">

                        <a
                            href="#"
                            class="text-primary text-decoration-none small">

                            Quên mật khẩu?

                        </a>

                    </div>


                    <!-- ĐĂNG NHẬP -->

                    <div class="d-grid">

                        <button
                            type="submit"
                            class="btn btn-danger py-3">

                            Đăng Nhập Ngay

                        </button>

                    </div>


                    <!-- ĐĂNG KÝ -->

                    <div class="d-grid mt-2">

                        <a
                            href="#"
                            class="btn btn-light border py-3">

                            Tạo tài khoản mới

                        </a>

                    </div>


                    <!-- ĐIỀU KHOẢN -->

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