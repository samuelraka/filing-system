<?php
header('Content-Type: application/json');
require_once '../../config/database.php';
require_once '../../config/session.php';

if (!isset($_GET['id_user'])) {
    echo json_encode(['success' => false, 'message' => 'ID pengguna tidak diberikan']);
    exit;
}

$id_user = intval($_GET['id_user']);

try {
    $stmt = $conn->prepare("
        SELECT 
            u.id_user, 
            u.nama, 
            u.email, 
            u.username, 
            u.role,
            p.id_unit,
            up.nama_unit
        FROM user u
        LEFT JOIN profil p ON u.id_user = p.id_user
        LEFT JOIN unit_pengolah up ON p.id_unit = up.id_unit
        WHERE u.id_user = ?
    ");
    $stmt->bind_param("i", $id_user);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        echo json_encode([
            'success' => true,
            'user' => $user
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Pengguna tidak ditemukan.'
        ]);
    }

    $stmt->close();
    $conn->close();

} catch (Exception $e) {
    error_log("get_user.php ERROR: " . $e->getMessage() . "\n", 3, "../../error_log.txt");
    echo json_encode(['success' => false, 'message' => 'Terjadi kesalahan server.']);
}
?>