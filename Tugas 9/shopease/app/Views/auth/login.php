<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow border-0 rounded-4 overflow-hidden">
                <div class="card-header bg-primary text-white text-center p-4">
                    <h3 class="fw-bold mb-1"><i class="bi bi-bag-check-fill me-2"></i>ShopEase</h3>
                    <p class="mb-0 text-white-50">Sistem Autentikasi Pengguna</p>
                </div>
                <div class="card-body p-4 p-md-5">

                    <!-- Flash Message Success -->
                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            <?= esc(session()->getFlashdata('success')) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <!-- Flash Message Error -->
                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <?= esc(session()->getFlashdata('error')) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <!-- Validation Errors -->
                    <?php if (isset($validation)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong><i class="bi bi-x-circle-fill me-1"></i> Perhatian:</strong>
                            <div class="mt-1 small">
                                <?= $validation->listErrors() ?>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <form action="<?= site_url('login') ?>" method="POST">
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Alamat Email</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-envelope"></i></span>
                                <input type="email" name="email" class="form-control"
                                       value="<?= old('email') ?>"
                                       placeholder="nama@email.com" required autofocus>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-lock"></i></span>
                                <input type="password" name="password"
                                       class="form-control"
                                       placeholder="Minimal 8 karakter" required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold rounded-3 shadow-sm">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Login Masuk
                        </button>
                    </form>

                    <!-- Akun Demo untuk Pengujian -->
                    <div class="mt-4 p-3 bg-light rounded-3 border">
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-shield-check text-primary me-2 fs-5"></i>
                            <span class="fw-bold small text-dark">Akun Demo Pengujian (HOL Skenario)</span>
                        </div>
                        <div class="small text-muted mb-2">Password semua akun demo: <code>password123</code></div>
                        <ul class="list-unstyled mb-0 small">
                            <li class="mb-1 d-flex justify-content-between">
                                <span><span class="badge bg-danger me-1">Admin</span> admin@shopease.com</span>
                                <span class="text-primary fw-semibold cursor-pointer" onclick="fillLogin('admin@shopease.com')">Pilih</span>
                            </li>
                            <li class="mb-1 d-flex justify-content-between">
                                <span><span class="badge bg-warning text-dark me-1">Operator</span> operator@shopease.com</span>
                                <span class="text-primary fw-semibold cursor-pointer" onclick="fillLogin('operator@shopease.com')">Pilih</span>
                            </li>
                            <li class="d-flex justify-content-between">
                                <span><span class="badge bg-success me-1">Customer</span> malekaghaniya0809@gmail.com</span>
                                <span class="text-primary fw-semibold cursor-pointer" onclick="fillLogin('malekaghaniya0809@gmail.com')">Pilih</span>
                            </li>
                        </ul>
                    </div>

                    <hr class="my-4">
                    <p class="text-center mb-0 text-muted small">
                        Belum memiliki akun?
                        <a href="<?= site_url('register') ?>" class="text-primary fw-semibold text-decoration-none">Registrasi Sekarang</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function fillLogin(email) {
    document.querySelector('input[name="email"]').value = email;
    document.querySelector('input[name="password"]').value = 'password123';
}
</script>
<?= $this->endSection() ?>
