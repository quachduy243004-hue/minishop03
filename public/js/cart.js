// =====================================================
// THÊM GIỎ HÀNG
// =====================================================

document
    .querySelectorAll(".btn-add-cart")
    .forEach(button => {

        button.addEventListener("click", function () {

            const productid =
                this.dataset.productid;


            // Tạo FormData

            const formData =
                new FormData();

            formData.append(
                "productid",
                productid
            );


            // AJAX

            fetch(
                BASE_URL + "cart/add",
                {
                    method: "POST",
                    body: formData
                }
            )

            .then(response => {

                if (!response.ok) {
                    throw new Error(
                        "HTTP Error: "
                        + response.status
                    );
                }

                return response.json();
            })

            .then(data => {

                console.log(data);


                if (data.success) {

                    // Thông báo

                    alert(data.message);


                    // Cập nhật số lượng Header

                    const cartCount =
                        document.querySelector(
                            "#cartCount"
                        );


                    if (cartCount) {

                        cartCount.textContent =
                            data.cartCount;
                    }

                } else {

                    alert(
                        data.message
                        || "Có lỗi xảy ra."
                    );
                }

            })

            .catch(error => {

                console.error(
                    "Lỗi:",
                    error
                );

                alert(
                    "Không thể thêm sản phẩm vào giỏ hàng."
                );
            });

        });

    });


// =====================================================
// CẬP NHẬT GIỎ HÀNG
// =====================================================

function updateCart(
    productid,
    quantity
) {

    if (quantity < 1) {
        quantity = 1;
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
        BASE_URL + "cart/update",
        {
            method: "POST",
            body: formData
        }
    )

    .then(response => response.json())

    .then(data => {

        console.log(data);


        if (!data.success) {

            alert(data.message);

            return;
        }


        // Số lượng

        const quantityElement =
            document.querySelector(
                "#quantity-" + productid
            );


        if (quantityElement) {

            quantityElement.textContent =
                data.quantity;
        }


        // Thành tiền

        const itemTotal =
            document.querySelector(
                "#item-total-" + productid
            );


        if (itemTotal) {

            itemTotal.textContent =
                formatMoney(data.itemTotal);
        }


        // Tổng tiền

        const cartTotal =
            document.querySelector(
                "#cartTotal"
            );


        if (cartTotal) {

            cartTotal.textContent =
                formatMoney(data.cartTotal);
        }


        // Header

        const cartCount =
            document.querySelector(
                "#cartCount"
            );


        if (cartCount) {

            cartCount.textContent =
                data.cartCount;
        }

    })

    .catch(error => {

        console.error(
            "Lỗi:",
            error
        );

    });
}


// =====================================================
// XÓA GIỎ HÀNG
// =====================================================

function removeCart(productid) {

    const formData =
        new FormData();

    formData.append(
        "productid",
        productid
    );


    fetch(
        BASE_URL + "cart/remove",
        {
            method: "POST",
            body: formData
        }
    )

    .then(response => response.json())

    .then(data => {

        console.log(data);


        if (!data.success) {

            alert(data.message);

            return;
        }


        // Xóa dòng

        const row =
            document.querySelector(
                "#cart-row-" + productid
            );


        if (row) {

            row.remove();
        }


        // Cập nhật tổng

        const cartTotal =
            document.querySelector(
                "#cartTotal"
            );


        if (cartTotal) {

            cartTotal.textContent =
                formatMoney(data.cartTotal);
        }


        // Header

        const cartCount =
            document.querySelector(
                "#cartCount"
            );


        if (cartCount) {

            cartCount.textContent =
                data.cartCount;
        }


        // Nếu hết sản phẩm

        if (data.cartCount === 0) {

            location.reload();
        }


        alert(data.message);

    })

    .catch(error => {

        console.error(
            "Lỗi:",
            error
        );

    });
}


// =====================================================
// FORMAT TIỀN
// =====================================================

function formatMoney(number) {

    return Number(number)
        .toLocaleString(
            "vi-VN"
        ) + " đ";
}