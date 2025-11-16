<?php
include_once __DIR__ . '/../../../config/session.php';
include_once __DIR__ . '/../../../config/database.php';

if (!isAdminOrSuperAdmin()) {
    header('Location: ../../../dashboard.php');
    exit;
}

$id = isset($_GET['id']) ? $_GET['id'] : '';
if (empty($id) || !ctype_digit($id)) {
    header('Location: ../../../pages/edit_aktif.php?id=' . urlencode($id) . '&status=invalid_id');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_FILES['files'])) {
    header('Location: ../../../pages/edit_aktif.php?id=' . urlencode($id) . '&status=no_files');
    exit;
}

$idInt = intval($id);
$targetDir = __DIR__ . '/../../../uploads/';
if (!is_dir($targetDir)) {
    @mkdir($targetDir, 0775, true);
}

// Ambil file_path saat ini
$current = '';
$stmt = $conn->prepare('SELECT file_path FROM item_arsip WHERE id_item = ?');
$stmt->bind_param('i', $idInt);
$stmt->execute();
$res = $stmt->get_result();
if ($row = $res->fetch_assoc()) {
    $current = $row['file_path'] ?? '';
}
$stmt->close();

$uploaded = [];

$files = $_FILES['files'];
$count = is_array($files['name']) ? count($files['name']) : 0;
for ($i = 0; $i < $count; $i++) {
    $name = $files['name'][$i] ?? '';
    $tmp = $files['tmp_name'][$i] ?? '';
    $error = $files['error'][$i] ?? UPLOAD_ERR_NO_FILE;

    if ($error !== UPLOAD_ERR_OK || empty($tmp)) {
        continue;
    }

    $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
    if ($ext !== 'pdf') {
        continue;
    }

    $base = pathinfo($name, PATHINFO_FILENAME);
    $safeBase = preg_replace('/[^A-Za-z0-9_-]+/', '_', $base);
    $unique = date('Ymd_His') . '_' . $idInt . '_' . substr(sha1(random_bytes(8)), 0, 8);
    $finalName = $safeBase . '_' . $unique . '.pdf';
    $dest = $targetDir . $finalName;

    if (move_uploaded_file($tmp, $dest)) {
        $uploaded[] = $finalName;
    }
}

if (!empty($uploaded)) {
    $list = array_filter(array_map('trim', explode(',', $current)));
    $list = array_merge($list, $uploaded);
    $newPath = implode(',', $list);

    $stmt = $conn->prepare('UPDATE item_arsip SET file_path = ? WHERE id_item = ?');
    $stmt->bind_param('si', $newPath, $idInt);
    $stmt->execute();
    $stmt->close();
}

header('Location: ../../../pages/edit_aktif.php?id=' . urlencode($id) . '&status=uploaded');
exit;
?>