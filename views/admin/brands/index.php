<?php

require_once "../../../dao/BrandDAO.php";

$pageTitle = "Quản lý thương hiệu";

$brandDAO = new BrandDAO();

$message = "";


/*
|--------------------------------------------------------------------------
| XÓA THƯƠNG HIỆU
|--------------------------------------------------------------------------
*/

if (isset($_POST["btnDelete"])) {

    $id = intval($_POST["id"] ?? 0);

    if ($id > 0 && $brandDAO->delete($id)) {

        header("Location: index.php");
        exit;

    } else {

        $message = "Xóa thương hiệu thất bại!";
    }
}


/*
|--------------------------------------------------------------------------
| TÌM KIẾM
|--------------------------------------------------------------------------
*/

$keyword = trim($_GET["keyword"] ?? "");

if ($keyword === "") {

    $list = $brandDAO->getAll();

} else {

    $list = $brandDAO->search($keyword);
}


ob_start();

?>

<div class="container-fluid">

    <!-- =====================================================
         HEADER
    ====================================================== -->

    <div class="card shadow-sm border-0 mb-3">

        <div class="card-body py-3">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <h4 class="mb-1 fw-bold">
                        Quản lý thương hiệu
                    </h4>

                    <small class="text-muted">
                        Quản lý danh sách thương hiệu của cửa hàng
                    </small>

                </div>


                <a
                    href="create.php"
                    class="btn btn-primary"
                >

                    <i class="fa fa-plus-circle me-1"></i>

                    Thêm thương hiệu

                </a>

            </div>

        </div>

    </div>


    <!-- =====================================================
         THÔNG BÁO
    ====================================================== -->

    <?php if (!empty($message)): ?>

        <div class="alert alert-danger alert-dismissible fade show">

            <i class="fa fa-exclamation-circle me-1"></i>

            <?= htmlspecialchars($message) ?>

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    <?php endif; ?>


    <!-- =====================================================
         SEARCH
    ====================================================== -->

    <div class="card shadow-sm border-0 mb-3">

        <div class="card-body">

            <form
                method="GET"
                class="row g-2 align-items-center"
            >

                <div class="col-md-6">

                    <div class="input-group">

                        <span class="input-group-text bg-white">

                            <i class="fa fa-search text-muted"></i>

                        </span>

                        <input
                            type="text"
                            name="keyword"
                            class="form-control"
                            value="<?= htmlspecialchars($keyword) ?>"
                            placeholder="Nhập tên thương hiệu..."
                        >

                    </div>

                </div>


                <div class="col-auto">

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >

                        <i class="fa fa-search me-1"></i>

                        Tìm kiếm

                    </button>

                </div>


                <div class="col-auto">

                    <a
                        href="index.php"
                        class="btn btn-outline-secondary"
                    >

                        <i class="fa fa-refresh me-1"></i>

                        Tất cả

                    </a>

                </div>

            </form>

        </div>

    </div>


    <!-- =====================================================
         TABLE
    ====================================================== -->

    <div class="card shadow-sm border-0">

        <div class="card-header bg-white py-3">

            <div class="d-flex justify-content-between align-items-center">

                <h5 class="mb-0 fw-semibold">

                    <i class="fa fa-tags text-primary me-2"></i>

                    Danh sách thương hiệu

                </h5>


                <span class="badge bg-light text-dark border">

                    <?= count($list) ?> thương hiệu

                </span>

            </div>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table
                    class="table table-hover align-middle mb-0"
                >

                    <thead class="table-dark">

                        <tr>

                            <th
                                class="text-center"
                                style="width: 70px;"
                            >
                                ID
                            </th>

                            <th
                                class="text-center"
                                style="width: 150px;"
                            >
                                Hình ảnh
                            </th>

                            <th style="min-width: 180px;">
                                Tên thương hiệu
                            </th>

                            <th style="min-width: 180px;">
                                Slug
                            </th>

                            <th style="min-width: 250px;">
                                Mô tả
                            </th>

                            <th
                                class="text-center"
                                style="width: 120px;"
                            >
                                Trạng thái
                            </th>

                            <th
                                class="text-center"
                                style="width: 230px;"
                            >
                                Thao tác
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        <?php if (!empty($list)): ?>

                            <?php foreach ($list as $brand): ?>

                                <tr>

                                    <!-- ID -->

                                    <td class="text-center fw-semibold">

                                        <?= $brand->id ?>

                                    </td>


                                    <!-- IMAGE -->

                                    <td class="text-center">

                                        <?php if (!empty($brand->image)): ?>

                                            <div
                                                class="brand-image-box mx-auto"
                                            >

                                                <img
                                                    src="/MiniShop_quachvanduy/uploads/brand/<?= htmlspecialchars($brand->image) ?>"
                                                    alt="<?= htmlspecialchars($brand->brandname) ?>"
                                                    class="brand-image"
                                                >

                                            </div>

                                        <?php else: ?>

                                            <div
                                                class="brand-no-image mx-auto"
                                            >

                                                <i class="fa fa-image"></i>

                                            </div>

                                        <?php endif; ?>

                                    </td>


                                    <!-- NAME -->

                                    <td>

                                        <div class="fw-bold">

                                            <?= htmlspecialchars($brand->brandname) ?>

                                        </div>

                                    </td>


                                    <!-- SLUG -->

                                    <td>

                                        <span class="text-muted">

                                            <?= htmlspecialchars($brand->slug) ?>

                                        </span>

                                    </td>


                                    <!-- DESCRIPTION -->

                                    <td>

                                        <?php if (!empty($brand->description)): ?>

                                            <span
                                                title="<?= htmlspecialchars($brand->description) ?>"
                                            >

                                                <?= htmlspecialchars(
                                                    mb_strimwidth(
                                                        $brand->description,
                                                        0,
                                                        80,
                                                        "..."
                                                    )
                                                ) ?>

                                            </span>

                                        <?php else: ?>

                                            <span class="text-muted">
                                                Chưa có mô tả
                                            </span>

                                        <?php endif; ?>

                                    </td>


                                    <!-- STATUS -->

                                    <td class="text-center">

                                        <?php if ($brand->status == 1): ?>

                                            <span class="badge bg-success px-3 py-2">

                                                <i class="fa fa-check me-1"></i>

                                                Hiển thị

                                            </span>

                                        <?php else: ?>

                                            <span class="badge bg-secondary px-3 py-2">

                                                <i class="fa fa-eye-slash me-1"></i>

                                                Ẩn

                                            </span>

                                        <?php endif; ?>

                                    </td>


                                    <!-- ACTION -->

                                    <td class="text-center">

                                        <div
                                            class="d-flex justify-content-center gap-1"
                                        >

                                            <!-- XEM -->

                                            <a
                                                href="detail.php?id=<?= $brand->id ?>"
                                                class="btn btn-info btn-sm text-white"
                                                title="Xem"
                                            >

                                                <i class="fa fa-eye"></i>

                                            </a>


                                            <!-- SỬA -->

                                            <a
                                                href="edit.php?id=<?= $brand->id ?>"
                                                class="btn btn-warning btn-sm"
                                                title="Sửa"
                                            >

                                                <i class="fa fa-edit"></i>

                                            </a>


                                            <!-- XÓA -->

                                            <form
                                                method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm('Bạn có chắc chắn muốn xóa thương hiệu này không?');"
                                            >

                                                <input
                                                    type="hidden"
                                                    name="id"
                                                    value="<?= $brand->id ?>"
                                                >

                                                <button
                                                    type="submit"
                                                    name="btnDelete"
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
                                    colspan="7"
                                    class="text-center py-5"
                                >

                                    <div class="text-muted">

                                        <i
                                            class="fa fa-tags fa-3x mb-3"
                                        ></i>

                                        <div class="fw-semibold">

                                            Không tìm thấy thương hiệu

                                        </div>

                                        <small>

                                            Thử tìm kiếm với từ khóa khác.

                                        </small>

                                    </div>

                                </td>

                            </tr>

                        <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>


<!-- =====================================================
     CSS
====================================================== -->

<style>

.brand-image-box {
    width: 110px;
    height: 75px;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    background: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 5px;
}

.brand-image {
    width: 100%;
    height: 100%;
    object-fit: contain;
}

.brand-no-image {
    width: 110px;
    height: 75px;
    border: 1px dashed #ced4da;
    border-radius: 8px;
    background: #f8f9fa;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #adb5bd;
    font-size: 25px;
}

.table > :not(caption) > * > * {
    padding: 12px 10px;
}

.table tbody tr {
    transition: background-color 0.15s ease;
}

</style>


<?php

$content = ob_get_clean();

include "../layouts/master.php";

?>