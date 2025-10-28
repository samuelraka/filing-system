<?php
include_once __DIR__ . '/../../config/database.php';
include_once __DIR__ . '/../../config/session.php';

header('Content-Type: application/json');

try {
    $data = json_decode(file_get_contents("php://input"), true);

    if (!$data || empty($data['id_user'])) {
        throw new Exception("Data tidak lengkap.");
    }

    $id = $data['id_user'];
    $name = $data['name'];
    $email = $data['email'];
    $role = $data['role'];
    $password = !empty($data['password']) ? password_hash($data['password'], PASSWORD_DEFAULT) : null;

    if ($password) {
        $stmt = $conn->prepare("UPDATE user SET nama=?, email=?, role=?, password=? WHERE id_user=?");
        $stmt->bind_param("ssssi", $name, $email, $role, $password, $id);
    } else {
        $stmt = $conn->prepare("UPDATE user SET nama=?, email=?, role=? WHERE id_user=?");
        $stmt->bind_param("sssi", $name, $email, $role, $id);
    }

    if (!$stmt->execute()) {
        throw new Exception("Gagal mengupdate data user.");
    }

    echo json_encode(["success" => true, "message" => "Data pengguna berhasil diperbarui."]);
} catch (Exception $e) {
    error_log("[" . date('Y-m-d H:i:s') . "] ERROR: " . $e->getMessage() . "\n", 3, __DIR__ . "/../../error_log.txt");
    echo json_encode(["success" => false, "message" => "Terjadi kesalahan: " . $e->getMessage()]);
}
