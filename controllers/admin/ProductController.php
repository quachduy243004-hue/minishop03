<?php

namespace Controllers\Admin;

use DAO\ProductDAO;
use DAO\CategoryDAO;
use DAO\BrandDAO;
use Models\Product;

class ProductController
{
    /*
    |--------------------------------------------------------------------------
    | INDEX - DANH SÁCH SẢN PHẨM
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $title = "Quản lý sản phẩm";

        $productDAO = new ProductDAO();

        // ==============================
        // TÌM KIẾM
        // ==============================

        $keyword = trim($_GET["keyword"] ?? "");

        // ==============================
        // SỐ SẢN PHẨM / TRANG
        // ==============================

        $limit = (int)($_GET["limit"] ?? 10);

        if (!in_array($limit, [10, 20, 30], true)) {
            $limit = 10;
        }

        // ==============================
        // TRANG HIỆN TẠI
        // ==============================

        $page = (int)($_GET["page"] ?? 1);

        if ($page < 1) {
            $page = 1;
        }

        // ==============================
        // SẮP XẾP
        // ==============================

        $sort = $_GET["sort"] ?? "name_asc";

        $allowedSort = [
            "name_asc",
            "name_desc",
            "price_asc",
            "price_desc"
        ];

        if (!in_array($sort, $allowedSort, true)) {
            $sort = "name_asc";
        }

        // ==============================
        // ĐẾM SẢN PHẨM
        // ==============================

        $totalRecords = $productDAO->count(
            "products",
            "proname",
            $keyword
        );

        // ==============================
        // TỔNG SỐ TRANG
        // ==============================

        $totalPages = $totalRecords > 0
            ? (int)ceil($totalRecords / $limit)
            : 0;

        // ==============================
        // KIỂM TRA PAGE
        // ==============================

        if ($totalPages > 0 && $page > $totalPages) {
            $page = $totalPages;
        }

        // ==============================
        // OFFSET
        // ==============================

        $offset = ($page - 1) * $limit;

        // ==============================
        // LẤY DANH SÁCH
        // ==============================

        $products = $productDAO->getPage(
            $limit,
            $offset,
            $keyword,
            $sort
        );

        $list = $products;

        // ==============================
        // VIEW
        // ==============================

        require __DIR__
            . "/../../views/admin/product/index.php";
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE - THÊM SẢN PHẨM
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $productDAO = new ProductDAO();
        $categoryDAO = new CategoryDAO();
        $brandDAO = new BrandDAO();

        // ==============================
        // LẤY DANH MỤC
        // ==============================

        $categories = $categoryDAO->getAll();

        // ==============================
        // LẤY THƯƠNG HIỆU
        // ==============================

        $brands = $brandDAO->getAll();

        // ==============================
        // GIÁ TRỊ MẶC ĐỊNH
        // ==============================

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

        // ==============================
        // SUBMIT FORM
        // ==============================

        if ($_SERVER["REQUEST_METHOD"] === "POST") {

            // ==============================
            // LẤY DỮ LIỆU
            // ==============================

            $categoryId = (int)($_POST["categoryId"] ?? 0);

            $brandId = (int)($_POST["brandId"] ?? 0);

            $proname = trim(
                $_POST["proname"] ?? ""
            );

            $slug = trim(
                $_POST["slug"] ?? ""
            );

            $price = trim(
                $_POST["price"] ?? ""
            );

            $discountPrice = trim(
                $_POST["discountPrice"] ?? ""
            );

            $quantity = trim(
                $_POST["quantity"] ?? ""
            );

            $description = trim(
                $_POST["description"] ?? ""
            );

            $status = (int)(
                $_POST["status"] ?? 1
            );

            // ==============================
            // VALIDATION
            // ==============================

            if ($categoryId <= 0) {

                $errors[] =
                    "Vui lòng chọn danh mục.";
            }

            if ($brandId <= 0) {

                $errors[] =
                    "Vui lòng chọn thương hiệu.";
            }

            if ($proname === "") {

                $errors[] =
                    "Vui lòng nhập tên sản phẩm.";

            } elseif (mb_strlen($proname) < 2) {

                $errors[] =
                    "Tên sản phẩm phải có ít nhất 2 ký tự.";
            }

            if ($slug === "") {

                $errors[] =
                    "Vui lòng nhập slug.";

            } elseif (!preg_match(
                '/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                $slug
            )) {

                $errors[] =
                    "Slug chỉ được chứa chữ thường, số và dấu gạch ngang.";
            }

            // ==============================
            // GIÁ
            // ==============================

            if (
                $price === ""
                || !is_numeric($price)
            ) {

                $errors[] =
                    "Giá sản phẩm không hợp lệ.";

            } elseif ((float)$price < 0) {

                $errors[] =
                    "Giá sản phẩm không được nhỏ hơn 0.";
            }

            // ==============================
            // GIÁ KHUYẾN MÃI
            // ==============================

            if ($discountPrice === "") {

                $discountPrice = 0;
            }

            if (!is_numeric($discountPrice)) {

                $errors[] =
                    "Giá khuyến mãi không hợp lệ.";

            } elseif ((float)$discountPrice < 0) {

                $errors[] =
                    "Giá khuyến mãi không được nhỏ hơn 0.";
            }

            if (
                is_numeric($price)
                && is_numeric($discountPrice)
                && (float)$discountPrice > (float)$price
            ) {

                $errors[] =
                    "Giá khuyến mãi không được lớn hơn giá gốc.";
            }

            // ==============================
            // SỐ LƯỢNG
            // ==============================

            if (
                $quantity === ""
                || !is_numeric($quantity)
            ) {

                $errors[] =
                    "Số lượng không hợp lệ.";

            } elseif (
                (int)$quantity < 0
            ) {

                $errors[] =
                    "Số lượng không được nhỏ hơn 0.";
            }

            // ==============================
            // STATUS
            // ==============================

            if (!in_array(
                $status,
                [0, 1],
                true
            )) {

                $errors[] =
                    "Trạng thái không hợp lệ.";
            }

            // ==============================
            // KIỂM TRA SLUG TRÙNG
            // ==============================

            if ($slug !== "") {

                if (
                    $productDAO->existsBySlug($slug)
                ) {

                    $errors[] =
                        "Slug sản phẩm đã tồn tại.";
                }
            }

            // ==============================
            // XỬ LÝ ẢNH
            // ==============================

            $imageName = "";

            if (
                isset($_FILES["image"])
                && $_FILES["image"]["error"]
                    !== UPLOAD_ERR_NO_FILE
            ) {

                $file = $_FILES["image"];

                if (
                    $file["error"]
                    !== UPLOAD_ERR_OK
                ) {

                    $errors[] =
                        "Upload hình ảnh thất bại.";

                } else {

                    // ==============================
                    // KÍCH THƯỚC
                    // ==============================

                    if (
                        $file["size"]
                        > 200 * 1024
                    ) {

                        $errors[] =
                            "Hình ảnh không được vượt quá 200KB.";
                    }

                    // ==============================
                    // MIME
                    // ==============================

                    $allowedTypes = [
                        "image/jpeg",
                        "image/png",
                        "image/gif",
                        "image/webp"
                    ];

                    $finfo = finfo_open(
                        FILEINFO_MIME_TYPE
                    );

                    $mimeType = finfo_file(
                        $finfo,
                        $file["tmp_name"]
                    );

                    finfo_close($finfo);

                    if (
                        !in_array(
                            $mimeType,
                            $allowedTypes,
                            true
                        )
                    ) {

                        $errors[] =
                            "Định dạng hình ảnh không hợp lệ.";
                    }

                    // ==============================
                    // EXTENSION
                    // ==============================

                    $extension = strtolower(
                        pathinfo(
                            $file["name"],
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
                            $allowedExtensions,
                            true
                        )
                    ) {

                        $errors[] =
                            "Phần mở rộng hình ảnh không hợp lệ.";
                    }

                    // ==============================
                    // TẠO TÊN FILE
                    // ==============================

                    if (empty($errors)) {

                        $imageName =
                            uniqid(
                                "product_",
                                true
                            )
                            . "."
                            . $extension;
                    }
                }
            }

            // ==============================
            // INSERT
            // ==============================

            if (empty($errors)) {

                try {

                    $product = new Product(
                        $categoryId,
                        $brandId,
                        $proname,
                        $slug,
                        (float)$price,
                        (float)$discountPrice,
                        (int)$quantity,
                        $imageName,
                        $description,
                        $status
                    );

                    // ==============================
                    // UPLOAD ẢNH TRƯỚC
                    // ==============================

                    $imageUploaded = false;

                    if (
                        $imageName !== ""
                        && isset($_FILES["image"])
                        && $_FILES["image"]["error"]
                            === UPLOAD_ERR_OK
                    ) {

                        $uploadDir =
                            __DIR__
                            . "/../../uploads/products/";

                        if (!is_dir($uploadDir)) {

                            mkdir(
                                $uploadDir,
                                0777,
                                true
                            );
                        }

                        $uploadPath =
                            $uploadDir
                            . $imageName;

                        if (
                            move_uploaded_file(
                                $_FILES["image"]["tmp_name"],
                                $uploadPath
                            )
                        ) {

                            $imageUploaded = true;

                        } else {

                            $errors[] =
                                "Không thể lưu hình ảnh.";
                        }
                    }

                    // ==============================
                    // INSERT DATABASE
                    // ==============================

                    if (empty($errors)) {

                        if (
                            $productDAO->insert(
                                $product
                            )
                        ) {

                            // ==========================
                            // THÀNH CÔNG
                            // ==========================

                            header(
                                "Location: /MiniShop_quachvanduy/admin/product"
                            );

                            exit;
                        }

                        // ==============================
                        // INSERT THẤT BẠI
                        // ==============================

                        if (
                            $imageUploaded
                            && $imageName !== ""
                        ) {

                            $imagePath =
                                __DIR__
                                . "/../../uploads/products/"
                                . $imageName;

                            if (
                                file_exists(
                                    $imagePath
                                )
                            ) {

                                unlink(
                                    $imagePath
                                );
                            }
                        }

                        $errors[] =
                            "Thêm sản phẩm thất bại.";
                    }

                } catch (\Throwable $e) {

                    // Nếu DB lỗi thì xóa ảnh vừa upload

                    if (
                        $imageName !== ""
                    ) {

                        $imagePath =
                            __DIR__
                            . "/../../uploads/products/"
                            . $imageName;

                        if (
                            file_exists(
                                $imagePath
                            )
                        ) {

                            unlink(
                                $imagePath
                            );
                        }
                    }

                    $errors[] =
                        "Lỗi: "
                        . $e->getMessage();
                }
            }
        }

        // ==============================
        // VIEW
        // ==============================

        $pageTitle = "Thêm sản phẩm";

        require __DIR__
            . "/../../views/admin/product/create.php";
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT - SỬA SẢN PHẨM
    |--------------------------------------------------------------------------
    */

