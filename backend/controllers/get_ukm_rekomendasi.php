<?php
session_start();
require_once '../config/config.php';
header('Content-Type: application/json');

// Pastikan user sudah login
if (!isset($_SESSION['username'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'User belum login'
    ]);
    exit;
}

try {
    $username = $_SESSION['username'];
    
    // Query untuk mendapatkan UKM yang TIDAK diikuti oleh mahasiswa
    $query = "
        SELECT u.id_ukm, u.nama_ukm, u.deskripsi, u.logo_path,
               pk.tahun_mulai, pk.tahun_selesai
        FROM ukm u
        CROSS JOIN periode_kepengurusan pk
        WHERE pk.status = 'aktif'
        AND u.id_ukm NOT IN (
            SELECT ku.id_ukm 
            FROM keanggotaan_ukm ku 
            INNER JOIN user_login ul ON ku.nim = ul.username
            WHERE ul.username = :username
        )
        ORDER BY u.nama_ukm ASC
    ";
    
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    
    $ukm_rekomendasi = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if ($ukm_rekomendasi) {
        echo json_encode([
            'status' => 'success',
            'data' => $ukm_rekomendasi
        ]);
    } else {
        echo json_encode([
            'status' => 'success',
            'data' => [],
            'message' => 'Tidak ada UKM rekomendasi'
        ]);
    }

} catch (PDOException $e) {
    error_log($e->getMessage());
    echo json_encode([
        'status' => 'error',
        'message' => 'Terjadi kesalahan pada server'
    ]);
}
?>