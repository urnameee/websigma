<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Timeline Kegiatan UKM</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="/frontend/src/pages/admin/plugins/fontawesome-free/css/all.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="/frontend/src/pages/admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <!-- Select2 -->
  <link rel="stylesheet" href="/frontend/src/pages/admin/plugins/select2/css/select2.min.css">
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
    <!-- Content Header -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Timeline Kegiatan</h1>
          </div>
          <div class="col-sm-6">
            <button class="btn btn-primary float-right" data-toggle="modal" data-target="#modal-form">
              <i class="fas fa-plus"></i> Tambah Kegiatan
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
                      <th>Banner</th>
                      <th>Judul Kegiatan</th>
                      <th>Tanggal</th>
                      <th>Waktu</th>
                      <th>Status</th>
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
    </section>
  </div>

  <!-- Modal Form Kegiatan -->
  <div class="modal fade" id="modal-form">
    <!-- Modal content remains the same -->
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
<!-- DataTables -->
<script src="/frontend/src/pages/admin/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="/frontend/src/pages/admin/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<!-- Select2 -->
<script src="/frontend/src/pages/admin/plugins/select2/js/select2.min.js"></script>
<!-- bs-custom-file-input -->
<script src="/frontend/src/pages/admin/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
<!-- AdminLTE App -->
<script src="/frontend/src/pages/admin/dist/js/adminlte.min.js"></script>

<!-- Import Sidebar Manager -->
<script type="module">
    import SidebarManager from '/frontend/src/js/sidebar.js';
    window.SidebarManager = SidebarManager; // Make it globally available
</script>

<!-- Initialize Sidebar -->
<script type="module">
    document.addEventListener('DOMContentLoaded', function() {
        SidebarManager.init();
    });
</script>

<!-- Global logout function for sidebar -->
<script>
window.logout = function() {
    SidebarManager.logout();
};
</script>

<!-- Page specific script -->
<script>
$(document).ready(function() {
    // Initialize DataTable
    $('#table-timeline').DataTable();
    
    // Initialize bs-custom-file-input
    bsCustomFileInput.init();

    // Preview image when selected
    $('#image').change(function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#preview-image').html(`
                    <img src="${e.target.result}" class="img-fluid" style="max-height: 200px">
                `);
            }
            reader.readAsDataURL(file);
        }
    });

    // Add dokumentasi field
    let dokumentasiCount = 0;
    $('#add-dokumentasi').click(function() {
        dokumentasiCount++;
        const dokumentasiHtml = `
            <div class="dokumentasi-item mb-2">
                <div class="input-group">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input dokumentasi" name="dokumentasi[]" accept="image/*">
                        <label class="custom-file-label">Pilih file dokumentasi</label>
                    </div>
                    <div class="input-group-append">
                        <button type="button" class="btn btn-danger remove-dokumentasi">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
        $('#dokumentasi-container').append(dokumentasiHtml);
        bsCustomFileInput.init();
    });

    // Remove dokumentasi field
    $(document).on('click', '.remove-dokumentasi', function() {
        $(this).closest('.dokumentasi-item').remove();
    });
});
</script>

</body>
</html>