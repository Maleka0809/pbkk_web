<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">
            <div class="card shadow border-0 rounded-4 overflow-hidden">
                <div class="card-header bg-primary text-white text-center p-4">
                    <h3 class="fw-bold mb-1"><i class="bi bi-person-plus-fill me-2"></i>ShopEase</h3>
                    <p class="mb-0 text-white-50">Registrasi Akun Baru (Customer)</p>
                </div>
                <div class="card-body p-4 p-md-5">

                    <!-- Validation Errors -->
                    <?php if (isset($validation)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong><i class="bi bi-exclamation-triangle-fill me-1"></i> Validasi Gagal:</strong>
                            <div class="mt-1 small">
                                <?= $validation->listErrors() ?>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <form action="<?= site_url('register') ?>" method="POST">
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-person"></i></span>
                                <input type="text" name="name" class="form-control"
                                       value="<?= old('name') ?>"
                                       placeholder="Masukkan nama lengkap Anda" required autofocus>
                            </div>
                            <div class="form-text small">Minimal 3 karakter, maksimal 100 karakter.</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Alamat Email <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-envelope"></i></span>
                                <input type="email" name="email" class="form-control"
                                       value="<?= old('email') ?>"
                                       placeholder="nama@email.com" required>
                            </div>
                            <div class="form-text small">Email harus unik dan belum pernah didaftarkan.</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nomor Telepon / WhatsApp</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-telephone"></i></span>
                                <input type="text" name="phone" class="form-control"
                                       value="<?= old('phone') ?>"
                                       placeholder="08xxxxxxxxxx">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Alamat Tempat Tinggal</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-geo-alt"></i></span>
                                <textarea name="address" class="form-control"
                                          rows="2" placeholder="Alamat pengiriman lengkap..."><?= old('address') ?></textarea>
                            </div>
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-lock"></i></span>
                                    <input type="password" name="password" class="form-control"
                                           placeholder="Minimal 8 karakter" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Konfirmasi Password <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-lock-fill"></i></span>
                                    <input type="password" name="password_confirm" class="form-control"
                                           placeholder="Ulangi password" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-text small mb-4">Password akan disimpan secara aman dengan hashing <code>PASSWORD_DEFAULT (Bcrypt)</code>.</div>

                        <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold rounded-3 shadow-sm">
                            <i class="bi bi-check2-circle me-1"></i> Daftar Sekarang
                        </button>
                    </form>

                    <div class="alert alert-info mt-4 small mb-0 d-flex align-items-center">
                        <i class="bi bi-info-circle-fill fs-5 me-2 flex-shrink-0"></i>
                        <div>
                            <strong>Catatan Otorisasi:</strong> Role akun yang didaftarkan secara publik otomatis bertipe <code>customer</code>. Hak akses <code>admin</code> atau <code>operator</code> hanya dapat diberikan melalui konsol basis data.
                        </div>
                    </div>

                    <hr class="my-4">
                    <p class="text-center mb-0 text-muted small">
                        Sudah memiliki akun?
                        <a href="<?= site_url('login') ?>" class="text-primary fw-semibold text-decoration-none">Login di sini</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
