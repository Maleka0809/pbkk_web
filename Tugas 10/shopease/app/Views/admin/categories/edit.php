<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-dark mb-1"><i class="bi bi-pencil-square text-warning me-2"></i>Ubah Kategori</h3>
        <p class="text-muted small mb-0">Perbarui informasi kelompok kategori #<?= esc($category['id_category']) ?>.</p>
    </div>
    <a href="<?= base_url('admin/categories') ?>" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
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

<div class="card border-0 shadow-sm rounded-4 p-4 bg-white" style="max-width: 680px;">
    <form action="<?= base_url('admin/categories/update/' . $category['id_category']) ?>" method="POST">
        <?= csrf_field() ?>

        <div class="mb-3">
            <label class="form-label fw-bold small text-dark">Nama Kategori <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control" value="<?= esc($category['name']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold small text-dark">Slug URL</label>
            <input type="text" class="form-control bg-light" value="<?= esc($category['slug']) ?>" readonly>
            <div class="form-text small">Slug dibuat otomatis dari nama saat pertama kali disimpan.</div>
        </div>

        <div class="mb-4">
            <label class="form-label fw-bold small text-dark">Deskripsi Kategori</label>
            <textarea name="description" class="form-control" rows="3"><?= esc($category['description']) ?></textarea>
        </div>

        <div class="d-flex justify-content-end gap-2 pt-2 border-top">
            <a href="<?= base_url('admin/categories') ?>" class="btn btn-light px-4">Batal</a>
            <button type="submit" class="btn btn-warning px-4 fw-bold shadow-sm">
                <i class="bi bi-check-circle me-1"></i> Simpan Perubahan
            </button>
        </div>
    </form>
</div>
<?= $this->endSection() ?>
