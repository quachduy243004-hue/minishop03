<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <h2 class="fw-bold mb-0">

            <i class="bi bi-grid me-2"></i>

            <?= htmlspecialchars($title ?? "Danh sách sản phẩm") ?>

        </h2>

    </div>


    <?php if (empty($products)): ?>

        <div class="alert alert-warning text-center">

            <i class="bi bi-exclamation-circle me-2"></i>

            Không tìm thấy sản phẩm.

        </div>

    <?php else: ?>

        <div class="row">

            <?php foreach ($products as $product): ?>

                <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">

                    <?php
                    require __DIR__ . "/../layouts/product-card.php";
                    ?>

                </div>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>

</div>