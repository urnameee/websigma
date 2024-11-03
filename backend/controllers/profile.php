<?php
session_start();
require_once '../config/config.php';

header('Content-Type: application/json');

try {
    if (!isset($_SESSION['username'])) {
        echo json_encode([
            'status' => 'error',
            'message' => 'User not logged in'
        ]);
        exit;
    }

    $nim = $_SESSION['username'];

    // Query untuk mengambil data mahasiswa
    $query = "SELECT * FROM mahasiswa WHERE nim = :nim"; 

    $stmt = $pdo->prepare($query);
    $stmt->execute(['nim' => $nim]);
    $profile_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'status' => 'success',
        'data' => $profile_data
    ]);

} catch (PDOException $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Terjadi kesalahan: ' . $e->getMessage()
    ]);
}
?>