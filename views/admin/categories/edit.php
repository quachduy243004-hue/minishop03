<?php
require_once "../../../dao/CategoryDAO.php";
require_once "../../../models/Category.php";

$pageTitle = "Cập nhật danh mục";

$categoryDAO = new CategoryDAO();

$id = $_GET["id"] ?? 0;

$category = $categoryDAO->findById($id);

if (!$category) {
    die("Danh mục không tồn tại.");
}

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $category->name = trim($_POST["cateName"] ?? "");
    $category->slug = trim($_POST["slug"] ?? "");
    $category->description = trim($_POST["description"] ?? "");
    $category->status = $_POST["status"] ?? 1;

    // Validation
    if ($category->name == "") {
        $errors[] = "Tên danh mục không được để trống.";
    }

    if ($category->slug == "") {
        $errors[] = "Slug không được để trống.";
    }

    if (strlen($category->name) > 100) {
        $errors[] = "Tên danh mục tối đa 100 ký tự.";
    }

    if (strlen($category->slug) > 100) {
        $errors[] = "Slug tối đa 100 ký tự.";
    }

    if (empty($errors)) {

        if ($categoryDAO->update($category)) {

            header("Location: index.php");
            exit;

        } else {

            $errors[] = "Cập nhật thất bại.";

        }
    }
}

ob_start();
?>

<div class="container-fluid">

    <div class="card shadow">

        <div class="card-header bg-warning">
            <h4 class="mb-0">
                Cập nhật danh mục
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

                <input
                    type="hidden"
                    name="id"
                    value="<?= $category->id ?>">

                <div class="mb-3">

                    <label class="form-label">
                        Tên danh mục
                    </label>

                    <input
                        type="text"
                        name="cateName"
                        class="form-control"
                        value="<?= htmlspecialchars($category->name) ?>">

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Slug
                    </label>

                    <input
                        type="text"
                        name="slug"
                        class="form-control"
                        value="<?= htmlspecialchars($category->slug) ?>">

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Mô tả
                    </label>

                    <textarea
                        name="description"
                        rows="5"
                        class="form-control"><?= htmlspecialchars($category->description) ?></textarea>

                </div>

                <div class="mb-3">

                    <label class="form-label d-block">
                        Trạng thái
                    </label>

                    <div class="form-check form-check-inline">

                        <input
                            class="form-check-input"
                            type="radio"
                            name="status"
                            value="1"
                            <?= $category->status == 1 ? "checked" : "" ?>>

                        <label class="form-check-label">
                            Hiển thị
                        </label>

                    </div>

                    <div class="form-check form-check-inline">

                        <input
                            class="form-check-input"
                            type="radio"
                            name="status"
                            value="0"
                            <?= $category->status == 0 ? "checked" : "" ?>>

                        <label class="form-check-label">
                            Ẩn
                        </label>

                    </div>

                </div>

                <button
                    type="submit"
                    class="btn btn-primary">

                    <i class="fa fa-save"></i>
                    Cập nhật

                </button>

                <button
                    type="reset"
                    class="btn btn-warning">

                    Làm mới

                </button>

                <a
                    href="index.php"
                    class="btn btn-secondary">

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