<?php
include_once "../layouts/master/header.php";
include_once "../config/database.php";

// === Pagination Config ===
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

// === Ambil parameter pencarian & filter ===
$keyword = isset($_GET['search']) ? trim($_GET['search']) : '';
$filter_kode = isset($_GET['kode']) ? trim($_GET['kode']) : '';
$filter_skaad = isset($_GET['skaad']) ? trim($_GET['skaad']) : '';
$filter_tahun = isset($_GET['tahun']) ? trim($_GET['tahun']) : '';
$filter_dari = isset($_GET['date_from']) ? trim($_GET['date_from']) : '';
$filter_sampai = isset($_GET['date_to']) ? trim($_GET['date_to']) : '';

// === Bangun kondisi WHERE dinamis ===
$where = "WHERE 1=1";

if ($keyword !== '') {
    $keyword_safe = mysqli_real_escape_string($conn, $keyword);
    $where .= " AND (
        ia.tanggal LIKE '%$keyword_safe%' OR
        ssm.kode_subsub LIKE '%$keyword_safe%' OR
        ia.keterangan_skaad LIKE '%$keyword_safe%'
    )";
}

if ($filter_kode !== '') {
    $kode_safe = mysqli_real_escape_string($conn, $filter_kode);
    $where .= " AND ssm.kode_subsub = '$kode_safe'";
}

if ($filter_skaad !== '') {
    $skaad_safe = mysqli_real_escape_string($conn, $filter_skaad);
    $where .= " AND ia.keterangan_skaad = '$skaad_safe'";
}

// Filter tahun (berdasarkan YEAR dari kolom tanggal)
if ($filter_tahun !== '') {
    $tahun_int = intval($filter_tahun);
    if ($tahun_int > 0) {
        $where .= " AND YEAR(ia.tanggal) = $tahun_int";
    }
}

// Filter rentang tanggal
if ($filter_dari !== '') {
    $from_safe = mysqli_real_escape_string($conn, $filter_dari);
    $where .= " AND ia.tanggal >= '$from_safe'";
}
if ($filter_sampai !== '') {
    $to_safe = mysqli_real_escape_string($conn, $filter_sampai);
    $where .= " AND ia.tanggal <= '$to_safe'";
}

