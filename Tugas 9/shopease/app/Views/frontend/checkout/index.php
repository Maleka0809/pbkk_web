<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container">

    <nav class="mb-3 small">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url() ?>">Home</a></li>
            <li class="breadcrumb-item"><a href="<?= base_url('cart') ?>">Keranjang</a></li>
            <li class="breadcrumb-item active">Checkout & Pembayaran</li>
        </ol>
    </nav>

    <div class="row g-4">
        <!-- Left: Form Checkout -->
        <div class="col-lg-8">
            <form action="<?= base_url('checkout/process') ?>" method="POST" id="checkoutForm">
                <?= csrf_field() ?>

                <!-- Step 1: Alamat Pengiriman -->
                <div class="card border-0 bg-white rounded-4 shadow-sm p-4 mb-4">
                    <h5 class="fw-bold text-dark mb-3">
                        <i class="bi bi-geo-alt-fill text-danger me-2"></i> 1. Alamat Pengiriman
                    </h5>

                    <?php if (!empty($addresses)): ?>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Pilih Alamat Tersimpan:</label>
                            <?php foreach ($addresses as $index => $addr): ?>
                                <div class="form-check p-3 border rounded-3 mb-2 bg-light">
                                    <input class="form-check-input" type="radio" name="address_id" id="addr_<?= $addr['id'] ?>" value="<?= $addr['id'] ?>" <?= $index === 0 ? 'checked' : '' ?>>
                                    <label class="form-check-label w-100" for="addr_<?= $addr['id'] ?>">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <strong><?= esc($addr['recipient_name']) ?></strong>
                                            <span class="badge bg-secondary"><?= esc($addr['phone']) ?></span>
                                        </div>
                                        <div class="small text-muted mt-1">
                                            <?= esc($addr['address_line']) ?>, <?= esc($addr['city']) ?> (Kode Pos: <?= esc($addr['postal_code']) ?>)
                                        </div>
                                    </label>
                                </div>
                            <?php endforeach; ?>

                            <div class="form-check p-2">
                                <input class="form-check-input" type="radio" name="address_id" id="addr_new" value="new">
                                <label class="form-check-label small fw-semibold" for="addr_new">
                                    + Tambah Alamat Pengiriman Baru
                                </label>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Form Tambah Alamat Baru -->
                    <div id="newAddressFields" class="<?= !empty($addresses) ? 'd-none' : '' ?> bg-light p-3 rounded-3 border">
                        <h6 class="fw-bold small mb-3">Form Alamat Pengiriman Baru</h6>
                        <div class="row g-2 mb-2">
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Nama Penerima</label>
                                <input type="text" name="new_recipient_name" class="form-control form-control-sm" placeholder="Nama Penerima">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Nomor Telepon / WA</label>
                                <input type="tel" name="new_phone" class="form-control form-control-sm" placeholder="08xxxxxxxxxx">
                            </div>
                        </div>
                        <div class="mb-2">
                            <label class="form-label small fw-semibold">Alamat Lengkap</label>
                            <textarea name="new_address_line" class="form-control form-control-sm" rows="2" placeholder="Nama jalan, nomor rumah, RT/RW, kelurahan/kecamatan"></textarea>
                        </div>
                        <div class="row g-2">
                            <div class="col-md-8">
                                <label class="form-label small fw-semibold">Kota / Kabupaten</label>
                                <input type="text" name="new_city" class="form-control form-control-sm" placeholder="Contoh: Surabaya">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-semibold">Kode Pos</label>
                                <input type="text" name="new_postal_code" class="form-control form-control-sm" placeholder="60111">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Kurir & Opsi Pengiriman -->
                <div class="card border-0 bg-white rounded-4 shadow-sm p-4 mb-4">
                    <h5 class="fw-bold text-dark mb-3">
                        <i class="bi bi-truck text-primary me-2"></i> 2. Opsi Ekspedisi Pengiriman
                    </h5>
                    <div class="row g-2">
                        <div class="col-md-4">
                            <div class="border rounded-3 p-3 bg-light">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="courier" id="c_jne" value="JNE" checked>
                                    <label class="form-check-label fw-bold small" for="c_jne">JNE Express</label>
                                </div>
                                <div class="small text-muted mt-1">Layanan Reguler (2-3 hari)</div>
                                <div class="fw-bold text-primary small mt-1">Rp 20.000</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border rounded-3 p-3 bg-light">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="courier" id="c_sicepat" value="SiCepat">
                                    <label class="form-check-label fw-bold small" for="c_sicepat">SiCepat Express</label>
                                </div>
                                <div class="small text-muted mt-1">Layanan BEST (1-2 hari)</div>
                                <div class="fw-bold text-primary small mt-1">Rp 20.000</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border rounded-3 p-3 bg-light">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="courier" id="c_jnt" value="J&T">
                                    <label class="form-check-label fw-bold small" for="c_jnt">J&T Express</label>
                                </div>
                                <div class="small text-muted mt-1">Layanan EZ (2-3 hari)</div>
                                <div class="fw-bold text-primary small mt-1">Rp 20.000</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 3: Metode Pembayaran Transfer -->
                <div class="card border-0 bg-white rounded-4 shadow-sm p-4 mb-4">
                    <h5 class="fw-bold text-dark mb-3">
                        <i class="bi bi-credit-card-2-front-fill text-success me-2"></i> 3. Metode Pembayaran Transfer Bank
                    </h5>
                    <div class="row g-2 mb-3">
                        <div class="col-sm-6">
                            <div class="border rounded-3 p-3 bg-light">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="bank_name" id="b_bca" value="BCA" checked>
                                    <label class="form-check-label fw-bold small" for="b_bca">Bank BCA</label>
                                </div>
                                <div class="small text-muted">No. Rek: 8291029102 a/n PT ShopEase Indonesia</div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="border rounded-3 p-3 bg-light">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="bank_name" id="b_mandiri" value="Mandiri">
                                    <label class="form-check-label fw-bold small" for="b_mandiri">Bank Mandiri</label>
                                </div>
                                <div class="small text-muted">No. Rek: 1420098192019 a/n PT ShopEase Indonesia</div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="border rounded-3 p-3 bg-light">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="bank_name" id="b_bri" value="BRI">
                                    <label class="form-check-label fw-bold small" for="b_bri">Bank BRI</label>
                                </div>
                                <div class="small text-muted">No. Rek: 034101000982531 a/n PT ShopEase Indonesia</div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="border rounded-3 p-3 bg-light">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="bank_name" id="b_bni" value="BNI">
                                    <label class="form-check-label fw-bold small" for="b_bni">Bank BNI</label>
                                </div>
                                <div class="small text-muted">No. Rek: 0981298102 a/n PT ShopEase Indonesia</div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label small fw-semibold">Catatan Tambahan untuk Penjual (Opsional)</label>
                        <textarea name="customer_notes" class="form-control form-control-sm" rows="2" placeholder="Contoh: Titipkan di satpam, bubble wrap ekstra tebal, dll."></textarea>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-lg w-100 rounded-pill fw-bold shadow-sm py-3 mb-4">
                    <i class="bi bi-check2-circle me-1"></i> Konfirmasi & Buat Pesanan Sekarang
                </button>
            </form>
        </div>

        <!-- Right: Order Items Summary -->
        <div class="col-lg-4">
            <div class="card border-0 bg-white rounded-4 shadow-sm p-4 sticky-top" style="top: 90px;">
                <h5 class="fw-bold text-dark mb-3">Rincian Barang (<?= $totalItems ?>)</h5>

                <div class="list-group list-group-flush mb-3" style="max-height: 280px; overflow-y: auto;">
                    <?php foreach ($items as $it): ?>
                        <div class="list-group-item px-0 py-2 border-bottom">
                            <div class="d-flex align-items-center">
                                <img src="<?= base_url('uploads/products/' . $it['image']) ?>" alt="<?= esc($it['product_name']) ?>" width="45" height="45" class="rounded border p-1 me-2 bg-light" style="object-fit: contain;">
                                <div class="flex-grow-1">
                                    <div class="small fw-bold text-truncate" style="max-width: 170px;" title="<?= esc($it['product_name']) ?>">
                                        <?= esc($it['product_name']) ?>
                                    </div>
                                    <small class="text-muted"><?= $it['quantity'] ?>x @ Rp <?= number_format($it['price'], 0, ',', '.') ?></small>
                                </div>
                                <div class="text-end small fw-bold text-primary">
                                    Rp <?= number_format($it['price'] * $it['quantity'], 0, ',', '.') ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="d-flex justify-content-between mb-2 small">
                    <span class="text-muted">Subtotal Produk</span>
                    <span class="fw-bold">Rp <?= number_format($totalPrice, 0, ',', '.') ?></span>
                </div>
                <div class="d-flex justify-content-between mb-3 small">
                    <span class="text-muted">Ongkos Kirim Flat</span>
                    <span class="fw-bold text-success">Rp 20.000</span>
                </div>

                <hr class="my-3">

                <div class="d-flex justify-content-between align-items-baseline mb-3">
                    <span class="fw-bold fs-6">Grand Total</span>
                    <span class="fw-extrabold fs-5 text-primary">
                        Rp <?= number_format($totalPrice + 20000, 0, ',', '.') ?>
                    </span>
                </div>

                <div class="p-3 bg-light rounded-3 small text-muted">
                    <i class="bi bi-info-circle text-primary me-1"></i> Setelah konfirmasi pesanan, Anda akan diarahkan ke halaman transfer untuk mengunggah bukti pembayaran.
                </div>
            </div>
        </div>
    </div>

</div>

<script>
document.querySelectorAll('input[name="address_id"]').forEach(function(radio) {
    radio.addEventListener('change', function() {
        var fields = document.getElementById('newAddressFields');
        if (this.value === 'new') {
            fields.classList.remove('d-none');
        } else {
            fields.classList.add('d-none');
        }
    });
});
</script>
<?= $this->endSection() ?>

