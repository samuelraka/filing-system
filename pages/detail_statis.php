<?php
include_once "../layouts/master/header.php";
include_once "../config/database.php";

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$pdfUrl = isset($_GET['file']) ? $_GET['file'] : '';

// Default item placeholder
$item = [
    'no' => $id ? (string)$id : '',
    'kode' => '',
    'jenis' => '',
    'tahun' => '',
    'jumlah' => '',
    'perkembangan' => '',
    'keterangan' => '',
    'file_path' => ''
];
$notFound = false;

if ($id > 0) {
    try {
        $stmt = $conn->prepare("SELECT a.id_arsip_statis, a.id_subsub, s.kode_subsub, a.jenis_arsip, a.tahun, a.jumlah, a.tingkat_perkembangan, a.keterangan, a.file_path FROM arsip_statis a LEFT JOIN sub_sub_masalah s ON a.id_subsub = s.id_subsub WHERE a.id_arsip_statis = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($row = $res->fetch_assoc()) {
            $item = [
                'no' => (string)($row['id_arsip_statis'] ?? $id),
                'kode' => $row['kode_subsub'] ?? (string)($row['id_subsub'] ?? ''),
                'jenis' => $row['jenis_arsip'] ?? '',
                'tahun' => $row['tahun'] ?? '',
                'jumlah' => $row['jumlah'] ?? '',
                'perkembangan' => $row['tingkat_perkembangan'] ?? '',
                'keterangan' => $row['keterangan'] ?? '',
                'file_path' => $row['file_path'] ?? ''
            ];
        } else {
            $stmt->close();
            $stmt2 = $conn->prepare("SELECT id_arsip_statis, id_subsub, jenis_arsip, tahun, jumlah, tingkat_perkembangan, keterangan, file_path FROM arsip_statis WHERE id_arsip_statis = ?");
            $stmt2->bind_param("i", $id);
            $stmt2->execute();
            $res2 = $stmt2->get_result();
            if ($row = $res2->fetch_assoc()) {
                $item = [
                    'no' => (string)($row['id_arsip_statis'] ?? $id),
                    'kode' => (string)($row['id_subsub'] ?? ''),
                    'jenis' => $row['jenis_arsip'] ?? '',
                    'tahun' => $row['tahun'] ?? '',
                    'jumlah' => $row['jumlah'] ?? '',
                    'perkembangan' => $row['tingkat_perkembangan'] ?? '',
                    'keterangan' => $row['keterangan'] ?? '',
                    'file_path' => $row['file_path'] ?? ''
                ];
            } else {
                $notFound = true;
            }
            $stmt2->close();
        }
        if ($stmt) { $stmt->close(); }
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
                    <h2 class="text-3xl font-medium text-slate-700">Detail Arsip Statis</h2>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm px-6 py-6 max-w-screen">
                <div class="flex justify-between items-center mb-8">
                    <a href="statis.php" class="flex items-center text-2xl border-b">
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
                        <!-- <div>
                            <label class="block text-sm font-medium text-gray-700">No</label>
                            <input type="text" value="<?php echo htmlspecialchars($item['no']); ?>" disabled class="mt-1 w-full border border-gray-200 bg-gray-50 rounded-md px-3 py-2">
                        </div> -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Kode Klasifikasi Arsip</label>
                            <input type="text" value="<?php echo htmlspecialchars($item['kode']); ?>" disabled class="mt-1 w-full border border-gray-200 bg-gray-50 rounded-md px-3 py-2">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Jenis/Series Arsip</label>
                            <input type="text" value="<?php echo htmlspecialchars($item['jenis']); ?>" disabled class="mt-1 w-full border border-gray-200 bg-gray-50 rounded-md px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tahun</label>
                            <input type="number" value="<?php echo htmlspecialchars($item['tahun']); ?>" disabled class="mt-1 w-full border border-gray-200 bg-gray-50 rounded-md px-3 py-2">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Jumlah</label>
                            <input type="number" value="<?php echo htmlspecialchars($item['jumlah']); ?>" disabled class="mt-1 w-full border border-gray-200 bg-gray-50 rounded-md px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tingkat Perkembangan</label>
                            <select disabled class="mt-1 w-full border border-gray-200 bg-gray-50 rounded-md px-3 py-2">
                                <option <?php echo $item['perkembangan']==='Asli'?'selected':''; ?>>Asli</option>
                                <option <?php echo $item['perkembangan']==='Salinan'?'selected':''; ?>>Salinan</option>
                                <option <?php echo $item['perkembangan']==='Lengkap'?'selected':''; ?>>Lengkap</option>
                            </select>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Keterangan</label>
                            <textarea rows="3" disabled class="mt-1 w-full border border-gray-200 bg-gray-50 rounded-md px-3 py-2"><?php echo htmlspecialchars($item['keterangan']); ?></textarea>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <a href="edit_statis.php?id=<?php echo $id; ?>" class="bg-slate-700 hover:bg-slate-700/90 text-white px-4 py-2 rounded-md inline-flex items-center">Edit</a>
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
                                            <a href="../uploads/arsip_statis/<?php echo htmlspecialchars($file); ?>" target="_blank" class="bg-cyan-600 hover:bg-cyan-600/90 text-white px-3 py-1 rounded text-sm inline-flex items-center gap-1">
                                                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7"></path>
                                                    <path d="M10 3h5v5"></path>
                                                </svg>
                                                Lihat
                                            </a>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
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