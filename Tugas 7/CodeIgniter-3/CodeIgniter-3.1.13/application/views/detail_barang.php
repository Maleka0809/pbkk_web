<div class="container-fluid">

    <div class="card shadow-sm border-0">
        <h5 class="card-header bg-primary text-white font-weight-bold">
            <i class="fas fa-info-circle mr-2"></i>Detail Spesifikasi Produk
        </h5>
        <div class="card-body">

            <?php if ($barang) : ?>
                <?php foreach ($barang as $brg) : ?>
                    <div class="row align-items-center">
                        <div class="col-md-4 mb-3 mb-md-0 text-center">
                            <img src="<?php echo base_url() . 'uploads/' . $brg->gambar ?>" class="img-fluid rounded shadow-sm" alt="<?php echo htmlspecialchars($brg->nama_brg) ?>">
                        </div>
                        <div class="col-md-8">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="30%" class="bg-light">Nama Produk</th>
                                    <td><strong><?php echo htmlspecialchars($brg->nama_brg) ?></strong></td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Keterangan & Spesifikasi</th>
                                    <td><?php echo htmlspecialchars($brg->keterangan) ?></td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Kategori</th>
                                    <td><span class="badge badge-info px-2 py-1"><?php echo htmlspecialchars($brg->kategori) ?></span></td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Stok Tersedia</th>
                                    <td><strong><?php echo (int)$brg->stok ?> unit</strong></td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Harga</th>
                                    <td>
                                        <span class="badge badge-success px-3 py-2" style="font-size: 1rem;">
                                            Rp <?php echo number_format($brg->harga, 0, ',', '.') ?>
                                        </span>
                                    </td>
                                </tr>
                            </table>

                            <div class="mt-3">
                                <?php if ($brg->stok > 0) : ?>
                                    <?php echo anchor('dashboard/tambah_ke_keranjang/' . $brg->id_brg, '<i class="fas fa-cart-plus mr-1"></i>Tambah ke Keranjang', array('class' => 'btn btn-primary mr-2')) ?>
                                <?php endif; ?>
                                <?php echo anchor('welcome', '<i class="fas fa-arrow-left mr-1"></i>Kembali ke Katalog', array('class' => 'btn btn-secondary')) ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

        </div>
    </div>

</div>

