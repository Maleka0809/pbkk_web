<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Panel Administrator - ShopEase') ?></title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f1f5f9;
            color: #1e293b;
        }
        .sidebar {
            width: 260px;
            min-height: 100vh;
            background-color: #0f172a;
            color: #94a3b8;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 100;
        }
        .sidebar-brand {
            padding: 1.25rem 1.5rem;
            font-size: 1.35rem;
            font-weight: 800;
            color: #ffffff;
            border-bottom: 1px solid #1e293b;
            text-decoration: none;
            display: flex;
            align-items: center;
        }
        .sidebar-brand span {
            color: #38bdf8;
        }
        .sidebar-nav {
            padding: 1rem 0;
            list-style: none;
            margin: 0;
        }
        .sidebar-heading {
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.75px;
            color: #64748b;
            padding: 0.75rem 1.5rem 0.25rem;
        }
        .sidebar-link {
            display: flex;
            align-items: center;
            padding: 0.65rem 1.5rem;
            color: #cbd5e1;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.92rem;
            transition: all 0.2s;
        }
        .sidebar-link i {
            font-size: 1.15rem;
            margin-right: 0.85rem;
            width: 20px;
            text-align: center;
        }
        .sidebar-link:hover, .sidebar-link.active {
            color: #ffffff;
            background-color: #1e293b;
            border-left: 4px solid #38bdf8;
        }
        .main-content {
            margin-left: 260px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .topbar {
            height: 68px;
            background: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
            position: sticky;
            top: 0;
            z-index: 90;
        }
        .content-body {
            padding: 2rem;
            flex-grow: 1;
        }
    </style>
</head>
<body>

    <!-- Sidebar Navigation -->
    <aside class="sidebar">
        <a href="<?= base_url('admin/dashboard') ?>" class="sidebar-brand">
            <i class="bi bi-bag-heart-fill text-info me-2"></i>
            Shop<span>Ease</span> <small class="badge bg-primary ms-2" style="font-size: 10px;">ADMIN</small>
        </a>

        <ul class="sidebar-nav">
            <li class="sidebar-heading">Menu Utama</li>
            <li>
                <a href="<?= base_url('admin/dashboard') ?>" class="sidebar-link">
                    <i class="bi bi-speedometer2 text-info"></i> Dashboard
                </a>
            </li>

            <li class="sidebar-heading">Manajemen Produk</li>
            <li>
                <a href="<?= base_url('admin/categories') ?>" class="sidebar-link">
                    <i class="bi bi-tags text-warning"></i> Kelola Kategori
                </a>
            </li>
            <li>
                <a href="<?= base_url('admin/products') ?>" class="sidebar-link">
                    <i class="bi bi-box-seam text-primary"></i> Kelola Produk
                </a>
            </li>

            <li class="sidebar-heading">Transaksi & Pesanan</li>
            <li>
                <a href="<?= base_url('admin/orders') ?>" class="sidebar-link">
                    <i class="bi bi-receipt text-success"></i> Pesanan Masuk
                </a>
            </li>
            <li>
                <a href="<?= base_url('admin/payments') ?>" class="sidebar-link">
                    <i class="bi bi-credit-card text-info"></i> Verifikasi Pembayaran
                </a>
            </li>
            <li>
                <a href="<?= base_url('admin/shipments') ?>" class="sidebar-link">
                    <i class="bi bi-truck text-secondary"></i> Pengiriman & Resi
                </a>
            </li>

            <li class="sidebar-heading">Pengguna & Laporan</li>
            <li>
                <a href="<?= base_url('admin/users') ?>" class="sidebar-link">
                    <i class="bi bi-people text-light"></i> Data Pengguna
                </a>
            </li>
            <li>
                <a href="<?= base_url('admin/reports') ?>" class="sidebar-link">
                    <i class="bi bi-graph-up-arrow text-danger"></i> Laporan Penjualan
                </a>
            </li>

            <li class="sidebar-heading">Navigasi Toko</li>
            <li>
                <a href="<?= base_url() ?>" target="_blank" class="sidebar-link text-white-50">
                    <i class="bi bi-shop"></i> Buka Toko (Frontend)
                </a>
            </li>
            <li>
                <a href="<?= base_url('logout') ?>" class="sidebar-link text-danger">
                    <i class="bi bi-box-arrow-right"></i> Keluar (Logout)
                </a>
            </li>
        </ul>
    </aside>

    <!-- Main Content Area -->
    <div class="main-content">
        <!-- Topbar -->
        <header class="topbar">
            <div>
                <h6 class="mb-0 fw-bold text-dark"><?= esc($title ?? 'Dashboard') ?></h6>
                <small class="text-muted">ShopEase Admin Console &bull; <?= date('l, d F Y') ?></small>
            </div>
            <div class="d-flex align-items-center gap-3">
                <a href="<?= base_url() ?>" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill">
                    <i class="bi bi-box-arrow-up-right me-1"></i> Lihat Toko
                </a>
                <div class="d-flex align-items-center">
                    <img src="<?= base_url('uploads/avatars/' . (session()->get('avatar') ?: 'default-avatar.svg')) ?>" alt="Avatar" width="38" height="38" class="rounded-circle border me-2">
                    <div class="d-none d-md-block text-end">
                        <div class="fw-bold small"><?= esc(session()->get('name')) ?></div>
                        <span class="badge bg-primary-subtle text-primary text-uppercase" style="font-size: 10px;"><?= esc(session()->get('role')) ?></span>
                    </div>
                </div>
            </div>
        </header>

        <!-- Body -->
        <main class="content-body">
            <!-- Flash Alert Messages -->
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
                    <strong>Terdapat kesalahan input:</strong>
                    <ul class="mb-0 mt-1 ps-3">
                        <?php foreach (session()->getFlashdata('errors') as $err): ?>
                            <li><?= esc($err) ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?= $this->renderSection('content') ?>
        </main>
    </div>

    <!-- Bootstrap 5 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

