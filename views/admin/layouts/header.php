<?php

// Đảm bảo User đã được load trước khi đọc session
require_once __DIR__ . "/../../../models/User.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$user = $_SESSION["user"] ?? null;

?>

<header class="admin-header bg-white border-bottom shadow-sm">

    <div class="container-fluid d-flex justify-content-between align-items-center px-4 h-100">

        <!-- LOGO -->

        <h4 class="mb-0 fw-bold text-primary">

            <i class="fa-solid fa-store me-2"></i>

            Mini Shop Admin

        </h4>


        <!-- USER -->

        <div class="d-flex align-items-center gap-3">

            <?php if ($user): ?>

                <div class="d-flex align-items-center">

                    <i class="fa-solid fa-circle-user fs-3 me-2 text-secondary"></i>

                    <div>

                        <div class="fw-semibold">

                            <?= htmlspecialchars($user->fullname ?? "Người dùng") ?>

                        </div>

                        <small class="text-muted">

                            <?= htmlspecialchars($user->username ?? "") ?>

                        </small>

                    </div>

                </div>


                <!-- LOGOUT -->

                <a
                    href="logout.php"
                    class="btn btn-outline-danger btn-sm"
                >

                    <i class="fa-solid fa-right-from-bracket me-1"></i>

                    Đăng xuất

                </a>

            <?php endif; ?>

        </div>

    </div>

</header>