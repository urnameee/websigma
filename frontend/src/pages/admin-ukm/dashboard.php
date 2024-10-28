<?php
session_start(); // Memulai sesi

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['id_ukm'])) {
    // Jika belum login, arahkan ke halaman login
    header('Location: /index.html'); // Ganti 'login.php' dengan URL halaman login Anda
    exit(); // Pastikan untuk menghentikan eksekusi skrip setelah pengalihan
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

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a href="index.html" class="brand-link">
            <img src="/frontend/src/pages/admin/dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
            <span class="brand-text font-weight-light">Admin UKM</span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <li class="nav-item">
                        <a href="admin_ukm_dashboard.html" class="nav-link">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="profile_ukm.html" class="nav-link">
                            <i class="nav-icon fas fa-university"></i>
                            <p>Profil UKM</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="struktur_organisasi.html" class="nav-link">
                            <i class="nav-icon fas fa-sitemap"></i>
                            <p>Struktur Organisasi</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="timeline_ukm.html" class="nav-link">
                            <i class="nav-icon fas fa-calendar-alt"></i>
                            <p>Timeline Kegiatan</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="keanggotaan.html" class="nav-link">
                            <i class="nav-icon fas fa-users"></i>
                            <p>Keanggotaan</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="rapat_ukm.html" class="nav-link">
                            <i class="nav-icon fas fa-comments"></i>
                            <p>Rapat</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="dokumentasi_kegiatan_ukm.html" class="nav-link">
                            <i class="nav-icon fas fa-images"></i>
                            <p>Dokumentasi Kegiatan</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="pendaftaran_ukm.html" class="nav-link">
                            <i class="nav-icon fas fa-user-plus"></i>
                            <p>Pendaftaran</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link" onclick="logout()">
                            <i class="nav-icon fas fa-sign-out-alt"></i>
                            <p>Logout</p>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>

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

<!-- Page specific script -->
<script>
$(document).ready(function() {
    function loadTotalAnggota() {
        $.ajax({
            url: '/backend/controllers/admin-ukm/get-dashboard.php',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                $('#total-anggota').text(data.totalAnggota);
            },
            error: function() {
                alert('Terjadi kesalahan saat mengambil tdata.');
            }
        });
    }
    // Load dashboard data on page load
    loadTotalAnggota();

    function loadTotalKegiatan() {
        $.ajax({
            url: '/backend/controllers/admin-ukm/get-dashboard.php',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                $('#total-kegiatan').text(data.totalKegiatan);
            },
            error: function() {
                alert('Terjadi kesalahan saat mengambil tdata.');
            }
        });
    }
    // Load dashboard data on page load
    loadTotalKegiatan();

    function loadTotalRapat() {
        $.ajax({
            url: '/backend/controllers/admin-ukm/get-dashboard.php',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                $('#total-rapat').text(data.totalRapat);
            },
            error: function() {
                alert('Terjadi kesalahan saat mengambil data.');
            }
        });
    }
    // Load dashboard data on page load
    loadTotalRapat();
    
    function loadUpcomingEvents() {
    $.ajax({
        url: '/backend/controllers/admin-ukm/get-dashboard.php',
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            if (data.timelines.length > 0) {
                let eventsHtml = '<ul>';
                data.timelines.forEach(function(event) {
                    // Menampilkan judul dan tanggal kegiatan
                    eventsHtml += `<li>${event.judul_kegiatan} ( ${event.tanggal_kegiatan} )</li>`;
                });
                eventsHtml += '</ul>';
                $('#upcoming-events').html(eventsHtml);
            } else {
                $('#upcoming-events').text('Tidak ada kegiatan mendatang.');
            }
        },
        error: function() {
            $('#upcoming-events').text('Terjadi kesalahan saat mengambil data kegiatan mendatang.');
        }
    });
}
    loadUpcomingEvents();
    
    function loadLatestMeetings() {
    $.ajax({
        url: '/backend/controllers/admin-ukm/get-dashboard.php', // Pastikan URL ini mengarah ke endpoint yang tepat
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            if (data.rapatDilaksanakan.length > 0) {
                let meetingsHtml = '<ul>';
                data.rapatDilaksanakan.forEach(function(meeting) {
                    // Menampilkan judul dan tanggal rapat
                    meetingsHtml += `<li>${meeting.judul} ( ${meeting.tanggal} )</li>`;
                });
                meetingsHtml += '</ul>';
                $('#latest-meetings').html(meetingsHtml);
            } else {
                $('#latest-meetings').text('Tidak ada rapat yang sudah dilaksanakan.');
            }
        },
        error: function() {
            $('#latest-meetings').text('Terjadi kesalahan saat mengambil data rapat yang sudah dilaksanakan.');
        }
    });
}
loadLatestMeetings();
    
    // Logout function
    window.logout = function() {
        $.ajax({
            url: '/backend/controllers/logout.php', // URL endpoint untuk logout
            type: 'POST',
            success: function() {
                window.location.href = '/index.html'; // Arahkan kembali ke halaman login setelah logout
            },
            error: function() {
                alert('Terjadi kesalahan saat logout.');
            }
        });
    };
});
</script>
</body>
</html>
