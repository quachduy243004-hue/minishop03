<?php

namespace DAO;

use Models\Category;

class CategoryDAO extends BaseDAO
{
    public function __construct()
    {
        parent::__construct();
    }

    // =========================================================
    // LẤY TẤT CẢ DANH MỤC
    // =========================================================
    public function getAll(): array
    {
        $list = [];

        $sql = "SELECT *
                FROM categories
                ORDER BY catename ASC";

        $result = $this->executeQuery($sql);

        while ($row = $result->fetch_assoc()) {

            $category = new Category(
                $row["catename"] ?? "",
                $row["slug"] ?? "",
                $row["image"] ?? null,
                $row["description"] ?? null,
                (int)($row["status"] ?? 1)
            );

            $category->id = (int)$row["id"];
            $category->createdAt = $row["created_at"] ?? null;
            $category->updatedAt = $row["updated_at"] ?? null;

            $list[] = $category;
        }

        return $list;
    }

    // =========================================================
    // TÌM THEO ID
    // =========================================================
    public function findById(int $id): ?Category
    {
        $sql = "SELECT *
                FROM categories
                WHERE id = ?";

        $stmt = $this->prepare($sql);

        $stmt->bind_param("i", $id);

        $stmt->execute();

        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {

            $category = new Category(
                $row["catename"] ?? "",
                $row["slug"] ?? "",
                $row["image"] ?? null,
                $row["description"] ?? null,
                (int)($row["status"] ?? 1)
            );

            $category->id = (int)$row["id"];
            $category->createdAt = $row["created_at"] ?? null;
            $category->updatedAt = $row["updated_at"] ?? null;

            return $category;
        }

        return null;
    }

    // =========================================================
    // THÊM DANH MỤC
    // =========================================================
    public function insert(Category $category): bool
    {
        $sql = "INSERT INTO categories
                (
                    catename,
                    slug,
                    image,
                    description,
                    status
                )
                VALUES (?, ?, ?, ?, ?)";

        $stmt = $this->prepare($sql);

        $stmt->bind_param(
            "ssssi",
            $category->name,
            $category->slug,
            $category->image,
            $category->description,
            $category->status
        );

        return $stmt->execute();
    }

    // =========================================================
    // CẬP NHẬT DANH MỤC
    // =========================================================
    public function update(Category $category): bool
    {
        $sql = "UPDATE categories
                SET
                    catename = ?,
                    slug = ?,
                    image = ?,
                    description = ?,
                    status = ?
                WHERE id = ?";

        $stmt = $this->prepare($sql);

        $stmt->bind_param(
            "ssssii",
            $category->name,
            $category->slug,
            $category->image,
            $category->description,
            $category->status,
            $category->id
        );

        return $stmt->execute();
    }

    // =========================================================
    // XÓA DANH MỤC
    // =========================================================
    public function delete(int $id): bool
    {
        $sql = "DELETE FROM categories
                WHERE id = ?";

        $stmt = $this->prepare($sql);

        $stmt->bind_param("i", $id);

        return $stmt->execute();
    }

    // =========================================================
    // TÌM KIẾM
    // =========================================================
    public function search(string $keyword): array
    {
        $list = [];

        $sql = "SELECT *
                FROM categories
                WHERE catename LIKE ?
                   OR slug LIKE ?
                ORDER BY catename ASC";

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

            $category = new Category(
                $row["catename"] ?? "",
                $row["slug"] ?? "",
                $row["image"] ?? null,
                $row["description"] ?? null,
                (int)($row["status"] ?? 1)
            );

            $category->id = (int)$row["id"];
            $category->createdAt = $row["created_at"] ?? null;
            $category->updatedAt = $row["updated_at"] ?? null;

            $list[] = $category;
        }

        return $list;
    }

    // =========================================================
    // KIỂM TRA SLUG
    // DÙNG CHO CREATE
    // =========================================================
    public function existsBySlug(string $slug): bool
    {
        $sql = "SELECT id
                FROM categories
                WHERE slug = ?
                LIMIT 1";

        $stmt = $this->prepare($sql);

        $stmt->bind_param("s", $slug);

        $stmt->execute();

        $result = $stmt->get_result();

        return $result->num_rows > 0;
    }

    // =========================================================
    // KIỂM TRA SLUG KHI EDIT
    // LOẠI TRỪ ID HIỆN TẠI
    // =========================================================
    public function existsBySlugExceptId(
        string $slug,
        int $id
    ): bool {

        $sql = "SELECT id
                FROM categories
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
public function getByLimit(int $limit = 5): array
{
    $limit = max(1, (int)$limit);

    $sql = "SELECT *
            FROM categories
            ORDER BY catename ASC
            LIMIT $limit";

    $result = $this->executeQuery($sql);

    $list = [];

    while ($row = $result->fetch_assoc()) {

        $category = new Category(
            $row["catename"] ?? "",
            $row["slug"] ?? "",
            $row["image"] ?? null,
            $row["description"] ?? null,
            (int)($row["status"] ?? 1)
        );

        $category->id = (int)($row["id"] ?? 0);

        $category->createdAt =
            $row["created_at"] ?? null;

        $category->updatedAt =
            $row["updated_at"] ?? null;

        $list[] = $category;
    }

    return $list;
}
}
