<?php

require_once "../../../dao/ProductDAO.php";
require_once "../../../dao/CategoryDAO.php";
require_once "../../../dao/BrandDAO.php";
require_once "../../../models/Product.php";

$pageTitle = "Cập nhật sản phẩm";

$productDAO = new ProductDAO();
$categoryDAO = new CategoryDAO();
$brandDAO = new BrandDAO();

$errors = [];


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
| LẤY SẢN PHẨM CŨ
|--------------------------------------------------------------------------
*/

$productOld = $productDAO->findById($id);

if (!$productOld) {
    header("Location: index.php");
    exit;
}


/*
|--------------------------------------------------------------------------
| LẤY DANH MỤC + THƯƠNG HIỆU
|--------------------------------------------------------------------------
*/

$categories = $categoryDAO->getAll();
$brands = $brandDAO->getAll();


/*
|--------------------------------------------------------------------------
| LẤY GALLERY
|--------------------------------------------------------------------------
*/

$images = $productDAO->getImagesByProductId($id);


/*
|--------------------------------------------------------------------------
| GÁN DỮ LIỆU CŨ
|--------------------------------------------------------------------------
*/

$categoryId = $productOld->categoryId;
$brandId = $productOld->brandId;

$proname = $productOld->proname;
$slug = $productOld->slug;

$price = $productOld->price;
$discountPrice = $productOld->discountPrice;
$quantity = $productOld->quantity;

$description = $productOld->description;
$status = $productOld->status;


/*
|--------------------------------------------------------------------------
| XỬ LÝ FORM
|--------------------------------------------------------------------------
*/

