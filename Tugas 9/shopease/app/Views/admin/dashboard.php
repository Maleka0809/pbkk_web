<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="row g-3 mb-4">
    <!-- Stat 1: Total Revenue -->
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 bg-white rounded-3 shadow-sm p-3">
            <div class="d-flex align-items-center">
                <div class="p-3 bg-success-subtle text-success rounded-3 me-3">
                    <i class="bi bi-wallet2 fs-3"></i>
                </div>
                <div>
                    <small class="text-muted text-uppercase fw-semibold" style="font-size: 11px;">Total Pendapatan</small>
                    <h5 class="fw-extrabold text-dark mb-0">Rp <?= number_format($revenue, 0, ',', '.') ?></h5>
                </div>
            </div>
        </div>
    </div>

    <!-- Stat 2: Total Orders -->
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 bg-white rounded-3 shadow-sm p-3">
            <div class="d-flex align-items-center">
                <div class="p-3 bg-primary-subtle text-primary rounded-3 me-3">
                    <i class="bi bi-cart-check fs-3"></i>
                </div>
                <div>
                    <small class="text-muted text-uppercase fw-semibold" style="font-size: 11px;">Total Transaksi</small>
                    <h5 class="fw-extrabold text-dark mb-0"><?= $totalOrders ?> Pesanan</h5>
                </div>
            </div>
        </div>
    </div>

    <!-- Stat 3: Total Products -->
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 bg-white rounded-3 shadow-sm p-3">
            <div class="d-flex align-items-center">
                <div class="p-3 bg-warning-subtle text-warning rounded-3 me-3">
                    <i class="bi bi-box-seam fs-3"></i>
                </div>
                <div>
                    <small class="text-muted text-uppercase fw-semibold" style="font-size: 11px;">Katalog Produk</small>
                    <h5 class="fw-extrabold text-dark mb-0"><?= $totalProducts ?> Item</h5>
                </div>
            </div>
        </div>
    </div>

    <!-- Stat 4: Total Customers -->
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 bg-white rounded-3 shadow-sm p-3">
            <div class="d-flex align-items-center">
                <div class="p-3 bg-info-subtle text-info rounded-3 me-3">
                    <i class="bi bi-people fs-3"></i>
                </div>
                <div>
                    <small class="text-muted text-uppercase fw-semibold" style="font-size: 11px;">Pelanggan Terdaftar</small>
                    <h5 class="fw-extrabold text-dark mb-0"><?= $totalCustomers ?> Akun</h5>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Left: Recent Orders -->
    <div class="col-lg-8">
        <div class="card border-0 bg-white rounded-4 shadow-sm p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold text-dark mb-0"><i class="bi bi-clock-history text-primary me-2"></i> 5 Transaksi Pesanan Terbaru</h6>
                <a href="<?= base_url('admin/orders') ?>" class="btn btn-sm btn-outline-primary rounded-pill">
                    Lihat Semua
                </a>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle small">
                    <thead class="table-light">
                        <tr>
                            <th>No. Order</th>
                            <th>Pelanggan</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($recentOrders)): ?>
                            <tr><td colspan="5" class="text-center text-muted py-3">Belum ada pesanan terbaru.</td></tr>
                        <?php else: ?>
                            <?php foreach ($recentOrders as $ro): ?>
                                <tr>
                                    <td class="fw-bold">#<?= esc($ro['order_number']) ?></td>
                                    <td><?= esc($ro['customer_name']) ?></td>
                                    <td class="fw-bold text-primary">Rp <?= number_format($ro['grand_total'], 0, ',', '.') ?></td>
                                    <td>
                                        <span class="badge bg-secondary text-uppercase" style="font-size: 10px;"><?= $ro['status'] ?></span>
                                    </td>
                                    <td>
                                        <a href="<?= base_url('admin/orders/' . $ro['id']) ?>" class="btn btn-sm btn-light border py-0 px-2">
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Right: Pending Payments & Low Stock -->
    <div class="col-lg-4">
        <!-- Pending Payments -->
        <div class="card border-0 bg-white rounded-4 shadow-sm p-4 mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold text-dark mb-0"><i class="bi bi-hourglass-split text-warning me-1"></i> Pembayaran Pending</h6>
                <span class="badge bg-warning text-dark"><?= count($pendingPayments) ?></span>
            </div>

            <?php if (empty($pendingPayments)): ?>
                <small class="text-muted">Tidak ada pembayaran menunggu verifikasi.</small>
            <?php else: ?>
                <div class="list-group list-group-flush small">
                    <?php foreach ($pendingPayments as $p): ?>
                        <div class="list-group-item px-0 py-2 d-flex justify-content-between align-items-center">
                            <div>
                                <div class="fw-bold"><?= esc($p['customer_name']) ?></div>
                                <small class="text-muted"><?= esc($p['order_number']) ?> &bull; <?= esc($p['bank_name']) ?></small>
                            </div>
                            <a href="<?= base_url('admin/payments') ?>" class="btn btn-sm btn-outline-success py-1 px-2" style="font-size: 11px;">
                                Review
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Low Stock Alerts -->
        <div class="card border-0 bg-white rounded-4 shadow-sm p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold text-dark mb-0"><i class="bi bi-exclamation-triangle-fill text-danger me-1"></i> Stok Menipis (&lt; 10)</h6>
                <span class="badge bg-danger"><?= count($lowStockProducts) ?></span>
            </div>

            <?php if (empty($lowStockProducts)): ?>
                <small class="text-muted">Semua stok produk dalam batas aman.</small>
            <?php else: ?>
                <div class="list-group list-group-flush small">
                    <?php foreach ($lowStockProducts as $lp): ?>
                        <div class="list-group-item px-0 py-2 d-flex justify-content-between align-items-center">
                            <span class="text-truncate me-2" style="max-width: 170px;" title="<?= esc($lp['name']) ?>">
                                <?= esc($lp['name']) ?>
                            </span>
                            <span class="badge bg-danger-subtle text-danger fw-bold">Sisa: <?= $lp['stock'] ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

