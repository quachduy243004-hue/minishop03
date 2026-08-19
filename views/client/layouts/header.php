<?php

use Composers\HeaderComposer;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/*
|--------------------------------------------------------------------------
| HEADER DATA
|--------------------------------------------------------------------------
*/

$headerData = HeaderComposer::compose();

$categories = $headerData['categories'] ?? [];
$brands     = $headerData['brands'] ?? [];

/*
|--------------------------------------------------------------------------
| CART
|--------------------------------------------------------------------------
*/

$cartCount = 0;

$cart = $_SESSION[CART_SESSION_KEY] ?? [];

if (is_array($cart)) {

    foreach ($cart as $item) {

        $cartCount += (int)($item['quantity'] ?? 0);

    }

}

?>

<nav class="navbar navbar-expand-lg bg-white shadow-sm">

    <div class="container">

        <!-- LOGO -->

        <a
            class="navbar-brand fw-bold text-primary"
            href="<?= BASE_URL ?>"
        >

            <i class="bi bi-shop me-2"></i>

            MiniShop

        </a>


        <!-- MOBILE -->

        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#mainNavbar"
            aria-controls="mainNavbar"
            aria-expanded="false"
        >

            <span class="navbar-toggler-icon"></span>

        </button>


        <div
            class="collapse navbar-collapse"
            id="mainNavbar"
        >

            <!-- MENU -->

            <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                <!-- TRANG CHỦ -->

                <li class="nav-item">

                    <a
                        class="nav-link"
                        href="<?= BASE_URL ?>"
                    >

                        <i class="bi bi-house me-1"></i>

                        Trang chủ

                    </a>

                </li>


                <!-- DANH MỤC -->

                <li class="nav-item dropdown">

                    <a
                        class="nav-link dropdown-toggle"
                        href="#"
                        role="button"
                        data-bs-toggle="dropdown"
                    >

                        <i class="bi bi-grid me-1"></i>

                        Danh mục

                    </a>


                    <ul class="dropdown-menu">

                        <?php if (!empty($categories)): ?>

                            <?php foreach ($categories as $category): ?>

                                <li>

                                    <a
                                        class="dropdown-item"
                                        href="<?= BASE_URL ?>category/<?= urlencode($category->slug ?? '') ?>"
                                    >

                                        <i class="bi bi-folder me-2"></i>

                                        <?= htmlspecialchars(
                                            $category->name
                                            ?? $category->catename
                                            ?? ''
                                        ) ?>

                                    </a>

                                </li>

                            <?php endforeach; ?>

                        <?php else: ?>

                            <li>

                                <span class="dropdown-item text-muted">

                                    Chưa có danh mục

                                </span>

                            </li>

                        <?php endif; ?>

                    </ul>

                </li>


                <!-- THƯƠNG HIỆU -->

                <li class="nav-item dropdown">

                    <a
                        class="nav-link dropdown-toggle"
                        href="#"
                        role="button"
                        data-bs-toggle="dropdown"
                    >

                        <i class="bi bi-tags me-1"></i>

                        Thương hiệu

                    </a>


                    <ul class="dropdown-menu">

                        <?php if (!empty($brands)): ?>

                            <?php foreach ($brands as $brand): ?>

                                <li>

                                    <a
                                        class="dropdown-item"
                                        href="<?= BASE_URL ?>brand/<?= urlencode($brand->slug ?? '') ?>"
                                    >

                                        <i class="bi bi-tag me-2"></i>

                                        <?= htmlspecialchars(
                                            $brand->name
                                            ?? $brand->brandname
                                            ?? ''
                                        ) ?>

                                    </a>

                                </li>

                            <?php endforeach; ?>

                        <?php else: ?>

                            <li>

                                <span class="dropdown-item text-muted">

                                    Chưa có thương hiệu

                                </span>

                            </li>

                        <?php endif; ?>

                    </ul>

                </li>

            </ul>


            <!-- SEARCH -->

            <form
                class="d-flex me-3"
                method="GET"
                action="<?= BASE_URL ?>"
            >

                <input
                    type="hidden"
                    name="area"
                    value="client"
                >

                <input
                    type="hidden"
                    name="controller"
                    value="product"
                >

                <input
                    type="hidden"
                    name="action"
                    value="index"
                >

                <input
                    class="form-control me-2"
                    type="search"
                    name="keyword"
                    placeholder="Tìm sản phẩm..."
                >

                <button
                    class="btn btn-outline-primary"
                    type="submit"
                >

                    <i class="bi bi-search"></i>

                </button>

            </form>


            <!-- ĐĂNG NHẬP -->

            <a
                href="<?= BASE_URL ?>?area=admin&controller=auth&action=login"
                class="btn btn-outline-primary me-2"
                title="Đăng nhập"
            >

                <i class="bi bi-person"></i>

            </a>


            <!-- GIỎ HÀNG -->

            <a
                href="<?= BASE_URL ?>cart"
                class="btn btn-warning position-relative"
                title="Giỏ hàng"
            >

                <i class="bi bi-cart3 fs-5"></i>


                <span
                    id="cartCount"
                    class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                >

                    <?= $cartCount ?>

                </span>

            </a>

        </div>

    </div>

</nav>


<script>

/*
|--------------------------------------------------------------------------
| CẬP NHẬT SỐ LƯỢNG GIỎ HÀNG
|--------------------------------------------------------------------------
*/

function updateHeaderCartCount(count)
{
    const cartCount =
        document.getElementById("cartCount");

    if (cartCount) {

        cartCount.textContent = count;

    }
}


/*
|--------------------------------------------------------------------------
| THÊM VÀO GIỎ HÀNG
|--------------------------------------------------------------------------
*/

document.addEventListener(
    "DOMContentLoaded",
    function ()
    {

        document
            .querySelectorAll(".btn-add-cart")
            .forEach(
                function (button)
                {

                    button.addEventListener(
                        "click",
                        function ()
                        {

                            const productId =
                                this.dataset.productid;

                            if (!productId) {

                                alert(
                                    "Không xác định được sản phẩm!"
                                );

                                return;
                            }


                            const formData =
                                new FormData();

                            formData.append(
                                "productid",
                                productId
                            );

                            formData.append(
                                "quantity",
                                1
                            );


                            fetch(
                                "<?= BASE_URL ?>cart/add",
                                {
                                    method: "POST",
                                    body: formData
                                }
                            )
                            .then(
                                response =>
                                {

                                    if (!response.ok) {

                                        throw new Error(
                                            "HTTP " +
                                            response.status
                                        );

                                    }

                                    return response.json();

                                }
                            )
                            .then(
                                data =>
                                {

                                    if (data.success) {

                                        updateHeaderCartCount(
                                            data.cartCount
                                        );

                                        alert(
                                            data.message
                                            ||
                                            "Đã thêm vào giỏ hàng!"
                                        );

                                    } else {

                                        alert(
                                            data.message
                                            ||
                                            "Không thể thêm vào giỏ hàng!"
                                        );

                                    }

                                }
                            )
                            .catch(
                                error =>
                                {

                                    console.error(
                                        error
                                    );

                                    alert(
                                        "Không thể kết nối đến server!"
                                    );

                                }
                            );

                        }
                    );

                }
            );

    }
);

</script>