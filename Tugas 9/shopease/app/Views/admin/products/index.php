<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="card border-0 bg-white rounded-4 shadow-sm p-4">
    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <div>
            <h5 class="fw-bold text-dark mb-1"><i class="bi bi-box-seam text-primary me-2"></i> Master Data Produk & Stok</h5>
            <small class="text-muted">Kelola inventaris, harga jual, kuantitas stok, dan unggah foto produk</small>
        </div>
        <button type="button" class="btn btn-primary btn-sm rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#modalTambahProduk">
            <i class="bi bi-plus-circle me-1"></i> Tambah Produk Baru
        </button>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light small">
                <tr>
                    <th style="width: 50px;">Foto</th>
                    <th>Nama Produk</th>
                    <th>Kategori</th>
                    <th>Harga Satuan</th>
                    <th class="text-center">Stok</th>
                    <th class="text-center">Status</th>
                    <th class="text-center" style="width: 120px;">Aksi</th>
                </tr>
            </thead>
            <tbody class="small">
                <?php foreach ($products as $prod): ?>
                    <tr>
                        <td>
                            <img src="<?= base_url('uploads/products/' . $prod['image']) ?>" alt="<?= esc($prod['name']) ?>" width="46" height="46" class="rounded border p-1 bg-light" style="object-fit: contain;">
                        </td>
                        <td>
                            <div class="fw-bold text-dark"><?= esc($prod['name']) ?></div>
                            <small class="text-muted"><code><?= esc($prod['slug']) ?></code></small>
                        </td>
                        <td>
                            <span class="badge bg-primary-subtle text-primary"><?= esc($prod['category_name']) ?></span>
                        </td>
                        <td class="fw-bold text-primary">
                            Rp <?= number_format($prod['price'], 0, ',', '.') ?>
                        </td>
                        <td class="text-center">
                            <span class="badge <?= $prod['stock'] > 5 ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' ?> fw-bold px-2 py-1">
                                <?= $prod['stock'] ?> unit
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="badge <?= $prod['status'] === 'active' ? 'bg-success' : 'bg-secondary' ?> text-uppercase" style="font-size: 10px;">
                                <?= $prod['status'] ?>
                            </span>
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm btn-outline-warning border-0" data-bs-toggle="modal" data-bs-target="#modalEditProd_<?= $prod['id'] ?>" title="Edit">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                            <a href="<?= base_url('admin/products/delete/' . $prod['id']) ?>" class="btn btn-sm btn-outline-danger border-0" onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini?')" title="Hapus">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>

                    <!-- Modal Edit Produk -->
                    <div class="modal fade" id="modalEditProd_<?= $prod['id'] ?>" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content rounded-4 border-0">
                                <form action="<?= base_url('admin/products/update/' . $prod['id']) ?>" method="POST" enctype="multipart/form-data">
                                    <?= csrf_field() ?>
                                    <div class="modal-header border-bottom">
                                        <h6 class="modal-title fw-bold">Edit Produk #<?= $prod['id'] ?> - <?= esc($prod['name']) ?></h6>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label small fw-semibold">Nama Produk</label>
                                                <input type="text" name="name" class="form-control" value="<?= esc($prod['name']) ?>" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label small fw-semibold">Slug URL</label>
                                                <input type="text" name="slug" class="form-control" value="<?= esc($prod['slug']) ?>" required>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label small fw-semibold">Kategori</label>
                                                <select name="category_id" class="form-select" required>
                                                    <?php foreach ($categories as $c): ?>
                                                        <option value="<?= $c['id'] ?>" <?= $c['id'] == $prod['category_id'] ? 'selected' : '' ?>>
                                                            <?= esc($c['name']) ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label small fw-semibold">Harga Jual (Rp)</label>
                                                <input type="number" name="price" class="form-control" value="<?= $prod['price'] ?>" required>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label small fw-semibold">Jumlah Stok</label>
                                                <input type="number" name="stock" class="form-control" value="<?= $prod['stock'] ?>" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label small fw-semibold">Status Produk</label>
                                                <select name="status" class="form-select">
                                                    <option value="active" <?= $prod['status'] === 'active' ? 'selected' : '' ?>>Active (Tampil di Toko)</option>
                                                    <option value="inactive" <?= $prod['status'] === 'inactive' ? 'selected' : '' ?>>Inactive (Sembunyikan)</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label small fw-semibold">Ubah Foto Produk (Opsional)</label>
                                                <input type="file" name="image" class="form-control" accept="image/*">
                                                <small class="text-muted" style="font-size: 11px;">Biarkan kosong jika tidak ingin mengubah foto saat ini.</small>
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label small fw-semibold">Deskripsi & Spesifikasi Produk</label>
                                                <textarea name="description" class="form-control" rows="3"><?= esc($prod['description']) ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer border-top">
                                        <button type="button" class="btn btn-light btn-sm rounded-pill" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary btn-sm rounded-pill fw-bold">Simpan Perubahan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah Produk -->
<div class="modal fade" id="modalTambahProduk" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content rounded-4 border-0">
            <form action="<?= base_url('admin/products/store') ?>" method="POST" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="modal-header border-bottom">
                    <h6 class="modal-title fw-bold"><i class="bi bi-plus-circle text-primary me-2"></i> Tambah Produk Baru</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Nama Produk</label>
                            <input type="text" name="name" class="form-control" placeholder="Contoh: iPad Air M2 11 Inch" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Slug URL</label>
                            <input type="text" name="slug" class="form-control" placeholder="Contoh: ipad-air-m2-11-inch" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Kategori</label>
                            <select name="category_id" class="form-select" required>
                                <option value="">-- Pilih Kategori --</option>
                                <?php foreach ($categories as $c): ?>
                                    <option value="<?= $c['id'] ?>"><?= esc($c['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Harga Jual (Rp)</label>
                            <input type="number" name="price" class="form-control" placeholder="10999000" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Stok Awal</label>
                            <input type="number" name="stock" class="form-control" value="10" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Status Awal</label>
                            <select name="status" class="form-select">
                                <option value="active" selected>Active (Tampil di Toko)</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Foto Produk</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Deskripsi & Spesifikasi</label>
                            <textarea name="description" class="form-control" rows="3" placeholder="Informasi spesifikasi teknis prosesor, RAM, garansi..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-light btn-sm rounded-pill" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm rounded-pill fw-bold">Simpan Produk</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

