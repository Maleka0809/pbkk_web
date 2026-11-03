<div class="container-fluid">
    <div class="card shadow-sm border-0 col-lg-8 mx-auto">
        <div class="card-header bg-primary text-white font-weight-bold">
            <i class="fas fa-edit mr-2"></i>Edit Data Barang
        </div>
        <div class="card-body">

            <?php foreach ($barang as $brg) : ?>
                <form method="post" action="<?php echo base_url() . 'admin/data_barang/update' ?>" enctype="multipart/form-data">

                    <input type="hidden" name="id_brg" value="<?php echo $brg->id_brg ?>">

                    <div class="form-group">
                        <label class="font-weight-bold">Nama Barang</label>
                        <input type="text" name="nama_brg" class="form-control" value="<?php echo htmlspecialchars($brg->nama_brg) ?>" required>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Keterangan / Spesifikasi</label>
                        <input type="text" name="keterangan" class="form-control" value="<?php echo htmlspecialchars($brg->keterangan) ?>" required>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label class="font-weight-bold">Kategori</label>
                            <select class="form-control" name="kategori">
                                <?php
                                $kategori_list = array('Laptop', 'Smartphone', 'Aksesoris', 'Gaming');
                                foreach ($kategori_list as $kat) :
                                ?>
                                    <option value="<?php echo $kat; ?>" <?php echo ($brg->kategori == $kat) ? 'selected' : ''; ?>>
                                        <?php echo $kat; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group col-md-4">
                            <label class="font-weight-bold">Harga (Rp)</label>
                            <input type="number" name="harga" class="form-control" value="<?php echo $brg->harga ?>" required>
                        </div>

                        <div class="form-group col-md-4">
                            <label class="font-weight-bold">Stok</label>
                            <input type="number" name="stok" class="form-control" value="<?php echo $brg->stok ?>" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold d-block">Gambar Saat Ini</label>
                        <img src="<?php echo base_url('uploads/' . $brg->gambar); ?>" width="140" class="rounded shadow-sm mb-2">
                        <input type="file" name="gambar" class="form-control-file">
                        <small class="text-muted">Biarkan kosong jika tidak ingin mengganti gambar.</small>
                    </div>

                    <hr>
                    <div class="d-flex justify-content-between">
                        <a href="<?php echo base_url('admin/data_barang'); ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left mr-1"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-primary font-weight-bold px-4">
                            <i class="fas fa-save mr-1"></i> Simpan Perubahan
                        </button>
                    </div>

                </form>
            <?php endforeach; ?>

        </div>
    </div>
</div>