// === Hitung total data untuk pagination ===
$total_result = mysqli_query($conn, "
    SELECT COUNT(*) AS total
    FROM item_arsip ia
    LEFT JOIN arsip_aktif aa ON ia.id_arsip = aa.id_arsip
    LEFT JOIN sub_sub_masalah ssm ON aa.id_subsub = ssm.id_subsub
    $where
");
$total_row = mysqli_fetch_assoc($total_result);
$total_data = $total_row['total'];
$total_pages = ceil($total_data / $limit);

// === Ambil data sesuai page & filter ===
$query = "
    SELECT 
        aa.id_arsip,
        aa.nomor_berkas,
        aa.jumlah_item,
        aa.keterangan AS keterangan_berkas,
        CONCAT(pm.kode_pokok, '.', sm.kode_sub, '.', ssm.kode_subsub) AS kode_klasifikasi,
        ia.id_item,
        ia.nomor_item,
        ia.tanggal,
        ia.keterangan_skaad,
        ia.uraian_informasi
    FROM item_arsip ia
    LEFT JOIN arsip_aktif aa ON ia.id_arsip = aa.id_arsip
    LEFT JOIN sub_sub_masalah ssm ON aa.id_subsub = ssm.id_subsub
    LEFT JOIN sub_masalah sm ON ssm.id_sub = sm.id_sub
    LEFT JOIN pokok_masalah pm ON sm.id_pokok = pm.id_pokok
    $where
    ORDER BY ssm.kode_subsub ASC, aa.nomor_berkas ASC, ia.nomor_item ASC
    LIMIT $limit OFFSET $offset
";
$result = mysqli_query($conn, $query);

// Ambil data dropdown untuk filter
$kode_result = mysqli_query($conn, "SELECT DISTINCT kode_subsub FROM sub_sub_masalah ORDER BY kode_subsub ASC");
$skaad_result = mysqli_query($conn, "SELECT DISTINCT keterangan_skaad FROM item_arsip ORDER BY keterangan_skaad ASC");
?>

<div class="flex h-screen">
    <!-- Include sidebar -->
    <?php include_once "../layouts/components/sidebar_dynamic.php"; ?>

    <div class="flex-1 flex flex-col ml-64">
        <!-- Include topbar -->
        <?php include_once "../layouts/components/topbar.php"; ?>

        <!-- Main content -->
        <div class="p-6 mt-16 overflow-y-auto">
            <!-- Header with search and add user button -->
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-3xl font-medium text-slate-700">Arsip Aktif</h2>
            </div>

            <!-- Filters -->
            <div class="flex justify-between items-center mb-5">
                <div class="flex items-center gap-x-4">
                    <a href="tambah_aktif.php" class="bg-cyan-600 hover:bg-cyan-600/90 text-white px-3 py-2 gap-1 rounded-md flex items-center">
                        <span class="material-symbols-outlined">add</span>
                        <span>Tambah Arsip</span>
                    </a>
                    <a href="../api/arsip/arsip_aktif/export_excel.php?search=<?= urlencode($keyword) ?>&kode=<?= urlencode($filter_kode) ?>&skaad=<?= urlencode($filter_skaad) ?>&tahun=<?= urlencode($filter_tahun) ?>" class="bg-slate-700 hover:bg-slate-700/90 text-white px-3 py-2 gap-2 rounded-md flex items-center" target="_blank" rel="noopener">
                        <span class="material-symbols-outlined">print</span>
                        <span>Cetak Tabel Arsip</span>
                    </a>
                </div>
                <div class="flex items-center gap-x-4">
                    <div class="relative flex items-center">
                        <button id="filtersBtn" class="border border-gray-300 bg-white text-slate-700 px-4 py-2 rounded-md flex items-center hover:bg-gray-100">
                            <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd" />
                            </svg>
                            Filters
                        </button>
                        <!-- Dropdown filter anchored under button -->
                        <div id="filterDropdown" class="hidden fixed z-50 bg-white border border-gray-200 rounded-md shadow p-4 w-[320px] transition ease-out duration-200 transform opacity-0" style="left:0;top:0;">
                            <div class="mb-3">
                                <label for="filterKode" class="block text-sm text-gray-700 font-medium">Kode Klasifikasi</label>
                                <input id="filterKode" type="text" placeholder="mis. 101.2" class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2" />
                            </div>
                            <div class="mb-3">
                                <label for="filterSkaad" class="block text-sm text-gray-700 font-medium">Keterangan SKAAD</label>
                                <select id="filterSkaad" class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2">
                                    <option value="">Semua</option>
                                    <option value="Biasa">Biasa</option>
                                    <option value="Terbatas">Terbatas</option>
                                    <option value="Rahasia">Rahasia</option>
                                </select>
                            </div>
                            <div id="dateFilterGroup" class="space-y-2">
                                <div class="flex items-center gap-2">
                                    <button type="button" id="yearModeBtn" class="border border-gray-300 bg-white text-slate-700 px-2 py-1 rounded-md">Tahun</button>
                                    <button type="button" id="rangeModeBtn" class="border border-gray-300 bg-white text-slate-700 px-2 py-1 rounded-md">Rentang Tanggal</button>
                                </div>
                                <div id="yearMode" class="border border-gray-300 rounded-md p-2">
                                    <label for="filterTahun" class="block text-sm text-gray-700 font-medium">Tahun</label>
                                    <input id="filterTahun" type="text" placeholder="mis. 2024" class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2" />
                                </div>
                                <div id="rangeMode" class="hidden border border-gray-300 rounded-md p-2">
                                    <label for="filterDateFrom" class="block text-sm text-gray-700 font-medium">Dari</label>
                                    <input id="filterDateFrom" type="date" class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2" />
                                    <label for="filterDateTo" class="block text-sm text-gray-700 font-medium mt-2">Sampai</label>
                                    <input id="filterDateTo" type="date" class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2" />
                                </div>
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
                                    <th class="px-3 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Kode Klasifikasi Arsip</th>
                                    <th class="px-3 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Nomor Berkas</th>
                                    <th class="px-3 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Nomor Item Arsip</th>
                                    <th class="px-3 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Uraian Singkat Arsip</th>
                                    <th class="px-3 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                    <th class="px-3 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Item Arsip</th>
                                    <th class="px-3 py-3 text-xs font-medium text-gray-500 uppercase tracking-tight">Keterangan SKAAD</th>
                                    <th class="px-3 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Keterangan</th>
                                    <th class="px-3 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Opsi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php
                                require_once "../config/database.php";

                                // Ambil data gabungan arsip & item arsip
                                $query = "
                                    SELECT 
                                        aa.id_arsip,
                                        aa.nomor_berkas,
                                        aa.jumlah_item,
                                        aa.keterangan AS keterangan_berkas,
                                        CONCAT(pm.kode_pokok, '.', sm.kode_sub, '.', ssm.kode_subsub) AS kode_klasifikasi,
                                        ia.id_item,
                                        ia.nomor_item,
                                        ia.tanggal,
                                        ia.keterangan_skaad,
                                        ia.uraian_singkat,
                                        ia.uraian_informasi,
                                        ia.file_path
                                    FROM arsip_aktif aa
                                    LEFT JOIN item_arsip ia ON aa.id_arsip = ia.id_arsip
                                    LEFT JOIN sub_sub_masalah ssm ON aa.id_subsub = ssm.id_subsub
                                    LEFT JOIN sub_masalah sm ON ssm.id_sub = sm.id_sub
                                    LEFT JOIN pokok_masalah pm ON sm.id_pokok = pm.id_pokok
                                    $where
                                    ORDER BY ssm.kode_subsub ASC, aa.nomor_berkas ASC, ia.nomor_item ASC
                                    LIMIT $limit OFFSET $offset
                                ";

                                $result = mysqli_query($conn, $query);

                                if (!$result) {
                                    echo "<tr><td colspan='9' class='text-center text-red-500 py-4'>Gagal mengambil data: " . mysqli_error($conn) . "</td></tr>";
                                    exit;
                                }

                                $rows = [];
                                while ($row = mysqli_fetch_assoc($result)) { $rows[] = $row; }

                                if (count($rows) === 0) {
                                    echo "<tr><td colspan='9' class='text-center py-4 text-gray-500'>Tidak ada data arsip aktif.</td></tr>";
                                } else {
                                    $kodeRowspanCounts = [];
                                    foreach ($rows as $r) {
                                        $k = isset($r['kode_klasifikasi']) ? $r['kode_klasifikasi'] : '';
                                        if (!isset($kodeRowspanCounts[$k])) $kodeRowspanCounts[$k] = 0;
                                        $kodeRowspanCounts[$k]++;
                                    }

                                    $lastKode = null;
                                    $lastComposite = null;
                                    foreach ($rows as $item) {
                                        $composite = ($item['nomor_berkas'] ?? '') . '|' . ($item['kode_klasifikasi'] ?? '');
                                        $currentKode = isset($item['kode_klasifikasi']) ? $item['kode_klasifikasi'] : '';
                                        echo "<tr class='divide-x divide-gray-200 text-center'>";

                                        if ($lastKode === null || $lastKode !== $currentKode) {
                                            $rowspan = isset($kodeRowspanCounts[$currentKode]) ? $kodeRowspanCounts[$currentKode] : 1;
                                            echo "<td class='px-3 py-4 whitespace-nowrap text-sm text-gray-900' rowspan='" . intval($rowspan) . "'>" . htmlspecialchars($currentKode) . "</td>";
                                        }

                                        if ($lastComposite === null || $lastComposite !== $composite) {
                                            echo "<td class='px-3 py-4 whitespace-nowrap text-sm text-gray-900 font-medium'>" . htmlspecialchars($item['nomor_berkas']) . "</td>";
                                        } else {
                                            echo "<td class='px-3 py-4 whitespace-nowrap text-sm text-gray-900'></td>";
                                        }

                                        echo "
                                            <td class='px-3 py-4 whitespace-nowrap text-sm text-gray-500'>" . htmlspecialchars($item['nomor_item']) . "</td>
                                            <td class='px-3 py-4 whitespace-nowrap text-sm text-left text-gray-900'>" . htmlspecialchars($item['uraian_singkat'] ?? '') . "</td>
                                            <td class='px-3 py-4 whitespace-nowrap text-sm text-gray-900'>" . ($item['tanggal'] ? date('d-m-Y', strtotime($item['tanggal'])) : '') . "</td>
                                            <td class='px-3 py-4 whitespace-nowrap text-sm text-gray-900'>" . htmlspecialchars($item['jumlah_item']) . "</td>
                                            <td class='px-3 py-4 whitespace-nowrap text-center'>
                                                " . ("<span class='" . (($item['keterangan_skaad']==='Biasa') ? 'bg-blue-50 text-blue-600' : (($item['keterangan_skaad']==='Terbatas') ? 'bg-yellow-50 text-yellow-600' : 'bg-red-50 text-red-600')) . " px-2 py-1 rounded-full text-sm'>" . htmlspecialchars($item['keterangan_skaad']) . "</span>") . "
                                            </td>
                                            <td class='px-3 py-4 whitespace-nowrap text-sm text-gray-900'>" . htmlspecialchars($item['keterangan_berkas']) . "</td>
                                            <td class='px-3 py-4 whitespace-nowrap text-center text-sm font-medium'>
                                                <a href='detail_aktif.php?id=" . urlencode($item['id_item']) . "' class='border border-gray-300 inline-flex bg-white hover:bg-gray-100 rounded-md p-1 shadow-sm' title='Lihat Detail'>
                                                    <span class='material-symbols-outlined text-gray-700 text-xs'>quick_reference_all</span>
                                                </a>
                                                " . (isAdminOrSuperAdmin() ? "<a href='../api/arsip/arsip_aktif/delete_aktif.php?id=" . urlencode($item['id_item']) . "' class='border border-red-300 inline-flex bg-white hover:bg-red-50 rounded-md p-1 shadow-sm ml-2' title='Hapus'>
                                                    <span class='material-symbols-outlined text-red-600 text-xs'>delete</span>
                                                </a>" : "") . "
                                            </td>
                                        ";

                                        echo "</tr>";
                                        $lastComposite = $composite;
                                        $lastKode = $currentKode;
                                    }
                                }
                                ?>
                                </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="flex items-center justify-between mt-4">
                        <div class="flex items-center">
                            <span class="text-sm text-gray-700 mr-2">Show</span>
                            <select id="perPage" class="border border-gray-300 rounded-full-md text-sm py-1 px-2">
                                <option value="12">12</option>
                                <option value="24">24</option>
                                <option value="36">36</option>
                                <option value="48">48</option>
                            </select>
                        </div>
                        <div class="flex items-center space-x-1" id="pagination">
                            <button class="px-2 py-1 border border-gray-300 rounded-md text-sm disabled:opacity-50" id="prevPage">
                                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </button>
                            <!-- Page buttons will be added by JavaScript -->
                            <button class="px-2 py-1 border border-gray-300 rounded-md text-sm disabled:opacity-50" id="nextPage">
                                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Toggle dropdown filter
const filtersBtn = document.getElementById('filtersBtn');
const filterDropdown = document.getElementById('filterDropdown');
const yearModeBtn = document.getElementById('yearModeBtn');
const rangeModeBtn = document.getElementById('rangeModeBtn');
const yearMode = document.getElementById('yearMode');
const rangeMode = document.getElementById('rangeMode');
function setActiveMode(mode){
    if(mode==='range'){
        yearMode.classList.add('hidden');
        rangeMode.classList.remove('hidden');
        yearModeBtn.classList.remove('ring-2','ring-cyan-500','border-cyan-500');
        rangeModeBtn.classList.add('ring-2','ring-cyan-500','border-cyan-500');
    } else {
        rangeMode.classList.add('hidden');
        yearMode.classList.remove('hidden');
        rangeModeBtn.classList.remove('ring-2','ring-cyan-500','border-cyan-500');
        yearModeBtn.classList.add('ring-2','ring-cyan-500','border-cyan-500');
    }
}

function prefillFiltersFromParams(){
    const params = new URLSearchParams(window.location.search);
    const kode = params.get('kode') || '';
    const skaad = params.get('skaad') || '';
    const tahun = params.get('tahun') || '';
    const dari = params.get('date_from') || '';
    const sampai = params.get('date_to') || '';
    const kodeEl = document.getElementById('filterKode');
    const skaadEl = document.getElementById('filterSkaad');
    const tahunEl = document.getElementById('filterTahun');
    const dariEl = document.getElementById('filterDateFrom');
    const sampaiEl = document.getElementById('filterDateTo');
    if(kodeEl) kodeEl.value = kode;
    if(skaadEl) skaadEl.value = skaad;
    if(tahunEl) tahunEl.value = tahun;
    if(dariEl) dariEl.value = dari;
    if(sampaiEl) sampaiEl.value = sampai;
    const useRange = (dari || sampai) && !(tahun);
    setActiveMode(useRange ? 'range' : 'year');
}

filtersBtn.addEventListener('click', () => {
    const isHidden = filterDropdown.classList.contains('hidden');
    if(isHidden){
        prefillFiltersFromParams();
        const rect = filtersBtn.getBoundingClientRect();
        const dw = filterDropdown.offsetWidth || 320;
        let left = rect.right - dw;
        left = Math.max(8, Math.min(left, window.innerWidth - dw - 8));
        const top = rect.bottom + 8;
        filterDropdown.style.left = left + 'px';
        filterDropdown.style.top = top + 'px';
        filterDropdown.classList.remove('hidden', 'opacity-0', 'translate-y-2');
        filterDropdown.classList.add('opacity-100');
    } else {
        filterDropdown.classList.add('hidden', 'opacity-0');
        filterDropdown.classList.remove('opacity-100');
    }
});

yearModeBtn.addEventListener('click', () => {
    setActiveMode('year');
});
rangeModeBtn.addEventListener('click', () => {
    setActiveMode('range');
});

// Prefill saat halaman pertama kali dimuat
document.addEventListener('DOMContentLoaded', prefillFiltersFromParams);

// Apply filter button
document.getElementById('applyFilterBtn').addEventListener('click', () => {
    const kode = document.getElementById('filterKode').value.trim();
    const skaad = document.getElementById('filterSkaad').value;
    const tahun = document.getElementById('filterTahun').value.trim();
    const dari = document.getElementById('filterDateFrom').value;
    const sampai = document.getElementById('filterDateTo').value;
    const params = new URLSearchParams(window.location.search);

    // set atau hapus parameter sesuai input
    if(kode) params.set('kode', kode); else params.delete('kode');
    if(skaad) params.set('skaad', skaad); else params.delete('skaad');
    if(!rangeMode.classList.contains('hidden')){
        if(dari) params.set('date_from', dari); else params.delete('date_from');
        if(sampai) params.set('date_to', sampai); else params.delete('date_to');
        params.delete('tahun');
    } else {
        if(tahun) params.set('tahun', tahun); else params.delete('tahun');
        params.delete('date_from');
        params.delete('date_to');
    }

    params.set('page', 1); // reset ke halaman 1
    window.location.search = params.toString();
});

// Reset filter button
document.getElementById('resetFilterBtn').addEventListener('click', () => {
    document.getElementById('filterKode').value = '';
    document.getElementById('filterSkaad').value = '';
    document.getElementById('filterTahun').value = '';
    const dariEl = document.getElementById('filterDateFrom');
    const sampaiEl = document.getElementById('filterDateTo');
    if(dariEl) dariEl.value = '';
    if(sampaiEl) sampaiEl.value = '';

    const params = new URLSearchParams(window.location.search);
    params.delete('kode');
    params.delete('skaad');
    params.delete('tahun');
    params.delete('date_from');
    params.delete('date_to');
    params.set('page', 1);

    window.location.search = params.toString();
});
</script>

<?php
// Include footer
include_once "../layouts/master/footer.php";
?>
