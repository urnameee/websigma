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
  <title>Struktur Organisasi UKM</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="/frontend/src/pages/admin/plugins/fontawesome-free/css/all.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="/frontend/src/pages/admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="/frontend/src/pages/admin/dist/css/adminlte.min.css">
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
          <<form id="form-struktur" enctype="multipart/form-data">
            <input type="hidden" id="id_struktur" name="id_struktur">
            <div class="modal-body">
                <div class="form-group">
                    <label for="nim">NIM</label>
                    <select class="form-control" id="nim" name="nim" required>
                        <option value="">Pilih Mahasiswa</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="id_jabatan">Jabatan</label>
                    <select class="form-control" id="id_jabatan" name="id_jabatan" required>
                        <option value="">Pilih Jabatan</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="id_periode">Periode</label>
                    <select class="form-control" id="id_periode" name="id_periode" required>
                        <option value="">Pilih Periode</option>
                    </select>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>

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
  // Ambil id_ukm dari session PHP
  const id_ukm = <?php echo $_SESSION['id_ukm']; ?>;
  
  bsCustomFileInput.init();
  loadStruktur();
  loadMahasiswa();
  loadJabatan();
  loadPeriode();

  // Function to load mahasiswa for dropdown
  function loadMahasiswa() {
    $.ajax({
      url: `/backend/controllers/admin-ukm/struktur_organisasi.php?action=get_mahasiswa&id_ukm=${id_ukm}`,
      method: 'GET',
      dataType: 'json',
      success: function(data) {
        const select = $('#nim');
        select.empty();
        select.append('<option value="">Pilih Mahasiswa</option>');
        data.forEach(function(item) {
          select.append(`<option value="${item.nim}">${item.nim} - ${item.nama_lengkap}</option>`);
        });
      },
      error: function(xhr, status, error) {
        console.error('AJAX Error:', status, error);
      }
    });
  }

  // Function khusus untuk load mahasiswa saat edit
  function loadMahasiswaForEdit(id_struktur, selectedNim) {
    $.ajax({
      url: `/backend/controllers/admin-ukm/struktur_organisasi.php?action=get_mahasiswa_edit&id_ukm=${id_ukm}&id_struktur=${id_struktur}`,
      method: 'GET',
      dataType: 'json',
      success: function(data) {
        const select = $('#nim');
        select.empty();
        select.append('<option value="">Pilih Mahasiswa</option>');
        data.forEach(function(item) {
          const selected = item.nim === selectedNim ? 'selected' : '';
          select.append(`<option value="${item.nim}" ${selected}>${item.nim} - ${item.nama_lengkap}</option>`);
        });
        select.val(selectedNim);
      },
      error: function(xhr, status, error) {
        console.error('AJAX Error:', status, error);
      }
    });
  }

  // Function to load jabatan for dropdown
  function loadJabatan() {
    $.ajax({
      url: '/backend/controllers/admin-ukm/struktur_organisasi.php?action=get_jabatan',
      method: 'GET',
      dataType: 'json',
      success: function(data) {
        const select = $('#id_jabatan');
        select.empty();
        select.append('<option value="">Pilih Jabatan</option>');
        data.forEach(function(item) {
          select.append(`<option value="${item.id_jabatan}">${item.nama_jabatan}</option>`);
        });
      },
      error: function(xhr, status, error) {
        console.error('AJAX Error:', status, error);
      }
    });
  }

  // Function to load periode for dropdown
  function loadPeriode() {
    $.ajax({
      url: '/backend/controllers/admin-ukm/struktur_organisasi.php?action=get_periode',
      method: 'GET',
      dataType: 'json',
      success: function(data) {
        const select = $('#id_periode');
        select.empty();
        select.append('<option value="">Pilih Periode</option>');
        data.forEach(function(item) {
          select.append(`<option value="${item.id_periode}">${item.tahun_mulai} - ${item.tahun_selesai}</option>`);
        });
      },
      error: function(xhr, status, error) {
        console.error('AJAX Error:', status, error);
      }
    });
  }

  // Function to load organizational structure data
  function loadStruktur() {
    $.ajax({
      url: `/backend/controllers/admin-ukm/struktur_organisasi.php?id_ukm=${id_ukm}`,
      method: 'GET',
      dataType: 'json',
      success: populateTable,
      error: function(xhr, status, error) {
        console.error('AJAX Error:', status, error);
      }
    });
  }

  // Populate table with data
    function populateTable(data) {
      const tbody = $('#table-struktur tbody');
      tbody.empty();
      $.each(data, function(index, item) {
        const row = `
          <tr>
            <td>${index + 1}</td>
            <td><img src='/frontend/public/assets/${item.foto_path || 'default.png'}' width='50' height='50' class="img-circle" alt='Foto'></td>
            <td>${item.nim || 'N/A'}</td>
            <td>${item.nama || 'N/A'}</td>
            <td>${item.jabatan || 'N/A'}</td>
            <td>${item.periode || 'N/A'}</td>
            <td>
              <button class="btn btn-primary btn-sm edit-btn" data-id="${item.id_struktur}">
                <i class="fas fa-edit"></i> Edit
              </button>
              <button class="btn btn-danger btn-sm delete-btn" data-id="${item.id_struktur}">
                <i class="fas fa-trash"></i> Delete
              </button>
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
      loadMahasiswa();
  });

  // Edit button click event
  $(document).on('click', '.edit-btn', function () {
      const id = $(this).data('id');
      $.ajax({
          url: '/backend/controllers/admin-ukm/struktur_organisasi.php?id_struktur=' + id,
          method: 'GET',
          dataType: 'json',
          success: function (data) {
              console.log('Data edit:', data); // Debug
              $('#id_struktur').val(data.id_struktur);
              
              // Load mahasiswa dan set selected value
              const select = $('#nim');
              select.empty();
              select.append('<option value="">Pilih Mahasiswa</option>');
              
              // Tambahkan opsi untuk mahasiswa yang sedang diedit
              select.append(`<option value="${data.nim}" selected>${data.nim} - ${data.nama_lengkap}</option>`);
              
              $('#id_jabatan').val(data.id_jabatan);
              $('#id_periode').val(data.id_periode);
              if (data.foto_path) {
                  $('#preview-foto').html(`<img src="/frontend/public/assets/${data.foto_path}" width="100" alt="Preview Foto">`);
              }
              $('#modal-title').text('Edit Pengurus');
              $('#modal-form').modal('show');
          },
          error: function (xhr, status, error) {
              console.error('AJAX Error:', status, error);
              alert('Gagal mengambil data pengurus');
          }
      });
  });

  // Form submission untuk add/edit
  $('#form-struktur').on('submit', function (event) {
      event.preventDefault();
      const formData = new FormData(this);
      formData.append('id_ukm', id_ukm);

      $.ajax({
          url: '/backend/controllers/admin-ukm/struktur_organisasi.php',
          method: 'POST',
          data: formData,
          contentType: false,
          processData: false,
          success: function (response) {
              if (response.status === 'success') {
                  Swal.fire({
                      icon: 'success',
                      title: 'Berhasil!',
                      text: formData.get('id_struktur') ? 'Data pengurus berhasil diperbarui' : 'Pengurus baru berhasil ditambahkan',
                      showConfirmButton: false,
                      timer: 1500
                  }).then(() => {
                      $('#modal-form').modal('hide');
                      loadStruktur();
                  });
              } else {
                  Swal.fire({
                      icon: 'error',
                      title: 'Gagal!',
                      text: response.message || 'Terjadi kesalahan saat menyimpan data'
                  });
              }
          },
          error: function (xhr, status, error) {
              console.error('AJAX Error:', status, error);
              Swal.fire({
                  icon: 'error',
                  title: 'Error!',
                  text: 'Terjadi kesalahan saat menghubungi server'
              });
          }
      });
  });

  // Delete button click event
  $(document).on('click', '.delete-btn', function () {
      const id = $(this).data('id');
      
      Swal.fire({
          title: 'Apakah Anda yakin?',
          text: "Data pengurus akan dihapus permanen!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Ya, hapus!',
          cancelButtonText: 'Batal'
      }).then((result) => {
          if (result.isConfirmed) {
              $.ajax({
                  url: '/backend/controllers/admin-ukm/struktur_organisasi.php?id_struktur=' + id,
                  method: 'DELETE',
                  success: function (response) {
                      if (response.status === 'success') {
                          Swal.fire({
                              icon: 'success',
                              title: 'Terhapus!',
                              text: 'Data pengurus berhasil dihapus',
                              showConfirmButton: false,
                              timer: 1500
                          }).then(() => {
                              loadStruktur();
                          });
                      } else {
                          Swal.fire({
                              icon: 'error',
                              title: 'Gagal!',
                              text: response.message || 'Gagal menghapus data pengurus'
                          });
                      }
                  },
                  error: function (xhr, status, error) {
                      console.error('AJAX Error:', status, error);
                      Swal.fire({
                          icon: 'error',
                          title: 'Error!',
                          text: 'Terjadi kesalahan saat menghubungi server'
                      });
                  }
              });
          }
      });
  });

  // Function to reset the modal form
  function resetForm() {
    $('#form-struktur')[0].reset();
    $('#id_struktur').val('');
    $('#preview-foto').empty();
  }

  // Function untuk konfirmasi sukses
  function showSuccessMessage(message) {
      Swal.fire({
          icon: 'success',
          title: 'Berhasil!',
          text: message,
          showConfirmButton: false,
          timer: 1500
      });
  }

  // Function untuk konfirmasi error
  function showErrorMessage(message) {
      Swal.fire({
          icon: 'error',
          title: 'Gagal!',
          text: message
      });
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

// Global logout function for sidebar
window.logout = function() {
    SidebarManager.logout();
};
</script>
</body>
</html>