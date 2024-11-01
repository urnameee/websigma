<?php
// Koneksi database
require_once __DIR__ . '/../../config/config.php';

// Set header untuk response JSON
header('Content-Type: application/json');

// Function untuk response JSON
function jsonResponse($status, $message = '', $data = null) {
    $response = [
        'status' => $status,
        'message' => $message,
        'data' => $data
    ];
    echo json_encode($response);
    exit;
}

// Fungsi untuk upload file notulensi
function uploadNotulensi($file) {
    $target_dir = "../../../frontend/public/assets/";
    $file_extension = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
    $new_filename = "notulensi-" . uniqid() . "." . $file_extension;
    $target_file = $target_dir . $new_filename;

    // Cek ukuran file (max 5MB)
    if ($file["size"] > 5000000) {
        return ['status' => false, 'message' => 'Ukuran file terlalu besar (max 5MB)'];
    }

    // Cek tipe file
    if ($file_extension != "pdf") {
        return ['status' => false, 'message' => 'File harus berformat PDF'];
    }

    if (move_uploaded_file($file["tmp_name"], $target_file)) {
        return ['status' => true, 'filename' => $new_filename];
    }
    return ['status' => false, 'message' => 'Gagal upload file'];
}

// Fungsi untuk upload foto dokumentasi
function uploadDokumentasi($file) {
    $target_dir = "../../../frontend/public/assets/";
    $file_extension = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
    $new_filename = "dokumentasi-" . uniqid() . "." . $file_extension;
    $target_file = $target_dir . $new_filename;

    // Cek ukuran file (max 2MB)
    if ($file["size"] > 2000000) {
        return ['status' => false, 'message' => 'Ukuran file terlalu besar (max 2MB)'];
    }

    // Cek tipe file
    if ($file_extension != "jpg" && $file_extension != "jpeg" && $file_extension != "png") {
        return ['status' => false, 'message' => 'File harus berformat JPG, JPEG, atau PNG'];
    }

    if (move_uploaded_file($file["tmp_name"], $target_file)) {
        return ['status' => true, 'filename' => $new_filename];
    }
    return ['status' => false, 'message' => 'Gagal upload file'];
}

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        if (isset($_GET['id_timeline'])) {
            $id_timeline = $_GET['id_timeline'];
            
            // Get rapat dan dokumentasinya
            $query = "SELECT r.*, 
                     (SELECT COUNT(*) FROM dokumentasi_rapat WHERE id_rapat = r.id_rapat) as jumlah_foto
                     FROM rapat r 
                     WHERE r.id_timeline = ?
                     ORDER BY r.tanggal DESC";
            
            $stmt = $pdo->prepare($query);
            $stmt->execute([$id_timeline]);
            $rapat = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Get dokumentasi untuk setiap rapat
            foreach ($rapat as &$r) {
                $query = "SELECT * FROM dokumentasi_rapat WHERE id_rapat = ?";
                $stmt = $pdo->prepare($query);
                $stmt->execute([$r['id_rapat']]);
                $r['dokumentasi'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }

            jsonResponse('success', 'Data rapat berhasil diambil', $rapat);
        }

        // Get detail rapat untuk edit
        if (isset($_GET['id_rapat'])) {
            $id_rapat = $_GET['id_rapat'];
            
            $query = "SELECT r.*, 
                     (SELECT COUNT(*) FROM dokumentasi_rapat WHERE id_rapat = r.id_rapat) as jumlah_foto
                     FROM rapat r 
                     WHERE r.id_rapat = ?";
            
            $stmt = $pdo->prepare($query);
            $stmt->execute([$id_rapat]);
            $rapat = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$rapat) {
                jsonResponse('error', 'Data rapat tidak ditemukan');
            }

            // Get dokumentasi
            $query = "SELECT * FROM dokumentasi_rapat WHERE id_rapat = ?";
            $stmt = $pdo->prepare($query);
            $stmt->execute([$id_rapat]);
            $rapat['dokumentasi'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

            jsonResponse('success', 'Detail rapat berhasil diambil', $rapat);
        }

        jsonResponse('error', 'Invalid request');
    } catch (Exception $e) {
        jsonResponse('error', $e->getMessage());
    }
}

