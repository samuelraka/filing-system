<?php
session_start();
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set("error_log", __DIR__ . "/../../error_log.txt");

include_once '../../config/database.php'; // pastikan koneksi ada

header('Content-Type: application/json');

// Cek role
if (!isset($_SESSION['user_role']) || strtolower($_SESSION['user_role']) !== 'superadmin') {
    echo json_encode([
        "success" => false,
        "message" => "Akses ditolak! Hanya superadmin yang dapat menambahkan pokok masalah."
    ]);
    exit;
}

// Ambil input dari frontend
$data = json_decode(file_get_contents('php://input'), true);

$kode = trim($data['kode_pokok_masalah'] ?? '');
$nama = trim($data['nama_pokok_masalah'] ?? '');

// Validasi input
if ($kode === '' || $nama === '') {
    echo json_encode(['success' => false, 'message' => 'Semua field wajib diisi.']);
    exit;
}

try {
    $query = "INSERT INTO pokok_masalah (kode_pokok, topik_pokok) VALUES (?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->execute([$kode, $nama]);

    echo json_encode(['success' => true, 'message' => 'Pokok Masalah berhasil ditambahkan.']);
} catch (Exception $e) {
    error_log("Error insert pokok_masalah: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Gagal menambahkan data.']);
}
