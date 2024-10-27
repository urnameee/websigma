<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../config/config.php';
header('Content-Type: application/json');

if (!isset($_GET['id'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'ID tidak ditemukan'
    ]);
    exit;
}

$id_timeline = $_GET['id'];

try {
    $response = [];

    /**
     * Get Detail Program Kerja
     * Mengambil informasi detail program kerja dan UKM terkait
     */
    $stmt = $pdo->prepare("
        SELECT t.*, u.nama_ukm, u.banner_path 
        FROM timeline_ukm t
        JOIN ukm u ON t.id_ukm = u.id_ukm 
        WHERE t.id_timeline = ?
    ");
    $stmt->execute([$id_timeline]);
    $timeline = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$timeline) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Program kerja tidak ditemukan'
        ]);
        exit;
    }
    $response['timeline'] = $timeline;

    /**
     * Get Sidebar Programs
     * Mengambil daftar program kerja untuk ditampilkan di sidebar
     */
    $stmt = $pdo->prepare("
        SELECT id_timeline, judul_kegiatan 
        FROM timeline_ukm 
        WHERE id_ukm = ? AND status = 'active'
        ORDER BY tanggal_kegiatan DESC
    ");
    $stmt->execute([$timeline['id_ukm']]);
    $response['sidebar_programs'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

    /**
     * Get Panitia
     * Mengambil daftar panitia beserta jabatan dan informasi mahasiswa
     */
    $stmt = $pdo->prepare("
        SELECT 
            p.*,
            m.nama_lengkap,
            m.kelas,
            jp.nama_jabatan,
            jp.level
        FROM panitia_proker p
        JOIN mahasiswa m ON p.nim = m.nim
        JOIN jabatan_panitia jp ON p.id_jabatan_panitia = jp.id_jabatan_panitia
        WHERE p.id_timeline = ?
        ORDER BY jp.level ASC, jp.nama_jabatan ASC
    ");
    $stmt->execute([$id_timeline]);
    $response['panitia'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

    /**
     * Get Rapat Data
     * Mengambil informasi rapat terkait program kerja
     */
    $stmt = $pdo->prepare("
        SELECT 
            r.id_rapat,
            r.judul,
            r.tanggal,
            r.notulensi_path
        FROM rapat r
        WHERE r.id_timeline = ?
        ORDER BY r.tanggal DESC
    ");
    $stmt->execute([$id_timeline]);
    $rapat_list = $stmt->fetchAll(PDO::FETCH_ASSOC);

    /**
     * Get Dokumentasi Rapat
     * Mengambil foto dokumentasi untuk setiap rapat
     */
    foreach($rapat_list as &$rapat) {
        $stmt = $pdo->prepare("
            SELECT foto_path
            FROM dokumentasi_rapat
            WHERE id_rapat = ?
        ");
        $stmt->execute([$rapat['id_rapat']]);
        $rapat['dokumentasi'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    $response['rapat'] = $rapat_list;

    /**
     * Format Response
     * Menyusun respons dalam format yang sesuai
     */
    $response['status'] = 'success';
    $response['message'] = 'Data berhasil diambil';

    echo json_encode($response);

} catch(PDOException $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Terjadi kesalahan: ' . $e->getMessage()
    ]);
}
?>