<?php
// require_once __DIR__ . '/autoload.php';
// Nhận Request
$area = $_GET["area"] ?? "admin";
$controller = $_GET["controller"] ?? "product";
$action = $_GET["action"] ?? "index";
// ========================================
// LOAD CLASS
// ========================================

// require_once __DIR__ . "/../../../models/User.php";
// require_once __DIR__ . "/../../../middleware/AuthMiddleware.php";
// require_once __DIR__ . "/../../../middleware/CsrfMiddleware.php";

// ========================================
// START SESSION
// ========================================

// if (session_status() === PHP_SESSION_NONE) {
//     session_start();
// }

// ========================================
// KIỂM TRA ĐĂNG NHẬP
// ========================================

// AuthMiddleware::handle();

// ========================================
// CSRF TOKEN
// ========================================

// CsrfMiddleware::generateToken();

?>

<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        <?= htmlspecialchars($pageTitle ?? "Mini Shop Admin") ?>
    </title>

    <!-- Bootstrap -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <!-- Font Awesome -->
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
    >

    <style>

        body {
            background-color: #f8f9fa;
        }

        .admin-header {
            height: 65px;
        }

        .sidebar {
            min-height: calc(100vh - 65px);
            background: #212529;
        }

        .sidebar a {
            color: #fff;
            text-decoration: none;
            display: block;
            padding: 12px 20px;
        }

        .sidebar a:hover {
            background: #343a40;
        }

        .sidebar .active {
            background: #dc3545;
        }

        .card-box {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        }

        .card-icon {
            font-size: 35px;
        }

        .table-box {
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        }

    </style>

</head>

<body>

    <!-- HEADER -->
    <?php include __DIR__ . "/header.php"; ?>


    <div class="container-fluid">

        <div class="row">

            <!-- SIDEBAR -->
            <?php include __DIR__ . "/sidebar.php"; ?>


            <!-- CONTENT -->
            <main class="col-md-10 p-4">

                <?= $content ?? "" ?>

            </main>

        </div>

    </div>


    <!-- FOOTER -->
    <?php include __DIR__ . "/footer.php"; ?>


    <!-- Bootstrap JS -->
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
    </script>

</body>

</html>