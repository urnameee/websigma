<?php
session_start();
require_once '../config/config.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($data['username']) && isset($data['password'])) {
    $username = $data['username'];
    $password = $data['password'];

    try {
        // Query untuk mencari user dan rolenya di database
        $stmt = $pdo->prepare("SELECT id_login, username, password, role FROM user_login WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            if ($password === $user['password']) {
                // Set session variables
                $_SESSION['id_login'] = $user['id_login'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                // Return success response with user role
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Login berhasil',
                    'user' => [
                        'id_login' => $user['id_login'],
                        'username' => $user['username'],
                        'role' => $user['role']
                    ]
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Username atau password salah'
                ]);
            }
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Username atau password salah'
            ]);
        }
    } catch (PDOException $e) {
        error_log($e->getMessage());
        echo json_encode([
            'status' => 'error',
            'message' => 'Terjadi kesalahan pada server'
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Request tidak valid'
    ]);
}
?>