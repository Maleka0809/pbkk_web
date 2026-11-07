<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container">

    <nav class="mb-3 small">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url() ?>">Home</a></li>
            <li class="breadcrumb-item"><a href="<?= base_url('orders') ?>">Pesanan Saya</a></li>
            <li class="breadcrumb-item active">#<?= esc($order['order_number']) ?></li>
        </ol>
    </nav>

    <!-- Header Status Order -->
    <div class="card border-0 bg-white rounded-4 shadow-sm p-4 mb-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
            <div>
                <span class="badge bg-primary text-uppercase mb-2">Detail Transaksi</span>
                <h3 class="fw-bold mb-1">Pesanan #<?= esc($order['order_number']) ?></h3>
                <small class="text-muted">Dibuat pada: <?= date('d F Y, H:i:s', strtotime($order['created_at'])) ?> WIB</small>
            </div>
            <div class="mt-3 mt-md-0 text-md-end">
                <small class="text-muted d-block mb-1">Status Transaksi:</small>
                <?php
                    $badgeClass = 'bg-secondary';
                    $statusLabel = strtoupper($order['status']);
                    if ($order['status'] === 'pending') { $badgeClass = 'bg-warning text-dark'; $statusLabel = 'Menunggu Pembayaran'; }
                    elseif ($order['status'] === 'paid') { $badgeClass = 'bg-info text-dark'; $statusLabel = 'Sudah Dibayar'; }
                    elseif ($order['status'] === 'processing') { $badgeClass = 'bg-primary'; $statusLabel = 'Sedang Diproses'; }
                    elseif ($order['status'] === 'shipped') { $badgeClass = 'bg-dark'; $statusLabel = 'Sedang Dikirim'; }
                    elseif ($order['status'] === 'completed') { $badgeClass = 'bg-success'; $statusLabel = 'Pesanan Selesai'; }
                    elseif ($order['status'] === 'cancelled') { $badgeClass = 'bg-danger'; $statusLabel = 'Pesanan Dibatalkan'; }
                ?>
                <span class="badge <?= $badgeClass ?> fs-6 px-3 py-2 rounded-pill">
                    <?= $statusLabel ?>
                </span>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Left: Items & Delivery Details -->
        <div class="col-lg-7">
            <!-- Items Card -->
            <div class="card border-0 bg-white rounded-4 shadow-sm p-4 mb-4">
                <h5 class="fw-bold text-dark mb-3"><i class="bi bi-box-seam text-primary me-2"></i> Produk yang Dipesan</h5>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead class="table-light small">
                            <tr>
                                <th>Produk</th>
                                <th>Harga</th>
                                <th class="text-center">Jumlah</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($items as $item): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="<?= base_url('uploads/products/' . ($item['image'] ?: 'default-product.svg')) ?>" alt="<?= esc($item['product_name']) ?>" width="48" height="48" class="rounded border p-1 me-2 bg-light" style="object-fit: contain;">
                                            <div class="small fw-bold"><?= esc($item['product_name']) ?></div>
                                        </div>
                                    </td>
                                    <td class="small">Rp <?= number_format($item['price'], 0, ',', '.') ?></td>
                                    <td class="small text-center"><?= $item['quantity'] ?></td>
                                    <td class="small fw-bold text-end">Rp <?= number_format($item['subtotal'], 0, ',', '.') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3" class="text-end small">Subtotal Produk:</th>
                                <th class="text-end small">Rp <?= number_format($order['total_amount'], 0, ',', '.') ?></th>
                            </tr>
                            <tr>
                                <th colspan="3" class="text-end small">Ongkos Kirim:</th>
                                <th class="text-end small text-success">Rp <?= number_format($order['shipping_cost'], 0, ',', '.') ?></th>
                            </tr>
                            <tr class="table-light">
                                <th colspan="3" class="text-end fs-6">Grand Total:</th>
                                <th class="text-end fs-6 text-primary fw-extrabold">Rp <?= number_format($order['grand_total'], 0, ',', '.') ?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Delivery & Address Card -->
            <div class="card border-0 bg-white rounded-4 shadow-sm p-4 mb-4">
                <h5 class="fw-bold text-dark mb-3"><i class="bi bi-truck text-success me-2"></i> Informasi Pengiriman</h5>
                <div class="row g-3 small">
                    <div class="col-sm-6">
                        <div class="text-muted mb-1">Penerima Paket:</div>
                        <div class="fw-bold text-dark"><?= esc($order['recipient_name'] ?? $order['customer_name']) ?></div>
                        <div><?= esc($order['recipient_phone'] ?? $order['customer_phone']) ?></div>
                        <div class="text-muted mt-1"><?= esc($order['address_line']) ?>, <?= esc($order['city']) ?> <?= esc($order['postal_code']) ?></div>
                    </div>
                    <div class="col-sm-6 border-start-sm">
                        <div class="text-muted mb-1">Kurir & Resi:</div>
                        <div class="fw-bold text-dark"><?= esc($order['courier'] ?? 'JNE') ?> (<?= esc($order['shipment_service'] ?? 'Reguler') ?>)</div>
                        <div class="mt-2">
                            <span class="text-muted">Nomor Resi:</span>
                            <?php if ($order['tracking_number']): ?>
                                <span class="badge bg-dark fs-6 d-inline-block mt-1"><?= esc($order['tracking_number']) ?></span>
                            <?php else: ?>
                                <span class="badge bg-warning text-dark mt-1">Resi sedang diproses penjual</span>
                            <?php endif; ?>
                        </div>
                        <?php if ($order['shipment_status']): ?>
                            <div class="mt-2 small">
                                Status Kurir: <span class="badge bg-secondary"><?= strtoupper($order['shipment_status']) ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if ($order['customer_notes']): ?>
                    <div class="mt-3 p-2 bg-light rounded small border">
                        <strong>Catatan Pembeli:</strong> <?= esc($order['customer_notes']) ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Right: Payment & Upload Proof Form -->
        <div class="col-lg-5">
            <div class="card border-0 bg-white rounded-4 shadow-sm p-4 mb-4">
                <h5 class="fw-bold text-dark mb-3"><i class="bi bi-credit-card text-warning me-2"></i> Status Pembayaran</h5>

                <div class="p-3 bg-light rounded-3 mb-3 border">
                    <div class="d-flex justify-content-between mb-1 small">
                        <span class="text-muted">Metode:</span>
                        <span class="fw-bold"><?= esc($order['payment_method'] ?? 'Bank Transfer') ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-1 small">
                        <span class="text-muted">Bank Tujuan:</span>
                        <span class="fw-bold text-primary"><?= esc($order['bank_name'] ?? 'BCA') ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-1 small">
                        <span class="text-muted">Total Transfer:</span>
                        <span class="fw-extrabold text-danger fs-6">Rp <?= number_format($order['grand_total'], 0, ',', '.') ?></span>
                    </div>
                    <div class="d-flex justify-content-between small">
                        <span class="text-muted">Status Bukti:</span>
                        <span class="badge <?= ($order['payment_status'] === 'verified') ? 'bg-success' : (($order['payment_status'] === 'rejected') ? 'bg-danger' : 'bg-warning text-dark') ?>">
                            <?= strtoupper($order['payment_status'] ?? 'BELUM UPLOAD') ?>
                        </span>
                    </div>
                </div>

                <!-- Proof Preview if uploaded -->
                <?php if (!empty($order['proof_image'])): ?>
                    <div class="mb-3 text-center">
                        <label class="form-label small fw-semibold text-muted d-block">Bukti Pembayaran Terunggah:</label>
                        <a href="<?= base_url('uploads/payments/' . $order['proof_image']) ?>" target="_blank">
                            <img src="<?= base_url('uploads/payments/' . $order['proof_image']) ?>" alt="Bukti Transfer" class="img-fluid rounded border p-2 bg-light" style="max-height: 200px;">
                        </a>
                        <small class="d-block text-muted mt-1" style="font-size: 11px;">Klik foto untuk melihat ukuran penuh</small>

                        <?php if ($order['payment_status'] === 'verified'): ?>
                            <div class="alert alert-success small py-2 mt-2 mb-0">
                                <i class="bi bi-check-circle-fill me-1"></i> Pembayaran Anda telah <strong>Diverifikasi</strong> oleh Admin!
                            </div>
                        <?php elseif ($order['payment_status'] === 'rejected'): ?>
                            <div class="alert alert-danger small py-2 mt-2 mb-0">
                                <i class="bi bi-x-circle-fill me-1"></i> Bukti ditolak: <?= esc($order['rejection_reason'] ?: 'Mohon upload ulang bukti transfer yang jelas.') ?>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-info small py-2 mt-2 mb-0">
                                <i class="bi bi-hourglass-split me-1"></i> Menunggu tinjauan & verifikasi admin toko.
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <!-- Upload / Re-upload Form -->
                <?php if ($order['payment_status'] !== 'verified'): ?>
                    <form action="<?= base_url('orders/' . $order['id'] . '/upload-payment') ?>" method="POST" enctype="multipart/form-data" class="border-top pt-3">
                        <?= csrf_field() ?>
                        <h6 class="fw-bold small mb-2">Unggah Bukti Transfer Baru:</h6>

                        <div class="mb-2">
                            <label class="form-label small fw-semibold">Nama Bank Pengirim</label>
                            <input type="text" name="bank_name" class="form-control form-control-sm" placeholder="Contoh: BCA / Mandiri / BRI" value="<?= esc($order['bank_name'] ?? 'BCA') ?>" required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label small fw-semibold">Nomor Rekening Pengirim</label>
                            <input type="text" name="account_number" class="form-control form-control-sm" placeholder="Nomor rekening Anda" value="<?= esc($order['account_number'] ?? '') ?>" required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label small fw-semibold">Nama Pemilik Rekening</label>
                            <input type="text" name="account_holder" class="form-control form-control-sm" placeholder="Nama atas rekening Anda" value="<?= esc($order['account_holder'] ?? $order['customer_name']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">File Foto Bukti Transfer</label>
                            <input type="file" name="proof_image" class="form-control form-control-sm" accept="image/*" required>
                            <small class="text-muted" style="font-size: 11px;">Format JPG, PNG, WEBP, atau SVG. Maks 4MB.</small>
                        </div>

                        <button type="submit" class="btn btn-success w-100 rounded-pill fw-bold">
                            <i class="bi bi-upload me-1"></i> Unggah Bukti Pembayaran
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>

</div>
<?= $this->endSection() ?>

