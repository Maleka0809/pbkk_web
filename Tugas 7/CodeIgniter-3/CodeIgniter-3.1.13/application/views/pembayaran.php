<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-primary text-white font-weight-bold">
                    <i class="fas fa-truck mr-2"></i>Konfirmasi Alamat Pengiriman & Pembayaran
                </div>
                <div class="card-body">

                    <?php
                    $grand_total = 0;
                    if ($keranjang = $this->cart->contents()) {
                        foreach ($keranjang as $item) {
                            $grand_total = $grand_total + $item['subtotal'];
                        }
                    ?>
                        <div class="alert alert-success d-flex justify-content-between align-items-center mb-4">
                            <span><i class="fas fa-receipt mr-2"></i>Total Tagihan Belanja Anda:</span>
                            <h5 class="mb-0 font-weight-bold">Rp <?php echo number_format($grand_total, 0, ',', '.'); ?></h5>
                        </div>

                        <form method="post" action="<?php echo base_url('dashboard/proses_pesanan') ?>">

                            <div class="form-group">
                                <label class="font-weight-bold">Nama Lengkap Penerima</label>
                                <input type="text" name="nama" placeholder="Masukkan Nama Lengkap Anda" class="form-control" value="<?php echo htmlspecialchars($this->session->userdata('nama') ?: ''); ?>" required>
                            </div>

                            <div class="form-group">
                                <label class="font-weight-bold">Alamat Lengkap Pengiriman</label>
                                <textarea name="alamat" rows="3" placeholder="Jalan, Nomor Rumah, Kecamatan, Kota, Kode Pos" class="form-control" required></textarea>
                            </div>

                            <div class="form-group">
                                <label class="font-weight-bold">No. Telepon / WhatsApp Aktif</label>
                                <input type="text" name="no_telp" placeholder="Contoh: 081234567890" class="form-control" required>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label class="font-weight-bold">Jasa Pengiriman</label>
                                    <select class="form-control" name="jasa_pengiriman">
                                        <option value="JNE Reguler">JNE Reguler</option>
                                        <option value="J&T Express">J&T Express</option>
                                        <option value="SiCepat BEST">SiCepat BEST</option>
                                        <option value="GoSend Instant">GoSend Instant</option>
                                        <option value="POS Indonesia">POS Indonesia</option>
                                    </select>
                                </div>

                                <div class="form-group col-md-6">
                                    <label class="font-weight-bold">Pilih Metode Bank Transfer</label>
                                    <select class="form-control" name="bank">
                                        <option value="BCA - 8820192831">BCA - 8820192831</option>
                                        <option value="BNI - 0918273645">BNI - 0918273645</option>
                                        <option value="BRI - 1122334455">BRI - 1122334455</option>
                                        <option value="Mandiri - 14000987654">Mandiri - 14000987654</option>
                                    </select>
                                </div>
                            </div>

                            <hr>
                            <div class="d-flex justify-content-between">
                                <a href="<?php echo base_url('dashboard/detail_keranjang') ?>" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left mr-1"></i> Kembali ke Keranjang
                                </a>
                                <button type="submit" class="btn btn-success font-weight-bold px-4">
                                    <i class="fas fa-check-circle mr-1"></i> Pesan Sekarang
                                </button>
                            </div>

                        </form>

                    <?php
                    } else {
                        echo "<div class='alert alert-warning text-center'>Keranjang Belanja Anda Masih Kosong!</div>";
                    }
                    ?>

                </div>
            </div>

        </div>
    </div>
</div>

