<?php

require_once '../config/config.php';

// Validasi ID UKM dari request
$id_ukm = isset($_GET['id_ukm']) ? $_GET['id_ukm'] : null;
if (!$id_ukm) {
    echo json_encode(["status" => "error", "message" => "ID UKM tidak ditemukan"]);
    exit;
}

// Query untuk mengambil data detail UKM berdasarkan id_ukm
$queryUkm = "SELECT banner_path, deskripsi, visi, misi FROM ukm WHERE id_ukm = :id_ukm";
$stmtUkm = $pdo->prepare($queryUkm);
$stmtUkm->bindParam(':id_ukm', $id_ukm, PDO::PARAM_INT);
$stmtUkm->execute();
$ukm = $stmtUkm->fetch(PDO::FETCH_ASSOC);

if (!$ukm) {
    echo json_encode(["status" => "error", "message" => "Data UKM tidak ditemukan"]);
    exit;
}

// Query untuk mengambil data struktur organisasi berdasarkan hierarki
$queryStruktur = "
    SELECT 
        m.nama_lengkap,
        j.nama_jabatan,
        j.hierarki,
        s.foto_path,
        p.tahun_mulai,
        p.tahun_selesai
    FROM 
        struktur_organisasi_ukm s
        JOIN mahasiswa m ON s.nim = m.nim
        JOIN jabatan j ON s.id_jabatan = j.id_jabatan
        JOIN periode_kepengurusan p ON s.id_periode = p.id_periode
    WHERE 
        s.id_ukm = :id_ukm
        AND p.status = 'aktif'
    ORDER BY 
        j.hierarki ASC, s.tanggal_mulai;
";

$stmtStruktur = $pdo->prepare($queryStruktur);
$stmtStruktur->bindParam(':id_ukm', $id_ukm, PDO::PARAM_INT);
$stmtStruktur->execute();
$struktur = $stmtStruktur->fetchAll(PDO::FETCH_ASSOC);

// Gabungkan data detail UKM dengan struktur organisasi
$response = [
    "status" => "success",
    "ukm_detail" => $ukm,
    "struktur_organisasi" => $struktur
];

echo json_encode($response);

?>
