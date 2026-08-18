<div class="container py-4">

    <!-- =========================
         DANH MỤC NỔI BẬT
    ========================== -->

    <h2 class="mb-4">
        <i class="bi bi-grid me-2"></i>
        Danh mục nổi bật
    </h2>

    <div class="row">

        <?php if (!empty($categories)): ?>

            <?php foreach ($categories as $category): ?>

                <div class="col-12 col-sm-6 col-md-3 mb-3">

                    <div class="card h-100 shadow-sm">

                        <div class="card-body text-center">

                            <i class="bi bi-tag fs-2 text-primary"></i>

                            <h5 class="mt-2">

                                <a
                                    href="<?= BASE_URL ?>/category/<?= urlencode($category->slug) ?>"
                                    class="text-decoration-none"
                                >
                                    <?= htmlspecialchars($category->name) ?>
                                </a>

                            </h5>

                        </div>

                    </div>

                </div>

            <?php endforeach; ?>

        <?php else: ?>

            <div class="col-12">

                <div class="alert alert-info">
                    Chưa có danh mục.
                </div>

            </div>

        <?php endif; ?>

    </div>


    <!-- =========================
         SẢN PHẨM GIẢM GIÁ
    ========================== -->

    <h2 class="mb-4 mt-5">

        <i class="bi bi-fire text-danger me-2"></i>

        Sản phẩm giảm giá

    </h2>

    <div class="row">

        <?php if (!empty($discountProducts)): ?>

            <?php foreach ($discountProducts as $product): ?>

                <div class="col-12 col-sm-6 col-md-3 mb-4">

                    <?php
                    require __DIR__ . "/../layouts/product-card.php";
                    ?>

                </div>

            <?php endforeach; ?>

        <?php else: ?>

            <div class="col-12">

                <div class="alert alert-info">
                    Chưa có sản phẩm giảm giá.
                </div>

            </div>

        <?php endif; ?>

    </div>


    <!-- =========================
         SẢN PHẨM MỚI
    ========================== -->

    <h2 class="mb-4 mt-4">

        <i class="bi bi-stars text-warning me-2"></i>

        Sản phẩm mới nhất

    </h2>

    <div class="row">

        <?php if (!empty($newProducts)): ?>

            <?php foreach ($newProducts as $product): ?>

                <div class="col-12 col-sm-6 col-md-3 mb-4">

                    <?php
                    require __DIR__ . "/../layouts/product-card.php";
                    ?>

                </div>

            <?php endforeach; ?>

        <?php else: ?>

            <div class="col-12">

                <div class="alert alert-info">
                    Chưa có sản phẩm mới.
                </div>

            </div>

        <?php endif; ?>

    </div>

</div>