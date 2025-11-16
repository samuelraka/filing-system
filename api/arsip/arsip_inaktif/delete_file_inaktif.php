<?php
include_once __DIR__ . '/../../../config/session.php';
include_once __DIR__ . '/../../../config/database.php';

if (!isAdminOrSuperAdmin()) {
    header('Location: ../../../dashboard.php');
    exit;
}

$id = isset($_GET['id']) ? $_GET['id'] : '';
$file = isset($_GET['file']) ? $_GET['file'] : '';

if (empty($id) || !ctype_digit($id) || empty($file)) {
    header('Location: ../../../pages/edit_inaktif.php?id=' . urlencode($id) . '&status=invalid');
    exit;
}

$idInt = intval($id);
$targetDir = __DIR__ . '/../../../uploads_inaktif/';

// Ambil file_path saat ini
$current = '';
$stmt = $conn->prepare('SELECT file_path FROM item_arsip_inaktif WHERE id_item = ?');
$stmt->bind_param('i', $idInt);
$stmt->execute();
$res = $stmt->get_result();
if ($row = $res->fetch_assoc()) {
    $current = $row['file_path'] ?? '';
}
$stmt->close();

$list = array_filter(array_map('trim', explode(',', $current)));

// Hapus dari daftar
$newList = [];
$found = false;
foreach ($list as $fname) {
    if ($fname === $file) {
        $found = true;
        continue;
    }
    $newList[] = $fname;
}

if ($found) {
    $newPath = implode(',', $newList);
    $stmt = $conn->prepare('UPDATE item_arsip_inaktif SET file_path = ? WHERE id_item = ?');
    $stmt->bind_param('si', $newPath, $idInt);
    $stmt->execute();
    $stmt->close();

    $full = $targetDir . $file;
    if (is_file($full)) {
        @unlink($full);
    }
}

header('Location: ../../../pages/edit_inaktif.php?id=' . urlencode($id) . '&status=deleted');
exit;
?>