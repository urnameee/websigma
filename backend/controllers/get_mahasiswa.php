<?php
session_start();
require_once '../config/config.php';

header('Content-Type: application/json');

try {
    // Ambil username dari sesi
    if (isset($_SESSION['username'])) {
        $username = $_SESSION['username'];

        // Query untuk mengambil data mahasiswa berdasarkan username (nim)
        $stmt = $pdo->prepare("SELECT nim, nama_lengkap, email, kelas FROM mahasiswa WHERE nim = :username");
        $stmt->execute(['username' => $username]);

        // Menyimpan data mahasiswa ke array
        $mahasiswa = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Mengembalikan data dalam format JSON
        echo json_encode($mahasiswa);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    }
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
}
?>