<?php
include_once "../layouts/master/header.php";
include_once "../config/database.php";

// --- konfigurasi pagination ---
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

// --- ambil parameter pencarian & filter ---
$keyword = isset($_GET['search']) ? trim($_GET['search']) : '';
$filter_jenis = isset($_GET['jenis']) ? trim($_GET['jenis']) : '';
$filter_tahun = isset($_GET['tahun']) ? trim($_GET['tahun']) : '';

// --- bangun query WHERE dinamis ---
$where = "WHERE 1=1";

if ($keyword !== '') {
    $keyword_safe = mysqli_real_escape_string($conn, $keyword);
    $where .= " AND (s.kode_subsub LIKE '%$keyword_safe%' 
                OR a.jenis_arsip LIKE '%$keyword_safe%' 
                OR a.tahun LIKE '%$keyword_safe%')";
}

if ($filter_jenis !== '') {
    $filter_jenis_safe = mysqli_real_escape_string($conn, $filter_jenis);
    $where .= " AND a.jenis_arsip = '$filter_jenis_safe'";
}

if ($filter_tahun !== '') {
    $filter_tahun_safe = mysqli_real_escape_string($conn, $filter_tahun);
    $where .= " AND a.tahun = '$filter_tahun_safe'";
}

// --- hitung total data ---
$total_result = mysqli_query($conn, "
    SELECT COUNT(*) AS total 
    FROM arsip_statis a 
    JOIN sub_sub_masalah s ON a.id_subsub = s.id_subsub 
    $where
");
$total_row = mysqli_fetch_assoc($total_result);
$total_data = $total_row['total'];
$total_pages = ceil($total_data / $limit);

// --- ambil data arsip statis ---
$query = "
    SELECT a.*, s.kode_subsub, s.topik_subsub 
    FROM arsip_statis a 
    JOIN sub_sub_masalah s ON a.id_subsub = s.id_subsub
    $where
    ORDER BY a.created_at DESC
    LIMIT $limit OFFSET $offset
";
$result = mysqli_query($conn, $query);

// --- ambil data unik untuk dropdown filter ---
$jenis_result = mysqli_query($conn, "SELECT DISTINCT jenis_arsip FROM arsip_statis ORDER BY jenis_arsip ASC");
$tahun_result = mysqli_query($conn, "SELECT DISTINCT tahun FROM arsip_statis ORDER BY tahun DESC");
?>


<div class="flex h-screen">
    <!-- Include sidebar -->
    <?php include_once "../layouts/components/sidebar_dynamic.php"; ?>

    <div class="flex-1 flex flex-col ml-64">
        <!-- Include topbar -->
        <?php include_once "../layouts/components/topbar.php"; ?>

        <!-- Main content -->
        <div class="p-6 mt-16 overflow-y-auto max-w-[calc(100vw-16rem)] flex-1">
            <!-- Header with search and actions -->
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-3xl font-medium text-slate-700">Arsip Statis</h2>
            </div>

            <!-- Filters / Actions -->
            <div class="flex justify-between items-center mb-5">
                <div class="flex items-center gap-x-4">
                    <a href="tambah_statis.php" class="bg-cyan-600 hover:bg-cyan-600/90 text-white px-3 py-2 gap-1 rounded-md flex items-center">
                        <span class="material-symbols-outlined">add</span>
                        <span>Tambah Arsip</span>
                    </a>
                    <a href="../api/arsip/arsip_statis/export_excel.php?search=<?= urlencode($keyword) ?>&jenis=<?= urlencode($filter_jenis) ?>&tahun=<?= urlencode($filter_tahun) ?>" class="bg-slate-700 hover:bg-slate-700/90 text-white px-3 py-2 gap-2 rounded-md flex items-center" target="_blank" rel="noopener">
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
                        <input type="text" id="searchInput" value="<?= htmlspecialchars($keyword) ?>" placeholder="Kode Klasifikasi, Jenis Arsip, Tahun" class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#0092B8]">
                    </div>
                    <div class="relative">
                        <button id="filtersBtn" class="border border-gray-300 bg-white text-slate-700 px-4 py-2 rounded-md flex items-center hover:bg-gray-100">
                            <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd" />
                            </svg>
                            Filters
                        </button>
                        <!-- Dropdown filter anchored under button -->
                        <div id="filterDropdown" class="hidden absolute top-full right-0 mt-2 z-50 bg-white border border-gray-200 rounded-md shadow p-4 w-[300px] transition ease-out duration-200 transform origin-top-right opacity-0 translate-y-2">
                            <div class="mb-3">
                                <label for="filterJenis" class="block text-sm text-gray-700 font-medium">Jenis Arsip</label>
                                <select id="filterJenis" class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2">
                                    <option value="">Semua</option>
                                    <?php while ($j = mysqli_fetch_assoc($jenis_result)): ?>
                                        <option value="<?= htmlspecialchars($j['jenis_arsip']) ?>" <?= ($filter_jenis == $j['jenis_arsip']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($j['jenis_arsip']) ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>

                            <div>
                                <label for="filterTahun" class="block text-sm text-gray-700 font-medium">Tahun</label>
                                <select id="filterTahun" class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2">
                                    <option value="">Semua</option>
                                    <?php while ($t = mysqli_fetch_assoc($tahun_result)): ?>
                                        <option value="<?= htmlspecialchars($t['tahun']) ?>" <?= ($filter_tahun == $t['tahun']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($t['tahun']) ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>

                            <div class="flex justify-between mt-4">
                                <button id="resetFilterBtn" class="border border-gray-300 bg-white text-slate-700 px-3 py-2 rounded-md hover:bg-gray-100">Reset</button>
                                <button id="applyFilterBtn" class="bg-[#0092B8] hover:bg-[#007A99] text-white px-3 py-2 rounded-md">Terapkan</button>
                            </div>
                        </div>
                    </div>
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
                                    <th class="px-3 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Kode Klasifikasi Arsip</th>
                                    <th class="px-3 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis/Series Arsip</th>
                                    <th class="px-3 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Tahun</th>
                                    <th class="px-3 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                                    <th class="px-3 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Tingkat Perkembangan</th>
                                    <th class="px-3 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Keterangan</th>
                                    <th class="px-3 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Detail</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php $no = 1; while ($row = mysqli_fetch_assoc($result)) : ?>
                                <!-- Row 1 -->
                                <tr class="hover:bg-gray-50 divide-x divide-gray-200">
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900 text-center"><?= $no++ ?></td>
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900"><?= $row['kode_subsub'] ?></td>
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900"><?= $row['jenis_arsip'] ?></td>
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900 text-center"><?= $row['tahun'] ?></td>
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900 text-center"><?= $row['jumlah'] ?></td>
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900"><?= $row['tingkat_perkembangan'] ?></td>
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900"><?= $row['keterangan'] ?></td>
                                    <td class="px-3 py-4 whitespace-nowrap text-center text-sm font-medium">
                                        <a href="detail_statis.php?id=<?= $row['id_arsip_statis'] ?>" class="action-button border border-gray-300 inline-flex bg-white hover:bg-gray-100 rounded-md p-1 shadow-sm" title="Lihat Detail">
                                            <span class="material-symbols-outlined text-gray-700 text-xs">quick_reference_all</span>
                                        </a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>

                    <?php
                        $baseUrl = "?search=$keyword&jenis=$filter_jenis&tahun=$filter_tahun";
                    ?>

                    <!-- Pagination -->
                    <div class="flex items-center justify-between mt-4">
                        <p class="text-sm text-gray-600">
                            Halaman <?= $page ?> dari <?= $total_pages ?> (Total <?= $total_data ?> data)
                        </p>

                        <div class="flex items-center space-x-1">
                            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                <a href="<?= $baseUrl ?>&page=<?= $i ?>" 
                                class="px-3 py-1 border border-gray-300 rounded-md text-sm <?= ($i == $page) ? 'bg-cyan-600 text-white' : 'hover:bg-gray-100' ?>">
                                    <?= $i ?>
                                </a>
                            <?php endfor; ?>

                            <?php if ($page > 1): ?>
                                <a href="<?= $baseUrl ?>&page=<?= $page - 1 ?>" class="px-3 py-1 border border-gray-300 rounded-md text-sm hover:bg-gray-100">
                                    &laquo; Sebelumnya
                                </a>
                            <?php endif; ?>

                            <?php if ($page < $total_pages): ?>
                                <a href="<?= $baseUrl ?>&page=<?= $page + 1 ?>" class="px-3 py-1 border border-gray-300 rounded-md text-sm hover:bg-gray-100">
                                    Berikutnya &raquo;
                                </a>
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
        params.set('page', 1); // reset ke halaman 1
        window.location.search = params.toString();
    }
});

