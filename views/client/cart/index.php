<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$cart = $_SESSION[CART_SESSION_KEY] ?? [];

?>

<div class="container py-5">

    <!-- =====================================================
         TIÊU ĐỀ
    ====================================================== -->

    <h2 class="fw-bold mb-4">

        <i class="bi bi-cart3 me-2"></i>

        Giỏ hàng

    </h2>


    <?php if (empty($cart)): ?>

        <!-- =================================================
             GIỎ HÀNG TRỐNG
        ================================================== -->

        <div class="alert alert-warning text-center">

            <i
                class="bi bi-cart-x fs-1 d-block mb-3"
            ></i>

            <h5 class="fw-bold">

                Giỏ hàng đang trống

            </h5>

            <p class="mb-3">

                Bạn chưa có sản phẩm nào
                trong giỏ hàng.

            </p>

            <a
                href="<?= BASE_URL ?>product"
                class="btn btn-primary"
            >

                <i class="bi bi-shop me-1"></i>

                Tiếp tục mua hàng

            </a>

        </div>


    <?php else: ?>


        <!-- =================================================
             DANH SÁCH SẢN PHẨM
        ================================================== -->

        <div class="table-responsive">

            <table
                class="table table-bordered
                       table-hover
                       align-middle
                       bg-white"
            >

                <thead class="table-light">

                    <tr>

                        <th>
                            Sản phẩm
                        </th>

                        <th
                            class="text-center"
                            style="width:150px;"
                        >
                            Giá
                        </th>

                        <th
                            class="text-center"
                            style="width:180px;"
                        >
                            Số lượng
                        </th>

                        <th
                            class="text-center"
                            style="width:180px;"
                        >
                            Thành tiền
                        </th>

                        <th
                            class="text-center"
                            style="width:100px;"
                        >
                            Xóa
                        </th>

                    </tr>

                </thead>


                <tbody>

                    <?php

                    $total = 0;

                    foreach ($cart as $item):

                        $productId =
                            (int)(
                                $item['productid'] ?? 0
                            );

                        $productName =
                            $item['productname']
                            ?? 'Sản phẩm';

                        $price =
                            (float)(
                                $item['price'] ?? 0
                            );

                        $quantity =
                            max(
                                1,
                                (int)(
                                    $item['quantity'] ?? 1
                                )
                            );

                        $image =
                            $item['image'] ?? '';

                        $subtotal =
                            $price * $quantity;

                        $total += $subtotal;

                    ?>

                        <tr>


                            <!-- =================================
                                 SẢN PHẨM
                            ================================== -->

                            <td>

                                <div
                                    class="d-flex
                                           align-items-center"
                                >

                                    <?php if (!empty($image)): ?>

                                        <img
                                            src="<?= PRODUCT_IMAGE_URL . htmlspecialchars($image) ?>"
                                            alt="<?= htmlspecialchars($productName) ?>"
                                            style="
                                                width:80px;
                                                height:80px;
                                                object-fit:contain;
                                            "
                                            class="me-3"
                                        >

                                    <?php else: ?>

                                        <div
                                            class="bg-light
                                                   d-flex
                                                   align-items-center
                                                   justify-content-center
                                                   me-3"
                                            style="
                                                width:80px;
                                                height:80px;
                                            "
                                        >

                                            <i
                                                class="bi bi-image
                                                       text-muted
                                                       fs-3"
                                            ></i>

                                        </div>

                                    <?php endif; ?>


                                    <div>

                                        <h6 class="mb-1">

                                            <?= htmlspecialchars(
                                                $productName
                                            ) ?>

                                        </h6>

                                    </div>

                                </div>

                            </td>


                            <!-- =================================
                                 GIÁ
                            ================================== -->

                            <td class="text-center">

                                <?= number_format(
                                    $price,
                                    0,
                                    ",",
                                    "."
                                ) ?>

                                đ

                            </td>


                            <!-- =================================
                                 SỐ LƯỢNG
                            ================================== -->

                            <td class="text-center">

                                <div
                                    class="input-group"
                                    style="max-width:160px; margin:auto;"
                                >


                                    <!-- NÚT GIẢM -->

                                    <button
                                        type="button"
                                        class="btn btn-outline-secondary"
                                        onclick="decreaseCart(
                                            <?= $productId ?>,
                                            <?= $quantity ?>
                                        )"
                                    >

                                        −

                                    </button>


                                    <!-- SỐ LƯỢNG -->

                                    <input
                                        type="text"
                                        class="form-control text-center"
                                        value="<?= $quantity ?>"
                                        readonly
                                    >


                                    <!-- NÚT TĂNG -->

                                    <button
                                        type="button"
                                        class="btn btn-outline-secondary"
                                        onclick="increaseCart(
                                            <?= $productId ?>,
                                            <?= $quantity ?>
                                        )"
                                    >

                                        +

                                    </button>

                                </div>

                            </td>


                            <!-- =================================
                                 THÀNH TIỀN
                            ================================== -->

                            <td
                                class="text-center
                                       text-danger
                                       fw-bold"
                            >

                                <?= number_format(
                                    $subtotal,
                                    0,
                                    ",",
                                    "."
                                ) ?>

                                đ

                            </td>


                            <!-- =================================
                                 XÓA
                            ================================== -->

                            <td class="text-center">

                                <button
                                    type="button"
                                    class="btn btn-danger btn-sm"
                                    onclick="removeCart(
                                        <?= $productId ?>
                                    )"
                                    title="Xóa sản phẩm"
                                >

                                    <i class="bi bi-trash"></i>

                                </button>

                            </td>

                        </tr>


                    <?php endforeach; ?>

                </tbody>

            </table>

        </div>


        <!-- =================================================
             TỔNG TIỀN
        ================================================== -->

        <div class="row justify-content-end">

            <div class="col-md-5">

                <div class="card shadow-sm">

                    <div class="card-body">

                        <h5 class="fw-bold mb-3">

                            Tổng thanh toán

                        </h5>


                        <div
                            class="d-flex
                                   justify-content-between
                                   align-items-center"
                        >

                            <span>

                                Tổng tiền:

                            </span>


                            <span
                                class="text-danger
                                       fw-bold
                                       fs-4"
                            >

                                <?= number_format(
                                    $total,
                                    0,
                                    ",",
                                    "."
                                ) ?>

                                đ

                            </span>

                        </div>


                        <hr>


                        <div class="d-flex gap-2">

                            <a
                                href="<?= BASE_URL ?>product"
                                class="btn btn-outline-secondary"
                            >

                                <i
                                    class="bi bi-arrow-left"
                                ></i>

                                Tiếp tục mua hàng

                            </a>


                            <a
                                href="<?= BASE_URL ?>cart/checkout"
                                class="btn btn-primary"
                            >

                                <i
                                    class="bi bi-credit-card"
                                ></i>

                                Thanh toán

                            </a>

                        </div>

                    </div>

                </div>

            </div>

        </div>


    <?php endif; ?>

