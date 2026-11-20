<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-dark mb-1"><i class="bi bi-tags text-warning me-2"></i>Manajemen Kategori</h3>
        <p class="text-muted small mb-0">Kelola kelompok produk, taksonomi katalog, dan relasi 1-ke-N produk ShopEase.</p>
    </div>
    <a href="<?= base_url('admin/categories/create') ?>" class="btn btn-warning text-dark fw-bold rounded-pill px-3 shadow-sm">
        <i class="bi bi-plus-lg me-1"></i> Tambah Kategori Baru
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

<div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr class="small text-muted text-uppercase">
                    <th style="width: 50px;">ID</th>
                    <th>Nama Kategori</th>
                    <th>Slug URL</th>
                    <th>Deskripsi</th>
                    <th class="text-center">Jumlah Produk Terkait</th>
                    <th style="width: 150px;" class="text-center">Aksi (CRUD)</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($categories)): foreach($categories as $c): ?>
                <tr>
                    <td><span class="badge bg-light text-muted border">#<?= esc($c['id_category']) ?></span></td>
                    <td class="fw-bold text-dark"><?= esc($c['name']) ?></td>
                    <td><code><?= esc($c['slug']) ?></code></td>
                    <td class="small text-muted"><?= esc($c['description'] ?? '-') ?></td>
                    <td class="text-center">
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-1">
                            <?= esc($c['product_count']) ?> Produk
                        </span>
                    </td>
                    <td class="text-center">
                        <div class="btn-group btn-group-sm">
                            <a href="<?= base_url('admin/categories/edit/' . $c['id_category']) ?>" class="btn btn-outline-primary" title="Ubah Kategori">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="<?= base_url('admin/categories/delete/' . $c['id_category']) ?>" class="btn btn-outline-danger" title="Hapus Kategori" onclick="return confirm('Apakah Anda yakin ingin menghapus kategori <?= esc($c['name']) ?>?')">
                                <i class="bi bi-trash"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr><td colspan="6" class="text-center py-4 text-muted">Belum ada kategori yang dibuat.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>
