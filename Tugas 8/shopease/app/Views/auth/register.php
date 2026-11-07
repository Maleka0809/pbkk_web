<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white">
                <div class="text-center mb-4">
                    <div class="d-inline-flex align-items-center justify-content-center bg-success-subtle text-success rounded-circle mb-3" style="width: 60px; height: 60px;">
                        <i class="bi bi-person-plus-fill fs-2"></i>
                    </div>
                    <h4 class="fw-bold text-dark">Daftar Akun ShopEase</h4>
                    <p class="text-muted small">Buat akun untuk mulai berbelanja gadget dan perlengkapan komputer.</p>
                </div>

                <form action="<?= base_url('register') ?>" method="POST">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Nama Lengkap</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted"><i class="bi bi-person"></i></span>
                            <input type="text" name="name" class="form-control" placeholder="Contoh: Budi Santoso" value="<?= old('name') ?>" required>
                        </div>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Alamat Email</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted"><i class="bi bi-envelope"></i></span>
                                <input type="email" name="email" class="form-control" placeholder="nama@email.com" value="<?= old('email') ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Nomor WhatsApp / HP</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted"><i class="bi bi-telephone"></i></span>
                                <input type="tel" name="phone" class="form-control" placeholder="081234567890" value="<?= old('phone') ?>" required>
                            </div>
                        </div>
                    </div>

                    <div class="row g-2 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Kata Sandi (Password)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted"><i class="bi bi-shield-lock"></i></span>
                                <input type="password" name="password" class="form-control" placeholder="Min. 6 karakter" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Konfirmasi Kata Sandi</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted"><i class="bi bi-check2-circle"></i></span>
                                <input type="password" name="password_confirm" class="form-control" placeholder="Ulangi kata sandi" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-check mb-4 small">
                        <input class="form-check-input" type="checkbox" id="termsCheck" required checked>
                        <label class="form-check-label text-muted" for="termsCheck">
                            Saya menyetujui <a href="#" class="text-decoration-none">Syarat & Ketentuan</a> serta <a href="#" class="text-decoration-none">Kebijakan Privasi</a> ShopEase.
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2 fw-bold rounded-pill mb-3">
                        <i class="bi bi-person-check me-1"></i> Selesaikan Registrasi
                    </button>
                </form>

                <div class="text-center mt-3 pt-2 border-top">
                    <span class="text-muted small">Sudah memiliki akun?</span>
                    <a href="<?= base_url('login') ?>" class="small fw-bold text-decoration-none ms-1">Masuk ke Sini</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

