<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="card border-0 bg-white rounded-4 shadow-sm p-4">
    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <div>
            <h5 class="fw-bold text-dark mb-1"><i class="bi bi-credit-card text-info me-2"></i> Verifikasi Pembayaran Transfer</h5>
            <small class="text-muted">Tinjau keaslian bukti transfer pelanggan dan terima/tolak verifikasi</small>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle small">
            <thead class="table-light">
                <tr>
                    <th>No. Order</th>
                    <th>Nama Pelanggan</th>
                    <th>Bank & Rekening</th>
                    <th>Nominal Transfer</th>
                    <th>Bukti Foto</th>
                    <th class="text-center">Status</th>
                    <th class="text-center" style="width: 160px;">Aksi Verifikasi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($payments)): ?>
                    <tr><td colspan="7" class="text-center text-muted py-4">Belum ada data pembayaran yang masuk.</td></tr>
                <?php else: ?>
                    <?php foreach ($payments as $pay): ?>
                        <tr>
                            <td class="fw-bold">#<?= esc($pay['order_number']) ?></td>
                            <td>
                                <div class="fw-bold text-dark"><?= esc($pay['customer_name']) ?></div>
                                <small class="text-muted"><?= esc($pay['customer_email']) ?></small>
                            </td>
                            <td>
                                <div><strong><?= esc($pay['bank_name']) ?></strong></div>
                                <small class="text-muted"><?= esc($pay['account_number'] ?? '-') ?> (a/n <?= esc($pay['account_holder'] ?? '-') ?>)</small>
                            </td>
                            <td class="fw-bold text-primary">Rp <?= number_format($pay['amount'], 0, ',', '.') ?></td>
                            <td>
                                <?php if ($pay['proof_image']): ?>
                                    <a href="<?= base_url('uploads/payments/' . $pay['proof_image']) ?>" target="_blank" class="btn btn-sm btn-light border py-1 px-2">
                                        <i class="bi bi-image me-1"></i> Lihat Foto
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted fst-italic">Belum upload</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <?php
                                    $b = 'bg-warning text-dark';
                                    if ($pay['status'] === 'verified') $b = 'bg-success';
                                    elseif ($pay['status'] === 'rejected') $b = 'bg-danger';
                                ?>
                                <span class="badge <?= $b ?> text-uppercase"><?= $pay['status'] ?></span>
                            </td>
                            <td class="text-center">
                                <?php if ($pay['status'] !== 'verified'): ?>
                                    <a href="<?= base_url('admin/payments/verify/' . $pay['id']) ?>" class="btn btn-sm btn-success rounded-pill px-2" onclick="return confirm('Verifikasi dan terima pembayaran ini?')">
                                        <i class="bi bi-check-lg"></i> Terima
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-2" data-bs-toggle="modal" data-bs-target="#modalReject_<?= $pay['id'] ?>">
                                        <i class="bi bi-x-lg"></i> Tolak
                                    </button>
                                <?php else: ?>
                                    <span class="text-success small fw-bold"><i class="bi bi-check-circle-fill"></i> Selesai</span>
                                <?php endif; ?>
                            </td>
                        </tr>

                        <!-- Modal Tolak Pembayaran -->
                        <div class="modal fade" id="modalReject_<?= $pay['id'] ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content rounded-4 border-0">
                                    <form action="<?= base_url('admin/payments/reject/' . $pay['id']) ?>" method="POST">
                                        <?= csrf_field() ?>
                                        <div class="modal-header border-bottom">
                                            <h6 class="modal-title fw-bold">Tolak Pembayaran #<?= esc($pay['order_number']) ?></h6>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <label class="form-label small fw-semibold">Alasan Penolakan</label>
                                            <textarea name="reason" class="form-control" rows="3" placeholder="Contoh: Bukti transfer buram, mutasi rekening belum masuk..." required></textarea>
                                        </div>
                                        <div class="modal-footer border-top">
                                            <button type="button" class="btn btn-light btn-sm rounded-pill" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-danger btn-sm rounded-pill fw-bold">Tolak Pembayaran</button>
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

