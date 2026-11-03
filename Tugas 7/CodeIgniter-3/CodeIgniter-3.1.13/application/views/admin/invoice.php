<div class="container-fluid">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <h5 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-file-invoice-dollar mr-2"></i>Daftar Invoice Pemesanan Produk
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th>ID Invoice</th>
                            <th>Nama Pemesan</th>
                            <th>Alamat Pengiriman</th>
                            <th>No. Telp</th>
                            <th>Kurir & Bank</th>
                            <th>Tanggal Pemesanan</th>
                            <th>Batas Pembayaran</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($invoice)) : ?>
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">Belum ada data invoice pemesanan.</td>
                            </tr>
                        <?php else : ?>
                            <?php foreach ($invoice as $inv) : ?>
                                <tr>
                                    <td class="font-weight-bold">#INV-<?php echo $inv->id ?></td>
                                    <td><?php echo htmlspecialchars($inv->nama) ?></td>
                                    <td><?php echo htmlspecialchars($inv->alamat) ?></td>
                                    <td><?php echo htmlspecialchars($inv->no_telp ?: '-') ?></td>
                                    <td>
                                        <span class="badge badge-info"><?php echo htmlspecialchars($inv->jasa_pengiriman ?: 'JNE') ?></span>
                                        <small class="d-block text-muted"><?php echo htmlspecialchars($inv->bank ?: '-') ?></small>
                                    </td>
                                    <td><?php echo $inv->tgl_pesan ?></td>
                                    <td><?php echo $inv->batas_bayar ?></td>
                                    <td class="text-center">
                                        <?php echo anchor('admin/invoice/detail/' . $inv->id, '<div class="btn btn-sm btn-primary"><i class="fas fa-eye mr-1"></i>Detail</div>') ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

