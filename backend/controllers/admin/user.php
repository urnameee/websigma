<?php
require_once '../../config/config.php';

// Set header JSON
header('Content-Type: application/json');

// Get action from URL parameter
$action = $_GET['action'] ?? '';

switch($action) {
    case 'get_all':
        getAllUsers();
        break;
    case 'get_one':
        getOneUser();
        break;
    case 'add':
        addUser();
        break;
    case 'edit':
        editUser();
        break;
    case 'delete':
        deleteUser();
        break;
    case 'reset_password':
        resetPassword();
        break;
    default:
        echo json_encode(['status' => 'error', 'message' => 'Action not found']);
        break;
}

function getAllUsers() {
    global $pdo;
    
    try {
        $query = "SELECT * FROM user_login ORDER BY id_login DESC";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode(['data' => $data]);
    } catch(PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}

function getOneUser() {
    global $pdo;
    
    $id = $_GET['id'] ?? '';
    
    try {
        $query = "SELECT * FROM user_login WHERE id_login = :id";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        echo json_encode($data);
    } catch(PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}

function addUser() {
    global $pdo;
    
    try {
        // Check if username already exists
        $check_query = "SELECT username FROM user_login WHERE username = :username";
        $check_stmt = $pdo->prepare($check_query);
        $check_stmt->execute(['username' => $_POST['username']]);
        
        if ($check_stmt->fetch()) {
            throw new Exception('Username sudah terdaftar');
        }

        // Jika rolenya mahasiswa, verify NIM dan set nim_reference
        $nim_reference = null;
        if ($_POST['role'] === 'mahasiswa') {
            $verify_query = "SELECT nim FROM mahasiswa WHERE nim = :nim";
            $verify_stmt = $pdo->prepare($verify_query);
            $verify_stmt->execute(['nim' => $_POST['username']]);
            
            if (!$verify_stmt->fetch()) {
                throw new Exception('Username harus sesuai dengan NIM mahasiswa yang terdaftar');
            }
            $nim_reference = $_POST['username'];
        }

        // Insert new user
        $query = "INSERT INTO user_login (username, password, role, nim_reference) 
                  VALUES (:username, :password, :role, :nim_reference)";
        
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            'username' => $_POST['username'],
            'password' => $_POST['password'],
            'role' => $_POST['role'],
            'nim_reference' => $nim_reference
        ]);

        echo json_encode(['status' => 'success', 'message' => 'Data berhasil ditambahkan']);
        
    } catch(Exception $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}

function editUser() {
    global $pdo;
    
    try {
        // Get current user data
        $current_query = "SELECT username, role FROM user_login WHERE id_login = :id";
        $current_stmt = $pdo->prepare($current_query);
        $current_stmt->execute(['id' => $_POST['id_login']]);
        $current_data = $current_stmt->fetch(PDO::FETCH_ASSOC);

        if ($current_data['username'] !== $_POST['username']) {
            // Check if new username already exists
            $check_query = "SELECT username FROM user_login WHERE username = :username AND id_login != :id";
            $check_stmt = $pdo->prepare($check_query);
            $check_stmt->execute([
                'username' => $_POST['username'],
                'id' => $_POST['id_login']
            ]);
            
            if ($check_stmt->fetch()) {
                throw new Exception('Username sudah terdaftar');
            }
        }

        // Set nim_reference based on role
        $nim_reference = null;
        if ($_POST['role'] === 'mahasiswa') {
            $verify_query = "SELECT nim FROM mahasiswa WHERE nim = :nim";
            $verify_stmt = $pdo->prepare($verify_query);
            $verify_stmt->execute(['nim' => $_POST['username']]);
            
            if (!$verify_stmt->fetch()) {
                throw new Exception('Username harus sesuai dengan NIM mahasiswa yang terdaftar');
            }
            $nim_reference = $_POST['username'];
        }

        // Update user
        $query = "UPDATE user_login SET 
                  username = :username,
                  role = :role,
                  nim_reference = :nim_reference
                  WHERE id_login = :id_login";
        
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            'id_login' => $_POST['id_login'],
            'username' => $_POST['username'],
            'role' => $_POST['role'],
            'nim_reference' => $nim_reference
        ]);
        
        echo json_encode(['status' => 'success', 'message' => 'Data berhasil diupdate']);
    } catch(Exception $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}

function deleteUser() {
    global $pdo;
    
    try {
        $query = "DELETE FROM user_login WHERE id_login = :id_login";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['id_login' => $_POST['id_login']]);

        echo json_encode(['status' => 'success', 'message' => 'Data berhasil dihapus']);
        
    } catch(PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}

function resetPassword() {
    global $pdo;
    
    try {
        $query = "UPDATE user_login SET password = '123' WHERE id_login = :id_login";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['id_login' => $_POST['id_login']]);

        echo json_encode(['status' => 'success', 'message' => 'Password berhasil direset']);
        
    } catch(PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}
?>