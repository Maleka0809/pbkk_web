<body class="bg-gradient-primary">

    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-xl-5 col-lg-6 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="p-5">
                                    <div class="text-center">
                                        <div class="mb-2">
                                            <i class="fas fa-bolt fa-3x text-primary"></i>
                                        </div>
                                        <h1 class="h4 text-gray-900 font-weight-bold mb-1">Login ElectroStore</h1>
                                        <p class="text-muted small mb-4">Masuk sebagai Pelanggan atau Administrator</p>
                                    </div>

                                    <?php echo $this->session->flashdata('pesan') ?>

                                    <form method="post" action="<?php echo base_url('auth/login') ?>" class="user">
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-user" name="username" placeholder="Masukkan Username..." value="<?php echo set_value('username'); ?>">
                                            <?php echo form_error('username', '<div class="text-danger small ml-2 mt-1">', '</div>'); ?>
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-user" name="password" placeholder="Masukkan Password...">
                                            <?php echo form_error('password', '<div class="text-danger small ml-2 mt-1">', '</div>'); ?>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-user btn-block font-weight-bold">
                                            <i class="fas fa-sign-in-alt mr-1"></i> Login Sekarang
                                        </button>
                                    </form>

                                    <hr>
                                    <div class="bg-light rounded p-3 mb-3 small text-gray-700 border">
                                        <div class="font-weight-bold text-primary mb-1"><i class="fas fa-key mr-1"></i> Akun Demo Siap Pakai:</div>
                                        <div><strong>Admin:</strong> username <code>admin</code> | password <code>123</code></div>
                                        <div><strong>Pelanggan:</strong> username <code>user</code> | password <code>123</code></div>
                                    </div>

                                    <div class="text-center">
                                        <a class="small font-weight-bold" href="<?php echo base_url('registrasi/index'); ?>">Belum punya akun? Daftar Pelanggan Baru!</a>
                                    </div>
                                    <div class="text-center mt-2">
                                        <a class="small text-secondary" href="<?php echo base_url('welcome'); ?>">&larr; Kembali ke Katalog Toko</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

