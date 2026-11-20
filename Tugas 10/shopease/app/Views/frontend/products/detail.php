<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container">

    <!-- Breadcrumb -->
    <nav class="mb-4 small">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url() ?>">Home</a></li>
            <li class="breadcrumb-item"><a href="<?= base_url('products') ?>">Katalog</a></li>
            <li class="breadcrumb-item"><a href="<?= base_url('products?category=' . $product['category_id']) ?>"><?= esc($product['category_name']) ?></a></li>
            <li class="breadcrumb-item active"><?= esc($product['name']) ?></li>
        </ol>
    </nav>

    <!-- Product Showcase Card -->
    <div class="card border-0 bg-white rounded-4 shadow-sm p-4 p-md-5 mb-5">
        <div class="row g-5">
            <!-- Left: Product Image -->
            <div class="col-lg-5 text-center">
                <div class="bg-light rounded-4 p-4 border mb-3">
                    <img src="<?= base_url('uploads/products/' . $product['image']) ?>" alt="<?= esc($product['name']) ?>" class="img-fluid" style="max-height: 360px; object-fit: contain;">
                </div>
                <div class="d-flex justify-content-center gap-2">
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 rounded-pill small">
                        <i class="bi bi-shield-check me-1"></i> Garansi Resmi Distributor 1 Tahun
                    </span>
                    <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 rounded-pill small">
                        <i class="bi bi-box-seam me-1"></i> Original 100%
                    </span>
                </div>
            </div>

            <!-- Right: Product Info & Buy Form -->
            <div class="col-lg-7">
                <span class="badge bg-primary text-uppercase mb-2"><?= esc($product['category_name']) ?></span>
                <h2 class="fw-bold text-dark mb-2"><?= esc($product['name']) ?></h2>
                
                <div class="d-flex align-items-center gap-2 mb-3">
                    <div class="text-warning small">
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                    </div>
                    <span class="small text-muted">&bull; 4.9 (120+ Ulasan Pembeli) &bull; Terjual 250+</span>
                </div>

                <div class="p-3 bg-light rounded-3 mb-4 d-flex align-items-baseline">
                    <span class="display-6 fw-extrabold text-primary me-3">
                        Rp <?= number_format($product['price'], 0, ',', '.') ?>
                    </span>
                    <span class="text-muted text-decoration-line-through small">
                        Rp <?= number_format($product['price'] * 1.15, 0, ',', '.') ?>
                    </span>
                    <span class="badge bg-danger ms-2">-15%</span>
                </div>

                <div class="mb-4">
                    <h6 class="fw-bold text-dark mb-2">Deskripsi & Spesifikasi Produk</h6>
                    <p class="text-muted lh-base" style="font-size: 14.5px;">
                        <?= nl2br(esc($product['description'])) ?>
                    </p>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-sm-6">
                        <div class="p-2 border rounded-3 bg-white d-flex align-items-center">
                            <i class="bi bi-box-seam-fill text-primary fs-3 me-2"></i>
                            <div>
                                <small class="text-muted d-block" style="font-size: 11px;">Ketersediaan Stok</small>
                                <span class="fw-bold <?= $product['stock'] > 5 ? 'text-success' : 'text-danger' ?>">
                                    <?= $product['stock'] ?> unit tersedia
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="p-2 border rounded-3 bg-white d-flex align-items-center">
                            <i class="bi bi-truck text-success fs-3 me-2"></i>
                            <div>
                                <small class="text-muted d-block" style="font-size: 11px;">Estimasi Pengiriman</small>
                                <span class="fw-bold text-dark">1 - 2 Hari Kerja</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Add to Cart Form -->
                <form action="<?= base_url('cart/add') ?>" method="POST" class="border-top pt-4">
                    <?= csrf_field() ?>
                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">

                    <div class="row g-3 align-items-center">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small mb-1">Jumlah Pembelian</label>
                            <div class="input-group">
                                <button class="btn btn-outline-secondary" type="button" onclick="decrementQty()">-</button>
                                <input type="number" id="qtyInput" name="quantity" class="form-control text-center" value="1" min="1" max="<?= $product['stock'] ?>">
                                <button class="btn btn-outline-secondary" type="button" onclick="incrementQty()">+</button>
                            </div>
                        </div>
                        <div class="col-md-8 pt-md-4">
                            <button type="submit" class="btn btn-primary btn-lg w-100 rounded-pill fw-bold" <?= $product['stock'] <= 0 ? 'disabled' : '' ?>>
                                <i class="bi bi-cart-plus me-1"></i> Masukkan ke Keranjang
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    <?php if (!empty($relatedProducts)): ?>
        <div class="mb-5">
            <h4 class="fw-bold text-dark mb-3">Produk Serupa yang Mungkin Anda Sukai</h4>
            <div class="row g-3">
                <?php foreach ($relatedProducts as $rel): ?>
                    <div class="col-6 col-md-3">
                        <div class="card card-product h-100 p-2 text-center bg-white">
                            <img src="<?= base_url('uploads/products/' . $rel['image']) ?>" alt="<?= esc($rel['name']) ?>" class="img-fluid rounded mb-2" style="height: 140px; object-fit: contain;">
                            <div class="fw-bold text-dark text-truncate small mb-1">
                                <a href="<?= base_url('products/' . $rel['slug']) ?>" class="text-dark text-decoration-none">
                                    <?= esc($rel['name']) ?>
                                </a>
                            </div>
                            <div class="text-primary fw-bold mb-2">Rp <?= number_format($rel['price'], 0, ',', '.') ?></div>
                            <a href="<?= base_url('products/' . $rel['slug']) ?>" class="btn btn-sm btn-outline-primary rounded-pill">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

</div>

<script>
function decrementQty() {
    var input = document.getElementById('qtyInput');
    var val = parseInt(input.value);
    if (val > 1) {
        input.value = val - 1;
    }
}
function incrementQty() {
    var input = document.getElementById('qtyInput');
    var val = parseInt(input.value);
    var max = parseInt(input.getAttribute('max'));
    if (val < max) {
        input.value = val + 1;
    }
}
</script>
<?= $this->endSection() ?>

