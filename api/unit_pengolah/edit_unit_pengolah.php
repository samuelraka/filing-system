<?php
header("Content-Type: application/json");
session_start();

ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set("error_log", __DIR__ . "/../../error_log.txt"); // simpan ke file log

include_once __DIR__ . "/../../config/database.php";
include_once __DIR__ . "/../../config/session.php";

// Cek role superadmin
if (!isset($_SESSION['user_role']) || strtolower($_SESSION['user_role']) !== 'superadmin') {
    echo json_encode([
        "success" => false,
        "message" => "Akses ditolak! Hanya superadmin yang dapat mengedit unit pengolah."
    ]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['id_unit']) || !isset($data['kode_unit']) || !isset($data['nama_unit'])) {
    echo json_encode([
        "success" => false,
        "message" => "Semua field wajib diisi."
    ]);
    exit;
}

$id_unit = intval($data['id_unit']);
$kode_unit = trim($data['kode_unit']);
$nama_unit = trim($data['nama_unit']);

// Pastikan data unit_pengolah-nya ada
$stmt = $conn->prepare("SELECT id_unit FROM unit_pengolah WHERE id_unit = ?");
$stmt->bind_param("i", $id_unit);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    echo json_encode(["success" => false, "message" => "Unit tidak ditemukan."]);
    exit;
}

// Update data
$stmt = $conn->prepare("UPDATE unit_pengolah SET kode_unit = ?, nama_unit = ? WHERE id_unit = ?");
$stmt->bind_param("ssi", $kode_unit, $nama_unit, $id_unit);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Data unit berhasil diperbarui!"]);
} else {
    echo json_encode(["success" => false, "message" => "Gagal mengupdate data: " . $stmt->error]);
}

$stmt->close();
$conn->close();
