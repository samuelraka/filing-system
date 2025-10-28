<?php
header("Content-Type: application/json");
include_once __DIR__ . "/../../config/database.php";
include_once __DIR__ . "/../../config/session.php";
session_start();

ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set("error_log", __DIR__ . "/../../error_log.txt"); // simpan ke file log
// Cek role superadmin
if (!isset($_SESSION['user_role']) || strtolower($_SESSION['user_role']) !== 'superadmin') {
    echo json_encode([
        "success" => false,
        "message" => "Akses ditolak! Hanya superadmin yang dapat menghapus unit pengolah."
    ]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['id_unit'])) {
    echo json_encode([
        "success" => false,
        "message" => "ID unit tidak ditemukan."
    ]);
    exit;
}

$id_unit = intval($data['id_unit']);

// Cek apakah unit ada
$stmt = $conn->prepare("SELECT id_unit FROM unit_pengolah WHERE id_unit = ?");
$stmt->bind_param("i", $id_unit);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(["success" => false, "message" => "Data unit tidak ditemukan."]);
    exit;
}

// Hapus data
$stmt = $conn->prepare("DELETE FROM unit_pengolah WHERE id_unit = ?");
$stmt->bind_param("i", $id_unit);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Unit berhasil dihapus!"]);
} else {
    echo json_encode(["success" => false, "message" => "Gagal menghapus unit: " . $stmt->error]);
}

$stmt->close();
$conn->close();
