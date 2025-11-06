<?php
include_once "../layouts/master/header.php";

$id = isset($_GET['id']) ? intval($_GET['id']) : 1;
$data = [
    1 => ['no' => '1','kode' => '001.1','jenis' => 'Surat Keputusan','tahun' => '2020','jumlah' => '3','perkembangan' => 'Asli','keterangan' => 'Arsip statis penting'],
    2 => ['no' => '2','kode' => '101.2','jenis' => 'Laporan Tahunan','tahun' => '2022','jumlah' => '5','perkembangan' => 'Salinan','keterangan' => 'Untuk referensi'],
    3 => ['no' => '3','kode' => '201.4','jenis' => 'Notulen Rapat','tahun' => '2019','jumlah' => '2','perkembangan' => 'Asli','keterangan' => 'Dokumen terdigitalisasi'],
    4 => ['no' => '4','kode' => '305.6','jenis' => 'Buku Agenda','tahun' => '2015','jumlah' => '7','perkembangan' => 'Lengkap','keterangan' => 'Disimpan permanen'],
    5 => ['no' => '5','kode' => '410.2','jenis' => 'Dokumen Proyek Final','tahun' => '2021','jumlah' => '4','perkembangan' => 'Asli','keterangan' => 'Arsip statis untuk audit'],
];
$item = $data[$id] ?? $data[1];
$pdfUrl = isset($_GET['file']) ? $_GET['file'] : '';
?>

<div class="flex h-screen">
    <?php include_once "../layouts/components/sidebar_dynamic.php"; ?>

    <div class="flex-1 flex flex-col ml-64">
        <?php include_once "../layouts/components/topbar.php"; ?>

        <div class="p-6 mt-16 overflow-y-auto">
            <div class="flex justify-between items-center mb-8">
                <div class="flex items-center gap-3">
                    <h2 class="text-3xl font-medium text-slate-700">Edit Arsip Statis</h2>
                    <span class="text-sm text-gray-500">#<?php echo htmlspecialchars($item['no']); ?></span>
                </div>
                <div class="flex items-center gap-3">
                    <a href="detail_statis.php?id=<?php echo $id; ?>" class="text-sm text-cyan-700 hover:underline">Kembali ke Detail</a>
                    <a href="statis.php" class="text-sm text-slate-700 hover:underline">Kembali ke Arsip Statis</a>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm px-6 py-6 max-w-screen">
                <form action="#" method="post" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">No</label>
                            <input type="text" value="<?php echo htmlspecialchars($item['no']); ?>" class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Kode Klasifikasi Arsip</label>
                            <input type="text" value="<?php echo htmlspecialchars($item['kode']); ?>" class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Jenis/Series Arsip</label>
                            <input type="text" value="<?php echo htmlspecialchars($item['jenis']); ?>" class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tahun</label>
                            <input type="number" value="<?php echo htmlspecialchars($item['tahun']); ?>" class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Jumlah</label>
                            <input type="number" value="<?php echo htmlspecialchars($item['jumlah']); ?>" class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tingkat Perkembangan</label>
                            <select class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2">
                                <option <?php echo $item['perkembangan']==='Asli'?'selected':''; ?>>Asli</option>
                                <option <?php echo $item['perkembangan']==='Salinan'?'selected':''; ?>>Salinan</option>
                                <option <?php echo $item['perkembangan']==='Lengkap'?'selected':''; ?>>Lengkap</option>
                            </select>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Keterangan</label>
                            <textarea rows="3" class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2"><?php echo htmlspecialchars($item['keterangan']); ?></textarea>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <button type="submit" class="bg-cyan-600 hover:bg-cyan-600/90 text-white px-4 py-2 rounded-md">Simpan Perubahan</button>
                        <a href="detail_statis.php?id=<?php echo $id; ?>" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-md">Batal</a>
                    </div>
                </form>

                <div class="mt-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-3">File Arsip (Preview PDF)</h3>
                    <div class="border border-dashed border-gray-300 rounded-md p-4 bg-gray-50">
                        <?php if (!empty($pdfUrl)) : ?>
                            <iframe src="<?php echo htmlspecialchars($pdfUrl); ?>" class="w-full h-[500px]" title="Preview PDF"></iframe>
                            <p class="text-sm text-gray-500 mt-2">Pratinjau dokumen PDF.</p>
                        <?php else: ?>
                            <div class="flex items-center justify-center h-64 text-gray-400">Preview PDF akan ditampilkan di sini.</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once "../layouts/master/footer.php"; ?>