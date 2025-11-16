<?php
include_once "../layouts/master/header.php";
include_once "../config/database.php";

// Ambil parameter id dari query string
$idParam = isset($_GET['id']) ? $_GET['id'] : null;

// Ambil data dari database
$item = null;
if ($idParam !== null && is_numeric($idParam)) {
    $id = (int)$idParam;
    $stmt = $conn->prepare("SELECT id_arsip, jenis_arsip, tingkat_perkembangan, kurun_tahun, media, jumlah, jangka_simpan, lokasi_simpan, metode_perlindungan, keterangan, file_path FROM arsip_vital WHERE id_arsip = ?");
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
                    <h2 class="text-3xl font-medium text-slate-700">Detail Arsip Vital</h2>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm px-6 py-6 max-w-screen">
                <div class="flex justify-between items-center mb-8">
                    <a href="vital.php" class="flex items-center text-2xl border-b">
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
                <form action="#" method="post" class="space-y-6" id="detailForm">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- <div>
                            <label class="block text-sm font-medium text-gray-700">No</label>
                            <input type="text" value="<?php echo htmlspecialchars($item['id_arsip']); ?>" disabled class="mt-1 w-full border border-gray-200 bg-gray-50 rounded-md px-3 py-2">
                        </div> -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Jenis Arsip</label>
                            <input type="text" value="<?php echo htmlspecialchars($item['jenis_arsip']); ?>" disabled class="mt-1 w-full border border-gray-200 bg-gray-50 rounded-md px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tingkat Perkembangan</label>
                            <input type="text" value="<?php echo htmlspecialchars($item['tingkat_perkembangan']); ?>" disabled class="mt-1 w-full border border-gray-200 bg-gray-50 rounded-md px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Kurun Tahun</label>
                            <input type="text" value="<?php echo htmlspecialchars($item['kurun_tahun']); ?>" disabled class="mt-1 w-full border border-gray-200 bg-gray-50 rounded-md px-3 py-2">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Media</label>
                            <select disabled class="mt-1 w-full border border-gray-200 bg-gray-50 rounded-md px-3 py-2">
                                <option <?php echo $item['media']==='Kertas'? 'selected' : ''; ?>>Kertas</option>
                                <option <?php echo $item['media']==='Digital'? 'selected' : ''; ?>>Digital</option>
                                <option <?php echo $item['media']==='Microfilm'? 'selected' : ''; ?>>Microfilm</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Jumlah</label>
                            <input type="number" value="<?php echo htmlspecialchars($item['jumlah']); ?>" disabled class="mt-1 w-full border border-gray-200 bg-gray-50 rounded-md px-3 py-2">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Jangka Simpan</label>
                            <input type="text" value="<?php echo htmlspecialchars($item['jangka_simpan']); ?>" disabled class="mt-1 w-full border border-gray-200 bg-gray-50 rounded-md px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Lokasi Simpan</label>
                            <input type="text" value="<?php echo htmlspecialchars($item['lokasi_simpan']); ?>" disabled class="mt-1 w-full border border-gray-200 bg-gray-50 rounded-md px-3 py-2">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Metode Perlindungan</label>
                            <input type="text" value="<?php echo htmlspecialchars($item['metode_perlindungan']); ?>" disabled class="mt-1 w-full border border-gray-200 bg-gray-50 rounded-md px-3 py-2">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Keterangan</label>
                            <textarea rows="3" disabled class="mt-1 w-full border border-gray-200 bg-gray-50 rounded-md px-3 py-2"><?php echo htmlspecialchars($item['keterangan']); ?></textarea>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <a href="edit_vital.php?id=<?php echo htmlspecialchars($item['id_arsip']); ?>" class="bg-slate-700 hover:bg-slate-700/90 text-white px-4 py-2 rounded-md inline-flex items-center">Edit</a>
                    </div>
                </form>

                <!-- File Arsip Section -->
                <div class="mt-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-3">File Arsip (Preview PDF)</h3>
                    <?php 
                    $files = [];
                    if (!empty($item['file_path'])) {
                        $files = json_decode($item['file_path'], true);
                        if (!is_array($files)) {
                            $files = [];
                        }
                    }
                    ?>
                    <?php if (!empty($files) && count($files) > 0) : ?>
                        <div class="space-y-4">
                            <!-- File List -->
                            <div class="border border-gray-300 rounded-md p-4 bg-gray-50">
                                <h4 class="text-sm font-medium text-gray-700 mb-3">Daftar File (<?php echo count($files); ?> file)</h4>
                                <div class="space-y-2">
                                    <?php foreach ($files as $index => $file) : ?>
                                        <div class="flex items-center justify-between p-3 bg-white border border-gray-200 rounded-md hover:bg-gray-50">
                                            <div class="flex items-center gap-2">
                                                <svg class="w-5 h-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                                    <path d="M7 3H4a1 1 0 00-1 1v16a1 1 0 001 1h16a1 1 0 001-1V8.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0014.586 2H8a1 1 0 00-1 1v1H7V3zm0 2v1h10V5H7zm10 14H7v-2h10v2z"/>
                                                </svg>
                                                <span class="text-sm text-gray-700"><?php echo htmlspecialchars($file); ?></span>
                                            </div>
                                            <a href="../api/arsip/arsip_vital/download_file.php?file=<?php echo urlencode($file); ?>&id=<?php echo $id; ?>" class="bg-cyan-600 hover:bg-cyan-600/90 text-white px-3 py-1 rounded text-sm inline-flex items-center gap-1">
                                                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                                    <polyline points="7 10 12 15 17 10"></polyline>
                                                    <line x1="12" y1="15" x2="12" y2="3"></line>
                                                </svg>
                                                Download
                                            </a>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <!-- PDF Preview -->
                            <div class="border border-gray-300 rounded-md p-4 bg-gray-50">
                                <h4 class="text-sm font-medium text-gray-700 mb-3">Preview PDF</h4>
                                <div class="bg-white rounded-md overflow-hidden">
                                    <iframe src="../uploads/arsip_vital/<?php echo htmlspecialchars($files[0]); ?>" class="w-full h-[600px]" title="Preview PDF"></iframe>
                                </div>
                                <p class="text-xs text-gray-500 mt-2">Menampilkan file pertama. Gunakan tombol download untuk mengakses file lainnya.</p>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="border border-dashed border-gray-300 rounded-md p-4 bg-gray-50">
                            <div class="flex items-center justify-center h-64 text-gray-400">
                                <div class="text-center">
                                    <svg class="w-12 h-12 mx-auto mb-2 text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <p>Belum ada file yang diunggah</p>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>



<?php include_once "../layouts/master/footer.php"; ?>