// Load Data untuk Dropdowns
async function loadDropdowns() {
    try {
        const response = await fetch('backend/controllers/admin-ukm/keanggotaan.php?action=dropdowns');
        const data = await response.json();

        // Populate periode dropdowns (filter dan form)
        const periodeHtml = data.periode.map(p => 
            `<option value="${p.id_periode}">${p.tahun_mulai}/${p.tahun_selesai}</option>`
        ).join('');
        $('#filter-periode, #id_periode').append(periodeHtml);

        // Populate prodi filter
        const prodiHtml = data.prodi.map(p => 
            `<option value="${p.id_program_studi}">${p.nama_program_studi}</option>`
        ).join('');
        $('#filter-prodi').append(prodiHtml);

        // Populate mahasiswa dropdown
        const mahasiswaHtml = data.mahasiswa.map(m => 
            `<option value="${m.nim}">${m.nim} - ${m.nama_lengkap}</option>`
        ).join('');
        $('#nim').append(mahasiswaHtml);
    } catch (error) {
        Swal.fire('Error', 'Gagal memuat data dropdown', 'error');
    }
}

// Initialize DataTable
const table = $('#table-anggota').DataTable({
    processing: true,
    serverSide: false,
    ajax: {
        url: 'backend/controllers/admin-ukm/keanggotaan.php',
        data: function(d) {
            return {
                ...d,
                periode: $('#filter-periode').val(),
                status: $('#filter-status').val()
            };
        }
    },
    columns: [
        { 
            data: null,
            render: (data, type, row, meta) => meta.row + 1
        },
        { data: 'nim' },
        { data: 'nama_lengkap' },
        { data: 'nama_program_studi' },
        { 
            data: 'status',
            render: data => data.charAt(0).toUpperCase() + data.slice(1)
        },
        { 
            data: 'tanggal_bergabung',
            render: function(data) {
                if (!data) return '-';
                return new Date(data).toLocaleDateString('id-ID', {
                    day: '2-digit',
                    month: 'long',
                    year: 'numeric'
                });
            }
        },
        { data: 'periode' },
        {
            data: null,
            orderable: false,
            render: function(data) {
                return `
                    <button class="btn btn-sm btn-warning edit-btn" data-id="${data.id_keanggotaan}">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-danger delete-btn" data-id="${data.id_keanggotaan}">
                        <i class="fas fa-trash"></i>
                    </button>
                `;
            }
        }
    ],
    order: [[2, 'asc']], // Sort by nama_lengkap
    responsive: true,
    autoWidth: false,
    language: {
        url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json'
    }
});

// Modifikasi search box default DataTables
$('.dataTables_filter input').attr('placeholder', 'Cari NIM/Nama...');

// Event Handlers untuk Filter
$('#filter-periode, #filter-status, #filter-prodi').on('change', () => table.ajax.reload());

// Handle form submit untuk Create/Update
$('#form-anggota').on('submit', async function(e) {
    e.preventDefault();
    try {
        const formData = new FormData(this);
        const id = $('#id_keanggotaan').val();
        const url = id ? 
            `backend/controllers/admin-ukm/keanggotaan.php?action=update&id=${id}` : 
            'backend/controllers/admin-ukm/keanggotaan.php?action=create';
        
        const response = await fetch(url, {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (!response.ok) throw new Error(result.error || 'Terjadi kesalahan');
        
        await Swal.fire('Sukses', 'Data berhasil disimpan', 'success');
        $('#modal-form').modal('hide');
        table.ajax.reload();
    } catch (error) {
        Swal.fire('Error', error.message, 'error');
    }
});

// Handle Edit Button
$('#table-anggota').on('click', '.edit-btn', async function() {
    try {
        const id = $(this).data('id');
        const response = await fetch(`backend/controllers/admin-ukm/keanggotaan.php?action=show&id=${id}`);
        const data = await response.json();
        
        if (!response.ok) throw new Error(data.error || 'Terjadi kesalahan');
        
        // Populate form
        $('#id_keanggotaan').val(data.id_keanggotaan);
        $('#nim').val(data.nim);
        $('#status').val(data.status);
        $('#id_periode').val(data.id_periode);
        
        // Update modal title
        $('.modal-title').text('Edit Anggota');
        $('#modal-form').modal('show');
    } catch (error) {
        Swal.fire('Error', error.message, 'error');
    }
});

// Handle Delete Button
$('#table-anggota').on('click', '.delete-btn', async function() {
    try {
        const result = await Swal.fire({
            title: 'Konfirmasi',
            text: 'Yakin ingin menghapus data ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        });
        
        if (result.isConfirmed) {
            const id = $(this).data('id');
            const response = await fetch(
                `backend/controllers/admin-ukm/keanggotaan.php?action=delete&id=${id}`, 
                { method: 'DELETE' }
            );
            
            const data = await response.json();
            
            if (!response.ok) throw new Error(data.error || 'Terjadi kesalahan');
            
            await Swal.fire('Sukses', 'Data berhasil dihapus', 'success');
            table.ajax.reload();
        }
    } catch (error) {
        Swal.fire('Error', error.message, 'error');
    }
});

// Handle Modal Close
$('#modal-form').on('hidden.bs.modal', function() {
    $('#form-anggota')[0].reset();
    $('#id_keanggotaan').val('');
    $('.modal-title').text('Tambah Anggota');
});

// Initialize
$(document).ready(function() {
    loadDropdowns();
});