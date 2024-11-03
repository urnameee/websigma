<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Manajemen Rapat UKM</title>

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
  <!-- Content Wrapper -->
  <div class="content-wrapper">
    <!-- Content Header -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Manajemen Rapat</h1>
          </div>
          <div class="col-sm-6">
            <button class="btn btn-primary float-right" data-toggle="modal" data-target="#modal-form">
              <i class="fas fa-plus"></i> Tambah Rapat
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
                <table id="table-rapat" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>Judul Rapat</th>
                      <th>Tanggal</th>
                      <th>Kegiatan Terkait</th>
                      <th>Notulensi</th>
                      <th>Dokumentasi</th>
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

  <!-- Modal Form Rapat -->
  <div class="modal fade" id="modal-form">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="modal-title">Tambah Rapat</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="form-rapat" enctype="multipart/form-data">
          <input type="hidden" id="id_rapat" name="id_rapat">
          <div class="modal-body">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="judul">Judul Rapat</label>
                  <input type="text" class="form-control" id="judul" name="judul" required>
                </div>
                <div class="form-group">
                  <label for="tanggal">Tanggal Rapat</label>
                  <input type="date" class="form-control" id="tanggal" name="tanggal" required>
                </div>
                <div class="form-group">
                  <label for="id_timeline">Kegiatan Terkait</label>
                  <select class="form-control" id="id_timeline" name="id_timeline">
                    <option value="">Pilih Kegiatan (Opsional)</option>
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="notulensi">File Notulensi</label>
                  <div class="custom-file">
                    <input type="file" class="custom-file-input" id="notulensi" name="notulensi" accept=".pdf,.doc,.docx">
                    <label class="custom-file-label" for="notulensi">Pilih file</label>
                  </div>
                  <small class="form-text text-muted">Format: PDF, DOC, DOCX. Maksimal 5MB</small>
                  <div id="preview-notulensi" class="mt-2"></div>
                </div>

                <!-- Multiple Dokumentasi -->
                <div class="form-group">
                  <label>Dokumentasi Rapat</label>
                  <div id="dokumentasi-container">
                    <div class="dokumentasi-item mb-2">
                      <div class="custom-file">
                        <input type="file" class="custom-file-input dokumentasi-file" name="dokumentasi[]" accept="image/*" multiple>
                        <label class="custom-file-label">Pilih file foto</label>
                      </div>
                    </div>
                  </div>
                  <div id="preview-dokumentasi" class="mt-2 row"></div>
                  <small class="form-text text-muted">Format: JPG, PNG. Maksimal 2MB per foto</small>
                </div>
              </div>
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

  <!-- Modal View Dokumentasi -->
  <div class="modal fade" id="modal-dokumentasi">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Dokumentasi Rapat</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div id="dokumentasi-gallery" class="row">
            <!-- Dokumentasi images will be loaded here -->
          </div>
        </div>
      </div>
    </div>
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
<!-- bs-custom-file-input -->
<script src="/frontend/src/pages/admin/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
<!-- AdminLTE App -->
<script src="/frontend/src/pages/admin/dist/js/adminlte.min.js"></script>

