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
             LEFT JOIN sub_sub_masalah ssm ON ia.id_subsub = ssm.id_subsub
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
                <form action="#" method="post" class="space-y-6" id="editFormAktif">
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
                            <input type="text" name="uraianInformasi" id="uraianInformasi" value="<?php echo htmlspecialchars($item['uraian']); ?>" class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tanggal</label>
                            <input type="date" name="tanggal" id="tanggal" value="<?php echo htmlspecialchars($item['tanggal']); ?>" class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2">
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
                                                <a href="delete_file_aktif.php?id=<?php echo urlencode($id); ?>&file=<?php echo urlencode($fname); ?>" class="border border-red-300 inline-flex bg-white hover:bg-red-50 text-red-600 rounded-md px-2 py-1 text-sm">Hapus</a>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php else: ?>
                                <p class="text-sm text-gray-500">Belum ada file terunggah.</p>
                            <?php endif; ?>

                            <form action="upload_file_aktif.php?id=<?php echo urlencode($id); ?>" method="post" enctype="multipart/form-data" class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Tambah PDF baru</label>
                                <input type="file" name="files[]" accept=".pdf" multiple class="border border-gray-300 rounded-md px-3 py-2" />
                                <button type="submit" class="bg-cyan-600 hover:bg-cyan-600/90 text-white px-4 py-2 rounded-md">Upload</button>
                            </form>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <button type="submit" class="bg-cyan-600 hover:bg-cyan-600/90 text-white px-4 py-2 rounded-md">Simpan Perubahan</button>
                        <a href="detail_aktif.php?id=<?php echo urlencode($id); ?>" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-md">Batal</a>
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
    const form = document.getElementById('editFormAktif');
    if (!form) return;
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
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
        }
    });
});
</script>

<?php include_once "../layouts/master/footer.php"; ?>