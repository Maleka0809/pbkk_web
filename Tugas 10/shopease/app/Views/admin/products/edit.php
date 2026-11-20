<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-dark mb-1"><i class="bi bi-pencil-square text-primary me-2"></i>Ubah Data Produk</h3>
        <p class="text-muted small mb-0">Perbarui spesifikasi, harga, stok, dan galeri gambar produk #<?= esc($product['id_product']) ?>.</p>
    </div>
    <a href="<?= base_url('admin/products') ?>" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
    </a>
</div>

<!-- Errors Display -->
<?php if (!empty($validation)): ?>
    <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm" role="alert">
        <strong><i class="bi bi-exclamation-triangle-fill me-2"></i>Validasi Gagal:</strong>
        <ul class="mb-0 mt-2 small">
            <?php foreach($validation as $err): ?>
                <li><?= esc($err) ?></li>
            <?php endforeach; ?>
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="row">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
            <form action="<?= base_url('admin/products/update/' . $product['id_product']) ?>" method="POST" enctype="multipart/form-data">
                <?= csrf_field() ?>

                <div class="mb-3">
                    <label class="form-label fw-bold small text-dark">Kategori Produk <span class="text-danger">*</span></label>
                    <select name="id_category" class="form-select" required>
                        <?php foreach($categories as $cat): ?>
                            <option value="<?= $cat['id_category'] ?>" <?= ($product['id_category'] == $cat['id_category']) ? 'selected' : '' ?>>
                                <?= esc($cat['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold small text-dark">Nama Produk <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" value="<?= esc($product['name']) ?>" required>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-dark">Harga Satuan (Rp) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">Rp</span>
                            <input type="number" name="price" step="0.01" class="form-control" value="<?= esc($product['price']) ?>" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold small text-dark">Stok Fisik <span class="text-danger">*</span></label>
                        <input type="number" name="stock" class="form-control" value="<?= esc($product['stock']) ?>" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold small text-dark">Status Ketersediaan <span class="text-danger">*</span></label>
                        <select name="status" class="form-select" required>
                            <option value="tersedia" <?= ($product['status'] === 'tersedia') ? 'selected' : '' ?>>Tersedia</option>
                            <option value="tidak_tersedia" <?= ($product['status'] === 'tidak_tersedia') ? 'selected' : '' ?>>Tidak Tersedia</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold small text-dark">Deskripsi Produk</label>
                    <textarea name="description" class="form-control" rows="4"><?= esc($product['description']) ?></textarea>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold small text-dark"><i class="bi bi-cloud-arrow-up text-primary me-1"></i>Tambah Gambar Tambahan (Opsional)</label>
                    <input type="file" name="images[]" class="form-control" multiple accept="image/jpeg,image/png,image/webp,image/svg+xml">
                </div>

                <div class="d-flex justify-content-end gap-2 pt-2 border-top">
                    <a href="<?= base_url('admin/products') ?>" class="btn btn-light px-4">Batal</a>
                    <button type="submit" class="btn btn-primary px-4 fw-semibold shadow-sm">
                        <i class="bi bi-check-circle me-1"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Galeri Gambar Terunggah -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
            <h6 class="fw-bold text-dark mb-3"><i class="bi bi-images text-primary me-2"></i>Galeri Gambar Produk</h6>
            <div class="row g-2">
                <?php if (!empty($images)): foreach($images as $img): ?>
                    <div class="col-6">
                        <div class="card border rounded-3 overflow-hidden text-center p-1">
                            <div class="d-flex align-items-center justify-content-center bg-light" style="height: 100px;">
                                <img src="<?= base_url('uploads/products/' . $img['image']) ?>" alt="Foto" style="max-height: 90px; max-width: 100%; object-fit: contain;">
                            </div>
                            <div class="p-1">
                                <?php if ($img['is_primary']): ?>
                                    <span class="badge bg-success w-100 mb-1" style="font-size: 10px;">Utama</span>
                                <?php else: ?>
                                    <a href="<?= base_url('admin/products/set-primary/' . $product['id_product'] . '/' . $img['id_image']) ?>" class="btn btn-outline-secondary btn-sm w-100 mb-1" style="font-size: 10px;">Set Utama</a>
                                <?php endif; ?>
                                <a href="<?= base_url('admin/products/delete-image/' . $img['id_image']) ?>" class="btn btn-outline-danger btn-sm w-100" style="font-size: 10px;" onclick="return confirm('Hapus gambar ini?')">Hapus</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; else: ?>
                    <div class="text-muted small py-3 text-center">Belum ada gambar yang diupload.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
