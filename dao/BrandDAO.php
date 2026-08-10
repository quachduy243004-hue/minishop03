<?php
require_once __DIR__ . "/BaseDAO.php";
require_once __DIR__ . "/../models/Brand.php";

class BrandDAO extends BaseDAO
{
    public function __construct()
    {
        parent::__construct();
    }

    // Lấy tất cả thương hiệu
    public function getAll(): array
    {
        $list = [];

        try {
            $sql = "SELECT * FROM brands ORDER BY brandname";
            $result = $this->executeQuery($sql);

            while ($row = $result->fetch_assoc()) {

                $brand = new Brand(
                    $row["brandname"],
                    $row["slug"],
                    $row["image"],
                    $row["description"],
                    $row["status"]
                );

                $brand->id = $row["id"];
                $brand->createdAt = $row["created_at"];
                $brand->updatedAt = $row["updated_at"];

                $list[] = $brand;
            }
        } catch (Exception $e) {
            throw $e;
        }

        return $list;
    }

    // Tìm theo ID
    public function findById(int $id): ?Brand
    {
        try {

            $sql = "SELECT * FROM brands WHERE id=?";
            $stmt = $this->prepare($sql);

            $stmt->bind_param("i", $id);

            $stmt->execute();

            $result = $stmt->get_result();

            if ($row = $result->fetch_assoc()) {

                $brand = new Brand(
                    $row["brandname"],
                    $row["slug"],
                    $row["image"],
                    $row["description"],
                    $row["status"]
                );

                $brand->id = $row["id"];
                $brand->createdAt = $row["created_at"];
                $brand->updatedAt = $row["updated_at"];

                return $brand;
            }
        } catch (Exception $e) {
            throw $e;
        }

        return null;
    }

    // Thêm thương hiệu
    public function insert(Brand $brand): bool
    {
        try {

            $sql = "INSERT INTO brands
                    (brandname,slug,image,description,status)
                    VALUES (?,?,?,?,?)";

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
        } catch (Exception $e) {
            throw $e;
        }
    }

    // Cập nhật thương hiệu
    public function update(Brand $brand): bool
    {
        try {

            $sql = "UPDATE brands
                    SET
                        brandname=?,
                        slug=?,
                        image=?,
                        description=?,
                        status=?
                    WHERE id=?";

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
        } catch (Exception $e) {
            throw $e;
        }
    }

    // Xóa thương hiệu
    public function delete(int $id): bool
    {
        try {

            $sql = "DELETE FROM brands WHERE id=?";

            $stmt = $this->prepare($sql);

            $stmt->bind_param("i", $id);

            return $stmt->execute();
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function search(string $keyword): array
    {
        $list = [];

        $sql = "SELECT *
            FROM brands
            WHERE brandname LIKE ?
            ORDER BY id DESC";

        $stmt = $this->prepare($sql);

        $keyword = "%" . $keyword . "%";

        $stmt->bind_param("s", $keyword);

        $stmt->execute();

        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {

            $brand = new Brand();

            $brand->id = $row["id"];
            $brand->brandname = $row["brandname"];
            $brand->slug = $row["slug"];

            // Nếu bảng brands có cột image
            $brand->image = $row["image"];

            $brand->description = $row["description"];
            $brand->status = $row["status"];

            $brand->createdAt = $row["created_at"];
            $brand->updatedAt = $row["updated_at"];

            $list[] = $brand;
        }

        return $list;
    }
    public function existsBySlugExceptId(string $slug, int $id): bool
{
    $sql = "
        SELECT id
        FROM brands
        WHERE slug = ?
        AND id != ?
        LIMIT 1
    ";

    $stmt = $this->prepare($sql);

    $stmt->bind_param("si", $slug, $id);

    $stmt->execute();

    $result = $stmt->get_result();

    return $result->num_rows > 0;
}
}
