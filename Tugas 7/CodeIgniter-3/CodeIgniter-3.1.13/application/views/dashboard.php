<div class="container-fluid">

    <?php if ($this->session->flashdata('notif_cart')) : ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle mr-1"></i> <?php echo $this->session->flashdata('notif_cart'); ?>
            <a href="<?php echo base_url('dashboard/detail_keranjang'); ?>" class="alert-link ml-2">Lihat Keranjang &rarr;</a>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <!-- Hero Banner -->
    <div class="hero-banner p-4 p-md-5 mb-4 shadow-sm">
        <div class="row align-items-center">
            <div class="col-md-8">
                <span class="badge badge-warning text-dark px-3 py-1 mb-2 font-weight-bold">GARANSI RESMI 100%</span>
                <h2 class="font-weight-bold mb-2">ElectroStore — Pusat Gadget & Elektronik Terbaik</h2>
                <p class="mb-3 text-light" style="opacity: 0.9;">
                    Temukan koleksi Laptop, Smartphone Flagship, Aksesoris Audio, hingga Konsol Gaming terbaru dengan harga terbaik dan pengiriman cepat ke seluruh Indonesia.
                </p>
                <a href="<?php echo base_url('kategori/laptop'); ?>" class="btn btn-light btn-sm font-weight-bold text-primary mr-2">
                    <i class="fas fa-laptop mr-1"></i> Eksplor Laptop
                </a>
                <a href="<?php echo base_url('kategori/gaming'); ?>" class="btn btn-outline-light btn-sm font-weight-bold">
                    <i class="fas fa-gamepad mr-1"></i> Promo Gaming
                </a>
            </div>
            <div class="col-md-4 text-center d-none d-md-block">
                <i class="fas fa-microchip fa-6x text-white" style="opacity: 0.25;"></i>
            </div>
        </div>
    </div>

    <!-- Heading & Category Quick Filter -->
    <div class="d-sm-flex align-items-center justify-content-between mb-3">
        <h5 class="mb-0 text-gray-800 font-weight-bold">
            <i class="fas fa-th-large text-primary mr-2"></i><?php echo isset($judul_kategori) ? $judul_kategori : 'Katalog Produk'; ?>
        </h5>
        <div class="mt-2 mt-sm-0">
            <a href="<?php echo base_url('welcome'); ?>" class="btn btn-sm <?php echo ($this->uri->segment(1) == '' || $this->uri->segment(1) == 'welcome') ? 'btn-primary' : 'btn-outline-primary'; ?> mr-1">Semua</a>
            <a href="<?php echo base_url('kategori/laptop'); ?>" class="btn btn-sm <?php echo ($this->uri->segment(2) == 'laptop') ? 'btn-primary' : 'btn-outline-primary'; ?> mr-1">Laptop</a>
            <a href="<?php echo base_url('kategori/smartphone'); ?>" class="btn btn-sm <?php echo ($this->uri->segment(2) == 'smartphone') ? 'btn-primary' : 'btn-outline-primary'; ?> mr-1">Smartphone</a>
            <a href="<?php echo base_url('kategori/aksesoris'); ?>" class="btn btn-sm <?php echo ($this->uri->segment(2) == 'aksesoris') ? 'btn-primary' : 'btn-outline-primary'; ?> mr-1">Aksesoris</a>
            <a href="<?php echo base_url('kategori/gaming'); ?>" class="btn btn-sm <?php echo ($this->uri->segment(2) == 'gaming') ? 'btn-primary' : 'btn-outline-primary'; ?>">Gaming</a>
        </div>
    </div>

    <!-- Product Grid -->
    <div class="row">
        <?php if (empty($barang)) : ?>
            <div class="col-12">
                <div class="alert alert-info text-center py-4">
                    <i class="fas fa-box-open fa-2x mb-2 d-block"></i>
                    Belum ada produk pada kategori atau pencarian ini.
                </div>
            </div>
        <?php else : ?>
            <?php foreach ($barang as $brg) : ?>
                <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                    <div class="card product-card h-100 shadow-sm border-0">
                        <div class="product-img-wrapper">
                            <img src="<?php echo base_url() . 'uploads/' . $brg->gambar ?>" alt="<?php echo htmlspecialchars($brg->nama_brg) ?>">
                        </div>
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="badge badge-info"><?php echo htmlspecialchars($brg->kategori) ?></span>
                                <small class="text-muted">Stok: <strong><?php echo (int)$brg->stok ?></strong></small>
                            </div>
                            <h6 class="card-title font-weight-bold text-gray-900 mb-1">
                                <?php echo htmlspecialchars($brg->nama_brg) ?>
                            </h6>
                            <p class="card-text text-muted small mb-3 flex-grow-1">
                                <?php echo htmlspecialchars($brg->keterangan) ?>
                            </p>
                            <div class="mb-3">
                                <span class="badge badge-success px-3 py-2" style="font-size: 0.9rem;">
                                    Rp <?php echo number_format($brg->harga, 0, ',', '.') ?>
                                </span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <?php if ($brg->stok > 0) : ?>
                                    <?php echo anchor('dashboard/tambah_ke_keranjang/' . $brg->id_brg, '<i class="fas fa-cart-plus mr-1"></i>+ Keranjang', array('class' => 'btn btn-sm btn-primary flex-grow-1 mr-2')) ?>
                                <?php else : ?>
                                    <button class="btn btn-sm btn-secondary flex-grow-1 mr-2" disabled>Stok Habis</button>
                                <?php endif; ?>
                                <?php echo anchor('dashboard/detail/' . $brg->id_brg, 'Detail', array('class' => 'btn btn-sm btn-outline-success')) ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

</div>

