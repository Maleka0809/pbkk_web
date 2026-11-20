<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container">

    <!-- Breadcrumb & Header -->
    <nav class="mb-3 small">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url() ?>">Home</a></li>
            <li class="breadcrumb-item active">Katalog Produk</li>
            <?php if ($currentCategory): ?>
                <li class="breadcrumb-item active"><?= esc($currentCategory['name']) ?></li>
            <?php endif; ?>
        </ol>
    </nav>

    <div class="row g-4">
        <!-- Sidebar Filter -->
        <div class="col-lg-3">
            <div class="card border bg-white rounded-3 p-3 shadow-sm mb-4">
                <h6 class="fw-bold text-dark mb-3"><i class="bi bi-funnel me-1 text-primary"></i> Filter Kategori</h6>
                <div class="list-group list-group-flush small">
                    <a href="<?= base_url('products' . ($search ? '?search=' . urlencode($search) : '')) ?>" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center border-0 px-2 rounded-2 <?= empty($selectedCategory) ? 'active bg-primary text-white' : '' ?>">
                        <span><i class="bi bi-grid-fill me-2"></i> Semua Kategori</span>
                    </a>
                    <?php foreach ($categories as $cat): ?>
                        <a href="<?= base_url('products?category=' . $cat['id'] . ($search ? '&search=' . urlencode($search) : '')) ?>" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center border-0 px-2 rounded-2 <?= ($selectedCategory == $cat['id']) ? 'active bg-primary text-white' : '' ?>">
                            <span><i class="bi <?= esc($cat['icon']) ?> me-2"></i> <?= esc($cat['name']) ?></span>
                            <span class="badge <?= ($selectedCategory == $cat['id']) ? 'bg-light text-primary' : 'bg-secondary' ?> rounded-pill">
                                <?= $cat['product_count'] ?>
                            </span>
                        </a>
                    <?php endforeach; ?>
                </div>

                <hr class="my-3">

                <h6 class="fw-bold text-dark mb-2"><i class="bi bi-sort-down me-1 text-primary"></i> Urutkan Harga</h6>
                <form action="<?= base_url('products') ?>" method="GET">
                    <?php if ($selectedCategory): ?>
                        <input type="hidden" name="category" value="<?= esc($selectedCategory) ?>">
                    <?php endif; ?>
                    <?php if ($search): ?>
                        <input type="hidden" name="search" value="<?= esc($search) ?>">
                    <?php endif; ?>

                    <select name="sort" class="form-select form-select-sm mb-2" onchange="this.form.submit()">
                        <option value="latest" <?= $sort === 'latest' ? 'selected' : '' ?>>Produk Terbaru</option>
                        <option value="price_low" <?= $sort === 'price_low' ? 'selected' : '' ?>>Harga: Termurah &rarr; Termahal</option>
                        <option value="price_high" <?= $sort === 'price_high' ? 'selected' : '' ?>>Harga: Termahal &rarr; Termurah</option>
                        <option value="name_asc" <?= $sort === 'name_asc' ? 'selected' : '' ?>>Nama: A - Z</option>
                    </select>
                </form>

                <div class="bg-primary-subtle p-3 rounded-3 mt-3 text-center">
                    <i class="bi bi-whatsapp text-success fs-3 mb-1 d-block"></i>
                    <small class="fw-bold d-block text-dark">Butuh Bantuan Memilih?</small>
                    <small class="text-muted d-block mb-2" style="font-size: 11px;">Konsultasikan spesifikasi teknis bersama teknisi kami.</small>
                    <a href="https://wa.me/6281234567890" target="_blank" class="btn btn-sm btn-success rounded-pill px-3 fw-bold" style="font-size: 11px;">
                        Chat WhatsApp
                    </a>
                </div>
            </div>
        </div>

        <!-- Product Grid -->
        <div class="col-lg-9">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3 pb-2 border-bottom">
                <div>
                    <h5 class="fw-bold mb-1">
                        <?= $currentCategory ? esc($currentCategory['name']) : 'Semua Katalog Produk' ?>
                        <?php if ($search): ?>
                            <small class="text-muted fw-normal fs-6">Pencarian: "<?= esc($search) ?>"</small>
                        <?php endif; ?>
                    </h5>
                    <small class="text-muted">Menampilkan <strong><?= $totalResults ?></strong> produk berkualitas tinggi</small>
                </div>
            </div>

            <?php if (empty($products)): ?>
                <div class="card border-0 bg-white rounded-3 p-5 text-center shadow-sm">
                    <i class="bi bi-search text-muted fs-1 mb-3"></i>
                    <h5 class="fw-bold">Tidak ada produk yang cocok</h5>
                    <p class="text-muted small">Coba ubah kata kunci pencarian Anda atau pilih kategori lainnya.</p>
                    <div>
                        <a href="<?= base_url('products') ?>" class="btn btn-outline-primary rounded-pill px-4">
                            Reset Filter Pencarian
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <div class="row g-3">
                    <?php foreach ($products as $prod): ?>
                        <div class="col-6 col-md-4">
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
            <?php endif; ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

