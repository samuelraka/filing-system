<?php
include_once "../layouts/master/header.php";

$id = isset($_GET['id']) ? $_GET['id'] : 'IT-1001';
$data = [
    'IT-1001' => ['berkas' => 'A-001','item' => 'IT-1001','kode' => '101.2','uraian' => 'Surat Keputusan Direktur','tanggal' => '2023-01-10','jumlah' => '5','skaad' => 'Biasa','keterangan' => 'Arsip penting'],
    'IT-1002' => ['berkas' => 'A-001','item' => 'IT-1002','kode' => '101.3','uraian' => 'Laporan Tahunan','tanggal' => '2022-12-05','jumlah' => '3','skaad' => 'Terbatas','keterangan' => 'Perlu verifikasi'],
    'IT-1003' => ['berkas' => 'A-001','item' => 'IT-1003','kode' => '101.4','uraian' => 'Dokumen Kontrak','tanggal' => '2021-07-21','jumlah' => '2','skaad' => 'Biasa','keterangan' => 'Sudah diverifikasi'],
    'IT-1004' => ['berkas' => 'A-002','item' => 'IT-1004','kode' => '102.1','uraian' => 'Berita Acara','tanggal' => '2024-02-15','jumlah' => '4','skaad' => 'Biasa','keterangan' => '-'],
    'IT-1005' => ['berkas' => 'A-002','item' => 'IT-1005','kode' => '102.2','uraian' => 'Memo Internal','tanggal' => '2023-11-30','jumlah' => '1','skaad' => 'Rahasia','keterangan' => 'Butuh lampiran'],
    'IT-1006' => ['berkas' => 'A-003','item' => 'IT-1006','kode' => '103.1','uraian' => 'Surat Perjanjian','tanggal' => '2023-09-18','jumlah' => '7','skaad' => 'Biasa','keterangan' => 'Sudah disetujui'],
];
$item = $data[$id] ?? $data['IT-1001'];
$pdfUrl = isset($_GET['file']) ? $_GET['file'] : '';
?>

<div class="flex h-screen">
    <?php include_once "../layouts/components/sidebar_dynamic.php"; ?>

    <div class="flex-1 flex flex-col ml-64">
        <?php include_once "../layouts/components/topbar.php"; ?>

        <div class="p-6 mt-16 overflow-y-auto">
            <div class="flex justify-between items-center mb-8">
                <div class="flex items-center gap-3">
                    <h2 class="text-3xl font-medium text-slate-700">Edit Arsip Aktif</h2>
                    <span class="text-sm text-gray-500">#<?php echo htmlspecialchars($item['item']); ?></span>
                </div>
                <div class="flex items-center gap-3">
                    <a href="detail_aktif.php?id=<?php echo urlencode($id); ?>" class="text-sm text-cyan-700 hover:underline">Kembali ke Detail</a>
                    <a href="aktif.php" class="text-sm text-slate-700 hover:underline">Kembali ke Arsip Aktif</a>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm px-6 py-6 max-w-screen">
                <form action="#" method="post" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nomor Berkas</label>
                            <input type="text" value="<?php echo htmlspecialchars($item['berkas']); ?>" class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nomor Item Arsip</label>
                            <input type="text" value="<?php echo htmlspecialchars($item['item']); ?>" class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Kode Klasifikasi Arsip</label>
                            <input type="text" value="<?php echo htmlspecialchars($item['kode']); ?>" class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Uraian Informasi Arsip</label>
                            <input type="text" value="<?php echo htmlspecialchars($item['uraian']); ?>" class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tanggal</label>
                            <input type="date" value="<?php echo htmlspecialchars($item['tanggal']); ?>" class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Jumlah Item Arsip</label>
                            <input type="number" value="<?php echo htmlspecialchars($item['jumlah']); ?>" class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Keterangan SKAAD</label>
                            <select class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2">
                                <option <?php echo $item['skaad']==='Biasa'?'selected':''; ?>>Biasa</option>
                                <option <?php echo $item['skaad']==='Terbatas'?'selected':''; ?>>Terbatas</option>
                                <option <?php echo $item['skaad']==='Rahasia'?'selected':''; ?>>Rahasia</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Keterangan</label>
                            <textarea rows="3" class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2"><?php echo htmlspecialchars($item['keterangan']); ?></textarea>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <button type="submit" class="bg-cyan-600 hover:bg-cyan-600/90 text-white px-4 py-2 rounded-md">Simpan Perubahan</button>
                        <a href="detail_aktif.php?id=<?php echo urlencode($id); ?>" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-md">Batal</a>
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