<?php
require_once("../../../config/database.php");

header("Content-Type: application/json");

$id_subsub = $_GET['id_subsub'] ?? null;

if (!$id_subsub) {
    echo json_encode(["status" => "error", "message" => "id_subsub tidak valid"]);
    exit;
}

// Cek apakah sudah ada arsip aktif untuk subsub tersebut
$sql = "SELECT id_arsip, nomor_berkas, jumlah_item 
        FROM arsip_aktif 
        WHERE id_subsub = ? 
        ORDER BY id_arsip DESC 
        LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_subsub);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Sudah ada arsip aktif untuk subsub ini
    $row = $result->fetch_assoc();
    echo json_encode([
        "status" => "existing",
        "nomor_berkas" => $row['nomor_berkas'],
        "nomor_item" => $row['jumlah_item'] + 1
    ]);
} else {
    // Subsub baru → ambil nomor_berkas terakhir dari seluruh arsip aktif
    $max_sql = "SELECT MAX(nomor_berkas) AS max_berkas FROM arsip_aktif";
    $max_result = $conn->query($max_sql);
    $max_row = $max_result->fetch_assoc();
    $next_berkas = ($max_row['max_berkas'] ?? 0) + 1;

    echo json_encode([
        "status" => "new",
        "nomor_berkas" => $next_berkas,
        "nomor_item" => 1
    ]);
}
?>
