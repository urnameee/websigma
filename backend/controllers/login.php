<?php
session_start();
require_once '../config/config.php';

// Set header untuk mengembalikan response JSON
header('Content-Type: application/json');

// Mendapatkan data dari request (bisa melalui POST atau JSON)
$data = json_decode(file_get_contents("php://input"), true);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($data['username']) && isset($data['password'])) {
    $username = $data['username'];
    $password = $data['password'];

    // Query untuk mencari user di database
    $stmt = $pdo->prepare("SELECT * FROM user_login WHERE username = :username AND password = :password");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password); // Password tidak di-hash sesuai preferensi kamu
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Simpan informasi user ke session
        $_SESSION['id_login'] = $user['id_login'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        // Kirim response sukses dalam format JSON
        echo json_encode([
            'status' => 'success',
            'message' => 'Login berhasil',
            'user' => [
                'id_login' => $user['id_login'],
                'username' => $user['username'],
                'role' => $user['role']
            ]
        ]);
    } else {
        // Jika login gagal, kirim response error
        echo json_encode([
            'status' => 'error',
            'message' => 'Username atau password salah'
        ]);
    }
} else {
    // Jika request method bukan POST atau data tidak lengkap
    echo json_encode([
        'status' => 'error',
        'message' => 'Request tidak valid'
    ]);
}
?>