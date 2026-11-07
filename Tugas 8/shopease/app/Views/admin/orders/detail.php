<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="mb-3">
    <a href="<?= base_url('admin/orders') ?>" class="btn btn-outline-secondary btn-sm rounded-pill">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Pesanan
    </a>
</div>

<div class="card border-0 bg-white rounded-4 shadow-sm p-4 mb-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
        <div>
            <span class="badge bg-primary text-uppercase mb-2">Detail Transaksi Toko</span>
            <h4 class="fw-bold mb-1">Pesanan #<?= esc($order['order_number']) ?></h4>
            <small class="text-muted">Tanggal: <?= date('d F Y, H:i:s', strtotime($order['created_at'])) ?> WIB</small>
        </div>
        <div class="mt-3 mt-md-0 d-flex gap-2 align-items-center">
            <!-- Modal Ubah Status Form -->
            <form action="<?= base_url('admin/orders/' . $order['id'] . '/status') ?>" method="POST" class="d-flex gap-2">
                <?= csrf_field() ?>
                <select name="status" class="form-select form-select-sm" style="width: 160px;">
                    <option value="pending" <?= $order['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="paid" <?= $order['status'] === 'paid' ? 'selected' : '' ?>>Paid</option>
                    <option value="processing" <?= $order['status'] === 'processing' ? 'selected' : '' ?>>Processing</option>
                    <option value="shipped" <?= $order['status'] === 'shipped' ? 'selected' : '' ?>>Shipped</option>
                    <option value="completed" <?= $order['status'] === 'completed' ? 'selected' : '' ?>>Completed</option>
                    <option value="cancelled" <?= $order['status'] === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                </select>
                <button type="submit" class="btn btn-sm btn-primary rounded-pill fw-bold">Update Status</button>
            </form>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Left: Items -->
    <div class="col-lg-7">
        <div class="card border-0 bg-white rounded-4 shadow-sm p-4 mb-4">
            <h6 class="fw-bold text-dark mb-3">Daftar Item Barang</h6>
            <div class="table-responsive">
                <table class="table align-middle small">
                    <thead class="table-light">
                        <tr>
                            <th>Item</th>
                            <th>Harga Satuan</th>
                            <th class="text-center">Kuantitas</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $it): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="<?= base_url('uploads/products/' . ($it['image'] ?: 'default-product.svg')) ?>" width="40" height="40" class="rounded border p-1 me-2 bg-light" style="object-fit: contain;">
                                        <span class="fw-bold"><?= esc($it['product_name']) ?></span>
                                    </div>
                                </td>
                                <td>Rp <?= number_format($it['price'], 0, ',', '.') ?></td>
                                <td class="text-center"><?= $it['quantity'] ?></td>
                                <td class="text-end fw-bold">Rp <?= number_format($it['subtotal'], 0, ',', '.') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3" class="text-end">Subtotal:</th>
                            <th class="text-end">Rp <?= number_format($order['total_amount'], 0, ',', '.') ?></th>
                        </tr>
                        <tr>
                            <th colspan="3" class="text-end">Ongkos Kirim:</th>
                            <th class="text-end text-success">Rp <?= number_format($order['shipping_cost'], 0, ',', '.') ?></th>
                        </tr>
                        <tr class="table-light">
                            <th colspan="3" class="text-end fs-6">Grand Total:</th>
                            <th class="text-end fs-6 text-primary fw-extrabold">Rp <?= number_format($order['grand_total'], 0, ',', '.') ?></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <!-- Right: Customer, Shipping & Payment Details -->
    <div class="col-lg-5">
        <div class="card border-0 bg-white rounded-4 shadow-sm p-4 mb-4 small">
            <h6 class="fw-bold text-dark mb-3"><i class="bi bi-person text-primary me-2"></i> Data Pelanggan & Alamat</h6>
            <div class="mb-2"><strong>Nama:</strong> <?= esc($order['customer_name']) ?> (<?= esc($order['customer_email']) ?>)</div>
            <div class="mb-2"><strong>Penerima:</strong> <?= esc($order['recipient_name'] ?? $order['customer_name']) ?> (<?= esc($order['recipient_phone'] ?? $order['customer_phone']) ?>)</div>
            <div class="mb-2"><strong>Alamat:</strong> <?= esc($order['address_line'] ?? '-') ?>, <?= esc($order['city'] ?? '-') ?> <?= esc($order['postal_code'] ?? '') ?></div>
            <?php if ($order['customer_notes']): ?>
                <div class="p-2 bg-light rounded mt-2 border"><strong>Catatan:</strong> <?= esc($order['customer_notes']) ?></div>
            <?php endif; ?>
        </div>

        <div class="card border-0 bg-white rounded-4 shadow-sm p-4 mb-4 small">
            <h6 class="fw-bold text-dark mb-3"><i class="bi bi-credit-card text-success me-2"></i> Bukti Pembayaran</h6>
            <div class="mb-1"><strong>Metode:</strong> <?= esc($order['payment_method'] ?? 'Bank Transfer') ?> - <?= esc($order['bank_name'] ?? 'BCA') ?></div>
            <div class="mb-2"><strong>Status Pembayaran:</strong> <span class="badge bg-secondary"><?= strtoupper($order['payment_status'] ?? 'PENDING') ?></span></div>

            <?php if (!empty($order['proof_image'])): ?>
                <div class="text-center mt-2 border rounded p-2 bg-light">
                    <a href="<?= base_url('uploads/payments/' . $order['proof_image']) ?>" target="_blank">
                        <img src="<?= base_url('uploads/payments/' . $order['proof_image']) ?>" alt="Bukti Transfer" class="img-fluid rounded" style="max-height: 180px;">
                    </a>
                </div>
            <?php else: ?>
                <div class="text-muted fst-italic">Pelanggan belum mengunggah foto bukti transfer.</div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

