<?php
include_once "../layouts/master/header.php";
include_once "../config/database.php";

$id = isset($_GET['id']) ? $_GET['id'] : '';
$pdfUrl = isset($_GET['file']) ? $_GET['file'] : '';
$currentFiles = [];

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

// Fetch from DB based on item_arsip.id_item and join arsip_aktif + sub_sub_masalah
if (!empty($id) && ctype_digit($id)) {
    try {
        $idInt = intval($id);
        $stmt = $conn->prepare(
            "SELECT 
                ia.id_item,
                ia.nomor_item,
                ia.uraian_singkat,
                ia.uraian_informasi,
                ia.tanggal,
                ia.keterangan_skaad,
                aa.id_arsip,
                aa.nomor_berkas,
                aa.jumlah_item,
                aa.keterangan AS keterangan_berkas,
                ssm.kode_subsub,
                ia.file_path
             FROM item_arsip ia
             JOIN arsip_aktif aa ON ia.id_arsip = aa.id_arsip
             LEFT JOIN sub_sub_masalah ssm ON aa.id_subsub = ssm.id_subsub
             WHERE ia.id_item = ?"
        );
        $stmt->bind_param("i", $idInt);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($row = $res->fetch_assoc()) {
            $item = [
                'berkas' => $row['nomor_berkas'] ?? '',
                'item' => $row['nomor_item'] ?? ($row['id_item'] ?? ''),
                'kode' => $row['kode_subsub'] ?? '',
                'uraian' => $row['uraian_informasi'] ?? '',
                'uraian_singkat' => $row['uraian_singkat'] ?? '',
                'tanggal' => $row['tanggal'] ?? '',
                'jumlah' => $row['jumlah_item'] ?? '',
                'skaad' => $row['keterangan_skaad'] ?? '',
                'keterangan' => $row['keterangan_berkas'] ?? ''
            ];
            if (empty($pdfUrl) && !empty($row['file_path'])) {
                $files = explode(',', $row['file_path']);
                $firstFile = trim($files[0] ?? '');
                if (!empty($firstFile)) {
                    $pdfUrl = '../uploads/' . $firstFile;
                }
            }
            $currentFiles = !empty($row['file_path']) ? array_filter(array_map('trim', explode(',', $row['file_path']))) : [];
        }
        $stmt->close();
    } catch (Exception $e) {
        // silent fail, keep defaults
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
                    <h2 class="text-3xl font-medium text-slate-700">Edit Arsip Aktif</h2>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm px-6 py-6 max-w-screen">
                <div class="flex justify-between items-center mb-8">
                    <a href="detail_aktif.php?id=<?php echo urlencode($id); ?>" class="flex items-center text-2xl border-b">
                        <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" /></svg>
                        Kembali
                    </a>
                </div>
                <form action="#" method="post" class="space-y-6" id="editFormAktif" enctype="multipart/form-data">
                    <input type="hidden" name="id_item" id="id_item" value="<?php echo htmlspecialchars($id); ?>" />
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
                            <input type="text" name="uraianInformasi" id="uraianInformasi" value="<?php echo htmlspecialchars($item['uraian']); ?>" class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Uraian Singkat</label>
                            <textarea name="uraianSingkat" id="uraianSingkat" rows="3" class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2" required><?php echo htmlspecialchars($item['uraian_singkat'] ?? ''); ?></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tanggal</label>
                            <input type="date" name="tanggal" id="tanggal" value="<?php echo htmlspecialchars($item['tanggal']); ?>" class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Jumlah Item Arsip</label>
                            <input type="number" value="<?php echo htmlspecialchars($item['jumlah']); ?>" class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Keterangan SKAAD</label>
                            <select name="keteranganSKAAD" id="keteranganSKAAD" class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2">
                                <option <?php echo $item['skaad']==='Biasa'?'selected':''; ?>>Biasa</option>
                                <option <?php echo $item['skaad']==='Terbatas'?'selected':''; ?>>Terbatas</option>
                                <option <?php echo $item['skaad']==='Rahasia'?'selected':''; ?>>Rahasia</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Keterangan</label>
                            <textarea name="keterangan" id="keterangan" rows="3" class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2"><?php echo htmlspecialchars($item['keterangan']); ?></textarea>
                        </div>
                    </div>

                    <div class="mt-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-3">Kelola File PDF</h3>
                        <div class="space-y-3">
                            <?php if (!empty($currentFiles)) : ?>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">File saat ini</label>
                                    <ul class="divide-y divide-gray-200 border rounded-md">
                                        <?php foreach ($currentFiles as $fname): $href = '../uploads/' . $fname; ?>
                                            <li class="flex items-center justify-between px-3 py-2">
                                                <div class="flex items-center gap-3">
                                                    <span class="text-sm text-gray-700"><?php echo htmlspecialchars($fname); ?></span>
                                                    <a href="<?php echo htmlspecialchars($href); ?>" target="_blank" class="text-sm text-cyan-700 hover:underline">Lihat</a>
                                                </div>
                                                <a href="../api/arsip/arsip_aktif/delete_file_aktif.php?id=<?php echo urlencode($id); ?>&file=<?php echo urlencode($fname); ?>" class="border border-red-300 inline-flex bg-white hover:bg-red-50 text-red-600 rounded-md px-2 py-1 text-sm">Hapus</a>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php else: ?>
                                <p class="text-sm text-gray-500">Belum ada file terunggah.</p>
                            <?php endif; ?>

                            <label class="block text-sm font-medium text-gray-700 mb-1">Upload Dokumen PDF</label>
                            <div class="flex flex-col space-y-2">
                                <div class="flex items-center justify-center w-full">
                                    <label for="fileUploadAktif" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                            <svg class="w-8 h-8 mb-3 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" /></svg>
                                            <p class="mb-1 text-sm text-gray-500">Klik area ini untuk memulai upload file</p>
                                            <p class="text-xs text-gray-500">PDF (Maksimal 1 File)</p>
                                        </div>
                                        <input id="fileUploadAktif" name="files[]" type="file" class="hidden" accept=".pdf" multiple />
                                    </label>
                                </div>
                                <div id="fileListAktif" class="mt-2 space-y-2"></div>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <button type="submit" class="bg-cyan-600 hover:bg-cyan-600/90 text-white px-4 py-2 rounded-md">Simpan Perubahan</button>
                        <a href="detail_aktif.php?id=<?php echo urlencode($id); ?>" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-md">Batal</a>
                    </div>
                </form>                
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('editFormAktif');
    const fileInput = document.getElementById('fileUploadAktif');
    const fileList = document.getElementById('fileListAktif');
    if (fileInput && fileList) {
        fileInput.addEventListener('change', function() {
            fileList.innerHTML = '';
            const files = Array.from(this.files);
            files.forEach((file, index) => {
                const div = document.createElement('div');
                div.classList.add('flex','items-center','justify-between','p-2','border','rounded','bg-gray-50');
                div.innerHTML = `<span class="text-sm text-gray-700">${index + 1}. ${file.name}</span><span class="text-xs text-gray-500">${(file.size / 1024).toFixed(1)} KB</span>`;
                fileList.appendChild(div);
            });
        });
    }
    if (!form) return;
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn ? submitBtn.textContent : '';
        if (submitBtn) { submitBtn.disabled = true; submitBtn.textContent = 'Menyimpan...'; }
        const formData = new FormData(form);
        try {
            const res = await fetch('../api/arsip/arsip_aktif/proses_edit_arsip.php', { method: 'POST', body: formData });
            const text = await res.text();
            let data;
            try { data = JSON.parse(text); } catch (err) { throw new Error('Respons bukan JSON: ' + text); }
            alert(data.message || 'Perubahan tersimpan.');
            if (data.success) {
                const id = document.getElementById('id_item').value;
                window.location.href = `detail_aktif.php?id=${encodeURIComponent(id)}`;
            }
        } catch (err) {
            console.error(err);
            alert('Terjadi kesalahan saat menyimpan perubahan.');
        } finally {
            if (submitBtn) { submitBtn.disabled = false; submitBtn.textContent = originalText; }
        }
    });
});
</script>

<?php include_once "../layouts/master/footer.php"; ?>