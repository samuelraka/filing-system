<?php
header("Content-Type: application/json");
require_once __DIR__ . "/../../config/database.php";

// Konfigurasi error log
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set("error_log", __DIR__ . "/../../error_log.txt"); // simpan error ke file log

$response = ["success" => false, "message" => "Terjadi kesalahan."];

try {
    // Ambil data dari body JSON
    $data = json_decode(file_get_contents("php://input"), true);

    if (!$data) {
        throw new Exception("Data tidak valid.");
    }

    // Validasi input
    if (
        empty($data['id_sub_sub_masalah']) ||
        empty($data['kode_sub_sub_masalah']) ||
        empty($data['id_sub_masalah']) ||
        empty($data['topik_sub_sub_masalah'])
    ) {
        throw new Exception("Semua field wajib diisi.");
    }

    $id_sub_sub = intval($data['id_sub_sub_masalah']);
    $kode_sub_sub = trim($data['kode_sub_sub_masalah']);
    $id_sub_masalah = intval($data['id_sub_masalah']);
    $topik_sub_sub = trim($data['topik_sub_sub_masalah']);

    // Pastikan id_sub_masalah valid di tabel sub_masalah
    $cek = $conn->prepare("SELECT id_sub FROM sub_masalah WHERE id_sub = ?");
    $cek->bind_param("i", $id_sub_masalah);
    $cek->execute();
    $cekResult = $cek->get_result();

    if ($cekResult->num_rows === 0) {
        throw new Exception("Sub Masalah yang dipilih tidak valid.");
    }
    $cek->close();

    // Query update
    $stmt = $conn->prepare("UPDATE sub_sub_masalah SET kode_subsub = ?, id_sub = ?, topik_subsub = ? WHERE id_subsub = ?");
    $stmt->bind_param("sisi", $kode_sub_sub, $id_sub_masalah, $topik_sub_sub, $id_sub_sub);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            $response = ["success" => true, "message" => "Sub Sub Masalah berhasil diperbarui."];
        } else {
            $response = ["success" => false, "message" => "Tidak ada perubahan data."];
        }
    } else {
        throw new Exception("Gagal mengupdate data Sub Sub Masalah.");
    }

    $stmt->close();
    $conn->close();

} catch (Exception $e) {
    error_log("Error edit_sub_sub_masalah.php: " . $e->getMessage());
    $response = ["success" => false, "message" => $e->getMessage()];
}

echo json_encode($response);
?>
