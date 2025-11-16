<?php
include_once "../layouts/master/header.php";
include_once "../config/database.php";

// Ambil parameter id
$idParam = isset($_GET['id']) ? $_GET['id'] : null;

// Ambil data arsip vital dari DB
$item = null;
if ($idParam !== null && is_numeric($idParam)) {
    $id = (int)$idParam;

    $hasJenis = false; $hasUraian = false; $hasKurunTahun = false; $hasKurunWaktu = false; $hasFilePath = false;
    $r = $conn->query("SHOW COLUMNS FROM arsip_vital LIKE 'jenis_arsip'"); if ($r && $r->num_rows > 0) { $hasJenis = true; }
    $r = $conn->query("SHOW COLUMNS FROM arsip_vital LIKE 'uraian_arsip'"); if ($r && $r->num_rows > 0) { $hasUraian = true; }
    $r = $conn->query("SHOW COLUMNS FROM arsip_vital LIKE 'kurun_tahun'"); if ($r && $r->num_rows > 0) { $hasKurunTahun = true; }
    $r = $conn->query("SHOW COLUMNS FROM arsip_vital LIKE 'kurun_waktu'"); if ($r && $r->num_rows > 0) { $hasKurunWaktu = true; }
    $r = $conn->query("SHOW COLUMNS FROM arsip_vital LIKE 'file_path'"); if ($r && $r->num_rows > 0) { $hasFilePath = true; }

    $colJenis = $hasJenis ? 'jenis_arsip' : ($hasUraian ? 'uraian_arsip' : 'jenis_arsip');
    $colKurun = $hasKurunTahun ? 'kurun_tahun' : ($hasKurunWaktu ? 'kurun_waktu' : 'kurun_tahun');
    $selFile = $hasFilePath ? 'file_path' : 'NULL AS file_path';

    $sql = "SELECT id_arsip, $colJenis AS jenis_arsip, tingkat_perkembangan, $colKurun AS kurun_tahun, media, jumlah, jangka_simpan, lokasi_simpan, metode_perlindungan, keterangan, $selFile FROM arsip_vital WHERE id_arsip = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $item = $result->fetch_assoc();
        $stmt->close();
    }
}

$notFound = false;
if (!$item) {
    $notFound = true;
    $item = [
        'id_arsip' => '',
        'jenis_arsip' => '',
        'tingkat_perkembangan' => '',
        'kurun_tahun' => '',
        'media' => '',
        'jumlah' => '',
        'jangka_simpan' => '',
        'lokasi_simpan' => '',
        'metode_perlindungan' => '',
        'keterangan' => '',
    ];
}

$pdfUrl = isset($_GET['file']) ? $_GET['file'] : '';
?>

