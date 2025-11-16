<?php
// ======================
// Konfigurasi Error Log
// ======================
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . "/../../../error_log.txt");

include_once "../../../config/database.php";

header('Content-Type: application/json');

$conn->begin_transaction();

try {
    // Ambil data dari form
    $id_item = $_POST['id_item'] ?? null;
    $tanggal = $_POST['tanggal'] ?? null;
    $keterangan_skaad = $_POST['keteranganSKAAD'] ?? null;
    $uraian_singkat = $_POST['uraianSingkat'] ?? null;
    $uraian_informasi = $_POST['uraianInformasi'] ?? null;
    $keterangan_arsip = $_POST['keterangan'] ?? null;

    if (!$id_item) {
        throw new Exception("ID item tidak ditemukan.");
    }

    if (!$tanggal || !$uraian_singkat || !$uraian_informasi) {
        throw new Exception("Beberapa field wajib tidak diisi.");
    }

    // ======================
    // Langkah 1: Cek item yang akan diedit
    // ======================
    $stmt_check = $conn->prepare("SELECT id_item, file_path FROM item_arsip WHERE id_item = ?");
    $stmt_check->bind_param("i", $id_item);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows == 0) {
        throw new Exception("Item arsip tidak ditemukan.");
    }

    $item_data = $result_check->fetch_assoc();
    $old_file_path = $item_data['file_path'];
    $file_path = $old_file_path; // Default gunakan file lama

    // ======================
    // Langkah 2: Handle file upload (jika ada file baru)
    // ======================
    if (!empty($_FILES['files']['name'][0])) {
        $upload_dir = "../../../uploads/";
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

        $file_names = [];
        foreach ($_FILES['files']['tmp_name'] as $key => $tmp_name) {
            $file_name = time() . "_" . basename($_FILES['files']['name'][$key]);
            $target_file = $upload_dir . $file_name;

            if (move_uploaded_file($tmp_name, $target_file)) {
                $file_names[] = $file_name;
            } else {
                error_log("Gagal upload file: " . $_FILES['files']['name'][$key]);
            }
        }

        if (!empty($file_names)) {
            // Hapus file lama jika ada
            if (!empty($old_file_path)) {
                $old_files = explode(",", $old_file_path);
                foreach ($old_files as $old_file) {
                    $old_file_path_full = $upload_dir . trim($old_file);
                    if (file_exists($old_file_path_full)) {
                        unlink($old_file_path_full);
                    }
                }
            }
            $file_path = implode(",", $file_names);
        }
    }

    // ======================
    // Langkah 3: Update item_arsip
    // ======================
    $query_update = "UPDATE item_arsip 
                     SET tanggal = ?, 
                         keterangan_skaad = ?, 
                         uraian_singkat = ?, 
                         uraian_informasi = ?, 
                         file_path = ?
                     WHERE id_item = ?";
    
    $stmt_update = $conn->prepare($query_update);
    $stmt_update->bind_param("sssssi", $tanggal, $keterangan_skaad, $uraian_singkat, $uraian_informasi, $file_path, $id_item);

    if ($stmt_update->execute()) {
        $conn->commit();

        // ======================
        // Logging
        // ======================
        $log_message = sprintf(
            "Arsip diperbarui: ID Item=%d, Tanggal=%s, SKAAD=%s",
            $id_item,
            $tanggal,
            $keterangan_skaad
        );
        error_log($log_message);
        
        echo json_encode([
            "success" => true,
            "message" => "Arsip berhasil diperbarui!",
            "id_item" => $id_item
        ]);
    } else {
        throw new Exception("Gagal memperbarui item arsip: " . $stmt_update->error);
    }

} catch (Exception $e) {
    $conn->rollback();
    error_log("Error di proses_edit_arsip.php: " . $e->getMessage());
    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
}
?>