if ($_SERVER["REQUEST_METHOD"] == "POST") {


    /*
    |--------------------------------------------------------------------------
    | NHẬN DỮ LIỆU
    |--------------------------------------------------------------------------
    */

    $categoryId = intval($_POST["categoryId"] ?? 0);
    $brandId = intval($_POST["brandId"] ?? 0);

    $proname = trim($_POST["proname"] ?? "");
    $slug = trim($_POST["slug"] ?? "");

    $price = trim($_POST["price"] ?? "");
    $discountPrice = trim($_POST["discountPrice"] ?? "");
    $quantity = trim($_POST["quantity"] ?? "");

    $description = trim($_POST["description"] ?? "");

    $status = intval($_POST["status"] ?? 1);


    /*
    |--------------------------------------------------------------------------
    | VALIDATION
    |--------------------------------------------------------------------------
    */

    if ($categoryId <= 0) {
        $errors[] = "Vui lòng chọn danh mục.";
    }

    if ($brandId <= 0) {
        $errors[] = "Vui lòng chọn thương hiệu.";
    }

    if ($proname == "") {

        $errors[] = "Vui lòng nhập tên sản phẩm.";

    } elseif (mb_strlen($proname) > 255) {

        $errors[] =
            "Tên sản phẩm không được vượt quá 255 ký tự.";
    }


    if ($slug == "") {
        $errors[] = "Vui lòng nhập slug.";
    }


    if ($price == "") {

        $errors[] = "Vui lòng nhập giá sản phẩm.";

    } elseif (!is_numeric($price) || $price < 0) {

        $errors[] = "Giá sản phẩm không hợp lệ.";
    }


    if ($discountPrice == "") {

        $discountPrice = 0;

    } elseif (!is_numeric($discountPrice) || $discountPrice < 0) {

        $errors[] = "Giá giảm không hợp lệ.";
    }


    if ($quantity == "") {

        $errors[] = "Vui lòng nhập số lượng.";

    } elseif (!is_numeric($quantity) || $quantity < 0) {

        $errors[] = "Số lượng không hợp lệ.";
    }


    /*
    |--------------------------------------------------------------------------
    | GIỮ ẢNH CŨ
    |--------------------------------------------------------------------------
    */

    $image = $productOld->image;


    /*
    |--------------------------------------------------------------------------
    | XỬ LÝ ẢNH MỚI
    |--------------------------------------------------------------------------
    */

    $fileName = $_FILES["image"]["name"] ?? "";
    $tmpName = $_FILES["image"]["tmp_name"] ?? "";
    $fileError = $_FILES["image"]["error"] ?? UPLOAD_ERR_NO_FILE;
    $fileSize = $_FILES["image"]["size"] ?? 0;


    if ($fileName != "") {


        /*
        | Kiểm tra upload
        */

        if ($fileError != UPLOAD_ERR_OK) {

            $errors[] =
                "Upload hình ảnh thất bại.";

        } else {


            /*
            | Lấy extension
            */

            $extension = strtolower(
                pathinfo(
                    $fileName,
                    PATHINFO_EXTENSION
                )
            );


            /*
            | Định dạng cho phép
            */

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
            | Kiểm tra dung lượng
            */

            if ($fileSize > 5 * 1024 * 1024) {

                $errors[] =
                    "Hình ảnh không được vượt quá 5MB.";
            }


            /*
            |--------------------------------------------------------------------------
            | UPLOAD
            |--------------------------------------------------------------------------
            */

            if (empty($errors)) {


                /*
                | Tạo tên file mới
                */

                $image =
                    time()
                    . "_"
                    . $slug
                    . "."
                    . $extension;


                /*
                | Thư mục upload
                */

                $uploadDir =
                    __DIR__
                    . "/../../../uploads/products/";


                /*
                | Tạo thư mục nếu chưa có
                */

                if (!is_dir($uploadDir)) {

                    mkdir(
                        $uploadDir,
                        0777,
                        true
                    );
                }


                /*
                | Đường dẫn file
                */

                $uploadPath =
                    $uploadDir . $image;


                /*
                | Upload ảnh mới
                */

                if (
                    move_uploaded_file(
                        $tmpName,
                        $uploadPath
                    )
                ) {


                    /*
                    | Upload thành công
                    | Xóa ảnh cũ
                    */

                    if (!empty($productOld->image)) {

                        $oldImage =
                            $uploadDir
                            . $productOld->image;


                        if (file_exists($oldImage)) {

                            unlink($oldImage);
                        }
                    }

                } else {

                    $errors[] =
                        "Không thể upload hình ảnh mới.";

                    $image =
                        $productOld->image;
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


        $product = new Product();


        $product->id = $id;

        $product->categoryId = $categoryId;
        $product->brandId = $brandId;

        $product->proname = $proname;
        $product->slug = $slug;

        $product->price = $price;
        $product->discountPrice = $discountPrice;
        $product->quantity = $quantity;

        $product->image = $image;

        $product->description = $description;
        $product->status = $status;


        if ($productDAO->update($product)) {

            header("Location: index.php");
            exit;

        } else {

            $errors[] =
                "Cập nhật sản phẩm thất bại.";
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


<h2 class="mb-4">
    Cập nhật sản phẩm
</h2>


<!-- HIỂN THỊ LỖI -->

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


<!-- FORM -->

<form
    method="POST"
    enctype="multipart/form-data">


    <!-- DANH MỤC -->

    <div class="mb-3">

        <label class="form-label">
            Danh mục
        </label>

        <select
            name="categoryId"
            class="form-select">

            <?php foreach ($categories as $c): ?>

                <option
                    value="<?= $c->id ?>"
                    <?= ($categoryId == $c->id)
                        ? "selected"
                        : "" ?>>

                    <?= htmlspecialchars($c->name) ?>

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

            <?php foreach ($brands as $b): ?>

                <option
                    value="<?= $b->id ?>"
                    <?= ($brandId == $b->id)
                        ? "selected"
                        : "" ?>>

                    <?= htmlspecialchars($b->brandname) ?>

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


    <!-- HÌNH ẢNH HIỆN TẠI -->

    <div class="mb-3">

        <label class="form-label">
            Hình ảnh hiện tại
        </label>

        <div>

            <?php if (!empty($productOld->image)): ?>

                <img
                    src="/MiniShop_quachvanduy/uploads/products/<?= htmlspecialchars($productOld->image) ?>"
                    alt="Hình ảnh sản phẩm"
                    width="150"
                    height="150"
                    class="img-thumbnail"
                    style="object-fit: cover;">

            <?php else: ?>

                <p class="text-muted">
                    Sản phẩm chưa có hình ảnh.
                </p>

            <?php endif; ?>

        </div>

    </div>


    <!-- CHỌN ẢNH MỚI -->

    <div class="mb-3">

        <label class="form-label">
            Chọn hình ảnh mới
        </label>

        <input
            type="file"
            name="image"
            class="form-control"
            accept=".jpg,.jpeg,.png,.gif,.webp">

        <small class="text-muted">
            Nếu không chọn hình ảnh mới,
            hình ảnh cũ sẽ được giữ nguyên.
        </small>

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


<!-- ===================================================== -->
<!-- GALLERY -->
<!-- ===================================================== -->

<hr class="my-4">


<h5 class="mb-3">
    Hình ảnh Gallery
</h5>


<div class="row">


    <?php if (!empty($images)): ?>


        <?php foreach ($images as $img): ?>


            <div class="col-md-3 mb-4">


                <div class="card">


                    <!-- ẢNH -->

                    <img
                        src="/MiniShop_quachvanduy/uploads/products/<?= htmlspecialchars($img["image"]) ?>"
                        alt="Gallery"
                        class="card-img-top"
                        style="
                            height:180px;
                            object-fit:contain;
                            padding:10px;
                        ">


                    <!-- NÚT XÓA -->

                    <div class="card-body text-center">


                        <a
                            href="delete_image.php?id=<?= $img["id"] ?>&productId=<?= $id ?>"
                            class="btn btn-danger btn-sm"
                            onclick="return confirm('Bạn có chắc chắn muốn xóa hình ảnh này không?');">

                            <i class="fa fa-trash"></i>

                            Xóa

                        </a>


                    </div>


                </div>


            </div>


        <?php endforeach; ?>


    <?php else: ?>


        <div class="col-12">

            <p class="text-muted">
                Chưa có hình ảnh gallery.
            </p>

        </div>


    <?php endif; ?>


</div>


<?php

$content = ob_get_clean();

include "../layouts/master.php";

?>