<?php
include_once __DIR__ . '/../../config/session.php';
include_once __DIR__ . '/../../config/database.php';

ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set("error_log", __DIR__ . "/../../error_log.txt");

header('Content-Type: application/json');

try {
    $data = json_decode(file_get_contents("php://input"), true);
    $newUsername = trim($data['newUsername'] ?? '');
    $userId = $_SESSION['user_id'] ?? null;

    if (!$userId) {
        throw new Exception("User tidak login.");
    }

    if (empty($newUsername)) {
        throw new Exception("Username baru wajib diisi.");
    }

    // Cek apakah username sudah digunakan
    $check = $conn->prepare("SELECT id_user FROM user WHERE username = ? AND id_user != ?");
    $check->bind_param("si", $newUsername, $userId);
    $check->execute();
    $res = $check->get_result();

    if ($res->num_rows > 0) {
        throw new Exception("Username sudah digunakan oleh pengguna lain.");
    }

    // Update username
    $update = $conn->prepare("UPDATE user SET username = ? WHERE id_user = ?");
    $update->bind_param("si", $newUsername, $userId);
    $update->execute();

    echo json_encode(['success' => true, 'message' => 'Username berhasil diubah.']);
} catch (Exception $e) {
    error_log("[update_username.php] " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
