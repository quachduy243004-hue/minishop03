<?php

require_once "../../../dao/ProductDAO.php";
require_once "../../../dao/CategoryDAO.php";
require_once "../../../dao/BrandDAO.php";
require_once "../../../models/Product.php";

$pageTitle = "Sửa sản phẩm";

$productDAO = new ProductDAO();
$categoryDAO = new CategoryDAO();
$brandDAO = new BrandDAO();


// =====================================================
// LẤY ID SẢN PHẨM
// =====================================================

$id = intval($_GET["id"] ?? 0);

if ($id <= 0) {
    header("Location: index.php");
    exit;
}


// =====================================================
// LẤY DỮ LIỆU DANH MỤC + THƯƠNG HIỆU
// =====================================================

$categories = $categoryDAO->getAll();
$brands = $brandDAO->getAll();


// =====================================================
// LẤY SẢN PHẨM
// =====================================================

$product = $productDAO->getById($id);

if (!$product) {
    header("Location: index.php");
    exit;
}


// =====================================================
// GÁN DỮ LIỆU BAN ĐẦU
// =====================================================

$categoryId = $product->categoryId ?? "";
$brandId = $product->brandId ?? "";
$proname = $product->proname ?? "";
$slug = $product->slug ?? "";
$price = $product->price ?? 0;
$discountPrice = $product->discountPrice ?? 0;
$quantity = $product->quantity ?? 0;
$description = $product->description ?? "";
$status = $product->status ?? 1;

// Ảnh cũ
$oldImage = $product->image ?? "";

$errors = [];


// =====================================================
// XỬ LÝ SUBMIT
// =====================================================

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


    // =================================================
    // VALIDATION
    // =================================================

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

    if ($discountPrice < 0) {
        $errors[] = "Giảm giá không hợp lệ.";
    }

    if ($quantity < 0) {
        $errors[] = "Số lượng không hợp lệ.";
    }


    // =================================================
    // XỬ LÝ ẢNH
    // =================================================

    $image = $oldImage;

    $fileName = $_FILES["image"]["name"] ?? "";
    $tmpName = $_FILES["image"]["tmp_name"] ?? "";
    $fileSize = $_FILES["image"]["size"] ?? 0;
    $error = $_FILES["image"]["error"] ?? UPLOAD_ERR_NO_FILE;


    if ($fileName != "") {

        if ($error != UPLOAD_ERR_OK) {

            $errors[] = "Upload ảnh thất bại.";

        } else {

            $allow = [
                "jpg",
                "jpeg",
                "png",
                "gif",
                "webp"
            ];

            $ext = strtolower(
                pathinfo($fileName, PATHINFO_EXTENSION)
            );


            if (!in_array($ext, $allow)) {

                $errors[] =
                    "Chỉ cho phép JPG, JPEG, PNG, GIF, WEBP.";
            }


            if ($fileSize > 200 * 1024) {

                $errors[] =
                    "Ảnh tối đa 200KB.";
            }

        }
    }


    // =================================================
    // UPDATE
    // =================================================

    if (empty($errors)) {


        // ---------------------------------------------
        // Nếu chọn ảnh mới
        // ---------------------------------------------

        if ($fileName != "") {

            $ext = strtolower(
                pathinfo($fileName, PATHINFO_EXTENSION)
            );


            // Tạo tên ảnh mới
            $image = time() . "_" . $slug . "." . $ext;


            // Đường dẫn thư mục upload
            $uploadDir =
                __DIR__ . "/../../../uploads/products/";


            // Tạo thư mục nếu chưa tồn tại
            if (!is_dir($uploadDir)) {

                mkdir(
                    $uploadDir,
                    0777,
                    true
                );
            }


            // Đường dẫn file mới
            $uploadPath =
                $uploadDir . $image;


            // Upload ảnh
            if (!move_uploaded_file(
                $tmpName,
                $uploadPath
            )) {

                $errors[] =
                    "Không thể lưu hình ảnh.";
            }


            // -----------------------------------------
            // Xóa ảnh cũ
            // -----------------------------------------

            if (
                empty($errors)
                && !empty($oldImage)
            ) {

                $oldImagePath =
                    $uploadDir . $oldImage;


                if (
                    file_exists($oldImagePath)
                    && is_file($oldImagePath)
                ) {

                    unlink($oldImagePath);
                }
            }
        }


        // ---------------------------------------------
        // Nếu không có lỗi -> UPDATE DATABASE
        // ---------------------------------------------

        if (empty($errors)) {

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


            $product->id = $id;


            if ($productDAO->update($product)) {

                header(
                    "Location: index.php"
                );

                exit;

            } else {

                $errors[] =
                    "Cập nhật sản phẩm thất bại.";
            }
        }
    }
}


ob_start();

?>