<!-- Page specific script -->
<script>
$(function () {
  bsCustomFileInput.init();

  // Initialize DataTable
  var table = $('#table-rapat').DataTable({
    "ajax": {
      "url": "/backend/controllers/admin_ukm/rapat/get_all.php",
      "type": "GET"
    },
    "columns": [
      {"data": null, "render": function (data, type, row, meta) {
        return meta.row + 1;
      }},
      {"data": "judul"},
      {"data": "tanggal"},
      {"data": "judul_kegiatan", "render": function(data) {
        return data ? data : '-';
      }},
      {"data": "notulensi_path", "render": function(data) {
        return data ? 
          `<a href="/uploads/notulensi/${data}" target="_blank" class="btn btn-info btn-sm">
            <i class="fas fa-file-alt"></i> Lihat
           </a>` : 
          '-';
      }},
      {"data": null, "render": function(data) {
        return `<button class="btn btn-info btn-sm view-dokumentasi" data-id="${data.id_rapat}">
                  <i class="fas fa-images"></i> Lihat
                </button>`;
      }},
      {"data": null, "render": function(data) {
        return `
          <button class="btn btn-sm btn-warning edit-btn" data-id="${data.id_rapat}">
            <i class="fas fa-edit"></i>
          </button>
          <button class="btn btn-sm btn-danger delete-btn" data-id="${data.id_rapat}">
            <i class="fas fa-trash"></i>
          </button>
        `;
      }}
    ],
    "order": [[2, "desc"]] // Sort by date
  });

  // Load kegiatan dropdown
  function loadKegiatan() {
    $.get("/backend/controllers/admin_ukm/rapat/get_kegiatan.php", function(response) {
      if(response.status === 'success') {
        let options = '<option value="">Pilih Kegiatan (Opsional)</option>';
        response.data.forEach(function(item) {
          options += <option value="${item.id_timeline}">${item.judul_kegiatan} (${item.tanggal_kegiatan})</option>;
        });
        $('#id_timeline').html(options);
      }
    });
  }

  // Handle view dokumentasi
  $('#table-rapat').on('click', '.view-dokumentasi', function() {
    let id = $(this).data('id');
    $.get(/backend/controllers/admin_ukm/rapat/get_dokumentasi.php?id=${id}, function(response) {
      if(response.status === 'success') {
        let gallery = '';
        response.data.forEach(function(item) {
          gallery += `
            <div class="col-md-4 mb-3">
              <img src="/uploads/dokumentasi_rapat/${item.foto_path}" class="img-fluid">
            </div>
          `;
        });
        $('#dokumentasi-gallery').html(gallery || 'Tidak ada dokumentasi');
        $('#modal-dokumentasi').modal('show');
      }
    });
  });

  // Preview selected images
  $('.dokumentasi-file').change(function() {
    let preview = $('#preview-dokumentasi');
    preview.empty();
    
    if (this.files) {
      [...this.files].forEach(function(file) {
        let reader = new FileReader();
        reader.onload = function(e) {
          preview.append(`
            <div class="col-md-4 mb-2">
              <img src="${e.target.result}" class="img-thumbnail">
            </div>
          `);
        }
        reader.readAsDataURL(file);
      });
    }
  });

  // Handle form submit
  $('#form-rapat').on('submit', function(e) {
    e.preventDefault();
    
    let formData = new FormData(this);
    let url = $('#id_rapat').val() ? 
      '/backend/controllers/admin_ukm/rapat/update.php' : 
      '/backend/controllers/admin_ukm/rapat/create.php';

    $.ajax({
      url: url,
      type: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      success: function(response) {
        if(response.status === 'success') {
          alert('Data berhasil disimpan!');
          $('#modal-form').modal('hide');
          table.ajax.reload();
        } else {
          alert('Gagal menyimpan data: ' + response.message);
        }
      }
    });
  });

  // Handle edit button
  $('#table-rapat').on('click', '.edit-btn', function() {
    let id = $(this).data('id');
    $('#modal-title').text('Edit Rapat');
    
    $.get(/backend/controllers/admin_ukm/rapat/get_single.php?id=${id}, function(response) {
      $('#id_rapat').val(response.data.id_rapat);
      $('#judul').val(response.data.judul);
      $('#tanggal').val(response.data.tanggal);
      $('#id_timeline').val(response.data.id_timeline);
      
      if(response.data.notulensi_path) {
        $('#preview-notulensi').html(`
          <a href="/uploads/notulensi/${response.data.notulensi_path}" target="_blank" class="btn btn-info btn-sm">
            <i class="fas fa-file-alt"></i> Lihat Notulensi Saat Ini
          </a>
        `);
      }
      
      $('#modal-form').modal('show');
    });
  });

  // Handle delete button
  $('#table-rapat').on('click', '.delete-btn', function() {
    if(confirm('Apakah Anda yakin ingin menghapus rapat ini?')) {
      let id = $(this).data('id');
      $.ajax({
        url: '/backend/controllers/admin_ukm/rapat/delete.php',
        type: 'POST',
        data: {id_rapat: id},
        success: function(response) {
          if(response.status === 'success') {
            alert('Data berhasil dihapus!');
            table.ajax.reload();
          } else {
            alert('Gagal menghapus data: ' + response.message);
          }
        }
      });
    }
  });

  // Reset form when modal is closed
  $('#modal-form').on('hidden.bs.modal', function() {
    $('#form-rapat')[0].reset();
    $('#id_rapat').val('');
    $('#preview-dokumentasi').empty();
    $('#preview-notulensi').empty();
    $('#modal-title').text('Tambah Rapat');
  });

  // Load kegiatan when page loads
  loadKegiatan();
});
</script>
</body>
</html>