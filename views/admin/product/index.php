<?php

/*
|--------------------------------------------------------------------------
| NHẬN DỮ LIỆU TỪ CONTROLLER
|--------------------------------------------------------------------------
*/

$list = $list ?? [];

$keyword = $keyword ?? "";

$limit = $limit ?? 10;

$page = $page ?? 1;

$sort = $sort ?? "name_asc";

$totalRecords = $totalRecords ?? 0;

$totalPages = $totalPages ?? 0;

$offset = $offset ?? 0;

$message = $message ?? "";


/*
|--------------------------------------------------------------------------
| CSRF
|--------------------------------------------------------------------------
*/

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$csrfToken = $_SESSION["csrf_token"] ?? "";


/*
|--------------------------------------------------------------------------
| TITLE
|--------------------------------------------------------------------------
*/

$pageTitle = $pageTitle ?? "Quản lý sản phẩm";


ob_start();

?>

<div class="container-fluid">


    <!-- ==========================================================
         HEADER
    =========================================================== -->

    <div class="d-flex justify-content-between align-items-center mb-3">

        <div>

            <h3 class="mb-0">
                Quản lý sản phẩm
            </h3>

            <small class="text-muted">
                Danh sách sản phẩm
            </small>

        </div>


        <a
            href="/MiniShop_quachvanduy/admin/product/create"
            class="btn btn-primary"
        >

            <i class="fa fa-plus-circle"></i>

            Thêm sản phẩm

        </a>

    </div>



    <!-- ==========================================================
         SEARCH + SORT
    =========================================================== -->

    <div class="card shadow-sm mb-3">

        <div class="card-body">

            <div class="row g-2">


                <!-- SEARCH -->

                <div class="col-md-6">

                    <form method="GET">

                        <div class="input-group">

                            <input
                                type="text"
                                name="keyword"
                                value="<?= htmlspecialchars($keyword) ?>"
                                class="form-control"
                                placeholder="Tìm kiếm sản phẩm..."
                            >


                            <input
                                type="hidden"
                                name="limit"
                                value="<?= (int)$limit ?>"
                            >


                            <input
                                type="hidden"
                                name="sort"
                                value="<?= htmlspecialchars($sort) ?>"
                            >


                            <button
                                type="submit"
                                class="btn btn-primary"
                            >

                                <i class="fa fa-search"></i>

                                Tìm kiếm

                            </button>

                        </div>

                    </form>

                </div>



                <!-- SORT -->

                <div class="col-md-3">

                    <form method="GET">

                        <input
                            type="hidden"
                            name="keyword"
                            value="<?= htmlspecialchars($keyword) ?>"
                        >


                        <input
                            type="hidden"
                            name="limit"
                            value="<?= (int)$limit ?>"
                        >


                        <select
                            name="sort"
                            class="form-select"
                            onchange="this.form.submit()"
                        >

                            <option
                                value="name_asc"
                                <?= $sort === "name_asc" ? "selected" : "" ?>
                            >

                                Tên A - Z

                            </option>


                            <option
                                value="name_desc"
                                <?= $sort === "name_desc" ? "selected" : "" ?>
                            >

                                Tên Z - A

                            </option>


                            <option
                                value="price_asc"
                                <?= $sort === "price_asc" ? "selected" : "" ?>
                            >

                                Giá thấp → cao

                            </option>


                            <option
                                value="price_desc"
                                <?= $sort === "price_desc" ? "selected" : "" ?>
                            >

                                Giá cao → thấp

                            </option>

                        </select>

                    </form>

                </div>



                <!-- REFRESH -->

                <div class="col-md-3">

                    <a
                        href="/MiniShop_quachvanduy/admin/product"
                        class="btn btn-secondary"
                    >

                        <i class="fa fa-refresh"></i>

                        Làm mới

                    </a>

                </div>

            </div>

        </div>

    </div>



    <!-- ==========================================================
         MESSAGE
    =========================================================== -->

    <?php if (!empty($message)): ?>

        <div class="alert alert-danger">

            <i class="fa fa-exclamation-circle"></i>

            <?= htmlspecialchars($message) ?>

        </div>

    <?php endif; ?>



    <!-- ==========================================================
         SEARCH RESULT
    =========================================================== -->

    <?php if ($keyword !== "" && $totalRecords === 0): ?>

        <div class="alert alert-warning">

            <i class="fa fa-info-circle"></i>

            Không tìm thấy sản phẩm với từ khóa:

            <strong>
                <?= htmlspecialchars($keyword) ?>
            </strong>

        </div>

    <?php endif; ?>



    <!-- ==========================================================
         PRODUCT TABLE
    =========================================================== -->

    <div class="card shadow-sm">


        <!-- HEADER -->

        <div class="card-header bg-white">

            <div class="d-flex justify-content-between align-items-center">

                <strong>
                    Danh sách sản phẩm
                </strong>


                <?php if ($totalRecords > 0): ?>

                    <span class="text-muted small">

                        Tổng:
                        <strong>
                            <?= (int)$totalRecords ?>
                        </strong>
                        sản phẩm

                    </span>

                <?php endif; ?>

            </div>

        </div>



        <!-- BODY -->

        <div class="card-body p-0">

            <div class="table-responsive">

                <table
                    class="table table-bordered table-hover align-middle mb-0"
                >

                    <thead class="table-light text-center">

                        <tr>

                            <th width="60">
                                STT
                            </th>

                            <th width="100">
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

                            <th width="130">
                                Giá
                            </th>

                            <th width="130">
                                Giá KM
                            </th>

                            <th width="80">
                                SL
                            </th>

                            <th width="120">
                                Trạng thái
                            </th>

                            <th width="170">
                                Ngày tạo
                            </th>

                            <th width="150">
                                Thao tác
                            </th>

                        </tr>

                    </thead>



                    <tbody>


                        <?php if (!empty($list)): ?>


                            <?php

                            $stt = $offset + 1;

                            ?>


                            <?php foreach ($list as $item): ?>

                                <tr>


                                    <!-- STT -->

                                    <td class="text-center">

                                        <?= $stt++ ?>

                                    </td>



                                    <!-- IMAGE -->

                                    <td class="text-center">

                                        <?php if (!empty($item->image)): ?>

                                            <img
                                                src="/MiniShop_quachvanduy/uploads/products/<?= htmlspecialchars($item->image) ?>"
                                                alt="<?= htmlspecialchars($item->proname ?? "") ?>"
                                                width="60"
                                                height="60"
                                                class="img-thumbnail"
                                                style="object-fit: cover;"
                                            >

                                        <?php else: ?>

                                            <span class="text-muted small">

                                                Không có ảnh

                                            </span>

                                        <?php endif; ?>

                                    </td>



                                    <!-- NAME -->

                                    <td>

                                        <strong>

                                            <?= htmlspecialchars(
                                                $item->proname ?? ""
                                            ) ?>

                                        </strong>

                                        <?php if (!empty($item->slug)): ?>

                                            <br>

                                            <small class="text-muted">

                                                <?= htmlspecialchars(
                                                    $item->slug
                                                ) ?>

                                            </small>

                                        <?php endif; ?>

                                    </td>



                                    <!-- CATEGORY -->

                                    <td>

                                        <?= htmlspecialchars(
                                            $item->categoryName ?? ""
                                        ) ?>

                                    </td>



                                    <!-- BRAND -->

                                    <td>

                                        <?= htmlspecialchars(
                                            $item->brandName ?? ""
                                        ) ?>

                                    </td>



                                    <!-- PRICE -->

                                    <td class="text-end">

                                        <strong>

                                            <?= number_format(
                                                (float)($item->price ?? 0),
                                                0,
                                                ",",
                                                "."
                                            ) ?>

                                            đ

                                        </strong>

                                    </td>



                                    <!-- DISCOUNT PRICE -->

                                    <td class="text-end">

                                        <?php if (
                                            (float)($item->discountPrice ?? 0) > 0
                                        ): ?>

                                            <span
                                                class="text-danger fw-bold"
                                            >

                                                <?= number_format(
                                                    (float)$item->discountPrice,
                                                    0,
                                                    ",",
                                                    "."
                                                ) ?>

                                                đ

                                            </span>

                                        <?php else: ?>

                                            <span class="text-muted">

                                                -

                                            </span>

                                        <?php endif; ?>

                                    </td>



                                    <!-- QUANTITY -->

                                    <td class="text-center">

                                        <?= (int)(
                                            $item->quantity ?? 0
                                        ) ?>

                                    </td>



                                    <!-- STATUS -->

                                    <td class="text-center">

                                        <?php if (
                                            (int)($item->status ?? 0) === 1
                                        ): ?>

                                            <span
                                                class="badge bg-success"
                                            >

                                                Hiển thị

                                            </span>

                                        <?php else: ?>

                                            <span
                                                class="badge bg-secondary"
                                            >

                                                Ẩn

                                            </span>

                                        <?php endif; ?>

                                    </td>



                                    <!-- CREATED -->

                                    <td class="text-center">

                                        <small>

                                            <?= htmlspecialchars(
                                                $item->createdAt ?? ""
                                            ) ?>

                                        </small>

                                    </td>



                                    <!-- ACTION -->

                                    <td class="text-center">

                                        <div
                                            class="d-flex justify-content-center gap-1"
                                        >


                                            <!-- DETAIL -->

                                            <a
                                                href="/MiniShop_quachvanduy/admin/product/detail?id=<?= (int)$item->id ?>"
                                                class="btn btn-info btn-sm"
                                                title="Xem chi tiết"
                                            >

                                                <i class="fa fa-eye"></i>

                                            </a>



                                            <!-- EDIT -->

                                            <a
                                                href="/MiniShop_quachvanduy/admin/product/edit?id=<?= (int)$item->id ?>"
                                                class="btn btn-warning btn-sm"
                                                title="Sửa"
                                            >

                                                <i class="fa fa-edit"></i>

                                            </a>



                                            <!-- DELETE -->

                                            <form
                                                method="POST"
                                                action="/MiniShop_quachvanduy/admin/product/delete"
                                                class="d-inline"
                                                onsubmit="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này không?');"
                                            >

                                                <input
                                                    type="hidden"
                                                    name="id"
                                                    value="<?= (int)$item->id ?>"
                                                >


                                                <input
                                                    type="hidden"
                                                    name="csrf_token"
                                                    value="<?= htmlspecialchars($csrfToken) ?>"
                                                >


                                                <button
                                                    type="submit"
                                                    class="btn btn-danger btn-sm"
                                                    title="Xóa"
                                                >

                                                    <i class="fa fa-trash"></i>

                                                </button>

                                            </form>

                                        </div>

                                    </td>

                                </tr>

                            <?php endforeach; ?>


                        <?php else: ?>


                            <tr>

                                <td
                                    colspan="11"
                                    class="text-center text-muted py-5"
                                >

                                    <i
                                        class="fa fa-inbox fa-2x mb-2 d-block"
                                    ></i>


                                    <?php if ($keyword !== ""): ?>

                                        Không tìm thấy sản phẩm.

                                    <?php else: ?>

                                        Chưa có sản phẩm nào.

                                    <?php endif; ?>

                                </td>

                            </tr>


                        <?php endif; ?>


                    </tbody>

                </table>

            </div>

        </div>



        <!-- ======================================================
             FOOTER
        ======================================================= -->

        <?php if ($totalRecords > 0): ?>

            <div class="card-footer bg-white">

                <div
                    class="d-flex justify-content-between align-items-center flex-wrap gap-2"
                >


                    <!-- LIMIT -->

                    <div class="d-flex align-items-center">

                        <span class="me-2">

                            Hiển thị:

                        </span>


                        <form method="GET">

                            <input
                                type="hidden"
                                name="keyword"
                                value="<?= htmlspecialchars($keyword) ?>"
                            >


                            <input
                                type="hidden"
                                name="sort"
                                value="<?= htmlspecialchars($sort) ?>"
                            >


                            <select
                                name="limit"
                                class="form-select"
                                style="width: 80px;"
                                onchange="this.form.submit()"
                            >

                                <option
                                    value="10"
                                    <?= $limit === 10 ? "selected" : "" ?>
                                >
                                    10
                                </option>


                                <option
                                    value="20"
                                    <?= $limit === 20 ? "selected" : "" ?>
                                >
                                    20
                                </option>


                                <option
                                    value="30"
                                    <?= $limit === 30 ? "selected" : "" ?>
                                >
                                    30
                                </option>

                            </select>

                        </form>


                        <span class="ms-2">

                            sản phẩm / trang

                        </span>

                    </div>



                    <!-- PAGINATION -->

                    <?php if ($totalPages > 1): ?>

                        <nav>

                            <ul class="pagination mb-0">


                                <!-- PREVIOUS -->

                                <?php if ($page > 1): ?>

                                    <li class="page-item">

                                        <a
                                            class="page-link"
                                            href="?keyword=<?= urlencode($keyword) ?>&limit=<?= (int)$limit ?>&sort=<?= urlencode($sort) ?>&page=<?= $page - 1 ?>"
                                        >

                                            ←

                                        </a>

                                    </li>

                                <?php else: ?>

                                    <li class="page-item disabled">

                                        <span class="page-link">

                                            ←

                                        </span>

                                    </li>

                                <?php endif; ?>



                                <!-- PAGE NUMBERS -->

                                <?php for (
                                    $i = 1;
                                    $i <= $totalPages;
                                    $i++
                                ): ?>

                                    <li
                                        class="page-item
                                        <?= $i === $page ? "active" : "" ?>"
                                    >

                                        <a
                                            class="page-link"
                                            href="?keyword=<?= urlencode($keyword) ?>&limit=<?= (int)$limit ?>&sort=<?= urlencode($sort) ?>&page=<?= $i ?>"
                                        >

                                            <?= $i ?>

                                        </a>

                                    </li>

                                <?php endfor; ?>



                                <!-- NEXT -->

                                <?php if ($page < $totalPages): ?>

                                    <li class="page-item">

                                        <a
                                            class="page-link"
                                            href="?keyword=<?= urlencode($keyword) ?>&limit=<?= (int)$limit ?>&sort=<?= urlencode($sort) ?>&page=<?= $page + 1 ?>"
                                        >

                                            →

                                        </a>

                                    </li>

                                <?php else: ?>

                                    <li class="page-item disabled">

                                        <span class="page-link">

                                            →

                                        </span>

                                    </li>

                                <?php endif; ?>


                            </ul>

                        </nav>

                    <?php endif; ?>


                </div>

            </div>

        <?php endif; ?>


    </div>

</div>



<?php

$content = ob_get_clean();

require __DIR__ . "/../layouts/master.php";

?>