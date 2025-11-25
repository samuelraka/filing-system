<?php
include_once '../config/session.php';
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
    $stored = (string)($user['password'] ?? '');
    $info = password_get_info($stored);
    $ok = false;

    if ($info['algo'] !== 0) {
        if (password_verify($password, $stored)) {
            $ok = true;
            if (password_needs_rehash($stored, PASSWORD_DEFAULT)) {
                $newHash = password_hash($password, PASSWORD_DEFAULT);
                $up = $conn->prepare("UPDATE user SET password = ? WHERE id_user = ?");
                $up->bind_param("si", $newHash, $user['id_user']);
                $up->execute();
                $up->close();
            }
        }
    } else {
        if ($password === $stored) {
            $ok = true;
            $newHash = password_hash($password, PASSWORD_DEFAULT);
            $up = $conn->prepare("UPDATE user SET password = ? WHERE id_user = ?");
            $up->bind_param("si", $newHash, $user['id_user']);
            $up->execute();
            $up->close();
        }
    }

    if ($ok) {
        $_SESSION['user_id'] = $user['id_user'];
        $_SESSION['user_name'] = $user['nama'];
        $_SESSION['user_role'] = strtolower($user['role']);

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
            'redirect' => '../pages/dashboard.php'
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Password salah']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Username tidak ditemukan']);
}

$stmt->close();
$conn->close();