    public function edit()
    {
        $productDAO = new ProductDAO();

        $categoryDAO = new CategoryDAO();

        $brandDAO = new BrandDAO();

        // ==============================
        // ID
        // ==============================

        $id = (int)(
            $_GET["id"]
            ?? $_POST["id"]
            ?? 0
        );

        if ($id <= 0) {

            header(
                "Location: /MiniShop_quachvanduy/admin/product"
            );

            exit;
        }

        // ==============================
        // TÌM SẢN PHẨM
        // ==============================

        $product = $productDAO->getById($id);

        if (!$product) {

            header(
                "Location: /MiniShop_quachvanduy/admin/product"
            );

            exit;
        }

        // ==============================
        // DANH MỤC
        // ==============================

        $categories =
            $categoryDAO->getAll();

        // ==============================
        // THƯƠNG HIỆU
        // ==============================

        $brands =
            $brandDAO->getAll();

        // ==============================
        // GIÁ TRỊ FORM
        // ==============================

        $categoryId =
            $product->categoryId;

        $brandId =
            $product->brandId;

        $proname =
            $product->proname;

        $slug =
            $product->slug;

        $price =
            $product->price;

        $discountPrice =
            $product->discountPrice;

        $quantity =
            $product->quantity;

        $description =
            $product->description;

        $status =
            $product->status;

        $oldImage =
            $product->image;

        $errors = [];

        // ==============================
        // POST
        // ==============================

        if (
            $_SERVER["REQUEST_METHOD"]
            === "POST"
        ) {

            // ==============================
            // LẤY DỮ LIỆU
            // ==============================

            $categoryId = (int)(
                $_POST["categoryId"] ?? 0
            );

            $brandId = (int)(
                $_POST["brandId"] ?? 0
            );

            $proname = trim(
                $_POST["proname"] ?? ""
            );

            $slug = trim(
                $_POST["slug"] ?? ""
            );

            $price = trim(
                $_POST["price"] ?? ""
            );

            $discountPrice = trim(
                $_POST["discountPrice"] ?? ""
            );

            $quantity = trim(
                $_POST["quantity"] ?? ""
            );

            $description = trim(
                $_POST["description"] ?? ""
            );

            $status = (int)(
                $_POST["status"] ?? 1
            );

            // ==============================
            // VALIDATION
            // ==============================

            if ($categoryId <= 0) {

                $errors[] =
                    "Vui lòng chọn danh mục.";
            }

            if ($brandId <= 0) {

                $errors[] =
                    "Vui lòng chọn thương hiệu.";
            }

            if ($proname === "") {

                $errors[] =
                    "Vui lòng nhập tên sản phẩm.";

            } elseif (
                mb_strlen($proname) < 2
            ) {

                $errors[] =
                    "Tên sản phẩm phải có ít nhất 2 ký tự.";
            }

            if ($slug === "") {

                $errors[] =
                    "Vui lòng nhập slug.";

            } elseif (!preg_match(
                '/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                $slug
            )) {

                $errors[] =
                    "Slug chỉ được chứa chữ thường, số và dấu gạch ngang.";
            }

            // ==============================
            // GIÁ
            // ==============================

            if (
                $price === ""
                || !is_numeric($price)
            ) {

                $errors[] =
                    "Giá sản phẩm không hợp lệ.";

            } elseif (
                (float)$price < 0
            ) {

                $errors[] =
                    "Giá sản phẩm không được nhỏ hơn 0.";
            }

            // ==============================
            // GIÁ KHUYẾN MÃI
            // ==============================

            if ($discountPrice === "") {

                $discountPrice = 0;
            }

            if (
                !is_numeric(
                    $discountPrice
                )
            ) {

                $errors[] =
                    "Giá khuyến mãi không hợp lệ.";

            } elseif (
                (float)$discountPrice < 0
            ) {

                $errors[] =
                    "Giá khuyến mãi không được nhỏ hơn 0.";
            }

            if (
                is_numeric($price)
                && is_numeric($discountPrice)
                && (float)$discountPrice
                    > (float)$price
            ) {

                $errors[] =
                    "Giá khuyến mãi không được lớn hơn giá gốc.";
            }

            // ==============================
            // QUANTITY
            // ==============================

            if (
                $quantity === ""
                || !is_numeric($quantity)
            ) {

                $errors[] =
                    "Số lượng không hợp lệ.";

            } elseif (
                (int)$quantity < 0
            ) {

                $errors[] =
                    "Số lượng không được nhỏ hơn 0.";
            }

            // ==============================
            // STATUS
            // ==============================

            if (!in_array(
                $status,
                [0, 1],
                true
            )) {

                $errors[] =
                    "Trạng thái không hợp lệ.";
            }

            // ==============================
            // SLUG TRÙNG
            // ==============================

            if (
                $slug !== ""
                && $productDAO->existsBySlugExceptId(
                    $slug,
                    $id
                )
            ) {

                $errors[] =
                    "Slug sản phẩm đã tồn tại.";
            }

            // ==============================
            // ẢNH MỚI
            // ==============================

            $newImageName = $oldImage;

            $uploadedNewImage = false;

            if (
                isset($_FILES["image"])
                && $_FILES["image"]["error"]
                    !== UPLOAD_ERR_NO_FILE
            ) {

                $file = $_FILES["image"];

                if (
                    $file["error"]
                    !== UPLOAD_ERR_OK
                ) {

                    $errors[] =
                        "Upload hình ảnh thất bại.";

                } else {

                    // ==========================
                    // SIZE
                    // ==========================

                    if (
                        $file["size"]
                        > 200 * 1024
                    ) {

                        $errors[] =
                            "Hình ảnh không được vượt quá 200KB.";
                    }

                    // ==========================
                    // MIME
                    // ==========================

                    $allowedTypes = [
                        "image/jpeg",
                        "image/png",
                        "image/gif",
                        "image/webp"
                    ];

                    $finfo = finfo_open(
                        FILEINFO_MIME_TYPE
                    );

                    $mimeType = finfo_file(
                        $finfo,
                        $file["tmp_name"]
                    );

                    finfo_close($finfo);

                    if (
                        !in_array(
                            $mimeType,
                            $allowedTypes,
                            true
                        )
                    ) {

                        $errors[] =
                            "Định dạng hình ảnh không hợp lệ.";
                    }

                    // ==========================
                    // EXTENSION
                    // ==========================

                    $extension = strtolower(
                        pathinfo(
                            $file["name"],
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
                            $allowedExtensions,
                            true
                        )
                    ) {

                        $errors[] =
                            "Phần mở rộng hình ảnh không hợp lệ.";
                    }

                    // ==========================
                    // TÊN ẢNH MỚI
                    // ==========================

                    if (empty($errors)) {

                        $newImageName =
                            uniqid(
                                "product_",
                                true
                            )
                            . "."
                            . $extension;
                    }
                }
            }

            // ==============================
            // UPDATE
            // ==============================

            if (empty($errors)) {

                try {

                    // ==========================
                    // UPLOAD ẢNH MỚI
                    // ==========================

                    if (
                        isset($_FILES["image"])
                        && $_FILES["image"]["error"]
                            === UPLOAD_ERR_OK
                    ) {

                        $uploadDir =
                            __DIR__
                            . "/../../uploads/products/";

                        if (!is_dir($uploadDir)) {

                            mkdir(
                                $uploadDir,
                                0777,
                                true
                            );
                        }

                        $uploadPath =
                            $uploadDir
                            . $newImageName;

                        if (
                            !move_uploaded_file(
                                $_FILES["image"]["tmp_name"],
                                $uploadPath
                            )
                        ) {

                            $errors[] =
                                "Không thể lưu hình ảnh.";

                        } else {

                            $uploadedNewImage = true;
                        }
                    }

                    // ==========================
                    // UPDATE DATABASE
                    // ==========================

                    if (empty($errors)) {

                        $updatedProduct =
                            new Product(
                                $categoryId,
                                $brandId,
                                $proname,
                                $slug,
                                (float)$price,
                                (float)$discountPrice,
                                (int)$quantity,
                                $newImageName,
                                $description,
                                $status
                            );

                        $updatedProduct->id =
                            $id;

                        if (
                            $productDAO->update(
                                $updatedProduct
                            )
                        ) {

                            // ======================
                            // XÓA ẢNH CŨ
                            // ======================

                            if (
                                $uploadedNewImage
                                && $oldImage !== ""
                                && $oldImage !== null
                                && $oldImage
                                    !== $newImageName
                            ) {

                                $oldImagePath =
                                    __DIR__
                                    . "/../../uploads/products/"
                                    . $oldImage;

                                if (
                                    file_exists(
                                        $oldImagePath
                                    )
                                ) {

                                    unlink(
                                        $oldImagePath
                                    );
                                }
                            }

                            // ======================
                            // REDIRECT
                            // ======================

                            header(
                                "Location: /MiniShop_quachvanduy/admin/product"
                            );

                            exit;
                        }

                        // ==========================
                        // UPDATE THẤT BẠI
                        // ==========================

                        if (
                            $uploadedNewImage
                            && $newImageName !== $oldImage
                        ) {

                            $newImagePath =
                                __DIR__
                                . "/../../uploads/products/"
                                . $newImageName;

                            if (
                                file_exists(
                                    $newImagePath
                                )
                            ) {

                                unlink(
                                    $newImagePath
                                );
                            }
                        }

                        $errors[] =
                            "Cập nhật sản phẩm thất bại.";
                    }

                } catch (\Throwable $e) {

                    // Xóa ảnh mới nếu database lỗi

                    if (
                        $uploadedNewImage
                        && $newImageName !== $oldImage
                    ) {

                        $newImagePath =
                            __DIR__
                            . "/../../uploads/products/"
                            . $newImageName;

                        if (
                            file_exists(
                                $newImagePath
                            )
                        ) {

                            unlink(
                                $newImagePath
                            );
                        }
                    }

                    $errors[] =
                        "Lỗi: "
                        . $e->getMessage();
                }
            }
        }

        // ==============================
        // VIEW
        // ==============================

        $pageTitle =
            "Sửa sản phẩm";

        require __DIR__
            . "/../../views/admin/product/edit.php";
    }