<div class="container-fluid">

    <div class="card shadow">

        <div class="card-header bg-white">

            <h4 class="mb-0">

                Sửa sản phẩm

            </h4>

        </div>


        <div class="card-body">


            <!-- ========================================= -->
            <!-- HIỂN THỊ LỖI -->
            <!-- ========================================= -->

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


            <!-- ========================================= -->
            <!-- FORM -->
            <!-- ========================================= -->

            <form
                method="POST"
                enctype="multipart/form-data">


                <!-- ===================================== -->
                <!-- DANH MỤC -->
                <!-- ===================================== -->

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
                                    $c->name
                                    ?? $c->catename
                                    ?? ""
                                ) ?>

                            </option>

                        <?php endforeach; ?>


                    </select>

                </div>


                <!-- ===================================== -->
                <!-- THƯƠNG HIỆU -->
                <!-- ===================================== -->

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
                                    $b->brandname
                                    ?? $b->name
                                    ?? ""
                                ) ?>

                            </option>

                        <?php endforeach; ?>


                    </select>

                </div>


                <!-- ===================================== -->
                <!-- TÊN SẢN PHẨM -->
                <!-- ===================================== -->

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


                <!-- ===================================== -->
                <!-- SLUG -->
                <!-- ===================================== -->

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


                <!-- ===================================== -->
                <!-- GIÁ -->
                <!-- ===================================== -->

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


                <!-- ===================================== -->
                <!-- GIẢM GIÁ -->
                <!-- ===================================== -->

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


                <!-- ===================================== -->
                <!-- SỐ LƯỢNG -->
                <!-- ===================================== -->

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


                <!-- ===================================== -->
                <!-- ẢNH HIỆN TẠI -->
                <!-- ===================================== -->

                <div class="mb-3">

                    <label class="form-label">

                        Hình ảnh hiện tại

                    </label>


                    <div class="mb-2">


                        <?php if (!empty($oldImage)): ?>


                            <?php
                            $imagePath =
                                "../../../uploads/products/"
                                . $oldImage;
                            ?>


                            <img
                                src="<?= htmlspecialchars($imagePath) ?>"
                                alt="Hình ảnh sản phẩm"
                                class="img-thumbnail"
                                style="
                                    width: 180px;
                                    height: 180px;
                                    object-fit: cover;
                                "
                                onerror="this.style.display='none'; document.getElementById('imageError').style.display='block';">


                            <div
                                id="imageError"
                                class="alert alert-warning mt-2"
                                style="display:none;">

                                Không tìm thấy file ảnh:

                                <strong>
                                    <?= htmlspecialchars($oldImage) ?>
                                </strong>

                            </div>


                        <?php else: ?>


                            <div class="text-muted">

                                Chưa có hình ảnh

                            </div>


                        <?php endif; ?>


                    </div>

                </div>


                <!-- ===================================== -->
                <!-- CHỌN ẢNH MỚI -->
                <!-- ===================================== -->

                <div class="mb-3">

                    <label class="form-label">

                        Chọn hình ảnh mới

                    </label>


                    <input
                        type="file"
                        name="image"
                        id="image"
                        class="form-control"
                        accept=".jpg,.jpeg,.png,.gif,.webp">


                    <small class="text-muted">

                        Nếu không chọn ảnh mới,
                        hình ảnh cũ sẽ được giữ nguyên.

                    </small>


                    <!-- Preview ảnh mới -->

                    <div
                        class="mt-3"
                        id="previewContainer"
                        style="display:none;">

                        <label class="form-label">

                            Ảnh mới

                        </label>


                        <br>


                        <img
                            id="previewImage"
                            src=""
                            alt="Ảnh mới"
                            class="img-thumbnail"
                            style="
                                width: 180px;
                                height: 180px;
                                object-fit: cover;
                            ">

                    </div>

                </div>


                <!-- ===================================== -->
                <!-- MÔ TẢ -->
                <!-- ===================================== -->

                <div class="mb-3">

                    <label class="form-label">

                        Mô tả

                    </label>


                    <textarea
                        name="description"
                        rows="4"
                        class="form-control"><?= htmlspecialchars($description) ?></textarea>

                </div>


                <!-- ===================================== -->
                <!-- TRẠNG THÁI -->
                <!-- ===================================== -->

                <div class="mb-3">

                    <label class="form-label">

                        Trạng thái

                    </label>


                    <select
                        name="status"
                        class="form-select">


                        <option
                            value="1"
                            <?= $status == 1
                                ? "selected"
                                : "" ?>>

                            Hiển thị

                        </option>


                        <option
                            value="0"
                            <?= $status == 0
                                ? "selected"
                                : "" ?>>

                            Ẩn

                        </option>


                    </select>

                </div>


                <!-- ===================================== -->
                <!-- BUTTON -->
                <!-- ===================================== -->

                <button
                    type="submit"
                    class="btn btn-success">

                    <i class="fa fa-save"></i>

                    Cập nhật

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


<!-- ============================================= -->
<!-- JAVASCRIPT PREVIEW ẢNH -->
<!-- ============================================= -->

<script>

document
    .getElementById("image")
    .addEventListener("change", function (event) {

        const file = event.target.files[0];

        const preview =
            document.getElementById("previewImage");

        const container =
            document.getElementById("previewContainer");


        if (file) {

            preview.src =
                URL.createObjectURL(file);

            container.style.display = "block";

        } else {

            preview.src = "";

            container.style.display = "none";

        }

    });

</script>


<?php

$content = ob_get_clean();

include "../layouts/master.php";

?>