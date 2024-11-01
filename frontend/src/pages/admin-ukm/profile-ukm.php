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
  <title>Edit Profil UKM</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="/frontend/src/pages/admin/plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="/frontend/src/pages/admin/dist/css/adminlte.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
      <li class="nav-item"><a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a></li>
    </ul>
  </nav>

  <!-- Sidebar Container -->
  <div id="sidebar-container"></div>

  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid"><h1>Edit Profil UKM</h1></div>
    </section>

    <section class="content">
      <div class="container-fluid">
        <div class="card">
          <div class="card-body">
            <form id="form-ukm" enctype="multipart/form-data">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group"><label for="nama_ukm">Nama UKM</label><input type="text" class="form-control" id="nama_ukm" name="nama_ukm" required></div>
                  <div class="form-group"><label for="deskripsi">Deskripsi</label><textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" required></textarea></div>
                  <div class="form-group"><label for="visi">Visi</label><textarea class="form-control" id="visi" name="visi" rows="3" required></textarea></div>
                  <div class="form-group"><label for="misi">Misi</label><textarea class="form-control" id="misi" name="misi" rows="5" required></textarea></div>
                  <div class="form-group"><label for="tanggal_berdiri">Tanggal Berdiri</label><input type="date" class="form-control" id="tanggal_berdiri" name="tanggal_berdiri" required></div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="logo">Logo UKM</label>
                    <div class="custom-file">
                      <input type="file" class="custom-file-input" id="logo" name="logo" accept="image/*">
                      <label class="custom-file-label" for="logo">Pilih file</label>
                    </div>
                    <small class="form-text text-muted">Format: JPG, PNG. Maksimal 2MB</small>
                    <img id="current-logo" src="/path/to/current/logo.png" alt="Current Logo" class="img-thumbnail mt-2" width="150">
                  </div>
                  <div class="form-group">
                    <label for="banner">Banner UKM</label>
                    <div class="custom-file">
                      <input type="file" class="custom-file-input" id="banner" name="banner" accept="image/*">
                      <label class="custom-file-label" for="banner">Pilih file</label>
                    </div>
                    <small class="form-text text-muted">Format: JPG, PNG. Maksimal 2MB</small>
                    <img id="current-banner" src="/path/to/current/banner.png" alt="Current Banner" class="img-thumbnail mt-2" width="150">
                  </div>
                </div>
              </div>
              <div class="row mt-4">
                <div class="col-12"><button type="submit" class="btn btn-primary">Simpan Perubahan</button></div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </section>
  </div>

  <footer class="main-footer"><strong>Copyright &copy; 2024</strong> All rights reserved.</footer>
</div>

<script src="/frontend/src/pages/admin/plugins/jquery/jquery.min.js"></script>
<script src="/frontend/src/pages/admin/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="/frontend/src/pages/admin/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
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

<script>
$(function() {
    function loadProfileData() {
        $.ajax({
            url: '/backend/controllers/admin-ukm/profile.php',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                $('#nama_ukm').val(data.nama_ukm);
                $('#deskripsi').val(data.deskripsi);
                $('#visi').val(data.visi.trim());
                $('#misi').val(data.misi.join("\n"));
                $('#tanggal_berdiri').val(data.tanggal_berdiri);
                $('#current-logo').attr('src', data.logo);
                $('#current-banner').attr('src', data.cover);
            },
            error: function() {
                alert('Gagal memuat data. Silakan coba lagi nanti.');
            }
        });
    }

    $('#form-ukm').on('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(this);
        $.ajax({
            url: '/backend/controllers/admin-ukm/profile.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function() {
                Swal.fire({ icon: 'success', title: 'Profil Berhasil Diperbarui!', text: 'Data profil UKM telah berhasil diperbarui.', confirmButtonText: 'OK' });
            },
            error: function() {
                alert('Gagal memperbarui profil. Silakan coba lagi nanti.');
            }
        });
    });

    loadProfileData();
});

 // Global logout function for sidebar
 window.logout = function() {
    SidebarManager.logout();
};
</script>
</body>
</html>
