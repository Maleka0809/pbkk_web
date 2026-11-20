<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="card border-0 bg-white rounded-4 shadow-sm p-4 mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
        <div>
            <h5 class="fw-bold text-dark mb-1"><i class="bi bi-graph-up-arrow text-danger me-2"></i> Laporan Rekap Penjualan</h5>
            <small class="text-muted">Analisis pendapatan dan rekap transaksi berdasarkan rentang tanggal</small>
        </div>
        <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill" onclick="window.print()">
            <i class="bi bi-printer me-1"></i> Cetak Laporan
        </button>
    </div>

    <!-- Filter Form -->
    <form action="<?= base_url('admin/reports') ?>" method="GET" class="row g-2 align-items-end mb-3">
        <div class="col-md-3">
            <label class="form-label small fw-semibold">Tanggal Mulai</label>
            <input type="date" name="start_date" class="form-control form-control-sm" value="<?= esc($startDate) ?>">
        </div>
        <div class="col-md-3">
            <label class="form-label small fw-semibold">Tanggal Akhir</label>
            <input type="date" name="end_date" class="form-control form-control-sm" value="<?= esc($endDate) ?>">
        </div>
        <div class="col-md-3">
            <label class="form-label small fw-semibold">Status Transaksi</label>
            <select name="status" class="form-select form-select-sm">
                <option value="">-- Semua Status --</option>
                <option value="paid" <?= $status === 'paid' ? 'selected' : '' ?>>Paid (Sudah Bayar)</option>
                <option value="processing" <?= $status === 'processing' ? 'selected' : '' ?>>Processing</option>
                <option value="shipped" <?= $status === 'shipped' ? 'selected' : '' ?>>Shipped</option>
                <option value="completed" <?= $status === 'completed' ? 'selected' : '' ?>>Completed</option>
            </select>
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-primary btn-sm rounded-pill w-100 fw-bold">
                <i class="bi bi-filter me-1"></i> Terapkan Filter
            </button>
        </div>
    </form>

    <!-- Total Revenue Box -->
    <div class="p-3 bg-primary-subtle text-primary rounded-3 d-flex justify-content-between align-items-center mb-4">
        <div>
            <small class="text-uppercase fw-bold" style="font-size: 11px;">Total Omset / Pendapatan Periode Ini:</small>
            <h4 class="fw-extrabold mb-0">Rp <?= number_format($totalRevenue, 0, ',', '.') ?></h4>
        </div>
        <div class="text-end">
            <span class="badge bg-primary px-3 py-2 rounded-pill"><?= count($orders) ?> Total Transaksi</span>
        </div>
    </div>

    <!-- Table -->
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle small">
            <thead class="table-light">
                <tr>
                    <th>No. Order</th>
                    <th>Waktu Pesan</th>
                    <th>Nama Pelanggan</th>
                    <th>Subtotal Produk</th>
                    <th>Ongkir</th>
                    <th>Grand Total</th>
                    <th class="text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($orders)): ?>
                    <tr><td colspan="7" class="text-center text-muted py-4">Tidak ada data transaksi pada periode tanggal ini.</td></tr>
                <?php else: ?>
                    <?php foreach ($orders as $ord): ?>
                        <tr>
                            <td class="fw-bold">#<?= esc($ord['order_number']) ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($ord['created_at'])) ?></td>
                            <td><?= esc($ord['customer_name']) ?></td>
                            <td>Rp <?= number_format($ord['total_amount'], 0, ',', '.') ?></td>
                            <td class="text-success">Rp <?= number_format($ord['shipping_cost'], 0, ',', '.') ?></td>
                            <td class="fw-bold text-primary">Rp <?= number_format($ord['grand_total'], 0, ',', '.') ?></td>
                            <td class="text-center">
                                <span class="badge bg-secondary text-uppercase" style="font-size: 10px;"><?= $ord['status'] ?></span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>

