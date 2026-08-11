<?php

require_once __DIR__ . "/../../../dao/ProductDAO.php";
require_once __DIR__ . "/../../../dao/CategoryDAO.php";
require_once __DIR__ . "/../../../dao/BrandDAO.php";
require_once __DIR__ . "/../../../models/Product.php";

$pageTitle = "Quản lý sản phẩm";

$productDAO = new ProductDAO();

$message = "";

// ======================================================
// XÓA SẢN PHẨM
// ======================================================

if (isset($_POST["btnDelete"])) {

    $id = intval($_POST["id"]);


    if ($productDAO->delete($id)) {

        header("Location: index.php");
        exit;

    } else {

        $message = "Xóa sản phẩm thất bại!";
    }
}


// ======================================================
// LẤY KEYWORD
// ======================================================

$keyword = trim($_GET["keyword"] ?? "");


// ======================================================
// LẤY LIMIT
// ======================================================

$limit = (int)($_GET["limit"] ?? 10);


// Chỉ cho phép 10, 20, 30

if (!in_array($limit, [10, 20, 30])) {

    $limit = 10;
}


// ======================================================
// LẤY PAGE
// ======================================================

$page = (int)($_GET["page"] ?? 1);


if ($page < 1) {

    $page = 1;
}


// ======================================================
// LẤY SORT
// ======================================================

$sort = $_GET["sort"] ?? "name_asc";


// Các kiểu sort được phép

$allowedSort = [

    "name_asc",
    "name_desc",
    "price_asc",
    "price_desc"

];


if (!in_array($sort, $allowedSort)) {

    $sort = "name_asc";
}


// ======================================================
// ĐẾM TỔNG SẢN PHẨM
// ======================================================

$totalRecords = $productDAO->count(
    "products",
    "proname",
    $keyword
);


// ======================================================
// TÍNH TỔNG SỐ TRANG
// ======================================================

$totalPages = (int)ceil(
    $totalRecords / $limit
);


// ======================================================
// NẾU PAGE VƯỢT QUÁ TỔNG TRANG
// ======================================================

if ($totalPages > 0 && $page > $totalPages) {

    $page = $totalPages;
}


// ======================================================
// OFFSET
// ======================================================

$offset = ($page - 1) * $limit;


// ======================================================
// LẤY DANH SÁCH
// ======================================================

$list = $productDAO->getPage(

    $limit,
    $offset,
    $keyword,
    $sort

);


ob_start();

?>


