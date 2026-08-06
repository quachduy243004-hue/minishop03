<?php
$pageTitle = "Dashboard";
ob_start();
?>

<div class="container-fluid mt-4">

    <div class="row">

        <div class="col-md-3">
            <div class="card card-box">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h5>Khách hàng</h5>
                    </div>
                    <i class="fa fa-users text-primary card-icon"></i>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card card-box">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h5>Sản phẩm</h5>
                    </div>
                    <i class="fa fa-cart-shopping text-success card-icon"></i>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card card-box">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h5>Đơn hàng</h5>
                    </div>
                    <i class="fa fa-truck text-warning card-icon"></i>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card card-box">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h5>Người dùng</h5>
                    </div>
                    <i class="fa fa-user text-danger card-icon"></i>
                </div>
            </div>
        </div>

    </div>

    <div class="row mt-4">

        <div class="col-12">

            <div class="table-box">

                <h4 class="mb-3">Đơn hàng mới nhất</h4>

                <table class="table table-bordered table-hover">

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
                            <td>DH001</td>
                            <td>Nguyễn Văn A</td>
                            <td>20/05/2024</td>
                            <td>1.250.000 đ</td>
                            <td><span class="badge bg-warning">Chờ xử lý</span></td>
                        </tr>

                        <tr>
                            <td>2</td>
                            <td>DH002</td>
                            <td>Trần Thị B</td>
                            <td>20/05/2024</td>
                            <td>850.000 đ</td>
                            <td><span class="badge bg-primary">Đã xác nhận</span></td>
                        </tr>

                        <tr>
                            <td>3</td>
                            <td>DH003</td>
                            <td>Lê Văn C</td>
                            <td>19/05/2024</td>
                            <td>2.150.000 đ</td>
                            <td><span class="badge bg-info">Đang giao</span></td>
                        </tr>

                        <tr>
                            <td>4</td>
                            <td>DH004</td>
                            <td>Phạm Thị D</td>
                            <td>19/05/2024</td>
                            <td>950.000 đ</td>
                            <td><span class="badge bg-success">Hoàn thành</span></td>
                        </tr>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

<?php
$content = ob_get_clean();
include "layouts/master.php";
?>