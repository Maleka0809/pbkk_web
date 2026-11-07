<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white">
                <div class="text-center mb-4">
                    <div class="d-inline-flex align-items-center justify-content-center bg-primary-subtle text-primary rounded-circle mb-3" style="width: 60px; height: 60px;">
                        <i class="bi bi-person-lock fs-2"></i>
                    </div>
                    <h4 class="fw-bold text-dark">Masuk ke Akun Anda</h4>
                    <p class="text-muted small">Akses keranjang belanja, checkout, dan lacak pesanan.</p>
                </div>

                <form action="<?= base_url('login') ?>" method="POST">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Alamat Email</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted"><i class="bi bi-envelope"></i></span>
                            <input type="email" name="email" class="form-control" placeholder="nama@email.com" value="<?= old('email') ?>" required autofocus>
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <label class="form-label fw-semibold small mb-0">Kata Sandi (Password)</label>
                            <a href="#" class="small text-decoration-none">Lupa kata sandi?</a>
                        </div>
                        <div class="input-group mt-1">
                            <span class="input-group-text bg-light text-muted"><i class="bi bi-shield-lock"></i></span>
                            <input type="password" name="password" class="form-control" placeholder="Masukkan password Anda" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2 fw-bold rounded-pill mb-3">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Masuk Sekarang
                    </button>
                </form>

                <!-- Quick Login Demo Credentials -->
                <div class="bg-light p-3 rounded-3 mt-2 border">
                    <div class="fw-bold small text-dark mb-2"><i class="bi bi-info-circle text-primary me-1"></i> Akun Demo Siap Pakai:</div>
                    <div class="d-flex justify-content-between align-items-center border-bottom pb-1 mb-1 small">
                        <span><strong>Admin:</strong> admin@shopease.com</span>
                        <span class="badge bg-secondary">admin123</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center border-bottom pb-1 mb-1 small">
                        <span><strong>Operator:</strong> operator@shopease.com</span>
                        <span class="badge bg-secondary">operator123</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center small">
                        <span><strong>Customer:</strong> budi@gmail.com</span>
                        <span class="badge bg-secondary">user123</span>
                    </div>
                </div>

                <div class="text-center mt-4 pt-2 border-top">
                    <span class="text-muted small">Belum punya akun?</span>
                    <a href="<?= base_url('register') ?>" class="small fw-bold text-decoration-none ms-1">Daftar Akun Baru</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

