// login.js
document.getElementById('username').addEventListener('input', function() {
    const errorMessageContainer = document.getElementById('errorMessageContainer');
    errorMessageContainer.style.display = 'none';
});

document.getElementById('password').addEventListener('input', function() {
    const errorMessageContainer = document.getElementById('errorMessageContainer');
    errorMessageContainer.style.display = 'none';
});

function showErrorMessage(message) {
    const errorMessageContainer = document.getElementById('errorMessageContainer');
    const errorMessageText = document.getElementById('errorMessageText');
    
    errorMessageText.textContent = message;
    errorMessageContainer.style.display = 'flex';
    
    setTimeout(() => {
        errorMessageContainer.style.display = 'none';
    }, 5000);
}

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

// Handle role-based redirect
function handleRoleRedirect(role) {
    switch(role.toLowerCase()) {
        case 'mahasiswa':
            window.location.href = '/frontend/src/pages/user/beranda.html';
            break;
        case 'super_admin':
            window.location.href = '/frontend/src/pages/admin/dashboard.html';
            break;
        default:
            showErrorMessage('Role tidak valid');
            break;
    }
}

document.getElementById('loginForm').addEventListener('submit', async function(event) {
    event.preventDefault();
    
    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;
    
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
            throw new Error('HTTP error! status: ${response.status}');
        }
        
        const result = await response.json();
        
        if (result.status === 'success') {
            document.getElementById('errorMessageContainer').style.display = 'none';
            // Use the role from the response to determine redirect
            handleRoleRedirect(result.user.role);
        } else {
            showErrorMessage(result.message || 'Maaf, username atau password yang anda masukan salah.');
        }
    } catch (error) {
        console.error('Error:', error);
        showErrorMessage('Terjadi kesalahan pada server. Silakan coba lagi.');
    }
});

document.getElementById('errorMessageContainer').addEventListener('click', function() {
    this.style.display = 'none';
});

// agar berwarna saat di klik
document.addEventListener("DOMContentLoaded", function () {
    const loginForm = document.getElementById("loginForm");
    const loginButton = document.getElementById("loginButton");

    loginForm.addEventListener("input", function () {
        const username = document.getElementById("username").value;
        const password = document.getElementById("password").value;

        if (username && password) {
            loginButton.classList.add("active");
            loginButton.disabled = false;
        } else {
            loginButton.classList.remove("active");
            loginButton.disabled = true;
        }
    });
});
