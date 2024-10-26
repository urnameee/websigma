<?php

require_once '../config/config.php';
// Validasi ID UKM dari request
$id_ukm = isset($_GET['id_ukm']) ? $_GET['id_ukm'] : null;
if (!$id_ukm) {
    echo json_encode(["status" => "error", "message" => "ID UKM tidak ditemukan"]);
    exit;
}

// Query untuk mengambil data detail UKM berdasarkan id_ukm
$query = "SELECT banner_path, deskripsi, visi, misi FROM ukm WHERE id_ukm = :id_ukm";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':id_ukm', $id_ukm, PDO::PARAM_INT);
$stmt->execute();
$ukm = $stmt->fetch(PDO::FETCH_ASSOC);

if ($ukm) {
    echo json_encode(["status" => "success", "data" => $ukm]);
} else {
    echo json_encode(["status" => "error", "message" => "Data UKM tidak ditemukan"]);
}
?>