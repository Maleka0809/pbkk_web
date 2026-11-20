<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="card border-0 bg-white rounded-4 shadow-sm p-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 pb-2 border-bottom">
        <div>
            <h5 class="fw-bold text-dark mb-1"><i class="bi bi-receipt text-success me-2"></i> Manajemen Pesanan Masuk</h5>
            <small class="text-muted">Pantau seluruh order, status pembayaran, dan pengiriman barang</small>
        </div>
        <div class="mt-2 mt-md-0 d-flex gap-2">
            <a href="<?= base_url('admin/orders') ?>" class="btn btn-sm <?= empty($selectedStatus) ? 'btn-primary' : 'btn-light border' ?>">Semua</a>
            <a href="<?= base_url('admin/orders?status=pending') ?>" class="btn btn-sm <?= $selectedStatus === 'pending' ? 'btn-warning text-dark' : 'btn-light border' ?>">Pending</a>
            <a href="<?= base_url('admin/orders?status=processing') ?>" class="btn btn-sm <?= $selectedStatus === 'processing' ? 'btn-info text-white' : 'btn-light border' ?>">Processing</a>
            <a href="<?= base_url('admin/orders?status=shipped') ?>" class="btn btn-sm <?= $selectedStatus === 'shipped' ? 'btn-dark' : 'btn-light border' ?>">Shipped</a>
            <a href="<?= base_url('admin/orders?status=completed') ?>" class="btn btn-sm <?= $selectedStatus === 'completed' ? 'btn-success' : 'btn-light border' ?>">Completed</a>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle small">
            <thead class="table-light">
                <tr>
                    <th>No. Order</th>
                    <th>Tanggal Pesan</th>
                    <th>Nama Pelanggan</th>
                    <th>Kota Tujuan</th>
                    <th>Kurir & Resi</th>
                    <th>Grand Total</th>
                    <th class="text-center">Status Pesanan</th>
                    <th class="text-center" style="width: 100px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($orders)): ?>
                    <tr><td colspan="8" class="text-center text-muted py-4">Belum ada pesanan dengan status tersebut.</td></tr>
                <?php else: ?>
                    <?php foreach ($orders as $ord): ?>
                        <tr>
                            <td class="fw-bold">#<?= esc($ord['order_number']) ?></td>
                            <td class="text-muted"><?= date('d/m/Y H:i', strtotime($ord['created_at'])) ?></td>
                            <td>
                                <div class="fw-bold text-dark"><?= esc($ord['customer_name']) ?></div>
                                <small class="text-muted"><?= esc($ord['customer_email']) ?></small>
                            </td>
                            <td><?= esc($ord['city'] ?? '-') ?></td>
                            <td>
                                <div><?= esc($ord['courier'] ?? 'JNE') ?></div>
                                <small class="text-muted font-monospace"><?= esc($ord['tracking_number'] ?: 'Belum ada resi') ?></small>
                            </td>
                            <td class="fw-bold text-primary">Rp <?= number_format($ord['grand_total'], 0, ',', '.') ?></td>
                            <td class="text-center">
                                <?php
                                    $badge = 'bg-secondary';
                                    if ($ord['status'] === 'pending') $badge = 'bg-warning text-dark';
                                    elseif ($ord['status'] === 'paid') $badge = 'bg-info text-dark';
                                    elseif ($ord['status'] === 'processing') $badge = 'bg-primary';
                                    elseif ($ord['status'] === 'shipped') $badge = 'bg-dark';
                                    elseif ($ord['status'] === 'completed') $badge = 'bg-success';
                                    elseif ($ord['status'] === 'cancelled') $badge = 'bg-danger';
                                ?>
                                <span class="badge <?= $badge ?> text-uppercase px-2 py-1" style="font-size: 10px;">
                                    <?= $ord['status'] ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="<?= base_url('admin/orders/' . $ord['id']) ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3">
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
<?= $this->endSection() ?>

