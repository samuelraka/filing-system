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

try {
    if ($action === 'insert') {
        $query = "INSERT INTO arsip_statis (id_subsub, jenis_arsip, tahun, jumlah, tingkat_perkembangan, keterangan) 
                  VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("isisss", $kode_klasifikasi, $jenis_arsip, $tahun, $jumlah, $tingkat_perkembangan, $keterangan);
        $stmt->execute();

        header("Location: ../../../pages/statis.php?msg=success_insert");
        exit();

    } elseif ($action === 'update') {
        $query = "UPDATE arsip_statis 
                  SET id_subsub = ?, jenis_arsip = ?, tahun = ?, jumlah = ?, tingkat_perkembangan = ?, keterangan = ?
                  WHERE id_arsip_statis = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("isisssi", $kode_klasifikasi, $jenis_arsip, $tahun, $jumlah, $tingkat_perkembangan, $keterangan, $id);
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
