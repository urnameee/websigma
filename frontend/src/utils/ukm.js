// Mendapatkan data UKM dari API
fetch('/backend/controllers/get_ukm.php') // Sesuaikan jalur ini jika diperlukan
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json(); // Mengembalikan hasil dalam format JSON
    })
    .then(data => {
        const ukmGrid = document.querySelector('.ukm-grid');
        ukmGrid.innerHTML = ''; // Kosongkan grid sebelum menambahkan data baru
        
        // Iterasi melalui data UKM dan membuat elemen HTML untuk masing-masing UKM
        data.forEach(ukm => {
            const ukmCard = document.createElement('a');
            ukmCard.href = '/frontend/src/pages/user/ukm_detail_not_registered.html'; // Sesuaikan dengan link yang sesuai
            ukmCard.className = 'ukm-card';

            const logo = document.createElement('img');
            logo.src = ukm.logo_path; // Menggunakan logo_path dari data
            logo.alt = `${ukm.nama_ukm} Logo`;
            logo.className = 'ukm-logo';

            const nameDiv = document.createElement('div');
            nameDiv.className = 'ukm-name';
            nameDiv.textContent = ukm.nama_ukm;

            const descDiv = document.createElement('div');
            descDiv.className = 'ukm-desc';
            descDiv.textContent = ukm.deskripsi;

            // Menambahkan elemen ke dalam kartu UKM
            ukmCard.appendChild(logo);
            ukmCard.appendChild(nameDiv);
            ukmCard.appendChild(descDiv);
            ukmGrid.appendChild(ukmCard); // Menambahkan kartu ke grid
        });
    })
    .catch(error => {
        console.error('Ada masalah dengan pengambilan data:', error);
    });
