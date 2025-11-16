<?php
header("Content-Type: application/json");
include "../../../config/database.php";

$response = [];

// Fungsi untuk menangani upload file
function handleFileUpload($files) {
    $uploadDir = __DIR__ . "/../../../uploads/arsip_vital/";
    
    // Buat direktori jika belum ada
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    
    $uploadedFiles = [];
    $maxFileSize = 10 * 1024 * 1024; // 10 MB
    $allowedExtensions = ['pdf'];
    
    if (isset($files['name']) && is_array($files['name'])) {
        $fileCount = count($files['name']);
        
        for ($i = 0; $i < $fileCount; $i++) {
            if ($files['error'][$i] === UPLOAD_ERR_NO_FILE) {
                continue; // Skip jika tidak ada file
            }
            
            if ($files['error'][$i] !== UPLOAD_ERR_OK) {
                throw new Exception("Error upload file: " . $files['error'][$i]);
            }
            
            $fileName = $files['name'][$i];
            $fileTmpName = $files['tmp_name'][$i];
            $fileSize = $files['size'][$i];
            
            // Validasi ukuran file
            if ($fileSize > $maxFileSize) {
                throw new Exception("File terlalu besar: " . $fileName);
            }
            
            // Validasi ekstensi file
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            if (!in_array($fileExtension, $allowedExtensions)) {
                throw new Exception("Tipe file tidak diizinkan: " . $fileName);
            }
            
            // Generate nama file unik
            $uniqueFileName = time() . "_" . uniqid() . "_" . basename($fileName);
            $filePath = $uploadDir . $uniqueFileName;
            
            // Pindahkan file
            if (!move_uploaded_file($fileTmpName, $filePath)) {
                throw new Exception("Gagal memindahkan file: " . $fileName);
            }
            
            $uploadedFiles[] = $uniqueFileName;
        }
    }
    
    return $uploadedFiles;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_arsip = $_POST['id_arsip'] ?? 0;
    $jenis_arsip = $_POST['jenis_arsip'] ?? '';
    $tingkat_perkembangan = $_POST['tingkat_perkembangan'] ?? '';
    $kurun_tahun = $_POST['kurun_tahun'] ?? '';
    $media = $_POST['media'] ?? '';
    $jumlah = $_POST['jumlah'] ?? 0;
    $jangka_simpan = $_POST['jangka_simpan'] ?? '';
    $lokasi_simpan = $_POST['lokasi_simpan'] ?? '';
    $metode_perlindungan = $_POST['metode_perlindungan'] ?? '';
    $keterangan = $_POST['keterangan'] ?? '';

    try {
        $hasJenis = false; $hasUraian = false; $hasKurunTahun = false; $hasKurunWaktu = false; $hasFilePath = false;
        $r = $conn->query("SHOW COLUMNS FROM arsip_vital LIKE 'jenis_arsip'"); if ($r && $r->num_rows > 0) { $hasJenis = true; }
        $r = $conn->query("SHOW COLUMNS FROM arsip_vital LIKE 'uraian_arsip'"); if ($r && $r->num_rows > 0) { $hasUraian = true; }
        $r = $conn->query("SHOW COLUMNS FROM arsip_vital LIKE 'kurun_tahun'"); if ($r && $r->num_rows > 0) { $hasKurunTahun = true; }
        $r = $conn->query("SHOW COLUMNS FROM arsip_vital LIKE 'kurun_waktu'"); if ($r && $r->num_rows > 0) { $hasKurunWaktu = true; }
        $r = $conn->query("SHOW COLUMNS FROM arsip_vital LIKE 'file_path'"); if ($r && $r->num_rows > 0) { $hasFilePath = true; }
        $colJenis = $hasJenis ? 'jenis_arsip' : ($hasUraian ? 'uraian_arsip' : 'jenis_arsip');
        $colKurun = $hasKurunTahun ? 'kurun_tahun' : ($hasKurunWaktu ? 'kurun_waktu' : 'kurun_tahun');

        // Handle file upload jika ada
        $uploadedFiles = handleFileUpload($_FILES['files'] ?? []);
        
        if ($hasFilePath && !empty($uploadedFiles)) {
            $existing = [];
            $stmtCur = $conn->prepare("SELECT file_path FROM arsip_vital WHERE id_arsip = ?");
            if ($stmtCur) {
                $stmtCur->bind_param("i", $id_arsip);
                $stmtCur->execute();
                $resCur = $stmtCur->get_result();
                if ($rowCur = $resCur->fetch_assoc()) {
                    $dec = json_decode($rowCur['file_path'], true);
                    if (is_array($dec)) { $existing = $dec; }
                }
                $stmtCur->close();
            }
            $merged = array_values(array_unique(array_merge($existing, $uploadedFiles)));
            $filesJson = json_encode($merged);
            $stmt = $conn->prepare("
                UPDATE arsip_vital 
                SET $colJenis = ?, tingkat_perkembangan = ?, $colKurun = ?, media = ?, jumlah = ?, jangka_simpan = ?, lokasi_simpan = ?, metode_perlindungan = ?, keterangan = ?, file_path = ?
                WHERE id_arsip = ?
            ");

            $stmt->bind_param(
                "ssssisssssi",
                $jenis_arsip,
                $tingkat_perkembangan,
                $kurun_tahun,
                $media,
                $jumlah,
                $jangka_simpan,
                $lokasi_simpan,
                $metode_perlindungan,
                $keterangan,
                $filesJson,
                $id_arsip
            );
        } else {
            $stmt = $conn->prepare("
                UPDATE arsip_vital 
                SET $colJenis = ?, tingkat_perkembangan = ?, $colKurun = ?, media = ?, jumlah = ?, jangka_simpan = ?, lokasi_simpan = ?, metode_perlindungan = ?, keterangan = ?
                WHERE id_arsip = ?
            ");

            $stmt->bind_param(
                "ssssissssi",
                $jenis_arsip,
                $tingkat_perkembangan,
                $kurun_tahun,
                $media,
                $jumlah,
                $jangka_simpan,
                $lokasi_simpan,
                $metode_perlindungan,
                $keterangan,
                $id_arsip
            );
        }

        if ($stmt->execute()) {
            $response = ["success" => true, "message" => "Arsip berhasil diperbarui."];
        } else {
            throw new Exception("Gagal memperbarui arsip.");
        }

        $stmt->close();
    } catch (Exception $e) {
        $response = ["success" => false, "message" => $e->getMessage()];
    }
} else {
    $response = ["success" => false, "message" => "Metode tidak diizinkan."];
}

echo json_encode($response);
?>
