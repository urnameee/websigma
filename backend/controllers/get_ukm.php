<?php
session_start(); // Mulai sesi untuk mendapatkan data login

include '../config/config.php'; // Memasukkan file koneksi database

// Memeriksa apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    echo json_encode(["status" => "error", "message" => "Anda belum login."]);
    exit();
}

$username = $_SESSION['username']; // Ambil username dari sesi

// Query untuk mengambil UKM yang diikuti
$sql = "
    SELECT u.id_ukm, u.nama_ukm, u.deskripsi 
    FROM ukm u 
    JOIN keanggotaan_ukm k ON u.id_ukm = k.id_ukm 
    JOIN mahasiswa m ON k.nim = m.nim 
    WHERE m.nim = ?";
    
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username); // Bind parameter
$stmt->execute();
$result = $stmt->get_result();

// Menyimpan hasil dalam array
$ukm_list = [];
while ($row = $result->fetch_assoc()) {
    $ukm_list[] = $row;
}

// Menutup koneksi
$stmt->close();
$conn->close();

// Mengembalikan data sebagai JSON
header('Content-Type: application/json');
echo json_encode($ukm_list);
?>
