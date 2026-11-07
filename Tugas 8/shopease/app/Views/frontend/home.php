<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container">

    <!-- Hero Banner Carousel -->
    <div id="heroCarousel" class="carousel slide rounded-4 overflow-hidden shadow-sm mb-5" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2"></button>
        </div>
        <div class="carousel-inner">
            <div class="carousel-item active bg-dark text-white p-5 position-relative" style="background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 100%); min-height: 380px;">
                <div class="row align-items-center h-100 py-4">
                    <div class="col-lg-7">
                        <span class="badge bg-danger mb-2 px-3 py-2 rounded-pill"><i class="bi bi-fire me-1"></i> PROMO SPESIAL GAJIAN</span>
                        <h1 class="display-5 fw-extrabold text-white mb-3">Upgrade Laptop & Gadget Impian Anda!</h1>
                        <p class="lead text-light mb-4">Dapatkan diskon hingga Rp 2.000.000 untuk laptop ASUS ROG, MacBook Air M3, dan seri Samsung Galaxy terbaru.</p>
                        <div class="d-flex gap-2">
                            <a href="<?= base_url('products') ?>" class="btn btn-primary btn-lg rounded-pill px-4 fw-bold">
                                <i class="bi bi-cart-plus me-1"></i> Belanja Sekarang
                            </a>
                            <a href="<?= base_url('products?category=1') ?>" class="btn btn-outline-light btn-lg rounded-pill px-4">
                                Seri Laptop
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-5 d-none d-lg-block text-center">
                        <img src="<?= base_url('uploads/products/rog-g14.svg') ?>" alt="Laptop ROG" class="img-fluid" style="max-height: 280px;">
                    </div>
                </div>
            </div>

            <div class="carousel-item bg-dark text-white p-5 position-relative" style="background: linear-gradient(135deg, #1e1b4b 0%, #4338ca 100%); min-height: 380px;">
                <div class="row align-items-center h-100 py-4">
                    <div class="col-lg-7">
                        <span class="badge bg-warning text-dark mb-2 px-3 py-2 rounded-pill"><i class="bi bi-controller me-1"></i> GAMING ZONE</span>
                        <h1 class="display-5 fw-extrabold text-white mb-3">PlayStation 5 & Custom Mechanical Keyboard</h1>
                        <p class="lead text-light mb-4">Pengalaman gaming kompetitif tanpa batas dengan latency ultra-rendah dan switch mekanikal hot-swappable.</p>
                        <a href="<?= base_url('products?category=4') ?>" class="btn btn-warning btn-lg rounded-pill px-4 fw-bold">
                            Jelajahi Gaming Gear
                        </a>
                    </div>
                    <div class="col-lg-5 d-none d-lg-block text-center">
                        <img src="<?= base_url('uploads/products/ps5-slim.svg') ?>" alt="PS5 Slim" class="img-fluid" style="max-height: 280px;">
                    </div>
                </div>
            </div>

            <div class="carousel-item bg-dark text-white p-5 position-relative" style="background: linear-gradient(135deg, #064e3b 0%, #059669 100%); min-height: 380px;">
                <div class="row align-items-center h-100 py-4">
                    <div class="col-lg-7">
                        <span class="badge bg-light text-success mb-2 px-3 py-2 rounded-pill"><i class="bi bi-headphones me-1"></i> AUDIO KELAS DUNIA</span>
                        <h1 class="display-5 fw-extrabold text-white mb-3">Sony WH-1000XM5 & Apple AirPods Pro</h1>
                        <p class="lead text-light mb-4">Heningkan kebisingan kota dengan Active Noise Cancelling cerdas dan kualitas suara studio Hi-Res LDAC.</p>
                        <a href="<?= base_url('products?category=3') ?>" class="btn btn-light text-success btn-lg rounded-pill px-4 fw-bold">
                            Cek Koleksi Audio
                        </a>
                    </div>
                    <div class="col-lg-5 d-none d-lg-block text-center">
                        <img src="<?= base_url('uploads/products/sony-xm5.svg') ?>" alt="Sony XM5" class="img-fluid" style="max-height: 280px;">
                    </div>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>

    <!-- Trust Badges -->
    <div class="row g-3 mb-5">
        <div class="col-6 col-md-3">
            <div class="p-3 bg-white rounded-3 border d-flex align-items-center shadow-sm">
                <i class="bi bi-shield-check text-primary fs-1 me-3"></i>
                <div>
                    <div class="fw-bold small">100% Original</div>
                    <small class="text-muted">Garansi resmi distributor</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="p-3 bg-white rounded-3 border d-flex align-items-center shadow-sm">
                <i class="bi bi-truck text-success fs-1 me-3"></i>
                <div>
                    <div class="fw-bold small">Gratis Ongkir</div>
                    <small class="text-muted">Ke seluruh kota Indonesia</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="p-3 bg-white rounded-3 border d-flex align-items-center shadow-sm">
                <i class="bi bi-credit-card-2-front text-warning fs-1 me-3"></i>
                <div>
                    <div class="fw-bold small">Pembayaran Aman</div>
                    <small class="text-muted">Transfer bank terverifikasi</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="p-3 bg-white rounded-3 border d-flex align-items-center shadow-sm">
                <i class="bi bi-headset text-danger fs-1 me-3"></i>
                <div>
                    <div class="fw-bold small">Bantuan 24/7</div>
                    <small class="text-muted">Respon cepat customer care</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Kategori Pilihan -->
    <div class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h4 class="fw-bold mb-1">Kategori Produk Unggulan</h4>
                <p class="text-muted small mb-0">Temukan perangkat elektronik sesuai kebutuhan harian & profesional Anda</p>
            </div>
            <a href="<?= base_url('products') ?>" class="btn btn-sm btn-outline-primary rounded-pill">
                Lihat Semua <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>

        <div class="row g-3">
            <?php foreach ($categories as $cat): ?>
                <div class="col-6 col-md-4 col-lg-2-4" style="flex: 0 0 20%; max-width: 20%;">
                    <a href="<?= base_url('products?category=' . $cat['id']) ?>" class="card h-100 border text-center p-3 text-decoration-none bg-white rounded-3 shadow-sm hover-shadow">
                        <div class="d-inline-flex align-items-center justify-content-center bg-primary-subtle text-primary rounded-circle mx-auto mb-2" style="width: 52px; height: 52px;">
                            <i class="bi <?= esc($cat['icon']) ?> fs-4"></i>
                        </div>
                        <h6 class="fw-bold text-dark small mb-1"><?= esc($cat['name']) ?></h6>
                        <small class="text-muted" style="font-size: 11px;"><?= $cat['product_count'] ?> Produk</small>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Produk Terbaru -->
    <div class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h4 class="fw-bold mb-1">Produk Terbaru & Paling Dicari</h4>
                <p class="text-muted small mb-0">Koleksi gadget mutakhir dengan spesifikasi terbaik</p>
            </div>
            <a href="<?= base_url('products') ?>" class="btn btn-sm btn-outline-primary rounded-pill">
                Katalog Lengkap <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>

        <div class="row g-3">
            <?php foreach ($latestProducts as $prod): ?>
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="card card-product h-100 d-flex flex-column">
                        <div class="position-relative p-3 text-center bg-light">
                            <span class="badge bg-primary position-absolute top-0 start-0 m-2" style="font-size: 10px;">
                                <?= esc($prod['category_name']) ?>
                            </span>
                            <img src="<?= base_url('uploads/products/' . $prod['image']) ?>" alt="<?= esc($prod['name']) ?>" class="img-fluid" style="height: 180px; object-fit: contain;">
                        </div>
                        <div class="card-body p-3 d-flex flex-column">
                            <h6 class="card-title fw-bold text-dark mb-1 text-truncate" title="<?= esc($prod['name']) ?>">
                                <a href="<?= base_url('products/' . $prod['slug']) ?>" class="text-dark text-decoration-none">
                                    <?= esc($prod['name']) ?>
                                </a>
                            </h6>
                            <p class="text-muted small mb-2 text-truncate-2" style="height: 38px; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; font-size: 12px;">
                                <?= esc($prod['description']) ?>
                            </p>
                            <div class="mt-auto">
                                <div class="d-flex justify-content-between align-items-baseline mb-2">
                                    <span class="text-primary fw-extrabold fs-6">
                                        Rp <?= number_format($prod['price'], 0, ',', '.') ?>
                                    </span>
                                    <small class="text-muted" style="font-size: 11px;">Stok: <?= $prod['stock'] ?></small>
                                </div>

                                <div class="row g-1">
                                    <div class="col-4">
                                        <a href="<?= base_url('products/' . $prod['slug']) ?>" class="btn btn-outline-secondary btn-sm w-100 rounded-pill" title="Lihat Spesifikasi">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </div>
                                    <div class="col-8">
                                        <form action="<?= base_url('cart/add') ?>" method="POST">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="product_id" value="<?= $prod['id'] ?>">
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" class="btn btn-primary btn-sm w-100 rounded-pill fw-semibold" <?= $prod['stock'] <= 0 ? 'disabled' : '' ?>>
                                                <i class="bi bi-cart-plus me-1"></i> Beli
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Showcase Gaming Gear -->
    <div class="card border-0 rounded-4 p-4 p-md-5 mb-5 text-white" style="background: linear-gradient(135deg, #111827 0%, #1e1b4b 50%, #312e81 100%);">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <span class="badge bg-warning text-dark px-3 py-1 rounded-pill mb-2 fw-bold">PRO GAMER CHOICE</span>
                <h2 class="fw-bold mb-3">Zona Gaming Eksklusif ShopEase</h2>
                <p class="text-light-50 lead fs-6 mb-4">Lengkapi setup meja kerja dan arena bermain Anda dengan konsol PS5 Slim, mouse superlight ultra-ringan 60 gram, serta keyboard mechanical custom RGB hot-swappable bergaransi resmi.</p>
                <a href="<?= base_url('products?category=4') ?>" class="btn btn-warning btn-lg rounded-pill px-4 fw-bold">
                    <i class="bi bi-controller me-1"></i> Beli Gear Gaming
                </a>
            </div>
            <div class="col-lg-6">
                <div class="row g-2">
                    <?php foreach ($featuredGaming as $g): ?>
                        <div class="col-6">
                            <div class="card bg-white text-dark rounded-3 p-2 h-100 border-0 shadow">
                                <img src="<?= base_url('uploads/products/' . $g['image']) ?>" alt="<?= esc($g['name']) ?>" class="img-fluid rounded mb-2" style="height: 100px; object-fit: contain;">
                                <div class="fw-bold small text-truncate"><?= esc($g['name']) ?></div>
                                <div class="text-primary fw-bold small">Rp <?= number_format($g['price'], 0, ',', '.') ?></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

</div>
<?= $this->endSection() ?>

