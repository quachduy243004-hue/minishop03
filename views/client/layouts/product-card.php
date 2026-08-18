<?php
$price = (float)($product->price ?? 0);
$discountPrice = (float)($product->discountPrice ?? 0);
?>

<div class="card h-100 shadow-sm">

    <img
        src="/MiniShop_quachvanduy/uploads/products/<?= htmlspecialchars($product->image ?? '') ?>"
        class="card-img-top"
        alt="<?= htmlspecialchars($product->proname ?? 'Sản phẩm') ?>"
        style="height:220px; object-fit:contain;"
    >

    <div class="card-body">

        <h5 class="card-title">
            <?= htmlspecialchars($product->proname ?? 'Sản phẩm') ?>
        </h5>

        <?php if ($discountPrice > 0 && $discountPrice < $price): ?>

            <del class="text-muted">
                <?= number_format($price) ?> đ
            </del>

            <p class="text-danger fw-bold fs-5">
                <?= number_format($discountPrice) ?> đ
            </p>

        <?php else: ?>

            <p class="text-danger fw-bold fs-5">
                <?= number_format($price) ?> đ
            </p>

        <?php endif; ?>

        <div class="d-flex justify-content-end gap-2">

            <a
                href="#"
                class="btn btn-outline-secondary btn-sm"
                title="Xem chi tiết"
            >
                <i class="bi bi-eye"></i>
            </a>

            <button
                type="button"
                class="btn btn-primary btn-sm"
                title="Mua hàng"
            >
                <i class="bi bi-cart-plus"></i>
            </button>

        </div>

    </div>

</div>