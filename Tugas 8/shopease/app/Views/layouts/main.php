<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'ShopEase - Toko Online') ?></title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
            color: #1e293b;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .navbar-brand {
            font-weight: 800;
            font-size: 1.45rem;
            letter-spacing: -0.5px;
        }
        .navbar-brand span {
            color: #0284c7;
        }
        .nav-link {
            font-weight: 600;
            color: #475569;
            padding: 0.5rem 0.85rem;
        }
        .nav-link:hover, .nav-link.active {
            color: #0284c7;
        }
        .card-product {
            transition: transform 0.2s, box-shadow 0.2s;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            overflow: hidden;
            background: #fff;
        }
        .card-product:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.08);
        }
        .badge-status {
            font-size: 0.75rem;
            padding: 0.35rem 0.65rem;
            font-weight: 600;
            border-radius: 20px;
        }
        .footer-bg {
            background-color: #0f172a;
            color: #94a3b8;
            margin-top: auto;
        }
        .footer-bg a {
            color: #cbd5e1;
            text-decoration: none;
            transition: color 0.2s;
        }
        .footer-bg a:hover {
            color: #38bdf8;
        }
    </style>
</head>
<body>

    <!-- Top Info Bar -->
    <div class="bg-primary text-white py-1 px-3 small">
        <div class="container d-flex justify-content-between align-items-center">
            <span><i class="bi bi-shield-check me-1"></i> Official Store ShopEase &bull; Garansi Resmi 100% Original</span>
            <span><i class="bi bi-truck me-1"></i> Gratis Ongkir ke Seluruh Indonesia min. belanja Rp 500rb</span>
        </div>
    </div>

    <!-- Main Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top border-bottom shadow-sm">
        <div class="container">
            <a class="navbar-brand text-dark d-flex align-items-center" href="<?= base_url() ?>">
                <i class="bi bi-bag-heart-fill text-primary me-2 fs-3"></i>
                Shop<span>Ease</span>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarMain">
                <!-- Search Box -->
                <form class="d-flex mx-lg-4 my-2 my-lg-0 flex-grow-1" action="<?= base_url('products') ?>" method="GET" style="max-width: 520px;">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control bg-light border-end-0" placeholder="Cari gadget, laptop, smartphone..." value="<?= esc($_GET['search'] ?? '') ?>">
                        <button class="btn btn-light border border-start-0 text-muted" type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </form>

                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-lg-center">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url() ?>"><i class="bi bi-house me-1"></i> Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('products') ?>"><i class="bi bi-grid me-1"></i> Semua Produk</a>
                    </li>

                    <?php if (session()->get('isLoggedIn')): ?>
                        <!-- Cart Icon -->
                        <?php 
                            $cartModel = new \App\Models\CartModel();
                            $cartItemModel = new \App\Models\CartItemModel();
                            $userCart = $cartModel->getOrCreateCart(session()->get('user_id'));
                            $cartCount = $cartItemModel->where('cart_id', $userCart['id'])->countAllResults();
                        ?>
                        <li class="nav-item me-2">
                            <a class="btn btn-outline-primary position-relative rounded-pill px-3 py-1 my-1" href="<?= base_url('cart') ?>">
                                <i class="bi bi-cart3 me-1"></i> Keranjang
                                <?php if ($cartCount > 0): ?>
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                        <?= $cartCount ?>
                                    </span>
                                <?php endif; ?>
                            </a>
                        </li>

                        <!-- User Profile Dropdown -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userMenu" role="button" data-bs-toggle="dropdown">
                                <img src="<?= base_url('uploads/avatars/' . (session()->get('avatar') ?: 'default-avatar.svg')) ?>" alt="Avatar" width="30" height="30" class="rounded-circle me-2 border">
                                <span><?= esc(session()->get('name')) ?></span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                                <li class="px-3 py-2 border-bottom">
                                    <div class="fw-bold"><?= esc(session()->get('name')) ?></div>
                                    <small class="text-muted"><?= esc(session()->get('email')) ?></small>
                                    <span class="badge bg-primary text-uppercase mt-1"><?= esc(session()->get('role')) ?></span>
                                </li>
                                <?php if (in_array(session()->get('role'), ['admin', 'operator'], true)): ?>
                                    <li><a class="dropdown-item py-2" href="<?= base_url('admin/dashboard') ?>"><i class="bi bi-speedometer2 text-primary me-2"></i> Dashboard Admin</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                <?php endif; ?>
                                <li><a class="dropdown-item py-2" href="<?= base_url('orders') ?>"><i class="bi bi-receipt-cutoff text-success me-2"></i> Pesanan Saya</a></li>
                                <li><a class="dropdown-item py-2" href="<?= base_url('cart') ?>"><i class="bi bi-cart text-info me-2"></i> Keranjang Belanja</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item py-2 text-danger" href="<?= base_url('logout') ?>"><i class="bi bi-box-arrow-right me-2"></i> Keluar (Logout)</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item ms-lg-2">
                            <a class="btn btn-outline-secondary rounded-pill px-3 py-1 me-2" href="<?= base_url('login') ?>">Masuk</a>
                            <a class="btn btn-primary rounded-pill px-3 py-1" href="<?= base_url('register') ?>">Daftar</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Global Flash Messages -->
    <div class="container mt-3">
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <i class="bi bi-check-circle-fill me-2 fs-5 align-middle"></i>
                <?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2 fs-5 align-middle"></i>
                <?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                <i class="bi bi-exclamation-octagon-fill me-2 fs-5 align-middle"></i>
                <strong>Terdapat beberapa kesalahan pengisian:</strong>
                <ul class="mb-0 mt-1 ps-3">
                    <?php foreach (session()->getFlashdata('errors') as $err): ?>
                        <li><?= esc($err) ?></li>
                    <?php endforeach; ?>
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('info')): ?>
            <div class="alert alert-info alert-dismissible fade show shadow-sm" role="alert">
                <i class="bi bi-info-circle-fill me-2 fs-5 align-middle"></i>
                <?= session()->getFlashdata('info') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
    </div>

    <!-- Main Dynamic Content -->
    <main class="py-4 flex-grow-1">
        <?= $this->renderSection('content') ?>
    </main>

    <!-- Footer -->
    <footer class="footer-bg pt-5 pb-4">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4">
                    <h5 class="text-white fw-bold mb-3"><i class="bi bi-bag-heart-fill text-primary me-2"></i> ShopEase</h5>
                    <p class="small">Platform e-commerce modern terlengkap untuk perangkat elektronik, laptop gaming, smartphone, dan aksesoris audio berkualitas dengan garansi resmi dan pengiriman aman ke seluruh nusantara.</p>
                    <div class="d-flex gap-2 text-white">
                        <span class="badge bg-secondary"><i class="bi bi-shield-check"></i> 100% Original</span>
                        <span class="badge bg-secondary"><i class="bi bi-truck"></i> Fast Delivery</span>
                        <span class="badge bg-secondary"><i class="bi bi-arrow-repeat"></i> 7 Hari Retur</span>
                    </div>
                </div>
                <div class="col-6 col-lg-2">
                    <h6 class="text-white fw-bold mb-3">Kategori Produk</h6>
                    <ul class="list-unstyled small">
                        <li class="mb-2"><a href="<?= base_url('products?category=1') ?>">Laptop & Komputer</a></li>
                        <li class="mb-2"><a href="<?= base_url('products?category=2') ?>">Smartphone & Tablet</a></li>
                        <li class="mb-2"><a href="<?= base_url('products?category=3') ?>">Audio & Headphone</a></li>
                        <li class="mb-2"><a href="<?= base_url('products?category=4') ?>">Gaming Gear</a></li>
                        <li class="mb-2"><a href="<?= base_url('products?category=5') ?>">Smartwatch</a></li>
                    </ul>
                </div>
                <div class="col-6 col-lg-3">
                    <h6 class="text-white fw-bold mb-3">Layanan & Bantuan</h6>
                    <ul class="list-unstyled small">
                        <li class="mb-2"><a href="<?= base_url('orders') ?>">Lacak Status Pesanan</a></li>
                        <li class="mb-2"><a href="<?= base_url('cart') ?>">Keranjang Belanja</a></li>
                        <li class="mb-2"><a href="<?= base_url('login') ?>">Masuk / Daftar Akun</a></li>
                        <li class="mb-2"><a href="#">Panduan Pembayaran Transfer</a></li>
                        <li class="mb-2"><a href="#">Kebijakan Privasi & Garansi</a></li>
                    </ul>
                </div>
                <div class="col-lg-3">
                    <h6 class="text-white fw-bold mb-3">Metode Pembayaran</h6>
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        <span class="badge bg-light text-dark p-2 border">BCA</span>
                        <span class="badge bg-light text-dark p-2 border">Mandiri</span>
                        <span class="badge bg-light text-dark p-2 border">BRI</span>
                        <span class="badge bg-light text-dark p-2 border">BNI</span>
                    </div>
                    <h6 class="text-white fw-bold mb-2">Partner Kurir</h6>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="badge bg-light text-dark p-2 border">JNE Reguler</span>
                        <span class="badge bg-light text-dark p-2 border">SiCepat</span>
                        <span class="badge bg-light text-dark p-2 border">J&T Express</span>
                    </div>
                </div>
            </div>
            <hr class="my-4 border-secondary">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center small">
                <span>&copy; <?= date('Y') ?> <strong>ShopEase</strong> - Tugas 8 PBKK Web (CodeIgniter 4 & MySQL). All rights reserved.</span>
                <span class="text-muted">Built with CodeIgniter 4 &bull; Bootstrap 5 &bull; MariaDB</span>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

