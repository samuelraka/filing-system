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
    'keterangan' => ''
];
$notFound = false;

if ($id > 0) {
    try {
        $stmt = $conn->prepare("SELECT a.id_arsip_statis, a.id_subsub, s.kode_subsub, a.jenis_arsip, a.tahun, a.jumlah, a.tingkat_perkembangan, a.keterangan FROM arsip_statis a LEFT JOIN sub_sub_masalah s ON a.id_subsub = s.id_subsub WHERE a.id_arsip_statis = ?");
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
                'keterangan' => $row['keterangan'] ?? ''
            ];
        } else {
            $notFound = true;
        }
        $stmt->close();
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
                    <span class="text-sm text-gray-500">#<?php echo htmlspecialchars($item['no']); ?></span>
                </div>
                <div class="flex items-center gap-3">
                    <a href="statis.php" class="text-sm text-cyan-700 hover:underline">Kembali ke Arsip Statis</a>
                    <a href="edit_statis.php?id=<?php echo $id; ?>" class="text-sm text-slate-700 hover:underline">Buka Halaman Edit</a>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm px-6 py-6 max-w-screen">
                <?php if ($notFound): ?>
                    <div class="p-4 mb-4 text-red-700 bg-red-50 border border-red-200 rounded">Data arsip tidak ditemukan untuk ID yang diberikan.</div>
                <?php endif; ?>
                <form action="#" method="post" class="space-y-6" id="detailForm">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">No</label>
                            <input type="text" value="<?php echo htmlspecialchars($item['no']); ?>" disabled class="mt-1 w-full border border-gray-200 bg-gray-50 rounded-md px-3 py-2">
                        </div>
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
                        <button type="button" id="toggleEditBtn" class="bg-slate-700 hover:bg-slate-700/90 text-white px-4 py-2 rounded-md">Edit</button>
                        <button type="submit" id="saveBtn" class="bg-cyan-600 hover:bg-cyan-600/90 text-white px-4 py-2 rounded-md hidden">Simpan</button>
                        <button type="button" id="cancelBtn" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-md hidden">Batal</button>
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

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('detailForm');
    const inputs = form.querySelectorAll('input, select, textarea');
    const toggleBtn = document.getElementById('toggleEditBtn');
    const saveBtn = document.getElementById('saveBtn');
    const cancelBtn = document.getElementById('cancelBtn');

    function setDisabled(state) {
        inputs.forEach(el => {
            el.disabled = state;
            if (state) {
                el.classList.add('bg-gray-50', 'border-gray-200');
                el.classList.remove('border-gray-300');
            } else {
                el.classList.remove('bg-gray-50');
                el.classList.add('border-gray-300');
            }
        });
    }

    toggleBtn.addEventListener('click', function () {
        const isDisabled = inputs[0].disabled;
        setDisabled(!isDisabled);
        saveBtn.classList.toggle('hidden');
        cancelBtn.classList.toggle('hidden');
        toggleBtn.textContent = isDisabled ? 'Edit' : 'Selesai Edit';
    });

    cancelBtn.addEventListener('click', function () {
        setDisabled(true);
        saveBtn.classList.add('hidden');
        cancelBtn.classList.add('hidden');
        toggleBtn.textContent = 'Edit';
    });
});
</script>

<?php include_once "../layouts/master/footer.php"; ?>