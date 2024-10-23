<?php
// Memulai session (jika diperlukan untuk fitur login)
session_start();

// Mengambil koneksi dari config.php
require_once '../config/config.php';

// Set header untuk mengembalikan response dalam format JSON
header('Content-Type: application/json');

try {
    // Query untuk mengambil data nim dan nama_lengkap dari tabel mahasiswa
    $stmt = $pdo->query("SELECT nim, nama_lengkap, email, kelas FROM mahasiswa");
    
    // Menyimpan data mahasiswa ke array
    $mahasiswa = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $mahasiswa[] = $row;
    }

    // Mengembalikan data dalam format JSON
    echo json_encode($mahasiswa);
} catch (PDOException $e) {
    // Jika ada error, kirim response dengan status error
    echo json_encode([
        'status' => 'error',
        'message' => 'Terjadi kesalahan: ' . $e->getMessage()
    ]);
}
?>
