<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="card border-0 bg-white rounded-4 shadow-sm p-4">
    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <div>
            <h5 class="fw-bold text-dark mb-1"><i class="bi bi-tags text-warning me-2"></i> Master Data Kategori</h5>
            <small class="text-muted">Kelola pengelompokan produk toko online ShopEase</small>
        </div>
        <button type="button" class="btn btn-primary btn-sm rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#modalTambahKategori">
            <i class="bi bi-plus-circle me-1"></i> Tambah Kategori
        </button>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light small">
                <tr>
                    <th style="width: 50px;">ID</th>
                    <th>Nama Kategori</th>
                    <th>Slug URL</th>
                    <th>Ikon</th>
                    <th>Deskripsi</th>
                    <th class="text-center">Jumlah Produk</th>
                    <th class="text-center" style="width: 130px;">Aksi</th>
                </tr>
            </thead>
            <tbody class="small">
                <?php foreach ($categories as $cat): ?>
                    <tr>
                        <td class="fw-bold">#<?= $cat['id'] ?></td>
                        <td class="fw-bold text-dark"><?= esc($cat['name']) ?></td>
                        <td><code><?= esc($cat['slug']) ?></code></td>
                        <td><i class="bi <?= esc($cat['icon']) ?> fs-5 text-primary"></i> <small class="text-muted ms-1"><?= esc($cat['icon']) ?></small></td>
                        <td class="text-muted"><?= esc($cat['description']) ?></td>
                        <td class="text-center">
                            <span class="badge bg-light text-dark border px-2 py-1"><?= $cat['product_count'] ?> item</span>
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm btn-outline-warning border-0" data-bs-toggle="modal" data-bs-target="#modalEdit_<?= $cat['id'] ?>" title="Edit">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                            <a href="<?= base_url('admin/categories/delete/' . $cat['id']) ?>" class="btn btn-sm btn-outline-danger border-0" onclick="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')" title="Hapus">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>

                    <!-- Modal Edit Kategori -->
                    <div class="modal fade" id="modalEdit_<?= $cat['id'] ?>" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content rounded-4 border-0">
                                <form action="<?= base_url('admin/categories/update/' . $cat['id']) ?>" method="POST">
                                    <?= csrf_field() ?>
                                    <div class="modal-header border-bottom">
                                        <h6 class="modal-title fw-bold">Edit Kategori #<?= $cat['id'] ?></h6>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label small fw-semibold">Nama Kategori</label>
                                            <input type="text" name="name" class="form-control" value="<?= esc($cat['name']) ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label small fw-semibold">Slug URL</label>
                                            <input type="text" name="slug" class="form-control" value="<?= esc($cat['slug']) ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label small fw-semibold">Ikon Bootstrap</label>
                                            <input type="text" name="icon" class="form-control" value="<?= esc($cat['icon']) ?>" placeholder="bi-laptop">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label small fw-semibold">Deskripsi</label>
                                            <textarea name="description" class="form-control" rows="2"><?= esc($cat['description']) ?></textarea>
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

<!-- Modal Tambah Kategori -->
<div class="modal fade" id="modalTambahKategori" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content rounded-4 border-0">
            <form action="<?= base_url('admin/categories/store') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="modal-header border-bottom">
                    <h6 class="modal-title fw-bold"><i class="bi bi-plus-circle text-primary me-2"></i> Tambah Kategori Baru</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Nama Kategori</label>
                        <input type="text" name="name" class="form-control" placeholder="Contoh: Drone & Kamera" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Slug URL</label>
                        <input type="text" name="slug" class="form-control" placeholder="Contoh: drone-kamera" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Ikon Bootstrap</label>
                        <input type="text" name="icon" class="form-control" placeholder="bi-camera" value="bi-box-seam">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Deskripsi Kategori</label>
                        <textarea name="description" class="form-control" rows="2" placeholder="Deskripsi singkat produk pada kategori ini"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-light btn-sm rounded-pill" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm rounded-pill fw-bold">Tambah Kategori</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

