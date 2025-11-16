<?php
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . "/../../../error_log.txt");

include_once __DIR__ . '/../../../config/session.php';
include_once __DIR__ . '/../../../config/database.php';

if (!isLoggedIn()) {
    header('Location: ../../../login.php');
    exit;
}

if (getUserRole() !== 'superadmin') {
    header('Location: ../../../pages/vital.php?msg=forbidden');
    exit;
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    header('Location: ../../../pages/vital.php?msg=invalid_id');
    exit;
}

$files = [];
$stmt = $conn->prepare('SELECT file_path FROM arsip_vital WHERE id_arsip = ?');
$stmt->bind_param('i', $id);
$stmt->execute();
$res = $stmt->get_result();
if ($row = $res->fetch_assoc()) {
    $dec = json_decode($row['file_path'] ?? '[]', true);
    if (is_array($dec)) { $files = $dec; }
}
$stmt->close();

$ok = false;
$del = $conn->prepare('DELETE FROM arsip_vital WHERE id_arsip = ?');
$del->bind_param('i', $id);
$ok = $del->execute();
$del->close();

if (!empty($files)) {
    $base = __DIR__ . '/../../../uploads/arsip_vital/';
    foreach ($files as $f) {
        $p = $base . basename($f);
        if (is_file($p)) { @unlink($p); }
    }
}

if ($ok) {
    header('Location: ../../../pages/vital.php?msg=success_delete');
} else {
    header('Location: ../../../pages/vital.php?msg=delete_error');
}
exit;
?>