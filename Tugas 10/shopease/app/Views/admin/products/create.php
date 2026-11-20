<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-dark mb-1"><i class="bi bi-plus-circle text-primary me-2"></i>Tambah Produk Baru</h3>
        <p class="text-muted small mb-0">Isi formulir di bawah ini untuk menambahkan entitas barang baru ke katalog ShopEase.</p>
    </div>
    <a href="<?= base_url('admin/products') ?>" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
    </a>
</div>

<!-- Errors Display -->
<?php if (!empty($validation)): ?>
    <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm" role="alert">
        <strong><i class="bi bi-exclamation-triangle-fill me-2"></i>Validasi Input Gagal:</strong>
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
            <form action="<?= base_url('admin/products/store') ?>" method="POST" enctype="multipart/form-data">
                <?= csrf_field() ?>

                <div class="mb-3">
                    <label class="form-label fw-bold small text-dark">Kategori Produk <span class="text-danger">*</span></label>
                    <select name="id_category" class="form-select" required>
                        <option value="">-- Pilih Kategori --</option>
                        <?php foreach($categories as $cat): ?>
                            <option value="<?= $cat['id_category'] ?>" <?= (old('id_category') == $cat['id_category']) ? 'selected' : '' ?>>
                                <?= esc($cat['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="form-text small">Kategori mengelompokkan produk di etalase toko.</div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold small text-dark">Nama Produk <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" placeholder="Contoh: Mechanical Keyboard RGB Wireless" value="<?= old('name') ?>" required>
                    <div class="form-text small">Minimal 3 karakter, maksimal 150 karakter.</div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-dark">Harga Satuan (Rp) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">Rp</span>
                            <input type="number" name="price" step="0.01" class="form-control" placeholder="350000" value="<?= old('price') ?>" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold small text-dark">Stok Fisik <span class="text-danger">*</span></label>
                        <input type="number" name="stock" class="form-control" placeholder="10" value="<?= old('stock', 0) ?>" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold small text-dark">Status Ketersediaan <span class="text-danger">*</span></label>
                        <select name="status" class="form-select" required>
                            <option value="tersedia" <?= (old('status') === 'tersedia') ? 'selected' : '' ?>>Tersedia</option>
                            <option value="tidak_tersedia" <?= (old('status') === 'tidak_tersedia') ? 'selected' : '' ?>>Tidak Tersedia</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold small text-dark">Deskripsi & Spesifikasi Produk</label>
                    <textarea name="description" class="form-control" rows="4" placeholder="Jelaskan fitur, bahan, dimensi, dan garansi produk..."><?= old('description') ?></textarea>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold small text-dark"><i class="bi bi-images text-primary me-1"></i>Upload Gambar Produk (Multi-Upload)</label>
                    <input type="file" name="images[]" class="form-control" multiple accept="image/jpeg,image/png,image/webp,image/svg+xml">
                    <div class="form-text small">Format diperbolehkan: JPG, PNG, WEBP, SVG (Maks. 3MB per berkas). Gambar pertama otomatis dijadikan gambar utama.</div>
                </div>

                <div class="d-flex justify-content-end gap-2 pt-2 border-top">
                    <a href="<?= base_url('admin/products') ?>" class="btn btn-light px-4">Batal</a>
                    <button type="submit" class="btn btn-primary px-4 fw-semibold shadow-sm">
                        <i class="bi bi-save me-1"></i> Simpan Produk
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Informasi Panduan HOL -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-3">
            <h6 class="fw-bold text-dark mb-2"><i class="bi bi-shield-check text-success me-2"></i>Standar Keamanan HOL:</h6>
            <p class="small text-muted mb-2">Operasi <code>Create</code> ini mengimplementasikan validasi server-side:</p>
            <ul class="small text-muted ps-3 mb-0">
                <li><code>id_category</code> harus ada di database (Foreign Key).</li>
                <li>Nama produk divalidasi keunikan slug-nya.</li>
                <li>File upload diverifikasi tipe MIME dan ukurannya secara aman.</li>
                <li>Nama file di-randomize untuk mencegah <em>direct script execution</em>.</li>
            </ul>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
