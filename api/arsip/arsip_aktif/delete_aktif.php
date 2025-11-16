<?php
// Delete handler for Arsip Aktif items
include_once __DIR__ . '/../../../config/session.php';
include_once __DIR__ . '/../../../config/database.php';

requireLogin();

// Only admin or superadmin may delete
if (!isAdminOrSuperAdmin()) {
    header('Location: ../../../pages/aktif.php?msg=forbidden');
    exit();
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    header('Location: ../../../pages/aktif.php?msg=invalid_id');
    exit();
}

$conn->begin_transaction();
try {
    // Get the item details before deletion for logging
    $stmt_get = $conn->prepare('SELECT * FROM item_arsip WHERE id_item = ?');
    $stmt_get->bind_param('i', $id);
    $stmt_get->execute();
    $item_result = $stmt_get->get_result();
    $item_data = $item_result->fetch_assoc();
    $stmt_get->close();

    // Find parent arsip for counter update
    $stmt = $conn->prepare('SELECT id_arsip FROM item_arsip WHERE id_item = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $res = $stmt->get_result();
    if (!$res || $res->num_rows === 0) {
        throw new Exception('Item arsip tidak ditemukan');
    }
    $row = $res->fetch_assoc();
    $id_arsip = intval($row['id_arsip']);
    $stmt->close();

    // Delete item
    $del = $conn->prepare('DELETE FROM item_arsip WHERE id_item = ?');
    $del->bind_param('i', $id);
    if (!$del->execute()) {
        throw new Exception('Gagal menghapus item arsip');
    }
    $del->close();

    // Decrement jumlah_item counter safely
    if ($id_arsip > 0) {
        $conn->query("UPDATE arsip_aktif SET jumlah_item = GREATEST(jumlah_item - 1, 0) WHERE id_arsip = $id_arsip");
    }

    $conn->commit();
    header('Location: ../../../pages/aktif.php?msg=deleted');
    exit();
} catch (Exception $e) {
    $conn->rollback();
    error_log('[delete_aktif] ' . $e->getMessage());
    header('Location: ../../../pages/aktif.php?msg=delete_error');
    exit();
}
?>
