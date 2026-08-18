<?php

// ========================================
// LOAD CLASS USER TRƯỚC SESSION
// ========================================

use Middleware\AuthMiddleware;

require_once __DIR__ . "/../../models/User.php";
require_once __DIR__ . "/../../middleware/AuthMiddleware.php";

// ========================================
// SESSION
// ========================================

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ========================================
// AUTH MIDDLEWARE
// ========================================

AuthMiddleware::handle();

// ========================================
// USER SESSION
// ========================================

$user = $_SESSION["user"] ?? null;

// Nếu không có user thì về login
if (!$user) {
    header("Location: login.php");
    exit;
}

$pageTitle = "Dashboard";

ob_start();

?>

<div class="container-fluid mt-4">

    <!-- ================================
         THÔNG TIN TỔNG QUAN
    ================================= -->

    <div class="row">

        <!-- KHÁCH HÀNG -->

        <div class="col-md-3 mb-3">

            <div class="card shadow-sm">

                <div class="card-body d-flex justify-content-between align-items-center">

                    <div>

                        <h5 class="mb-1">
                            Khách hàng
                        </h5>

                        <small class="text-muted">
                            Quản lý khách hàng
                        </small>

                    </div>

                    <i class="fa fa-users text-primary fs-2"></i>

                </div>

            </div>

        </div>


        <!-- SẢN PHẨM -->

        <div class="col-md-3 mb-3">

            <div class="card shadow-sm">

                <div class="card-body d-flex justify-content-between align-items-center">

                    <div>

                        <h5 class="mb-1">
                            Sản phẩm
                        </h5>

                        <small class="text-muted">
                            Quản lý sản phẩm
                        </small>

                    </div>

                    <i class="fa fa-cart-shopping text-success fs-2"></i>

                </div>

            </div>

        </div>


        <!-- ĐƠN HÀNG -->

        <div class="col-md-3 mb-3">

            <div class="card shadow-sm">

                <div class="card-body d-flex justify-content-between align-items-center">

                    <div>

                        <h5 class="mb-1">
                            Đơn hàng
                        </h5>

                        <small class="text-muted">
                            Quản lý đơn hàng
                        </small>

                    </div>

                    <i class="fa fa-truck text-warning fs-2"></i>

                </div>

            </div>

        </div>


        <!-- NGƯỜI DÙNG -->

        <div class="col-md-3 mb-3">

            <div class="card shadow-sm">

                <div class="card-body d-flex justify-content-between align-items-center">

                    <div>

                        <h5 class="mb-1">
                            Người dùng
                        </h5>

                        <small class="text-muted">
                            Quản lý tài khoản
                        </small>

                    </div>

                    <i class="fa fa-user text-danger fs-2"></i>

                </div>

            </div>

        </div>

    </div>


    <!-- ================================
         THÔNG TIN USER
    ================================= -->

    <div class="row mt-2">

        <div class="col-12">

            <div class="alert alert-success">

                <i class="fa fa-circle-check me-2"></i>

                Xin chào,

                <strong>
                    <?= htmlspecialchars($user->fullname ?? '') ?>
                </strong>

                <span class="ms-2">
                    (<?= htmlspecialchars($user->username ?? '') ?>)
                </span>

                <?php if ((int)($user->role ?? 0) === 1): ?>

                    <span class="badge bg-danger ms-2">
                        Quản trị viên
                    </span>

                <?php else: ?>

                    <span class="badge bg-primary ms-2">
                        Nhân viên
                    </span>

                <?php endif; ?>

            </div>

        </div>

    </div>


    <!-- ================================
         ĐƠN HÀNG
    ================================= -->

    <div class="row mt-3">

        <div class="col-12">

            <div class="card shadow-sm">

                <div class="card-header bg-white">

                    <h4 class="mb-0">
                        Đơn hàng mới nhất
                    </h4>

                </div>

                <div class="card-body">

                    <div class="table-responsive">

                        <table class="table table-bordered table-hover align-middle">

                            <thead class="table-light">

                                <tr>

                                    <th>#</th>
                                    <th>Mã đơn</th>
                                    <th>Khách hàng</th>
                                    <th>Ngày đặt</th>
                                    <th>Tổng tiền</th>
                                    <th>Trạng thái</th>

                                </tr>

                            </thead>

                            <tbody>

                                <tr>

                                    <td>1</td>

                                    <td>
                                        <strong>DH001</strong>
                                    </td>

                                    <td>
                                        Nguyễn Văn A
                                    </td>

                                    <td>
                                        20/05/2024
                                    </td>

                                    <td>
                                        1.250.000 đ
                                    </td>

                                    <td>
                                        <span class="badge bg-warning text-dark">
                                            Chờ xử lý
                                        </span>
                                    </td>

                                </tr>


                                <tr>

                                    <td>2</td>

                                    <td>
                                        <strong>DH002</strong>
                                    </td>

                                    <td>
                                        Trần Thị B
                                    </td>

                                    <td>
                                        20/05/2024
                                    </td>

                                    <td>
                                        850.000 đ
                                    </td>

                                    <td>
                                        <span class="badge bg-primary">
                                            Đã xác nhận
                                        </span>
                                    </td>

                                </tr>


                                <tr>

                                    <td>3</td>

                                    <td>
                                        <strong>DH003</strong>
                                    </td>

                                    <td>
                                        Lê Văn C
                                    </td>

                                    <td>
                                        19/05/2024
                                    </td>

                                    <td>
                                        2.150.000 đ
                                    </td>

                                    <td>
                                        <span class="badge bg-info text-dark">
                                            Đang giao
                                        </span>
                                    </td>

                                </tr>


                                <tr>

                                    <td>4</td>

                                    <td>
                                        <strong>DH004</strong>
                                    </td>

                                    <td>
                                        Phạm Thị D
                                    </td>

                                    <td>
                                        19/05/2024
                                    </td>

                                    <td>
                                        950.000 đ
                                    </td>

                                    <td>
                                        <span class="badge bg-success">
                                            Hoàn thành
                                        </span>
                                    </td>

                                </tr>

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<?php

$content = ob_get_clean();

require __DIR__ . "/layouts/master.php";