<div class="container py-5">

    <?php if (!isset($product) || !$product): ?>

        <!-- ==========================================
             KHÔNG TÌM THẤY
        =========================================== -->

        <div class="alert alert-danger text-center">

            <h4 class="mb-2">
                <i class="bi bi-exclamation-circle me-2"></i>
                Không tìm thấy sản phẩm
            </h4>

            <p class="mb-3">
                Sản phẩm không tồn tại hoặc đã bị xóa.
            </p>

            <a
                href="<?= BASE_URL ?>product"
                class="btn btn-primary"
            >

                <i class="bi bi-arrow-left me-1"></i>

                Quay lại sản phẩm

            </a>

        </div>


    <?php else: ?>


        <?php

        $price = (float)($product->price ?? 0);

        $discountPrice =
            (float)($product->discountPrice ?? 0);

        $quantity =
            (int)($product->quantity ?? 0);

        ?>


        <!-- ==========================================
             BREADCRUMB
        =========================================== -->

        <nav aria-label="breadcrumb" class="mb-4">

            <ol class="breadcrumb">

                <li class="breadcrumb-item">

                    <a
                        href="<?= BASE_URL ?>"
                        class="text-decoration-none"
                    >
                        Trang chủ
                    </a>

                </li>

                <li class="breadcrumb-item">

                    <a
                        href="<?= BASE_URL ?>product"
                        class="text-decoration-none"
                    >
                        Sản phẩm
                    </a>

                </li>

                <li
                    class="breadcrumb-item active"
                    aria-current="page"
                >

                    <?= htmlspecialchars(
                        $product->proname ?? "Chi tiết"
                    ) ?>

                </li>

            </ol>

        </nav>


        <!-- ==========================================
             CHI TIẾT
        =========================================== -->

        <div class="row g-5">


            <!-- ==========================================
                 HÌNH ẢNH
            =========================================== -->

            <div class="col-md-6">

                <div class="card shadow-sm border-0">

                    <div class="card-body">

                        <?php if (!empty($product->image)): ?>

                            <img
                                src="<?= PRODUCT_IMAGE_URL . htmlspecialchars($product->image) ?>"
                                alt="<?= htmlspecialchars($product->proname ?? 'Sản phẩm') ?>"
                                class="img-fluid w-100"
                                style="
                                    height:450px;
                                    object-fit:contain;
                                "
                            >

                        <?php else: ?>

                            <div
                                class="d-flex align-items-center justify-content-center bg-light rounded"
                                style="height:450px;"
                            >

                                <div class="text-center text-muted">

                                    <i
                                        class="bi bi-image"
                                        style="font-size:80px;"
                                    ></i>

                                    <p class="mt-3 mb-0">
                                        Không có hình ảnh
                                    </p>

                                </div>

                            </div>

                        <?php endif; ?>

                    </div>

                </div>

            </div>


            <!-- ==========================================
                 THÔNG TIN
            =========================================== -->

            <div class="col-md-6">


                <!-- TÊN -->

                <h1 class="fw-bold mb-3">

                    <?= htmlspecialchars(
                        $product->proname ?? "Sản phẩm"
                    ) ?>

                </h1>


                <!-- ĐÁNH GIÁ GIẢ -->
                <!-- Có thể bỏ phần này nếu chưa cần -->

                <div class="mb-3">

                    <span class="text-warning">

                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star"></i>

                    </span>

                    <span class="text-muted ms-2">
                        Sản phẩm
                    </span>

                </div>


                <!-- ==========================================
                     GIÁ
                =========================================== -->

                <div class="mb-4">

                    <?php if (
                        $discountPrice > 0 &&
                        $discountPrice < $price
                    ): ?>

                        <div>

                            <del class="text-muted fs-5">

                                <?= number_format(
                                    $price,
                                    0,
                                    ",",
                                    "."
                                ) ?>

                                đ

                            </del>

                        </div>


                        <div class="text-danger fw-bold fs-2">

                            <?= number_format(
                                $discountPrice,
                                0,
                                ",",
                                "."
                            ) ?>

                            đ

                        </div>


                        <span class="badge bg-danger mt-2">

                            Giảm giá

                        </span>

                    <?php else: ?>

                        <div class="text-danger fw-bold fs-2">

                            <?= number_format(
                                $price,
                                0,
                                ",",
                                "."
                            ) ?>

                            đ

                        </div>

                    <?php endif; ?>

                </div>


                <hr>


                <!-- ==========================================
                     DANH MỤC
                =========================================== -->

                <div class="mb-3">

                    <strong>

                        <i class="bi bi-grid me-2"></i>

                        Danh mục:

                    </strong>

                    <span class="ms-2">

                        <?= htmlspecialchars(
                            $product->categoryName
                            ?? "Chưa có"
                        ) ?>

                    </span>

                </div>


                <!-- ==========================================
                     THƯƠNG HIỆU
                =========================================== -->

                <div class="mb-3">

                    <strong>

                        <i class="bi bi-tags me-2"></i>

                        Thương hiệu:

                    </strong>

                    <span class="ms-2">

                        <?= htmlspecialchars(
                            $product->brandName
                            ?? "Chưa có"
                        ) ?>

                    </span>

                </div>


                <!-- ==========================================
                     SỐ LƯỢNG
                =========================================== -->

                <div class="mb-3">

                    <strong>

                        <i class="bi bi-box-seam me-2"></i>

                        Số lượng:

                    </strong>

                    <span class="ms-2">

                        <?= $quantity ?>

                    </span>

                </div>


                <!-- ==========================================
                     TRẠNG THÁI
                =========================================== -->

                <div class="mb-4">

                    <strong>

                        <i class="bi bi-check-circle me-2"></i>

                        Trạng thái:

                    </strong>


                    <?php if ($quantity > 0): ?>

                        <span class="badge bg-success ms-2">

                            Còn hàng

                        </span>

                    <?php else: ?>

                        <span class="badge bg-danger ms-2">

                            Hết hàng

                        </span>

                    <?php endif; ?>

                </div>


                <hr>


                <!-- ==========================================
                     MÔ TẢ
                =========================================== -->

                <h5 class="fw-bold mb-3">

                    <i class="bi bi-info-circle me-2"></i>

                    Mô tả sản phẩm

                </h5>


                <div class="text-muted mb-4">

                    <?php if (
                        !empty($product->description)
                    ): ?>

                        <?= nl2br(
                            htmlspecialchars(
                                $product->description
                            )
                        ) ?>

                    <?php else: ?>

                        <span>
                            Chưa có mô tả sản phẩm.
                        </span>

                    <?php endif; ?>

                </div>


                <!-- ==========================================
                     MUA HÀNG
                =========================================== -->

                <div class="d-flex gap-2">


                    <?php if ($quantity > 0): ?>

                        <button
                            type="button"
                            class="btn btn-primary btn-lg"
                        >

                            <i class="bi bi-cart-plus me-2"></i>

                            Thêm vào giỏ hàng

                        </button>


                        <button
                            type="button"
                            class="btn btn-danger btn-lg"
                        >

                            Mua ngay

                        </button>

                    <?php else: ?>

                        <button
                            type="button"
                            class="btn btn-secondary btn-lg"
                            disabled
                        >

                            <i class="bi bi-x-circle me-2"></i>

                            Hết hàng

                        </button>

                    <?php endif; ?>


                </div>


            </div>

        </div>


        <!-- ==========================================
             MÔ TẢ CHI TIẾT
        =========================================== -->

        <div class="card shadow-sm border-0 mt-5">

            <div class="card-body p-4">

                <h4 class="fw-bold mb-4">

                    <i class="bi bi-file-text me-2"></i>

                    Thông tin sản phẩm

                </h4>


                <div class="row">

                    <div class="col-md-6">


                        <p>

                            <strong>
                                Tên sản phẩm:
                            </strong>

                            <?= htmlspecialchars(
                                $product->proname ?? ""
                            ) ?>

                        </p>


                        <p>

                            <strong>
                                Danh mục:
                            </strong>

                            <?= htmlspecialchars(
                                $product->categoryName
                                ?? "Chưa có"
                            ) ?>

                        </p>


                    </div>


                    <div class="col-md-6">


                        <p>

                            <strong>
                                Thương hiệu:
                            </strong>

                            <?= htmlspecialchars(
                                $product->brandName
                                ?? "Chưa có"
                            ) ?>

                        </p>


                        <p>

                            <strong>
                                Số lượng:
                            </strong>

                            <?= $quantity ?>

                        </p>


                    </div>

                </div>

            </div>

        </div>


        <!-- ==========================================
             QUAY LẠI
        =========================================== -->

        <div class="mt-4">

            <a
                href="<?= BASE_URL ?>product"
                class="btn btn-outline-secondary"
            >

                <i class="bi bi-arrow-left me-2"></i>

                Tiếp tục xem sản phẩm

            </a>

        </div>


    <?php endif; ?>

</div>