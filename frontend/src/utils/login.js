// Event listener untuk menyembunyikan error message saat user mulai mengetik
document.getElementById('username').addEventListener('input', function() {
    const errorMessageContainer = document.getElementById('errorMessageContainer');
    errorMessageContainer.style.display = 'none';
});

document.getElementById('password').addEventListener('input', function() {
    const errorMessageContainer = document.getElementById('errorMessageContainer');
    errorMessageContainer.style.display = 'none';
});

// Function untuk menampilkan error message
function showErrorMessage(message) {
    const errorMessageContainer = document.getElementById('errorMessageContainer');
    const errorMessageText = document.getElementById('errorMessageText');
    
    errorMessageText.textContent = message;
    errorMessageContainer.style.display = 'flex';
    
    // Optional: Auto hide after 5 seconds
    setTimeout(() => {
        errorMessageContainer.style.display = 'none';
    }, 5000);
}

// Function untuk validasi input
function validateInput(username, password) {
    if (!username.trim()) {
        showErrorMessage('Silakan masukkan username');
        return false;
    }
    if (!password.trim()) {
        showErrorMessage('Silakan masukkan password');
        return false;
    }
    return true;
}

// Event listener untuk form submission
document.getElementById('loginForm').addEventListener('submit', async function(event) {
    event.preventDefault();
    
    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;
    
    // Validasi input
    if (!validateInput(username, password)) {
        return;
    }
    
    try {
        const response = await fetch('/backend/controllers/login.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ username, password })
        });
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const result = await response.json();
        
        if (result.status === 'success') {
            // Hide any error messages if they exist
            document.getElementById('errorMessageContainer').style.display = 'none';
            // Redirect ke halaman yang sesuai
            window.location.href = '/frontend/src/pages/user/beranda.html';
        } else {
            showErrorMessage(result.message || 'Maaf, username atau password yang anda masukan salah.');
        }
    } catch (error) {
        console.error('Error:', error);
        showErrorMessage('Terjadi kesalahan pada server. Silakan coba lagi.');
    }
});

// Optional: Close error message when clicking on it
document.getElementById('errorMessageContainer').addEventListener('click', function() {
    this.style.display = 'none';
});