// toggle dropdown filter
document.getElementById('filtersBtn').addEventListener('click', function() {
    const dd = document.getElementById('filterDropdown');
    if (dd.classList.contains('hidden')) {
        dd.classList.remove('hidden');
        // animate in
        dd.classList.remove('opacity-0', 'translate-y-2');
        dd.classList.add('opacity-100', 'translate-y-0');
    } else {
        // animate out then hide
        dd.classList.remove('opacity-100', 'translate-y-0');
        dd.classList.add('opacity-0', 'translate-y-2');
        setTimeout(() => dd.classList.add('hidden'), 200);
    }
});

// tombol apply filter
document.getElementById('applyFilterBtn').addEventListener('click', function() {
    const jenis = document.getElementById('filterJenis').value;
    const tahun = document.getElementById('filterTahun').value;

    const params = new URLSearchParams(window.location.search);
    if (jenis) params.set('jenis', jenis); else params.delete('jenis');
    if (tahun) params.set('tahun', tahun); else params.delete('tahun');
    params.set('page', 1);
    window.location.search = params.toString();
});

// reset filter
document.getElementById('resetFilterBtn').addEventListener('click', function() {
    document.getElementById('filterJenis').value = '';
    document.getElementById('filterTahun').value = '';

    const params = new URLSearchParams(window.location.search);
    params.delete('jenis');
    params.delete('tahun');
    params.set('page', 1);
    window.location.search = params.toString();
});
</script>


<?php
// Include footer
include_once "../layouts/master/footer.php";
?>