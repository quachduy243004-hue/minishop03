<?php

require_once __DIR__ . "/BaseDAO.php";
require_once __DIR__ . "/../models/User.php";

class UserDAO extends BaseDAO
{
    public function __construct()
    {
        parent::__construct();
    }


    // ================================
    // LẤY TẤT CẢ USER
    // ================================

    public function getAll(): array
    {
        $list = [];

        $sql = "SELECT * FROM users ORDER BY fullname";

        $result = $this->executeQuery($sql);

        while ($row = $result->fetch_assoc()) {

            $user = new User(
                $row["fullname"],
                $row["username"],
                $row["password"],
                $row["email"],
                $row["phone"],
                $row["address"],
                $row["role"],
                $row["status"]
            );

            $user->id = $row["id"];

            if (isset($row["created_at"])) {
                $user->createdAt = $row["created_at"];
            }

            if (isset($row["updated_at"])) {
                $user->updatedAt = $row["updated_at"];
            }

            $list[] = $user;
        }

        return $list;
    }


    // ================================
    // TÌM USER THEO ID
    // ================================

    public function findById(int $id): ?User
    {
        $sql = "SELECT * FROM users WHERE id = ?";

        $stmt = $this->prepare($sql);

        $stmt->bind_param("i", $id);

        $stmt->execute();

        $result = $stmt->get_result();

        $row = $result->fetch_assoc();

        if (!$row) {
            return null;
        }

        $user = new User(
            $row["fullname"],
            $row["username"],
            $row["password"],
            $row["email"],
            $row["phone"],
            $row["address"],
            $row["role"],
            $row["status"]
        );

        $user->id = $row["id"];

        if (isset($row["created_at"])) {
            $user->createdAt = $row["created_at"];
        }

        if (isset($row["updated_at"])) {
            $user->updatedAt = $row["updated_at"];
        }

        return $user;
    }


    // ================================
    // TÌM USER THEO USERNAME
    // ================================

    public function findByUsername(string $username): ?User
    {
        $sql = "SELECT * FROM users WHERE username = ?";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            return null;
        }

        $stmt->bind_param("s", $username);

        $stmt->execute();

        $result = $stmt->get_result();

        $row = $result->fetch_assoc();

        if (!$row) {
            return null;
        }

        $user = new User(
            $row["fullname"],
            $row["username"],
            $row["password"],
            $row["email"],
            $row["phone"],
            $row["address"],
            $row["role"],
            $row["status"]
        );

        $user->id = $row["id"];

        if (isset($row["created_at"])) {
            $user->createdAt = $row["created_at"];
        }

        if (isset($row["updated_at"])) {
            $user->updatedAt = $row["updated_at"];
        }

        return $user;
    }


    // ================================
    // THÊM USER
    // ================================

    public function insert(User $user): bool
    {
        $sql = "INSERT INTO users
                (
                    fullname,
                    username,
                    password,
                    email,
                    phone,
                    address,
                    role,
                    status
                )
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->prepare($sql);

        $stmt->bind_param(
            "ssssssii",
            $user->fullname,
            $user->username,
            $user->password,
            $user->email,
            $user->phone,
            $user->address,
            $user->role,
            $user->status
        );

        return $stmt->execute();
    }


    // ================================
    // UPDATE USER
    // ================================

    public function update(User $user): bool
    {
        $sql = "UPDATE users
                SET
                    fullname = ?,
                    username = ?,
                    password = ?,
                    email = ?,
                    phone = ?,
                    address = ?,
                    role = ?,
                    status = ?
                WHERE id = ?";

        $stmt = $this->prepare($sql);

        $stmt->bind_param(
            "ssssssiii",
            $user->fullname,
            $user->username,
            $user->password,
            $user->email,
            $user->phone,
            $user->address,
            $user->role,
            $user->status,
            $user->id
        );

        return $stmt->execute();
    }


    // ================================
    // XÓA USER
    // ================================

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM users WHERE id = ?";

        $stmt = $this->prepare($sql);

        $stmt->bind_param("i", $id);

        return $stmt->execute();
    }
}