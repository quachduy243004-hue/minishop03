<?php

require_once "../../../dao/BrandDAO.php";
require_once "../../../models/Brand.php";

$pageTitle = "Thêm thương hiệu";

$brandDAO = new BrandDAO();

$errors = [];

/*
|--------------------------------------------------------------------------
| GIÁ TRỊ MẶC ĐỊNH
|--------------------------------------------------------------------------
*/

$brandname = "";
$slug = "";
$description = "";
$status = 1;


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

    /*
     * Checkbox:
     * checked = 1
     * không checked = 0
     */
    $status = isset($_POST["status"]) ? 1 : 0;


    /*
    |--------------------------------------------------------------------------
    | VALIDATION TÊN
    |--------------------------------------------------------------------------
    */

    if ($brandname === "") {

        $errors[] = "Vui lòng nhập tên thương hiệu.";

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

        $errors[] = "Vui lòng nhập slug.";

    } elseif (!preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $slug)) {

        $errors[] =
            "Slug chỉ được chứa chữ thường, số và dấu gạch ngang.";
    }


    /*
    |--------------------------------------------------------------------------
    | XỬ LÝ ẢNH
    |--------------------------------------------------------------------------
    */

    $image = "";

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
        | KIỂM TRA UPLOAD
        |--------------------------------------------------------------------------
        */

        if ($fileError !== UPLOAD_ERR_OK) {

            $errors[] = "Upload hình ảnh thất bại.";

        } else {

            /*
            |--------------------------------------------------------------------------
            | KIỂM TRA DUNG LƯỢNG
            |--------------------------------------------------------------------------
            */

            if ($fileSize > 200 * 1024) {

                $errors[] =
                    "Hình ảnh không được vượt quá 200KB.";
            }


            /*
            |--------------------------------------------------------------------------
            | KIỂM TRA EXTENSION
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

            if (!in_array($extension, $allowedExtensions)) {

                $errors[] =
                    "Hình ảnh chỉ được phép JPG, JPEG, PNG, GIF hoặc WEBP.";
            }


            /*
            |--------------------------------------------------------------------------
            | KIỂM TRA MIME TYPE
            |--------------------------------------------------------------------------
            */

            $allowedMimeTypes = [
                "image/jpeg",
                "image/png",
                "image/gif",
                "image/webp"
            ];

            $mimeType = mime_content_type($tmpName);

            if (!in_array($mimeType, $allowedMimeTypes)) {

                $errors[] =
                    "File hình ảnh không hợp lệ.";
            }


            /*
            |--------------------------------------------------------------------------
            | UPLOAD
            |--------------------------------------------------------------------------
            */

            if (empty($errors)) {

                /*
                |--------------------------------------------------------------------------
                | THƯ MỤC UPLOAD
                |--------------------------------------------------------------------------
                */

                $uploadDir =
                    __DIR__
                    . "/../../../uploads/brand/";


                /*
                |--------------------------------------------------------------------------
                | TẠO THƯ MỤC NẾU CHƯA CÓ
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
                | TẠO TÊN FILE
                |--------------------------------------------------------------------------
                */

                $image =
                    time()
                    . "_"
                    . $slug
                    . "."
                    . $extension;


                /*
                |--------------------------------------------------------------------------
                | ĐƯỜNG DẪN FILE
                |--------------------------------------------------------------------------
                */

                $uploadPath =
                    $uploadDir . $image;


                /*
                |--------------------------------------------------------------------------
                | MOVE FILE
                |--------------------------------------------------------------------------
                */

                if (!move_uploaded_file(
                    $tmpName,
                    $uploadPath
                )) {

                    $errors[] =
                        "Không thể lưu hình ảnh.";
                }
            }
        }
    }


    /*
    |--------------------------------------------------------------------------
    | LƯU DATABASE
    |--------------------------------------------------------------------------
    */

    if (empty($errors)) {

        $brand = new Brand();

        $brand->brandname = $brandname;

        $brand->slug = $slug;

        $brand->image = $image;

        $brand->description = $description;

        $brand->status = $status;


        /*
        |--------------------------------------------------------------------------
        | INSERT
        |--------------------------------------------------------------------------
        */

        if ($brandDAO->insert($brand)) {

            header("Location: index.php");

            exit;

        } else {

            /*
            |--------------------------------------------------------------------------
            | NẾU DATABASE LỖI
            | XÓA FILE ĐÃ UPLOAD
            |--------------------------------------------------------------------------
            */

            if (
                !empty($image) &&
                isset($uploadPath) &&
                file_exists($uploadPath)
            ) {

                unlink($uploadPath);
            }

            $errors[] =
                "Thêm thương hiệu thất bại.";
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

    <!-- HEADER -->

    <div class="d-flex justify-content-between align-items-center mb-4">

        <h3 class="mb-0">
            Thêm thương hiệu
        </h3>

    </div>


    <!-- FORM -->

    <div class="card shadow-sm">

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


            <form
                method="POST"
                enctype="multipart/form-data"
                id="brandForm"
            >


                <!-- ====================================
                     TÊN THƯƠNG HIỆU
                ===================================== -->

                <div class="mb-3">

                    <label class="form-label fw-semibold">

                        Tên thương hiệu

                    </label>

                    <input
                        type="text"
                        name="brandname"
                        id="brandname"
                        class="form-control"
                        value="<?= htmlspecialchars($brandname) ?>"
                        placeholder="Nhập tên thương hiệu"
                    >

                </div>


                <!-- ====================================
                     SLUG
                ===================================== -->

                <div class="mb-3">

                    <label class="form-label fw-semibold">

                        Slug

                    </label>

                    <input
                        type="text"
                        name="slug"
                        id="slug"
                        class="form-control"
                        value="<?= htmlspecialchars($slug) ?>"
                    >

                </div>


                <!-- ====================================
                     HÌNH ẢNH
                ===================================== -->

                <div class="mb-3">

                    <label class="form-label fw-semibold">

                        Hình ảnh

                    </label>


                    <!-- PREVIEW -->

                    <div
                        id="imagePreview"
                        class="mb-2"
                        style="display: none;"
                    >

                        <img
                            id="preview"
                            src=""
                            alt="Preview"
                            class="img-thumbnail"
                            style="
                                width: 150px;
                                height: 150px;
                                object-fit: contain;
                            "
                        >

                    </div>


                    <!-- INPUT FILE -->

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


                <!-- ====================================
                     MÔ TẢ
                ===================================== -->

                <div class="mb-3">

                    <label class="form-label fw-semibold">

                        Mô tả

                    </label>

                    <textarea
                        name="description"
                        rows="5"
                        class="form-control"
                        placeholder="Nhập mô tả thương hiệu"
                    ><?= htmlspecialchars($description) ?></textarea>

                </div>


                <!-- ====================================
                     TRẠNG THÁI
                ===================================== -->

                <div class="mb-3">

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


                <!-- ====================================
                     BUTTON
                ===================================== -->

                <button
                    type="submit"
                    class="btn btn-success"
                >

                    <i class="fa fa-save"></i>

                    Lưu

                </button>


                <a
                    href="index.php"
                    class="btn btn-secondary"
                >

                    <i class="fa fa-arrow-left"></i>

                    Quay lại

                </a>


            </form>

        </div>

    </div>

</div>


<!-- ====================================
     JAVASCRIPT PREVIEW IMAGE
===================================== -->

<script>

const imageInput = document.getElementById("image");

const imagePreview = document.getElementById("imagePreview");

const preview = document.getElementById("preview");


imageInput.addEventListener("change", function () {

    const file = this.files[0];

    /*
    |--------------------------------------------------------------------------
    | KHÔNG CHỌN FILE
    |--------------------------------------------------------------------------
    */

    if (!file) {

        imagePreview.style.display = "none";

        preview.src = "";

        return;
    }


    /*
    |--------------------------------------------------------------------------
    | KIỂM TRA DUNG LƯỢNG
    |--------------------------------------------------------------------------
    */

    if (file.size > 200 * 1024) {

        alert("Hình ảnh không được vượt quá 200KB.");

        this.value = "";

        imagePreview.style.display = "none";

        preview.src = "";

        return;
    }


    /*
    |--------------------------------------------------------------------------
    | KIỂM TRA ĐỊNH DẠNG
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

        imagePreview.style.display = "none";

        preview.src = "";

        return;
    }


    /*
    |--------------------------------------------------------------------------
    | ĐỌC ẢNH
    |--------------------------------------------------------------------------
    */

    const reader = new FileReader();


    reader.onload = function (e) {

        preview.src = e.target.result;

        imagePreview.style.display = "block";

    };


    reader.readAsDataURL(file);

});

</script>


<?php

$content = ob_get_clean();

include "../layouts/master.php";

?>