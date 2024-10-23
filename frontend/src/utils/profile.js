document.addEventListener('DOMContentLoaded', function() {
    // Ganti dengan NIM yang sesuai
    const nim = '43323223'; // Bisa diambil dari session/localStorage

    fetch(`/controllers/profile.php?nim=${nim}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                document.querySelector('.profile-name').textContent = data.data.nama_lengkap;
                document.querySelector('.profile-nim').textContent = data.data.nim;
            } else {
                console.error('Error:', data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
}); 