<div class="container-fluid">


    <!-- ==================================================
         CARD
    ================================================== -->

    <div class="card shadow">


        <!-- ==================================================
             HEADER
        ================================================== -->

        <div class="card-header bg-white">


            <div class="d-flex justify-content-between align-items-center">


                <h4 class="mb-0">

                    Quản lý sản phẩm

                </h4>


                <a
                    href="create.php"
                    class="btn btn-primary btn-sm">

                    <i class="fa fa-plus-circle"></i>

                    Thêm sản phẩm

                </a>


            </div>

        </div>


        <!-- ==================================================
             BODY
        ================================================== -->

        <div class="card-body">


            <!-- ==================================================
                 TÌM KIẾM + SẮP XẾP
            ================================================== -->

            <div class="row mb-3">


                <!-- =========================
                     TÌM KIẾM
                ========================== -->

                <div class="col-md-5">


                    <form
                        method="GET"
                        class="d-flex">


                        <input
                            type="text"
                            name="keyword"
                            value="<?= htmlspecialchars($keyword) ?>"
                            class="form-control"
                            placeholder="Nhập tên sản phẩm...">


                        <!-- Giữ limit -->

                        <input
                            type="hidden"
                            name="limit"
                            value="<?= $limit ?>">


                        <!-- Giữ sort -->

                        <input
                            type="hidden"
                            name="sort"
                            value="<?= htmlspecialchars($sort) ?>">


                        <button
                            type="submit"
                            class="btn btn-success ms-2">

                            <i class="fa fa-search"></i>

                            Tìm

                        </button>


                    </form>


                </div>


                <!-- =========================
                     SẮP XẾP
                ========================== -->

                <div class="col-md-3">


                    <form method="GET">


                        <!-- Giữ keyword -->

                        <input
                            type="hidden"
                            name="keyword"
                            value="<?= htmlspecialchars($keyword) ?>">


                        <!-- Giữ limit -->

                        <input
                            type="hidden"
                            name="limit"
                            value="<?= $limit ?>">


                        <select
                            name="sort"
                            class="form-select"
                            onchange="this.form.submit()">


                            <option
                                value="name_asc"
                                <?= $sort == "name_asc" ? "selected" : "" ?>>

                                Tên A-Z

                            </option>


                            <option
                                value="name_desc"
                                <?= $sort == "name_desc" ? "selected" : "" ?>>

                                Tên Z-A

                            </option>


                            <option
                                value="price_asc"
                                <?= $sort == "price_asc" ? "selected" : "" ?>>

                                Giá thấp → cao

                            </option>


                            <option
                                value="price_desc"
                                <?= $sort == "price_desc" ? "selected" : "" ?>>

                                Giá cao → thấp

                            </option>


                        </select>


                    </form>


                </div>


                <!-- =========================
                     LÀM MỚI
                ========================== -->

                <div class="col-md-2">


                    <a
                        href="index.php"
                        class="btn btn-secondary">


                        <i class="fa fa-refresh"></i>

                        Làm mới


                    </a>


                </div>


            </div>


            <!-- ==================================================
                 THÔNG BÁO
            ================================================== -->

            <?php if (!empty($message)): ?>


                <div class="alert alert-danger">

                    <?= htmlspecialchars($message) ?>

                </div>


            <?php endif; ?>


            <!-- ==================================================
                 THÔNG BÁO TÌM KIẾM
            ================================================== -->

            <?php if ($keyword != "" && $totalRecords == 0): ?>


                <div class="alert alert-warning">

                    <i class="fa fa-info-circle"></i>

                    Không tìm thấy sản phẩm
                    với từ khóa:

                    <strong>
                        <?= htmlspecialchars($keyword) ?>
                    </strong>

                </div>


            <?php endif; ?>


            <!-- ==================================================
                 TABLE
            ================================================== -->

            <div class="table-responsive">


                <table
                    class="table table-bordered table-hover align-middle">


                    <!-- ==================================================
                         THEAD
                    ================================================== -->

                    <thead
                        class="table-dark text-center">


                        <tr>


                            <th width="60">
                                STT
                            </th>


                            <th width="90">
                                Hình ảnh
                            </th>


                            <th>
                                Tên sản phẩm
                            </th>


                            <th>
                                Danh mục
                            </th>


                            <th>
                                Thương hiệu
                            </th>


                            <th width="120">
                                Giá
                            </th>


                            <th width="120">
                                Giảm giá
                            </th>


                            <th width="70">
                                SL
                            </th>


                            <th width="120">
                                Trạng thái
                            </th>


                            <th width="170">
                                Ngày tạo
                            </th>


                            <th width="170">
                                Chức năng
                            </th>


                        </tr>


                    </thead>


                    <!-- ==================================================
                         TBODY
                    ================================================== -->

                    <tbody>


                        <?php if (!empty($list)): ?>


                            <?php

                            /*
                             * STT phải tính theo page
                             *
                             * Page 1:
                             * 1 -> 10
                             *
                             * Page 2:
                             * 11 -> 20
                             *
                             */

                            $stt = $offset + 1;

                            ?>


                            <?php foreach ($list as $item): ?>


                                <tr>


                                    <!-- STT -->

                                    <td class="text-center">

                                        <?= $stt++ ?>

                                    </td>


                                    <!-- HÌNH -->

                                    <td class="text-center">


                                        <?php if (!empty($item->image)): ?>


                                            <img
                                                src="../../../uploads/products/<?= htmlspecialchars($item->image) ?>"
                                                width="60"
                                                height="60"
                                                class="img-thumbnail"
                                                style="object-fit: cover;">


                                        <?php else: ?>


                                            <span class="text-muted">

                                                No Image

                                            </span>


                                        <?php endif; ?>


                                    </td>


                                    <!-- TÊN -->

                                    <td>

                                        <?= htmlspecialchars(
                                            $item->proname ?? ""
                                        ) ?>

                                    </td>


                                    <!-- DANH MỤC -->

                                    <td>

                                        <?= htmlspecialchars(
                                            $item->categoryName ?? ""
                                        ) ?>

                                    </td>


                                    <!-- THƯƠNG HIỆU -->

                                    <td>

                                        <?= htmlspecialchars(
                                            $item->brandName ?? ""
                                        ) ?>

                                    </td>


                                    <!-- GIÁ -->

                                    <td class="text-end">

                                        <?= number_format(
                                            $item->price ?? 0,
                                            0,
                                            ",",
                                            "."
                                        ) ?>

                                    </td>


                                    <!-- GIẢM GIÁ -->

                                    <td class="text-end">

                                        <?= number_format(
                                            $item->discountPrice ?? 0,
                                            0,
                                            ",",
                                            "."
                                        ) ?>

                                    </td>


                                    <!-- SỐ LƯỢNG -->

                                    <td class="text-center">

                                        <?= $item->quantity ?? 0 ?>

                                    </td>


                                    <!-- TRẠNG THÁI -->

                                    <td class="text-center">


                                        <?php if (($item->status ?? 0) == 1): ?>


                                            <span class="badge bg-success">

                                                Hiển thị

                                            </span>


                                        <?php else: ?>


                                            <span class="badge bg-danger">

                                                Ẩn

                                            </span>


                                        <?php endif; ?>


                                    </td>


                                    <!-- NGÀY TẠO -->

                                    <td class="text-center">

                                        <?= htmlspecialchars(
                                            $item->createdAt ?? ""
                                        ) ?>

                                    </td>


                                    <!-- CHỨC NĂNG -->

                                    <td class="text-center">


                                        <!-- XEM -->

                                        <a
                                            href="detail.php?id=<?= $item->id ?>"
                                            class="btn btn-info btn-sm">

                                            <i class="fa fa-eye"></i>

                                        </a>


                                        <!-- SỬA -->

                                        <a
                                            href="edit.php?id=<?= $item->id ?>"
                                            class="btn btn-warning btn-sm">

                                            <i class="fa fa-edit"></i>

                                        </a>


                                        <!-- XÓA -->

                                        <form
                                            method="POST"
                                            style="display:inline-block"
                                            onsubmit="return confirm('Bạn có chắc muốn xóa?');">


                                            <input
                                                type="hidden"
                                                name="id"
                                                value="<?= $item->id ?>">


                                            <button
                                                type="submit"
                                                name="btnDelete"
                                                class="btn btn-danger btn-sm">


                                                <i class="fa fa-trash"></i>


                                            </button>


                                        </form>


                                    </td>


                                </tr>


                            <?php endforeach; ?>


                        <?php else: ?>


                            <tr>


                                <td
                                    colspan="11"
                                    class="text-center text-danger py-4">


                                    <?php if ($keyword != ""): ?>


                                        Không tìm thấy sản phẩm.


                                    <?php else: ?>


                                        Không có dữ liệu.


                                    <?php endif; ?>


                                </td>


                            </tr>


                        <?php endif; ?>


                    </tbody>


                </table>


            </div>


            <!-- ==================================================
                 PHÂN TRANG + LIMIT
            ================================================== -->

            <?php if ($totalRecords > 0): ?>


                <div
                    class="d-flex justify-content-between align-items-center mt-3">


                    <!-- =========================
                         CHỌN SỐ SP / TRANG
                    ========================== -->

                    <div
                        class="d-flex align-items-center">


                        <label class="me-2 mb-0">

                            Hiển thị:

                        </label>


                        <form method="GET">


                            <!-- Giữ keyword -->

                            <input
                                type="hidden"
                                name="keyword"
                                value="<?= htmlspecialchars($keyword) ?>">


                            <!-- Giữ sort -->

                            <input
                                type="hidden"
                                name="sort"
                                value="<?= htmlspecialchars($sort) ?>">


                            <select
                                name="limit"
                                class="form-select"
                                onchange="this.form.submit()"
                                style="width:90px;">


                                <option
                                    value="10"
                                    <?= $limit == 10 ? "selected" : "" ?>>

                                    10

                                </option>


                                <option
                                    value="20"
                                    <?= $limit == 20 ? "selected" : "" ?>>

                                    20

                                </option>


                                <option
                                    value="30"
                                    <?= $limit == 30 ? "selected" : "" ?>>

                                    30

                                </option>


                            </select>


                        </form>


                    </div>


                    <!-- =========================
                         PHÂN TRANG
                    ========================== -->

                    <?php if ($totalPages > 1): ?>


                        <nav>


                            <ul class="pagination mb-0">


                                <!-- TRANG TRƯỚC -->

                                <li
                                    class="page-item <?= $page <= 1 ? "disabled" : "" ?>">


                                    <?php if ($page > 1): ?>


                                        <a
                                            class="page-link"
                                            href="?keyword=<?= urlencode($keyword) ?>&limit=<?= $limit ?>&sort=<?= urlencode($sort) ?>&page=<?= $page - 1 ?>">

                                            ← Trước

                                        </a>


                                    <?php else: ?>


                                        <span class="page-link">

                                            ← Trước

                                        </span>


                                    <?php endif; ?>


                                </li>


                                <!-- CÁC TRANG -->

                                <?php for (
                                    $i = 1;
                                    $i <= $totalPages;
                                    $i++
                                ): ?>


                                    <li
                                        class="page-item <?= $i == $page ? "active" : "" ?>">


                                        <a
                                            class="page-link"
                                            href="?keyword=<?= urlencode($keyword) ?>&limit=<?= $limit ?>&sort=<?= urlencode($sort) ?>&page=<?= $i ?>">

                                            <?= $i ?>

                                        </a>


                                    </li>


                                <?php endfor; ?>


                                <!-- TRANG SAU -->

                                <li
                                    class="page-item <?= $page >= $totalPages ? "disabled" : "" ?>">


                                    <?php if ($page < $totalPages): ?>


                                        <a
                                            class="page-link"
                                            href="?keyword=<?= urlencode($keyword) ?>&limit=<?= $limit ?>&sort=<?= urlencode($sort) ?>&page=<?= $page + 1 ?>">

                                            Sau →

                                        </a>


                                    <?php else: ?>


                                        <span class="page-link">

                                            Sau →

                                        </span>


                                    <?php endif; ?>


                                </li>


                            </ul>


                        </nav>


                    <?php endif; ?>


                </div>


            <?php endif; ?>


        </div>


    </div>


</div>


<?php

$content = ob_get_clean();

include "../layouts/master.php";

?>