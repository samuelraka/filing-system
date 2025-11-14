<?php
// Delete handler for Arsip Inaktif items
include_once __DIR__ . '/../config/session.php';
include_once __DIR__ . '/../config/database.php';

requireLogin();

// Only admin or superadmin may delete
if (!isAdminOrSuperAdmin()) {
    header('Location: ../pages/inaktif.php?msg=forbidden');
    exit();
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    header('Location: ../pages/inaktif.php?msg=invalid_id');
    exit();
}

$conn->begin_transaction();
try {
    // Find parent arsip for counter update
    $stmt = $conn->prepare('SELECT id_arsip_inaktif FROM item_arsip_inaktif WHERE id_item_inaktif = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $res = $stmt->get_result();
    if (!$res || $res->num_rows === 0) {
        throw new Exception('Item arsip inaktif tidak ditemukan');
    }
    $row = $res->fetch_assoc();
    $id_arsip = intval($row['id_arsip_inaktif']);
    $stmt->close();

    // Delete item
    $del = $conn->prepare('DELETE FROM item_arsip_inaktif WHERE id_item_inaktif = ?');
    $del->bind_param('i', $id);
    if (!$del->execute()) {
        throw new Exception('Gagal menghapus item arsip inaktif');
    }
    $del->close();

    // Decrement jumlah_item counter safely
    if ($id_arsip > 0) {
        $conn->query("UPDATE arsip_inaktif SET jumlah_item = GREATEST(jumlah_item - 1, 0) WHERE id_arsip_inaktif = $id_arsip");
    }

    $conn->commit();
    header('Location: ../pages/inaktif.php?msg=deleted');
    exit();
} catch (Exception $e) {
    $conn->rollback();
    error_log('[delete_inaktif] ' . $e->getMessage());
    header('Location: ../pages/inaktif.php?msg=delete_error');
    exit();
}
?>