<?php
include_once "../layouts/master/header.php";
include_once "../config/database.php";

$id = isset($_GET['id']) ? $_GET['id'] : '';
$pdfUrl = isset($_GET['file']) ? $_GET['file'] : '';

// Default item placeholder
$item = [
    'berkas' => '',
    'item' => htmlspecialchars($id ?: ''),
    'kode' => '',
    'uraian' => '',
    'kurun' => '',
    'perkembangan' => '',
    'jumlah' => '',
    'keterangan' => '',
    'definitif' => '',
    'lokasi' => '',
    'jangka' => '',
    'kategori' => ''
];
$notFound = false;

// Fetch from DB if ID provided (use item_arsip_inaktif.id_item)
if (!empty($id)) {
    try {
        $id_int = intval($id);
        if ($id_int > 0) {
            $stmt = $conn->prepare("SELECT 
                    ai.nomor_berkas,
                    ai.jumlah_item,
                    ia.nomor_item,
                    ssm.kode_subsub AS kode_klasifikasi,
                    ia.uraian_informasi,
                    ia.kurun_waktu,
                    ia.tingkat_perkembangan,
                    ia.keterangan,
                    ia.nomor_boks,
                    ia.lokasi_simpan,
                    ia.jangka_simpan,
                    ia.kategori_arsip
                FROM item_arsip_inaktif ia
                LEFT JOIN arsip_inaktif ai ON ia.id_arsip = ai.id_arsip
                LEFT JOIN sub_sub_masalah ssm ON ai.id_subsub = ssm.id_subsub
                WHERE ia.id_item = ?");
            $stmt->bind_param("i", $id_int);
            $stmt->execute();
            $res = $stmt->get_result();
            if ($row = $res->fetch_assoc()) {
                $item = [
                    'berkas' => $row['nomor_berkas'] ?? '',
                    'item' => $row['nomor_item'] ?? (string)$id_int,
                    'kode' => $row['kode_klasifikasi'] ?? '',
                    'uraian' => $row['uraian_informasi'] ?? '',
                    'kurun' => $row['kurun_waktu'] ?? '',
                    'perkembangan' => $row['tingkat_perkembangan'] ?? '',
                    'jumlah' => $row['jumlah_item'] ?? '',
                    'keterangan' => $row['keterangan'] ?? '',
                    'definitif' => $row['nomor_boks'] ?? '',
                    'lokasi' => $row['lokasi_simpan'] ?? '',
                    'jangka' => $row['jangka_simpan'] ?? '',
                    'kategori' => $row['kategori_arsip'] ?? ''
                ];
            } else {
                $notFound = true;
            }
            $stmt->close();
        } else {
            $notFound = true;
        }
    } catch (Exception $e) {
        $notFound = true;
    }
}
?>

<div class="flex h-screen overflow-x-auto">
    <?php include_once "../layouts/components/sidebar_dynamic.php"; ?>

    <div class="flex-1 flex flex-col ml-64">
        <?php include_once "../layouts/components/topbar.php"; ?>

        <div class="p-6 mt-16 overflow-y-auto max-w-[calc(100vw-16rem)]">
            <div class="flex justify-between items-center mb-8">
                <div class="flex items-center gap-3">
                    <h2 class="text-3xl font-medium text-gray-900">Detail Arsip Inaktif</h2>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm px-6 py-6 max-w-[calc(100vw-16rem)]">
                <div class="flex justify-between items-center mb-8">
                    <a href="inaktif.php" class="flex items-center text-2xl border-b">
                        <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                        </svg>
                        Kembali
                    </a>
                </div>
                <?php if ($notFound): ?>
                    <div class="p-4 mb-4 text-red-700 bg-red-50 border border-red-200 rounded">Data arsip tidak ditemukan untuk ID yang diberikan.</div>
                <?php endif; ?>
                <form action="#" method="post" class="space-y-6" id="detailForm">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nomor Berkas</label>
                            <input type="text" value="<?php echo htmlspecialchars($item['berkas']); ?>" disabled class="mt-1 w-full border border-gray-200 bg-gray-50 rounded-md px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nomor Item Arsip</label>
                            <input type="text" value="<?php echo htmlspecialchars($item['item']); ?>" disabled class="mt-1 w-full border border-gray-200 bg-gray-50 rounded-md px-3 py-2">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Kode Klasifikasi Arsip</label>
                            <input type="text" value="<?php echo htmlspecialchars($item['kode']); ?>" disabled class="mt-1 w-full border border-gray-200 bg-gray-50 rounded-md px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Uraian Informasi Arsip</label>
                            <input type="text" value="<?php echo htmlspecialchars($item['uraian']); ?>" disabled class="mt-1 w-full border border-gray-200 bg-gray-50 rounded-md px-3 py-2">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Kurun Waktu</label>
                            <input type="text" value="<?php echo htmlspecialchars($item['kurun']); ?>" disabled class="mt-1 w-full border border-gray-200 bg-gray-50 rounded-md px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tingkat Perkembangan</label>
                            <select disabled class="mt-1 w-full border border-gray-200 bg-gray-50 rounded-md px-3 py-2">
                                <option <?php echo $item['perkembangan']==='Asli'?'selected':''; ?>>Asli</option>
                                <option <?php echo $item['perkembangan']==='Copy'?'selected':''; ?>>Copy</option>
                                <option <?php echo $item['perkembangan']==='Permanen'?'selected':''; ?>>Permanen</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Jumlah Item Arsip</label>
                            <input type="number" value="<?php echo htmlspecialchars($item['jumlah']); ?>" disabled class="mt-1 w-full border border-gray-200 bg-gray-50 rounded-md px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Keterangan</label>
                            <textarea rows="3" disabled class="mt-1 w-full border border-gray-200 bg-gray-50 rounded-md px-3 py-2"><?php echo htmlspecialchars($item['keterangan']); ?></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nomor Definitif Folder dan Boks</label>
                            <input type="text" value="<?php echo htmlspecialchars($item['definitif']); ?>" disabled class="mt-1 w-full border border-gray-200 bg-gray-50 rounded-md px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Lokasi Simpan</label>
                            <input type="text" value="<?php echo htmlspecialchars($item['lokasi']); ?>" disabled class="mt-1 w-full border border-gray-200 bg-gray-50 rounded-md px-3 py-2">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Jangka Simpan dan Nasib Akhir</label>
                            <input type="text" value="<?php echo htmlspecialchars($item['jangka']); ?>" disabled class="mt-1 w-full border border-gray-200 bg-gray-50 rounded-md px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Kategori Arsip</label>
                            <input type="text" value="<?php echo htmlspecialchars($item['kategori']); ?>" disabled class="mt-1 w-full border border-gray-200 bg-gray-50 rounded-md px-3 py-2">
                        </div>
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
                <a href="edit_inaktif.php?id=<?php echo urlencode($id); ?>" class="bg-slate-700 hover:bg-slate-700/90 text-white px-4 py-2 mt-4 rounded-md inline-flex items-center">Edit</a>
            </div>
        </div>
    </div>
</div>



<?php include_once "../layouts/master/footer.php"; ?>