<?php
include_once __DIR__ . '/../../config/session.php';
include_once __DIR__ . '/../../config/database.php';

ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set("error_log", __DIR__ . "/../../error_log.txt");

header('Content-Type: application/json');

try {
    $data = json_decode(file_get_contents("php://input"), true);
    $newEmail = trim($data['newEmail'] ?? '');
    $confirmEmail = trim($data['confirmEmail'] ?? '');
    $password = $data['password'] ?? '';
    $userId = $_SESSION['user_id'] ?? null;

    if (!$userId) {
        throw new Exception("User tidak login.");
    }

    if (empty($newEmail) || empty($confirmEmail) || empty($password)) {
        throw new Exception("Semua field wajib diisi.");
    }

    if ($newEmail !== $confirmEmail) {
        throw new Exception("Email baru dan konfirmasi tidak cocok.");
    }

    // Validasi format email
    if (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
        throw new Exception("Format email tidak valid.");
    }

    // Ambil password user saat ini
    $stmt = $conn->prepare("SELECT password FROM user WHERE id_user = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user || $password !== $user['password']) {
        throw new Exception("Password salah.");
    }

    // Update email
    $update = $conn->prepare("UPDATE user SET email = ? WHERE id_user = ?");
    $update->bind_param("si", $newEmail, $userId);
    $update->execute();

    echo json_encode(['success' => true, 'message' => 'Email berhasil diperbarui.']);
} catch (Exception $e) {
    error_log("[update_email.php] " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
