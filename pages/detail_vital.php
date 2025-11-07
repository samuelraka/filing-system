<?php
include_once "../layouts/master/header.php";
include_once "../config/database.php";

// Ambil parameter id dari query string
$idParam = isset($_GET['id']) ? $_GET['id'] : null;

// Ambil data dari database
$item = null;
if ($idParam !== null && is_numeric($idParam)) {
    $id = (int)$idParam;
    $stmt = $conn->prepare("SELECT id_arsip, jenis_arsip, tingkat_perkembangan, kurun_tahun, media, jumlah, jangka_simpan, lokasi_simpan, metode_perlindungan, keterangan FROM arsip_vital WHERE id_arsip = ?");
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



<?php include_once "../layouts/master/footer.php"; ?>