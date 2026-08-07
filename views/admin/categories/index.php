<?php
require_once "../../../dao/CategoryDAO.php";

$pageTitle = "Quản lý danh mục";

$categoryDAO = new CategoryDAO();

$message = "";

// Xử lý xóa
if (isset($_POST["btnDelete"])) {

    $id = intval($_POST["id"]);

    if ($categoryDAO->delete($id)) {

        header("Location: index.php");
        exit;
    } else {

        $message = "Xóa danh mục thất bại!";
    }
}

// Tìm kiếm
$keyword = trim($_GET["keyword"] ?? "");

if ($keyword == "") {

    $list = $categoryDAO->getAll();
} else {

    $list = $categoryDAO->search($keyword);
}

ob_start();
?>

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-3">

        <h3 class="mb-0">Quản lý danh mục</h3>

        <form method="GET" class="d-flex">

            <input
                type="search"
                name="keyword"
                class="form-control me-2"
                style="width:250px"
                placeholder="Nhập tên danh mục"
                value="<?= htmlspecialchars($_GET["keyword"] ?? "") ?>">

            <button type="submit" class="btn btn-success me-2">
                <i class="fa fa-search"></i> Tìm
            </button>

            <a href="index.php" class="btn btn-secondary">
                Làm mới
            </a>

        </form>

        <a href="create.php" class="btn btn-primary">
            <i class="fa fa-plus"></i> Thêm mới
        </a>

    </div>

    <?php if ($message != "") : ?>

        <div class="alert alert-danger">
            <?= $message ?>
        </div>

    <?php endif; ?>

    <div class="card shadow">

        <div class="card-body">

            <table class="table table-bordered table-hover align-middle">

                <thead class="table-dark text-center">

                    <tr>
                        <th width="60">STT</th>
                        <th width="90">Ảnh</th>
                        <th>Tên danh mục</th>
                        <th>Slug</th>
                        <th width="120">Trạng thái</th>
                        <th width="170">Ngày tạo</th>
                        <th width="220">Chức năng</th>
                    </tr>

                </thead>

                <tbody>

                    <?php if (!empty($list)): ?>

                        <?php $stt = 1; ?>

                        <?php foreach ($list as $item): ?>

                            <tr>

                                <td class="text-center">
                                    <?= $stt++ ?>
                                </td>

                                <td class="text-center">

                                    <?php if (!empty($item->image)): ?>

                                        <img
                                            src="../../../uploads/category/<?= htmlspecialchars($item->image) ?>"
                                            width="70"
                                            height="70"
                                            style="object-fit:cover;border-radius:6px;">

                                    <?php else: ?>

                                        <span class="text-muted">
                                            Không có ảnh
                                        </span>

                                    <?php endif; ?>

                                </td>

                                <td>
                                    <?= htmlspecialchars($item->name) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($item->slug) ?>
                                </td>

                                <td class="text-center">

                                    <?php if ($item->status == 1): ?>

                                        <span class="badge bg-success">
                                            Hiển thị
                                        </span>

                                    <?php else: ?>

                                        <span class="badge bg-danger">
                                            Ẩn
                                        </span>

                                    <?php endif; ?>

                                </td>

                                <td class="text-center">
                                    <?= $item->createdAt ?>
                                </td>

                                <td class="text-center">

                                    <a href="detail.php?id=<?= $item->id ?>"
                                        class="btn btn-info btn-sm">

                                        <i class="fa fa-eye"></i>

                                    </a>

                                    <a href="edit.php?id=<?= $item->id ?>"
                                        class="btn btn-warning btn-sm">

                                        <i class="fa fa-edit"></i> Sửa

                                    </a>

                                    <form
                                        method="POST"
                                        style="display:inline"
                                        onsubmit="return confirm('Bạn có chắc muốn xóa?');">

                                        <input
                                            type="hidden"
                                            name="id"
                                            value="<?= $item->id ?>">

                                        <button
                                            type="submit"
                                            name="btnDelete"
                                            class="btn btn-danger btn-sm">

                                            <i class="fa fa-trash"></i> xóa

                                        </button>

                                    </form>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>

                            <td colspan="7" class="text-center text-danger">

                                Không tìm thấy dữ liệu.

                            </td>

                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<?php
$content = ob_get_clean();
include "../layouts/master.php";
?>