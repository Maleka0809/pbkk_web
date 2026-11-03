<div class="container-fluid">

    <?php echo $this->session->flashdata('pesan_admin'); ?>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="font-weight-bold text-gray-800 mb-0">
            <i class="fas fa-boxes text-primary mr-2"></i>Data Barang Elektronik & Gadget
        </h4>
        <button class="btn btn-primary shadow-sm" data-toggle="modal" data-target="#tambah_barang">
            <i class="fas fa-plus fa-sm mr-1"></i> Tambah Barang Baru
        </button>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th width="5%" class="text-center">No</th>
                            <th width="10%" class="text-center">Gambar</th>
                            <th>Nama Barang</th>
                            <th>Keterangan</th>
                            <th>Kategori</th>
                            <th>Harga</th>
                            <th class="text-center">Stok</th>
                            <th colspan="3" class="text-center" width="15%">Aksi CRUD</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach ($barang as $brg) : ?>
                            <tr>
                                <td class="text-center align-middle"><?php echo $no++ ?></td>
                                <td class="text-center align-middle">
                                    <img src="<?php echo base_url() . 'uploads/' . $brg->gambar ?>" width="65" class="rounded shadow-sm" alt="thumb">
                                </td>
                                <td class="align-middle font-weight-bold"><?php echo htmlspecialchars($brg->nama_brg) ?></td>
                                <td class="align-middle small"><?php echo htmlspecialchars($brg->keterangan) ?></td>
                                <td class="align-middle"><span class="badge badge-info"><?php echo htmlspecialchars($brg->kategori) ?></span></td>
                                <td class="align-middle font-weight-bold text-success">Rp <?php echo number_format($brg->harga, 0, ',', '.') ?></td>
                                <td class="text-center align-middle"><?php echo (int)$brg->stok ?></td>
                                <td class="text-center align-middle">
                                    <?php echo anchor('admin/data_barang/detail/' . $brg->id_brg, '<div class="btn btn-success btn-sm" title="Detail"><i class="fas fa-search-plus"></i></div>') ?>
                                </td>
                                <td class="text-center align-middle">
                                    <?php echo anchor('admin/data_barang/edit/' . $brg->id_brg, '<div class="btn btn-primary btn-sm" title="Edit"><i class="fa fa-edit"></i></div>') ?>
                                </td>
                                <td class="text-center align-middle">
                                    <?php echo anchor('admin/data_barang/hapus/' . $brg->id_brg, '<div class="btn btn-danger btn-sm" title="Hapus" onclick="return confirm(\'Yakin ingin menghapus produk ini?\')"><i class="fa fa-trash"></i></div>') ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<!-- Modal Tambah Barang -->
<div class="modal fade" id="tambah_barang" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title font-weight-bold" id="exampleModalLabel">
                    <i class="fas fa-plus-circle mr-1"></i> Form Input Produk Baru
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?php echo base_url() . 'admin/data_barang/tambah_aksi'; ?>" method="post" enctype="multipart/form-data">
                <div class="modal-body">

                    <div class="form-group">
                        <label class="font-weight-bold">Nama Barang</label>
                        <input type="text" name="nama_brg" class="form-control" placeholder="Contoh: ASUS ROG Strix G16" required>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Keterangan / Spesifikasi Singkat</label>
                        <input type="text" name="keterangan" class="form-control" placeholder="Contoh: Intel Core i9, RTX 4070, RAM 32GB, SSD 1TB" required>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label class="font-weight-bold">Kategori</label>
                            <select class="form-control" name="kategori" required>
                                <option value="Laptop">Laptop</option>
                                <option value="Smartphone">Smartphone</option>
                                <option value="Aksesoris">Aksesoris</option>
                                <option value="Gaming">Gaming</option>
                            </select>
                        </div>

                        <div class="form-group col-md-4">
                            <label class="font-weight-bold">Harga (Rp)</label>
                            <input type="number" name="harga" class="form-control" placeholder="Contoh: 15000000" required>
                        </div>

                        <div class="form-group col-md-4">
                            <label class="font-weight-bold">Stok Awal</label>
                            <input type="number" name="stok" class="form-control" placeholder="Contoh: 10" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Gambar Produk <small class="text-muted">(JPG/PNG/SVG/WEBP, opsional)</small></label>
                        <input type="file" name="gambar" class="form-control-file">
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary font-weight-bold">
                        <i class="fas fa-save mr-1"></i> Simpan Barang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

