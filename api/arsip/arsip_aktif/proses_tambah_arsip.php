<?php
// ======================
// Konfigurasi Error Log
// ======================
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . "/../../../error_log.txt");

include_once "../../../config/database.php";

$conn->begin_transaction();

try {
    // Ambil data dari form
    $kode_klasifikasi = $_POST['id_subsub'] ?? null;
    $tanggal = $_POST['tanggal'] ?? null;
    $keterangan_skaad = $_POST['keteranganSKAAD'] ?? null;
    $uraian_singkat = $_POST['uraianSingkat'] ?? null;
    $uraian_informasi = $_POST['uraianInformasi'] ?? null;
    $keterangan_arsip = $_POST['keterangan'] ?? null;
    $nomor_berkas_input = $_POST['nomorBerkas'] ?? null;
    $buat_baru = isset($_POST['buatBerkasBaru']); // checkbox
    $id_arsip = $_POST['idArsip'] ?? null;

    if (!$kode_klasifikasi || !$tanggal || !$uraian_singkat || !$uraian_informasi) {
        throw new Exception("Beberapa field wajib tidak diisi.");
    }

    // ======================
    // Langkah 1: Tentukan ID Arsip
    // ======================
    if ($buat_baru) {
        // 🔹 Jika user ingin buat berkas baru
        if (!$nomor_berkas_input || !is_numeric($nomor_berkas_input)) {
            throw new Exception("Nomor berkas baru tidak valid.");
        }

        $query_insert_arsip = "INSERT INTO arsip_aktif (id_subsub, nomor_berkas, jumlah_item, keterangan)
                               VALUES (?, ?, 0, ?)";
        $stmt_insert = $conn->prepare($query_insert_arsip);
        $stmt_insert->bind_param("sis", $kode_klasifikasi, $nomor_berkas_input, $keterangan_arsip);
        $stmt_insert->execute();
        $id_arsip = $conn->insert_id;
        $nomor_berkas = $nomor_berkas_input;

    } else if($id_arsip){
        // Gunakan id_arsip yang dikirim frontend
        $stmt_check = $conn->prepare("SELECT id_arsip, nomor_berkas FROM arsip_aktif WHERE id_arsip = ?");
        $stmt_check->bind_param("i", $id_arsip);
        $stmt_check->execute();
        $data_arsip = $stmt_check->get_result()->fetch_assoc();
        $id_arsip = $data_arsip['id_arsip'];
        $nomor_berkas = $data_arsip['nomor_berkas'];
    } else {
        // 🔹 Tidak buat baru → gunakan arsip terakhir
        $query_check = "SELECT id_arsip, nomor_berkas FROM arsip_aktif WHERE id_subsub = ? ORDER BY id_arsip DESC LIMIT 1";
        $stmt_check = $conn->prepare($query_check);
        $stmt_check->bind_param("s", $kode_klasifikasi);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();

        if ($result_check->num_rows == 0) {
            // Kalau belum ada arsip aktif sama sekali → buat otomatis dengan nomor 1
            $nomor_berkas = 1;
            $query_insert_arsip = "INSERT INTO arsip_aktif (id_subsub, nomor_berkas, jumlah_item, keterangan)
                                   VALUES (?, ?, 0, ?)";
            $stmt_insert = $conn->prepare($query_insert_arsip);
            $stmt_insert->bind_param("sis", $kode_klasifikasi, $nomor_berkas, $keterangan_arsip);
            $stmt_insert->execute();
            $id_arsip = $conn->insert_id;
        } else {
            $data_arsip = $result_check->fetch_assoc();
            $id_arsip = $data_arsip['id_arsip'];
            $nomor_berkas = $data_arsip['nomor_berkas'];
        }
    }

    // ======================
    // Langkah 2: Nomor item berikutnya
    // ======================
    $query_last_item = "SELECT MAX(nomor_item) AS last_item FROM item_arsip WHERE id_arsip = ?";
    $stmt_item = $conn->prepare($query_last_item);
    $stmt_item->bind_param("i", $id_arsip);
    $stmt_item->execute();
    $result_item = $stmt_item->get_result();
    $last_item = $result_item->fetch_assoc()['last_item'] ?? 0;
    $nomor_item = $last_item + 1;

    // ======================
    // Langkah 3: Upload File
    // ======================
    $file_path = null;
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
        $file_path = implode(",", $file_names);
    }

    // ======================
    // Langkah 4: Simpan ke item_arsip
    // ======================
    $query_item = "INSERT INTO item_arsip (id_arsip, nomor_item, tanggal, keterangan_skaad, uraian_singkat, uraian_informasi, file_path)
                   VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt_item_insert = $conn->prepare($query_item);
    $stmt_item_insert->bind_param("iisssss", $id_arsip, $nomor_item, $tanggal, $keterangan_skaad, $uraian_singkat, $uraian_informasi, $file_path);

    if ($stmt_item_insert->execute()) {
        // Update jumlah item di arsip_aktif
        $conn->query("UPDATE arsip_aktif SET jumlah_item = jumlah_item + 1 WHERE id_arsip = $id_arsip");

        $conn->commit();

        // ======================
        // Logging tambahan
        // ======================
        $log_message = sprintf(
            "Arsip baru ditambahkan: ID Arsip=%d, Nomor Berkas=%s, Nomor Item=%d, Tanggal=%s, ID Subsub=%s",
            $id_arsip,
            $nomor_berkas,
            $nomor_item,
            $tanggal,
            $kode_klasifikasi
        );
        error_log($log_message);
        
        echo json_encode([
            "success" => true,
            "message" => "Arsip berhasil disimpan!",
            "id_arsip" => $id_arsip,
            "nomor_berkas" => $nomor_berkas,
            "nomor_item" => $nomor_item
        ]);
    } else {
        throw new Exception("Gagal menyimpan item arsip: " . $stmt_item_insert->error);
    }

} catch (Exception $e) {
    $conn->rollback();
    error_log("Error di tambah_arsip.php: " . $e->getMessage());
    echo json_encode([
        "success" => false,
        "message" => "Terjadi kesalahan! Silakan cek log untuk detail."
    ]);
}
?>
