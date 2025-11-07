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
    'tanggal' => '',
    'jumlah' => '',
    'skaad' => '',
    'keterangan' => ''
];
$notFound = false;

// Fetch from DB if ID provided (use item_arsip.id_item)
if (!empty($id)) {
    try {
        $id_int = intval($id);
        if ($id_int > 0) {
            $stmt = $conn->prepare("SELECT 
                    aa.nomor_berkas,
                    ia.nomor_item,
                    ssm.kode_subsub AS kode_klasifikasi,
                    ia.uraian_informasi,
                    ia.tanggal,
                    aa.jumlah_item,
                    ia.keterangan_skaad,
                    aa.keterangan AS keterangan_berkas,
                    ia.file_path
                FROM item_arsip ia
                LEFT JOIN arsip_aktif aa ON ia.id_arsip = aa.id_arsip
                LEFT JOIN sub_sub_masalah ssm ON aa.id_subsub = ssm.id_subsub
                WHERE ia.id_item = ?");
            $stmt->bind_param("i", $id_int);
            $stmt->execute();
            $res = $stmt->get_result();
            if ($row = $res->fetch_assoc()) {
                // If no explicit file param provided, use stored file_path when available
                if (empty($pdfUrl) && !empty($row['file_path'])) {
                    $pdfUrl = $row['file_path'];
                }
                $item = [
                    'berkas' => $row['nomor_berkas'] ?? '',
                    'item' => $row['nomor_item'] ?? (string)$id_int,
                    'kode' => $row['kode_klasifikasi'] ?? '',
                    // Show full description on detail page
                    'uraian' => $row['uraian_informasi'] ?? '',
                    'tanggal' => $row['tanggal'] ?? '',
                    'jumlah' => $row['jumlah_item'] ?? '',
                    'skaad' => $row['keterangan_skaad'] ?? '',
                    'keterangan' => $row['keterangan_berkas'] ?? ''
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

<div class="flex h-screen">
    <?php include_once "../layouts/components/sidebar_dynamic.php"; ?>

    <div class="flex-1 flex flex-col ml-64">
        <?php include_once "../layouts/components/topbar.php"; ?>

        <div class="p-6 mt-16 overflow-y-auto">
            <div class="flex justify-between items-center mb-8">
                <div class="flex items-center gap-3">
                    <h2 class="text-3xl font-medium text-slate-700">Detail Berkas</h2>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm px-6 py-6 max-w-screen">
                <div class="flex justify-between items-center mb-8">
                    <a href="aktif.php" class="flex items-center text-2xl border-b">
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
                            <label class="block text-sm font-medium text-gray-700">Tanggal</label>
                            <input type="date" value="<?php echo htmlspecialchars($item['tanggal']); ?>" disabled class="mt-1 w-full border border-gray-200 bg-gray-50 rounded-md px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Jumlah Item Arsip</label>
                            <input type="number" value="<?php echo htmlspecialchars($item['jumlah']); ?>" disabled class="mt-1 w-full border border-gray-200 bg-gray-50 rounded-md px-3 py-2">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Keterangan SKAAD</label>
                            <select disabled class="mt-1 w-full border border-gray-200 bg-gray-50 rounded-md px-3 py-2">
                                <option <?php echo $item['skaad']==='Biasa'?'selected':''; ?>>Biasa</option>
                                <option <?php echo $item['skaad']==='Terbatas'?'selected':''; ?>>Terbatas</option>
                                <option <?php echo $item['skaad']==='Rahasia'?'selected':''; ?>>Rahasia</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Keterangan</label>
                            <textarea rows="3" disabled class="mt-1 w-full border border-gray-200 bg-gray-50 rounded-md px-3 py-2"><?php echo htmlspecialchars($item['keterangan']); ?></textarea>
                        </div>
                    </div>
                </form>

                <div class="mt-5">
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
                <a href="edit_aktif.php?id=<?php echo urlencode($id); ?>" class="bg-slate-700 hover:bg-slate-700/90 text-white px-4 py-2 mt-4 rounded-md inline-flex items-center">Edit</a>
            </div>
        </div>
    </div>
</div>



<?php include_once "../layouts/master/footer.php"; ?>