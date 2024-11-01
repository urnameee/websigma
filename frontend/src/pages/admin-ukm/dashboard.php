<?php
session_start(); // Memulai sesi

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['id_ukm'])) {
    // Jika belum login, arahkan ke halaman login
    header('Location: /index.html');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Admin UKM</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="/frontend/src/pages/admin/plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="/frontend/src/pages/admin/dist/css/adminlte.min.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
        </ul>
    </nav>

    <!-- Sidebar Container -->
    <div id="sidebar-container"></div>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Dashboard</h1>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Info boxes -->
                <div class="row">
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box">
                            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-users"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Total Anggota</span>
                                <span class="info-box-number" id="total-anggota">Loading...</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box">
                            <span class="info-box-icon bg-success elevation-1"><i class="fas fa-calendar-check"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Kegiatan Aktif</span>
                                <span class="info-box-number" id="total-kegiatan">Loading...</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box">
                            <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-user-plus"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Pendaftar Baru</span>
                                <span class="info-box-number" id="total-pendaftar">Loading...</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box">
                            <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-comments"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Total Rapat</span>
                                <span class="info-box-number" id="total-rapat">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Upcoming Events & Latest Meetings -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Kegiatan Mendatang</h3>
                            </div>
                            <div class="card-body">
                                <div id="upcoming-events">Loading...</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Rapat yang Sudah dilaksanakan</h3>
                            </div>
                            <div class="card-body">
                                <div id="latest-meetings">Loading...</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Footer -->
    <footer class="main-footer">
        <strong>Copyright &copy; 2024</strong>
        All rights reserved.
    </footer>
</div>

<!-- REQUIRED SCRIPTS -->
<!-- jQuery -->
<script src="/frontend/src/pages/admin/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="/frontend/src/pages/admin/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="/frontend/src/pages/admin/dist/js/adminlte.min.js"></script>

<!-- Import Sidebar Manager -->
<script type="module">
    import SidebarManager from '/frontend/src/pages/admin-ukm/js/sidebar.js';
    window.SidebarManager = SidebarManager; // Make it globally available
</script>

<!-- Initialize Sidebar -->
<script type="module">
    document.addEventListener('DOMContentLoaded', function() {
        SidebarManager.init();
    });
</script>

<!-- Dashboard specific script -->
<script>
// Global logout function for sidebar
window.logout = function() {
    SidebarManager.logout();
};
</script>

<script src = "http://websigma.test/frontend/src/pages/admin-ukm/js/dashboard.js"></script>

</body>
</html>