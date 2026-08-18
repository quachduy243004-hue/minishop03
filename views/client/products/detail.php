<div class="container py-5">

    <?php if (!isset($product) || !$product): ?>

        <div class="alert alert-danger text-center">

            <h4 class="mb-2">
                Không tìm thấy sản phẩm
            </h4>

            <p class="mb-3">
                Sản phẩm không tồn tại hoặc đã bị xóa.
            </p>

            <a
                href="<?= BASE_URL ?>/product"
                class="btn btn-primary"
            >
                <i class="bi bi-arrow-left"></i>
                Quay lại sản phẩm
            </a>

        </div>

    <?php else: ?>


        <div class="row g-5">


            <!-- ==========================================
                 HÌNH ẢNH
            =========================================== -->

            <div class="col-md-6">

                <div class="card shadow-sm">

                    <div class="card-body text-center">

                        <?php if (!empty($product->image)): ?>

                            <img
                                src="<?= PRODUCT_IMAGE_URL . htmlspecialchars($product->image) ?>"
                                alt="<?= htmlspecialchars($product->proname) ?>"
                                class="img-fluid"
                                style="
                                    width:100%;
                                    height:450px;
                                    object-fit:contain;
                                "
                            >

                        <?php else: ?>

                            <div
                                class="d-flex align-items-center justify-content-center bg-light"
                                style="height:450px;"
                            >

                                <span class="text-muted">
                                    Không có hình ảnh
                                </span>

                            </div>

                        <?php endif; ?>

                    </div>

                </div>

            </div>


            <!-- ==========================================
                 THÔNG TIN SẢN PHẨM
            =========================================== -->

            <div class="col-md-6">

                <!-- TÊN -->

                <h2 class="fw-bold mb-3">

                    <?= htmlspecialchars(
                        $product->proname
                    ) ?>

                </h2>


                <!-- GIÁ -->

                <?php

                $price =
                    (float)$product->price;

                $discountPrice =
                    (float)$product->discountPrice;

                ?>

                <?php if (
                    $discountPrice > 0 &&
                    $discountPrice < $price
                ): ?>

                    <div class="mb-4">

                        <div>

                            <del class="text-muted fs-5">

                                <?= number_format($price) ?> đ

                            </del>

                        </div>

                        <div class="text-danger fw-bold fs-2">

                            <?= number_format($discountPrice) ?> đ

                        </div>

                    </div>

                <?php else: ?>

                    <div class="text-danger fw-bold fs-2 mb-4">

                        <?= number_format($price) ?> đ

                    </div>

                <?php endif; ?>


                <hr>


                <!-- DANH MỤC -->

                <div class="mb-3">

                    <strong>

                        <i class="bi bi-grid"></i>

                        Danh mục:

                    </strong>

                    <span>

                        <?= htmlspecialchars(
                            $product->categoryName ?: "Chưa có"
                        ) ?>

                    </span>

                </div>


                <!-- THƯƠNG HIỆU -->

                <div class="mb-3">

                    <strong>

                        <i class="bi bi-tags"></i>

                        Thương hiệu:

                    </strong>

                    <span>

                        <?= htmlspecialchars(
                            $product->brandName ?: "Chưa có"
                        ) ?>

                    </span>

                </div>


                <!-- SỐ LƯỢNG -->

                <div class="mb-3">

                    <strong>

                        <i class="bi bi-box-seam"></i>

                        Số lượng:

                    </strong>

                    <span>

                        <?= (int)$product->quantity ?>

                    </span>

                </div>


                <hr>


                <!-- MÔ TẢ -->

                <h5 class="fw-bold mb-3">

                    <i class="bi bi-info-circle"></i>

                    Mô tả sản phẩm

                </h5>


                <div class="text-muted mb-4">

                    <?php if (!empty($product->description)): ?>

                        <?= nl2br(
                            htmlspecialchars(
                                $product->description
                            )
                        ) ?>

                    <?php else: ?>

                        Chưa có mô tả sản phẩm.

                    <?php endif; ?>

                </div>


                <!-- BUTTON -->

                <div class="d-flex gap-2">

                    <button
                        type="button"
                        class="btn btn-primary"
                    >

                        <i class="bi bi-cart-plus"></i>

                        Thêm vào giỏ hàng

                    </button>


                    <a
                        href="<?= BASE_URL ?>/product"
                        class="btn btn-outline-secondary"
                    >

                        <i class="bi bi-arrow-left"></i>

                        Quay lại

                    </a>

                </div>

            </div>

        </div>

    <?php endif; ?>

</div>