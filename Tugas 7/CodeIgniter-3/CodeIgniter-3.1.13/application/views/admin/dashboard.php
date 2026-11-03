<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800 font-weight-bold">Dashboard Administrator</h1>
        <a href="<?php echo base_url('admin/data_barang'); ?>" class="btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50 mr-1"></i> Kelola Data Barang
        </a>
    </div>

    <!-- Content Row: Summary Cards -->
    <div class="row">

        <!-- Total Produk Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Produk</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $total_barang; ?> Produk</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-boxes fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Stok Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Stok Tersedia</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $total_stok; ?> Unit</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-warehouse fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Invoice Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Transaksi (Invoice)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $total_invoice; ?> Pesanan</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-file-invoice-dollar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total User Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pengguna Terdaftar</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $total_user; ?> Akun</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Recent Invoices Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-clock mr-1"></i> Pesanan / Invoice Terbaru
            </h6>
            <a href="<?php echo base_url('admin/invoice'); ?>" class="btn btn-sm btn-outline-primary">Lihat Semua Invoice &rarr;</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>ID Invoice</th>
                            <th>Nama Pemesan</th>
                            <th>Alamat Pengiriman</th>
                            <th>Kurir & Bank</th>
                            <th>Tanggal Pesan</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($invoice_terbaru)) : ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted">Belum ada transaksi masuk.</td>
                            </tr>
                        <?php else : ?>
                            <?php foreach ($invoice_terbaru as $inv) : ?>
                                <tr>
                                    <td class="font-weight-bold">#INV-<?php echo $inv->id; ?></td>
                                    <td><?php echo htmlspecialchars($inv->nama); ?></td>
                                    <td><?php echo htmlspecialchars($inv->alamat); ?></td>
                                    <td>
                                        <span class="badge badge-info"><?php echo htmlspecialchars($inv->jasa_pengiriman ?: 'JNE'); ?></span>
                                        <small class="d-block text-muted"><?php echo htmlspecialchars($inv->bank ?: '-'); ?></small>
                                    </td>
                                    <td><?php echo $inv->tgl_pesan; ?></td>
                                    <td class="text-center">
                                        <?php echo anchor('admin/invoice/detail/' . $inv->id, '<i class="fas fa-eye mr-1"></i>Detail', array('class' => 'btn btn-sm btn-primary')); ?>
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

