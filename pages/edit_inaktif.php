<?php
include_once "../layouts/master/header.php";

$id = isset($_GET['id']) ? $_GET['id'] : 'INA-1001';
$data = [
    'INA-1001' => ['berkas' => 'IN-001','item' => 'INA-1001','kode' => '201.1','uraian' => 'Nota Dinas Internal','kurun' => '2021-2023','perkembangan' => 'Asli','jumlah' => '2','keterangan' => 'Sudah dipindahkan ke gudang','definitif' => 'F-01/B-01','lokasi' => 'Gudang Arsip Rak A-1','jangka' => '10 tahun - Musnah','kategori' => 'Keuangan'],
    'INA-1002' => ['berkas' => 'IN-001','item' => 'INA-1002','kode' => '201.2','uraian' => 'Laporan Keuangan Triwulan','kurun' => '2022-2023','perkembangan' => 'Copy','jumlah' => '4','keterangan' => 'Perlu pengecekan kelengkapan','definitif' => 'F-01/B-02','lokasi' => 'Gudang Arsip Rak A-1','jangka' => '5 tahun - Permanen','kategori' => 'Keuangan'],
    'INA-1003' => ['berkas' => 'IN-001','item' => 'INA-1003','kode' => '201.3','uraian' => 'Dokumen Pajak Tahunan','kurun' => '2019-2021','perkembangan' => 'Asli','jumlah' => '3','keterangan' => 'Lengkap dan tersusun rapi','definitif' => 'F-01/B-03','lokasi' => 'Gudang Arsip Rak A-1','jangka' => '15 tahun - Musnah','kategori' => 'Pajak'],
    'INA-1004' => ['berkas' => 'IN-002','item' => 'INA-1004','kode' => '202.1','uraian' => 'Laporan Proyek Selesai','kurun' => '2020-2022','perkembangan' => 'Copy','jumlah' => '4','keterangan' => 'Perlu pengecekan kelengkapan','definitif' => 'F-02/B-01','lokasi' => 'Gudang Arsip Rak A-2','jangka' => '5 tahun - Permanen','kategori' => 'Proyek'],
    'INA-1005' => ['berkas' => 'IN-002','item' => 'INA-1005','kode' => '202.2','uraian' => 'Dokumen Kontrak Proyek','kurun' => '2019-2021','perkembangan' => 'Asli','jumlah' => '6','keterangan' => 'Kontrak sudah selesai','definitif' => 'F-02/B-02','lokasi' => 'Gudang Arsip Rak A-2','jangka' => '10 tahun - Musnah','kategori' => 'Proyek'],
    'INA-1006' => ['berkas' => 'IN-003','item' => 'INA-1006','kode' => '203.1','uraian' => 'Surat Masuk Eksternal','kurun' => '2018-2020','perkembangan' => 'Copy','jumlah' => '1','keterangan' => 'Arsip lama yang sudah digitalisasi','definitif' => 'F-03/B-01','lokasi' => 'Gudang Arsip Rak B-1','jangka' => 'Permanen','kategori' => 'Surat Menyurat'],
];
$item = $data[$id] ?? $data['INA-1001'];
$pdfUrl = isset($_GET['file']) ? $_GET['file'] : '';
?>

<div class="flex h-screen overflow-x-auto">
    <?php include_once "../layouts/components/sidebar_dynamic.php"; ?>

    <div class="flex-1 flex flex-col ml-64">
        <?php include_once "../layouts/components/topbar.php"; ?>

        <div class="p-6 mt-16 overflow-y-auto max-w-[calc(100vw-16rem)]">
            <div class="flex justify-between items-center mb-8">
                <div class="flex items-center gap-3">
                    <h2 class="text-3xl font-medium text-gray-900">Edit Arsip Inaktif</h2>
                    <span class="text-sm text-gray-500">#<?php echo htmlspecialchars($item['item']); ?></span>
                </div>
                <div class="flex items-center gap-3">
                    <a href="detail_inaktif.php?id=<?php echo urlencode($id); ?>" class="text-sm text-cyan-700 hover:underline">Kembali ke Detail</a>
                    <a href="inaktif.php" class="text-sm text-slate-700 hover:underline">Kembali ke Arsip Inaktif</a>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm px-6 py-6 max-w-[calc(100vw-16rem)]">
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
                            <label class="block text-sm font-medium text-gray-700">Kurun Waktu</label>
                            <input type="text" value="<?php echo htmlspecialchars($item['kurun']); ?>" class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tingkat Perkembangan</label>
                            <select class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2">
                                <option <?php echo $item['perkembangan']==='Asli'?'selected':''; ?>>Asli</option>
                                <option <?php echo $item['perkembangan']==='Copy'?'selected':''; ?>>Copy</option>
                                <option <?php echo $item['perkembangan']==='Permanen'?'selected':''; ?>>Permanen</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Jumlah Item Arsip</label>
                            <input type="number" value="<?php echo htmlspecialchars($item['jumlah']); ?>" class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Keterangan</label>
                            <textarea rows="3" class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2"><?php echo htmlspecialchars($item['keterangan']); ?></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nomor Definitif Folder dan Boks</label>
                            <input type="text" value="<?php echo htmlspecialchars($item['definitif']); ?>" class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Lokasi Simpan</label>
                            <input type="text" value="<?php echo htmlspecialchars($item['lokasi']); ?>" class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Jangka Simpan dan Nasib Akhir</label>
                            <input type="text" value="<?php echo htmlspecialchars($item['jangka']); ?>" class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Kategori Arsip</label>
                            <input type="text" value="<?php echo htmlspecialchars($item['kategori']); ?>" class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2">
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <button type="submit" class="bg-cyan-600 hover:bg-cyan-600/90 text-white px-4 py-2 rounded-md">Simpan Perubahan</button>
                        <a href="detail_inaktif.php?id=<?php echo urlencode($id); ?>" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-md">Batal</a>
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