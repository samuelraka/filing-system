<?php
header("Content-Type: application/json");
ini_set('display_errors', 0);
error_reporting(E_ALL);
include_once __DIR__ . '/../../config/database.php';
include_once __DIR__ . '/../../config/session.php';

// Lokasi file log
$logFile = __DIR__ . '/../../error_log.txt';
function log_error($msg) {
    global $logFile;
    file_put_contents($logFile, "[" . date('Y-m-d H:i:s') . "] " . $msg . "\n", FILE_APPEND);
}

// Hanya admin & superadmin yang boleh menambah user
if (!isset($_SESSION['user_role']) || !in_array($_SESSION['user_role'], ['admin', 'superadmin'])) {
    echo json_encode(["success" => false, "message" => "Akses ditolak!"]);
    log_error("Akses ditolak oleh role: " . ($_SESSION['user_role'] ?? 'unknown'));
    exit;
}

// Baca input JSON
$data = json_decode(file_get_contents("php://input"), true);
if (!$data) {
    echo json_encode(["success" => false, "message" => "Data tidak valid"]);
    log_error("Gagal decode JSON dari input.");
    exit;
}

$name = trim($data['name'] ?? '');
$email = trim($data['email'] ?? '');
$username = trim($data['username'] ?? '');
$password = trim($data['password'] ?? '');
$role = trim($data['role'] ?? '');
$id_unit = intval($data['unit_pengolah'] ?? 0);

// Validasi dasar
if (empty($name) || empty($email) || empty($username) || empty($password) || empty($role)) {
    echo json_encode(["success" => false, "message" => "Semua field wajib diisi!"]);
    log_error("Validasi gagal: Ada field kosong saat menambah user.");
    exit;
}

try {
    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert ke tabel user
    $stmt = $conn->prepare("INSERT INTO user (nama, email, username, password, role) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $name, $email, $username, $hashed_password, $role);

    if (!$stmt->execute()) {
        throw new Exception("Gagal insert user: " . $stmt->error);
    }

    $new_user_id = $stmt->insert_id;

    // Tambahkan ke tabel profil (relasi user ke unit)
    if ($id_unit > 0) {
        $stmt2 = $conn->prepare("INSERT INTO profil (id_user, id_unit) VALUES (?, ?)");
        $stmt2->bind_param("ii", $new_user_id, $id_unit);

        if (!$stmt2->execute()) {
            throw new Exception("Gagal insert profil: " . $stmt2->error);
        }
        $stmt2->close();
    }

    echo json_encode([
        "success" => true,
        "message" => "Pengguna baru berhasil ditambahkan!"
    ]);

    log_error("Berhasil tambah user ID: $new_user_id ($username) oleh " . $_SESSION['user_role']);

} catch (Exception $e) {
    log_error("Error add_user: " . $e->getMessage());
    echo json_encode([
        "success" => false,
        "message" => "Terjadi kesalahan saat menambah pengguna."
    ]);
}

$conn->close();
?>
