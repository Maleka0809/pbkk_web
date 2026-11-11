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

    <!-- Header Operator -->
    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <div>
            <h4 class="fw-bold text-dark mb-1">
                <i class="bi bi-gear-wide-connected text-warning me-2"></i>Dashboard Operator Toko
            </h4>
            <div class="text-muted small">
                Logged in sebagai: <strong><?= esc(session()->get('name')) ?></strong> (<?= esc(session()->get('email')) ?>) | <span class="badge bg-warning text-dark">Role: OPERATOR</span>
            </div>
        </div>
        <div>
            <a href="<?= site_url('logout') ?>" class="btn btn-outline-danger btn-sm">
                <i class="bi bi-box-arrow-right me-1"></i> Logout
            </a>
        </div>
    </div>

    <!-- Stats row -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white">
                <div class="d-flex align-items-center">
                    <div class="rounded-3 bg-primary-subtle text-primary p-3 me-3 fs-3">
                        <i class="bi bi-box-seam"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Total Produk Terdaftar</div>
                        <div class="fs-4 fw-bold"><?= count($products ?? []) ?></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white">
                <div class="d-flex align-items-center">
                    <div class="rounded-3 bg-warning-subtle text-warning p-3 me-3 fs-3">
                        <i class="bi bi-tags"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Kategori Aktif</div>
                        <div class="fs-4 fw-bold"><?= count($categories ?? []) ?></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white">
                <div class="d-flex align-items-center">
                    <div class="rounded-3 bg-success-subtle text-success p-3 me-3 fs-3">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Otorisasi Filter</div>
                        <div class="fs-5 fw-bold text-success">auth:operator</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Product list managed by operator -->
    <div class="card border-0 shadow-sm rounded-4 p-4">
        <h5 class="fw-bold text-dark mb-3">Daftar Inventaris Produk ShopEase</h5>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Nama Produk</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($products)): foreach($products as $p): ?>
                        <tr>
                            <td>#<?= esc($p['id']) ?></td>
                            <td class="fw-semibold"><?= esc($p['name']) ?></td>
                            <td>Rp <?= number_format($p['price'], 0, ',', '.') ?></td>
                            <td><span class="badge bg-info-subtle text-info border"><?= esc($p['stock']) ?> unit</span></td>
                            <td><span class="badge bg-success">Tersedia</span></td>
                        </tr>
                    <?php endforeach; else: ?>
                        <tr><td colspan="5" class="text-center py-3 text-muted">Belum ada data produk.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

