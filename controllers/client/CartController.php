<?php

namespace Controllers\Client;

use DAO\ProductDAO;

class CartController
{
    private ProductDAO $productDAO;

    public function __construct()
    {
        // =====================================================
        // SESSION
        // =====================================================

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // =====================================================
        // PRODUCT DAO
        // =====================================================

        $this->productDAO = new ProductDAO();

        // =====================================================
        // KHỞI TẠO CART
        // =====================================================

        if (
            !isset($_SESSION[CART_SESSION_KEY]) ||
            !is_array($_SESSION[CART_SESSION_KEY])
        ) {
            $_SESSION[CART_SESSION_KEY] = [];
        }
    }


    // =========================================================
    // HIỂN THỊ GIỎ HÀNG
    // GET /cart
    // =========================================================

    public function index()
    {
        $cart = $_SESSION[CART_SESSION_KEY] ?? [];

        $total = 0;

        foreach ($cart as $item) {

            $price = (float)($item['price'] ?? 0);

            $quantity = (int)($item['quantity'] ?? 0);

            $total += $price * $quantity;
        }

        $title = "Giỏ hàng";

        ob_start();

        require __DIR__ . "/../../views/client/cart/index.php";

        $content = ob_get_clean();

        require __DIR__ . "/../../views/client/layouts/master.php";
    }


    // =========================================================
    // THÊM SẢN PHẨM
    // POST /cart/add
    // =========================================================

    public function add()
    {
        $this->jsonHeader();

        $productId = (int)($_POST['productid'] ?? 0);

        $quantity = (int)($_POST['quantity'] ?? 1);

        if ($quantity < 1) {
            $quantity = 1;
        }

        if ($productId <= 0) {
            $this->json([
                'success' => false,
                'message' => 'Sản phẩm không hợp lệ!'
            ]);
        }

        $product = $this->productDAO->findById($productId);

        if (!$product) {
            $this->json([
                'success' => false,
                'message' => 'Không tìm thấy sản phẩm!'
            ]);
        }

        // =====================================================
        // KIỂM TRA TRẠNG THÁI
        // =====================================================

        if (
            isset($product->status) &&
            (int)$product->status !== 1
        ) {
            $this->json([
                'success' => false,
                'message' => 'Sản phẩm hiện không bán!'
            ]);
        }

        // =====================================================
        // TỒN KHO
        // =====================================================

        $stock = (int)($product->quantity ?? 0);

        if ($stock <= 0) {
            $this->json([
                'success' => false,
                'message' => 'Sản phẩm đã hết hàng!'
            ]);
        }

        // =====================================================
        // GIÁ
        // =====================================================

        $price = (float)($product->price ?? 0);

        $discountPrice = (float)(
            $product->discountPrice ?? 0
        );

        $finalPrice = $price;

        if (
            $discountPrice > 0 &&
            $discountPrice < $price
        ) {
            $finalPrice = $discountPrice;
        }

        // =====================================================
        // THÔNG TIN SẢN PHẨM
        // =====================================================

        $productName =
            $product->proname ?? 'Sản phẩm';

        $image =
            $product->image ?? '';

        // =====================================================
        // ĐÃ CÓ TRONG CART
        // =====================================================

        if (isset($_SESSION[CART_SESSION_KEY][$productId])) {

            $oldQuantity = (int)(
                $_SESSION[CART_SESSION_KEY][$productId]['quantity']
                ?? 0
            );

            $newQuantity = $oldQuantity + $quantity;

            if ($newQuantity > $stock) {
                $this->json([
                    'success' => false,
                    'message' =>
                        "Chỉ còn {$stock} sản phẩm trong kho!"
                ]);
            }

            $_SESSION[CART_SESSION_KEY][$productId]['quantity']
                = $newQuantity;

        } else {

            if ($quantity > $stock) {
                $this->json([
                    'success' => false,
                    'message' =>
                        "Chỉ còn {$stock} sản phẩm trong kho!"
                ]);
            }

            $_SESSION[CART_SESSION_KEY][$productId] = [

                'productid' =>
                    $productId,

                'productname' =>
                    $productName,

                'image' =>
                    $image,

                'price' =>
                    $finalPrice,

                'quantity' =>
                    $quantity
            ];
        }

        $this->json([

            'success' =>
                true,

            'message' =>
                'Đã thêm sản phẩm vào giỏ hàng!',

            'cartCount' =>
                $this->getCartCount(),

            'cartTotal' =>
                $this->getCartTotal()

        ]);
    }


    // =========================================================
    // CẬP NHẬT SỐ LƯỢNG
    // POST /cart/update
    // =========================================================

