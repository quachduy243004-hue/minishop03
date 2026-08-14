<?php

// ========================================
// NHẬN DỮ LIỆU TỪ CONTROLLER
// ========================================

$categories = $categories ?? [];
$brands = $brands ?? [];

$categoryId = $categoryId ?? "";
$brandId = $brandId ?? "";

$proname = $proname ?? "";
$slug = $slug ?? "";

$price = $price ?? "";
$discountPrice = $discountPrice ?? "";

$quantity = $quantity ?? "";

$description = $description ?? "";

$status = $status ?? 1;

$errors = $errors ?? [];

$pageTitle = $pageTitle ?? "Thêm sản phẩm";

ob_start();

?>

<div class="container-fluid">

    <!-- ========================================
         HEADER
    ======================================== -->

    <div class="d-flex justify-content-between align-items-center mb-3">

        <div>
            <h3 class="mb-1">
                Thêm sản phẩm
            </h3>

            <small class="text-muted">
                Thêm sản phẩm mới vào hệ thống
            </small>
        </div>

        <a
            href="/MiniShop_quachvanduy/admin/product"
            class="btn btn-secondary">

            <i class="fa fa-arrow-left"></i>

            Quay lại

        </a>

    </div>


    <!-- ========================================
         HIỂN THỊ LỖI
    ======================================== -->

    <?php if (!empty($errors)): ?>

        <div class="alert alert-danger">

            <div class="fw-bold mb-2">
                Có lỗi xảy ra:
            </div>

            <ul class="mb-0">

                <?php foreach ($errors as $error): ?>

                    <li>
                        <?= htmlspecialchars($error) ?>
                    </li>

                <?php endforeach; ?>

            </ul>

        </div>

    <?php endif; ?>


    <!-- ========================================
         FORM
    ======================================== -->

    <div class="card shadow-sm">

        <div class="card-header bg-white">

            <h5 class="mb-0">
                Thông tin sản phẩm
            </h5>

        </div>


        <div class="card-body">

            <form
                method="POST"
                action="/MiniShop_quachvanduy/admin/product/create"
                enctype="multipart/form-data"
            >

                <div class="row">


                    <!-- ========================================
                         DANH MỤC
                    ======================================== -->

                    <div class="col-md-6 mb-3">

                        <label
                            for="categoryId"
                            class="form-label">

                            Danh mục
                            <span class="text-danger">*</span>

                        </label>


                        <select
                            name="categoryId"
                            id="categoryId"
                            class="form-select"
                        >

                            <option value="">
                                -- Chọn danh mục --
                            </option>


                            <?php foreach ($categories as $category): ?>

                                <option
                                    value="<?= $category->id ?>"
                                    <?= (string)$categoryId === (string)$category->id
                                        ? "selected"
                                        : "" ?>
                                >

                                    <?= htmlspecialchars(
                                        $category->catename
                                        ?? $category->name
                                        ?? ""
                                    ) ?>

                                </option>

                            <?php endforeach; ?>

                        </select>

                    </div>


                    <!-- ========================================
                         THƯƠNG HIỆU
                    ======================================== -->

                    <div class="col-md-6 mb-3">

                        <label
                            for="brandId"
                            class="form-label">

                            Thương hiệu
                            <span class="text-danger">*</span>

                        </label>


                        <select
                            name="brandId"
                            id="brandId"
                            class="form-select"
                        >

                            <option value="">
                                -- Chọn thương hiệu --
                            </option>


                            <?php foreach ($brands as $brand): ?>

                                <option
                                    value="<?= $brand->id ?>"
                                    <?= (string)$brandId === (string)$brand->id
                                        ? "selected"
                                        : "" ?>
                                >

                                    <?= htmlspecialchars(
                                        $brand->brandname
                                        ?? $brand->name
                                        ?? ""
                                    ) ?>

                                </option>

                            <?php endforeach; ?>

                        </select>

                    </div>


                    <!-- ========================================
                         TÊN SẢN PHẨM
                    ======================================== -->

                    <div class="col-md-6 mb-3">

                        <label
                            for="proname"
                            class="form-label">

                            Tên sản phẩm
                            <span class="text-danger">*</span>

                        </label>


                        <input
                            type="text"
                            name="proname"
                            id="proname"
                            class="form-control"
                            value="<?= htmlspecialchars($proname) ?>"
                            placeholder="Nhập tên sản phẩm"
                        >

                    </div>


                    <!-- ========================================
                         SLUG
                    ======================================== -->

                    <div class="col-md-6 mb-3">

                        <label
                            for="slug"
                            class="form-label">

                            Slug
                            <span class="text-danger">*</span>

                        </label>


                        <input
                            type="text"
                            name="slug"
                            id="slug"
                            class="form-control"
                            value="<?= htmlspecialchars($slug) ?>"
                            placeholder="vi-du-san-pham"
                        >

                    </div>


                    <!-- ========================================
                         GIÁ
                    ======================================== -->

                    <div class="col-md-4 mb-3">

                        <label
                            for="price"
                            class="form-label">

                            Giá
                            <span class="text-danger">*</span>

                        </label>


                        <input
                            type="number"
                            name="price"
                            id="price"
                            class="form-control"
                            value="<?= htmlspecialchars($price) ?>"
                            min="0"
                            step="1000"
                            placeholder="0"
                        >

                    </div>


                    <!-- ========================================
                         GIÁ KHUYẾN MÃI
                    ======================================== -->

                    <div class="col-md-4 mb-3">

                        <label
                            for="discountPrice"
                            class="form-label">

                            Giá khuyến mãi

                        </label>


                        <input
                            type="number"
                            name="discountPrice"
                            id="discountPrice"
                            class="form-control"
                            value="<?= htmlspecialchars($discountPrice) ?>"
                            min="0"
                            step="1000"
                            placeholder="0"
                        >

                    </div>


                    <!-- ========================================
                         SỐ LƯỢNG
                    ======================================== -->

                    <div class="col-md-4 mb-3">

                        <label
                            for="quantity"
                            class="form-label">

                            Số lượng
                            <span class="text-danger">*</span>

                        </label>


                        <input
                            type="number"
                            name="quantity"
                            id="quantity"
                            class="form-control"
                            value="<?= htmlspecialchars($quantity) ?>"
                            min="0"
                            placeholder="0"
                        >

                    </div>


                    <!-- ========================================
                         ẢNH
                    ======================================== -->

                    <div class="col-md-6 mb-3">

                        <label
                            for="image"
                            class="form-label">

                            Hình ảnh

                        </label>


                        <input
                            type="file"
                            name="image"
                            id="image"
                            class="form-control"
                            accept=".jpg,.jpeg,.png,.gif,.webp"
                        >


                        <small class="text-muted">

                            JPG, JPEG, PNG, GIF, WEBP.
                            Tối đa 200KB.

                        </small>


                        <!-- PREVIEW -->

                        <div class="mt-3">

                            <img
                                id="previewImage"
                                src=""
                                alt="Preview"
                                style="
                                    display:none;
                                    width:180px;
                                    height:180px;
                                    object-fit:cover;
                                    border:1px solid #ddd;
                                    border-radius:8px;
                                    padding:4px;
                                "
                            >

                        </div>

                    </div>


                    <!-- ========================================
                         TRẠNG THÁI
                    ======================================== -->

                    <div class="col-md-6 mb-3">

                        <label
                            for="status"
                            class="form-label">

                            Trạng thái

                        </label>


                        <select
                            name="status"
                            id="status"
                            class="form-select"
                        >

                            <option
                                value="1"
                                <?= (int)$status === 1
                                    ? "selected"
                                    : "" ?>
                            >

                                Hiển thị

                            </option>


                            <option
                                value="0"
                                <?= (int)$status === 0
                                    ? "selected"
                                    : "" ?>
                            >

                                Ẩn

                            </option>

                        </select>

                    </div>


                    <!-- ========================================
                         MÔ TẢ
                    ======================================== -->

                    <div class="col-12 mb-3">

                        <label
                            for="description"
                            class="form-label">

                            Mô tả

                        </label>


                        <textarea
                            name="description"
                            id="description"
                            rows="6"
                            class="form-control"
                            placeholder="Nhập mô tả sản phẩm..."
                        ><?= htmlspecialchars($description) ?></textarea>

                    </div>

                </div>


                <!-- ========================================
                     BUTTON
                ======================================== -->

                <div class="d-flex justify-content-end gap-2 mt-3">

                    <a
                        href="/MiniShop_quachvanduy/admin/product"
                        class="btn btn-secondary"
                    >

                        <i class="fa fa-times"></i>

                        Hủy

                    </a>


                    <button
                        type="submit"
                        class="btn btn-primary"
                    >

                        <i class="fa fa-save"></i>

                        Thêm sản phẩm

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


