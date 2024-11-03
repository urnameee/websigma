document.addEventListener("DOMContentLoaded", function() {
    // Cek auth dan ambil data profile
    fetch('/backend/controllers/auth.php')
        .then(response => response.json())
        .then(data => {
            if (!data.authenticated) {
                window.location.href = '/index.html';
            } else {
                loadProfileData();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            window.location.href = '/index.html';
        });
});

function loadProfileData() {
    fetch('/backend/controllers/get_mahasiswa.php')
        .then(response => response.json())
        .then(data => {
            if (data.profile) {
                // Update informasi profile
                document.getElementById('profileName').textContent = data.profile.nama_lengkap;
                document.getElementById('profileNim').textContent = data.profile.nim;
                
                // Update UKM Aktif
                const ukmAktifContainer = document.getElementById('ukmAktifContainer');
                ukmAktifContainer.innerHTML = '';
                if (data.ukm_aktif && data.ukm_aktif.length > 0) {
                    data.ukm_aktif.forEach(ukm => {
                        const card = createUkmCard(ukm);
                        ukmAktifContainer.appendChild(card);
                    });
                } else {
                    ukmAktifContainer.innerHTML = '<p class="info-text">Tidak ada UKM yang sedang diikuti</p>';
                }

                // Update Histori UKM
                const ukmHistoriContainer = document.getElementById('ukmHistoriContainer');
                ukmHistoriContainer.innerHTML = '';
                if (data.ukm_histori && data.ukm_histori.length > 0) {
                    data.ukm_histori.forEach(ukm => {
                        const card = createUkmCard(ukm);
                        ukmHistoriContainer.appendChild(card);
                    });
                } else {
                    ukmHistoriContainer.innerHTML = '<p class="info-text">Tidak ada riwayat UKM</p>';
                }

            } else if (data.status === 'error') {
                console.error(data.message);
                showError();
            }
        })
        .catch(error => {
            console.error('Error loading profile:', error);
            showError();
        });
}

function createUkmCard(ukm) {
    const card = document.createElement('div');
    card.className = 'card';
    
    const statusText = ukm.status ? `${ukm.status} - ` : '';
    const periodeText = `Periode ${ukm.periode}`;
    
    card.innerHTML = `
        <img src="/frontend/public/assets/${ukm.logo_ukm}" 
             alt="${ukm.nama_ukm}" 
             class="ukm-logo"
             onerror="this.src='/frontend/public/assets/default-ukm-logo.png'">
        <div class="card-content">
            <h2 class="card-title">${ukm.nama_ukm}</h2>
            <p class="card-description">${statusText}${periodeText}</p>
        </div>
    `;
    
    return card;
}

function showError() {
    document.getElementById('profileName').textContent = 'Error loading data';
    document.getElementById('profileNim').textContent = 'Please try again later';
    
    const containers = ['ukmAktifContainer', 'ukmHistoriContainer'];
    containers.forEach(containerId => {
        const container = document.getElementById(containerId);
        container.innerHTML = '<p class="error-text">Error loading UKM data</p>';
    });
}