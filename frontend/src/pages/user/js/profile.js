document.addEventListener('DOMContentLoaded', function() {
    // Ambil NIM dari sessionStorage atau localStorage
    const nim = sessionStorage.getItem('nim') || localStorage.getItem('nim') || '43323223'; // Pastikan Anda mengganti dengan cara yang sesuai untuk mendapatkan NIM

    // Lakukan fetch untuk mengambil data profil mahasiswa
    fetch(`/backend/controllers/profile.php?nim=${nim}`) // Ganti dengan path yang sesuai
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                // Update elemen HTML dengan data yang diterima
                document.querySelector('.profile-name').textContent = data[0].nama_lengkap; // Pastikan ini sesuai dengan struktur data yang diterima
                document.querySelector('.profile-nim').textContent = data[0].nim; // Pastikan ini sesuai dengan struktur data yang diterima
            } else {
                console.error('Error:', data.message);
                // Tampilkan pesan error di UI jika perlu
                showErrorMessage(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            // Tampilkan pesan error di UI jika terjadi kesalahan
            showErrorMessage('Terjadi kesalahan saat mengambil data profil.');
        });
});

// Fungsi untuk menampilkan pesan error
function showErrorMessage(message) {
    const errorMessageContainer = document.getElementById('errorMessageContainer');
    const errorMessageText = document.getElementById('errorMessageText');
    
    errorMessageText.textContent = message;
    errorMessageContainer.style.display = 'flex';
    
    setTimeout(() => {
        errorMessageContainer.style.display = 'none';
    }, 5000);
}
