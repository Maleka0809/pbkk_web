<div class="container-fluid">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white font-weight-bold">
            <i class="fas fa-info-circle mr-2"></i>Detail Barang (Mode Admin)
        </div>
        <div class="card-body">
            <?php if ($barang) : ?>
                <?php foreach ($barang as $brg) : ?>
                    <div class="row align-items-center">
                        <div class="col-md-4 text-center mb-3 mb-md-0">
                            <img src="<?php echo base_url() . 'uploads/' . $brg->gambar ?>" class="img-fluid rounded shadow-sm">
                        </div>
                        <div class="col-md-8">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="30%" class="bg-light">ID Barang</th>
                                    <td>#<?php echo $brg->id_brg ?></td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Nama Produk</th>
                                    <td class="font-weight-bold"><?php echo htmlspecialchars($brg->nama_brg) ?></td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Keterangan</th>
                                    <td><?php echo htmlspecialchars($brg->keterangan) ?></td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Kategori</th>
                                    <td><span class="badge badge-info"><?php echo htmlspecialchars($brg->kategori) ?></span></td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Stok</th>
                                    <td><strong><?php echo (int)$brg->stok ?> unit</strong></td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Harga</th>
                                    <td><span class="badge badge-success px-3 py-1">Rp <?php echo number_format($brg->harga, 0, ',', '.') ?></span></td>
                                </tr>
                            </table>
                            <a href="<?php echo base_url('admin/data_barang/edit/' . $brg->id_brg); ?>" class="btn btn-primary mr-2">
                                <i class="fas fa-edit mr-1"></i> Edit Barang
                            </a>
                            <a href="<?php echo base_url('admin/data_barang'); ?>" class="btn btn-secondary">
                                <i class="fas fa-arrow-left mr-1"></i> Kembali
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

