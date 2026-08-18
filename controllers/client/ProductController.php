<?php

namespace Controllers\Client;

use DAO\ProductDAO;

class ProductController
{
    private ProductDAO $productDAO;

    public function __construct()
    {
        $this->productDAO = new ProductDAO();
    }


    // =====================================================
    // DANH SÁCH SẢN PHẨM
    // /product
    // =====================================================
    public function index()
    {
        $keyword = trim($_GET['keyword'] ?? '');

        if ($keyword !== '') {

            $products = $this->productDAO->search($keyword);

            $title = "Tìm kiếm: " . $keyword;

        } else {

            $products = $this->productDAO->getAll();

            $title = "Tất cả sản phẩm";
        }


        ob_start();

        require __DIR__ . "/../../views/client/products/index.php";

        $content = ob_get_clean();

        require __DIR__ . "/../../views/client/layouts/master.php";
    }


    // =====================================================
    // CHI TIẾT SẢN PHẨM
    // /product/{slug}
    // =====================================================
    public function detail()
    {
        $slug = trim($_GET['slug'] ?? '');

        // Không có slug
        if ($slug === '') {

            http_response_code(404);

            $title = "Không tìm thấy sản phẩm";

            $message = "Slug sản phẩm không hợp lệ.";

            ob_start();

            require __DIR__ . "/../../views/client/errors/404.php";

            $content = ob_get_clean();

            require __DIR__ . "/../../views/client/layouts/master.php";

            return;
        }


        // Lấy sản phẩm theo slug
        $product = $this->productDAO->getBySlug($slug);


        // Không tìm thấy sản phẩm
        if ($product === null) {

            http_response_code(404);

            $title = "Không tìm thấy sản phẩm";

            $message = "Sản phẩm không tồn tại hoặc đã bị xóa.";

            ob_start();

            require __DIR__ . "/../../views/client/errors/404.php";

            $content = ob_get_clean();

            require __DIR__ . "/../../views/client/layouts/master.php";

            return;
        }


        // Tiêu đề trang
        $title = $product->proname;


        // Render view
        ob_start();

        require __DIR__ . "/../../views/client/products/detail.php";

        $content = ob_get_clean();


        // Layout chung
        require __DIR__ . "/../../views/client/layouts/master.php";
    }


    // =====================================================
    // SẢN PHẨM THEO DANH MỤC
    // /category/{slug}
    // =====================================================
    public function category()
    {
        $slug = trim($_GET['slug'] ?? '');

        $products = [];

        if ($slug !== '') {

            $products = $this->productDAO->getByCategory($slug);
        }

        $title = "Sản phẩm theo danh mục";


        ob_start();

        require __DIR__ . "/../../views/client/products/index.php";

        $content = ob_get_clean();


        require __DIR__ . "/../../views/client/layouts/master.php";
    }


    // =====================================================
    // SẢN PHẨM THEO THƯƠNG HIỆU
    // /brand/{slug}
    // =====================================================
    public function brand()
    {
        $slug = trim($_GET['slug'] ?? '');

        $products = [];

        if ($slug !== '') {

            $products = $this->productDAO->getByBrand($slug);
        }

        $title = "Sản phẩm theo thương hiệu";


        ob_start();

        require __DIR__ . "/../../views/client/products/index.php";

        $content = ob_get_clean();


        require __DIR__ . "/../../views/client/layouts/master.php";
    }
}