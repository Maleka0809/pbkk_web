<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container py-4">
    <!-- Flash Messages -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            <?= esc(session()->getFlashdata('success')) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <?= esc(session()->getFlashdata('error')) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row g-4">
        <!-- Customer Profile Card -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="bg-primary text-white p-4 text-center">
                    <div class="rounded-circle bg-white text-primary d-inline-flex align-items-center justify-content-center shadow-sm mb-3" style="width: 80px; height: 80px; font-size: 32px; font-weight: bold;">
                        <?= strtoupper(substr(session()->get('name') ?? 'C', 0, 1)) ?>
                    </div>
                    <h5 class="fw-bold mb-1"><?= esc(session()->get('name')) ?></h5>
                    <span class="badge bg-light text-primary px-3 py-1 rounded-pill fw-semibold">
                        <i class="bi bi-person-badge me-1"></i> Role: <?= esc(strtoupper(session()->get('role'))) ?>
                    </span>
                </div>
                <div class="card-body p-4">
                    <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">Informasi Akun Anda</h6>
                    <ul class="list-unstyled mb-4 small">
                        <li class="mb-2 text-muted"><strong class="text-dark">Email:</strong> <?= esc(session()->get('email')) ?></li>
                        <li class="mb-2 text-muted"><strong class="text-dark">Telepon:</strong> <?= esc($user['phone'] ?? session()->get('phone') ?? '-') ?></li>
                        <li class="mb-2 text-muted"><strong class="text-dark">Alamat:</strong> <?= esc($user['address'] ?? session()->get('address') ?? 'Belum diisi') ?></li>
                        <li class="mb-2 text-muted"><strong class="text-dark">Status Session:</strong> <span class="badge bg-success">Active (Terautentikasi)</span></li>
                    </ul>

                    <div class="d-grid gap-2">
                        <a href="<?= site_url('products') ?>" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-shop me-1"></i> Lihat Katalog Belanja
                        </a>
                        <a href="<?= site_url('logout') ?>" class="btn btn-danger btn-sm">
                            <i class="bi bi-box-arrow-right me-1"></i> Keluar (Logout)
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Content: Overview -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold text-dark mb-0">Selamat Datang di ShopEase!</h5>
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2">Customer Portal</span>
                </div>
                <p class="text-muted small">
                    Anda berhasil masuk melalui sistem autentikasi CodeIgniter 4 dengan pengamanan berbasis <strong>AuthFilter</strong>.
                    Halaman ini hanya dapat diakses setelah session login valid dibuat oleh Controller.
                </p>

                <div class="row g-3 mt-2">
                    <div class="col-md-4">
                        <div class="p-3 bg-light rounded-3 border text-center">
                            <i class="bi bi-cart-check text-primary fs-3"></i>
                            <div class="fw-bold fs-5 mt-2">0</div>
                            <div class="small text-muted">Pesanan Aktif</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 bg-light rounded-3 border text-center">
                            <i class="bi bi-heart text-danger fs-3"></i>
                            <div class="fw-bold fs-5 mt-2">3</div>
                            <div class="small text-muted">Wishlist Tersimpan</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 bg-light rounded-3 border text-center">
                            <i class="bi bi-shield-check text-success fs-3"></i>
                            <div class="fw-bold fs-5 mt-2">Aktif</div>
                            <div class="small text-muted">Keamanan Akun</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Rekomendasi Produk -->
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <h5 class="fw-bold text-dark mb-3">Rekomendasi Produk untuk Anda</h5>
                <div class="row g-3">
                    <?php if (!empty($products)): foreach($products as $prod): ?>
                        <div class="col-sm-6">
                            <div class="p-3 border rounded-3 h-100 d-flex flex-column justify-content-between">
                                <div>
                                    <div class="badge bg-secondary-subtle text-secondary small mb-2">Produk Pilihan</div>
                                    <h6 class="fw-bold mb-1"><?= esc($prod['name']) ?></h6>
                                    <div class="text-primary fw-bold mb-2">Rp <?= number_format($prod['price'], 0, ',', '.') ?></div>
                                    <p class="text-muted small mb-3"><?= esc(mb_strimwidth($prod['description'] ?? '', 0, 70, '...')) ?></p>
                                </div>
                                <a href="<?= site_url('products/' . $prod['slug']) ?>" class="btn btn-outline-primary btn-sm w-100">
                                    <i class="bi bi-eye me-1"></i> Lihat Detail
                                </a>
                            </div>
                        </div>
                    <?php endforeach; endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
