<?php
// Koneksi database
require_once __DIR__ . '/../../config/config.php';

// Set header untuk response JSON
header('Content-Type: application/json');

// Fungsi untuk upload image
function uploadImage($file) {
    $target_dir = "../../../frontend/public/assets/";
    $file_extension = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
    $new_filename = "event-" . uniqid() . "." . $file_extension;
    $target_file = $target_dir . $new_filename;

    // Cek ukuran file (max 2MB)
    if ($file["size"] > 2000000) {
        return false;
    }

    // Cek tipe file
    if ($file_extension != "jpg" && $file_extension != "jpeg" && $file_extension != "png") {
        return false;
    }

    if (move_uploaded_file($file["tmp_name"], $target_file)) {
        return $new_filename;
    }
    return false;
}

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        // Get data timeline berdasarkan UKM
        if (isset($_GET['id_ukm'])) {
            $id_ukm = $_GET['id_ukm'];
            $query = "SELECT t.*, 
                     (SELECT COUNT(*) FROM panitia_proker WHERE id_timeline = t.id_timeline) as jumlah_panitia
                     FROM timeline_ukm t
                     WHERE t.id_ukm = ?
                     ORDER BY t.tanggal_kegiatan DESC";
            $stmt = $pdo->prepare($query);
            $stmt->execute([$id_ukm]);
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
            exit;
        }

        // Get detail timeline untuk edit
        if (isset($_GET['id_timeline'])) {
            $id_timeline = $_GET['id_timeline'];
            $query = "SELECT * FROM timeline_ukm WHERE id_timeline = ?";
            $stmt = $pdo->prepare($query);
            $stmt->execute([$id_timeline]);
            echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
            exit;
        }

        // Get panitia untuk dropdown
        if (isset($_GET['action']) && $_GET['action'] === 'get_panitia') {
            $id_ukm = $_GET['id_ukm'];
            $query = "SELECT m.nim, m.nama_lengkap 
                     FROM keanggotaan_ukm k 
                     JOIN mahasiswa m ON k.nim = m.nim
                     WHERE k.id_ukm = ?";
            $stmt = $pdo->prepare($query);
            $stmt->execute([$id_ukm]);
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
            exit;
        }

        // Get jabatan panitia untuk dropdown
        if (isset($_GET['action']) && $_GET['action'] === 'get_jabatan_panitia') {
            $query = "SELECT * FROM jabatan_panitia ORDER BY level";
            $stmt = $pdo->prepare($query);
            $stmt->execute();
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
            exit;
        }

        // Get panitia suatu kegiatan
        if (isset($_GET['action']) && $_GET['action'] === 'get_panitia_kegiatan') {
            try {
                $id_timeline = $_GET['id_timeline'];
                error_log("ID Timeline yang dicari: " . $id_timeline); // tambahkan log

                $query = "SELECT p.id_panitia, p.nim, m.nama_lengkap, j.nama_jabatan
                        FROM panitia_proker p
                        JOIN mahasiswa m ON p.nim = m.nim
                        JOIN jabatan_panitia j ON p.id_jabatan_panitia = j.id_jabatan_panitia
                        WHERE p.id_timeline = ?
                        ORDER BY j.level ASC";
                
                $stmt = $pdo->prepare($query);
                $stmt->execute([$id_timeline]);
                $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                error_log("Query SQL: " . $query); // tambahkan log query
                error_log("Data panitia dari DB: " . print_r($data, true)); // tambahkan log hasil
                
                echo json_encode($data ?: []);
            } catch (PDOException $e) {
                error_log("Error Database: " . $e->getMessage());
                echo json_encode(['error' => $e->getMessage()]);
            }
            exit;
        }
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        exit;
    }
}

// Handle POST request (Create/Update)
elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $id_timeline = isset($_POST['id_timeline']) ? $_POST['id_timeline'] : null;
        $id_ukm = $_POST['id_ukm'];
        $judul_kegiatan = $_POST['judul_kegiatan'];
        $deskripsi = $_POST['deskripsi'];
        $tanggal_kegiatan = $_POST['tanggal_kegiatan'];
        $waktu_mulai = $_POST['waktu_mulai'];
        $waktu_selesai = $_POST['waktu_selesai'];
        $status = isset($_POST['status']) ? 'active' : 'inactive';

        // Handle image upload jika ada
        $image_path = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
            $image_path = uploadImage($_FILES['image']);
            if (!$image_path) {
                throw new Exception('Gagal upload image');
            }
        }

        if ($id_timeline) {
            // Update
            $query = "UPDATE timeline_ukm SET 
                     judul_kegiatan = ?, deskripsi = ?, tanggal_kegiatan = ?,
                     waktu_mulai = ?, waktu_selesai = ?, status = ?";
            
            $params = [$judul_kegiatan, $deskripsi, $tanggal_kegiatan, 
                      $waktu_mulai, $waktu_selesai, $status];

            if ($image_path) {
                $query .= ", image_path = ?";
                $params[] = $image_path;
            }

            $query .= " WHERE id_timeline = ?";
            $params[] = $id_timeline;

        } else {
            // Insert
            $query = "INSERT INTO timeline_ukm 
                     (id_ukm, judul_kegiatan, deskripsi, tanggal_kegiatan, 
                      waktu_mulai, waktu_selesai, status, image_path)
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $params = [$id_ukm, $judul_kegiatan, $deskripsi, $tanggal_kegiatan,
                      $waktu_mulai, $waktu_selesai, $status, $image_path];
        }

        $stmt = $pdo->prepare($query);
        $stmt->execute($params);

        echo json_encode(['status' => 'success']);
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}

// Handle DELETE request
elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    try {
        $id_timeline = $_GET['id_timeline'];

        // Get existing image_path
        $query = "SELECT image_path FROM timeline_ukm WHERE id_timeline = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$id_timeline]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        // Begin transaction
        $pdo->beginTransaction();

        // Delete panitia
        $query = "DELETE FROM panitia_proker WHERE id_timeline = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$id_timeline]);

        // Delete timeline
        $query = "DELETE FROM timeline_ukm WHERE id_timeline = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$id_timeline]);

        // Commit transaction
        $pdo->commit();

        // Delete image file if exists
        if ($data['image_path']) {
            $image_path = "../../../frontend/public/assets/" . $data['image_path'];
            if (file_exists($image_path)) {
                unlink($image_path);
            }
        }

        echo json_encode(['status' => 'success']);
    } catch (Exception $e) {
        // Rollback jika terjadi error
        $pdo->rollBack();
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}
?>