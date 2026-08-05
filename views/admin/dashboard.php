<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mini Shop Admin</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #f5f6fa;
        }

        .sidebar {
            width: 250px;
            height: 100vh;
            position: fixed;
            background: #fff;
            border-right: 1px solid #ddd;
        }

        .logo {
            padding: 18px;
            font-size: 22px;
            font-weight: bold;
            color: #0d6efd;
            border-bottom: 1px solid #ddd;
        }

        .sidebar ul {
            list-style: none;
            padding: 20px 0;
        }

        .sidebar ul li a {
            display: block;
            padding: 12px 25px;
            color: #333;
            text-decoration: none;
            transition: .3s;
        }

        .sidebar ul li a:hover,
        .sidebar ul li a.active {
            background: #e7eaed;
            color: #fff;
        }

        .content {
            margin-left: 250px;
        }

        .topbar {
            height: 70px;
            background: #fff;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 30px;
            border-bottom: 1px solid #ddd;
        }

        .card-box {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .1);
        }

        .card-icon {
            font-size: 35px;
        }

        .table-box {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .1);
        }
    </style>
</head>

<body>

    <div class="sidebar">

        <div class="logo">
            <i class="fa-solid fa-cart-shopping"></i>
            MINI SHOP 03
        </div>

        <ul>

            <li><a class="active" href="#"><i class="fa fa-home"></i> Dashboard</a></li>

            <li><a href="#"><i class="fa fa-list"></i> Danh mục</a></li>

            <li><a href="#"><i class="fa fa-tags"></i> Thương hiệu</a></li>

            <li><a href="#"><i class="fa fa-box"></i> Sản phẩm</a></li>

            <li><a href="#"><i class="fa fa-users"></i> Khách hàng</a></li>

            <li><a href="#"><i class="fa fa-user"></i> Người dùng</a></li>

            <li><a href="#"><i class="fa fa-cart-arrow-down"></i> Đơn hàng</a></li>

            <li><a href="#"><i class="fa fa-chart-column"></i> Báo cáo</a></li>

            <li><a href="#"><i class="fa fa-sign-out-alt"></i> Đăng xuất</a></li>

        </ul>

    </div>


    <div class="content">

        <div class="topbar">

            <h4>Dashboard</h4>

            <div>
                <i class="fa fa-cog"></i>


                <strong>Admin</strong>
            </div>

        </div>

        <div class="container-fluid mt-4">

            <div class="row">

                <div class="col-md-3">

                    <div class="card card-box">
                        <div class="card-body d-flex justify-content-between align-items-center">

                            <div>
                                <p>Khách hàng</p>
                            </div>

                            <i class="fa fa-users text-primary card-icon"></i>

                        </div>
                    </div>

                </div>


                <div class="col-md-3">

                    <div class="card card-box">

                        <div class="card-body d-flex justify-content-between align-items-center">

                            <div>
                                <p>Sản phẩm</p>
                            </div>

                            <i class="fa fa-cart-shopping text-success card-icon"></i>

                        </div>

                    </div>

                </div>


                <div class="col-md-3">

                    <div class="card card-box">

                        <div class="card-body d-flex justify-content-between align-items-center">

                            <div>
                                <p>Đơn hàng</p>
                            </div>

                            <i class="fa fa-truck text-warning card-icon"></i>

                        </div>

                    </div>

                </div>


                <div class="col-md-3">

                    <div class="card card-box">

                        <div class="card-body d-flex justify-content-between align-items-center">

                            <div>
                                <p>Người dùng</p>
                            </div>

                            <i class="fa fa-user text-danger card-icon"></i>

                        </div>

                    </div>

                </div>

            </div>


            <div class="row mt-4">

                <div class="col-12">

                    <div class="table-box">

                        <h5 class="mb-3">Đơn hàng mới nhất</h5>

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

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>