<?php

require_once "../../../dao/ProductDAO.php";

$productDAO = new ProductDAO();

$imageId   = intval($_GET["id"] ?? 0);
$productId = intval($_GET["productId"] ?? 0);

if ($imageId <= 0 || $productId <= 0) {
    die("ID không hợp lệ");
}

/*
|--------------------------------------------------------------------------
| LẤY TÊN ẢNH
|--------------------------------------------------------------------------
*/

$image = $productDAO->deleteImage($imageId);

if ($image === null) {
    die("Không tìm thấy ảnh trong database");
}

/*
|--------------------------------------------------------------------------
| ĐƯỜNG DẪN ROOT PROJECT
|--------------------------------------------------------------------------
|
| __DIR__:
| MiniShop_quachvanduy/views/admin/product
|
| dirname(__DIR__, 3):
| MiniShop_quachvanduy
|
*/

$rootPath = dirname(__DIR__, 3);

/*
|--------------------------------------------------------------------------
| ĐƯỜNG DẪN FILE
|--------------------------------------------------------------------------
*/

$imageName = basename($image);

$filePath = $rootPath
    . DIRECTORY_SEPARATOR
    . "uploads"
    . DIRECTORY_SEPARATOR
    . "products"
    . DIRECTORY_SEPARATOR
    . $imageName;


/*
|--------------------------------------------------------------------------
| XÓA FILE
|--------------------------------------------------------------------------
*/

if (file_exists($filePath)) {

    if (unlink($filePath)) {

        // Xóa thành công

    } else {

        die(
            "Không thể xóa file:<br><br>" .
            htmlspecialchars($filePath)
        );
    }

} else {

    die(
        "Không tìm thấy file:<br><br>" .
        htmlspecialchars($filePath)
    );
}


/*
|--------------------------------------------------------------------------
| QUAY LẠI
|--------------------------------------------------------------------------
*/

header("Location: edit.php?id=" . $productId);
exit;