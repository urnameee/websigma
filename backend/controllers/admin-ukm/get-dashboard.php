<?php
session_start();
header('Content-Type: application/json');

// Pastikan pengguna memiliki sesi yang valid
if (!isset($_SESSION['id_ukm'])) {
    echo json_encode(["error" => "Unauthorized"]);
    exit();
}

// Sertakan file konfigurasi database
require_once '../../config/config.php'; // Ini harus menyertakan koneksi PDO Anda

// Mendapatkan ID UKM dari sesi
$id_ukm = $_SESSION['id_ukm'];

// Inisialisasi respons
$response = [];

// Query untuk menghitung total anggota
$query = "SELECT COUNT(*) AS totalAnggota FROM keanggotaan_ukm WHERE id_ukm = :id_ukm"; 
$stmt = $pdo->prepare($query);
$stmt->bindParam(':id_ukm', $id_ukm, PDO::PARAM_INT);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$response['totalAnggota'] = $row['totalAnggota'];

// Query untuk menghitung total kegiatan
$query = "SELECT COUNT(*) AS totalKegiatan FROM timeline_ukm WHERE id_ukm = :id_ukm"; 
$stmt = $pdo->prepare($query);
$stmt->bindParam(':id_ukm', $id_ukm, PDO::PARAM_INT);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$response['totalKegiatan'] = $row['totalKegiatan'];

// Query untuk menghitung total rapat
$query = "SELECT COUNT(r.id_rapat) AS totalRapat 
          FROM rapat r 
          JOIN timeline_ukm t ON r.id_timeline = t.id_timeline 
          WHERE t.id_ukm = :id_ukm"; 
$stmt = $pdo->prepare($query);
$stmt->bindParam(':id_ukm', $id_ukm, PDO::PARAM_INT);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$response['totalRapat'] = $row['totalRapat'];

// Query untuk menampilkan nama timeline yang akan datang
// Query untuk menampilkan nama timeline yang akan datang
$query = "SELECT judul_kegiatan, tanggal_kegiatan
          FROM timeline_ukm 
          WHERE tanggal_kegiatan >= CURDATE() AND id_ukm = :id_ukm"; 
$stmt = $pdo->prepare($query);
$stmt->bindParam(':id_ukm', $id_ukm, PDO::PARAM_INT);
$stmt->execute();
$response['timelines'] = $stmt->fetchAll(PDO::FETCH_ASSOC); // Mengambil semua hasil

// Query untuk mengambil daftar rapat yang sudah dilaksanakan
$query = "SELECT r.judul, r.tanggal 
          FROM rapat r 
          JOIN timeline_ukm t ON r.id_timeline = t.id_timeline 
          WHERE t.id_ukm = :id_ukm 
            AND r.tanggal < CURDATE()"; 
$stmt = $pdo->prepare($query);
$stmt->bindParam(':id_ukm', $id_ukm, PDO::PARAM_INT);
$stmt->execute();
$response['rapatDilaksanakan'] = $stmt->fetchAll(PDO::FETCH_ASSOC); // Mengambil semua hasil


// Mengembalikan response sebagai JSON
echo json_encode($response);
<<<<<<< HEAD
?>
=======
?>
>>>>>>> 860e3f0788e00a524af5df060bd6638ff0c69d74
