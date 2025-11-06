<?php
include_once "../layouts/master/header.php";
include_once "../config/database.php";

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$pdfUrl = isset($_GET['file']) ? $_GET['file'] : '';

// Default item
$item = [
    'no' => $id ? (string)$id : '',
    'id_subsub' => '',
    'kode' => '',
    'jenis' => '',
    'tahun' => '',
    'jumlah' => '',
    'perkembangan' => '',
    'keterangan' => ''
];

if ($id > 0) {
    try {
        $stmt = $conn->prepare("SELECT a.id_arsip_statis, a.id_subsub, s.kode_subsub, a.jenis_arsip, a.tahun, a.jumlah, a.tingkat_perkembangan, a.keterangan FROM arsip_statis a LEFT JOIN sub_sub_masalah s ON a.id_subsub = s.id_subsub WHERE a.id_arsip_statis = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($row = $res->fetch_assoc()) {
            $item = [
                'no' => (string)($row['id_arsip_statis'] ?? $id),
                'id_subsub' => $row['id_subsub'] ?? '',
                'kode' => $row['kode_subsub'] ?? (string)($row['id_subsub'] ?? ''),
                'jenis' => $row['jenis_arsip'] ?? '',
                'tahun' => $row['tahun'] ?? '',
                'jumlah' => $row['jumlah'] ?? '',
                'perkembangan' => $row['tingkat_perkembangan'] ?? '',
                'keterangan' => $row['keterangan'] ?? ''
            ];
        }
        $stmt->close();
    } catch (Exception $e) {
        // silently ignore
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
                    <h2 class="text-3xl font-medium text-slate-700">Edit Arsip Statis</h2>
                    <span class="text-sm text-gray-500">#<?php echo htmlspecialchars($item['no']); ?></span>
                </div>
                <div class="flex items-center gap-3">
                    <a href="detail_statis.php?id=<?php echo $id; ?>" class="text-sm text-cyan-700 hover:underline">Kembali ke Detail</a>
                    <a href="statis.php" class="text-sm text-slate-700 hover:underline">Kembali ke Arsip Statis</a>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm px-6 py-6 max-w-screen">
                <form action="../api/arsip/arsip_statis/proses_statis.php" method="post" class="space-y-6">
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="id_arsip_statis" value="<?php echo htmlspecialchars($id); ?>">
                    <input type="hidden" name="id_subsub" value="<?php echo htmlspecialchars($item['id_subsub']); ?>">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">No</label>
                            <input type="text" value="<?php echo htmlspecialchars($item['no']); ?>" class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2" readonly>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Kode Klasifikasi Arsip</label>
                            <input type="text" value="<?php echo htmlspecialchars($item['kode']); ?>" class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2" readonly>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Jenis/Series Arsip</label>
                            <input type="text" name="jenis_arsip" value="<?php echo htmlspecialchars($item['jenis']); ?>" class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tahun</label>
                            <input type="number" name="tahun" value="<?php echo htmlspecialchars($item['tahun']); ?>" class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Jumlah</label>
                            <input type="number" name="jumlah" value="<?php echo htmlspecialchars($item['jumlah']); ?>" class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tingkat Perkembangan</label>
                            <select name="tingkat_perkembangan" class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2">
                                <option value="Asli" <?php echo $item['perkembangan']==='Asli'?'selected':''; ?>>Asli</option>
                                <option value="Salinan" <?php echo $item['perkembangan']==='Salinan'?'selected':''; ?>>Salinan</option>
                                <option value="Lengkap" <?php echo $item['perkembangan']==='Lengkap'?'selected':''; ?>>Lengkap</option>
                            </select>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Keterangan</label>
                            <textarea name="keterangan" rows="3" class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2"><?php echo htmlspecialchars($item['keterangan']); ?></textarea>
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