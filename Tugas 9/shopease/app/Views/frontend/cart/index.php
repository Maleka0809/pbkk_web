<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container">

    <div class="d-flex align-items-center justify-content-between mb-4 pb-2 border-bottom">
        <div>
            <h3 class="fw-bold mb-1"><i class="bi bi-cart3 text-primary me-2"></i> Keranjang Belanja Anda</h3>
            <p class="text-muted small mb-0">Periksa kuantitas dan daftar item sebelum melanjutkan ke proses pembayaran.</p>
        </div>
        <?php if (!empty($items)): ?>
            <a href="<?= base_url('cart/clear') ?>" class="btn btn-outline-danger btn-sm rounded-pill" onclick="return confirm('Apakah Anda yakin ingin mengosongkan seluruh isi keranjang?')">
                <i class="bi bi-trash3 me-1"></i> Kosongkan Keranjang
            </a>
        <?php endif; ?>
    </div>

    <?php if (empty($items)): ?>
        <div class="card border-0 bg-white rounded-4 p-5 text-center shadow-sm my-4">
            <div class="d-inline-flex align-items-center justify-content-center bg-light rounded-circle mx-auto mb-3" style="width: 80px; height: 80px;">
                <i class="bi bi-cart-x text-muted fs-1"></i>
            </div>
            <h4 class="fw-bold text-dark">Keranjang Belanja Masih Kosong</h4>
            <p class="text-muted small mb-4">Anda belum memasukkan produk apa pun ke dalam keranjang. Yuk temukan gadget impian Anda sekarang!</p>
            <div>
                <a href="<?= base_url('products') ?>" class="btn btn-primary rounded-pill px-4 py-2 fw-bold">
                    <i class="bi bi-bag-plus me-1"></i> Mulai Berbelanja
                </a>
            </div>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <!-- Left: Items Table -->
            <div class="col-lg-8">
                <div class="card border-0 bg-white rounded-4 shadow-sm p-3">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light small">
                                <tr>
                                    <th scope="col" style="min-width: 250px;">Produk</th>
                                    <th scope="col">Harga Satuan</th>
                                    <th scope="col" style="width: 140px;">Jumlah</th>
                                    <th scope="col">Subtotal</th>
                                    <th scope="col" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($items as $item): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="<?= base_url('uploads/products/' . $item['image']) ?>" alt="<?= esc($item['product_name']) ?>" width="60" height="60" class="rounded border p-1 me-3 bg-light" style="object-fit: contain;">
                                                <div>
                                                    <a href="<?= base_url('products/' . $item['product_slug']) ?>" class="fw-bold text-dark text-decoration-none small d-block mb-1">
                                                        <?= esc($item['product_name']) ?>
                                                    </a>
                                                    <small class="text-muted">Stok: <?= $item['stock'] ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="small fw-semibold">
                                                Rp <?= number_format($item['price'], 0, ',', '.') ?>
                                            </span>
                                        </td>
                                        <td>
                                            <form action="<?= base_url('cart/update') ?>" method="POST" class="d-flex align-items-center">
                                                <?= csrf_field() ?>
                                                <input type="hidden" name="item_id" value="<?= $item['id'] ?>">
                                                <input type="number" name="quantity" class="form-control form-control-sm text-center" value="<?= $item['quantity'] ?>" min="1" max="<?= $item['stock'] ?>" style="width: 65px;" onchange="this.form.submit()">
                                            </form>
                                        </td>
                                        <td>
                                            <span class="fw-bold text-primary">
                                                Rp <?= number_format($item['price'] * $item['quantity'], 0, ',', '.') ?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <a href="<?= base_url('cart/delete/' . $item['id']) ?>" class="btn btn-sm btn-outline-danger border-0" onclick="return confirm('Hapus item ini dari keranjang?')" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="mt-3">
                    <a href="<?= base_url('products') ?>" class="btn btn-outline-secondary rounded-pill px-3 py-2 small fw-semibold">
                        <i class="bi bi-arrow-left me-1"></i> Lanjut Belanja Produk Lain
                    </a>
                </div>
            </div>

            <!-- Right: Order Summary Card -->
            <div class="col-lg-4">
                <div class="card border-0 bg-white rounded-4 shadow-sm p-4 sticky-top" style="top: 90px;">
                    <h5 class="fw-bold text-dark mb-3">Ringkasan Belanja</h5>
                    
                    <div class="d-flex justify-content-between mb-2 small">
                        <span class="text-muted">Total Kuantitas</span>
                        <span class="fw-bold"><?= $totalItems ?> Barang</span>
                    </div>

                    <div class="d-flex justify-content-between mb-2 small">
                        <span class="text-muted">Subtotal Produk</span>
                        <span class="fw-bold">Rp <?= number_format($totalPrice, 0, ',', '.') ?></span>
                    </div>

                    <div class="d-flex justify-content-between mb-3 small">
                        <span class="text-muted">Estimasi Biaya Pengiriman</span>
                        <span class="fw-bold text-success">Rp 20.000</span>
                    </div>

                    <hr class="my-3">

                    <div class="d-flex justify-content-between mb-4">
                        <span class="fw-bold fs-6">Total Belanja</span>
                        <span class="fw-extrabold fs-5 text-primary">
                            Rp <?= number_format($totalPrice + 20000, 0, ',', '.') ?>
                        </span>
                    </div>

                    <a href="<?= base_url('checkout') ?>" class="btn btn-primary btn-lg w-100 rounded-pill fw-bold">
                        <i class="bi bi-shield-lock-fill me-1"></i> Lanjut ke Checkout
                    </a>

                    <div class="mt-3 text-center text-muted small" style="font-size: 11px;">
                        <i class="bi bi-check2-circle text-success me-1"></i> Transaksi Terenkripsi & Aman 100%
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

</div>
<?= $this->endSection() ?>

