CREATE DATABASE IF NOT EXISTS `db_toko_online` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `db_toko_online`;

DROP TRIGGER IF EXISTS `pesanan_penjualan`;
DROP TABLE IF EXISTS `tb_pesanan`;
DROP TABLE IF EXISTS `tb_invoice`;
DROP TABLE IF EXISTS `tb_barang`;
DROP TABLE IF EXISTS `tb_user`;

-- --------------------------------------------------------
-- Struktur dari tabel `tb_user`
-- --------------------------------------------------------
CREATE TABLE `tb_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role_id` tinyint(1) NOT NULL COMMENT '1 = Admin, 2 = Customer',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `tb_user` (`id`, `nama`, `username`, `password`, `role_id`) VALUES
(1, 'Administrator Toko', 'admin', '123', 1),
(2, 'Budi Santoso (Pelanggan)', 'user', '123', 2);

-- --------------------------------------------------------
-- Struktur dari tabel `tb_barang`
-- --------------------------------------------------------
CREATE TABLE `tb_barang` (
  `id_brg` int(11) NOT NULL AUTO_INCREMENT,
  `nama_brg` varchar(120) NOT NULL,
  `keterangan` varchar(255) NOT NULL,
  `kategori` varchar(60) NOT NULL,
  `harga` int(11) NOT NULL,
  `stok` int(4) NOT NULL,
  `gambar` text NOT NULL,
  PRIMARY KEY (`id_brg`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `tb_barang` (`id_brg`, `nama_brg`, `keterangan`, `kategori`, `harga`, `stok`, `gambar`) VALUES
(1, 'ASUS ROG Zephyrus G14', 'AMD Ryzen 9, RTX 4060 8GB, RAM 16GB DDR5, SSD 1TB, Layar 14 inch OLED 120Hz', 'Laptop', 24500000, 8, 'laptop_rog.svg'),
(2, 'MacBook Air M3 13 Inch', 'Apple M3 Chip 8-Core CPU, Unified Memory 16GB, SSD 512GB, Liquid Retina Display', 'Laptop', 18999000, 12, 'laptop_macbook.svg'),
(3, 'Samsung Galaxy S24 Ultra', 'Snapdragon 8 Gen 3, RAM 12GB, Internal 256GB, Kamera 200MP AI + S-Pen', 'Smartphone', 19499000, 15, 'hp_s24.svg'),
(4, 'iPhone 15 Pro 256GB', 'Chip A17 Pro Titanium, Kamera Pro 48MP, USB-C, Layar Super Retina XDR 120Hz', 'Smartphone', 20999000, 10, 'hp_iphone15.svg'),
(5, 'Sony WH-1000XM5 Wireless ANC', 'Headphone Bluetooth Active Noise Cancelling Terbaik, Baterai 30 Jam, Hi-Res Audio', 'Aksesoris', 4999000, 20, 'aksesoris_sony.svg'),
(6, 'Keychron K2 Pro Mechanical Keyboard', 'Wireless QMK/VIA Custom Mechanical Keyboard RGB, Hot-swappable Brown Switch', 'Aksesoris', 1650000, 25, 'aksesoris_keyboard.svg'),
(7, 'Sony PlayStation 5 Slim Disc Edition', 'Konsol PS5 Slim 1TB SSD Ultra-High Speed + 1x DualSense Wireless Controller', 'Gaming', 8799000, 9, 'gaming_ps5.svg'),
(8, 'Logitech G Pro X Superlight 2', 'Wireless Gaming Mouse Ultra-Ringan 60g, Sensor HERO 2 32.000 DPI, Lightspeed', 'Gaming', 2190000, 30, 'gaming_mouse.svg');

-- --------------------------------------------------------
-- Struktur dari tabel `tb_invoice`
-- --------------------------------------------------------
CREATE TABLE `tb_invoice` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  `alamat` varchar(255) NOT NULL,
  `no_telp` varchar(30) DEFAULT '-',
  `jasa_pengiriman` varchar(50) DEFAULT 'JNE',
  `bank` varchar(50) DEFAULT 'BCA - 1234567890',
  `tgl_pesan` datetime NOT NULL,
  `batas_bayar` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `tb_invoice` (`id`, `nama`, `alamat`, `no_telp`, `jasa_pengiriman`, `bank`, `tgl_pesan`, `batas_bayar`) VALUES
(1, 'Budi Santoso', 'Jl. Raya ITS Sukolilo No. 10, Surabaya', '081234567890', 'JNE Reguler', 'BCA - 8820192831', '2026-11-03 19:00:00', '2026-11-04 19:00:00');

-- --------------------------------------------------------
-- Struktur dari tabel `tb_pesanan`
-- --------------------------------------------------------
CREATE TABLE `tb_pesanan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_invoice` int(11) NOT NULL,
  `id_brg` int(11) NOT NULL,
  `nama_brg` varchar(120) NOT NULL,
  `jumlah` int(3) NOT NULL,
  `harga` int(11) NOT NULL,
  `pilihan` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `tb_pesanan` (`id`, `id_invoice`, `id_brg`, `nama_brg`, `jumlah`, `harga`, `pilihan`) VALUES
(1, 1, 6, 'Keychron K2 Pro Mechanical Keyboard', 1, 1650000, ''),
(2, 1, 8, 'Logitech G Pro X Superlight 2', 1, 2190000, '');

-- --------------------------------------------------------
-- Trigger otomatis kurangi stok saat pesanan baru masuk
-- --------------------------------------------------------
DELIMITER $$
CREATE TRIGGER `pesanan_penjualan` AFTER INSERT ON `tb_pesanan` FOR EACH ROW BEGIN
	UPDATE tb_barang SET stok = stok - NEW.jumlah
    WHERE id_brg = NEW.id_brg;
END$$
DELIMITER ;

