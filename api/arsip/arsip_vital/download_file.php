<?php
// Nonaktifkan tampilan error, tapi simpan ke file log
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set("error_log", __DIR__ . "/../../../error_log.txt");

// Koneksi ke database
require_once("../../../config/database.php");

// Validasi parameter
$file = isset($_GET['file']) ? basename($_GET['file']) : '';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if (empty($file) || $id <= 0) {
    http_response_code(400);
    die("Parameter tidak valid");
}

// Validasi bahwa file milik arsip yang diminta
try {
    $stmt = $conn->prepare("SELECT file_path FROM arsip_vital WHERE id_arsip = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();
    
    if ($row = $res->fetch_assoc()) {
        $files = json_decode($row['file_path'], true);
        
        // Cek apakah file ada dalam list
        if (!is_array($files) || !in_array($file, $files)) {
            http_response_code(403);
            die("File tidak ditemukan atau tidak diizinkan");
        }
    } else {
        http_response_code(404);
        die("Arsip tidak ditemukan");
    }
    $stmt->close();
} catch (Exception $e) {
    error_log("Error di download_file.php: " . $e->getMessage());
    http_response_code(500);
    die("Terjadi kesalahan");
}

// Path file
$uploadDir = __DIR__ . "/../../../uploads/arsip_vital/";
$filePath = $uploadDir . $file;

// Validasi file exists dan berada di direktori yang benar
if (!file_exists($filePath) || !is_file($filePath)) {
    http_response_code(404);
    die("File tidak ditemukan");
}

// Validasi file adalah PDF
if (strtolower(pathinfo($filePath, PATHINFO_EXTENSION)) !== 'pdf') {
    http_response_code(403);
    die("Hanya file PDF yang diizinkan");
}

// Download file
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="' . $file . '"');
header('Content-Length: ' . filesize($filePath));
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

readfile($filePath);
exit();
?>
