<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container">

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 pb-2 border-bottom">
        <div>
            <h3 class="fw-bold mb-1"><i class="bi bi-receipt-cutoff text-primary me-2"></i> Riwayat Pesanan Saya</h3>
            <p class="text-muted small mb-0">Pantau proses pembayaran, pengemasan, dan nomor resi pengiriman barang Anda.</p>
        </div>
        <div class="mt-2 mt-md-0">
            <a href="<?= base_url('products') ?>" class="btn btn-primary btn-sm rounded-pill px-3">
                <i class="bi bi-bag-plus me-1"></i> Belanja Lagi
            </a>
        </div>
    </div>

    <!-- Filter Status Tabs -->
    <div class="d-flex flex-wrap gap-2 mb-4">
        <a href="<?= base_url('orders') ?>" class="btn btn-sm rounded-pill <?= empty($selectedStatus) ? 'btn-primary' : 'btn-outline-secondary' ?>">
            Semua Pesanan
        </a>
        <a href="<?= base_url('orders?status=pending') ?>" class="btn btn-sm rounded-pill <?= ($selectedStatus === 'pending') ? 'btn-warning text-dark' : 'btn-outline-secondary' ?>">
            Menunggu Pembayaran
        </a>
        <a href="<?= base_url('orders?status=processing') ?>" class="btn btn-sm rounded-pill <?= ($selectedStatus === 'processing') ? 'btn-info text-white' : 'btn-outline-secondary' ?>">
            Diproses Penjual
        </a>
        <a href="<?= base_url('orders?status=shipped') ?>" class="btn btn-sm rounded-pill <?= ($selectedStatus === 'shipped') ? 'btn-primary' : 'btn-outline-secondary' ?>">
            Sedang Dikirim
        </a>
        <a href="<?= base_url('orders?status=completed') ?>" class="btn btn-sm rounded-pill <?= ($selectedStatus === 'completed') ? 'btn-success' : 'btn-outline-secondary' ?>">
            Selesai
        </a>
    </div>

    <?php if (empty($orders)): ?>
        <div class="card border-0 bg-white rounded-4 p-5 text-center shadow-sm">
            <i class="bi bi-inbox text-muted fs-1 mb-3"></i>
            <h5 class="fw-bold text-dark">Belum Ada Transaksi Pesanan</h5>
            <p class="text-muted small mb-3">Anda belum memiliki riwayat pesanan dengan status tersebut.</p>
            <div>
                <a href="<?= base_url('products') ?>" class="btn btn-primary rounded-pill px-4">
                    Jelajahi Produk ShopEase
                </a>
            </div>
        </div>
    <?php else: ?>
        <div class="row g-3">
            <?php foreach ($orders as $order): ?>
                <div class="col-12">
                    <div class="card border bg-white rounded-4 p-3 p-md-4 shadow-sm">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center border-bottom pb-3 mb-3">
                            <div>
                                <span class="fw-bold text-dark fs-6 me-2">#<?= esc($order['order_number']) ?></span>
                                <small class="text-muted">&bull; Tanggal: <?= date('d M Y, H:i', strtotime($order['created_at'])) ?> WIB</small>
                            </div>
                            <div class="mt-2 mt-md-0 d-flex gap-2 align-items-center">
                                <?php
                                    $badgeClass = 'bg-secondary';
                                    $statusLabel = strtoupper($order['status']);
                                    if ($order['status'] === 'pending') { $badgeClass = 'bg-warning text-dark'; $statusLabel = 'Menunggu Pembayaran'; }
                                    elseif ($order['status'] === 'paid') { $badgeClass = 'bg-info text-dark'; $statusLabel = 'Sudah Dibayar'; }
                                    elseif ($order['status'] === 'processing') { $badgeClass = 'bg-primary'; $statusLabel = 'Sedang Diproses'; }
                                    elseif ($order['status'] === 'shipped') { $badgeClass = 'bg-indigo text-white'; $statusLabel = 'Sedang Dikirim'; }
                                    elseif ($order['status'] === 'completed') { $badgeClass = 'bg-success'; $statusLabel = 'Selesai'; }
                                    elseif ($order['status'] === 'cancelled') { $badgeClass = 'bg-danger'; $statusLabel = 'Dibatalkan'; }
                                ?>
                                <span class="badge <?= $badgeClass ?> px-3 py-2 rounded-pill small">
                                    <?= $statusLabel ?>
                                </span>
                            </div>
                        </div>

                        <div class="row align-items-center">
                            <div class="col-md-7 mb-3 mb-md-0">
                                <div class="small text-muted mb-1">
                                    <i class="bi bi-geo-alt me-1"></i> Dikirim ke: <strong><?= esc($order['recipient_name'] ?? $order['customer_name']) ?></strong> (<?= esc($order['city'] ?? 'Alamat Terdaftar') ?>)
                                </div>
                                <div class="small text-muted">
                                    <i class="bi bi-truck me-1"></i> Kurir: <?= esc($order['courier'] ?? 'JNE') ?> &bull; No. Resi: <strong><?= esc($order['tracking_number'] ?: 'Belum diinput') ?></strong>
                                </div>
                            </div>
                            <div class="col-md-5 text-md-end">
                                <small class="text-muted d-block mb-1">Total Pembayaran:</small>
                                <span class="fw-extrabold fs-5 text-primary d-block mb-2">
                                    Rp <?= number_format($order['grand_total'], 0, ',', '.') ?>
                                </span>
                                <a href="<?= base_url('orders/' . $order['id']) ?>" class="btn btn-outline-primary btn-sm rounded-pill px-4 fw-semibold">
                                    Lihat Detail & Lacak <i class="bi bi-chevron-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>
<?= $this->endSection() ?>

