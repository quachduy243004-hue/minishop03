<?php

require_once __DIR__ . "/../../dao/ProductDAO.php";

class ProductController
{
    public function index()
    {
        // =========================
        // TIÊU ĐỀ
        // =========================

        $title = "Quản lý sản phẩm";

        // =========================
        // NHẬN REQUEST
        // =========================

        $keyword = trim($_GET["keyword"] ?? "");

        $limit = (int) ($_GET["limit"] ?? 10);

        $page = (int) ($_GET["page"] ?? 1);

        // Không cho limit nhỏ hơn 1
        if ($limit <= 0) {
            $limit = 10;
        }

        // Không cho page nhỏ hơn 1
        if ($page <= 0) {
            $page = 1;
        }

        // =========================
        // TÍNH OFFSET
        // =========================

        $offset = ($page - 1) * $limit;

        // =========================
        // GỌI DAO
        // =========================

        $productDAO = new ProductDAO();

        // Tổng số sản phẩm
        $totalRecords = $productDAO->count(
            "products",
            "productname",
            $keyword
        );

        // Tổng số trang
        $totalPages = ceil($totalRecords / $limit);

        // Danh sách sản phẩm
        $products = $productDAO->getPage(
            $limit,
            $offset,
            $keyword
        );

        // =========================
        // GỌI VIEW
        // =========================

        require __DIR__ . "/../../views/admin/products/index.php";
    }
}