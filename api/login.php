<?php
include_once '../config/session.php';
include_once '../utils/logging.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);
$username = $data['username'] ?? '';
$password = $data['password'] ?? '';

if (empty($username) || empty($password)) {
    echo json_encode(['success' => false, 'message' => 'Username dan password wajib diisi']);
    exit;
}

$stmt = $conn->prepare("SELECT * FROM user WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    // Jika password disimpan hash, ganti baris di bawah dengan password_verify()
    if ($password === $user['password']) {
        $_SESSION['user_id'] = $user['id_user'];
        $_SESSION['user_name'] = $user['nama'];
        $_SESSION['user_role'] = strtolower($user['role']);

        // Log the login activity
        logLogin($user['id_user'], "User logged in successfully");

        $roleLabel = ucfirst($user['role']);
        $message = "Login berhasil sebagai $roleLabel.";

        echo json_encode([
            'success' => true,
            'message' => $message,
            'user' => [
                'id' => $user['id_user'],
                'name' => $user['nama'],
                'role' => $user['role']
            ],
            'redirect' => '../pages/dashboard.php' // semua diarahkan ke dashboard
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Password salah']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Username tidak ditemukan']);
}

$stmt->close();
$conn->close();
