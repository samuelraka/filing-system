<?php
include_once __DIR__ . '/../../config/database.php';

// Nonaktifkan tampilan error di browser dan simpan log ke file
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set("error_log", __DIR__ . "/../../error_log.txt");

header('Content-Type: application/json');

try {
    // Ambil data JSON
    $data = json_decode(file_get_contents("php://input"), true);
    $id_pokok = $data['id_pokok_masalah'] ?? '';
    $kode_pokok = $data['kode_pokok_masalah'] ?? '';
    $nama_pokok = $data['nama_pokok_masalah'] ?? '';

    // Validasi input
    if (empty($id_pokok) || empty($kode_pokok) || empty($nama_pokok)) {
        echo json_encode([
            'success' => false,
            'message' => 'Semua field wajib diisi.'
        ]);
        exit;
    }

    // Update data pokok_masalah
    $stmt = $conn->prepare("UPDATE pokok_masalah 
                            SET kode_pokok = ?, topik_pokok = ?, updated_at = NOW()
                            WHERE id_pokok = ?");
    $stmt->bind_param("ssi", $kode_pokok, $nama_pokok, $id_pokok);

    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'Data pokok masalah berhasil diperbarui.'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Gagal memperbarui data pokok masalah.'
        ]);
    }

    $stmt->close();
    $conn->close();

} catch (Exception $e) {
    error_log("Error edit_pokok_masalah.php: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Terjadi kesalahan server. Silakan coba lagi nanti.'
    ]);
}
