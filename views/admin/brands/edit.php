<?php

require_once "../../../dao/BrandDAO.php";
require_once "../../../models/Brand.php";

$pageTitle = "Sửa thương hiệu";

$brandDAO = new BrandDAO();

$errors = [];


/*
|--------------------------------------------------------------------------
| LẤY ID
|--------------------------------------------------------------------------
*/

$id = intval($_GET["id"] ?? 0);

if ($id <= 0) {

    header("Location: index.php");
    exit;
}


/*
|--------------------------------------------------------------------------
| LẤY THƯƠNG HIỆU
|--------------------------------------------------------------------------
*/

$brand = $brandDAO->findById($id);

if (!$brand) {

    header("Location: index.php");
    exit;
}


/*
|--------------------------------------------------------------------------
| GIÁ TRỊ BAN ĐẦU
|--------------------------------------------------------------------------
*/

$brandname = $brand->brandname;
$slug = $brand->slug;
$oldImage = $brand->image;
$description = $brand->description;
$status = $brand->status;


/*
|--------------------------------------------------------------------------
| XỬ LÝ FORM
|--------------------------------------------------------------------------
*/

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    /*
    |--------------------------------------------------------------------------
    | NHẬN DỮ LIỆU
    |--------------------------------------------------------------------------
    */

    $brandname = trim($_POST["brandname"] ?? "");

    $slug = trim($_POST["slug"] ?? "");

    $description = trim($_POST["description"] ?? "");

    $status = isset($_POST["status"]) ? 1 : 0;


    /*
    |--------------------------------------------------------------------------
    | VALIDATION TÊN
    |--------------------------------------------------------------------------
    */

    if ($brandname === "") {

        $errors[] =
            "Vui lòng nhập tên thương hiệu.";

    } elseif (mb_strlen($brandname) > 255) {

        $errors[] =
            "Tên thương hiệu không được vượt quá 255 ký tự.";
    }


    /*
    |--------------------------------------------------------------------------
    | VALIDATION SLUG
    |--------------------------------------------------------------------------
    */

    if ($slug === "") {

        $errors[] =
            "Vui lòng nhập slug.";

    } elseif (
        !preg_match(
            '/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
            $slug
        )
    ) {

        $errors[] =
            "Slug chỉ được chứa chữ thường, số và dấu gạch ngang.";
    }


    /*
    |--------------------------------------------------------------------------
    | KIỂM TRA SLUG TRÙNG
    |--------------------------------------------------------------------------
    */

    if (
        $slug !== "" &&
        $brandDAO->existsBySlugExceptId($slug, $id)
    ) {

        $errors[] =
            'Slug "' .
            htmlspecialchars($slug) .
            '" đã tồn tại. Vui lòng nhập slug khác.';
    }


    /*
    |--------------------------------------------------------------------------
    | GIỮ ẢNH CŨ
    |--------------------------------------------------------------------------
    */

    $image = $oldImage;

    $newImagePath = null;

    $newImageUploaded = false;


    /*
    |--------------------------------------------------------------------------
    | XỬ LÝ ẢNH MỚI
    |--------------------------------------------------------------------------
    */

    if (
        isset($_FILES["image"]) &&
        $_FILES["image"]["error"] !== UPLOAD_ERR_NO_FILE
    ) {

        $file = $_FILES["image"];

        $fileName = $file["name"];

        $tmpName = $file["tmp_name"];

        $fileError = $file["error"];

        $fileSize = $file["size"];


        /*
        |--------------------------------------------------------------------------
        | UPLOAD ERROR
        |--------------------------------------------------------------------------
        */

        if ($fileError !== UPLOAD_ERR_OK) {

            $errors[] =
                "Upload hình ảnh thất bại.";

        } else {


            /*
            |--------------------------------------------------------------------------
            | DUNG LƯỢNG
            |--------------------------------------------------------------------------
            */

            if ($fileSize > 200 * 1024) {

                $errors[] =
                    "Hình ảnh không được vượt quá 200KB.";
            }


            /*
            |--------------------------------------------------------------------------
            | EXTENSION
            |--------------------------------------------------------------------------
            */

            $extension = strtolower(
                pathinfo(
                    $fileName,
                    PATHINFO_EXTENSION
                )
            );


            $allowedExtensions = [
                "jpg",
                "jpeg",
                "png",
                "gif",
                "webp"
            ];


            if (
                !in_array(
                    $extension,
                    $allowedExtensions
                )
            ) {

                $errors[] =
                    "Hình ảnh chỉ được phép JPG, JPEG, PNG, GIF hoặc WEBP.";
            }


            /*
            |--------------------------------------------------------------------------
            | MIME TYPE
            |--------------------------------------------------------------------------
            */

            $allowedMimeTypes = [
                "image/jpeg",
                "image/png",
                "image/gif",
                "image/webp"
            ];


            $mimeType = mime_content_type($tmpName);


            if (
                !in_array(
                    $mimeType,
                    $allowedMimeTypes
                )
            ) {

                $errors[] =
                    "File hình ảnh không hợp lệ.";
            }


            /*
            |--------------------------------------------------------------------------
            | UPLOAD FILE
            |--------------------------------------------------------------------------
            */

            if (empty($errors)) {

                $uploadDir =
                    __DIR__
                    . "/../../../uploads/brand/";


                /*
                |--------------------------------------------------------------------------
                | TẠO THƯ MỤC
                |--------------------------------------------------------------------------
                */

                if (!is_dir($uploadDir)) {

                    mkdir(
                        $uploadDir,
                        0777,
                        true
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | TÊN FILE MỚI
                |--------------------------------------------------------------------------
                */

                $newImageName =
                    time()
                    . "_"
                    . $slug
                    . "."
                    . $extension;


                $newImagePath =
                    $uploadDir . $newImageName;


                /*
                |--------------------------------------------------------------------------
                | MOVE
                |--------------------------------------------------------------------------
                */

                if (
                    move_uploaded_file(
                        $tmpName,
                        $newImagePath
                    )
                ) {

                    $image = $newImageName;

                    $newImageUploaded = true;

                } else {

                    $errors[] =
                        "Không thể lưu hình ảnh.";
                }
            }
        }
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE DATABASE
    |--------------------------------------------------------------------------
    */

    if (empty($errors)) {

        $brand->brandname = $brandname;

        $brand->slug = $slug;

        $brand->image = $image;

        $brand->description = $description;

        $brand->status = $status;


        /*
        |--------------------------------------------------------------------------
        | UPDATE
        |--------------------------------------------------------------------------
        */

        if ($brandDAO->update($brand)) {


            /*
            |--------------------------------------------------------------------------
            | NẾU CÓ ẢNH MỚI
            | XÓA ẢNH CŨ
            |--------------------------------------------------------------------------
            */

            if (
                $newImageUploaded &&
                !empty($oldImage) &&
                $oldImage !== $image
            ) {

                $oldImagePath =
                    __DIR__
                    . "/../../../uploads/brand/"
                    . basename($oldImage);


                if (
                    file_exists($oldImagePath)
                ) {

                    unlink($oldImagePath);
                }
            }


            /*
            |--------------------------------------------------------------------------
            | QUAY LẠI
            |--------------------------------------------------------------------------
            */

            header("Location: index.php");

            exit;

        } else {


            /*
            |--------------------------------------------------------------------------
            | DATABASE LỖI
            | XÓA ẢNH MỚI ĐÃ UPLOAD
            |--------------------------------------------------------------------------
            */

            if (
                $newImageUploaded &&
                !empty($newImagePath) &&
                file_exists($newImagePath)
            ) {

                unlink($newImagePath);
            }


            $errors[] =
                "Cập nhật thương hiệu thất bại.";
        }
    }
}


/*
|--------------------------------------------------------------------------
| GIAO DIỆN
|--------------------------------------------------------------------------
*/

ob_start();

?>

<div class="container-fluid">

    <!-- =====================================================
         HEADER
    ====================================================== -->

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3 class="mb-1 fw-bold">
                Sửa thương hiệu
            </h3>

            <small class="text-muted">
                Cập nhật thông tin thương hiệu
            </small>

        </div>


        <a
            href="index.php"
            class="btn btn-secondary"
        >

            <i class="fa fa-arrow-left me-1"></i>

            Quay lại

        </a>

    </div>


    <!-- =====================================================
         CARD
    ====================================================== -->

    <div class="card shadow-sm border-0">

        <div class="card-body">

            <!-- =================================================
                 ERROR
            ================================================== -->

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


            <form
                method="POST"
                enctype="multipart/form-data"
                id="brandForm"
            >


                <!-- =========================================
                     TÊN THƯƠNG HIỆU
                ========================================== -->

                <div class="mb-3">

                    <label
                        class="form-label fw-semibold"
                    >

                        Tên thương hiệu

                    </label>


                    <input
                        type="text"
                        name="brandname"
                        class="form-control"
                        value="<?= htmlspecialchars($brandname) ?>"
                        placeholder="Nhập tên thương hiệu"
                    >

                </div>


                <!-- =========================================
                     SLUG
                ========================================== -->

                <div class="mb-3">

                    <label
                        class="form-label fw-semibold"
                    >

                        Slug

                    </label>


                    <input
                        type="text"
                        name="slug"
                        class="form-control"
                        value="<?= htmlspecialchars($slug) ?>"
                        placeholder="Ví dụ: samsung"
                    >


                    <small class="text-muted">

                        Chỉ sử dụng chữ thường, số và dấu gạch ngang.

                    </small>

                </div>


                <!-- =========================================
                     HÌNH ẢNH
                ========================================== -->

                <div class="mb-3">

                    <label
                        class="form-label fw-semibold"
                    >

                        Hình ảnh

                    </label>


                    <!-- PREVIEW -->

                    <div
                        id="imagePreview"
                        class="mb-3"
                    >

                        <?php if (!empty($oldImage)): ?>

                            <img
                                id="preview"
                                src="/MiniShop_quachvanduy/uploads/brand/<?= htmlspecialchars($oldImage) ?>"
                                alt="<?= htmlspecialchars($brandname) ?>"
                                class="img-thumbnail"
                                style="
                                    width: 160px;
                                    height: 130px;
                                    object-fit: contain;
                                "
                            >

                        <?php else: ?>

                            <img
                                id="preview"
                                src=""
                                alt="Preview"
                                class="img-thumbnail"
                                style="
                                    width: 160px;
                                    height: 130px;
                                    object-fit: contain;
                                    display: none;
                                "
                            >

                        <?php endif; ?>

                    </div>


                    <!-- INPUT -->

                    <input
                        type="file"
                        name="image"
                        id="image"
                        class="form-control"
                        accept=".jpg,.jpeg,.png,.gif,.webp"
                    >


                    <small class="text-muted">

                        Chỉ cho phép JPG, JPEG, PNG, GIF, WEBP.
                        Kích thước tối đa 200 KB.

                    </small>

                </div>


                <!-- =========================================
                     MÔ TẢ
                ========================================== -->

                <div class="mb-3">

                    <label
                        class="form-label fw-semibold"
                    >

                        Mô tả

                    </label>


                    <textarea
                        name="description"
                        rows="5"
                        class="form-control"
                        placeholder="Nhập mô tả thương hiệu"
                    ><?= htmlspecialchars($description) ?></textarea>

                </div>


                <!-- =========================================
                     TRẠNG THÁI
                ========================================== -->

                <div class="mb-4">

                    <div class="form-check">

                        <input
                            type="checkbox"
                            name="status"
                            value="1"
                            class="form-check-input"
                            id="status"
                            <?= $status == 1 ? "checked" : "" ?>
                        >

                        <label
                            class="form-check-label"
                            for="status"
                        >

                            Đang bán

                        </label>

                    </div>

                </div>


                <!-- =========================================
                     BUTTON
                ========================================== -->

                <button
                    type="submit"
                    class="btn btn-success"
                >

                    <i class="fa fa-save me-1"></i>

                    Cập nhật

                </button>


                <a
                    href="index.php"
                    class="btn btn-secondary"
                >

                    Hủy

                </a>


            </form>

        </div>

    </div>

</div>


<!-- =====================================================
     PREVIEW JAVASCRIPT
====================================================== -->

<script>

const imageInput = document.getElementById("image");

const preview = document.getElementById("preview");


imageInput.addEventListener("change", function () {

    const file = this.files[0];


    if (!file) {

        return;

    }


    /*
    |--------------------------------------------------------------------------
    | KIỂM TRA DUNG LƯỢNG
    |--------------------------------------------------------------------------
    */

    if (file.size > 200 * 1024) {

        alert(
            "Hình ảnh không được vượt quá 200KB."
        );

        this.value = "";

        return;
    }


    /*
    |--------------------------------------------------------------------------
    | KIỂM TRA MIME
    |--------------------------------------------------------------------------
    */

    const allowedTypes = [
        "image/jpeg",
        "image/png",
        "image/gif",
        "image/webp"
    ];


    if (!allowedTypes.includes(file.type)) {

        alert(
            "Chỉ được chọn JPG, JPEG, PNG, GIF hoặc WEBP."
        );

        this.value = "";

        return;
    }


    /*
    |--------------------------------------------------------------------------
    | HIỂN THỊ PREVIEW
    |--------------------------------------------------------------------------
    */

    const reader = new FileReader();


    reader.onload = function (e) {

        preview.src = e.target.result;

        preview.style.display = "block";

    };


    reader.readAsDataURL(file);

});

</script>


<?php

$content = ob_get_clean();

include "../layouts/master.php";

?>