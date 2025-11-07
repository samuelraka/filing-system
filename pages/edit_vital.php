<?php
include_once "../layouts/master/header.php";
include_once "../config/database.php";

// Ambil parameter id
$idParam = isset($_GET['id']) ? $_GET['id'] : null;

// Ambil data arsip vital dari DB
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
                    <h2 class="text-3xl font-medium text-slate-700">Edit Arsip Vital</h2>
                </div>
                <div class="flex items-center gap-3">
                    <a href="detail_vital.php?id=<?php echo htmlspecialchars($item['id_arsip']); ?>" class="text-sm text-cyan-700 hover:underline">Kembali ke Detail</a>
                    <a href="vital.php" class="text-sm text-slate-700 hover:underline">Kembali ke Arsip Vital</a>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm px-6 py-6 max-w-screen">
                <?php if ($notFound): ?>
                    <div class="p-4 mb-6 rounded bg-yellow-50 text-yellow-700 border border-yellow-200">
                        Data arsip tidak ditemukan.
                    </div>
                <?php endif; ?>
                <form action="#" method="post" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- <div>
                            <label class="block text-sm font-medium text-gray-700">No</label>
                            <input type="text" value="<?php echo htmlspecialchars($item['id_arsip']); ?>" class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2" disabled>
                        </div> -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Uraian Arsip</label>
                            <input type="text" value="<?php echo htmlspecialchars($item['jenis_arsip']); ?>" class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tingkat Perkembangan</label>
                            <input type="text" value="<?php echo htmlspecialchars($item['tingkat_perkembangan']); ?>" class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Unit Kerja</label>
                            <input type="text" value="<?php echo htmlspecialchars($item['unit_kerja']); ?>" class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Kurun Tahun</label>
                            <input type="text" value="<?php echo htmlspecialchars($item['kurun_tahun']); ?>" class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Media</label>
                            <select class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2">
                                <option <?php echo $item['media']==='Kertas'? 'selected' : ''; ?>>Kertas</option>
                                <option <?php echo $item['media']==='Digital'? 'selected' : ''; ?>>Digital</option>
                                <option <?php echo $item['media']==='Microfilm'? 'selected' : ''; ?>>Microfilm</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Jumlah</label>
                            <input type="number" value="<?php echo htmlspecialchars($item['jumlah']); ?>" class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Jangka Simpan</label>
                            <input type="text" value="<?php echo htmlspecialchars($item['jangka_simpan']); ?>" class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Lokasi Simpan</label>
                            <input type="text" value="<?php echo htmlspecialchars($item['lokasi_simpan']); ?>" class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Metode Perlindungan</label>
                            <input type="text" value="<?php echo htmlspecialchars($item['metode_perlindungan']); ?>" class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Keterangan</label>
                            <textarea rows="3" class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2"><?php echo htmlspecialchars($item['keterangan']); ?></textarea>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <button type="submit" class="bg-cyan-600 hover:bg-cyan-600/90 text-white px-4 py-2 rounded-md">Simpan Perubahan</button>
                        <a href="detail_vital.php?id=<?php echo $id; ?>" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-md">Batal</a>
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