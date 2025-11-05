<?php
header("Content-Type: application/json");
require_once("../../config/database.php");

ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set("error_log", __DIR__ . "/../../error_log.txt");

$data = json_decode(file_get_contents("php://input"), true);

// Validasi input
if (!isset($data["id_sub_masalah"], $data["kode_sub_masalah"], $data["id_pokok"], $data["uraian_sub_masalah"])) {
    error_log("❌ Data tidak lengkap: " . print_r($data, true));
    echo json_encode(["success" => false, "message" => "Data tidak lengkap."]);
    exit;
}

$id_sub = intval($data["id_sub_masalah"]);
$kode_sub = trim($data["kode_sub_masalah"]);
$id_pokok = intval($data["id_pokok"]);
$uraian = trim($data["uraian_sub_masalah"]);

error_log("🟡 Input diterima: id_sub=$id_sub, kode_sub=$kode_sub, id_pokok=$id_pokok, uraian=$uraian");

// Cek apakah ID sub masalah ada
$check = $conn->prepare("SELECT COUNT(*) FROM sub_masalah WHERE id_sub = ?");
if (!$check) {
    error_log("❌ Prepare gagal (check existence): " . $conn->error);
    echo json_encode(["success" => false, "message" => "Gagal mempersiapkan query cek data."]);
    exit;
}

$check->bind_param("i", $id_sub);
$check->execute();
$check->bind_result($exists);
$check->fetch();
$check->close();

if ($exists == 0) {
    error_log("⚠️ Sub masalah dengan id_sub=$id_sub tidak ditemukan di database.");
    echo json_encode(["success" => false, "message" => "Sub masalah tidak ditemukan."]);
    exit;
}

// Cek duplikasi kode_sub (pastikan tidak sama dengan milik sendiri)
$checkDup = $conn->prepare("SELECT COUNT(*) FROM sub_masalah WHERE kode_sub = ? AND id_sub != ?");
if (!$checkDup) {
    error_log("❌ Prepare gagal (check duplicate): " . $conn->error);
    echo json_encode(["success" => false, "message" => "Gagal mempersiapkan query cek duplikasi."]);
    exit;
}
$checkDup->bind_param("si", $kode_sub, $id_sub);
$checkDup->execute();
$checkDup->bind_result($dupCount);
$checkDup->fetch();
$checkDup->close();

if ($dupCount > 0) {
    error_log("⚠️ Duplikasi kode_sub terdeteksi untuk kode_sub=$kode_sub (id_sub lain).");
    echo json_encode(["success" => false, "message" => "Kode sub masalah sudah digunakan oleh data lain."]);
    exit;
}

// Update data
$stmt = $conn->prepare("UPDATE sub_masalah SET kode_sub = ?, id_pokok = ?, topik_sub = ?, updated_at = NOW() WHERE id_sub = ?");
if (!$stmt) {
    error_log("❌ Prepare gagal (update): " . $conn->error);
    echo json_encode(["success" => false, "message" => "Gagal mempersiapkan query update."]);
    exit;
}

$stmt->bind_param("sisi", $kode_sub, $id_pokok, $uraian, $id_sub);

if ($stmt->execute()) {
    error_log("✅ Update berhasil untuk id_sub=$id_sub (kode_sub=$kode_sub, id_pokok=$id_pokok)");
    echo json_encode(["success" => true, "message" => "Sub masalah berhasil diperbarui."]);
} else {
    error_log("❌ Gagal eksekusi update: " . $stmt->error);
    echo json_encode(["success" => false, "message" => "Gagal memperbarui sub masalah."]);
}

$stmt->close();
$conn->close();
?>
