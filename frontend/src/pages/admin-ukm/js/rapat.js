// rapat.js
$(document).ready(function () {
    // Manage Rapat button click
    $(document).on('click', '.manage-rapat', function() {
        const id_timeline = $(this).data('id');
        $('#id_timeline_rapat').val(id_timeline);
        loadRapat(id_timeline);
        $('#modal-rapat').modal('show');
    });
 
    // Function untuk load data rapat
    function loadRapat(id_timeline) {
        $.ajax({
            url: `/backend/controllers/admin-ukm/rapat.php?id_timeline=${id_timeline}`,
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                console.log('Data rapat:', data);
                const tbody = $('#table-rapat tbody');
                tbody.empty();
                
                if (data && data.length > 0) {
                    data.forEach((item, index) => {
                        tbody.append(`
                            <tr>
                                <td>${index + 1}</td>
                                <td>${item.judul}</td>
                                <td>${formatDate(item.tanggal)}</td>
                                <td>
                                    ${item.notulensi_path ? 
                                        `<a href="/frontend/public/assets/${item.notulensi_path}" target="_blank" class="btn btn-sm btn-primary">
                                            <i class="fas fa-file-pdf"></i> Download
                                        </a>` : 
                                        'Tidak ada file'}
                                </td>
                                <td>
                                    ${item.dokumentasi && item.dokumentasi.length > 0 ? 
                                        `<button class="btn btn-sm btn-info preview-dokumentasi" data-dokumentasi='${JSON.stringify(item.dokumentasi)}'>
                                            <i class="fas fa-images"></i> Lihat Foto (${item.dokumentasi.length})
                                        </button>` : 
                                        'Tidak ada foto'}
                                </td>
                                <td>
                                    <button class="btn btn-primary btn-sm edit-rapat" data-id="${item.id_rapat}">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <button class="btn btn-danger btn-sm delete-rapat" data-id="${item.id_rapat}">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </td>
                            </tr>
                        `);
                    });
                } else {
                    tbody.append(`
                        <tr>
                            <td colspan="6" class="text-center">Belum ada rapat</td>
                        </tr>
                    `);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', xhr.responseText);
            }
        });
    }
 
    // Preview dokumentasi click
    $(document).on('click', '.preview-dokumentasi', function() {
        const dokumentasi = $(this).data('dokumentasi');
        const carouselInner = $('#dokumentasi-carousel .carousel-inner');
        carouselInner.empty();
        
        dokumentasi.forEach((foto, index) => {
            carouselInner.append(`
                <div class="carousel-item ${index === 0 ? 'active' : ''}">
                    <img src="/frontend/public/assets/${foto.foto_path}" class="d-block w-100" alt="Dokumentasi">
                </div>
            `);
        });
        
        $('#modal-preview-dokumentasi').modal('show');
    });
 
    // Edit rapat button click
    $(document).on('click', '.edit-rapat', function() {
        const id_rapat = $(this).data('id');
        $.ajax({
            url: `/backend/controllers/admin-ukm/rapat.php?id_rapat=${id_rapat}`,
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                $('#id_rapat').val(data.id_rapat);
                $('#judul_rapat').val(data.judul);
                $('#tanggal_rapat').val(data.tanggal);
                
                if (data.dokumentasi && data.dokumentasi.length > 0) {
                    const previewDiv = $('#preview-dokumentasi');
                    previewDiv.empty();
                    data.dokumentasi.forEach(foto => {
                        previewDiv.append(`
                            <div class="col-md-3 mb-2">
                                <img src="/frontend/public/assets/${foto.foto_path}" class="img-thumbnail" alt="Dokumentasi">
                            </div>
                        `);
                    });
                }
                
                $('#form-rapat button[type="submit"]').text('Update Rapat');
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', xhr.responseText);
                Swal.fire('Error!', 'Gagal mengambil data rapat', 'error');
            }
        });
    });
 
    // Form submit untuk tambah/edit rapat
    $('#form-rapat').on('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        
        $.ajax({
            url: '/backend/controllers/admin-ukm/rapat.php',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: formData.get('id_rapat') ? 'Rapat berhasil diupdate' : 'Rapat berhasil ditambahkan',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    loadRapat($('#id_timeline_rapat').val());
                    loadTimeline(); // Refresh timeline untuk update jumlah rapat
                    $('#form-rapat')[0].reset();
                    $('#preview-dokumentasi').empty();
                    $('#form-rapat button[type="submit"]').text('Tambah Rapat');
                } else {
                    Swal.fire('Error!', response.message, 'error');
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', xhr.responseText);
                Swal.fire('Error!', 'Gagal menyimpan data rapat', 'error');
            }
        });
    });
 
    // Delete rapat button click
    $(document).on('click', '.delete-rapat', function() {
        const id_rapat = $(this).data('id');
        
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Rapat akan dihapus beserta dokumentasinya!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/backend/controllers/admin-ukm/rapat.php?id_rapat=${id_rapat}`,
                    method: 'DELETE',
                    success: function(response) {
                        if (response.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Terhapus!',
                                text: 'Rapat berhasil dihapus',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            loadRapat($('#id_timeline_rapat').val());
                            loadTimeline(); // Refresh timeline untuk update jumlah rapat
                        } else {
                            Swal.fire('Error!', response.message, 'error');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', xhr.responseText);
                        Swal.fire('Error!', 'Gagal menghapus rapat', 'error');
                    }
                });
            }
        });
    });
 
    // Preview dokumentasi yang akan diupload
    $('#dokumentasi').on('change', function() {
        const files = this.files;
        const previewDiv = $('#preview-dokumentasi');
        previewDiv.empty();
        
        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewDiv.append(`
                        <div class="col-md-3 mb-2">
                            <img src="${e.target.result}" class="img-thumbnail" alt="Preview">
                        </div>
                    `);
                }
                reader.readAsDataURL(file);
            }
        }
    });
 
    // Helper function untuk format tanggal
    function formatDate(dateString) {
        if (!dateString) return 'N/A';
        const date = new Date(dateString);
        return new Intl.DateTimeFormat('id-ID', {
            day: '2-digit',
            month: 'long',
            year: 'numeric'
        }).format(date);
    }
 });