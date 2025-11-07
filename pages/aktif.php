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
        ssm.kode_subsub AS kode_klasifikasi,
        ia.id_item,
        ia.nomor_item,
        ia.tanggal,
        ia.keterangan_skaad,
        ia.uraian_informasi
    FROM item_arsip ia
    LEFT JOIN arsip_aktif aa ON ia.id_arsip = aa.id_arsip
    LEFT JOIN sub_sub_masalah ssm ON aa.id_subsub = ssm.id_subsub
    $where
    ORDER BY aa.nomor_berkas ASC, ia.nomor_item ASC
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
                        <input type="text" id="searchInput" placeholder="Nomor Berkas, Kode Klasifikasi, Uraian Arsip, Nomor Kotak" class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#0092B8]">
                    </div>
                    <div class="relative">
                        <button id="filtersBtn" class="border border-gray-300 bg-white text-slate-700 px-4 py-2 rounded-md flex items-center hover:bg-gray-100">
                            <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd" />
                            </svg>
                            Filters
                        </button>
                        <!-- Dropdown filter anchored under button -->
                        <div id="filterDropdown" class="hidden absolute top-full right-0 mt-2 z-50 bg-white border border-gray-200 rounded-md shadow p-4 w-[320px] transition ease-out duration-200 transform origin-top-right opacity-0 translate-y-2">
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
                            <div>
                                <label for="filterTahun" class="block text-sm text-gray-700 font-medium">Tahun</label>
                                <select id="filterTahun" class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2">
                                    <option value="">Semua</option>
                                    <option value="2024">2024</option>
                                    <option value="2023">2023</option>
                                    <option value="2022">2022</option>
                                    <option value="2021">2021</option>
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
                                    <th class="px-3 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Nomor Berkas</th>
                                    <th class="px-3 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Nomor Item Arsip</th>
                                    <th class="px-3 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Kode Klasifikasi Arsip</th>
                                    <th class="px-3 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Uraian Informasi Arsip</th>
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
                                        ssm.kode_subsub AS kode_klasifikasi,
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
                                    $where
                                    ORDER BY aa.nomor_berkas ASC, ia.nomor_item ASC
                                    LIMIT $limit OFFSET $offset
                                ";

                                $result = mysqli_query($conn, $query);

                                if (!$result) {
                                    echo "<tr><td colspan='9' class='text-center text-red-500 py-4'>Gagal mengambil data: " . mysqli_error($conn) . "</td></tr>";
                                    exit;
                                }

                                // Kelompokkan berdasarkan nomor_berkas
                                $arsip_data = [];
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $arsip_data[$row['nomor_berkas']][] = $row;
                                }

                                // Loop tampilkan data
                                foreach ($arsip_data as $nomor_berkas => $items) {
                                    $rowspan = count($items);
                                    $first = true;

                                    foreach ($items as $item) {
                                        echo "<tr class='divide-x divide-gray-200 text-center'>";

                                        // Kolom nomor berkas hanya tampil sekali di atas item pertama
                                        if ($first) {
                                            echo "<td class='px-3 py-4 whitespace-nowrap text-sm text-gray-900 align-top font-medium' rowspan='{$rowspan}'>" . htmlspecialchars($nomor_berkas) . "</td>";
                                            $first = false;
                                        }

                                        echo "
                                            <td class='px-3 py-4 whitespace-nowrap text-sm text-gray-500'>" . htmlspecialchars($item['nomor_item']) . "</td>
                                            <td class='px-3 py-4 whitespace-nowrap text-sm text-gray-900'>" . htmlspecialchars($item['kode_klasifikasi']) . "</td>
                                            <td class='px-3 py-4 whitespace-nowrap text-sm text-left text-gray-900'>" . htmlspecialchars($item['uraian_informasi']) . "</td>
                                            <td class='px-3 py-4 whitespace-nowrap text-sm text-gray-900'>" . htmlspecialchars($item['tanggal']) . "</td>
                                            <td class='px-3 py-4 whitespace-nowrap text-sm text-gray-900'>" . htmlspecialchars($items[0]['jumlah_item']) . "</td>
                                            <td class='px-3 py-4 whitespace-nowrap text-center'>
                                                <span class='bg-green-50 text-green-600 px-2 py-1 rounded-full text-sm'>" . htmlspecialchars($item['keterangan_skaad']) . "</span>
                                            </td>
                                            <td class='px-3 py-4 whitespace-nowrap text-sm text-gray-900'>" . htmlspecialchars($items[0]['keterangan_berkas']) . "</td>
                                            <td class='px-3 py-4 whitespace-nowrap text-center text-sm font-medium'>
                                                <a href='detail_aktif.php?id=" . urlencode($item['id_item']) . "' class='border border-gray-300 inline-flex bg-white hover:bg-gray-100 rounded-md p-1 shadow-sm' title='Lihat Detail'>
                                                    <span class='material-symbols-outlined text-gray-700 text-xs'>quick_reference_all</span>
                                                </a>
                                            </td>
                                        ";

                                        echo "</tr>";
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

filtersBtn.addEventListener('click', () => {
    const isHidden = filterDropdown.classList.contains('hidden');
    if(isHidden){
        filterDropdown.classList.remove('hidden', 'opacity-0', 'translate-y-2');
        filterDropdown.classList.add('opacity-100', 'translate-y-0');
    } else {
        filterDropdown.classList.add('hidden', 'opacity-0', 'translate-y-2');
        filterDropdown.classList.remove('opacity-100', 'translate-y-0');
    }
});

// Search by Enter
document.getElementById('searchInput').addEventListener('keypress', function(e){
    if(e.key === 'Enter'){
        const search = e.target.value.trim();
        const params = new URLSearchParams(window.location.search);
        params.set('search', search);
        params.set('page', 1); // reset halaman
        window.location.search = params.toString();
    }
});

// Apply filter button
document.getElementById('applyFilterBtn').addEventListener('click', () => {
    const kode = document.getElementById('filterKode').value.trim();
    const skaad = document.getElementById('filterSkaad').value;
    const tahun = document.getElementById('filterTahun').value;
    const params = new URLSearchParams(window.location.search);

    // set atau hapus parameter sesuai input
    if(kode) params.set('kode', kode); else params.delete('kode');
    if(skaad) params.set('skaad', skaad); else params.delete('skaad');
    if(tahun) params.set('tahun', tahun); else params.delete('tahun');

    params.set('page', 1); // reset ke halaman 1
    window.location.search = params.toString();
});

// Reset filter button
document.getElementById('resetFilterBtn').addEventListener('click', () => {
    document.getElementById('filterKode').value = '';
    document.getElementById('filterSkaad').value = '';
    document.getElementById('filterTahun').value = '';

    const params = new URLSearchParams(window.location.search);
    params.delete('kode');
    params.delete('skaad');
    params.delete('tahun');
    params.set('page', 1);

    window.location.search = params.toString();
});
</script>

<?php
// Include footer
include_once "../layouts/master/footer.php";
?>