</div>


<script>

/*
|--------------------------------------------------------------------------
| TĂNG SỐ LƯỢNG
|--------------------------------------------------------------------------
*/

function increaseCart(productid, quantity)
{

    const newQuantity =
        parseInt(quantity) + 1;


    updateCart(
        productid,
        newQuantity
    );

}


/*
|--------------------------------------------------------------------------
| GIẢM SỐ LƯỢNG
|--------------------------------------------------------------------------
|
| Nếu quantity = 1
| => hỏi người dùng
| => xóa sản phẩm
|
*/

function decreaseCart(productid, quantity)
{

    quantity =
        parseInt(quantity);


    if (quantity <= 1) {

        removeCart(productid);

        return;

    }


    const newQuantity =
        quantity - 1;


    updateCart(
        productid,
        newQuantity
    );

}


/*
|--------------------------------------------------------------------------
| CẬP NHẬT SỐ LƯỢNG
|--------------------------------------------------------------------------
*/

function updateCart(productid, quantity)
{

    if (!productid || productid <= 0) {

        alert(
            "Sản phẩm không hợp lệ!"
        );

        return;

    }


    const formData =
        new FormData();


    formData.append(
        "productid",
        productid
    );


    formData.append(
        "quantity",
        quantity
    );


    fetch(
        "<?= BASE_URL ?>cart/update",
        {
            method: "POST",

            body: formData,

            headers: {
                "X-Requested-With":
                    "XMLHttpRequest"
            }
        }
    )

    .then(response => {

        if (!response.ok) {

            throw new Error(
                "HTTP error " +
                response.status
            );

        }

        return response.json();

    })

    .then(data => {

        console.log(
            "Update cart:",
            data
        );


        if (data.success) {

            // Reload lại giỏ hàng

            location.reload();

        } else {

            alert(
                data.message
                ||
                "Không thể cập nhật giỏ hàng!"
            );

        }

    })

    .catch(error => {

        console.error(
            "Lỗi update cart:",
            error
        );


        alert(
            "Có lỗi xảy ra khi cập nhật giỏ hàng!"
        );

    });

}


/*
|--------------------------------------------------------------------------
| XÓA SẢN PHẨM
|--------------------------------------------------------------------------
*/

function removeCart(productid)
{

    if (!productid || productid <= 0) {

        alert(
            "Sản phẩm không hợp lệ!"
        );

        return;

    }


    if (
        !confirm(
            "Bạn có chắc muốn xóa sản phẩm này khỏi giỏ hàng?"
        )
    ) {

        return;

    }


    const formData =
        new FormData();


    formData.append(
        "productid",
        productid
    );


    fetch(
        "<?= BASE_URL ?>cart/remove",
        {
            method: "POST",

            body: formData,

            headers: {
                "X-Requested-With":
                    "XMLHttpRequest"
            }
        }
    )

    .then(response => {

        if (!response.ok) {

            throw new Error(
                "HTTP error " +
                response.status
            );

        }

        return response.json();

    })

    .then(data => {

        console.log(
            "Remove cart:",
            data
        );


        if (data.success) {

            // Xóa thành công

            location.reload();

        } else {

            alert(
                data.message
                ||
                "Không thể xóa sản phẩm!"
            );

        }

    })

    .catch(error => {

        console.error(
            "Lỗi remove cart:",
            error
        );


        alert(
            "Có lỗi xảy ra khi xóa sản phẩm!"
        );

    });

}

</script>