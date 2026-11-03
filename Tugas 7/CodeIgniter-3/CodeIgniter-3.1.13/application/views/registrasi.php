<body class="bg-gradient-primary">

    <div class="container">

        <div class="card o-hidden border-0 shadow-lg col-lg-6 my-5 mx-auto">
            <div class="card-body p-0">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 font-weight-bold mb-4">Daftar Akun Pelanggan Baru</h1>
                            </div>
                            <form method="post" action="<?php echo base_url('registrasi/index') ?>" class="user">
                                <div class="form-group">
                                    <input type="text" class="form-control form-control-user" placeholder="Nama Lengkap Anda" name="nama" value="<?php echo set_value('nama'); ?>">
                                    <?php echo form_error('nama', '<div class="text-danger small ml-2 mt-1">', '</div>') ?>
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control form-control-user" placeholder="Username Anda" name="username" value="<?php echo set_value('username'); ?>">
                                    <?php echo form_error('username', '<div class="text-danger small ml-2 mt-1">', '</div>') ?>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="password" class="form-control form-control-user" placeholder="Password" name="password_1">
                                        <?php echo form_error('password_1', '<div class="text-danger small ml-2 mt-1">', '</div>') ?>
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="password" class="form-control form-control-user" placeholder="Ulangi Password" name="password_2">
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary btn-user btn-block font-weight-bold">
                                    <i class="fas fa-user-plus mr-1"></i> Daftar Akun
                                </button>
                            </form>
                            <hr>
                            <div class="text-center">
                                <a class="small font-weight-bold" href="<?php echo base_url('auth/login') ?>">Sudah punya akun? Silakan Login!</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

