<div class="container-fluid">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0 font-weight-bold">
                <i class="fas fa-receipt mr-2"></i>Detail Pesanan — No. Invoice: #INV-<?php echo $invoice->id ?>
            </h5>
            <span class="badge badge-light text-primary px-3 py-1">Pemesan: <?php echo htmlspecialchars($invoice->nama) ?></span>
        </div>
        <div class="card-body">

            <div class="row mb-3">
                <div class="col-md-6">
                    <p class="mb-1"><strong>Nama Penerima:</strong> <?php echo htmlspecialchars($invoice->nama); ?></p>
                    <p class="mb-1"><strong>Alamat Pengiriman:</strong> <?php echo htmlspecialchars($invoice->alamat); ?></p>
                    <p class="mb-1"><strong>No. Telepon:</strong> <?php echo htmlspecialchars($invoice->no_telp ?: '-'); ?></p>
                </div>
                <div class="col-md-6 text-md-right">
                    <p class="mb-1"><strong>Jasa Pengiriman:</strong> <?php echo htmlspecialchars($invoice->jasa_pengiriman ?: 'JNE'); ?></p>
                    <p class="mb-1"><strong>Metode Pembayaran:</strong> <?php echo htmlspecialchars($invoice->bank ?: '-'); ?></p>
                    <p class="mb-1"><strong>Tanggal Pesan:</strong> <?php echo $invoice->tgl_pesan; ?></p>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th>ID Barang</th>
                            <th>Nama Produk</th>
                            <th class="text-center">Jumlah Pesanan</th>
                            <th class="text-right">Harga Satuan</th>
                            <th class="text-right">Sub-Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $total = 0;
                        foreach ($pesanan as $psn) :
                            $subtotal = $psn->jumlah * $psn->harga;
                            $total += $subtotal;
                        ?>
                            <tr>
                                <td>#<?php echo $psn->id_brg ?></td>
                                <td class="font-weight-bold"><?php echo htmlspecialchars($psn->nama_brg) ?></td>
                                <td class="text-center"><?php echo $psn->jumlah ?></td>
                                <td class="text-right">Rp <?php echo number_format($psn->harga, 0, ',', '.') ?></td>
                                <td class="text-right font-weight-bold">Rp <?php echo number_format($subtotal, 0, ',', '.') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" class="text-right font-weight-bold">Grand Total Transaksi:</td>
                            <td class="text-right font-weight-bold bg-success text-white">
                                Rp <?php echo number_format($total, 0, ',', '.') ?>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <a href="<?php echo base_url('admin/invoice/index') ?>" class="btn btn-secondary mt-2">
                <i class="fas fa-arrow-left mr-1"></i> Kembali ke Daftar Invoice
            </a>

        </div>
    </div>
</div>

