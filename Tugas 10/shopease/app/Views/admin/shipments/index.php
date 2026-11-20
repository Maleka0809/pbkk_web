<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="card border-0 bg-white rounded-4 shadow-sm p-4">
    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <div>
            <h5 class="fw-bold text-dark mb-1"><i class="bi bi-truck text-secondary me-2"></i> Pengiriman & Input Resi Kurir</h5>
            <small class="text-muted">Kelola pengemasan barang di gudang, ekspedisi kurir, dan input nomor resi pelacakan</small>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle small">
            <thead class="table-light">
                <tr>
                    <th>No. Order</th>
                    <th>Penerima</th>
                    <th>Kota Tujuan</th>
                    <th>Ekspedisi</th>
                    <th>Nomor Resi</th>
                    <th class="text-center">Status Kirim</th>
                    <th class="text-center" style="width: 120px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($shipments)): ?>
                    <tr><td colspan="7" class="text-center text-muted py-4">Belum ada data pengiriman.</td></tr>
                <?php else: ?>
                    <?php foreach ($shipments as $s): ?>
                        <tr>
                            <td class="fw-bold">#<?= esc($s['order_number']) ?></td>
                            <td><?= esc($s['recipient_name'] ?? $s['customer_name']) ?></td>
                            <td><?= esc($s['city'] ?? '-') ?></td>
                            <td><strong><?= esc($s['courier']) ?></strong> (<?= esc($s['service']) ?>)</td>
                            <td>
                                <?php if ($s['tracking_number']): ?>
                                    <span class="badge bg-dark font-monospace"><?= esc($s['tracking_number']) ?></span>
                                <?php else: ?>
                                    <span class="text-danger small fst-italic">Belum diinput</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <?php
                                    $b = 'bg-warning text-dark';
                                    if ($s['status'] === 'in_transit') $b = 'bg-primary';
                                    elseif ($s['status'] === 'delivered') $b = 'bg-success';
                                ?>
                                <span class="badge <?= $b ?> text-uppercase"><?= $s['status'] ?></span>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#modalShip_<?= $s['id'] ?>">
                                    Update Resi
                                </button>
                            </td>
                        </tr>

                        <!-- Modal Update Resi -->
                        <div class="modal fade" id="modalShip_<?= $s['id'] ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content rounded-4 border-0">
                                    <form action="<?= base_url('admin/shipments/update/' . $s['id']) ?>" method="POST">
                                        <?= csrf_field() ?>
                                        <div class="modal-header border-bottom">
                                            <h6 class="modal-title fw-bold">Update Resi & Status #<?= esc($s['order_number']) ?></h6>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label small fw-semibold">Nomor Resi Pelacakan</label>
                                                <input type="text" name="tracking_number" class="form-control" value="<?= esc($s['tracking_number'] ?? '') ?>" placeholder="Contoh: JNE88291029102" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label small fw-semibold">Status Pengiriman</label>
                                                <select name="status" class="form-select">
                                                    <option value="preparing" <?= $s['status'] === 'preparing' ? 'selected' : '' ?>>Preparing (Sedang Dikemas)</option>
                                                    <option value="in_transit" <?= $s['status'] === 'in_transit' ? 'selected' : '' ?>>In Transit (Diserahkan ke Kurir)</option>
                                                    <option value="delivered" <?= $s['status'] === 'delivered' ? 'selected' : '' ?>>Delivered (Paket Diterima Pembeli)</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label small fw-semibold">Catatan Pengiriman</label>
                                                <textarea name="notes" class="form-control" rows="2" placeholder="Catatan internal kurir..."><?= esc($s['notes'] ?? '') ?></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer border-top">
                                            <button type="button" class="btn btn-light btn-sm rounded-pill" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary btn-sm rounded-pill fw-bold">Simpan Resi</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>

