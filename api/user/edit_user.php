<?php
include_once __DIR__ . '/../../config/database.php';
include_once __DIR__ . '/../../config/session.php';

header('Content-Type: application/json');

try {
    $data = json_decode(file_get_contents("php://input"), true);

    if (!$data || empty($data['id_user'])) {
        throw new Exception("Data tidak lengkap.");
    }

    $id_user = $data['id_user'];
    $name = $data['name'] ?? '';
    $email = $data['email'] ?? '';
    $role = $data['role'] ?? '';
    $id_unit = $data['unit_pengolah'] ?? null;
    $password = !empty($data['password']) ? password_hash($data['password'], PASSWORD_DEFAULT) : null;

    // Log input untuk debugging
    $log_msg = "[" . date('Y-m-d H:i:s') . "] Update user ID $id_user - Nama: $name, Email: $email, Role: $role, Unit: " . ($id_unit ?? 'NULL') . "\n";
    error_log($log_msg, 3, __DIR__ . "/../../error_log.txt");

    // Update tabel user
    if ($password) {
        $stmt = $conn->prepare("UPDATE user SET nama=?, email=?, role=?, password=? WHERE id_user=?");
        $stmt->bind_param("ssssi", $name, $email, $role, $password, $id_user);
    } else {
        $stmt = $conn->prepare("UPDATE user SET nama=?, email=?, role=? WHERE id_user=?");
        $stmt->bind_param("sssi", $name, $email, $role, $id_user);

        error_log("[" . date('Y-m-d H:i:s') . "] 🔍 Data diterima update_user: " . print_r($data, true) . "\n", 3, __DIR__ . "/../../error_log.txt");
    }

    if (!$stmt->execute()) {
        throw new Exception("Gagal mengupdate data user: " . $stmt->error);
    }

    // Jika unit pengolah dikirim → update/insert ke tabel profil
    if (!empty($id_unit)) {
        // Cek apakah user sudah punya data di profil
        $cek = $conn->prepare("SELECT COUNT(*) FROM profil WHERE id_user=?");
        $cek->bind_param("i", $id_user);
        $cek->execute();
        $cek->bind_result($count);
        $cek->fetch();
        $cek->close();

        if ($count > 0) {
            // Update profil existing
            $update_profil = $conn->prepare("UPDATE profil SET id_unit=? WHERE id_user=?");
            $update_profil->bind_param("ii", $id_unit, $id_user);
            if (!$update_profil->execute()) {
                throw new Exception("Gagal memperbarui unit pengolah di profil: " . $update_profil->error);
            }
            $update_profil->close();
        } else {
            // Insert baru jika belum ada
            $insert_profil = $conn->prepare("INSERT INTO profil (id_user, id_unit) VALUES (?, ?)");
            $insert_profil->bind_param("ii", $id_user, $id_unit);
            if (!$insert_profil->execute()) {
                throw new Exception("Gagal menambahkan unit pengolah di profil: " . $insert_profil->error);
            }
            $insert_profil->close();
        }

        error_log("[" . date('Y-m-d H:i:s') . "] Profil user ID $id_user diperbarui ke unit ID $id_unit\n", 3, __DIR__ . "/../../error_log.txt");
    }

    echo json_encode(["success" => true, "message" => "Data pengguna berhasil diperbarui."]);

} catch (Exception $e) {
    error_log("[" . date('Y-m-d H:i:s') . "] ERROR update_user.php: " . $e->getMessage() . "\n", 3, __DIR__ . "/../../error_log.txt");
    echo json_encode(["success" => false, "message" => "Terjadi kesalahan: " . $e->getMessage()]);
}
