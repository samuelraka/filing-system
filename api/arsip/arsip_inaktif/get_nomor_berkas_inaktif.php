<?php
require_once("../../../config/database.php");
header("Content-Type: application/json");

$id_subsub = $_GET['id_subsub'] ?? null;
$nomor_berkas = $_GET['nomor_berkas'] ?? null; // ← tambahkan parameter dari frontend

if (!$id_subsub) {
    echo json_encode(["status" => "error", "message" => "id_subsub tidak valid"]);
    exit;
}

if ($nomor_berkas) {
    // 🔹 Cek apakah nomor berkas ini sudah ada untuk subsub tersebut
    $sql = "SELECT id_arsip, jumlah_item 
            FROM arsip_inaktif 
            WHERE id_subsub = ? AND nomor_berkas = ?
            LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $id_subsub, $nomor_berkas);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Jika sudah ada → lanjutkan nomor item berikutnya
        $row = $result->fetch_assoc();
        echo json_encode([
            "status" => "existing",
            "nomor_berkas" => $nomor_berkas,
            "nomor_item" => $row['jumlah_item'] + 1
        ]);
    } else {
        // Jika belum ada → nomor item mulai dari 1
        echo json_encode([
            "status" => "new",
            "nomor_berkas" => $nomor_berkas,
            "nomor_item" => 1
        ]);
    }
} else {
    // 🔹 Kalau nomor_berkas belum diinput, ambil nomor terakhir dari subsub tersebut
    $sql = "SELECT nomor_berkas 
            FROM arsip_inaktif 
            WHERE id_subsub = ? 
            ORDER BY nomor_berkas DESC 
            LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_subsub);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $next_berkas = $row['nomor_berkas'];
    } else {
        // Belum ada sama sekali, ambil nomor terakhir global
        $max_sql = "SELECT MAX(nomor_berkas) AS max_berkas FROM arsip_inaktif";
        $max_result = $conn->query($max_sql);
        $max_row = $max_result->fetch_assoc();
        $next_berkas = ($max_row['max_berkas'] ?? 0) + 1;
    }

    echo json_encode([
        "status" => "new",
        "nomor_berkas" => $next_berkas,
        "nomor_item" => 1
    ]);
}
?>
