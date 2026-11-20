-- Database: db_shopease_crud
-- Hands-On Lab Product Management (CRUD Kategori, Produk, & Pengelolaan Gambar)
-- Mata Kuliah: Pemrograman Berbasis Kerangka Kerja (PBKK)
-- Mahasiswa: Maleka Ghaniya (NRP: 5025241189)

CREATE DATABASE IF NOT EXISTS `db_shopease_crud` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `db_shopease_crud`;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS `product_images`;
DROP TABLE IF EXISTS `products`;
DROP TABLE IF EXISTS `categories`;
DROP TABLE IF EXISTS `users`;
SET FOREIGN_KEY_CHECKS = 1;

-- --------------------------------------------------------
-- 1. Tabel users (Autentikasi Multi-Role)
-- --------------------------------------------------------
CREATE TABLE `users` (
    `id_user` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `role` ENUM('customer', 'admin', 'operator') NOT NULL DEFAULT 'customer',
    `phone` VARCHAR(20) DEFAULT NULL,
    `address` TEXT DEFAULT NULL,
    `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `users` (`id_user`, `name`, `email`, `password`, `role`, `phone`, `address`) VALUES
(1, 'Administrator ShopEase', 'admin@shopease.com', '$2y$10$l7rlk1LH2doZ9IaXvFp4B.lDe7Kv4brOMbb3.iZmTATw.nhH8ASW2', 'admin', '081234567890', 'Gedung Rektorat Lt. 2, Kampus ITS Sukolilo, Surabaya'),
(2, 'Operator Gudang & Produk', 'operator@shopease.com', '$2y$10$l7rlk1LH2doZ9IaXvFp4B.lDe7Kv4brOMbb3.iZmTATw.nhH8ASW2', 'operator', '081298765432', 'Warehouse ShopEase Blok B-12, Rungkut Industri, Surabaya'),
(3, 'Maleka Ghaniya', 'malekaghaniya0809@gmail.com', '$2y$10$l7rlk1LH2doZ9IaXvFp4B.lDe7Kv4brOMbb3.iZmTATw.nhH8ASW2', 'customer', '081333444555', 'Jl. Gebang Wetan No. 15, Sukolilo, Surabaya');

-- --------------------------------------------------------
-- 2. Tabel categories (Kategori Produk)
-- --------------------------------------------------------
CREATE TABLE `categories` (
    `id_category` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `slug` VARCHAR(120) NOT NULL UNIQUE,
    `description` TEXT DEFAULT NULL,
    `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `categories` (`id_category`, `name`, `slug`, `description`) VALUES
(1, 'Fashion & Busana', 'fashion', 'Pakaian pria, wanita, sepatu kasual, dan aksesori gaya terkini'),
(2, 'Elektronik & Gadget', 'elektronik', 'Smartphone, laptop, headset bluetooth, dan perangkat pintar'),
(3, 'Olahraga & Outdoor', 'olahraga', 'Peralatan gym, sepatu lari, matras yoga, dan perlengkapan camping'),
(4, 'Buku & Perlengkapan Studi', 'buku-studi', 'Buku referensi pemrograman, sains, novel, dan stationery');

-- --------------------------------------------------------
-- 3. Tabel products (Data Produk & Stok)
-- --------------------------------------------------------
CREATE TABLE `products` (
    `id_product` INT AUTO_INCREMENT PRIMARY KEY,
    `id_category` INT NOT NULL,
    `name` VARCHAR(150) NOT NULL,
    `slug` VARCHAR(180) NOT NULL UNIQUE,
    `description` TEXT DEFAULT NULL,
    `price` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
    `stock` INT NOT NULL DEFAULT 0,
    `status` ENUM('tersedia', 'tidak_tersedia') NOT NULL DEFAULT 'tersedia',
    `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_product_category`
      FOREIGN KEY (`id_category`)
      REFERENCES `categories` (`id_category`)
      ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `products` (`id_product`, `id_category`, `name`, `slug`, `description`, `price`, `stock`, `status`) VALUES
(1, 1, 'Sepatu Sneakers Pria Streetwear', 'sepatu-sneakers-pria-streetwear', 'Sneakers kasual sol karet empuk, breathable canvas cocok untuk daily wear.', 299000.00, 25, 'tersedia'),
(2, 2, 'Headphone Wireless Active Noise Cancelling', 'headphone-wireless-anc', 'Headphone Bluetooth 5.3 dengan bass mendalam dan peredam bising aktif hingga 35dB.', 489000.00, 15, 'tersedia'),
(3, 2, 'Mechanical Keyboard RGB Dual Mode', 'mechanical-keyboard-rgb-dual-mode', 'Keyboard mekanikal 75% hot-swappable dengan switch linear gateron yellow.', 650000.00, 12, 'tersedia'),
(4, 3, 'Matras Yoga Anti-Slip TPE 6mm', 'matras-yoga-antislip-tpe', 'Matras senam yoga dan pilates ramah lingkungan dengan tekstur ganda anti licin.', 175000.00, 30, 'tersedia'),
(5, 4, 'Buku Panduan Mahir CodeIgniter 4', 'buku-panduan-mahir-codeigniter-4', 'Buku komprehensif implementasi MVC, Database Active Record, RESTful API, dan CRUD.', 125000.00, 40, 'tersedia'),
(6, 1, 'Jaket Hoodie Fleece Oversize Casual', 'jaket-hoodie-fleece-oversize', 'Hoodie katun fleece tebal hangat dengan kantong kangguru dan tali serut adjustable.', 219000.00, 0, 'tidak_tersedia');

-- --------------------------------------------------------
-- 4. Tabel product_images (Multi-Image Gallery & Upload)
-- --------------------------------------------------------
CREATE TABLE `product_images` (
    `id_image` INT AUTO_INCREMENT PRIMARY KEY,
    `id_product` INT NOT NULL,
    `image` VARCHAR(255) NOT NULL,
    `is_primary` TINYINT(1) DEFAULT 0,
    `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_image_product`
      FOREIGN KEY (`id_product`)
      REFERENCES `products` (`id_product`)
      ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `product_images` (`id_image`, `id_product`, `image`, `is_primary`) VALUES
(1, 1, 'sneakers-primary.svg', 1),
(2, 1, 'sneakers-angle2.svg', 0),
(3, 2, 'headphone-primary.svg', 1),
(4, 3, 'keyboard-primary.svg', 1),
(5, 4, 'matras-primary.svg', 1),
(6, 5, 'book-primary.svg', 1),
(7, 6, 'hoodie-primary.svg', 1);
