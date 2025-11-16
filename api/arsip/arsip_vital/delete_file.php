<?php
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . "/../../../error_log.txt");

require_once("../../../config/database.php");

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$file = isset($_GET['file']) ? basename($_GET['file']) : '';

if ($id <= 0 || $file === '') {
    header('Location: ../../../pages/edit_vital.php?id=' . urlencode($id) . '&status=invalid');
    exit;
}

$stmt = $conn->prepare("SELECT file_path FROM arsip_vital WHERE id_arsip = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
$files = [];
if ($row = $res->fetch_assoc()) {
    $dec = json_decode($row['file_path'] ?? '[]', true);
    if (is_array($dec)) { $files = $dec; }
}
$stmt->close();

$newFiles = [];
$found = false;
foreach ($files as $f) {
    if ($f === $file) { $found = true; continue; }
    $newFiles[] = $f;
}

if ($found) {
    $json = json_encode($newFiles);
    $up = $conn->prepare("UPDATE arsip_vital SET file_path = ? WHERE id_arsip = ?");
    $up->bind_param("si", $json, $id);
    $up->execute();
    $up->close();

    $path = __DIR__ . "/../../../uploads/arsip_vital/" . $file;
    if (is_file($path)) { @unlink($path); }
}

header('Location: ../../../pages/edit_vital.php?id=' . urlencode($id) . '&status=deleted');
exit;
?>