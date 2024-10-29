<?php
session_start(); // Start the session to access session variables
require '../../config/config.php'; // Connect to the database

header('Content-Type: application/json');

$request_method = $_SERVER['REQUEST_METHOD'];
$id_ukm = $_SESSION['id_ukm']; // Get the logged-in UKM ID

switch ($request_method) {
    case 'GET':
        if (isset($_GET['id'])) {
            getPengurus($_GET['id']);
        } else {
            getPengurus();
        }
        break;
    
    case 'POST':
        addPengurus();
        break;

    case 'PUT':
        updatePengurus($_GET['id']);
        break;

    case 'DELETE':
        deletePengurus($_GET['id']);
        break;

    default:
        header("HTTP/1.0 405 Method Not Allowed");
        break;
}

function getPengurus($id = null) {
  global $pdo, $id_ukm; // Include the global $id_ukm

  if ($id) {
      $stmt = $pdo->prepare("SELECT so.*, m.nama_lengkap, j.nama_jabatan 
                              FROM struktur_organisasi_ukm so
                              JOIN mahasiswa m ON so.nim = m.nim
                              JOIN jabatan j ON so.id_jabatan = j.id_jabatan
                              WHERE so.id_struktur = :id AND so.id_ukm = :id_ukm");
      $stmt->execute(['id' => $id, 'id_ukm' => $id_ukm]);
      $data = $stmt->fetch(PDO::FETCH_ASSOC);
      if ($data) {
          $data['nama'] = $data['nama_lengkap']; // Rename for consistency
          $data['jabatan'] = $data['nama_jabatan']; // Rename for consistency
          unset($data['nama_lengkap'], $data['nama_jabatan']); // Remove original fields
      }
      echo json_encode($data);
  } else {
      $stmt = $pdo->prepare("SELECT so.*, m.nama_lengkap, j.nama_jabatan 
                              FROM struktur_organisasi_ukm so
                              JOIN mahasiswa m ON so.nim = m.nim
                              JOIN jabatan j ON so.id_jabatan = j.id_jabatan
                              WHERE so.id_ukm = :id_ukm");
      $stmt->execute(['id_ukm' => $id_ukm]);
      $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
      foreach ($data as &$item) {
          $item['nama'] = $item['nama_lengkap']; // Rename for consistency
          $item['jabatan'] = $item['nama_jabatan']; // Rename for consistency
          unset($item['nama_lengkap'], $item['nama_jabatan']); // Remove original fields
      }
      echo json_encode($data);
  }
}

function addPengurus() {
    global $pdo, $id_ukm;
    $data = json_decode(file_get_contents("php://input"));

    $stmt = $pdo->prepare("INSERT INTO struktur_organisasi_ukm (nim, id_jabatan, id_periode, tanggal_mulai, tanggal_berakhir, foto_path, id_ukm) VALUES (:nim, :id_jabatan, :id_periode, :tanggal_mulai, :tanggal_berakhir, :foto, :id_ukm)");
    $stmt->execute([
        'nim' => $data->nim,
        'id_jabatan' => $data->id_jabatan,
        'id_periode' => $data->id_periode,
        'tanggal_mulai' => $data->tanggal_mulai,
        'tanggal_berakhir' => $data->tanggal_berakhir,
        'foto' => $data->foto, // Handle file uploads separately
        'id_ukm' => $id_ukm
    ]);

    echo json_encode(['status' => 'success', 'id' => $pdo->lastInsertId()]);
}

function updatePengurus($id) {
    global $pdo, $id_ukm;
    $data = json_decode(file_get_contents("php://input"));

    $stmt = $pdo->prepare("UPDATE struktur_organisasi_ukm SET nim = :nim, id_jabatan = :id_jabatan, id_periode = :id_periode, tanggal_mulai = :tanggal_mulai, tanggal_berakhir = :tanggal_berakhir, foto_path = :foto WHERE id_struktur = :id AND id_ukm = :id_ukm");
    $stmt->execute([
        'nim' => $data->nim,
        'id_jabatan' => $data->id_jabatan,
        'id_periode' => $data->id_periode,
        'tanggal_mulai' => $data->tanggal_mulai,
        'tanggal_berakhir' => $data->tanggal_berakhir,
        'foto' => $data->foto, // Handle file uploads separately
        'id' => $id,
        'id_ukm' => $id_ukm
    ]);

    echo json_encode(['status' => 'success']);
}

function deletePengurus($id) {
    global $pdo, $id_ukm;
    $stmt = $pdo->prepare("DELETE FROM struktur_organisasi_ukm WHERE id_struktur = :id AND id_ukm = :id_ukm");
    $stmt->execute(['id' => $id, 'id_ukm' => $id_ukm]);
    echo json_encode(['status' => 'success']);
}
?>
