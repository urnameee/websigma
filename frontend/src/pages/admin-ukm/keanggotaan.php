<?php
session_start(); 
if (!isset($_SESSION['id_ukm'])) {
    header('Location: /index.html');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Keanggotaan UKM</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="/frontend/src/pages/admin/plugins/fontawesome-free/css/all.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="/frontend/src/pages/admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="/frontend/src/pages/admin/dist/css/adminlte.min.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css">
</head>
<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <!-- Sidebar Container -->
        <div id="sidebar-container"></div>

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <!-- Content Header -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Keanggotaan UKM</h1>
                        </div>
                        <div class="col-sm-6">
                            <button class="btn btn-primary float-right" data-toggle="modal" data-target="#modal-form" id="add-btn">
                                <i class="fas fa-plus"></i> Tambah Anggota
                            </button>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Filter Status</label>
                                                <select class="form-control" id="filter-status">
                                                    <option value="">Semua Status</option>
                                                    <option value="anggota">Anggota</option>
                                                    <option value="pengurus">Pengurus</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Filter Periode</label>
                                                <select class="form-control" id="filter-periode">
                                                    <option value="">Semua Periode</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Search</label>
                                                <input type="text" class="form-control" id="search-box" placeholder="Cari NIM/Nama...">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <table id="table-anggota" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>NIM</th>
                                                <th>Nama Lengkap</th>
                                                <th>Program Studi</th>
                                                <th>Status</th>
                                                <th>Periode</th>
                                                <th>Tanggal Bergabung</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Data akan diisi oleh JavaScript -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <!-- Modal Add/Edit -->
        <div class="modal fade" id="modal-form">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="modal-title">Tambah Anggota</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="form-keanggotaan">
                        <input type="hidden" id="id_keanggotaan" name="id_keanggotaan">
                        <input type="hidden" name="id_ukm" value="<?php echo $_SESSION['id_ukm']; ?>">
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="nim">NIM</label>
                                <select class="form-control" id="nim" name="nim" required>
                                    <option value="">Pilih Mahasiswa</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Status</label>
                                <select class="form-control" id="status" name="status" required>
                                    <option value="anggota">Anggota</option>
                                    <option value="pengurus">Pengurus</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Periode</label>
                                <select class="form-control" id="id_periode" name="id_periode" required>
                                    <option value="">Pilih Periode</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Tanggal Bergabung</label>
                                <input type="date" class="form-control" id="tanggal_bergabung" name="tanggal_bergabung" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="main-footer">
            <strong>&copy; 2024</strong> All rights reserved.
        </footer>
    </div>

    <!-- REQUIRED SCRIPTS -->
    <script src="/frontend/src/pages/admin/plugins/jquery/jquery.min.js"></script>
    <script src="/frontend/src/pages/admin/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="/frontend/src/pages/admin/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="/frontend/src/pages/admin/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="/frontend/src/pages/admin/dist/js/adminlte.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>

    <!-- Import Sidebar Manager -->
    <script type="module">
        import SidebarManager from '/frontend/src/pages/admin-ukm/js/sidebar.js';
        window.SidebarManager = SidebarManager;

        document.addEventListener('DOMContentLoaded', function() {
            SidebarManager.init();
        });
    </script>

    <!-- Custom Scripts -->
    <script>
        // Inject PHP session variable ke JavaScript
        const id_ukm = <?php echo $_SESSION['id_ukm']; ?>;
    </script>
    <script src="/frontend/src/pages/admin-ukm/js/keanggotaan.js"></script>
</body>
</html>