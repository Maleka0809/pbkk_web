<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?php echo base_url('welcome') ?>">
                <div class="sidebar-brand-icon">
                    <i class="fas fa-bolt"></i>
                </div>
                <div class="sidebar-brand-text mx-2">ELECTRO STORE</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item <?php echo ($this->uri->segment(1) == '' || $this->uri->segment(1) == 'welcome') ? 'active' : ''; ?>">
                <a class="nav-link" href="<?php echo base_url('welcome') ?>">
                    <i class="fas fa-fw fa-store"></i>
                    <span>Semua Katalog</span>
                </a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Kategori Gadget
            </div>

            <li class="nav-item <?php echo ($this->uri->segment(2) == 'laptop') ? 'active' : ''; ?>">
                <a class="nav-link" href="<?php echo base_url('kategori/laptop') ?>">
                    <i class="fas fa-fw fa-laptop"></i>
                    <span>Laptop</span>
                </a>
            </li>

            <li class="nav-item <?php echo ($this->uri->segment(2) == 'smartphone') ? 'active' : ''; ?>">
                <a class="nav-link" href="<?php echo base_url('kategori/smartphone') ?>">
                    <i class="fas fa-fw fa-mobile-alt"></i>
                    <span>Smartphone</span>
                </a>
            </li>

            <li class="nav-item <?php echo ($this->uri->segment(2) == 'aksesoris') ? 'active' : ''; ?>">
                <a class="nav-link" href="<?php echo base_url('kategori/aksesoris') ?>">
                    <i class="fas fa-fw fa-headphones-alt"></i>
                    <span>Aksesoris</span>
                </a>
            </li>

            <li class="nav-item <?php echo ($this->uri->segment(2) == 'gaming') ? 'active' : ''; ?>">
                <a class="nav-link" href="<?php echo base_url('kategori/gaming') ?>">
                    <i class="fas fa-fw fa-gamepad"></i>
                    <span>Gaming</span>
                </a>
            </li>

            <?php if ($this->session->userdata('role_id') == '1') : ?>
            <hr class="sidebar-divider">
            <div class="sidebar-heading">Panel Admin</div>
            <li class="nav-item">
                <a class="nav-link text-warning font-weight-bold" href="<?php echo base_url('admin/dashboard_admin') ?>">
                    <i class="fas fa-fw fa-user-shield text-warning"></i>
                    <span>Ke Dashboard Admin</span>
                </a>
            </li>
            <?php endif; ?>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Search -->
                    <?php echo form_open('welcome/search', array('class' => 'd-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search')); ?>
                        <div class="input-group">
                            <input type="text" name="keyword" class="form-control bg-light border-0 small" placeholder="Cari laptop, smartphone, gaming..." aria-label="Search">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fas fa-search fa-sm"></i>
                                </button>
                            </div>
                        </div>
                    <?php echo form_close(); ?>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto align-items-center">

                        <!-- Keranjang Belanja -->
                        <li class="nav-item mx-1">
                            <?php $total_items = $this->cart->total_items(); ?>
                            <a href="<?php echo base_url('dashboard/detail_keranjang') ?>" class="btn btn-outline-primary btn-sm px-3 py-1">
                                <i class="fas fa-shopping-cart mr-1"></i>
                                Keranjang Belanja
                                <span class="badge badge-danger badge-pill ml-1"><?php echo $total_items; ?> item</span>
                            </a>
                        </li>

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- User Information -->
                        <?php if ($this->session->userdata('username')) : ?>
                            <li class="nav-item mr-2">
                                <span class="text-gray-700 small">
                                    <i class="fas fa-user-circle text-primary mr-1"></i>
                                    Halo, <strong><?php echo htmlspecialchars($this->session->userdata('nama') ?: $this->session->userdata('username')); ?></strong>
                                </span>
                            </li>
                            <li class="nav-item">
                                <?php echo anchor('auth/logout', '<i class="fas fa-sign-out-alt mr-1"></i>Logout', array('class' => 'btn btn-sm btn-danger')); ?>
                            </li>
                        <?php else : ?>
                            <li class="nav-item mr-2">
                                <?php echo anchor('auth/login', '<i class="fas fa-sign-in-alt mr-1"></i>Login', array('class' => 'btn btn-sm btn-primary')); ?>
                            </li>
                            <li class="nav-item">
                                <?php echo anchor('registrasi/index', '<i class="fas fa-user-plus mr-1"></i>Daftar', array('class' => 'btn btn-sm btn-outline-secondary')); ?>
                            </li>
                        <?php endif; ?>

                    </ul>

                </nav>
                <!-- End of Topbar -->

