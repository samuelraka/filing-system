<?php
include_once __DIR__ . '/../../config/session.php';
include_once __DIR__ . '/../../config/database.php';

ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set("error_log", __DIR__ . "/../../error_log.txt");

header('Content-Type: application/json');

try {
    $data = json_decode(file_get_contents("php://input"), true);
    $currentPassword = $data['currentPassword'] ?? '';
    $newPassword = $data['newPassword'] ?? '';
    $confirmPassword = $data['confirmPassword'] ?? '';
    $userId = $_SESSION['user_id'] ?? null;

    if (!$userId) {
        throw new Exception("User tidak login.");
    }

    if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
        throw new Exception("Semua field wajib diisi.");
    }

    if ($newPassword !== $confirmPassword) {
        throw new Exception("Password baru dan konfirmasi tidak cocok.");
    }

    $stmt = $conn->prepare("SELECT password FROM user WHERE id_user = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

    if (!$user) {
        throw new Exception("User tidak ditemukan.");
    }

    $stored = (string)($user['password'] ?? '');
    $info = password_get_info($stored);
    $valid = false;
    if ($info['algo'] !== 0) {
        $valid = password_verify($currentPassword, $stored);
    } else {
        $valid = ($currentPassword === $stored);
    }
    if (!$valid) {
        throw new Exception("Password lama salah.");
    }

    $newHash = password_hash($newPassword, PASSWORD_DEFAULT);
    $update = $conn->prepare("UPDATE user SET password = ? WHERE id_user = ?");
    $update->bind_param("si", $newHash, $userId);
    $update->execute();
    $update->close();

    echo json_encode(['success' => true, 'message' => 'Password berhasil diubah.']);
} catch (Exception $e) {
    error_log("[update_password.php] " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