// Handle POST request (Create/Update)
elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo->beginTransaction();

        $id_rapat = isset($_POST['id_rapat']) ? $_POST['id_rapat'] : null;
        $id_timeline = $_POST['id_timeline_rapat'];
        $judul = $_POST['judul_rapat'];
        $tanggal = $_POST['tanggal_rapat'];
        
        // Upload notulensi jika ada
        $notulensi_path = null;
        if (isset($_FILES['notulensi']) && $_FILES['notulensi']['error'] === 0) {
            $uploadResult = uploadNotulensi($_FILES['notulensi']);
            if (!$uploadResult['status']) {
                throw new Exception($uploadResult['message']);
            }
            $notulensi_path = $uploadResult['filename'];
        }

        if ($id_rapat) {
            // Update existing record
            $query = "UPDATE rapat SET judul = ?, tanggal = ?";
            $params = [$judul, $tanggal];
            
            if ($notulensi_path) {
                // Get old notulensi path
                $stmt = $pdo->prepare("SELECT notulensi_path FROM rapat WHERE id_rapat = ?");
                $stmt->execute([$id_rapat]);
                $old_data = $stmt->fetch(PDO::FETCH_ASSOC);
                
                // Delete old file if exists
                if ($old_data['notulensi_path']) {
                    $old_file = "../../../frontend/public/assets/" . $old_data['notulensi_path'];
                    if (file_exists($old_file)) {
                        unlink($old_file);
                    }
                }
                
                $query .= ", notulensi_path = ?";
                $params[] = $notulensi_path;
            }
            
            $query .= " WHERE id_rapat = ?";
            $params[] = $id_rapat;
            
            $stmt = $pdo->prepare($query);
            $stmt->execute($params);

            $message = 'Rapat berhasil diupdate';
            
        } else {
            // Insert new record
            $query = "INSERT INTO rapat (id_timeline, judul, tanggal, notulensi_path) 
                     VALUES (?, ?, ?, ?)";
            $stmt = $pdo->prepare($query);
            $stmt->execute([$id_timeline, $judul, $tanggal, $notulensi_path]);
            
            $id_rapat = $pdo->lastInsertId();
            $message = 'Rapat berhasil ditambahkan';
        }

        // Handle dokumentasi upload
        if (isset($_FILES['dokumentasi']) && !empty($_FILES['dokumentasi']['name'][0])) {
            $successUpload = 0;
            $failedUpload = 0;
            $total_files = count($_FILES['dokumentasi']['name']);
            
            for ($i = 0; $i < $total_files; $i++) {
                $file = [
                    "name" => $_FILES['dokumentasi']['name'][$i],
                    "type" => $_FILES['dokumentasi']['type'][$i],
                    "tmp_name" => $_FILES['dokumentasi']['tmp_name'][$i],
                    "error" => $_FILES['dokumentasi']['error'][$i],
                    "size" => $_FILES['dokumentasi']['size'][$i]
                ];

                if ($file['error'] === 0) {
                    $uploadResult = uploadDokumentasi($file);
                    if ($uploadResult['status']) {
                        $query = "INSERT INTO dokumentasi_rapat (id_rapat, foto_path) VALUES (?, ?)";
                        $stmt = $pdo->prepare($query);
                        $stmt->execute([$id_rapat, $uploadResult['filename']]);
                        $successUpload++;
                    } else {
                        $failedUpload++;
                    }
                }
            }
            
            if ($failedUpload > 0) {
                $message .= ". Berhasil upload $successUpload foto, gagal upload $failedUpload foto";
            }
        }

        $pdo->commit();
        jsonResponse('success', $message);

    } catch (Exception $e) {
        $pdo->rollBack();
        jsonResponse('error', $e->getMessage());
    }
}

// Handle DELETE request
elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    try {
        $pdo->beginTransaction();

        $id_rapat = $_GET['id_rapat'];

        // Get notulensi and dokumentasi paths
        $query = "SELECT notulensi_path FROM rapat WHERE id_rapat = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$id_rapat]);
        $rapat = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$rapat) {
            throw new Exception('Data rapat tidak ditemukan');
        }

        $query = "SELECT foto_path FROM dokumentasi_rapat WHERE id_rapat = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$id_rapat]);
        $dokumentasi = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Delete dokumentasi
        $query = "DELETE FROM dokumentasi_rapat WHERE id_rapat = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$id_rapat]);

        // Delete rapat
        $query = "DELETE FROM rapat WHERE id_rapat = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$id_rapat]);

        // Delete physical files
        if ($rapat['notulensi_path']) {
            $file_path = "../../../frontend/public/assets/" . $rapat['notulensi_path'];
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }

        foreach ($dokumentasi as $dok) {
            $file_path = "../../../frontend/public/assets/" . $dok['foto_path'];
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }

        $pdo->commit();
        jsonResponse('success', 'Rapat berhasil dihapus');

    } catch (Exception $e) {
        $pdo->rollBack();
        jsonResponse('error', $e->getMessage());
    }
}
?>