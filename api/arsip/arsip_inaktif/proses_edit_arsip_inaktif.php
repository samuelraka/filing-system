<?php
// ======================
// Konfigurasi Error Log
// ======================
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . "/../../../error_log.txt");

header('Content-Type: application/json');

include_once "../../../config/database.php";
include_once "../../../config/session.php";

try {
    // Otentikasi
    if (!isLoggedIn() || !isAdminOrSuperAdmin()) {
        http_response_code(403);
        echo json_encode([
            "success" => false,
            "message" => "Tidak diizinkan."
        ]);
        exit;
    }

    // Ambil ID item
    $id_item = $_POST['id_item'] ?? null;
    if (!$id_item || !ctype_digit((string)$id_item)) {
        throw new Exception("ID item tidak valid.");
    }
    $id_item_int = (int)$id_item;

    // Pastikan item ada
    $stmt = $conn->prepare("SELECT id_arsip FROM item_arsip_inaktif WHERE id_item = ?");
    $stmt->bind_param("i", $id_item_int);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 0) {
        throw new Exception("Item arsip inaktif tidak ditemukan.");
    }
    $stmt->close();

    // Field yang dapat diedit
    $kategori = $_POST['kategoriArsip'] ?? null;
    $kurun_waktu = $_POST['kurunWaktu'] ?? null;
    $jangka_simpan = $_POST['jangkaSimpan'] ?? null;
    $nomor_boks = $_POST['nomorBoks'] ?? null;
    $lokasi_simpan = $_POST['lokasiSimpan'] ?? null;
    $tingkat_perkembangan = $_POST['tingkatPerkembangan'] ?? null;
    $uraian_informasi = $_POST['uraianInformasi'] ?? null;
    $keterangan = $_POST['keterangan'] ?? null;

    // Update item_arsip_inaktif
    $stmt_update = $conn->prepare(
        "UPDATE item_arsip_inaktif 
         SET kategori_arsip = ?, kurun_waktu = ?, jangka_simpan = ?, nomor_boks = ?, lokasi_simpan = ?, tingkat_perkembangan = ?, uraian_informasi = ?, keterangan = ?
         WHERE id_item = ?"
    );
    $stmt_update->bind_param(
        "ssssssssi",
        $kategori,
        $kurun_waktu,
        $jangka_simpan,
        $nomor_boks,
        $lokasi_simpan,
        $tingkat_perkembangan,
        $uraian_informasi,
        $keterangan,
        $id_item_int
    );
    if (!$stmt_update->execute()) {
        throw new Exception("Gagal memperbarui item arsip inaktif: " . $stmt_update->error);
    }
    $stmt_update->close();

    echo json_encode([
        "success" => true,
        "message" => "Perubahan arsip inaktif berhasil disimpan."
    ]);
} catch (Exception $e) {
    error_log("Error edit inaktif: " . $e->getMessage());
    echo json_encode([
        "success" => false,
        "message" => "Terjadi kesalahan: " . $e->getMessage()
    ]);
}
?>