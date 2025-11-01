<?php
header("Content-Type: application/json");
require_once("../../config/database.php"); // sesuaikan path koneksi kamu
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set("error_log", __DIR__ . "/../../error_log.txt");


$data = json_decode(file_get_contents("php://input"), true);

// Validasi input
if (!isset($data["kode_sub_masalah"], $data["id_pokok_masalah"], $data["uraian_sub_masalah"])) {
    echo json_encode(["success" => false, "message" => "Data tidak lengkap."]);
    exit;
}

$kode_sub = trim($data["kode_sub_masalah"]);
$id_pokok = intval($data["id_pokok_masalah"]);
$uraian = trim($data["uraian_sub_masalah"]);

// Cek duplikasi kode_sub
$check = $conn->prepare("SELECT COUNT(*) FROM sub_masalah WHERE kode_sub = ?");
$check->bind_param("s", $kode_sub);
$check->execute();
$check->bind_result($count);
$check->fetch();
$check->close();

if ($count > 0) {
    echo json_encode(["success" => false, "message" => "Kode sub masalah sudah terdaftar."]);
    exit;
}

// Insert data baru
$stmt = $conn->prepare("INSERT INTO sub_masalah (kode_sub, id_pokok, topik_sub, created_at) VALUES (?, ?, ?, NOW())");
$stmt->bind_param("sis", $kode_sub, $id_pokok, $uraian);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Sub masalah berhasil ditambahkan."]);
} else {
    echo json_encode(["success" => false, "message" => "Gagal menambahkan sub masalah."]);
}

$stmt->close();
$conn->close();
?>
