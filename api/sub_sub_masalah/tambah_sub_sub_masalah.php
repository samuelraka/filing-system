<?php
header("Content-Type: application/json");
require_once __DIR__ . "/../../config/database.php";

ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set("error_log", __DIR__ . "/../../error_log.txt");

$response = ["success" => false, "message" => "Terjadi kesalahan."];

try {
    $data = json_decode(file_get_contents("php://input"), true);

    if (empty($data['kode_sub_sub_masalah']) || empty($data['id_sub_masalah']) || empty($data['uraian_sub_sub_masalah'])) {
        throw new Exception("Semua field wajib diisi.");
    }

    $kode = trim($data['kode_sub_sub_masalah']);
    $id_sub = intval($data['id_sub_masalah']);
    $uraian = trim($data['uraian_sub_sub_masalah']);

    $stmt = $conn->prepare("INSERT INTO sub_sub_masalah (kode_subsub, id_sub, topik_subsub) VALUES (?, ?, ?)");
    $stmt->bind_param("sis", $kode, $id_sub, $uraian);

    if ($stmt->execute()) {
        $response = ["success" => true, "message" => "Sub-Sub Masalah berhasil ditambahkan."];
    } else {
        throw new Exception("Gagal menambahkan data Sub-Sub Masalah.");
    }

    $stmt->close();
    $conn->close();

} catch (Exception $e) {
    error_log("Error tambah_sub_sub_masalah.php: " . $e->getMessage());
    $response = ["success" => false, "message" => $e->getMessage()];
}

echo json_encode($response);
?>
