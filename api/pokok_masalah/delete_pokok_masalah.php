<?php
include_once __DIR__ . '/../../config/database.php';

// Nonaktifkan tampilan error dan aktifkan pencatatan log
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set("error_log", __DIR__ . "/../../error_log.txt");

header('Content-Type: application/json');

try {
    // Ambil data dari body JSON
    $data = json_decode(file_get_contents("php://input"), true);
    $id_pokok = $data['id_pokok_masalah'] ?? '';

    // Validasi input
    if (empty($id_pokok)) {
        echo json_encode([
            'success' => false,
            'message' => 'ID Pokok Masalah tidak boleh kosong.'
        ]);
        exit;
    }

    // Query hapus
    $stmt = $conn->prepare("DELETE FROM pokok_masalah WHERE id_pokok = ?");
    $stmt->bind_param("i", $id_pokok);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo json_encode([
                'success' => true,
                'message' => 'Data pokok masalah berhasil dihapus.'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Data tidak ditemukan atau sudah dihapus.'
            ]);
        }
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Gagal menghapus data pokok masalah.'
        ]);
    }

    $stmt->close();
    $conn->close();

} catch (Exception $e) {
    error_log("Error delete_pokok_masalah.php: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Terjadi kesalahan server. Silakan coba lagi nanti.'
    ]);
}
