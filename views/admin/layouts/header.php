<?php

$user = $_SESSION["user"] ?? null;

$fullname = "Người dùng";
$username = "";

if ($user instanceof \Models\User) {

    $fullname = $user->fullname ?? "Người dùng";
    $username = $user->username ?? "";

} elseif (is_array($user)) {

    $fullname = $user["fullname"] ?? "Người dùng";
    $username = $user["username"] ?? "";
}

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

                            <?= htmlspecialchars($fullname) ?>

                        </div>

                        <small class="text-muted">

                            <?= htmlspecialchars($username) ?>

                        </small>

                    </div>

                </div>


                <!-- LOGOUT -->

                <a
                    href="/MiniShop_quachvanduy/admin/logout"
                    class="btn btn-outline-danger btn-sm"
                >

                    <i class="fa-solid fa-right-from-bracket me-1"></i>

                    Đăng xuất

                </a>

            <?php else: ?>

                <a
                    href="/MiniShop_quachvanduy/admin/login"
                    class="btn btn-outline-primary btn-sm"
                >

                    Đăng nhập

                </a>

            <?php endif; ?>

        </div>

    </div>

</header>