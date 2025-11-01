<?php
header("Content-Type: application/json");
require_once __DIR__ . "/../../config/database.php";

ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set("error_log", __DIR__ . "/../../error_log.txt"); // simpan error ke file log

$response = ["success" => false, "message" => "Terjadi kesalahan."];

try {
    // Baca data JSON dari fetch()
    $data = json_decode(file_get_contents("php://input"), true);

    if (!isset($data['id_sub_masalah']) || empty($data['id_sub_masalah'])) {
        throw new Exception("ID Sub Masalah tidak ditemukan.");
    }

    $id_sub = intval($data['id_sub_masalah']);

    // Query hapus data
    $stmt = $conn->prepare("DELETE FROM sub_masalah WHERE id_sub = ?");
    $stmt->bind_param("i", $id_sub);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            $response = ["success" => true, "message" => "Sub Masalah berhasil dihapus."];
        } else {
            $response = ["success" => false, "message" => "Sub Masalah tidak ditemukan."];
        }
    } else {
        throw new Exception("Gagal menghapus data Sub Masalah.");
    }

    $stmt->close();
    $conn->close();

} catch (Exception $e) {
    error_log("Error delete_sub_masalah.php: " . $e->getMessage());
    $response = ["success" => false, "message" => $e->getMessage()];
}

echo json_encode($response);
?>
