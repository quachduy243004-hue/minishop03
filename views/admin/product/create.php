<?php
require_once "../../../dao/ProductDAO.php";
require_once "../../../dao/CategoryDAO.php";
require_once "../../../dao/BrandDAO.php";
require_once "../../../models/Product.php";

$pageTitle = "Thêm sản phẩm";

$productDAO = new ProductDAO();
$categoryDAO = new CategoryDAO();
$brandDAO = new BrandDAO();

$categories = $categoryDAO->getAll();
$brands = $brandDAO->getAll();

$categoryId = "";
$brandId = "";
$proname = "";
$slug = "";
$price = "";
$discountPrice = "";
$quantity = "";
$description = "";
$status = 1;

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $categoryId = $_POST["categoryId"] ?? "";
    $brandId = $_POST["brandId"] ?? "";
    $proname = trim($_POST["proname"] ?? "");
    $slug = trim($_POST["slug"] ?? "");
    $price = $_POST["price"] ?? 0;
    $discountPrice = $_POST["discountPrice"] ?? 0;
    $quantity = $_POST["quantity"] ?? 0;
    $description = trim($_POST["description"] ?? "");
    $status = $_POST["status"] ?? 1;

    // =========================
    // UPLOAD ẢNH
    // =========================

    $fileName = $_FILES["image"]["name"] ?? "";
    $tmpName = $_FILES["image"]["tmp_name"] ?? "";
    $fileSize = $_FILES["image"]["size"] ?? 0;
    $error = $_FILES["image"]["error"] ?? 0;

    $image = "";

    // =========================
    // VALIDATION
    // =========================

    if ($categoryId == "") {
        $errors[] = "Chưa chọn danh mục.";
    }

    if ($brandId == "") {
        $errors[] = "Chưa chọn thương hiệu.";
    }

    if ($proname == "") {
        $errors[] = "Tên sản phẩm không được để trống.";
    }

    if ($slug == "") {
        $errors[] = "Slug không được để trống.";
    }

    if ($price <= 0) {
        $errors[] = "Giá phải lớn hơn 0.";
    }

    if ($quantity < 0) {
        $errors[] = "Số lượng không hợp lệ.";
    }

    // Kiểm tra ảnh
    if ($fileName != "") {

        if ($error != UPLOAD_ERR_OK) {
            $errors[] = "Upload ảnh thất bại.";
        }

        $allow = ["jpg", "jpeg", "png", "gif", "webp"];

        $ext = strtolower(
            pathinfo($fileName, PATHINFO_EXTENSION)
        );

        if (!in_array($ext, $allow)) {
            $errors[] = "Chỉ cho phép JPG, JPEG, PNG, GIF, WEBP.";
        }

        if ($fileSize > 200 * 1024) {
            $errors[] = "Ảnh tối đa 200KB.";
        }
    }

    // =========================
    // INSERT
    // =========================

    if (empty($errors)) {

        if ($fileName != "") {

            $ext = strtolower(
                pathinfo($fileName, PATHINFO_EXTENSION)
            );

            $image = time() . "_" . $slug . "." . $ext;

            $uploadPath =
                __DIR__ .
                "/../../../uploads/products/" .
                $image;

            move_uploaded_file(
                $tmpName,
                $uploadPath
            );
        }

        $product = new Product(
            $categoryId,
            $brandId,
            $proname,
            $slug,
            $price,
            $discountPrice,
            $quantity,
            $image,
            $description,
            $status
        );

        if ($productDAO->insert($product)) {

            header("Location:index.php");
            exit;

        } else {

            $errors[] = "Thêm sản phẩm thất bại.";
        }
    }
}

ob_start();
?>

