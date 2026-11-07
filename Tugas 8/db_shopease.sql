-- ========================================================
-- DATABASE: db_shopease
-- Project: ShopEase - Online Store (CodeIgniter 4 + MySQL)
-- ========================================================

CREATE DATABASE IF NOT EXISTS `db_shopease` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `db_shopease`;

-- Nonaktifkan pengecekan foreign key sementara
SET FOREIGN_KEY_CHECKS = 0;
DROP TRIGGER IF EXISTS `trg_reduce_stock_after_order`;
DROP TRIGGER IF EXISTS `trg_restore_stock_on_cancel`;
DROP TABLE IF EXISTS `shipments`;
DROP TABLE IF EXISTS `payments`;
DROP TABLE IF EXISTS `order_items`;
DROP TABLE IF EXISTS `orders`;
DROP TABLE IF EXISTS `addresses`;
DROP TABLE IF EXISTS `cart_items`;
DROP TABLE IF EXISTS `carts`;
DROP TABLE IF EXISTS `products`;
DROP TABLE IF EXISTS `categories`;
DROP TABLE IF EXISTS `users`;
SET FOREIGN_KEY_CHECKS = 1;

-- --------------------------------------------------------
-- 1. Tabel users
-- --------------------------------------------------------
CREATE TABLE `users` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(150) NOT NULL,
  `email` VARCHAR(150) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `role` ENUM('admin', 'operator', 'customer') NOT NULL DEFAULT 'customer',
  `phone` VARCHAR(30) DEFAULT NULL,
  `avatar` VARCHAR(255) DEFAULT 'default-avatar.svg',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 2. Tabel categories
-- --------------------------------------------------------
CREATE TABLE `categories` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `slug` VARCHAR(120) NOT NULL UNIQUE,
  `description` TEXT DEFAULT NULL,
  `icon` VARCHAR(50) DEFAULT 'bi-box-seam',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 3. Tabel products
-- --------------------------------------------------------
CREATE TABLE `products` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `category_id` INT(11) UNSIGNED NOT NULL,
  `name` VARCHAR(150) NOT NULL,
  `slug` VARCHAR(180) NOT NULL UNIQUE,
  `description` TEXT DEFAULT NULL,
  `price` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `stock` INT(11) NOT NULL DEFAULT 0,
  `image` VARCHAR(255) NOT NULL DEFAULT 'default-product.svg',
  `status` ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_products_category` (`category_id`),
  CONSTRAINT `fk_products_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 4. Tabel carts
-- --------------------------------------------------------
CREATE TABLE `carts` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) UNSIGNED NOT NULL UNIQUE,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_carts_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 5. Tabel cart_items
-- --------------------------------------------------------
CREATE TABLE `cart_items` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `cart_id` INT(11) UNSIGNED NOT NULL,
  `product_id` INT(11) UNSIGNED NOT NULL,
  `quantity` INT(11) NOT NULL DEFAULT 1,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_cart_items_cart` (`cart_id`),
  KEY `fk_cart_items_product` (`product_id`),
  CONSTRAINT `fk_cart_items_cart` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_cart_items_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 6. Tabel addresses
-- --------------------------------------------------------
CREATE TABLE `addresses` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) UNSIGNED NOT NULL,
  `recipient_name` VARCHAR(100) NOT NULL,
  `phone` VARCHAR(30) NOT NULL,
  `address_line` TEXT NOT NULL,
  `city` VARCHAR(100) NOT NULL,
  `postal_code` VARCHAR(20) NOT NULL,
  `is_default` TINYINT(1) DEFAULT 0,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_addresses_user` (`user_id`),
  CONSTRAINT `fk_addresses_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 7. Tabel orders
-- --------------------------------------------------------
CREATE TABLE `orders` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_number` VARCHAR(50) NOT NULL UNIQUE,
  `user_id` INT(11) UNSIGNED NOT NULL,
  `address_id` INT(11) UNSIGNED DEFAULT NULL,
  `total_amount` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `shipping_cost` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `grand_total` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `status` ENUM('pending', 'paid', 'processing', 'shipped', 'completed', 'cancelled') NOT NULL DEFAULT 'pending',
  `customer_notes` TEXT DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_orders_user` (`user_id`),
  KEY `fk_orders_address` (`address_id`),
  CONSTRAINT `fk_orders_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_orders_address` FOREIGN KEY (`address_id`) REFERENCES `addresses` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 8. Tabel order_items
-- --------------------------------------------------------
CREATE TABLE `order_items` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` INT(11) UNSIGNED NOT NULL,
  `product_id` INT(11) UNSIGNED NOT NULL,
  `product_name` VARCHAR(150) NOT NULL,
  `price` DECIMAL(12,2) NOT NULL,
  `quantity` INT(11) NOT NULL DEFAULT 1,
  `subtotal` DECIMAL(12,2) NOT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_order_items_order` (`order_id`),
  KEY `fk_order_items_product` (`product_id`),
  CONSTRAINT `fk_order_items_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_order_items_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 9. Tabel payments
