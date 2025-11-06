<?php
include_once "../layouts/master/header.php";
include_once "../config/database.php";

// Pagination setup
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

// Ambil parameter pencarian dan filter
$keyword = isset($_GET['search']) ? trim($_GET['search']) : '';
$filter_media = isset($_GET['media']) ? trim($_GET['media']) : '';
$filter_lokasi = isset($_GET['lokasi']) ? trim($_GET['lokasi']) : '';
$filter_metode = isset($_GET['metode']) ? trim($_GET['metode']) : '';

// WHERE dinamis
$where = "WHERE 1=1";

if ($keyword !== '') {
    $keyword_safe = mysqli_real_escape_string($conn, $keyword);
    $where .= " AND (v.kode_klasifikasi LIKE '%$keyword_safe%' 
                OR v.jenis_arsip LIKE '%$keyword_safe%' 
                OR v.tahun LIKE '%$keyword_safe%')";
}

if ($filter_media !== '') {
    $media_safe = mysqli_real_escape_string($conn, $filter_media);
    $where .= " AND v.media = '$media_safe'";
}

if ($filter_lokasi !== '') {
    $lokasi_safe = mysqli_real_escape_string($conn, $filter_lokasi);
    $where .= " AND v.lokasi_simpan = '$lokasi_safe'";
}

if ($filter_metode !== '') {
    $metode_safe = mysqli_real_escape_string($conn, $filter_metode);
    $where .= " AND v.metode_perlindungan = '$metode_safe'";
}

// Hitung total data
$total_result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM arsip_vital v $where");
$total_row = mysqli_fetch_assoc($total_result);
$total_data = $total_row['total'];
$total_pages = ceil($total_data / $limit);

// Query utama
$query = "
    SELECT v.* 
    FROM arsip_vital v
    $where
    ORDER BY v.created_at DESC
    LIMIT $limit OFFSET $offset
";
$result = mysqli_query($conn, $query);

// Ambil nilai unik untuk dropdown
$media_result = mysqli_query($conn, "SELECT DISTINCT media FROM arsip_vital ORDER BY media ASC");
$lokasi_result = mysqli_query($conn, "SELECT DISTINCT lokasi_simpan FROM arsip_vital ORDER BY lokasi_simpan ASC");
$metode_result = mysqli_query($conn, "SELECT DISTINCT metode_perlindungan FROM arsip_vital ORDER BY metode_perlindungan ASC");
?>


