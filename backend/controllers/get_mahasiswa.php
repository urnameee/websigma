<?php
session_start();
require_once '../config/config.php';
header('Content-Type: application/json');

try {
    if (isset($_SESSION['username'])) {
        $username = $_SESSION['username'];
        
        // Query untuk data mahasiswa dan UKM
        $stmt = $pdo->prepare("
            SELECT 
                m.nim, m.nama_lengkap, m.email, m.kelas,
                u.nama_ukm, u.logo_path, k.status as status_keanggotaan,
                pk.tahun_mulai, pk.tahun_selesai, pk.status as status_periode
            FROM mahasiswa m
            LEFT JOIN keanggotaan_ukm k ON m.nim = k.nim
            LEFT JOIN ukm u ON k.id_ukm = u.id_ukm
            LEFT JOIN periode_kepengurusan pk ON k.id_periode = pk.id_periode
            WHERE m.nim = :username");
        
        $stmt->execute(['username' => $username]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (count($result) > 0) {
            $response = [
                'profile' => [
                    'nim' => $result[0]['nim'],
                    'nama_lengkap' => $result[0]['nama_lengkap'],
                    'email' => $result[0]['email'],
                    'kelas' => $result[0]['kelas']
                ],
                'ukm_aktif' => [],
                'ukm_histori' => []
            ];
            
            // Kategorikan UKM
            foreach ($result as $row) {
                if ($row['nama_ukm']) {
                    $ukm_data = [
                        'nama_ukm' => $row['nama_ukm'],
                        'logo_ukm' => $row['logo_path'],
                        'status' => $row['status_keanggotaan'],
                        'status_periode' => $row['status_periode'],
                        'periode' => $row['tahun_mulai'] . '/' . $row['tahun_selesai']
                    ];
                    
                    // Jika periode aktif, masukkan ke UKM aktif
                    if ($row['status_periode'] === 'aktif') {
                        $response['ukm_aktif'][] = $ukm_data;
                    }
                    // Jika periode tidak aktif, masukkan ke histori
                    else {
                        $response['ukm_histori'][] = $ukm_data;
                    }
                }
            }
            
            echo json_encode($response);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Data tidak ditemukan']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    }
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
}
?>