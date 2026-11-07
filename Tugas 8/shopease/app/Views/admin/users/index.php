<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="card border-0 bg-white rounded-4 shadow-sm p-4">
    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <div>
            <h5 class="fw-bold text-dark mb-1"><i class="bi bi-people text-primary me-2"></i> Data Pengguna & Hak Akses</h5>
            <small class="text-muted">Daftar akun Administrator, Operator Toko, dan Pelanggan ShopEase</small>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle small">
            <thead class="table-light">
                <tr>
                    <th style="width: 50px;">Avatar</th>
                    <th>Nama Lengkap</th>
                    <th>Alamat Email</th>
                    <th>Nomor Telepon</th>
                    <th>Hak Akses (Role)</th>
                    <th>Tanggal Bergabung</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $u): ?>
                    <tr>
                        <td>
                            <img src="<?= base_url('uploads/avatars/' . ($u['avatar'] ?: 'default-avatar.svg')) ?>" alt="Avatar" width="36" height="36" class="rounded-circle border">
                        </td>
                        <td class="fw-bold text-dark"><?= esc($u['name']) ?></td>
                        <td><?= esc($u['email']) ?></td>
                        <td><?= esc($u['phone'] ?: '-') ?></td>
                        <td>
                            <?php
                                $rBadge = 'bg-secondary';
                                if ($u['role'] === 'admin') $rBadge = 'bg-danger';
                                elseif ($u['role'] === 'operator') $rBadge = 'bg-primary';
                                elseif ($u['role'] === 'customer') $rBadge = 'bg-success';
                            ?>
                            <span class="badge <?= $rBadge ?> text-uppercase"><?= $u['role'] ?></span>
                        </td>
                        <td class="text-muted"><?= date('d M Y, H:i', strtotime($u['created_at'])) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>

