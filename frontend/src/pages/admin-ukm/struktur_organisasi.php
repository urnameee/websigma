<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Struktur Organisasi UKM</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="/frontend/src/pages/admin/plugins/fontawesome-free/css/all.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="/frontend/src/pages/admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="/frontend/src/pages/admin/dist/css/adminlte.min.css">
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
            <h1>Struktur Organisasi</h1>
          </div>
          <div class="col-sm-6">
            <button class="btn btn-primary float-right" data-toggle="modal" data-target="#modal-form" id="add-btn">
              <i class="fas fa-plus"></i> Tambah Pengurus
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
                <table id="table-struktur" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>Foto</th>
                      <th>NIM</th>
                      <th>Nama</th>
                      <th>Jabatan</th>
                      <th>Periode</th>
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
          <h4 class="modal-title" id="modal-title">Tambah Pengurus</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="form-struktur" enctype="multipart/form-data">
          <input type="hidden" id="id_struktur" name="id_struktur">
          <div class="modal-body">
            <div class="form-group">
              <label for="nim">NIM</label>
              <select class="form-control" id="nim" name="nim" required>
                <option value="">Pilih Mahasiswa</option>
                <option value="43323205">Dirga</option>
                <!-- Tambah opsi mahasiswa lainnya di sini -->
              </select>
            </div>
            <div class="form-group">
              <label for="id_jabatan">Jabatan</label>
              <select class="form-control" id="id_jabatan" name="id_jabatan" required>
                <option value="">Pilih Jabatan</option>
                <option value="2">Wakil Ketua</option>
                <option value="1">Ketua</option>
                <!-- Tambah opsi jabatan lainnya di sini -->
              </select>
            </div>
            <div class="form-group">
              <label for="id_periode">Periode</label>
              <select class="form-control" id="id_periode" name="id_periode" required>
                <option value="">Pilih Periode</option>
                <option value="1">2024-10-01 s.d 2024-10-31</option>
                <!-- Tambah opsi periode lainnya di sini -->
              </select>
            </div>
            <div class="form-group">
              <label for="tanggal_mulai">Tanggal Mulai</label>
              <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai" required>
            </div>
            <div class="form-group">
              <label for="tanggal_berakhir">Tanggal Berakhir</label>
              <input type="date" class="form-control" id="tanggal_berakhir" name="tanggal_berakhir" required>
            </div>
            <div class="form-group">
              <label for="foto">Foto</label>
              <div class="custom-file">
                <input type="file" class="custom-file-input" id="foto" name="foto" accept="image/*">
                <label class="custom-file-label" for="foto">Pilih file</label>
              </div>
              <small class="form-text text-muted">Format: JPG, PNG. Maksimal 2MB</small>
              <div id="preview-foto" class="mt-2"></div>
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

<!-- Import Sidebar Manager -->
<script type="module">
    import SidebarManager from '/frontend/src/pages/admin-ukm/js/sidebar.js';
    window.SidebarManager = SidebarManager; // Make it globally available

    // Initialize Sidebar
    document.addEventListener('DOMContentLoaded', function() {
        SidebarManager.init();
    });
</script>

<script>
  $(document).ready(function () {
    bsCustomFileInput.init();
    loadStruktur(); // Call the function to load data on page load

    // Function to load organizational structure data
    function loadStruktur() {
      $.ajax({
        url: '/backend/controllers/admin-ukm/struktur_organisasi.php?id_ukm=1', // Ensure UKM ID is correct
        method: 'GET',
        dataType: 'json',
        success: populateTable,
        error: function(xhr, status, error) {
          console.error('AJAX Error:', status, error); // Log errors
        }
      });
    }

    // Populate table with data
    function populateTable(data) {
      const tbody = $('#table-struktur tbody');
      tbody.empty(); // Clear previous table data
      $.each(data, function(index, item) {
        const row = `
          <tr>
            <td>${index + 1}</td>
            <td><img src='/frontend/public/assets/${item.foto_path || 'default.png'}' width='50' alt='Foto'></td>
            <td>${item.nim || 'N/A'}</td>
            <td>${item.nama || 'N/A'}</td>
            <td>${item.jabatan || 'N/A'}</td>
            <td>${item.tanggal_mulai} s.d ${item.tanggal_berakhir}</td>
            <td>
              <button class="btn btn-primary btn-sm edit-btn" data-id="${item.id_struktur}">Edit</button>
              <button class="btn btn-danger btn-sm delete-btn" data-id="${item.id_struktur}">Delete</button>
            </td>
          </tr>
        `;
        tbody.append(row);
      });
    }

    // Reset modal for adding new data
    $('#add-btn').on('click', function () {
      resetForm();
      $('#modal-title').text('Tambah Pengurus');
    });

    // Edit button click event
    $(document).on('click', '.edit-btn', function () {
      const id = $(this).data('id'); // Get the ID of the item to edit
      $.ajax({
        url: '/backend/controllers/admin-ukm/struktur_organisasi.php?id_struktur=' + id,
        method: 'GET',
        dataType: 'json',
        success: function (data) {
          $('#id_struktur').val(data.id_struktur);
          $('#nim').val(data.nim);
          $('#id_jabatan').val(data.id_jabatan);
          $('#id_periode').val(data.id_periode);
          $('#tanggal_mulai').val(data.tanggal_mulai);
          $('#tanggal_berakhir').val(data.tanggal_berakhir);
          $('#modal-title').text('Edit Pengurus');
          $('#modal-form').modal('show');
        },
        error: function (xhr, status, error) {
          console.error('AJAX Error:', status, error); // Log errors
        }
      });
    });

    // Form submission for add/edit
    $('#form-struktur').on('submit', function (event) {
      event.preventDefault(); // Prevent form submission
      const formData = new FormData(this); // Create a FormData object
      $.ajax({
        url: '/backend/controllers/admin-ukm/struktur_organisasi.php',
        method: 'POST',
        data: formData,
        contentType: false, // Prevent jQuery from overriding content type
        processData: false, // Don't process data as a query string
        success: function (response) {
          $('#modal-form').modal('hide'); // Close modal
          loadStruktur(); // Reload the table data
        },
        error: function (xhr, status, error) {
          console.error('AJAX Error:', status, error); // Log errors
        }
      });
    });

    // Delete button click event
    $(document).on('click', '.delete-btn', function () {
      const id = $(this).data('id');
      if (confirm('Are you sure you want to delete this item?')) {
        $.ajax({
          url: '/backend/controllers/admin-ukm/struktur_organisasi.php?id_struktur=' + id,
          method: 'DELETE',
          success: function () {
            loadStruktur(); // Reload the table data
          },
          error: function (xhr, status, error) {
            console.error('AJAX Error:', status, error); // Log errors
          }
        });
      }
    });

    // Function to reset the modal form
    function resetForm() {
      $('#form-struktur')[0].reset(); // Reset the form
      $('#id_struktur').val(''); // Clear the ID input
      $('#preview-foto').empty(); // Clear the image preview
    }

    // Preview selected image
    $('#foto').on('change', function () {
      const file = this.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
          $('#preview-foto').html(`<img src="${e.target.result}" width="100" alt="Preview Foto">`);
        }
        reader.readAsDataURL(file);
      }
    });
  });
</script>
</body>
</html>
