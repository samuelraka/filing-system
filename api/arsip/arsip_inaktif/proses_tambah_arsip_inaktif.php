<?php
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . "/../../../error_log.txt");

include_once "../../../config/database.php";

$conn->begin_transaction();

try {
    // Ambil data dari form
    $id_subsub = $_POST['id_subsub'] ?? null;
    $nomor_berkas_input = $_POST['nomorBerkas'] ?? null;
    $buat_baru = isset($_POST['buatBerkasBaru']); // checkbox
    $nomor_item_input = $_POST['nomorItemArsip'] ?? null;

    $kategori = $_POST['kategoriArsip'] ?? null;
    $kurun_waktu = $_POST['kurunWaktu'] ?? null;
    $jangka_simpan = $_POST['jangkaSimpan'] ?? null;
    $nomor_boks = $_POST['nomorBoks'] ?? null;
    $lokasi_simpan = $_POST['lokasiSimpan'] ?? null;
    $tingkat_perkembangan = $_POST['tingkatPerkembangan'] ?? null;
    $uraian_informasi = $_POST['uraianInformasi'] ?? null;
    $uraian_singkat = $_POST['uraianSingkat'] ?? null;
    $keterangan = $_POST['keterangan'] ?? null;

    if (!$id_subsub || !$nomor_berkas_input || !$kategori || !$kurun_waktu || !$jangka_simpan || !$nomor_boks || !$lokasi_simpan || !$tingkat_perkembangan || !$uraian_informasi || !$uraian_singkat) {
        throw new Exception("Beberapa field wajib tidak diisi.");
    }

    // ======================
    // Langkah 1: Tentukan ID Arsip
    // ======================
    if ($buat_baru) {
        $query_insert_arsip = "INSERT INTO arsip_inaktif (id_subsub, nomor_berkas, jumlah_item) VALUES (?, ?, 0)";
        $stmt_insert = $conn->prepare($query_insert_arsip);
        $stmt_insert->bind_param("ii", $id_subsub, $nomor_berkas_input);
        $stmt_insert->execute();
        $id_arsip = $conn->insert_id;
        $nomor_berkas = $nomor_berkas_input;
    } else {
        $query_check = "SELECT id_arsip, nomor_berkas FROM arsip_inaktif WHERE id_subsub = ? ORDER BY id_arsip DESC LIMIT 1";
        $stmt_check = $conn->prepare($query_check);
        $stmt_check->bind_param("i", $id_subsub);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();

        if ($result_check->num_rows == 0) {
            $nomor_berkas = $nomor_berkas_input;
            $query_insert_arsip = "INSERT INTO arsip_inaktif (id_subsub, nomor_berkas, jumlah_item) VALUES (?, ?, 0)";
            $stmt_insert = $conn->prepare($query_insert_arsip);
            $stmt_insert->bind_param("ii", $id_subsub, $nomor_berkas);
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
    $query_last_item = "SELECT MAX(nomor_item) AS last_item FROM item_arsip_inaktif WHERE id_arsip = ?";
    $stmt_item = $conn->prepare($query_last_item);
    $stmt_item->bind_param("i", $id_arsip);
    $stmt_item->execute();
    $result_item = $stmt_item->get_result();
    $last_item = $result_item->fetch_assoc()['last_item'] ?? 0;
    $nomor_item = $buat_baru ? 1 : ($nomor_item_input ?? $last_item + 1);

    // ======================
    // Langkah 3: Upload File
    // ======================
    $file_path = null;
    if (!empty($_FILES['files']['name'][0])) {
        $upload_dir = "../../../uploads_inaktif/";
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
    // Langkah 4: Simpan ke item_arsip_inaktif
    // ======================
    $query_item = "INSERT INTO item_arsip_inaktif 
        (id_arsip, nomor_item, kategori_arsip, kurun_waktu, jangka_simpan, nomor_boks, lokasi_simpan, tingkat_perkembangan, uraian_singkat, uraian_informasi, keterangan, file_path)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt_item_insert = $conn->prepare($query_item);
    $stmt_item_insert->bind_param(
        "iissssssssss",
        $id_arsip,
        $nomor_item,
        $kategori,
        $kurun_waktu,
        $jangka_simpan,
        $nomor_boks,
        $lokasi_simpan,
        $tingkat_perkembangan,
        $uraian_singkat,
        $uraian_informasi,
        $keterangan,
        $file_path
    );

    if ($stmt_item_insert->execute()) {
        $conn->query("UPDATE arsip_inaktif SET jumlah_item = jumlah_item + 1 WHERE id_arsip = $id_arsip");
        $conn->commit();

        echo json_encode([
            "success" => true,
            "message" => "Arsip inaktif berhasil disimpan!",
            "id_arsip" => $id_arsip,
            "nomor_berkas" => $nomor_berkas,
            "nomor_item" => $nomor_item
        ]);
    } else {
        throw new Exception("Gagal menyimpan item arsip inaktif: " . $stmt_item_insert->error);
    }

} catch (Exception $e) {
    $conn->rollback();
    error_log("Error di tambah_arsip_inaktif.php: " . $e->getMessage());
    echo json_encode([
        "success" => false,
        "message" => "Terjadi kesalahan! Silakan cek log."
    ]);
}
?>
