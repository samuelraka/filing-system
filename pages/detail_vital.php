<?php
include_once "../layouts/master/header.php";

// Dummy data map by id
$id = isset($_GET['id']) ? intval($_GET['id']) : 1;
$data = [
    1 => [
        'no' => '1',
        'uraian' => 'Rencana Kontingensi',
        'unit' => 'TI',
        'kurun' => '2022–2024',
        'media' => 'Kertas',
        'jumlah' => '3',
        'jangka' => 'Permanen',
        'lokasi' => 'Ruang Arsip 1',
        'perlindungan' => 'Scan & Laminasi',
        'keterangan' => 'Akses terbatas, arsip vital',
    ],
    2 => [
        'no' => '2',
        'uraian' => 'Data Karyawan Kritis',
        'unit' => 'SDM',
        'kurun' => '2018–2023',
        'media' => 'Digital',
        'jumlah' => '2',
        'jangka' => '10 Tahun',
        'lokasi' => 'Vault Digital',
        'perlindungan' => 'Backup Harian + Enkripsi',
        'keterangan' => 'Rahasia',
    ],
    3 => [
        'no' => '3',
        'uraian' => 'Konfigurasi Sistem Keamanan',
        'unit' => 'TI',
        'kurun' => '2020–2024',
        'media' => 'Digital',
        'jumlah' => '1',
        'jangka' => 'Permanen',
        'lokasi' => 'Server Room',
        'perlindungan' => 'Redundansi + Enkripsi',
        'keterangan' => 'Dokumen kritikal',
    ],
    4 => [
        'no' => '4',
        'uraian' => 'Prosedur Penanggulangan Bencana',
        'unit' => 'Operasional',
        'kurun' => '2019–2024',
        'media' => 'Kertas',
        'jumlah' => '8',
        'jangka' => '5 Tahun',
        'lokasi' => 'Ruang Arsip 2',
        'perlindungan' => 'Laminasi + Kedap Air',
        'keterangan' => 'Untuk audit',
    ],
    5 => [
        'no' => '5',
        'uraian' => 'Backup Database Bulanan',
        'unit' => 'TI',
        'kurun' => '2021–2024',
        'media' => 'Digital',
        'jumlah' => '12',
        'jangka' => 'Permanen',
        'lokasi' => 'Vault Digital',
        'perlindungan' => 'Backup Offsite + Enkripsi',
        'keterangan' => 'High priority',
    ],
];

$item = $data[$id] ?? $data[1];
$pdfUrl = isset($_GET['file']) ? $_GET['file'] : '';
?>

<div class="flex h-screen">
    <?php include_once "../layouts/components/sidebar_dynamic.php"; ?>

    <div class="flex-1 flex flex-col ml-64">
        <?php include_once "../layouts/components/topbar.php"; ?>

        <div class="p-6 mt-16 overflow-y-auto">
            <div class="flex justify-between items-center mb-8">
                <div class="flex items-center gap-3">
                    <h2 class="text-3xl font-medium text-slate-700">Detail Arsip Vital</h2>
                    <span class="text-sm text-gray-500">#<?php echo htmlspecialchars($item['no']); ?></span>
                </div>
                <div class="flex items-center gap-3">
                    <a href="vital.php" class="text-sm text-cyan-700 hover:underline">Kembali ke Arsip Vital</a>
                    <a href="edit_vital.php?id=<?php echo $id; ?>" class="text-sm text-slate-700 hover:underline">Buka Halaman Edit</a>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm px-6 py-6 max-w-screen">
                <form action="#" method="post" class="space-y-6" id="detailForm">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">No</label>
                            <input type="text" value="<?php echo htmlspecialchars($item['no']); ?>" disabled class="mt-1 w-full border border-gray-200 bg-gray-50 rounded-md px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Uraian Arsip</label>
                            <input type="text" value="<?php echo htmlspecialchars($item['uraian']); ?>" disabled class="mt-1 w-full border border-gray-200 bg-gray-50 rounded-md px-3 py-2">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Unit Kerja</label>
                            <input type="text" value="<?php echo htmlspecialchars($item['unit']); ?>" disabled class="mt-1 w-full border border-gray-200 bg-gray-50 rounded-md px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Kurun Waktu</label>
                            <input type="text" value="<?php echo htmlspecialchars($item['kurun']); ?>" disabled class="mt-1 w-full border border-gray-200 bg-gray-50 rounded-md px-3 py-2">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Media</label>
                            <select disabled class="mt-1 w-full border border-gray-200 bg-gray-50 rounded-md px-3 py-2">
                                <option <?php echo $item['media']==='Kertas'?'selected':''; ?>>Kertas</option>
                                <option <?php echo $item['media']==='Digital'?'selected':''; ?>>Digital</option>
                                <option <?php echo $item['media']==='Microfilm'?'selected':''; ?>>Microfilm</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Jumlah</label>
                            <input type="number" value="<?php echo htmlspecialchars($item['jumlah']); ?>" disabled class="mt-1 w-full border border-gray-200 bg-gray-50 rounded-md px-3 py-2">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Jangka Simpan</label>
                            <input type="text" value="<?php echo htmlspecialchars($item['jangka']); ?>" disabled class="mt-1 w-full border border-gray-200 bg-gray-50 rounded-md px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Lokasi Simpan</label>
                            <input type="text" value="<?php echo htmlspecialchars($item['lokasi']); ?>" disabled class="mt-1 w-full border border-gray-200 bg-gray-50 rounded-md px-3 py-2">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Metode Perlindungan</label>
                            <input type="text" value="<?php echo htmlspecialchars($item['perlindungan']); ?>" disabled class="mt-1 w-full border border-gray-200 bg-gray-50 rounded-md px-3 py-2">
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