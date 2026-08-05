-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 05, 2026 at 07:30 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `quachvanduy_database`
--

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `id` int(11) NOT NULL,
  `brandname` varchar(100) NOT NULL,
  `slug` varchar(150) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` tinyint(4) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`id`, `brandname`, `slug`, `image`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Louis Vuitton', 'louis-vuitton', 'louisvuitton.png', 'Thương hiệu Louis Vuitton', 1, '2026-08-05 14:25:23', '2026-08-05 14:25:23'),
(2, 'Gucci', 'gucci', 'Gucci.png', 'Thương hiệu Gucci', 1, '2026-08-05 14:25:23', '2026-08-05 14:25:23'),
(3, 'Canifa', 'canifa', 'canifa.png', 'Thương hiệu Canifa', 1, '2026-08-05 14:25:23', '2026-08-05 14:25:23'),
(4, 'Coolmate', 'coolmate', 'coolmate.png', 'Thương hiệu Coolmate', 1, '2026-08-05 14:25:23', '2026-08-05 14:25:23'),
(5, 'IVY moda', 'ivy-moda', 'ivymoda.png', 'Thương hiệu IVY moda', 1, '2026-08-05 14:25:23', '2026-08-05 14:25:23');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `catename` varchar(100) NOT NULL,
  `slug` varchar(150) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` tinyint(4) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `catename`, `slug`, `image`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Áo thun', 'ao-thun', 'aothun.jpg', 'Các loại áo thun nam nữ', 1, '2026-08-05 14:15:25', '2026-08-05 14:15:25'),
(2, 'Áo sơ mi', 'ao-so-mi', 'aosomi.jpg', 'Các loại áo sơ mi', 1, '2026-08-05 14:15:25', '2026-08-05 14:15:25'),
(3, 'Quần jean', 'quan-jean', 'quanjean.jpg', 'Các loại quần jean', 1, '2026-08-05 14:15:25', '2026-08-05 14:15:25'),
(4, 'Áo khoác', 'ao-khoac', 'aokhoac.jpg', 'Các loại áo khoác', 1, '2026-08-05 14:15:25', '2026-08-05 14:15:25'),
(5, 'Váy đầm', 'vay-dam', 'vaydam.jpg', 'Các loại váy đầm nữ', 1, '2026-08-05 14:15:25', '2026-08-05 14:15:25');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `status` tinyint(4) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `fullname`, `phone`, `email`, `address`, `note`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Quách Văn Duy', '0988888888', 'duy@gmail.com', 'TP.HCM', '', 1, '2026-08-05 14:27:07', '2026-08-05 14:27:07'),
(2, 'Nguyễn Thị Lan', '0977777777', 'lan@gmail.com', 'Đà Nẵng', '', 1, '2026-08-05 14:27:07', '2026-08-05 14:27:07'),
(3, 'Trần Minh Anh', '0966666666', 'anh@gmail.com', 'Hà Nội', '', 1, '2026-08-05 14:27:07', '2026-08-05 14:27:07'),
(4, 'Lê Quốc Bảo', '0955555555', 'bao@gmail.com', 'Cần Thơ', '', 1, '2026-08-05 14:27:07', '2026-08-05 14:27:07'),
(5, 'Phạm Hoàng Nam', '0944444444', 'nam@gmail.com', 'Huế', '', 1, '2026-08-05 14:27:07', '2026-08-05 14:27:07');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `order_code` varchar(30) NOT NULL,
  `total_amount` decimal(12,2) DEFAULT 0.00,
  `note` text DEFAULT NULL,
  `status` tinyint(4) DEFAULT 0 COMMENT '0: Chờ xử lý, 1: Hoàn thành, 2: Hủy',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `customer_id`, `user_id`, `order_code`, `total_amount`, `note`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'DH001', 199000.00, '', 1, '2026-08-05 14:28:16', '2026-08-05 14:28:16'),
