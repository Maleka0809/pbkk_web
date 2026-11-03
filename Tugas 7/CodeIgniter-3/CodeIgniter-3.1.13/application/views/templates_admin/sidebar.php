<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-dark sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?php echo base_url('admin/dashboard_admin') ?>">
                <div class="sidebar-brand-icon">
                    <i class="fas fa-user-shield text-warning"></i>
                </div>
                <div class="sidebar-brand-text mx-2">ADMIN TOKO</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item <?php echo ($this->uri->segment(2) == 'dashboard_admin') ? 'active' : ''; ?>">
                <a class="nav-link" href="<?php echo base_url('admin/dashboard_admin') ?>">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <hr class="sidebar-divider">
            <div class="sidebar-heading">Manajemen Toko</div>

            <!-- Nav Item - Data Barang -->
            <li class="nav-item <?php echo ($this->uri->segment(2) == 'data_barang') ? 'active' : ''; ?>">
                <a class="nav-link" href="<?php echo base_url('admin/data_barang') ?>">
                    <i class="fas fa-fw fa-boxes"></i>
                    <span>Data Barang (CRUD)</span>
                </a>
            </li>

            <!-- Nav Item - Invoices -->
            <li class="nav-item <?php echo ($this->uri->segment(2) == 'invoice') ? 'active' : ''; ?>">
                <a class="nav-link" href="<?php echo base_url('admin/invoice') ?>">
                    <i class="fas fa-fw fa-file-invoice-dollar"></i>
                    <span>Invoices / Pesanan</span>
                </a>
            </li>

            <hr class="sidebar-divider">
            <div class="sidebar-heading">Pintasan</div>

            <li class="nav-item">
                <a class="nav-link" href="<?php echo base_url('welcome') ?>">
                    <i class="fas fa-fw fa-external-link-alt"></i>
                    <span>Lihat Halaman Toko</span>
                </a>
            </li>

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

                    <span class="font-weight-bold text-gray-800 d-none d-sm-inline-block ml-2">
                        <i class="fas fa-cogs text-primary mr-1"></i> Panel Manajemen ElectroStore
                    </span>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto align-items-center">
                        <?php if ($this->session->userdata('username')) : ?>
                            <li class="nav-item mr-3">
                                <span class="badge badge-primary px-2 py-1 mr-1">ADMIN</span>
                                <span class="text-gray-700 small font-weight-bold">
                                    <?php echo htmlspecialchars($this->session->userdata('nama') ?: $this->session->userdata('username')); ?>
                                </span>
                            </li>
                            <li class="nav-item">
                                <?php echo anchor('auth/logout', '<i class="fas fa-sign-out-alt mr-1"></i>Logout', array('class' => 'btn btn-sm btn-danger')); ?>
                            </li>
                        <?php endif; ?>
                    </ul>

                </nav>
                <!-- End of Topbar -->

