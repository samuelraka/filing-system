<?php
header('Content-Type: application/json');
require_once '../../config/database.php';
require_once '../../config/session.php';

// Konfigurasi logging
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', '../../error_log.txt');

if (!isset($_GET['id_user'])) {
    error_log("[get_user.php] ❌ ID pengguna tidak diberikan.\n", 3, "../../error_log.txt");
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
            COALESCE(p.id_unit, '') AS id_unit,
            COALESCE(up.nama_unit, '') AS nama_unit
        FROM user u
        LEFT JOIN profil p ON u.id_user = p.id_user
        LEFT JOIN unit_pengolah up ON p.id_unit = up.id_unit
        WHERE u.id_user = ?
    ");
    $stmt->bind_param("i", $id_user);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        // ✅ Logging data yang diambil
        $log_message = "[get_user.php] ✅ Data user berhasil diambil:\n" .
            "ID User: {$user['id_user']}\n" .
            "Nama: {$user['nama']}\n" .
            "Email: {$user['email']}\n" .
            "Username: {$user['username']}\n" .
            "Role: {$user['role']}\n" .
            "ID Unit: {$user['id_unit']}\n" .
            "Nama Unit: {$user['nama_unit']}\n" .
            "------------------------------------\n";
        error_log($log_message, 3, "../../error_log.txt");

        echo json_encode(['success' => true, 'user' => $user]);
    } else {
        error_log("[get_user.php] ⚠️ Pengguna dengan ID {$id_user} tidak ditemukan.\n", 3, "../../error_log.txt");
        echo json_encode(['success' => false, 'message' => 'Pengguna tidak ditemukan.']);
    }

    $stmt->close();
    $conn->close();

} catch (Exception $e) {
    error_log("[get_user.php] ❌ ERROR: " . $e->getMessage() . "\n", 3, "../../error_log.txt");
    echo json_encode(['success' => false, 'message' => 'Terjadi kesalahan server.']);
}
