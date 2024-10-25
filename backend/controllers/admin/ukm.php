<?php
require_once '../../config/config.php';

// Set header JSON
header('Content-Type: application/json');

// Get action from URL parameter
$action = $_GET['action'] ?? '';

switch($action) {
    case 'get_all':
        getAllUkm();
        break;
    case 'get_one':
        getOneUkm();
        break;
    case 'add':
        addUkm();
        break;
    case 'edit':
        editUkm();
        break;
    case 'delete':
        deleteUkm();
        break;
    default:
        echo json_encode(['status' => 'error', 'message' => 'Action not found']);
        break;
}

function getAllUkm() {
    global $pdo;
    
    try {
        $query = "SELECT * FROM ukm";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode(['data' => $data]);
    } catch(PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}

function getOneUkm() {
    global $pdo;
    
    $id = $_GET['id'] ?? '';
    
    try {
        $query = "SELECT * FROM ukm WHERE id_ukm = :id";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        echo json_encode($data);
    } catch(PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}

function addUkm() {
    global $pdo;
    
    try {
        // Handle file upload
        $logo_path = '';
        if(isset($_FILES['logo']) && $_FILES['logo']['error'] == 0) {
            // Validate file size (5MB)
            if($_FILES['logo']['size'] > 5 * 1024 * 1024) {
                throw new Exception('Ukuran file terlalu besar (maksimal 5MB)');
            }
            
            // Validate file type
            $file_type = strtolower(pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION));
            if($file_type != 'png') {
                throw new Exception('Format file harus PNG');
            }
            
            // Generate filename
            $logo_path = 'logo-' . strtolower(str_replace(' ', '-', $_POST['nama_ukm'])) . '.png';
            $target_path = $_SERVER['DOCUMENT_ROOT'] . '/frontend/public/assets/' . $logo_path;
            
            // Move uploaded file
            if(!move_uploaded_file($_FILES['logo']['tmp_name'], $target_path)) {
                throw new Exception('Gagal upload file');
            }
        }

        // Convert date format from DD/MM/YYYY to YYYY-MM-DD
        $tanggal_berdiri = DateTime::createFromFormat('d/m/Y', $_POST['tanggal_berdiri']);
        $tanggal_berdiri = $tanggal_berdiri ? $tanggal_berdiri->format('Y-m-d') : null;
        
        // Insert ke database
        $query = "INSERT INTO ukm (nama_ukm, deskripsi, visi, misi, tanggal_berdiri, logo_path) 
                  VALUES (:nama_ukm, :deskripsi, :visi, :misi, :tanggal_berdiri, :logo_path)";
        
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            'nama_ukm' => $_POST['nama_ukm'],
            'deskripsi' => $_POST['deskripsi'],
            'visi' => $_POST['visi'],
            'misi' => $_POST['misi'],
            'tanggal_berdiri' => $tanggal_berdiri,
            'logo_path' => $logo_path
        ]);

        echo json_encode(['status' => 'success', 'message' => 'Data berhasil ditambahkan']);
        
    } catch(Exception $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}

function editUkm() {
    global $pdo;
    
    try {
        // Get current logo path
        $query = "SELECT logo_path FROM ukm WHERE id_ukm = :id";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['id' => $_POST['id_ukm']]);
        $current_data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $logo_path = $current_data['logo_path'];
        
        // Handle file upload if new logo is uploaded
        if(isset($_FILES['logo']) && $_FILES['logo']['error'] == 0) {
            // Validate file size (5MB)
            if($_FILES['logo']['size'] > 5 * 1024 * 1024) {
                throw new Exception('Ukuran file terlalu besar (maksimal 5MB)');
            }
            
            // Validate file type
            $file_type = strtolower(pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION));
            if($file_type != 'png') {
                throw new Exception('Format file harus PNG');
            }
            
            // Delete old logo if exists
            if($current_data['logo_path']) {
                $old_file = $_SERVER['DOCUMENT_ROOT'] . '/frontend/public/assets/' . $current_data['logo_path'];
                if(file_exists($old_file)) {
                    unlink($old_file);
                }
            }
            
            // Generate filename
            $logo_path = 'logo-' . strtolower(str_replace(' ', '-', $_POST['nama_ukm'])) . '.png';
            $target_path = $_SERVER['DOCUMENT_ROOT'] . '/frontend/public/assets/' . $logo_path;
            
            // Move uploaded file
            if(!move_uploaded_file($_FILES['logo']['tmp_name'], $target_path)) {
                throw new Exception('Gagal upload file');
            }
        }

        // Convert date format from DD/MM/YYYY to YYYY-MM-DD
        $tanggal_berdiri = DateTime::createFromFormat('d/m/Y', $_POST['tanggal_berdiri']);
        $tanggal_berdiri = $tanggal_berdiri ? $tanggal_berdiri->format('Y-m-d') : null;
        
        // Update database
        $query = "UPDATE ukm SET 
                  nama_ukm = :nama_ukm,
                  deskripsi = :deskripsi,
                  visi = :visi,
                  misi = :misi,
                  tanggal_berdiri = :tanggal_berdiri,
                  logo_path = :logo_path
                  WHERE id_ukm = :id_ukm";
        
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            'id_ukm' => $_POST['id_ukm'],
            'nama_ukm' => $_POST['nama_ukm'],
            'deskripsi' => $_POST['deskripsi'],
            'visi' => $_POST['visi'],
            'misi' => $_POST['misi'],
            'tanggal_berdiri' => $tanggal_berdiri,
            'logo_path' => $logo_path
        ]);
        
        echo json_encode(['status' => 'success', 'message' => 'Data berhasil diupdate']);
    } catch(Exception $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}

function deleteUkm() {
    global $pdo;
    
    try {
        // Get logo path
        $query = "SELECT logo_path FROM ukm WHERE id_ukm = :id";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['id' => $_POST['id_ukm']]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Delete logo file if exists
        if($data['logo_path']) {
            $file_path = $_SERVER['DOCUMENT_ROOT'] . '/frontend/public/assets/' . $data['logo_path'];
            if(file_exists($file_path)) {
                unlink($file_path);
            }
        }

        // Delete from database
        $query = "DELETE FROM ukm WHERE id_ukm = :id_ukm";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['id_ukm' => $_POST['id_ukm']]);

        echo json_encode(['status' => 'success', 'message' => 'Data berhasil dihapus']);
        
    } catch(PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}
?>