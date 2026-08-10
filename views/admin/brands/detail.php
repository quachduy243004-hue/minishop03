<?php

require_once "../../../dao/BrandDAO.php";

$pageTitle = "Chi tiết thương hiệu";

$brandDAO = new BrandDAO();


$id = intval($_GET["id"] ?? 0);

if ($id <= 0) {

    header("Location: index.php");
    exit;
}



$brand = $brandDAO->findById($id);

if (!$brand) {

    header("Location: index.php");
    exit;
}



ob_start();

?>

<div class="container-fluid">


    <div class="d-flex justify-content-between align-items-center mb-3">

        <h3 class="mb-0">
            Chi tiết thương hiệu
        </h3>

        <a
            href="index.php"
            class="btn btn-secondary"
        >
            <i class="fa fa-arrow-left"></i>
            Quay lại
        </a>

    </div>


    <!-- CARD -->

    <div class="card shadow-sm">

        <div class="card-header bg-dark text-white">

            <h5 class="mb-0">

                Thông tin thương hiệu

            </h5>

        </div>


        <div class="card-body">

            <div class="row">



                <div class="col-md-4 text-center">

                    <label class="fw-bold d-block mb-3">

                        Hình ảnh

                    </label>


                    <?php if (!empty($brand->image)): ?>

                        <img
                            src="/MiniShop_quachvanduy/uploads/brand/<?= htmlspecialchars($brand->image) ?>"
                            alt="<?= htmlspecialchars($brand->brandname) ?>"
                            class="img-thumbnail"
                            style="
                                width: 250px;
                                height: 250px;
                                object-fit: contain;
                            "
                        >

                    <?php else: ?>

                        <div
                            class="border rounded d-flex align-items-center justify-content-center text-muted"
                            style="
                                width: 250px;
                                height: 250px;
                                margin: auto;
                            "
                        >

                            <div>

                                <i class="fa fa-image fa-3x mb-2"></i>

                                <br>

                                Chưa có hình ảnh

                            </div>

                        </div>

                    <?php endif; ?>


                </div>



                <div class="col-md-8">

                    <div class="row mb-3">

                        <div class="col-md-4 fw-bold">
                            ID
                        </div>

                        <div class="col-md-8">
                            <?= $brand->id ?>
                        </div>

                    </div>


                    <hr>


                    <div class="row mb-3">

                        <div class="col-md-4 fw-bold">
                            Tên thương hiệu
                        </div>

                        <div class="col-md-8">

                            <?= htmlspecialchars($brand->brandname) ?>

                        </div>

                    </div>


                    <hr>


                    <div class="row mb-3">

                        <div class="col-md-4 fw-bold">
                            Slug
                        </div>

                        <div class="col-md-8">

                            <code>
                                <?= htmlspecialchars($brand->slug) ?>
                            </code>

                        </div>

                    </div>


                    <hr>


                    <div class="row mb-3">

                        <div class="col-md-4 fw-bold">
                            Trạng thái
                        </div>

                        <div class="col-md-8">

                            <?php if ($brand->status == 1): ?>

                                <span class="badge bg-success">

                                    <i class="fa fa-check"></i>

                                    Hiển thị

                                </span>

                            <?php else: ?>

                                <span class="badge bg-secondary">

                                    <i class="fa fa-eye-slash"></i>

                                    Ẩn

                                </span>

                            <?php endif; ?>

                        </div>

                    </div>


                    <hr>


                    <div class="row mb-3">

                        <div class="col-md-4 fw-bold">
                            Mô tả
                        </div>

                        <div class="col-md-8">

                            <?php if (!empty($brand->description)): ?>

                                <?= nl2br(
                                    htmlspecialchars($brand->description)
                                ) ?>

                            <?php else: ?>

                                <span class="text-muted">

                                    Chưa có mô tả.

                                </span>

                            <?php endif; ?>

                        </div>

                    </div>


                </div>

            </div>

        </div>



        <div class="card-footer bg-white">

            <a
                href="edit.php?id=<?= $brand->id ?>"
                class="btn btn-warning"
            >

                <i class="fa fa-edit"></i>

                Sửa thương hiệu

            </a>


            <a
                href="index.php"
                class="btn btn-secondary"
            >

                <i class="fa fa-arrow-left"></i>

                Quay lại

            </a>

        </div>

    </div>

</div>


<?php

$content = ob_get_clean();

include "../layouts/master.php";

?>