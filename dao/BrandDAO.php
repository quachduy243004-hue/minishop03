<?php

namespace DAO;

use Models\Brand;

class BrandDAO extends BaseDAO
{
    public function __construct()
    {
        parent::__construct();
    }

    // =========================================================
    // LẤY TẤT CẢ THƯƠNG HIỆU
    // =========================================================
    public function getAll(): array
    {
        $list = [];

        $sql = "SELECT *
                FROM brands
                ORDER BY brandname ASC";

        $result = $this->executeQuery($sql);

        while ($row = $result->fetch_assoc()) {

            $brand = new Brand(
                $row["brandname"] ?? "",
                $row["slug"] ?? "",
                $row["image"] ?? null,
                $row["description"] ?? null,
                (int)($row["status"] ?? 1)
            );

            $brand->id = (int)$row["id"];
            $brand->createdAt = $row["created_at"] ?? null;
            $brand->updatedAt = $row["updated_at"] ?? null;

            $list[] = $brand;
        }

        return $list;
    }

    // =========================================================
    // TÌM THƯƠNG HIỆU THEO ID
    // =========================================================
    public function findById(int $id): ?Brand
    {
        $sql = "SELECT *
                FROM brands
                WHERE id = ?";

        $stmt = $this->prepare($sql);

        $stmt->bind_param("i", $id);

        $stmt->execute();

        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {

            $brand = new Brand(
                $row["brandname"] ?? "",
                $row["slug"] ?? "",
                $row["image"] ?? null,
                $row["description"] ?? null,
                (int)($row["status"] ?? 1)
            );

            $brand->id = (int)$row["id"];
            $brand->createdAt = $row["created_at"] ?? null;
            $brand->updatedAt = $row["updated_at"] ?? null;

            return $brand;
        }

        return null;
    }

    // =========================================================
    // THÊM THƯƠNG HIỆU
    // =========================================================
    public function insert(Brand $brand): bool
    {
        $sql = "INSERT INTO brands
                (
                    brandname,
                    slug,
                    image,
                    description,
                    status
                )
                VALUES (?, ?, ?, ?, ?)";

        $stmt = $this->prepare($sql);

        $stmt->bind_param(
            "ssssi",
            $brand->brandname,
            $brand->slug,
            $brand->image,
            $brand->description,
            $brand->status
        );

        return $stmt->execute();
    }

    // =========================================================
    // CẬP NHẬT THƯƠNG HIỆU
    // =========================================================
    public function update(Brand $brand): bool
    {
        $sql = "UPDATE brands
                SET
                    brandname = ?,
                    slug = ?,
                    image = ?,
                    description = ?,
                    status = ?
                WHERE id = ?";

        $stmt = $this->prepare($sql);

        $stmt->bind_param(
            "ssssii",
            $brand->brandname,
            $brand->slug,
            $brand->image,
            $brand->description,
            $brand->status,
            $brand->id
        );

        return $stmt->execute();
    }

    // =========================================================
    // XÓA THƯƠNG HIỆU
    // =========================================================
    public function delete(int $id): bool
    {
        $sql = "DELETE FROM brands
                WHERE id = ?";

        $stmt = $this->prepare($sql);

        $stmt->bind_param("i", $id);

        return $stmt->execute();
    }

    // =========================================================
    // TÌM KIẾM THƯƠNG HIỆU
    // =========================================================
    public function search(string $keyword): array
    {
        $list = [];

        $sql = "SELECT *
                FROM brands
                WHERE brandname LIKE ?
                   OR slug LIKE ?
                ORDER BY id DESC";

        $stmt = $this->prepare($sql);

        $keyword = "%" . $keyword . "%";

        $stmt->bind_param(
            "ss",
            $keyword,
            $keyword
        );

        $stmt->execute();

        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {

            $brand = new Brand(
                $row["brandname"] ?? "",
                $row["slug"] ?? "",
                $row["image"] ?? null,
                $row["description"] ?? null,
                (int)($row["status"] ?? 1)
            );

            $brand->id = (int)$row["id"];
            $brand->createdAt = $row["created_at"] ?? null;
            $brand->updatedAt = $row["updated_at"] ?? null;

            $list[] = $brand;
        }

        return $list;
    }

    // =========================================================
    // KIỂM TRA SLUG ĐÃ TỒN TẠI - TRỪ ID HIỆN TẠI
    // =========================================================
    public function existsBySlugExceptId(
        string $slug,
        int $id
    ): bool {

        $sql = "SELECT id
                FROM brands
                WHERE slug = ?
                  AND id != ?
                LIMIT 1";

        $stmt = $this->prepare($sql);

        $stmt->bind_param(
            "si",
            $slug,
            $id
        );

        $stmt->execute();

        $result = $stmt->get_result();

        return $result->num_rows > 0;
    }

    // =========================================================
    // KIỂM TRA SLUG ĐÃ TỒN TẠI
    // DÙNG CHO CREATE
    // =========================================================
    public function existsBySlug(string $slug): bool
    {
        $sql = "SELECT id
                FROM brands
                WHERE slug = ?
                LIMIT 1";

        $stmt = $this->prepare($sql);

        $stmt->bind_param("s", $slug);

        $stmt->execute();

        $result = $stmt->get_result();

        return $result->num_rows > 0;
    }
    public function getByLimit(int $limit = 5): array
    {
        $list = $this->getAll();

        return array_slice($list, 0, $limit);
    }
    // =========================================================
// LẤY SẢN PHẨM THEO SLUG THƯƠNG HIỆU
// =========================================================
public function getByBrand(string $slug): array
{
    $list = [];

    $sql = "
        SELECT 
            p.*
        FROM products p
        INNER JOIN brands b
            ON p.brand_id = b.id
        WHERE b.slug = ?
        ORDER BY p.created_at DESC
    ";

    $stmt = $this->prepare($sql);

    $stmt->bind_param("s", $slug);

    $stmt->execute();

    $result = $stmt->get_result();


    while ($row = $result->fetch_assoc()) {

        $product = new \Models\Product();

        $product->id =
            (int)($row['id'] ?? 0);

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
}
