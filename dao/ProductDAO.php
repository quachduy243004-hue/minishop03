<?php

namespace DAO;

use Models\Product;

// require_once __DIR__ . "/BaseDAO.php";
// require_once __DIR__ . "/../models/Product.php";

class ProductDAO extends BaseDAO
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getAll(): array
    {
        $list = [];

        try {

            $sql = "SELECT
                    p.*,
                    c.catename AS categoryName,
                    b.brandname AS brandName
                FROM products p
                LEFT JOIN categories c
                    ON p.category_id = c.id
                LEFT JOIN brands b
                    ON p.brand_id = b.id
                ORDER BY p.proname";

            $result = $this->executeQuery($sql);

            while ($row = $result->fetch_assoc()) {

                $product = new Product(
                    $row["category_id"],
                    $row["brand_id"],
                    $row["proname"],
                    $row["slug"],
                    $row["price"],
                    $row["discount_price"],
                    $row["quantity"],
                    $row["image"],
                    $row["description"],
                    $row["status"]
                );

                $product->id = $row["id"];
                $product->createdAt = $row["created_at"];
                $product->updatedAt = $row["updated_at"];

                // Thêm 2 thuộc tính này
                $product->categoryName = $row["categoryName"];
                $product->brandName = $row["brandName"];

                $list[] = $product;
            }
        } catch (\Exception $e) {
            throw $e;
        }

        return $list;
    }    // Tìm sản phẩm theo ID
    public function findById(int $id): ?Product
    {
        try {

            $sql = "SELECT * FROM products WHERE id=?";

            $stmt = $this->prepare($sql);

            $stmt->bind_param("i", $id);

            $stmt->execute();

            $result = $stmt->get_result();

            if ($row = $result->fetch_assoc()) {

                $product = new Product(
                    $row["category_id"],
                    $row["brand_id"],
                    $row["proname"],
                    $row["slug"],
                    $row["price"],
                    $row["discount_price"],
                    $row["quantity"],
                    $row["image"],
                    $row["description"],
                    $row["status"]
                );

                $product->id = $row["id"];
                $product->createdAt = $row["created_at"];
                $product->updatedAt = $row["updated_at"];

                return $product;
            }
        } catch (\Exception $e) {
            throw $e;
        }

        return null;
    }

    // Thêm sản phẩm
    public function insert(Product $product): bool
    {
        try {

            $sql = "INSERT INTO products
                    (
                        category_id,
                        brand_id,
                        proname,
                        slug,
                        price,
                        discount_price,
                        quantity,
                        image,
                        description,
                        status
                    )
                    VALUES (?,?,?,?,?,?,?,?,?,?)";

            $stmt = $this->prepare($sql);

            $stmt->bind_param(
                "iissddissi",
                $product->categoryId,
                $product->brandId,
                $product->proname,
                $product->slug,
                $product->price,
                $product->discountPrice,
                $product->quantity,
                $product->image,
                $product->description,
                $product->status
            );

            return $stmt->execute();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    // Cập nhật sản phẩm
    public function update(Product $product): bool
    {
        try {

            $sql = "UPDATE products
                    SET
                        category_id=?,
                        brand_id=?,
                        proname=?,
                        slug=?,
                        price=?,
                        discount_price=?,
                        quantity=?,
                        image=?,
                        description=?,
                        status=?
                    WHERE id=?";

            $stmt = $this->prepare($sql);

            $stmt->bind_param(
                "iissddissii",
                $product->categoryId,
                $product->brandId,
                $product->proname,
                $product->slug,
                $product->price,
                $product->discountPrice,
                $product->quantity,
                $product->image,
                $product->description,
                $product->status,
                $product->id
            );

            return $stmt->execute();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    // Xóa sản phẩm
    public function delete(int $id): bool
    {
        try {

            $sql = "DELETE FROM products WHERE id=?";

            $stmt = $this->prepare($sql);

            $stmt->bind_param("i", $id);

            return $stmt->execute();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    // Lấy danh sách ảnh Gallery
    public function getImagesByProductId(int $productId): array
    {
        $list = [];

        $sql = "
        SELECT *
        FROM product_images
        WHERE product_id = ?
        ORDER BY id DESC
    ";

        $stmt = $this->prepare($sql);

        $stmt->bind_param("i", $productId);

        $stmt->execute();

        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {

            $list[] = $row;
        }

        return $list;
    }


    // Xóa ảnh Gallery
    public function deleteImage(int $id): ?string
    {
        // Lấy tên file
        $sql = "
        SELECT image
        FROM product_images
        WHERE id = ?
    ";

        $stmt = $this->prepare($sql);

        $stmt->bind_param("i", $id);

        $stmt->execute();

        $result = $stmt->get_result();

        if (!$row = $result->fetch_assoc()) {

            return null;
        }


        $image = $row["image"];


        // Xóa database
        $sql = "
        DELETE FROM product_images
        WHERE id = ?
    ";

        $stmt = $this->prepare($sql);

        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {

            return $image;
        }


        return null;
    }
    public function getImageById(int $id): ?string
    {
        $sql = "
        SELECT image
        FROM product_images
        WHERE id = ?
    ";

        $stmt = $this->prepare($sql);

        $stmt->bind_param("i", $id);

        $stmt->execute();

        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            return $row["image"];
        }

        return null;
    }
    public function getPage(
        int $limit,
        int $offset,
        string $keyword = "",
        string $sort = "name_asc"
    ) {
        // ==============================
        // XÁC ĐỊNH CÁCH SẮP XẾP
        // ==============================

        switch ($sort) {

            case "name_desc":
                $orderBy = "p.proname DESC";
                break;

            case "price_asc":
                $orderBy = "p.price ASC";
                break;

            case "price_desc":
                $orderBy = "p.price DESC";
                break;

            case "name_asc":
            default:
                $orderBy = "p.proname ASC";
                break;
        }


        // ==============================
        // SQL
        // ==============================

        $sql = "SELECT
                p.*,
                c.catename AS categoryName,
                b.brandname AS brandName
            FROM products p

            INNER JOIN categories c
                ON p.category_id = c.id

            INNER JOIN brands b
                ON p.brand_id = b.id

            WHERE p.proname LIKE ?

            ORDER BY $orderBy

            LIMIT ? OFFSET ?";


        $stmt = $this->conn->prepare($sql);


        // ==============================
        // KEYWORD
        // ==============================

        $search = "%" . $keyword . "%";


        $stmt->bind_param(
            "sii",
            $search,
            $limit,
            $offset
        );


        $stmt->execute();


        $result = $stmt->get_result();


        $products = [];


        while ($row = $result->fetch_assoc()) {

            $product = new Product(

                $row["category_id"],
                $row["brand_id"],
                $row["proname"],
                $row["slug"],
                $row["price"],
                $row["discount_price"],
                $row["quantity"],
                $row["image"],
                $row["description"],
                $row["status"]

            );


            $product->id = $row["id"];

            $product->categoryName =
                $row["categoryName"] ?? "";

            $product->brandName =
                $row["brandName"] ?? "";

            $product->createdAt =
                $row["created_at"] ?? "";

            $product->updatedAt =
                $row["updated_at"] ?? "";


            $products[] = $product;
        }


        return $products;
    }
    public function getById(int $id)
    {
        $sql = "SELECT
                p.*,
                c.catename AS categoryName,
                b.brandname AS brandName
            FROM products p
            LEFT JOIN categories c
                ON p.category_id = c.id
            LEFT JOIN brands b
                ON p.brand_id = b.id
            WHERE p.id = ?";

        $stmt = $this->conn->prepare($sql);

        $stmt->bind_param("i", $id);

        $stmt->execute();

        $result = $stmt->get_result();

        $row = $result->fetch_assoc();

        if (!$row) {
            return null;
        }

        $product = new Product(
            $row["category_id"],
            $row["brand_id"],
            $row["proname"],
            $row["slug"],
            $row["price"],
            $row["discount_price"],
            $row["quantity"],
            $row["image"],
            $row["description"],
            $row["status"]
        );

        $product->id = $row["id"];

        $product->categoryName = $row["categoryName"] ?? "";
        $product->brandName = $row["brandName"] ?? "";

        $product->createdAt = $row["created_at"] ?? "";
        $product->updatedAt = $row["updated_at"] ?? "";

        return $product;
    }
    public function countByKeyword(string $keyword = "")
    {
        $sql = "SELECT COUNT(*) AS total
            FROM products
            WHERE proname LIKE ?";

        $stmt = $this->conn->prepare($sql);

        $search = "%" . $keyword . "%";

        $stmt->bind_param("s", $search);

        $stmt->execute();

        $result = $stmt->get_result();

        $row = $result->fetch_assoc();

        return (int)$row["total"];
    }
    public function existsBySlugExceptId(string $slug, int $id): bool
    {
        $sql = "SELECT id
            FROM products
            WHERE slug = ?
              AND id != ?
            LIMIT 1";

        $stmt = $this->prepare($sql);

        $stmt->bind_param("si", $slug, $id);

        $stmt->execute();

        $result = $stmt->get_result();

        return $result->num_rows > 0;
    }
    public function existsBySlug(string $slug): bool
    {
        $sql = "SELECT id
            FROM products
            WHERE slug = ?
            LIMIT 1";

        $stmt = $this->prepare($sql);

        $stmt->bind_param("s", $slug);

        $stmt->execute();

        $result = $stmt->get_result();

        return $result->num_rows > 0;
    }
    /**
     * Lấy sản phẩm giảm giá
     */
    public function getDiscountProducts(): array
    {
        $list = [];

        $sql = "
        SELECT
            p.*,
            c.catename AS categoryName,
            b.brandname AS brandName
        FROM products p

        LEFT JOIN categories c
            ON p.category_id = c.id

        LEFT JOIN brands b
            ON p.brand_id = b.id

        WHERE p.status = 1
          AND p.discount_price > 0
          AND p.discount_price < p.price

        ORDER BY p.created_at DESC

        LIMIT 8
    ";

        $result = $this->executeQuery($sql);

        while ($row = $result->fetch_assoc()) {

            $product = new \Models\Product(
                $row["category_id"],
                $row["brand_id"],
                $row["proname"],
                $row["slug"],
                $row["price"],
                $row["discount_price"],
                $row["quantity"],
                $row["image"],
                $row["description"],
                $row["status"]
            );

            $product->id = $row["id"];

            $product->categoryName =
                $row["categoryName"] ?? "";

            $product->brandName =
                $row["brandName"] ?? "";

            $product->createdAt =
                $row["created_at"] ?? "";

            $product->updatedAt =
                $row["updated_at"] ?? "";

            $list[] = $product;
        }

        return $list;
    }
    /**
     * Lấy sản phẩm mới
     */
    public function getNewProducts(int $limit = 4): array
    {
        $list = [];

        $limit = max(1, (int)$limit);

        $sql = "
        SELECT
            p.*,
            c.catename AS categoryName,
            b.brandname AS brandName
        FROM products p

        LEFT JOIN categories c
            ON p.category_id = c.id

        LEFT JOIN brands b
            ON p.brand_id = b.id

        WHERE p.status = 1

        ORDER BY p.created_at DESC

        LIMIT $limit
    ";

        $result = $this->executeQuery($sql);

        while ($row = $result->fetch_assoc()) {

            $product = new Product(
                $row["category_id"],
                $row["brand_id"],
                $row["proname"],
                $row["slug"],
                $row["price"],
                $row["discount_price"],
                $row["quantity"],
                $row["image"],
                $row["description"],
                $row["status"]
            );

            $product->id = $row["id"];

            $product->categoryName =
                $row["categoryName"] ?? "";

            $product->brandName =
                $row["brandName"] ?? "";

            $product->createdAt =
                $row["created_at"] ?? "";

            $product->updatedAt =
                $row["updated_at"] ?? "";

            $list[] = $product;
        }

        return $list;
    }
    // =========================================================
    // LẤY SẢN PHẨM THEO SLUG DANH MỤC
    // =========================================================
    public function getByCategory(string $slug): array
    {
        $list = [];

        $sql = "
        SELECT 
            p.*
        FROM products p
        INNER JOIN categories c
            ON p.category_id = c.id
        WHERE c.slug = ?
        ORDER BY p.created_at DESC
    ";

        $stmt = $this->prepare($sql);

        $stmt->bind_param("s", $slug);

        $stmt->execute();

        $result = $stmt->get_result();


        while ($row = $result->fetch_assoc()) {

            $product = new \Models\Product();

            $product->id = (int)($row['id'] ?? 0);

            $product->categoryId =
                (int)($row['category_id'] ?? 0);

            $product->brandId =
                (int)($row['brand_id'] ?? 0);

            $product->proname =
                $row['proname'] ?? '';

            $product->slug =
                $row['slug'] ?? '';

            $product->price =
                (float)($row['price'] ?? 0);

            $product->discountPrice =
                (float)($row['pricesale'] ?? $row['discount_price'] ?? 0);

            $product->quantity =
                (int)($row['qty'] ?? $row['quantity'] ?? 0);

            $product->image =
                $row['thumbnail'] ?? $row['image'] ?? '';

            $product->description =
                $row['description'] ?? '';

            $product->status =
                (int)($row['status'] ?? 1);

            $product->createdAt =
                $row['created_at'] ?? null;

            $product->updatedAt =
                $row['updated_at'] ?? null;

            $list[] = $product;
        }


        return $list;
    }
    public function getByBrand(string $slug): array
    {
        $list = [];

        $sql = "
        SELECT 
            p.*,
            c.catename AS categoryName,
            b.brandname AS brandName
        FROM products p
        INNER JOIN brands b 
            ON p.brand_id = b.id
        LEFT JOIN categories c 
            ON p.category_id = c.id
        WHERE b.slug = ?
          AND p.status = 1
        ORDER BY p.created_at DESC
    ";

        $stmt = $this->prepare($sql);

        $stmt->bind_param("s", $slug);

        $stmt->execute();

        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {

            $product = new \Models\Product();

            $product->id = (int)($row["id"] ?? 0);
            $product->categoryId = (int)($row["category_id"] ?? 0);
            $product->brandId = (int)($row["brand_id"] ?? 0);

            $product->proname = $row["proname"] ?? "";
            $product->slug = $row["slug"] ?? "";

            $product->price = (float)($row["price"] ?? 0);
            $product->discountPrice = (float)($row["pricesale"] ?? 0);

            $product->quantity = (int)($row["qty"] ?? 0);

            $product->image = $row["thumbnail"] ?? "";

            $product->description = $row["description"] ?? "";
            $product->status = (int)($row["status"] ?? 1);

            $product->categoryName = $row["categoryName"] ?? "";
            $product->brandName = $row["brandName"] ?? "";

            $product->createdAt = $row["created_at"] ?? null;
            $product->updatedAt = $row["updated_at"] ?? null;

            $list[] = $product;
        }

        return $list;
    }
    public function search(string $keyword): array
    {
        $list = [];

        $sql = "
        SELECT
            p.*,
            c.catename AS categoryName,
            b.brandname AS brandName
        FROM products p
        LEFT JOIN categories c
            ON p.category_id = c.id
        LEFT JOIN brands b
            ON p.brand_id = b.id
        WHERE p.proname LIKE ?
           OR p.slug LIKE ?
        ORDER BY p.proname ASC
    ";

        $stmt = $this->prepare($sql);

        $key = "%" . $keyword . "%";

        $stmt->bind_param(
            "ss",
            $key,
            $key
        );

        $stmt->execute();

        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {

            $product = new \Models\Product();

            $product->id =
                (int)($row["id"] ?? 0);

            $product->categoryId =
                (int)($row["category_id"] ?? 0);

            $product->brandId =
                (int)($row["brand_id"] ?? 0);

            $product->proname =
                $row["proname"] ?? "";

            $product->slug =
                $row["slug"] ?? "";

            $product->price =
                (float)($row["price"] ?? 0);

            $product->discountPrice =
                (float)($row["pricesale"] ?? 0);

            $product->quantity =
                (int)($row["qty"] ?? 0);

            $product->image =
                $row["thumbnail"] ?? "";

            $product->description =
                $row["description"] ?? "";

            $product->status =
                (int)($row["status"] ?? 1);

            $product->categoryName =
                $row["categoryName"] ?? "";

            $product->brandName =
                $row["brandName"] ?? "";

            $product->createdAt =
                $row["created_at"] ?? null;

            $product->updatedAt =
                $row["updated_at"] ?? null;

            $list[] = $product;
        }

        return $list;
    }
    public function getBySlug(string $slug): ?\Models\Product
    {
        $sql = "
        SELECT 
            p.*,
            c.catename AS categoryName,
            c.slug AS categorySlug,
            b.brandname AS brandName,
            b.slug AS brandSlug
        FROM products p

        LEFT JOIN categories c
            ON p.category_id = c.id

        LEFT JOIN brands b
            ON p.brand_id = b.id

        WHERE p.slug = ?
        LIMIT 1
    ";

        $stmt = $this->prepare($sql);

        $stmt->bind_param("s", $slug);

        $stmt->execute();

        $result = $stmt->get_result();

        if (!$row = $result->fetch_assoc()) {
            return null;
        }

        $product = new \Models\Product();

        /*
    |----------------------------------------------------------
    | GÁN DỮ LIỆU DATABASE VÀO MODEL
    |----------------------------------------------------------
    */

        $product->id = (int)($row["id"] ?? 0);

        $product->categoryId =
            (int)($row["category_id"] ?? 0);

        $product->brandId =
            (int)($row["brand_id"] ?? 0);

        $product->proname =
            $row["proname"] ?? "";

        $product->slug =
            $row["slug"] ?? "";

        $product->price =
            (float)($row["price"] ?? 0);

        $product->discountPrice =
            (float)($row["discountPrice"] ?? 0);

        $product->quantity =
            (int)($row["quantity"] ?? 0);

        $product->image =
            $row["image"] ?? "";

        $product->description =
            $row["description"] ?? "";

        $product->status =
            (int)($row["status"] ?? 1);

        $product->categoryName =
            $row["categoryName"] ?? "";

        $product->brandName =
            $row["brandName"] ?? "";

        return $product;
    }
}
