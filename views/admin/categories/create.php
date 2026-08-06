<?php
require_once "../../../dao/CategoryDAO.php";
require_once "../../../models/Category.php";

$pageTitle = "Thêm danh mục";

$categoryDAO = new CategoryDAO();

$cateName = "";
$slug = "";
$description = "";
$status = 1;

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $cateName = trim($_POST["cateName"] ?? "");
    $slug = trim($_POST["slug"] ?? "");
    $description = trim($_POST["description"] ?? "");
    $status = $_POST["status"] ?? 1;

    // Validation
    if ($cateName == "") {
        $errors[] = "Tên danh mục không được để trống.";
    }

    if ($slug == "") {
        $errors[] = "Slug không được để trống.";
    }

    if (strlen($cateName) > 100) {
        $errors[] = "Tên danh mục tối đa 100 ký tự.";
    }

    if (strlen($slug) > 100) {
        $errors[] = "Slug tối đa 100 ký tự.";
    }

    // Không có lỗi thì lưu
    if (empty($errors)) {

        $category = new Category(
            $cateName,
            $slug,
            null,
            $description,
            $status
        );

        if ($categoryDAO->insert($category)) {

            header("Location: index.php");
            exit;

        } else {

            $errors[] = "Thêm danh mục thất bại.";

        }
    }
}

ob_start();
?>

<div class="container-fluid">

    <div class="card shadow">

        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">
                Thêm danh mục
            </h4>
        </div>

        <div class="card-body">

            <?php if (!empty($errors)): ?>

                <div class="alert alert-danger">

                    <ul class="mb-0">

                        <?php foreach ($errors as $error): ?>

                            <li><?= $error ?></li>

                        <?php endforeach; ?>

                    </ul>

                </div>

            <?php endif; ?>

            <form method="POST">

                <div class="mb-3">

                    <label class="form-label">
                        Tên danh mục
                    </label>

                    <input
                        type="text"
                        name="cateName"
                        class="form-control"
                        value="<?= htmlspecialchars($cateName) ?>">

                </div>

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

                <div class="mb-3">

                    <label class="form-label">
                        Mô tả
                    </label>

                    <textarea
                        name="description"
                        class="form-control"
                        rows="4"><?= htmlspecialchars($description) ?></textarea>

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Trạng thái
                    </label>

                    <select
                        name="status"
                        class="form-select">

                        <option value="1"
                            <?= $status == 1 ? "selected" : "" ?>>
                            Hiển thị
                        </option>

                        <option value="0"
                            <?= $status == 0 ? "selected" : "" ?>>
                            Ẩn
                        </option>

                    </select>

                </div>

                <button
                    type="submit"
                    class="btn btn-success">

                    <i class="fa fa-save"></i>
                    Lưu

                </button>

                <button
                    type="reset"
                    class="btn btn-secondary">

                    Làm mới

                </button>

                <a
                    href="index.php"
                    class="btn btn-danger">

                    Quay lại

                </a>

            </form>

        </div>

    </div>

</div>

<?php
$content = ob_get_clean();
include "../layouts/master.php";
?>