<div class="container-fluid">

    <div class="card shadow">

        <div class="card-header bg-white">

            <h4 class="mb-0">
                Thêm sản phẩm
            </h4>

        </div>

        <div class="card-body">

            <?php if (!empty($errors)): ?>

                <div class="alert alert-danger">

                    <ul class="mb-0">

                        <?php foreach ($errors as $error): ?>

                            <li>
                                <?= htmlspecialchars($error) ?>
                            </li>

                        <?php endforeach; ?>

                    </ul>

                </div>

            <?php endif; ?>


            <form method="POST" enctype="multipart/form-data">

                <!-- DANH MỤC -->
                <div class="mb-3">

                    <label class="form-label">
                        Danh mục
                    </label>

                    <select
                        name="categoryId"
                        class="form-select">

                        <option value="">
                            -- Chọn danh mục --
                        </option>

                        <?php foreach ($categories as $c): ?>

                            <option
                                value="<?= $c->id ?>"
                                <?= ($categoryId == $c->id)
                                    ? "selected"
                                    : "" ?>>

                                <?= htmlspecialchars(
                                    $c->name ?? $c->catename ?? ""
                                ) ?>

                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>


                <!-- THƯƠNG HIỆU -->
                <div class="mb-3">

                    <label class="form-label">
                        Thương hiệu
                    </label>

                    <select
                        name="brandId"
                        class="form-select">

                        <option value="">
                            -- Chọn thương hiệu --
                        </option>

                        <?php foreach ($brands as $b): ?>

                            <option
                                value="<?= $b->id ?>"
                                <?= ($brandId == $b->id)
                                    ? "selected"
                                    : "" ?>>

                                <?= htmlspecialchars(
                                    $b->brandname ?? $b->name ?? ""
                                ) ?>

                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>


                <!-- TÊN SẢN PHẨM -->
                <div class="mb-3">

                    <label class="form-label">
                        Tên sản phẩm
                    </label>

                    <input
                        type="text"
                        name="proname"
                        class="form-control"
                        value="<?= htmlspecialchars($proname) ?>">

                </div>


                <!-- SLUG -->
                <div class="mb-3">

                    <label class="form-label">
                        Slug
                    </label>

                    <input
                        type="text"
                        name="slug"
                        class="form-control"
                        value="<?= htmlspecialchars($slug) ?>">

                </div>


                <!-- GIÁ -->
                <div class="mb-3">

                    <label class="form-label">
                        Giá
                    </label>

                    <input
                        type="number"
                        name="price"
                        class="form-control"
                        value="<?= htmlspecialchars($price) ?>">

                </div>


                <!-- GIẢM GIÁ -->
                <div class="mb-3">

                    <label class="form-label">
                        Giảm giá
                    </label>

                    <input
                        type="number"
                        name="discountPrice"
                        class="form-control"
                        value="<?= htmlspecialchars($discountPrice) ?>">

                </div>


                <!-- SỐ LƯỢNG -->
                <div class="mb-3">

                    <label class="form-label">
                        Số lượng
                    </label>

                    <input
                        type="number"
                        name="quantity"
                        class="form-control"
                        value="<?= htmlspecialchars($quantity) ?>">

                </div>


                <!-- HÌNH ẢNH -->
                <div class="mb-3">

                    <label class="form-label">
                        Hình ảnh
                    </label>

                    <input
                        type="file"
                        name="image"
                        id="image"
                        class="form-control"
                        accept=".jpg,.jpeg,.png,.gif,.webp">

                </div>


                <!-- PREVIEW ẢNH -->
                <div class="mb-3">

                    <label class="form-label">
                        Xem trước hình ảnh
                    </label>

                    <div>

                        <img
                            id="previewImage"
                            src=""
                            alt="Xem trước hình ảnh"
                            class="img-thumbnail"
                            style="
                                width: 200px;
                                height: 200px;
                                object-fit: cover;
                                display: none;
                            ">

                    </div>

                </div>


                <!-- MÔ TẢ -->
                <div class="mb-3">

                    <label class="form-label">
                        Mô tả
                    </label>

                    <textarea
                        name="description"
                        rows="4"
                        class="form-control"><?= htmlspecialchars($description) ?></textarea>

                </div>


                <!-- TRẠNG THÁI -->
                <div class="mb-3">

                    <label class="form-label">
                        Trạng thái
                    </label>

                    <select
                        name="status"
                        class="form-select">

                        <option
                            value="1"
                            <?= $status == 1 ? "selected" : "" ?>>

                            Hiển thị

                        </option>

                        <option
                            value="0"
                            <?= $status == 0 ? "selected" : "" ?>>

                            Ẩn

                        </option>

                    </select>

                </div>


                <!-- BUTTON -->
                <button
                    type="submit"
                    class="btn btn-success">

                    <i class="fa fa-save"></i>
                    Lưu

                </button>


                <a
                    href="index.php"
                    class="btn btn-secondary">

                    Quay lại

                </a>

            </form>

        </div>

    </div>

</div>


<!-- JAVASCRIPT PREVIEW ẢNH -->
<script>

document.getElementById("image").addEventListener("change", function(event) {

    const file = event.target.files[0];

    const preview = document.getElementById("previewImage");

    if (file) {

        const reader = new FileReader();

        reader.onload = function(e) {

            preview.src = e.target.result;

            preview.style.display = "block";

        };

        reader.readAsDataURL(file);

    } else {

        preview.src = "";

        preview.style.display = "none";

    }

});

</script>


<?php

$content = ob_get_clean();

include "../layouts/master.php";

?>