    public function update()
    {
        $this->jsonHeader();

        $productId =
            (int)($_POST['productid'] ?? 0);

        $quantity =
            (int)($_POST['quantity'] ?? 1);

        // =====================================================
        // KIỂM TRA ID
        // =====================================================

        if ($productId <= 0) {

            $this->json([
                'success' => false,
                'message' => 'Product ID không hợp lệ!'
            ]);
        }

        // =====================================================
        // KIỂM TRA SẢN PHẨM TRONG CART
        // =====================================================

        if (
            !isset(
                $_SESSION[CART_SESSION_KEY][$productId]
            )
        ) {

            $this->json([
                'success' => false,
                'message' =>
                    'Sản phẩm không tồn tại trong giỏ hàng!'
            ]);
        }

        // =====================================================
        // NẾU QUANTITY <= 0
        // =====================================================

        if ($quantity <= 0) {

            unset(
                $_SESSION[CART_SESSION_KEY][$productId]
            );

            $this->json([

                'success' =>
                    true,

                'message' =>
                    'Đã xóa sản phẩm khỏi giỏ hàng!',

                'cartCount' =>
                    $this->getCartCount(),

                'cartTotal' =>
                    $this->getCartTotal(),

                'removed' =>
                    true
            ]);
        }

        // =====================================================
        // LẤY SẢN PHẨM DATABASE
        // =====================================================

        $product =
            $this->productDAO->findById($productId);

        if (!$product) {

            unset(
                $_SESSION[CART_SESSION_KEY][$productId]
            );

            $this->json([

                'success' =>
                    false,

                'message' =>
                    'Sản phẩm không còn tồn tại!',

                'removed' =>
                    true
            ]);
        }

        // =====================================================
        // KIỂM TRA TỒN KHO
        // =====================================================

        $stock =
            (int)($product->quantity ?? 0);

        if ($stock <= 0) {

            unset(
                $_SESSION[CART_SESSION_KEY][$productId]
            );

            $this->json([

                'success' =>
                    false,

                'message' =>
                    'Sản phẩm đã hết hàng!',

                'removed' =>
                    true
            ]);
        }

        if ($quantity > $stock) {

            $this->json([

                'success' =>
                    false,

                'message' =>
                    "Chỉ còn {$stock} sản phẩm trong kho!"
            ]);
        }

        // =====================================================
        // CẬP NHẬT
        // =====================================================

        $_SESSION[CART_SESSION_KEY][$productId]['quantity']
            = $quantity;

        // =====================================================
        // TÍNH THÀNH TIỀN
        // =====================================================

        $item =
            $_SESSION[CART_SESSION_KEY][$productId];

        $itemTotal =
            (float)($item['price'] ?? 0)
            *
            $quantity;

        // =====================================================
        // RESPONSE
        // =====================================================

        $this->json([

            'success' =>
                true,

            'message' =>
                'Cập nhật số lượng thành công!',

            'productId' =>
                $productId,

            'quantity' =>
                $quantity,

            'itemTotal' =>
                $itemTotal,

            'cartCount' =>
                $this->getCartCount(),

            'cartTotal' =>
                $this->getCartTotal()

        ]);
    }


    // =========================================================
    // XÓA SẢN PHẨM
    // POST /cart/remove
    // =========================================================

    public function remove()
    {
        $this->jsonHeader();

        $productId =
            (int)($_POST['productid'] ?? 0);

        // =====================================================
        // KIỂM TRA ID
        // =====================================================

        if ($productId <= 0) {

            $this->json([

                'success' =>
                    false,

                'message' =>
                    'Product ID không hợp lệ!'
            ]);
        }

        // =====================================================
        // KIỂM TRA CART
        // =====================================================

        if (
            !isset(
                $_SESSION[CART_SESSION_KEY][$productId]
            )
        ) {

            $this->json([

                'success' =>
                    false,

                'message' =>
                    'Sản phẩm không tồn tại trong giỏ hàng!'
            ]);
        }

        // =====================================================
        // XÓA
        // =====================================================

        unset(
            $_SESSION[CART_SESSION_KEY][$productId]
        );

        // =====================================================
        // RESPONSE
        // =====================================================

        $this->json([

            'success' =>
                true,

            'message' =>
                'Đã xóa sản phẩm khỏi giỏ hàng!',

            'cartCount' =>
                $this->getCartCount(),

            'cartTotal' =>
                $this->getCartTotal()
        ]);
    }


    // =========================================================
    // COUNT
    // GET /cart/count
    // =========================================================

    public function count()
    {
        $this->jsonHeader();

        $this->json([

            'success' =>
                true,

            'cartCount' =>
                $this->getCartCount()
        ]);
    }


    // =========================================================
    // CHECKOUT
    // =========================================================

    public function checkout()
    {
        $cart =
            $_SESSION[CART_SESSION_KEY] ?? [];

        if (empty($cart)) {

            header(
                "Location: "
                . BASE_URL
                . "cart"
            );

            exit;
        }

        $title = "Thanh toán";

        ob_start();

        require __DIR__
            . "/../../views/client/cart/checkout.php";

        $content =
            ob_get_clean();

        require __DIR__
            . "/../../views/client/layouts/master.php";
    }


    // =========================================================
    // HELPER JSON HEADER
    // =========================================================

    private function jsonHeader(): void
    {
        header(
            "Content-Type: application/json; charset=UTF-8"
        );
    }


    // =========================================================
    // HELPER JSON RESPONSE
    // =========================================================

    private function json(array $data): void
    {
        echo json_encode(
            $data,
            JSON_UNESCAPED_UNICODE
        );

        exit;
    }


    // =========================================================
    // CART COUNT
    // =========================================================

    private function getCartCount(): int
    {
        $count = 0;

        $cart =
            $_SESSION[CART_SESSION_KEY] ?? [];

        foreach ($cart as $item) {

            $count +=
                (int)(
                    $item['quantity'] ?? 0
                );
        }

        return $count;
    }


    // =========================================================
    // CART TOTAL
    // =========================================================

    private function getCartTotal(): float
    {
        $total = 0;

        $cart =
            $_SESSION[CART_SESSION_KEY] ?? [];

        foreach ($cart as $item) {

            $price =
                (float)(
                    $item['price'] ?? 0
                );

            $quantity =
                (int)(
                    $item['quantity'] ?? 0
                );

            $total +=
                $price * $quantity;
        }

        return $total;
    }
}