<!-- ========================================
     PREVIEW IMAGE
======================================== -->

<script>

document
    .getElementById("image")
    .addEventListener("change", function (event) {

        const file =
            event.target.files[0];

        const preview =
            document.getElementById(
                "previewImage"
            );


        if (!file) {

            preview.src = "";

            preview.style.display =
                "none";

            return;
        }


        const reader =
            new FileReader();


        reader.onload =
            function (e) {

                preview.src =
                    e.target.result;

                preview.style.display =
                    "block";
            };


        reader.readAsDataURL(file);

    });


// ========================================
// TỰ TẠO SLUG
// ========================================

document
    .getElementById("proname")
    .addEventListener("input", function () {

        const slugInput =
            document.getElementById("slug");


        // Chỉ tự tạo nếu slug đang trống
        if (slugInput.value !== "") {
            return;
        }


        let value =
            this.value.toLowerCase();


        value =
            value.normalize("NFD")
                .replace(
                    /[\u0300-\u036f]/g,
                    ""
                );


        value =
            value.replace(
                /đ/g,
                "d"
            );


        value =
            value.replace(
                /[^a-z0-9\s-]/g,
                ""
            );


        value =
            value.trim()
                .replace(
                    /\s+/g,
                    "-"
                );


        value =
            value.replace(
                /-+/g,
                "-"
            );


        slugInput.value =
            value;

    });

</script>


<?php

$content = ob_get_clean();

include __DIR__ . "/../layouts/master.php";

?>