(2, 2, 2, 'DH002', 399000.00, '', 0, '2026-08-05 14:28:16', '2026-08-05 14:28:16'),
(3, 3, 3, 'DH003', 599000.00, '', 1, '2026-08-05 14:28:16', '2026-08-05 14:28:16'),
(4, 4, 4, 'DH004', 790000.00, '', 0, '2026-08-05 14:28:16', '2026-08-05 14:28:16'),
(5, 5, 5, 'DH005', 899000.00, '', 1, '2026-08-05 14:28:16', '2026-08-05 14:28:16');

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `subtotal` decimal(12,2) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`id`, `order_id`, `product_id`, `quantity`, `price`, `subtotal`, `created_at`) VALUES
(1, 1, 1, 1, 199000.00, 199000.00, '2026-08-05 14:28:46'),
(2, 2, 2, 1, 399000.00, 399000.00, '2026-08-05 14:28:46'),
(3, 3, 3, 1, 599000.00, 599000.00, '2026-08-05 14:28:46'),
(4, 4, 4, 1, 790000.00, 790000.00, '2026-08-05 14:28:46'),
(5, 5, 5, 1, 899000.00, 899000.00, '2026-08-05 14:28:46');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `brand_id` int(11) DEFAULT NULL,
  `proname` varchar(200) NOT NULL,
  `slug` varchar(150) DEFAULT NULL,
  `price` decimal(10,0) NOT NULL,
  `discount_price` decimal(10,0) NOT NULL,
  `quantity` int(11) DEFAULT 0,
  `image` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` tinyint(4) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `brand_id`, `proname`, `slug`, `price`, `discount_price`, `quantity`, `image`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'Áo thun nam Basic', 'ao-thun-nam-basic', 250000, 199000, 50, 'aothun1.jpg', 'Áo thun cotton 100%', 1, '2026-08-05 14:27:30', '2026-08-05 14:27:30'),
(2, 2, 2, 'Áo sơ mi trắng', 'ao-so-mi-trang', 450000, 399000, 40, 'aosomi1.jpg', 'Áo sơ mi công sở', 1, '2026-08-05 14:27:30', '2026-08-05 14:27:30'),
(3, 3, 3, 'Quần jean xanh', 'quan-jean-xanh', 650000, 599000, 35, 'jean1.jpg', 'Quần jean co giãn', 1, '2026-08-05 14:27:30', '2026-08-05 14:27:30'),
(4, 4, 4, 'Áo khoác bomber', 'ao-khoac-bomber', 850000, 790000, 20, 'aokhoac1.jpg', 'Áo khoác bomber thời trang', 1, '2026-08-05 14:27:30', '2026-08-05 14:27:30'),
(5, 5, 5, 'Váy đầm dự tiệc', 'vay-dam-du-tiec', 950000, 899000, 15, 'vay1.jpg', 'Váy đầm nữ cao cấp', 1, '2026-08-05 14:27:30', '2026-08-05 14:27:30');

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `sort_order` int(11) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_images`
--

INSERT INTO `product_images` (`id`, `product_id`, `image`, `sort_order`, `created_at`) VALUES
(1, 1, 'aothun1-1.jpg', 1, '2026-08-05 14:27:56'),
(2, 2, 'aosomi1-1.jpg', 1, '2026-08-05 14:27:56'),
(3, 3, 'jean1-1.jpg', 1, '2026-08-05 14:27:56'),
(4, 4, 'aokhoac1-1.jpg', 1, '2026-08-05 14:27:56'),
(5, 5, 'vay1-1.jpg', 1, '2026-08-05 14:27:56');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `role` tinyint(4) DEFAULT 0 COMMENT '0: Nhân viên, 1: Quản trị',
  `status` tinyint(4) DEFAULT 1 COMMENT '0: Khóa, 1: Hoạt động',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullname`, `username`, `password`, `email`, `phone`, `address`, `role`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Quản trị viên', 'admin', '123456', 'admin@shop.com', '0901111111', 'TP.HCM', 1, 1, '2026-08-05 14:26:33', '2026-08-05 14:26:33'),
(2, 'Nguyễn Văn Anh', 'nhanvien1', '123456', 'nv1@shop.com', '0902222222', 'Đồng Nai', 0, 1, '2026-08-05 14:26:33', '2026-08-05 14:26:33'),
(3, 'Trần Thị Bảo', 'nhanvien2', '123456', 'nv2@shop.com', '0903333333', 'Bình Dương', 0, 1, '2026-08-05 14:26:33', '2026-08-05 14:26:33'),
(4, 'Lê Văn Cường', 'nhanvien3', '123456', 'nv3@shop.com', '0904444444', 'Long An', 0, 1, '2026-08-05 14:26:33', '2026-08-05 14:26:33'),
(5, 'Phạm Thị Duyen', 'nhanvien4', '123456', 'nv4@shop.com', '0905555555', 'TP.HCM', 0, 1, '2026-08-05 14:26:33', '2026-08-05 14:26:33');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_code` (`order_code`),
  ADD KEY `fk_orders_customer` (`customer_id`),
  ADD KEY `fk_orders_user` (`user_id`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_order_details_order` (`order_id`),
  ADD KEY `fk_order_details_product` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `fk_product_category` (`category_id`),
  ADD KEY `fk_product_brand` (`brand_id`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_product_images_product` (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orders_customer` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_orders_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `fk_order_details_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_order_details_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_product_brand` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`),
  ADD CONSTRAINT `fk_product_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

--
-- Constraints for table `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `fk_product_images_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
