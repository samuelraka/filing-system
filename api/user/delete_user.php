<?php
include_once __DIR__ . '/../../config/database.php';
include_once __DIR__ . '/../../config/session.php';

header('Content-Type: application/json');

try {
    $data = json_decode(file_get_contents("php://input"), true);

    if (empty($data['id_user'])) {
        throw new Exception("ID pengguna tidak ditemukan.");
    }

    $id_user = intval($data['id_user']);

    // Hapus relasi dari tabel profil (jika ada)
    $stmt = $conn->prepare("DELETE FROM profil WHERE id_user = ?");
    $stmt->bind_param("i", $id_user);
    $stmt->execute();

    // Hapus user dari tabel user
    $stmt = $conn->prepare("DELETE FROM user WHERE id_user = ?");
    $stmt->bind_param("i", $id_user);
    if (!$stmt->execute()) {
        throw new Exception("Gagal menghapus pengguna dari database.");
    }

    echo json_encode(["success" => true, "message" => "Pengguna berhasil dihapus."]);

} catch (Exception $e) {
    error_log("[" . date('Y-m-d H:i:s') . "] ERROR DELETE USER: " . $e->getMessage() . "\n", 3, __DIR__ . "/../../error_log.txt");
    echo json_encode(["success" => false, "message" => "Terjadi kesalahan: " . $e->getMessage()]);
}
?>
