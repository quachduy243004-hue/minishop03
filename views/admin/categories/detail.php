<?php
require_once "../../../dao/CategoryDAO.php";

$pageTitle = "Chi tiết danh mục";

$categoryDAO = new CategoryDAO();

$id = $_GET["id"] ?? 0;

$category = $categoryDAO->findById($id);

if (!$category) {
    die("Danh mục không tồn tại.");
}

ob_start();
?>

<div class="container-fluid">

    <div class="card shadow">

        <div class="card-header bg-info text-white">
            <h4 class="mb-0">
                Chi tiết danh mục
            </h4>
        </div>

        <div class="card-body">

            <table class="table table-bordered">

                <tr>
                    <th width="220">Mã danh mục</th>
                    <td><?= $category->id ?></td>
                </tr>

                <tr>
                    <th>Tên danh mục</th>
                    <td><?= htmlspecialchars($category->name) ?></td>
                </tr>

                <tr>
                    <th>Slug</th>
                    <td><?= htmlspecialchars($category->slug) ?></td>
                </tr>

                <tr>
                    <th>Ảnh</th>
                    <td>

                        <?php if (!empty($category->image)) : ?>

                            <img
                                src="../../../uploads/category/<?= htmlspecialchars($category->image) ?>"
                                width="150"
                                class="img-thumbnail">

                        <?php else : ?>

                            <span class="text-muted">
                                Không có ảnh
                            </span>

                        <?php endif; ?>

                    </td>
                </tr>

                <tr>
                    <th>Mô tả</th>
                    <td><?= nl2br(htmlspecialchars($category->description)) ?></td>
                </tr>

                <tr>
                    <th>Trạng thái</th>
                    <td>

                        <?php if ($category->status == 1) : ?>

                            <span class="badge bg-success">
                                Hiển thị
                            </span>

                        <?php else : ?>

                            <span class="badge bg-danger">
                                Ẩn
                            </span>

                        <?php endif; ?>

                    </td>
                </tr>

                <tr>
                    <th>Ngày tạo</th>
                    <td><?= $category->createdAt ?></td>
                </tr>

                <tr>
                    <th>Ngày cập nhật</th>
                    <td><?= $category->updatedAt ?></td>
                </tr>

            </table>

            <a href="index.php" class="btn btn-secondary">
                <i class="fa fa-arrow-left"></i>
                Quay lại
            </a>

            <a href="edit.php?id=<?= $category->id ?>" class="btn btn-warning">
                <i class="fa fa-edit"></i>
                Cập nhật
            </a>

        </div>

    </div>

</div>

<?php
$content = ob_get_clean();
include "../layouts/master.php";
?>