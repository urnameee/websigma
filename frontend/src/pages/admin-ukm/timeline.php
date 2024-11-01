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
    <meta name="id_ukm" content="<?php echo $_SESSION['id_ukm']; ?>">
    <title>Timeline UKM</title>

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
                            <h1>Timeline UKM</h1>
                        </div>
                        <div class="col-sm-6">
                            <button class="btn btn-primary float-right" data-toggle="modal" data-target="#modal-form" id="add-btn">
                                <i class="fas fa-plus"></i> Tambah Timeline
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
                                <div class="card-body">
                                    <table id="table-timeline" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Judul Kegiatan</th>
                                                <th>Deskripsi</th>
                                                <th>Tanggal Kegiatan</th>
                                                <th>Waktu</th>
                                                <th>Status</th>
                                                <th>Panitia</th>
                                                <th>Rapat</th>
                                                <th>Foto/Banner</th>
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

        <!-- Modal Add/Edit Timeline -->
        <div class="modal fade" id="modal-form">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="modal-title">Tambah Timeline</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="form-timeline" enctype="multipart/form-data">
                        <input type="hidden" id="id_timeline" name="id_timeline">
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="judul_kegiatan">Judul Kegiatan</label>
                                <input type="text" class="form-control" id="judul_kegiatan" name="judul_kegiatan" required>
                            </div>
                            <div class="form-group">
                                <label for="deskripsi">Deskripsi</label>
                                <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="tanggal_kegiatan">Tanggal Kegiatan</label>
                                <input type="date" class="form-control" id="tanggal_kegiatan" name="tanggal_kegiatan" required>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="waktu_mulai">Waktu Mulai</label>
                                        <input type="time" class="form-control" id="waktu_mulai" name="waktu_mulai" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="waktu_selesai">Waktu Selesai</label>
                                        <input type="time" class="form-control" id="waktu_selesai" name="waktu_selesai" required>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Status</label>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="status" name="status" checked>
                                    <label class="custom-control-label" for="status">Active</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="foto">Foto/Banner</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="image" name="image" accept="image/*">
                                    <label class="custom-file-label" for="image">Pilih file</label>
                                </div>
                                <small class="form-text text-muted">Format: JPG, PNG. Maksimal 2MB</small>
                                <div id="preview-image" class="mt-2"></div>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal Panitia -->
        <div class="modal fade" id="modal-panitia">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Kelola Panitia</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="form-panitia">
                            <input type="hidden" id="id_timeline_panitia" name="id_timeline_panitia">
                            <div class="form-group">
                                <label for="nim_panitia">Mahasiswa</label>
                                <select class="form-control" id="nim_panitia" name="nim_panitia" required>
                                    <option value="">Pilih Mahasiswa</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="jabatan_panitia">Jabatan</label>
                                <select class="form-control" id="jabatan_panitia" name="jabatan_panitia" required>
                                    <option value="">Pilih Jabatan</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Tambah Panitia</button>
                        </form>

                        <div class="mt-4">
                            <h5>Daftar Panitia</h5>
                            <table id="table-panitia" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>NIM</th>
                                        <th>Nama</th>
                                        <th>Jabatan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Rapat -->
        <div class="modal fade" id="modal-rapat">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Kelola Rapat</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Form tambah rapat -->
                        <<form id="form-rapat" enctype="multipart/form-data">
                            <input type="hidden" id="id_rapat" name="id_rapat">
                            <input type="hidden" id="id_timeline_rapat" name="id_timeline_rapat">
                            <div class="form-group">
                                <label for="judul_rapat">Judul Rapat</label>
                                <input type="text" class="form-control" id="judul_rapat" name="judul_rapat" required>
                            </div>
                            <div class="form-group">
                                <label for="tanggal_rapat">Tanggal Rapat</label>
                                <input type="date" class="form-control" id="tanggal_rapat" name="tanggal_rapat" required>
                            </div>
                            <div class="form-group">
                                <label for="notulensi">File Notulensi (PDF)</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="notulensi" name="notulensi" accept=".pdf">
                                    <label class="custom-file-label" for="notulensi">Pilih file</label>
                                </div>
                                <small class="form-text text-muted">Format: PDF. Maksimal 5MB</small>
                            </div>
                            <div class="form-group">
                                <label for="dokumentasi">Foto Dokumentasi</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="dokumentasi" name="dokumentasi[]" accept="image/*" multiple>
                                    <label class="custom-file-label" for="dokumentasi">Pilih file</label>
                                </div>
                                <small class="form-text text-muted">Format: JPG, PNG. Maksimal 2MB per file</small>
                                <div id="preview-dokumentasi" class="mt-2 row"></div>
                            </div>
                            <button type="submit" class="btn btn-primary">Tambah Rapat</button>
                        </form>

                        <!-- Tabel daftar rapat -->
                        <div class="mt-4">
                            <h5>Daftar Rapat</h5>
                            <table id="table-rapat" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Judul Rapat</th>
                                        <th>Tanggal</th>
                                        <th>Notulensi</th>
                                        <th>Dokumentasi</th>
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

            <!-- Modal Preview Dokumentasi -->
            <div class="modal fade" id="modal-preview-dokumentasi">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Preview Dokumentasi</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div id="dokumentasi-carousel" class="carousel slide" data-ride="carousel">
                                <div class="carousel-inner">
                                    <!-- Foto akan diisi oleh JavaScript -->
                                </div>
                                <a class="carousel-control-prev" href="#dokumentasi-carousel" role="button" data-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="sr-only">Previous</span>
                                </a>
                                <a class="carousel-control-next" href="#dokumentasi-carousel" role="button" data-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="sr-only">Next</span>
                                </a>
                            </div>
                        </div>
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
    <script src="/frontend/src/pages/admin/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
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

    <script>
        // Inject PHP session variable ke JavaScript
        const id_ukm = <?php echo $_SESSION['id_ukm']; ?>;
    </script>
    <!-- Custom Script akan ditambahkan nanti -->
    <script src="/frontend/src/pages/admin-ukm/js/timeline.js"></script>
    <script src="/frontend/src/pages/admin-ukm/js/rapat.js"></script>
    <script src="/frontend/src/pages/admin-ukm/js/panitia.js"></script>
</body>
</html>