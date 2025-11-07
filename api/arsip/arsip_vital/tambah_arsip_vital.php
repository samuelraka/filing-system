<?php
header("Content-Type: application/json");
include "../../../config/database.php"; // sesuaikan path koneksi kamu

$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
        $stmt = $conn->prepare("
            INSERT INTO arsip_vital 
            (jenis_arsip, tingkat_perkembangan, kurun_tahun, media, jumlah, jangka_simpan, lokasi_simpan, metode_perlindungan, keterangan)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->bind_param(
            "ssssissss",
            $jenis_arsip,
            $tingkat_perkembangan,
            $kurun_tahun,
            $media,
            $jumlah,
            $jangka_simpan,
            $lokasi_simpan,
            $metode_perlindungan,
            $keterangan
        );

        if ($stmt->execute()) {
            $response = ["success" => true, "message" => "Arsip berhasil ditambahkan."];
        } else {
            throw new Exception("Gagal menambahkan arsip.");
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
