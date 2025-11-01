<?php
header("Content-Type: application/json");
require_once("../../config/database.php"); // sesuaikan path koneksi kamu

$data = json_decode(file_get_contents("php://input"), true);

// Validasi input
if (!isset($data["id_sub_masalah"], $data["kode_sub_masalah"], $data["id_pokok_masalah"], $data["uraian_sub_masalah"])) {
    echo json_encode(["success" => false, "message" => "Data tidak lengkap."]);
    exit;
}

$id_sub = intval($data["id_sub_masalah"]);
$kode_sub = trim($data["kode_sub_masalah"]);
$id_pokok = intval($data["id_pokok_masalah"]);
$uraian = trim($data["uraian_sub_masalah"]);

// Cek apakah ID sub masalah ada
$check = $conn->prepare("SELECT COUNT(*) FROM sub_masalah WHERE id_sub = ?");
$check->bind_param("i", $id_sub);
$check->execute();
$check->bind_result($exists);
$check->fetch();
$check->close();

if ($exists == 0) {
    echo json_encode(["success" => false, "message" => "Sub masalah tidak ditemukan."]);
    exit;
}

// Cek duplikasi kode_sub (pastikan tidak sama dengan milik sendiri)
$checkDup = $conn->prepare("SELECT COUNT(*) FROM sub_masalah WHERE kode_sub = ? AND id_sub != ?");
$checkDup->bind_param("si", $kode_sub, $id_sub);
$checkDup->execute();
$checkDup->bind_result($dupCount);
$checkDup->fetch();
$checkDup->close();

if ($dupCount > 0) {
    echo json_encode(["success" => false, "message" => "Kode sub masalah sudah digunakan oleh data lain."]);
    exit;
}

// Update data
$stmt = $conn->prepare("UPDATE sub_masalah SET kode_sub = ?, id_pokok = ?, topik_sub = ?, updated_at = NOW() WHERE id_sub = ?");
$stmt->bind_param("sisi", $kode_sub, $id_pokok, $uraian, $id_sub);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Sub masalah berhasil diperbarui."]);
} else {
    echo json_encode(["success" => false, "message" => "Gagal memperbarui sub masalah."]);
}

$stmt->close();
$conn->close();
?>
