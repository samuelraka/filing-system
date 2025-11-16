<?php
// Nonaktifkan tampilan error, tapi simpan ke file log
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set("error_log", __DIR__ . "/../../../error_log.txt");

// Koneksi ke database
require_once("../../../config/database.php");

// Pastikan method POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Akses tidak valid!");
}

$action = $_POST['action'] ?? '';
$id = $_POST['id_arsip_statis'] ?? null;
$kode_klasifikasi = $_POST['id_subsub'] ?? '';
$jenis_arsip = $_POST['jenis_arsip'] ?? '';
$tahun = $_POST['tahun'] ?? '';
$jumlah = $_POST['jumlah'] ?? '';
$tingkat_perkembangan = $_POST['tingkat_perkembangan'] ?? '';
$keterangan = $_POST['keterangan'] ?? '';

$allowed = ['insert', 'update', 'delete'];
if (!in_array($action, $allowed)) {
    die("Aksi tidak diizinkan!");
}

// Fungsi untuk menangani upload file
function handleFileUpload($files) {
    $uploadDir = __DIR__ . "/../../../uploads/arsip_statis/";
    
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

try {
    if ($action === 'insert') {
        // Handle file upload
        $uploadedFiles = handleFileUpload($_FILES['files'] ?? []);
        $filesJson = !empty($uploadedFiles) ? json_encode($uploadedFiles) : null;
        
        $query = "INSERT INTO arsip_statis (id_subsub, jenis_arsip, tahun, jumlah, tingkat_perkembangan, keterangan, file_path) 
                  VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("isissss", $kode_klasifikasi, $jenis_arsip, $tahun, $jumlah, $tingkat_perkembangan, $keterangan, $filesJson);
        $stmt->execute();

        header("Location: ../../../pages/statis.php?msg=success_insert");
        exit();

    } elseif ($action === 'update') {
        // Handle file upload jika ada
        $uploadedFiles = handleFileUpload($_FILES['files'] ?? []);
        
        if (!empty($uploadedFiles)) {
            $filesJson = json_encode($uploadedFiles);
            $query = "UPDATE arsip_statis 
                      SET id_subsub = ?, jenis_arsip = ?, tahun = ?, jumlah = ?, tingkat_perkembangan = ?, keterangan = ?, file_path = ?
                      WHERE id_arsip_statis = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("isissssi", $kode_klasifikasi, $jenis_arsip, $tahun, $jumlah, $tingkat_perkembangan, $keterangan, $filesJson, $id);
        } else {
            $query = "UPDATE arsip_statis 
                      SET id_subsub = ?, jenis_arsip = ?, tahun = ?, jumlah = ?, tingkat_perkembangan = ?, keterangan = ?
                      WHERE id_arsip_statis = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("isisssi", $kode_klasifikasi, $jenis_arsip, $tahun, $jumlah, $tingkat_perkembangan, $keterangan, $id);
        }
        $stmt->execute();

        header("Location: ../../../pages/statis.php?msg=success_update");
        exit();

    } elseif ($action === 'delete') {
        $query = "DELETE FROM arsip_statis WHERE id_arsip_statis = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        header("Location: ../../../pages/statis.php?msg=success_delete");
        exit();
    }

} catch (Exception $e) {
    error_log("Error di proses_statis.php: " . $e->getMessage());
    header("Location: ../../../pages/statis.php?msg=error");
    exit();
}
?>