    /*
    |--------------------------------------------------------------------------
    | DETAIL - XEM CHI TIẾT
    |--------------------------------------------------------------------------
    */

    public function detail()
    {
        $productDAO = new ProductDAO();

        // ==============================
        // ID
        // ==============================

        $id = (int)(
            $_GET["id"] ?? 0
        );

        if ($id <= 0) {

            header(
                "Location: /MiniShop_quachvanduy/admin/product"
            );

            exit;
        }

        // ==============================
        // LẤY SẢN PHẨM
        // ==============================

        $product =
            $productDAO->getById($id);

        if (!$product) {

            header(
                "Location: /MiniShop_quachvanduy/admin/product"
            );

            exit;
        }

        // ==============================
        // LẤY GALLERY
        // ==============================

        $images =
            $productDAO->getImagesByProductId(
                $id
            );

        // ==============================
        // VIEW
        // ==============================

        $pageTitle =
            "Chi tiết sản phẩm";

        require __DIR__
            . "/../../views/admin/product/detail.php";
    }


    /*
    |--------------------------------------------------------------------------
    | DELETE - XÓA SẢN PHẨM
    |--------------------------------------------------------------------------
    */

    public function delete()
    {
        // ==============================
        // CHỈ CHO PHÉP POST
        // ==============================

        if (
            $_SERVER["REQUEST_METHOD"]
            !== "POST"
        ) {

            header(
                "Location: /MiniShop_quachvanduy/admin/product"
            );

            exit;
        }

        // ==============================
        // ID
        // ==============================

        $id = (int)(
            $_POST["id"] ?? 0
        );

        if ($id <= 0) {

            header(
                "Location: /MiniShop_quachvanduy/admin/product"
            );

            exit;
        }

        $productDAO =
            new ProductDAO();

        // ==============================
        // LẤY SẢN PHẨM
        // ==============================

        $product =
            $productDAO->getById($id);

        if (!$product) {

            header(
                "Location: /MiniShop_quachvanduy/admin/product"
            );

            exit;
        }

        try {

            // ==============================
            // LẤY GALLERY
            // ==============================

            $images =
                $productDAO->getImagesByProductId(
                    $id
                );

            // ==============================
            // XÓA PRODUCT
            // ==============================

            if (
                $productDAO->delete($id)
            ) {

                // ==========================
                // XÓA ẢNH ĐẠI DIỆN
                // ==========================

                if (
                    !empty($product->image)
                ) {

                    $imagePath =
                        __DIR__
                        . "/../../uploads/products/"
                        . $product->image;

                    if (
                        file_exists(
                            $imagePath
                        )
                    ) {

                        unlink(
                            $imagePath
                        );
                    }
                }

                // ==========================
                // XÓA ẢNH GALLERY
                // ==========================

                foreach (
                    $images as $image
                ) {

                    if (
                        !empty($image["image"])
                    ) {

                        $galleryPath =
                            __DIR__
                            . "/../../uploads/products/"
                            . $image["image"];

                        if (
                            file_exists(
                                $galleryPath
                            )
                        ) {

                            unlink(
                                $galleryPath
                            );
                        }
                    }
                }
            }

        } catch (\Throwable $e) {

            // Có thể ghi log nếu cần
        }

        // ==============================
        // QUAY VỀ DANH SÁCH
        // ==============================

        header(
            "Location: /MiniShop_quachvanduy/admin/product"
        );

        exit;
    }
}