<div class="flex h-screen">
    <!-- Include sidebar -->
    <?php include_once "../layouts/components/sidebar_dynamic.php"; ?>

    <div class="flex-1 flex flex-col ml-64">
        <!-- Include topbar -->
        <?php include_once "../layouts/components/topbar.php"; ?>

        <!-- Main content -->
        <div class="p-6 mt-16 overflow-y-auto">
            <!-- Header with search and actions -->
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-3xl font-medium text-slate-700">Arsip Vital</h2>
            </div>

            <!-- Filters / Actions -->
            <div class="flex justify-between items-center mb-5">
                <div class="flex items-center gap-x-4">
                    <a href="tambah_vital.php" class="bg-cyan-600 hover:bg-cyan-600/90 text-white px-3 py-2 gap-1 rounded-md flex items-center">
                        <span class="material-symbols-outlined">add</span>
                        <span>Tambah Arsip</span>
                    </a>
                    <a href="#" class="bg-slate-700 hover:bg-slate-700/90 text-white px-3 py-2 gap-2 rounded-md flex items-center">
                        <span class="material-symbols-outlined">print</span>
                        <span>Cetak Tabel Arsip</span>
                    </a>
                </div>
                <div class="flex items-center gap-x-4">
                    <div class="relative w-64">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input type="text" id="searchInput" placeholder="Uraian Arsip, Unit Kerja, Lokasi Simpan" class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#0092B8]">
                    </div>
                    <div class="flex items-center">
                        <button id="filtersBtn" class="border border-gray-300 bg-white text-slate-700 px-4 py-2 rounded-md flex items-center hover:bg-gray-100">
                            <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd" />
                            </svg>
                            Filters
                        </button>
                    </div>
                </div>
            </div>

            <!-- Dropdown filter -->
            <div id="filterDropdown" class="hidden mt-3 bg-white border border-gray-200 rounded-md shadow p-4 w-[320px]">
                <div class="mb-3">
                    <label for="filterMedia" class="block text-sm text-gray-700 font-medium">Media</label>
                    <select id="filterMedia" class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2">
                        <option value="">Semua</option>
                        <?php while ($m = mysqli_fetch_assoc($media_result)): ?>
                            <option value="<?= htmlspecialchars($m['media']) ?>" <?= ($filter_media == $m['media']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($m['media']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="filterLokasi" class="block text-sm text-gray-700 font-medium">Lokasi Simpan</label>
                    <select id="filterLokasi" class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2">
                        <option value="">Semua</option>
                        <?php while ($l = mysqli_fetch_assoc($lokasi_result)): ?>
                            <option value="<?= htmlspecialchars($l['lokasi_simpan']) ?>" <?= ($filter_lokasi == $l['lokasi_simpan']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($l['lokasi_simpan']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div>
                    <label for="filterMetode" class="block text-sm text-gray-700 font-medium">Metode Perlindungan</label>
                    <select id="filterMetode" class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2">
                        <option value="">Semua</option>
                        <?php while ($p = mysqli_fetch_assoc($metode_result)): ?>
                            <option value="<?= htmlspecialchars($p['metode_perlindungan']) ?>" <?= ($filter_metode == $p['metode_perlindungan']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($p['metode_perlindungan']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="flex justify-end mt-4">
                    <button id="applyFilterBtn" class="bg-[#0092B8] hover:bg-[#007A99] text-white px-3 py-2 rounded-md">Terapkan</button>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm px-6 py-3 max-w-screen">
                <div class="flex flex-col space-y-4">
                    <!-- Table -->
                    <div class="overflow-x-auto mt-3">
                        <table class="min-w-full border border-gray-200 divide-y divide-gray-200 table-auto">
                            <thead class="bg-gray-50">
                                <tr class="divide-x divide-gray-200 text-center">
                                    <th class="px-3 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                    <th class="px-3 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Uraian Arsip</th>
                                    <th class="px-3 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Kerja</th>
                                    <th class="px-3 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Kurun Waktu</th>
                                    <th class="px-3 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Media</th>
                                    <th class="px-3 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                                    <th class="px-3 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Jangka Simpan</th>
                                    <th class="px-3 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Lokasi Simpan</th>
                                    <th class="px-3 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Metode Perlindungan</th>
                                    <th class="px-3 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Keterangan</th>
                                    <th class="px-3 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Detail</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php if (mysqli_num_rows($result) > 0): ?>
                                    <?php $no = 1; ?>
                                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                        <tr class="hover:bg-gray-50 divide-x divide-gray-200">
                                            <td class="px-3 py-4 text-center"><?= $no++; ?></td>
                                            <td class="px-3 py-4"><?= htmlspecialchars($row['uraian_arsip']); ?></td>
                                            <td class="px-3 py-4 text-center"><?= htmlspecialchars($row['unit_kerja']); ?></td>
                                            <td class="px-3 py-4 text-center"><?= htmlspecialchars($row['kurun_waktu']); ?></td>
                                            <td class="px-3 py-4 text-center"><?= htmlspecialchars($row['media']); ?></td>
                                            <td class="px-3 py-4 text-center"><?= htmlspecialchars($row['jumlah']); ?></td>
                                            <td class="px-3 py-4 text-center"><?= htmlspecialchars($row['jangka_simpan']); ?></td>
                                            <td class="px-3 py-4 text-center"><?= htmlspecialchars($row['lokasi_simpan']); ?></td>
                                            <td class="px-3 py-4 text-center"><?= htmlspecialchars($row['metode_perlindungan']); ?></td>
                                            <td class="px-3 py-4"><?= htmlspecialchars($row['keterangan']); ?></td>
                                            <td class="px-3 py-4 text-center">
                                                <a href="detail_vital.php?id=<?= $row['id_arsip'] ?>" 
                                                class="action-button border border-gray-300 bg-white hover:bg-gray-100 rounded-md p-1 shadow-sm"
                                                title="Lihat Detail">
                                                    <span class="material-symbols-outlined text-gray-700 text-xs">quick_reference_all</span>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="11" class="text-center py-4 text-gray-500">Tidak ada data arsip vital.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="flex items-center justify-between mt-4">
                        <p class="text-sm text-gray-600">
                            Halaman <?= $page ?> dari <?= $total_pages ?> (Total <?= $total_data ?> data)
                        </p>
                        <div class="flex items-center space-x-1">
                            <?php if ($page > 1): ?>
                                <a href="?page=<?= $page - 1 ?>&search=<?= $keyword ?>&media=<?= $filter_media ?>&lokasi=<?= $filter_lokasi ?>&metode=<?= $filter_metode ?>" class="px-3 py-1 border border-gray-300 rounded-md text-sm hover:bg-gray-100">&laquo; Sebelumnya</a>
                            <?php endif; ?>

                            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                <a href="?page=<?= $i ?>&search=<?= $keyword ?>&media=<?= $filter_media ?>&lokasi=<?= $filter_lokasi ?>&metode=<?= $filter_metode ?>" class="px-3 py-1 border border-gray-300 rounded-md text-sm <?= ($i == $page) ? 'bg-cyan-600 text-white' : 'hover:bg-gray-100' ?>"><?= $i ?></a>
                            <?php endfor; ?>

                            <?php if ($page < $total_pages): ?>
                                <a href="?page=<?= $page + 1 ?>&search=<?= $keyword ?>&media=<?= $filter_media ?>&lokasi=<?= $filter_lokasi ?>&metode=<?= $filter_metode ?>" class="px-3 py-1 border border-gray-300 rounded-md text-sm hover:bg-gray-100">Berikutnya &raquo;</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- /content -->
    </div><!-- /flex-1 -->
    
</div><!-- /screen -->

<script>
document.getElementById('searchInput').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        const search = e.target.value.trim();
        const params = new URLSearchParams(window.location.search);
        params.set('search', search);
        params.set('page', 1);
        window.location.search = params.toString();
    }
});

document.getElementById('filtersBtn').addEventListener('click', function() {
    document.getElementById('filterDropdown').classList.toggle('hidden');
});

document.getElementById('applyFilterBtn').addEventListener('click', function() {
    const media = document.getElementById('filterMedia').value;
    const lokasi = document.getElementById('filterLokasi').value;
    const metode = document.getElementById('filterMetode').value;
    const params = new URLSearchParams(window.location.search);

    if (media) params.set('media', media); else params.delete('media');
    if (lokasi) params.set('lokasi', lokasi); else params.delete('lokasi');
    if (metode) params.set('metode', metode); else params.delete('metode');
    params.set('page', 1);
    window.location.search = params.toString();
});
</script>


<?php
// Include footer
include_once "../layouts/master/footer.php";
?>