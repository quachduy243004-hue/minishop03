<?php

require_once "../../../dao/ProductDAO.php";
require_once "../../../dao/CategoryDAO.php";
require_once "../../../dao/BrandDAO.php";

$pageTitle = "Chi tiết sản phẩm";

$productDAO = new ProductDAO();

/*
|--------------------------------------------------------------------------
| LẤY ID SẢN PHẨM
|--------------------------------------------------------------------------
*/

$id = intval($_GET["id"] ?? 0);

if ($id <= 0) {
    header("Location: index.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| LẤY SẢN PHẨM
|--------------------------------------------------------------------------
*/

$product = $productDAO->findById($id);

if (!$product) {
    header("Location: index.php");
    exit;
}

?>

<?php ob_start(); ?>

<div class="container-fluid">

    <!-- Tiêu đề -->
    <div class="d-flex justify-content-between align-items-center mb-4">

        <h2>
            Chi tiết sản phẩm
        </h2>

        <a href="index.php" class="btn btn-secondary">
            <i class="fa fa-arrow-left"></i>
            Quay lại
        </a>

    </div>


    <!-- Nội dung -->
    <div class="card shadow-sm">

        <div class="card-body">

            <div class="row">


                <!-- =========================
                     HÌNH ẢNH
                ========================== -->

                <div class="col-md-5 text-center">

                    <?php if (!empty($product->image)): ?>

                        <img
                            src="/MiniShop_quachvanduy/uploads/products/<?= htmlspecialchars($product->image) ?>"
                            alt="<?= htmlspecialchars($product->proname) ?>"
                            class="img-fluid img-thumbnail"
                            style="
                                width: 100%;
                                max-width: 400px;
                                height: 400px;
                                object-fit: contain;
                            ">

                    <?php else: ?>

                        <div
                            class="border rounded d-flex align-items-center justify-content-center"
                            style="
                                width: 100%;
                                max-width: 400px;
                                height: 400px;
                                margin: auto;
                                background-color: #f8f9fa;
                            ">

                            <span class="text-muted fs-4">
                                No Image
                            </span>

                        </div>

                    <?php endif; ?>

                </div>


                <!-- =========================
                     THÔNG TIN SẢN PHẨM
                ========================== -->

                <div class="col-md-7">

                    <h3 class="mb-3">
                        <?= htmlspecialchars($product->proname) ?>
                    </h3>


                    <!-- ID -->

                    <div class="mb-3">

                        <strong>ID:</strong>

                        <?= $product->id ?>

                    </div>


                    <!-- Danh mục -->

                    <div class="mb-3">

                        <strong>Danh mục:</strong>

                        <?= !empty($product->categoryName)
                            ? htmlspecialchars($product->categoryName)
                            : "Chưa có danh mục" ?>

                    </div>


                    <!-- Thương hiệu -->

                    <div class="mb-3">

                        <strong>Thương hiệu:</strong>

                        <?= !empty($product->brandName)
                            ? htmlspecialchars($product->brandName)
                            : "Chưa có thương hiệu" ?>

                    </div>


                    <!-- Slug -->

                    <div class="mb-3">

                        <strong>Slug:</strong>

                        <?= htmlspecialchars($product->slug) ?>

                    </div>


                    <!-- Giá -->

                    <div class="mb-3">

                        <strong>Giá:</strong>

                        <span class="text-danger fw-bold fs-4">

                            <?= number_format(
                                $product->price,
                                0,
                                ",",
                                "."
                            ) ?>
                            VNĐ

                        </span>

                    </div>


                    <!-- Giá giảm -->

                    <div class="mb-3">

                        <strong>Giá giảm:</strong>

                        <?php if ($product->discountPrice > 0): ?>

                            <span class="text-success fw-bold">

                                <?= number_format(
                                    $product->discountPrice,
                                    0,
                                    ",",
                                    "."
                                ) ?>
                                VNĐ

                            </span>

                        <?php else: ?>

                            <span class="text-muted">
                                Không giảm giá
                            </span>

                        <?php endif; ?>

                    </div>


                    <!-- Số lượng -->

                    <div class="mb-3">

                        <strong>Số lượng:</strong>

                        <?= $product->quantity ?>

                    </div>


                    <!-- Trạng thái -->

                    <div class="mb-3">

                        <strong>Trạng thái:</strong>

                        <?php if ($product->status == 1): ?>

                            <span class="badge bg-success">
                                Hiển thị
                            </span>

                        <?php else: ?>

                            <span class="badge bg-secondary">
                                Ẩn
                            </span>

                        <?php endif; ?>

                    </div>


                    <!-- Ngày tạo -->

                    <?php if (!empty($product->createdAt)): ?>

                        <div class="mb-3">

                            <strong>Ngày tạo:</strong>

                            <?= htmlspecialchars($product->createdAt) ?>

                        </div>

                    <?php endif; ?>


                    <!-- Ngày cập nhật -->

                    <?php if (!empty($product->updatedAt)): ?>

                        <div class="mb-3">

                            <strong>Ngày cập nhật:</strong>

                            <?= htmlspecialchars($product->updatedAt) ?>

                        </div>

                    <?php endif; ?>


                </div>

            </div>


            <!-- =========================
                 MÔ TẢ
            ========================== -->

            <hr class="my-4">

            <div>

                <h4 class="mb-3">
                    Mô tả sản phẩm
                </h4>

                <?php if (!empty($product->description)): ?>

                    <div class="border rounded p-3 bg-light">

                        <?= nl2br(
                            htmlspecialchars($product->description)
                        ) ?>

                    </div>

                <?php else: ?>

                    <p class="text-muted">
                        Sản phẩm chưa có mô tả.
                    </p>

                <?php endif; ?>

            </div>



            <div class="mt-4">

                <a
                    href="edit.php?id=<?= $product->id ?>"
                    class="btn btn-warning">

                    <i class="fa fa-edit"></i>
                    Chỉnh sửa

                </a>

                <a
                    href="index.php"
                    class="btn btn-secondary">

                    Quay lại

                </a>

            </div>

        </div>

    </div>

</div>


<?php

$content = ob_get_clean();

require_once "../layouts/master.php";

?>
