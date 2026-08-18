<?php

namespace Controllers\Client;

use DAO\ProductDAO;
use DAO\CategoryDAO;

class HomeController
{
    private ProductDAO $productDAO;

    private CategoryDAO $categoryDAO;


    public function __construct()
    {
        $this->productDAO = new ProductDAO();

        $this->categoryDAO = new CategoryDAO();
    }


    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | TIÊU ĐỀ
        |--------------------------------------------------------------------------
        */

        $title = "Trang chủ";


        /*
        |--------------------------------------------------------------------------
        | DANH MỤC
        |--------------------------------------------------------------------------
        */

        $categories =
            $this->categoryDAO->getAll();


        /*
        |--------------------------------------------------------------------------
        | SẢN PHẨM GIẢM GIÁ
        |--------------------------------------------------------------------------
        */

        $discountProducts =
            $this->productDAO->getDiscountProducts();


        /*
        |--------------------------------------------------------------------------
        | SẢN PHẨM MỚI
        |--------------------------------------------------------------------------
        */

        $newProducts =
            $this->productDAO->getNewProducts(4);


        /*
        |--------------------------------------------------------------------------
        | LOAD VIEW HOME
        |--------------------------------------------------------------------------
        */

        ob_start();

        require __DIR__ .
            "/../../views/client/home/index.php";

        $content = ob_get_clean();


        /*
        |--------------------------------------------------------------------------
        | LOAD MASTER
        |--------------------------------------------------------------------------
        */

        require __DIR__ .
            "/../../views/client/layouts/master.php";
    }
}