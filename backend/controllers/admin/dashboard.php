// /backend/controllers/admin/dashboard.php
<?php
require_once '../../config/config.php';

// Set header JSON
header('Content-Type: application/json');
// Tambahkan header CORS jika diperlukan
header('Access-Control-Allow-Origin: *');
// Get action from URL parameter
$action = $_GET['action'] ?? '';

switch($action) {
    case 'get_stats':
        getStats();
        break;
    case 'get_ukm_members':
        getUkmMembers();
        break;
    default:
        echo json_encode(['status' => 'error', 'message' => 'Action not found']);
        break;
}

function getStats() {
    global $pdo;
    
    try {
        // Debug: Log query execution
        error_log("Executing getStats");

        // Get total mahasiswa
        $query_mahasiswa = "SELECT COUNT(*) as total FROM mahasiswa";
        $stmt = $pdo->query($query_mahasiswa);
        $total_mahasiswa = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        error_log("Total mahasiswa: " . $total_mahasiswa);

        // Get total UKM
        $query_ukm = "SELECT COUNT(*) as total FROM ukm";
        $stmt = $pdo->query($query_ukm);
        $total_ukm = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        error_log("Total UKM: " . $total_ukm);

        // Get users by role
        $query_users = "SELECT role, COUNT(*) as total FROM user_login GROUP BY role";
        $stmt = $pdo->query($query_users);
        $users_by_role = $stmt->fetchAll(PDO::FETCH_ASSOC);
        error_log("Users by role: " . print_r($users_by_role, true));

        $response = [
            'status' => 'success',
            'data' => [
                'total_mahasiswa' => $total_mahasiswa,
                'total_ukm' => $total_ukm,
                'users_by_role' => $users_by_role
            ]
        ];
        
        error_log("Response: " . print_r($response, true));
        echo json_encode($response);
        
    } catch(PDOException $e) {
        error_log("Error in getStats: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}

function getUkmMembers() {
    global $pdo;
    
    try {
        $query = "SELECT u.nama_ukm, COUNT(k.nim) as total_anggota 
                 FROM ukm u 
                 LEFT JOIN keanggotaan_ukm k ON u.id_ukm = k.id_ukm 
                 GROUP BY u.id_ukm, u.nama_ukm";
        $stmt = $pdo->query($query);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode([
            'status' => 'success',
            'data' => $data
        ]);
    } catch(PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}
?>