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

// Fetch from DB if ID provided
if (!empty($id)) {
    try {
        if (ctype_digit($id)) {
            // Numeric ID -> assume primary key id_arsip_aktif
            $stmt = $conn->prepare("SELECT id_arsip_aktif, nomor_berkas, nomor_item_arsip, kode_klasifikasi_arsip, uraian_informasi_arsip, tanggal, jumlah_item_arsip, keterangan_skaad, keterangan FROM arsip_aktif WHERE id_arsip_aktif = ?");
            $stmt->bind_param("i", $id);
        } else {
            // Non-numeric -> fallback by nomor_item_arsip
            $stmt = $conn->prepare("SELECT id_arsip_aktif, nomor_berkas, nomor_item_arsip, kode_klasifikasi_arsip, uraian_informasi_arsip, tanggal, jumlah_item_arsip, keterangan_skaad, keterangan FROM arsip_aktif WHERE nomor_item_arsip = ?");
            $stmt->bind_param("s", $id);
        }
        $stmt->execute();
        $res = $stmt->get_result();
        if ($row = $res->fetch_assoc()) {
            $item = [
                'berkas' => $row['nomor_berkas'] ?? '',
                'item' => $row['nomor_item_arsip'] ?? ($row['id_arsip_aktif'] ?? ''),
                'kode' => $row['kode_klasifikasi_arsip'] ?? '',
                'uraian' => $row['uraian_informasi_arsip'] ?? '',
                'tanggal' => $row['tanggal'] ?? '',
                'jumlah' => $row['jumlah_item_arsip'] ?? '',
                'skaad' => $row['keterangan_skaad'] ?? '',
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
                    <h2 class="text-3xl font-medium text-slate-700">Detail Berkas</h2>
                    <span class="text-sm text-gray-500"><?php echo htmlspecialchars($item['item']); ?></span>
                </div>
                <div class="flex items-center gap-3">
                    <a href="aktif.php" class="text-sm text-cyan-700 hover:underline">Kembali ke Arsip Aktif</a>
                    <a href="edit_aktif.php?id=<?php echo urlencode($id); ?>" class="text-sm text-slate-700 hover:underline">Buka Halaman Edit</a>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm px-6 py-6 max-w-screen">
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

                    <div class="flex items-center gap-3">
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
                <button type="button" id="toggleEditBtn" class="bg-slate-700 hover:bg-slate-700/90 text-white px-4 py-2 rounded-md">Edit</button>
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