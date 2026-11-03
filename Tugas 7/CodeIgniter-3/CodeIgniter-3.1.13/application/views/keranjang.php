<div class="container-fluid">

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-shopping-cart mr-2"></i>Keranjang Belanja Anda
            </h5>
            <span class="badge badge-primary px-3 py-2"><?php echo $this->cart->total_items(); ?> Item</span>
        </div>
        <div class="card-body">

            <?php if ($this->cart->total_items() == 0) : ?>
                <div class="text-center py-5">
                    <i class="fas fa-shopping-basket fa-4x text-gray-300 mb-3"></i>
                    <h5 class="text-gray-700">Keranjang Belanja Anda Masih Kosong</h5>
                    <p class="text-muted mb-4">Yuk pilih produk elektronik dan gadget favoritmu di katalog terlebih dahulu!</p>
                    <a href="<?php echo base_url('welcome') ?>" class="btn btn-primary">
                        <i class="fas fa-store mr-1"></i> Mulai Belanja
                    </a>
                </div>
            <?php else : ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th width="5%" class="text-center">No</th>
                                <th>Nama Produk</th>
                                <th width="12%" class="text-center">Jumlah</th>
                                <th width="20%" class="text-right">Harga Satuan</th>
                                <th width="20%" class="text-right">Sub-Total</th>
                                <th width="10%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; foreach ($this->cart->contents() as $items) : ?>
                                <tr>
                                    <td class="text-center"><?php echo $no++ ?></td>
                                    <td class="font-weight-bold"><?php echo htmlspecialchars($items['name']) ?></td>
                                    <td class="text-center"><?php echo $items['qty'] ?></td>
                                    <td class="text-right">Rp <?php echo number_format($items['price'], 0, ',', '.') ?></td>
                                    <td class="text-right font-weight-bold">Rp <?php echo number_format($items['subtotal'], 0, ',', '.') ?></td>
                                    <td class="text-center">
                                        <a href="<?php echo base_url('dashboard/hapus_item/' . $items['rowid']) ?>" class="btn btn-sm btn-outline-danger" title="Hapus item">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" class="text-right font-weight-bold">Grand Total Belanja:</td>
                                <td class="text-right font-weight-bold bg-success text-white">
                                    Rp <?php echo number_format($this->cart->total(), 0, ',', '.') ?>
                                </td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="d-flex justify-content-end mt-3">
                    <a href="<?php echo base_url('dashboard/hapus_keranjang') ?>" class="btn btn-danger mr-2" onclick="return confirm('Kosongkan seluruh keranjang belanja?')">
                        <i class="fas fa-trash-alt mr-1"></i> Kosongkan Keranjang
                    </a>
                    <a href="<?php echo base_url('welcome') ?>" class="btn btn-primary mr-2">
                        <i class="fas fa-shopping-bag mr-1"></i> Lanjutkan Belanja
                    </a>
                    <a href="<?php echo base_url('dashboard/pembayaran') ?>" class="btn btn-success font-weight-bold">
                        <i class="fas fa-credit-card mr-1"></i> Checkout / Pembayaran
                    </a>
                </div>
            <?php endif; ?>

        </div>
    </div>

</div>

