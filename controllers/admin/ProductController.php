<?php

namespace Controllers\Admin;

use DAO\ProductDAO;

class ProductController
{
    public function index()
    {
        $title = "Quản lý sản phẩm";

        $keyword = trim($_GET["keyword"] ?? "");

        $limit = (int)($_GET["limit"] ?? 10);
        $page = (int)($_GET["page"] ?? 1);

        if ($limit <= 0) {
            $limit = 10;
        }

        if ($page <= 0) {
            $page = 1;
        }

        $offset = ($page - 1) * $limit;

        $productDAO = new ProductDAO();

        $totalRecords = $productDAO->countByKeyword($keyword);

        $totalPages = (int)ceil($totalRecords / $limit);

        $products = $productDAO->getPage(
            $limit,
            $offset,
            $keyword
        );

        require __DIR__ . "/../../views/admin/product/index.php";
    }
}