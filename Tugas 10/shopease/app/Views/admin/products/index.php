<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-dark mb-1"><i class="bi bi-box-seam text-primary me-2"></i>Katalog Produk</h3>
        <p class="text-muted small mb-0">Kelola inventaris barang, harga, stok, dan gambar produk toko online ShopEase.</p>
    </div>
    <a href="<?= base_url('admin/products/create') ?>" class="btn btn-primary rounded-pill px-3 shadow-sm">
        <i class="bi bi-plus-lg me-1"></i> Tambah Produk Baru
    </a>
</div>

<!-- Alert Messages -->
<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i><?= esc(session()->getFlashdata('success')) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i><?= esc(session()->getFlashdata('error')) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- Filter & Search Toolbar -->
<div class="card border-0 shadow-sm rounded-4 p-3 bg-white mb-4">
    <form action="<?= base_url('admin/products') ?>" method="GET" class="row g-2 align-items-center">
        <div class="col-md-5">
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                <input type="text" name="search" class="form-control bg-light border-start-0" placeholder="Cari nama produk atau deskripsi..." value="<?= esc($search ?? '') ?>">
            </div>
        </div>
        <div class="col-md-4">
            <select name="category" class="form-select bg-light">
                <option value="">-- Semua Kategori --</option>
                <?php foreach($categories as $cat): ?>
                    <option value="<?= $cat['id_category'] ?>" <?= ($selectedCategory == $cat['id_category']) ? 'selected' : '' ?>>
                        <?= esc($cat['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-secondary px-3 w-100"><i class="bi bi-filter me-1"></i> Filter</button>
            <?php if (!empty($search) || !empty($selectedCategory)): ?>
                <a href="<?= base_url('admin/products') ?>" class="btn btn-outline-secondary"><i class="bi bi-arrow-counterclockwise"></i></a>
            <?php endif; ?>
        </div>
    </form>
</div>

<!-- Tabel Daftar Produk -->
<div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr class="small text-muted text-uppercase">
                    <th style="width: 70px;">Gambar</th>
                    <th>Nama & Info Produk</th>
                    <th>Kategori</th>
                    <th>Harga Jual</th>
                    <th>Stok Fisik</th>
                    <th>Status</th>
                    <th style="width: 150px;" class="text-center">Aksi (CRUD)</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($products)): foreach($products as $p): ?>
                <tr>
                    <td>
                        <div class="rounded-3 bg-light border d-flex align-items-center justify-content-center overflow-hidden" style="width: 54px; height: 54px;">
                            <?php if (!empty($p['primary_image']) && file_exists(FCPATH . 'uploads/products/' . $p['primary_image'])): ?>
                                <img src="<?= base_url('uploads/products/' . $p['primary_image']) ?>" alt="<?= esc($p['name']) ?>" style="max-width: 100%; max-height: 100%; object-fit: cover;">
                            <?php else: ?>
                                <i class="bi bi-image text-muted fs-4"></i>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td>
                        <div class="fw-bold text-dark"><?= esc($p['name']) ?></div>
                        <div class="small text-muted text-truncate" style="max-width: 280px;"><?= esc($p['description'] ?? 'Tidak ada deskripsi.') ?></div>
                    </td>
                    <td>
                        <span class="badge bg-light text-dark border px-2 py-1">
                            <?= esc($p['category_name']) ?>
                        </span>
                    </td>
                    <td class="fw-bold text-primary">
                        Rp <?= number_format($p['price'], 0, ',', '.') ?>
                    </td>
                    <td>
                        <?php if ($p['stock'] <= 5): ?>
                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1">
                                <?= esc($p['stock']) ?> unit (Menipis)
                            </span>
                        <?php else: ?>
                            <span class="badge bg-info-subtle text-info border border-info-subtle px-2 py-1">
                                <?= esc($p['stock']) ?> unit
                            </span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($p['status'] === 'tersedia'): ?>
                            <span class="badge bg-success-subtle text-success border border-success-subtle"><i class="bi bi-check-circle me-1"></i>Tersedia</span>
                        <?php else: ?>
                            <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle"><i class="bi bi-x-circle me-1"></i>Tidak Tersedia</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-center">
                        <div class="btn-group btn-group-sm">
                            <a href="<?= base_url('admin/products/edit/' . $p['id_product']) ?>" class="btn btn-outline-primary" title="Ubah Produk (Update)">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <a href="<?= base_url('admin/products/delete/' . $p['id_product']) ?>" class="btn btn-outline-danger" title="Hapus Produk (Delete)" onclick="return confirm('Apakah Anda yakin ingin menghapus produk <?= esc($p['name']) ?>?')">
                                <i class="bi bi-trash"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr>
                    <td colspan="7" class="text-center py-5 text-muted">
                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                        Tidak ada data produk yang sesuai dengan kriteria pencarian.
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>
