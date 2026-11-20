<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-dark mb-1">Dashboard Product Management</h3>
        <p class="text-muted small mb-0">Statistik katalog, status inventaris barang, dan pemantauan kategori ShopEase.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= base_url('admin/products/create') ?>" class="btn btn-primary btn-sm rounded-pill px-3 shadow-sm">
            <i class="bi bi-plus-circle me-1"></i> Tambah Produk
        </a>
        <a href="<?= base_url('admin/categories/create') ?>" class="btn btn-outline-primary btn-sm rounded-pill px-3">
            <i class="bi bi-folder-plus me-1"></i> Tambah Kategori
        </a>
    </div>
</div>

<!-- Statistik Cards -->
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
            <div class="d-flex align-items-center">
                <div class="rounded-3 bg-primary-subtle text-primary p-3 me-3 fs-3">
                    <i class="bi bi-box-seam"></i>
                </div>
                <div>
                    <div class="text-muted small fw-semibold">Total Produk</div>
                    <div class="fs-4 fw-bold text-dark"><?= esc($totalProducts) ?></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
            <div class="d-flex align-items-center">
                <div class="rounded-3 bg-warning-subtle text-warning p-3 me-3 fs-3">
                    <i class="bi bi-tags"></i>
                </div>
                <div>
                    <div class="text-muted small fw-semibold">Total Kategori</div>
                    <div class="fs-4 fw-bold text-dark"><?= esc($totalCategories) ?></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
            <div class="d-flex align-items-center">
                <div class="rounded-3 bg-success-subtle text-success p-3 me-3 fs-3">
                    <i class="bi bi-check-circle"></i>
                </div>
                <div>
                    <div class="text-muted small fw-semibold">Produk Tersedia</div>
                    <div class="fs-4 fw-bold text-dark"><?= esc($availableProducts) ?></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
            <div class="d-flex align-items-center">
                <div class="rounded-3 bg-danger-subtle text-danger p-3 me-3 fs-3">
                    <i class="bi bi-exclamation-triangle"></i>
                </div>
                <div>
                    <div class="text-muted small fw-semibold">Stok Habis / Nonaktif</div>
                    <div class="fs-4 fw-bold text-dark"><?= esc($outOfStock) ?></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Tabel Produk Terbaru -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold text-dark mb-0"><i class="bi bi-grid-3x3-gap-fill text-primary me-2"></i>Produk Terbaru</h5>
                <a href="<?= base_url('admin/products') ?>" class="small text-decoration-none">Lihat Semua &rarr;</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr class="small text-muted">
                            <th>Produk</th>
                            <th>Kategori</th>
                            <th>Harga</th>
                            <th>Stok</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($recentProducts)): foreach($recentProducts as $p): ?>
                        <tr>
                            <td>
                                <div class="fw-semibold text-dark"><?= esc($p['name']) ?></div>
                                <small class="text-muted">Slug: <?= esc($p['slug']) ?></small>
                            </td>
                            <td><span class="badge bg-light text-dark border"><?= esc($p['category_name']) ?></span></td>
                            <td class="fw-bold text-primary">Rp <?= number_format($p['price'], 0, ',', '.') ?></td>
                            <td><span class="badge bg-info-subtle text-info"><?= esc($p['stock']) ?> pcs</span></td>
                            <td>
                                <?php if ($p['status'] === 'tersedia'): ?>
                                    <span class="badge bg-success">Tersedia</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Habis</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; else: ?>
                        <tr><td colspan="5" class="text-center py-3 text-muted">Belum ada data produk.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Alert Stok Rendah -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
            <h5 class="fw-bold text-dark mb-3"><i class="bi bi-bell-fill text-warning me-2"></i>Peringatan Stok Rendah</h5>
            <div class="list-group list-group-flush">
                <?php if (!empty($lowStockProducts)): foreach(array_slice($lowStockProducts, 0, 4) as $lp): ?>
                    <div class="list-group-item px-0 py-2 d-flex justify-content-between align-items-center border-bottom">
                        <div>
                            <div class="fw-semibold small text-dark"><?= esc($lp['name']) ?></div>
                            <small class="text-muted">ID #<?= esc($lp['id_product']) ?></small>
                        </div>
                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle">
                            Sisa <?= esc($lp['stock']) ?>
                        </span>
                    </div>
                <?php endforeach; else: ?>
                    <div class="text-muted small py-2">Semua produk memiliki stok memadai.</div>
                <?php endif; ?>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 p-4 bg-light">
            <h6 class="fw-bold text-dark mb-2"><i class="bi bi-info-circle text-primary me-2"></i>Fitur CRUD Tugas 10:</h6>
            <ul class="small text-muted ps-3 mb-0">
                <li class="mb-1"><strong>Create:</strong> Tambah kategori & produk lengkap dengan gambar.</li>
                <li class="mb-1"><strong>Read:</strong> Filter kategori, search nama, & badge stok.</li>
                <li class="mb-1"><strong>Update:</strong> Edit deskripsi, harga, kuantitas, & status.</li>
                <li><strong>Delete:</strong> Proteksi Foreign Key & pembersihan gambar fisik.</li>
            </ul>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
