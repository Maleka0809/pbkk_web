-- Database: db_shopease_auth
-- Hands-On Lab & Roadmap Implementasi Authentication System ShopEase
-- Mata Kuliah: Pemrograman Berbasis Kerangka Kerja (PBKK)
-- Mahasiswa: Maleka Ghaniya (NRP: 5025241189)

CREATE DATABASE IF NOT EXISTS `db_shopease_auth` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `db_shopease_auth`;

-- --------------------------------------------------------
-- Table structure for table `users` (sesuai HOL & Roadmap)
-- --------------------------------------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
    `id_user` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `role` ENUM('customer', 'admin', 'operator') NOT NULL DEFAULT 'customer',
    `phone` VARCHAR(20) DEFAULT NULL,
    `address` TEXT DEFAULT NULL,
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Data awal untuk pengujian (Default password: password123)
-- --------------------------------------------------------
INSERT INTO `users` (`id_user`, `name`, `email`, `password`, `role`, `phone`, `address`, `created_at`, `updated_at`) VALUES
(1, 'Administrator ShopEase', 'admin@shopease.com', '$2y$10$l7rlk1LH2doZ9IaXvFp4B.lDe7Kv4brOMbb3.iZmTATw.nhH8ASW2', 'admin', '081234567890', 'Gedung Rektorat Lt. 2, Kampus ITS Sukolilo, Surabaya', NOW(), NOW()),
(2, 'Operator Gudang & Produk', 'operator@shopease.com', '$2y$10$l7rlk1LH2doZ9IaXvFp4B.lDe7Kv4brOMbb3.iZmTATw.nhH8ASW2', 'operator', '081298765432', 'Warehouse ShopEase Blok B-12, Rungkut Industri, Surabaya', NOW(), NOW()),
(3, 'Maleka Ghaniya', 'malekaghaniya0809@gmail.com', '$2y$10$l7rlk1LH2doZ9IaXvFp4B.lDe7Kv4brOMbb3.iZmTATw.nhH8ASW2', 'customer', '081333444555', 'Jl. Gebang Wetan No. 15, Sukolilo, Surabaya', NOW(), NOW()),
(4, 'Budi Santoso', 'budi@gmail.com', '$2y$10$l7rlk1LH2doZ9IaXvFp4B.lDe7Kv4brOMbb3.iZmTATw.nhH8ASW2', 'customer', '082155667788', 'Jl. Manyar Kertoarjo No. 8, Surabaya', NOW(), NOW());

-- --------------------------------------------------------
-- Table structure for table `categories`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `slug` VARCHAR(120) NOT NULL UNIQUE,
    `description` TEXT DEFAULT NULL,
    `icon` VARCHAR(50) DEFAULT 'bi-tag',
    `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `icon`) VALUES
(1, 'Elektronik & Gadget', 'elektronik-gadget', 'Peralatan elektronik, laptop, smartphone, dan aksesoris modern', 'bi-laptop'),
(2, 'Pakaian Pria & Wanita', 'fashion', 'Busana pria wanita terkini, kasual, formal dan sporty', 'bi-bag-heart'),
(3, 'Perlengkapan Rumah', 'home-living', 'Peralatan dapur, dekorasi ruangan, dan furnitur minimalis', 'bi-house-heart'),
(4, 'Buku & Alat Tulis', 'buku-alat-tulis', 'Buku referensi pemrograman, novel, dan perlengkapan studi', 'bi-journal-bookmark');

-- --------------------------------------------------------
-- Table structure for table `products`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `category_id` INT NOT NULL,
    `name` VARCHAR(200) NOT NULL,
    `slug` VARCHAR(220) NOT NULL UNIQUE,
    `description` TEXT DEFAULT NULL,
    `price` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
    `stock` INT NOT NULL DEFAULT 0,
    `weight` INT NOT NULL DEFAULT 500,
    `image` VARCHAR(255) DEFAULT 'default-product.svg',
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_products_categories_auth` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `products` (`id`, `category_id`, `name`, `slug`, `description`, `price`, `stock`, `weight`, `image`, `is_active`) VALUES
(1, 1, 'Laptop Asus Zenbook OLED 14', 'laptop-asus-zenbook-oled-14', 'Laptop ultra tipis layar OLED 2.8K, Intel Core i7 Gen 13, RAM 16GB, SSD 1TB.', 16499000.00, 15, 1390, 'laptop-zenbook.svg', 1),
(2, 1, 'Wireless Mechanical Keyboard RGB', 'wireless-mechanical-keyboard-rgb', 'Keyboard mechanical dual mode Bluetooth dan 2.4GHz dengan switch Gateron Pro Yellow.', 899000.00, 40, 850, 'keyboard-mech.svg', 1),
(3, 1, 'Noise Cancelling Headphone Sony WH-1000XM5', 'sony-wh1000xm5', 'Headphone nirkabel premium industri terdepan dalam active noise cancelling.', 4999000.00, 25, 250, 'headphone-sony.svg', 1),
(4, 2, 'Kemeja Flannel Tartan Casual', 'kemeja-flannel-tartan-casual', 'Kemeja motif kotak bahan katun 100% breathable dan nyaman dipakai santai.', 249000.00, 60, 320, 'shirt-flannel.svg', 1),
(5, 3, 'Smart Aroma Diffuser Ultrasonic', 'smart-aroma-diffuser-ultrasonic', 'Diffuser aromaterapi dengan lampu ambience 7 warna dan koneksi aplikasi pintar.', 320000.00, 35, 450, 'diffuser.svg', 1),
(6, 4, 'Buku Pemrograman Web Modern (CI4 & MVC)', 'buku-pemrograman-web-modern', 'Panduan komprehensif implementasi framework CodeIgniter 4, MVC, dan RESTful API.', 135000.00, 50, 400, 'book-ci4.svg', 1);

