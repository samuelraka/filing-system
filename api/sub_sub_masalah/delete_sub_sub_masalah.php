<?php
header("Content-Type: application/json");
require_once __DIR__ . "/../../config/database.php";

ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set("error_log", __DIR__ . "/../../error_log.txt");

$response = ["success" => false, "message" => "Terjadi kesalahan."];

try {
    $data = json_decode(file_get_contents("php://input"), true);

    if (empty($data['id_sub_sub_masalah'])) {
        throw new Exception("ID Sub-Sub Masalah tidak ditemukan.");
    }

    $id = intval($data['id_sub_sub_masalah']);

    $stmt = $conn->prepare("DELETE FROM sub_sub_masalah WHERE id_subsub = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            $response = ["success" => true, "message" => "Sub-Sub Masalah berhasil dihapus."];
        } else {
            $response = ["success" => false, "message" => "Sub-Sub Masalah tidak ditemukan."];
        }
    } else {
        throw new Exception("Gagal menghapus Sub-Sub Masalah.");
    }

    $stmt->close();
    $conn->close();

} catch (Exception $e) {
    error_log("Error delete_sub_sub_masalah.php: " . $e->getMessage());
    $response = ["success" => false, "message" => $e->getMessage()];
}

echo json_encode($response);
?>
