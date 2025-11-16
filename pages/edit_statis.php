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
        $stmt = $conn->prepare("SELECT a.id_arsip_statis, a.id_subsub, s.kode_subsub, a.jenis_arsip, a.tahun, a.jumlah, a.tingkat_perkembangan, a.keterangan, a.file_path FROM arsip_statis a LEFT JOIN sub_sub_masalah s ON a.id_subsub = s.id_subsub WHERE a.id_arsip_statis = ?");
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
                'keterangan' => $row['keterangan'] ?? '',
                'file_path' => $row['file_path'] ?? ''
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
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm px-6 py-6 max-w-screen">
                <div class="flex justify-between items-center mb-8">
                    <a href="detail_statis.php?id=<?php echo $id; ?>" class="flex items-center text-2xl border-b">
                        <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                        </svg>
                        Kembali
                    </a>
                </div>
                <form action="../api/arsip/arsip_statis/proses_statis.php" method="post" class="space-y-6" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="id_arsip_statis" value="<?php echo htmlspecialchars($id); ?>">
                    <input type="hidden" name="id_subsub" value="<?php echo htmlspecialchars($item['id_subsub']); ?>">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- <div>
                            <label class="block text-sm font-medium text-gray-700">No</label>
                            <input type="text" value="<?php echo htmlspecialchars($item['no']); ?>" class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2" readonly>
                        </div> -->
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

                    <div class="mt-6">
                        <h3 class="text-sm font-medium text-gray-700 mb-2">File saat ini</h3>
                        <?php 
                        $existingFiles = [];
                        if (!empty($item['file_path'])) {
                            $tmp = json_decode($item['file_path'], true);
                            if (is_array($tmp)) { $existingFiles = $tmp; }
                        }
                        ?>
                        <?php if (!empty($existingFiles)) : ?>
                            <ul class="divide-y divide-gray-200 border rounded-md mb-4">
                                <?php foreach ($existingFiles as $fname): ?>
                                    <li class="flex items-center justify-between px-3 py-2">
                                        <div class="flex items-center gap-3">
                                            <span class="text-sm text-gray-700"><?php echo htmlspecialchars($fname); ?></span>
                                            <a href="../uploads/arsip_statis/<?php echo htmlspecialchars($fname); ?>" target="_blank" class="text-sm text-cyan-700 hover:underline">Lihat</a>
                                        </div>
                                        <a href="../api/arsip/arsip_statis/delete_file.php?id=<?php echo urlencode($id); ?>&file=<?php echo urlencode($fname); ?>" class="border border-red-300 inline-flex bg-white hover:bg-red-50 text-red-600 rounded-md px-2 py-1 text-sm">Hapus</a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p class="text-sm text-gray-500 mb-4">Belum ada file terunggah.</p>
                        <?php endif; ?>
                    </div>

                    <!-- File Upload -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Upload Dokumen PDF</label>
                        <div class="flex flex-col space-y-2">
                            <div class="flex items-center justify-center w-full">
                                <label for="fileUpload" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <svg class="w-8 h-8 mb-3 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                        </svg>
                                        <p class="mb-1 text-sm text-gray-500">Klik area ini untuk memulai upload file</p>
                                        <p class="text-xs text-gray-500">PDF (Maksimal 10 file)</p>
                                    </div>
                                    <input id="fileUpload" name="files[]" type="file" class="hidden" accept=".pdf" multiple />
                                </label>
                            </div>
                            <div id="fileList" class="mt-2 space-y-2"></div>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <button type="submit" class="bg-cyan-600 hover:bg-cyan-600/90 text-white px-4 py-2 rounded-md">Simpan Perubahan</button>
                        <a href="detail_statis.php?id=<?php echo $id; ?>" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-md">Batal</a>
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.getElementById('fileUpload');
        const fileList = document.getElementById('fileList');

        fileInput.addEventListener('change', function() {
            fileList.innerHTML = '';
            const files = Array.from(this.files);
            files.forEach((file, index) => {
                const div = document.createElement('div');
                div.classList.add('flex', 'items-center', 'justify-between', 'p-2', 'border', 'rounded', 'bg-gray-50');
                div.innerHTML = `
                    <span class="text-sm text-gray-700">${index + 1}. ${file.name}</span>
                    <span class="text-xs text-gray-500">${(file.size / 1024).toFixed(1)} KB</span>
                `;
                fileList.appendChild(div);
            });
        });
    });
</script>

<?php include_once "../layouts/master/footer.php"; ?>