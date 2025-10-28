<?php
header("Content-Type: application/json");
session_start();

ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set("error_log", __DIR__ . "/../../error_log.txt"); // simpan ke file log

include_once __DIR__ . "/../../config/database.php";
include_once __DIR__ . "/../../config/session.php";

// Cek role
if (!isset($_SESSION['user_role']) || strtolower($_SESSION['user_role']) !== 'superadmin') {
    echo json_encode([
        "success" => false,
        "message" => "Akses ditolak! Hanya superadmin yang dapat menambahkan unit pengolah."
    ]);
    exit;
}

// Ambil data JSON dari body request
$data = json_decode(file_get_contents("php://input"), true);

if (empty($data['kode_unit']) || empty($data['nama_unit'])) {
    echo json_encode([
        "success" => false,
        "message" => "Kode unit dan nama unit wajib diisi."
    ]);
    exit;
}

$kode_unit = trim($data['kode_unit']);
$nama_unit = trim($data['nama_unit']);

// Cek apakah kode unit sudah ada
$stmt = $conn->prepare("SELECT id_unit FROM unit_pengolah WHERE kode_unit = ?");
$stmt->bind_param("s", $kode_unit);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode([
        "success" => false,
        "message" => "Kode unit sudah digunakan."
    ]);
    exit;
}

// Insert data baru
$stmt = $conn->prepare("INSERT INTO unit_pengolah (kode_unit, nama_unit) VALUES (?, ?)");
$stmt->bind_param("ss", $kode_unit, $nama_unit);

if ($stmt->execute()) {
    echo json_encode([
        "success" => true,
        "message" => "Unit pengolah berhasil ditambahkan!"
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Gagal menambahkan unit pengolah: " . $stmt->error
    ]);
}

$stmt->close();
$conn->close();