-- --------------------------------------------------------
CREATE TABLE `payments` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` INT(11) UNSIGNED NOT NULL,
  `payment_method` VARCHAR(50) NOT NULL DEFAULT 'Bank Transfer',
  `bank_name` VARCHAR(50) NOT NULL DEFAULT 'BCA',
  `account_number` VARCHAR(50) DEFAULT NULL,
  `account_holder` VARCHAR(100) DEFAULT NULL,
  `amount` DECIMAL(12,2) NOT NULL,
  `proof_image` VARCHAR(255) DEFAULT NULL,
  `status` ENUM('pending', 'verified', 'rejected') NOT NULL DEFAULT 'pending',
  `verified_by` INT(11) UNSIGNED DEFAULT NULL,
  `verified_at` DATETIME DEFAULT NULL,
  `rejection_reason` TEXT DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_payments_order` (`order_id`),
  KEY `fk_payments_verifier` (`verified_by`),
  CONSTRAINT `fk_payments_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_payments_verifier` FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 10. Tabel shipments
-- --------------------------------------------------------
CREATE TABLE `shipments` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` INT(11) UNSIGNED NOT NULL,
  `courier` VARCHAR(50) NOT NULL DEFAULT 'JNE',
  `service` VARCHAR(50) NOT NULL DEFAULT 'REG (Reguler)',
  `tracking_number` VARCHAR(100) DEFAULT NULL,
  `status` ENUM('preparing', 'in_transit', 'delivered') NOT NULL DEFAULT 'preparing',
  `shipped_at` DATETIME DEFAULT NULL,
  `delivered_at` DATETIME DEFAULT NULL,
  `notes` TEXT DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_shipments_order` (`order_id`),
  CONSTRAINT `fk_shipments_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Triggers: Pengurangan stok otomatis saat order_items bertambah
-- --------------------------------------------------------
DELIMITER $$
CREATE TRIGGER `trg_reduce_stock_after_order` AFTER INSERT ON `order_items` 
FOR EACH ROW BEGIN
    UPDATE `products` 
    SET `stock` = `stock` - NEW.`quantity`
    WHERE `id` = NEW.`product_id`;
END$$

CREATE TRIGGER `trg_restore_stock_on_cancel` AFTER UPDATE ON `orders`
FOR EACH ROW BEGIN
    IF OLD.`status` != 'cancelled' AND NEW.`status` = 'cancelled' THEN
        UPDATE `products` p
        INNER JOIN `order_items` oi ON p.`id` = oi.`product_id`
        SET p.`stock` = p.`stock` + oi.`quantity`
        WHERE oi.`order_id` = NEW.`id`;
    END IF;
END$$
DELIMITER ;

-- ========================================================
-- DATA AWAL (SEED DATA)
-- ========================================================

-- Akun Pengguna:
-- Admin:    admin@shopease.com    / admin123
-- Operator: operator@shopease.com / operator123
-- Customer: budi@gmail.com        / user123
-- Customer: siti@gmail.com        / user123
INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `phone`, `avatar`) VALUES
(1, 'Administrator ShopEase', 'admin@shopease.com', '$2y$10$jUUKEi5ker0uY4Xb7fCzWOPssUGgM/7DOZRhSY2E0/NZKF1GTnGMW', 'admin', '081299988877', 'avatar-admin.svg'),
(2, 'Operator Gudang & Pengiriman', 'operator@shopease.com', '$2y$10$q6B83G1Zz41/IeqfTYvaKeNKST8BZ2eRbWjSCxOIe4MM1q1tcTwJy', 'operator', '081277766655', 'avatar-operator.svg'),
(3, 'Budi Santoso', 'budi@gmail.com', '$2y$10$G6X7g1UprYK2MM4vfsmileWOHGtxT3qNJGrNIkZqU9ANuR7RaO6bW', 'customer', '081234567890', 'avatar-customer.svg'),
(4, 'Siti Nurhaliza', 'siti@gmail.com', '$2y$10$G6X7g1UprYK2MM4vfsmileWOHGtxT3qNJGrNIkZqU9ANuR7RaO6bW', 'customer', '082211223344', 'avatar-customer.svg');

-- Kategori Produk
INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `icon`) VALUES
(1, 'Laptop & Komputer', 'laptop-komputer', 'Perangkat komputasi portabel, PC rakitan, dan aksesoris performa tinggi', 'bi-laptop'),
(2, 'Smartphone & Tablet', 'smartphone-tablet', 'Ponsel pintar flagship, tablet kerja, dan aksesoris mobile', 'bi-phone'),
(3, 'Audio & Headphone', 'audio-headphone', 'Earphone TWS, headphone ANC studio, dan speaker bluetooth', 'bi-headphones'),
(4, 'Gaming Gear', 'gaming-gear', 'Konsol gaming, mouse gaming ultra-ringan, dan mechanical keyboard', 'bi-controller'),
(5, 'Smartwatch & Wearables', 'smartwatch-wearables', 'Jam tangan pintar fitness tracker dan perangkat wearable modern', 'bi-smartwatch');

-- Produk
INSERT INTO `products` (`id`, `category_id`, `name`, `slug`, `description`, `price`, `stock`, `image`, `status`) VALUES
(1, 1, 'ASUS ROG Zephyrus G14 OLED', 'asus-rog-zephyrus-g14-oled', 'AMD Ryzen 9 8945HS, NVIDIA GeForce RTX 4060 8GB, RAM 16GB LPDDR5X, SSD 1TB NVMe, Layar 14 inch 3K OLED 120Hz ROG Nebula Display.', 25999000.00, 10, 'rog-g14.svg', 'active'),
(2, 1, 'MacBook Air M3 13 Inch Retina', 'macbook-air-m3-13-inch-retina', 'Apple M3 Chip 8-Core CPU & 10-Core GPU, 16GB Unified Memory, SSD 512GB, Liquid Retina Display 500 nits, MagSafe 3 charging.', 18999000.00, 14, 'macbook-m3.svg', 'active'),
(3, 1, 'Lenovo Legion Slim 5 Gen 9', 'lenovo-legion-slim-5-gen-9', 'AMD Ryzen 7 8845HS, RTX 4060 8GB GDDR6, RAM 16GB DDR5 5600MHz, SSD 512GB, Layar 16 inch WQXGA 165Hz 100% sRGB.', 19800000.00, 8, 'lenovo-legion.svg', 'active'),
(4, 2, 'Samsung Galaxy S24 Ultra 5G', 'samsung-galaxy-s24-ultra-5g', 'Snapdragon 8 Gen 3 for Galaxy, RAM 12GB, Internal 256GB, Layar Dynamic AMOLED 2X 6.8 inch 120Hz, Kamera 200MP Quad Tele AI + S-Pen.', 19499000.00, 15, 'galaxy-s24.svg', 'active'),
(5, 2, 'iPhone 15 Pro Max 256GB Titanium', 'iphone-15-pro-max-256gb-titanium', 'Chip A17 Pro (3nm), Desain Titanium Kuat & Ringan, Kamera Utama 48MP dengan 5x Telephoto Optical Zoom, USB Type-C 10Gbps.', 22999000.00, 12, 'iphone-15pro.svg', 'active'),
(6, 2, 'Xiaomi 14 Leica Summilux', 'xiaomi-14-leica-summilux', 'Snapdragon 8 Gen 3, RAM 12GB, ROM 256GB, Kamera Profesional Leica Vario-Summilux 50MP, HyperOS, Fast Charging 90W HyperCharge.', 11999000.00, 20, 'xiaomi-14.svg', 'active'),
(7, 3, 'Sony WH-1000XM5 Wireless ANC', 'sony-wh-1000xm5-wireless-anc', 'Headphone Wireless Active Noise Cancelling Industri Terbaik, Auto NC Optimizer, 30 Jam Baterai, Hi-Res Audio Wireless LDAC.', 4899000.00, 22, 'sony-xm5.svg', 'active'),
(8, 3, 'AirPods Pro 2nd Gen USB-C', 'airpods-pro-2nd-gen-usb-c', 'Chip Apple H2, Peredam Bising Aktif 2x lebih baik, Audio Adaptif, Transparency Mode, Casing MagSafe dengan speaker pelacak.', 3699000.00, 25, 'airpods-pro2.svg', 'active'),
(9, 4, 'Sony PlayStation 5 Slim Disc Edition', 'sony-playstation-5-slim-disc-edition', 'Konsol PS5 Generasi Slim 1TB SSD Ultra-High Speed, Ray Tracing, 4K-TV Gaming hingga 120 FPS, Termasuk 1x DualSense Wireless Controller.', 8899000.00, 9, 'ps5-slim.svg', 'active'),
(10, 4, 'Keychron Q1 Pro QMK Wireless Keyboard', 'keychron-q1-pro-qmk-wireless-keyboard', 'Custom Mechanical Keyboard 75% Full Aluminum CNC, Wireless Bluetooth 5.1 & Type-C, Hot-swappable K Pro Banana Switches, Double Gasket.', 2950000.00, 18, 'keychron-q1.svg', 'active'),
(11, 4, 'Logitech G Pro X Superlight 2', 'logitech-g-pro-x-superlight-2', 'Mouse Gaming Wireless Ultra Ringan 60 gram, Sensor HERO 2 hingga 32.000 DPI, Switch Hybrid LIGHTFORCE optik-mekanikal.', 2199000.00, 30, 'logitech-superlight.svg', 'active'),
(12, 5, 'Apple Watch Series 9 GPS 45mm', 'apple-watch-series-9-gps-45mm', 'Chip S9 SiP, Layar Always-On Retina 2000 nits, Gerakan Double Tap Ajaib, Sensor Oksigen Darah & EKG, Pelacak Detak Jantung Akurat.', 7199000.00, 16, 'apple-watch9.svg', 'active');

-- Alamat Pengiriman Contoh
INSERT INTO `addresses` (`id`, `user_id`, `recipient_name`, `phone`, `address_line`, `city`, `postal_code`, `is_default`) VALUES
(1, 3, 'Budi Santoso', '081234567890', 'Jl. Raya ITS Kampus Sukolilo Gedung Informatika Lt. 2', 'Surabaya', '60111', 1),
(2, 4, 'Siti Nurhaliza', '082211223344', 'Jl. Darmo Permai Timur Blok A No. 15', 'Surabaya', '60226', 1);

-- Pesanan Contoh (Order 1: Selesai / Completed)
INSERT INTO `orders` (`id`, `order_number`, `user_id`, `address_id`, `total_amount`, `shipping_cost`, `grand_total`, `status`, `customer_notes`, `created_at`) VALUES
(1, 'ORD-20261107-0001', 3, 1, 4899000.00, 25000.00, 4924000.00, 'completed', 'Tolong bubble wrap tebal ya kak.', '2026-11-05 10:15:00'),
(2, 'ORD-20261107-0002', 4, 2, 2950000.00, 20000.00, 2970000.00, 'processing', 'Kirim warna carbon black.', '2026-11-07 14:30:00');

-- Detail Item Pesanan
INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_name`, `price`, `quantity`, `subtotal`) VALUES
(1, 1, 7, 'Sony WH-1000XM5 Wireless ANC', 4899000.00, 1, 4899000.00),
(2, 2, 10, 'Keychron Q1 Pro QMK Wireless Keyboard', 2950000.00, 1, 2950000.00);

-- Pembayaran
INSERT INTO `payments` (`id`, `order_id`, `payment_method`, `bank_name`, `account_number`, `account_holder`, `amount`, `proof_image`, `status`, `verified_by`, `verified_at`) VALUES
(1, 1, 'Bank Transfer', 'BCA', '8291029102', 'Budi Santoso', 4924000.00, 'proof_sample_1.svg', 'verified', 1, '2026-11-05 11:00:00'),
(2, 2, 'Bank Transfer', 'Mandiri', '1420098192019', 'Siti Nurhaliza', 2970000.00, 'proof_sample_2.svg', 'verified', 1, '2026-11-07 15:00:00');

-- Pengiriman
INSERT INTO `shipments` (`id`, `order_id`, `courier`, `service`, `tracking_number`, `status`, `shipped_at`, `delivered_at`, `notes`) VALUES
(1, 1, 'JNE', 'YES (Yakin Esok Sampai)', 'JNE889920192831', 'delivered', '2026-11-05 14:00:00', '2026-11-06 13:20:00', 'Paket telah diterima satpam komplek.'),
(2, 2, 'SiCepat', 'BEST (Besok Sampai)', 'SC991029102938', 'preparing', NULL, NULL, 'Pesanan sedang dipacking di gudang.');
