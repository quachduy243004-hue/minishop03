<?php

$price = (float)($product->price ?? 0);

$discountPrice = (float)(
    $product->discountPrice
    ?? $product->pricediscount
    ?? 0
);

$hasDiscount =
    $discountPrice > 0 &&
    $discountPrice < $price;


/*
|--------------------------------------------------------------------------
| LẤY ID SẢN PHẨM
|--------------------------------------------------------------------------
| Một số DAO dùng id
| Một số DAO dùng productid
*/
$productId = (int)(
    $product->id
    ?? $product->productid
    ?? 0
);

?>

<div class="card h-100 shadow-sm">

    <!-- =====================================================
         HÌNH ẢNH
    ====================================================== -->

    <?php if (!empty($product->image)): ?>

        <img
            src="<?= BASE_URL ?>uploads/products/<?= htmlspecialchars($product->image) ?>"
            class="card-img-top"
            alt="<?= htmlspecialchars($product->proname ?? 'Sản phẩm') ?>"
            style="height:220px; object-fit:contain;">

    <?php else: ?>

        <div
            class="d-flex align-items-center justify-content-center bg-light"
            style="height:220px;">

            <span class="text-muted">
                Không có hình ảnh
            </span>

        </div>

    <?php endif; ?>


    <!-- =====================================================
         THÔNG TIN
    ====================================================== -->

    <div class="card-body d-flex flex-column">

        <!-- TÊN SẢN PHẨM -->

        <h5 class="card-title">

            <?= htmlspecialchars(
                $product->proname ?? 'Sản phẩm'
            ) ?>

        </h5>


        <!-- =================================================
             GIÁ
        ================================================== -->

        <?php if ($hasDiscount): ?>

            <!-- GIÁ GỐC -->

            <del class="text-muted">

                <?= number_format(
                    $price,
                    0,
                    ",",
                    "."
                ) ?>

                đ

            </del>


            <!-- GIÁ GIẢM -->

            <p class="text-danger fw-bold fs-5 mb-3">

                <?= number_format(
                    $discountPrice,
                    0,
                    ",",
                    "."
                ) ?>

                đ

            </p>

        <?php else: ?>

            <!-- GIÁ THƯỜNG -->

            <p class="text-danger fw-bold fs-5 mb-3">

                <?= number_format(
                    $price,
                    0,
                    ",",
                    "."
                ) ?>

                đ

            </p>

        <?php endif; ?>


        <!-- =================================================
             BUTTON
        ================================================== -->

        <div class="d-flex justify-content-end gap-2 mt-auto">


            <!-- XEM CHI TIẾT -->

            <a
                href="<?= BASE_URL ?>product/<?= urlencode($product->slug ?? '') ?>"
                class="btn btn-outline-secondary btn-sm"
                title="Xem chi tiết">

                <i class="bi bi-eye"></i>

            </a>


            <!-- =================================================
                 THÊM GIỎ HÀNG
            ================================================== -->

            <?php if ($productId > 0): ?>

                <button
                    type="button"
                    class="btn btn-primary btn-sm btn-add-cart"
                    data-productid="<?= $productId ?>"
                    title="Thêm vào giỏ hàng">
                    <i class="bi bi-cart-plus"></i>
                </button>
            <?php else: ?>

                <button
                    type="button"
                    class="btn btn-secondary btn-sm"
                    disabled
                    title="Không xác định được sản phẩm">

                    <i class="bi bi-cart-x"></i>

                </button>

            <?php endif; ?>


        </div>

    </div>

</div>