<div class="flex h-screen">
    <?php include_once "../layouts/components/sidebar_dynamic.php"; ?>

    <div class="flex-1 flex flex-col ml-64">
        <?php include_once "../layouts/components/topbar.php"; ?>

        <div class="p-6 mt-16 overflow-y-auto">
            <div class="flex justify-between items-center mb-8">
                <div class="flex items-center gap-3">
                    <h2 class="text-3xl font-medium text-slate-700">Edit Arsip Vital</h2>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm px-6 py-6 max-w-screen">
                <div class="flex justify-between items-center mb-8">
                    <a href="detail_vital.php?id=<?php echo htmlspecialchars($item['id_arsip']); ?>" class="flex items-center text-2xl border-b">
                        <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                        </svg>
                        Kembali
                    </a>
                </div>
                <?php if ($notFound): ?>
                    <div class="p-4 mb-6 rounded bg-yellow-50 text-yellow-700 border border-yellow-200">
                        Data arsip tidak ditemukan.
                    </div>
                <?php endif; ?>
                <form action="#" method="post" class="space-y-6" enctype="multipart/form-data" id="editForm">
                    <input type="hidden" name="id_arsip" value="<?php echo htmlspecialchars($item['id_arsip']); ?>">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- <div>
                            <label class="block text-sm font-medium text-gray-700">No</label>
                            <input type="text" value="<?php echo htmlspecialchars($item['id_arsip']); ?>" class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2" disabled>
                        </div> -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Uraian Arsip</label>
                            <input type="text" name="jenis_arsip" value="<?php echo htmlspecialchars($item['jenis_arsip']); ?>" class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tingkat Perkembangan</label>
                            <input type="text" name="tingkat_perkembangan" value="<?php echo htmlspecialchars($item['tingkat_perkembangan']); ?>" class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Kurun Tahun</label>
                            <input type="text" name="kurun_tahun" value="<?php echo htmlspecialchars($item['kurun_tahun']); ?>" class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Media</label>
                            <select name="media" class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2" required>
                                <option <?php echo $item['media']==='Kertas'? 'selected' : ''; ?>>Kertas</option>
                                <option <?php echo $item['media']==='Digital'? 'selected' : ''; ?>>Digital</option>
                                <option <?php echo $item['media']==='Microfilm'? 'selected' : ''; ?>>Microfilm</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Jumlah</label>
                            <input type="number" name="jumlah" value="<?php echo htmlspecialchars($item['jumlah']); ?>" class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Jangka Simpan</label>
                            <input type="text" name="jangka_simpan" value="<?php echo htmlspecialchars($item['jangka_simpan']); ?>" class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Lokasi Simpan</label>
                            <input type="text" name="lokasi_simpan" value="<?php echo htmlspecialchars($item['lokasi_simpan']); ?>" class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2" required>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Metode Perlindungan</label>
                            <input type="text" name="metode_perlindungan" value="<?php echo htmlspecialchars($item['metode_perlindungan']); ?>" class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Keterangan</label>
                            <textarea name="keterangan" rows="3" class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2"><?php echo htmlspecialchars($item['keterangan']); ?></textarea>
                        </div>
                    </div>

                    <!-- File Upload -->
                    <div>
                        <h3 class="text-sm font-medium text-gray-700 mb-2">File saat ini</h3>
                        <?php 
                        $existingFiles = [];
                        if (!empty($item['file_path'])) {
                            $tmp = json_decode($item['file_path'], true);
                            if (is_array($tmp)) { $existingFiles = $tmp; }
                        }
                        ?>
                        <?php if (!empty($existingFiles)) : ?>
                            <ul class="divide-y divide-gray-200 border rounded-md mb-4">
                                <?php foreach ($existingFiles as $fname): ?>
                                    <li class="flex items-center justify-between px-3 py-2">
                                        <div class="flex items-center gap-3">
                                            <span class="text-sm text-gray-700"><?php echo htmlspecialchars($fname); ?></span>
                                            <a href="../uploads/arsip_vital/<?php echo htmlspecialchars($fname); ?>" target="_blank" class="text-sm text-cyan-700 hover:underline">Lihat</a>
                                        </div>
                                        <a href="../api/arsip/arsip_vital/delete_file.php?id=<?php echo urlencode($item['id_arsip']); ?>&file=<?php echo urlencode($fname); ?>" class="border border-red-300 inline-flex bg-white hover:bg-red-50 text-red-600 rounded-md px-2 py-1 text-sm">Hapus</a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p class="text-sm text-gray-500 mb-4">Belum ada file terunggah.</p>
                        <?php endif; ?>

                        <label class="block text-sm font-medium text-gray-700 mb-1">Upload Dokumen PDF</label>
                        <div class="flex flex-col space-y-2">
                            <div class="flex items-center justify-center w-full">
                                <label for="fileUpload" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <svg class="w-8 h-8 mb-3 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                        </svg>
                                        <p class="mb-1 text-sm text-gray-500">Klik area ini untuk memulai upload file</p>
                                        <p class="text-xs text-gray-500">PDF (Maksimal 10 file)</p>
                                    </div>
                                    <input id="fileUpload" name="files[]" type="file" class="hidden" accept=".pdf" multiple />
                                </label>
                            </div>
                            <div id="fileList" class="mt-2 space-y-2"></div>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <button type="submit" class="bg-cyan-600 hover:bg-cyan-600/90 text-white px-4 py-2 rounded-md">Simpan Perubahan</button>
                        <a href="detail_vital.php?id=<?php echo htmlspecialchars($item['id_arsip']); ?>" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-md">Batal</a>
                    </div>
                </form>

                <!-- <div class="mt-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-3">File Arsip (Preview PDF)</h3>
                    <div class="border border-dashed border-gray-300 rounded-md p-4 bg-gray-50">
                        <?php if (!empty($pdfUrl)) : ?>
                            <iframe src="<?php echo htmlspecialchars($pdfUrl); ?>" class="w-full h-[500px]" title="Preview PDF"></iframe>
                            <p class="text-sm text-gray-500 mt-2">Pratinjau dokumen PDF.</p>
                        <?php else: ?>
                            <div class="flex items-center justify-center h-64 text-gray-400">Preview PDF akan ditampilkan di sini.</div>
                        <?php endif; ?>
                    </div>
                </div> -->
            </div>
        </div>
    </div>
</div>

<!-- jQuery (harus sebelum script AJAX) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- SweetAlert (opsional tapi direkomendasikan) -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('fileUpload');
    const fileList = document.getElementById('fileList');

    fileInput.addEventListener('change', function() {
        fileList.innerHTML = '';
        const files = Array.from(this.files);
        files.forEach((file, index) => {
            const div = document.createElement('div');
            div.classList.add('flex', 'items-center', 'justify-between', 'p-2', 'border', 'rounded', 'bg-gray-50');
            div.innerHTML = `
                <span class="text-sm text-gray-700">${index + 1}. ${file.name}</span>
                <span class="text-xs text-gray-500">${(file.size / 1024).toFixed(1)} KB</span>
            `;
            fileList.appendChild(div);
        });
    });
});

$(document).ready(function() {
    $("#editForm").on("submit", function(e) {
        e.preventDefault();

        const formData = new FormData(this);

        $.ajax({
            url: "../api/arsip/arsip_vital/edit_arsip_vital.php",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function(res) {
                if (res.success) {
                    Swal.fire({
                        icon: "success",
                        title: "Berhasil!",
                        text: res.message,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = "detail_vital.php?id=" + formData.get('id_arsip');
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Gagal!",
                        text: res.message
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Terjadi kesalahan saat mengirim data!"
                });
            }
        });
    });
});
</script>

<?php include_once "../layouts/master/footer.php"; ?>