<?php
require_once "../../../dao/ProductDAO.php";

$pageTitle = "Quản lý danh mục";

$productDAO = new ProductDAO();

$message = "";

// Xử lý xóa
if (isset($_POST["btnDelete"])) {

    $id = intval($_POST["id"]);

    if ($productDAO->delete($id)) {

        header("Location: index.php");
        exit;
    } else {

        $message = "Xóa danh mục thất bại!";
    }
}
$list = $productDAO->getAll();

ob_start();
?>

<div class="container-fluid">

    <div class="card shadow">

        <div class="card-header bg-white">

            <div class="d-flex justify-content-between align-items-center">

                <h4 class="mb-0">
                    Quản lý sản phẩm
                </h4>

                <a href="create.php" class="btn btn-primary btn-sm">
                    <i class="fa fa-plus-circle"></i>
                    Thêm sản phẩm
                </a>

            </div>

        </div>

        <div class="card-body">

            <form method="GET" class="row g-2 mb-3">

                <div class="col-md-4">

                    <input
                        type="search"
                        name="keyword"
                        class="form-control"
                        placeholder="Nhập tên sản phẩm..."
                        value="<?= htmlspecialchars($_GET["keyword"] ?? "") ?>">

                </div>

                <div class="col-auto">

                    <button class="btn btn-success">
                        <i class="fa fa-search"></i>
                        Tìm kiếm
                    </button>

                    <a href="index.php" class="btn btn-secondary">
                        <i class="fa fa-refresh"></i>
                        Làm mới
                    </a>

                </div>

            </form>

            <?php if (!empty($message)): ?>

                <div class="alert alert-danger">
                    <?= htmlspecialchars($message) ?>
                </div>

            <?php endif; ?>

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle">

                    <thead class="table-dark text-center">

                        <tr>

                            <th width="60">STT</th>
                            <th width="90">Hình ảnh</th>
                            <th>Tên sản phẩm</th>
                            <th>Danh mục</th>
                            <th>Thương hiệu</th>
                            <th width="120">Giá</th>
                            <th width="120">Giảm giá</th>
                            <th width="70">SL</th>
                            <th width="120">Trạng thái</th>
                            <th width="170">Ngày tạo</th>
                            <th width="170">Chức năng</th>

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
                                                src="../../../uploads/products/<?= htmlspecialchars($item->image) ?>"
                                                width="60"
                                                height="60"
                                                class="img-thumbnail">

                                        <?php else: ?>

                                            <span class="text-muted">
                                                No Image
                                            </span>

                                        <?php endif; ?>

                                    </td>

                                    <td>
                                        <?= htmlspecialchars($item->proname ?? "") ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($item->categoryName ?? "") ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($item->brandName ?? "") ?>
                                    </td>

                                    <td class="text-end">
                                        <?= number_format($item->price ?? 0,0,",",".") ?>
                                    </td>

                                    <td class="text-end">
                                        <?= number_format($item->discountPrice ?? 0,0,",",".") ?>
                                    </td>

                                    <td class="text-center">
                                        <?= $item->quantity ?? 0 ?>
                                    </td>

                                    <td class="text-center">

                                        <?php if (($item->status ?? 0) == 1): ?>

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
                                        <?= htmlspecialchars($item->createdAt ?? "") ?>
                                    </td>

                                    <td class="text-center">

                                        <a
                                            href="detail.php?id=<?= $item->id ?>"
                                            class="btn btn-info btn-sm">

                                            <i class="fa fa-eye"></i>

                                        </a>

                                        <a
                                            href="edit.php?id=<?= $item->id ?>"
                                            class="btn btn-warning btn-sm">

                                            <i class="fa fa-edit"></i>

                                        </a>

                                        <form
                                            method="POST"
                                            style="display:inline-block"
                                            onsubmit="return confirm('Bạn có chắc muốn xóa?');">

                                            <input
                                                type="hidden"
                                                name="id"
                                                value="<?= $item->id ?>">

                                            <button
                                                type="submit"
                                                name="btnDelete"
                                                class="btn btn-danger btn-sm">

                                                <i class="fa fa-trash"></i>

                                            </button>

                                        </form>

                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        <?php else: ?>

                            <tr>

                                <td colspan="11" class="text-center text-danger">

                                    Không có dữ liệu.

                                </td>

                            </tr>

                        <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>
<?php
$content = ob_get_clean();
include "../layouts/master.php";
?>
