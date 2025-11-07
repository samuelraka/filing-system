<?php
// Include header
include_once "../layouts/master/header.php";
include_once "../config/database.php";

// == Pagination ==
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

// === Ambil parameter pencarian & filter ===
$keyword = isset($_GET['search']) ? trim($_GET['search']) : '';
$filter_kode = isset($_GET['kode']) ? trim($_GET['kode']) : '';
$filter_tk = isset($_GET['tk']) ? trim($_GET['tk']) : '';
$filterLok = isset($_GET['lok']) ? trim($_GET['lok']) : '';
$filterKat = isset($_GET['kat']) ? trim($_GET['kat']) : '';

// === Bangun kondisi WHERE dinamis ===
$where = "WHERE 1=1";

if ($keyword !== '') {
    $keyword_safe = mysqli_real_escape_string($conn, $keyword);
    $where .= " AND (
        ia.tanggal LIKE '%$keyword_safe%' OR
        ssm.kode_subsub LIKE '%$keyword_safe%' OR
        ia.tingkat_perkembangan LIKE '%$keyword_safe%' OR
        ia.lokasi_simpan LIKE '%$keyword_safe%' OR
        ia.kategori_arsip LIKE '%$keyword_safe%'
    )";
}

if ($filter_kode !== '') {
    $kode_safe = mysqli_real_escape_string($conn, $filter_kode);
    $where .= " AND ssm.kode_subsub = '$kode_safe'";
}

if ($filter_tk !== '') {
    $tk_safe = mysqli_real_escape_string($conn, $filter_tk);
    $where .= " AND ia.tingkat_perkembangan = '$tk_safe'";
}

if ($filterLok !== '') {
    $lok_safe = mysqli_real_escape_string($conn, $filterLok);
    $where .= " AND ia.lokasi_simpan = '$lok_safe'";
}

if ($filterKat !== '') {
    $kat_safe = mysqli_real_escape_string($conn, $filterKat);
    $where .= " AND ia.kategori_arsip = '$kat_safe'";
}

// === Hitung total data untuk pagination ===
$total_result = mysqli_query($conn, "
    SELECT COUNT(*) AS total
    FROM item_arsip_inaktif ia
    LEFT JOIN arsip_inaktif aa ON ia.id_arsip = aa.id_arsip
    LEFT JOIN sub_sub_masalah ssm ON aa.id_subsub = ssm.id_subsub
    $where
");
$total_row = mysqli_fetch_assoc($total_result);
$total_data = $total_row['total'];
$total_pages = ceil($total_data / $limit);

// === Ambil data sesuai page & filter ===
$query = "
    SELECT 
        ai.id_arsip,
        ai.nomor_berkas,
        ai.jumlah_item,
        ssm.kode_subsub as kode_klasifikasi,
        ia.id_item,
        ia.nomor_item,
        ia.uraian_informasi,
        ia.kurun_waktu,
        ia.tingkat_perkembangan,
        ia.keterangan,
        ia.nomor_boks,
        ia.lokasi_simpan,
        ia.jangka_simpan,
        ia.kategori_arsip
    FROM arsip_inaktif ai
    LEFT JOIN item_arsip_inaktif ia ON ai.id_arsip = ia.id_arsip
    LEFT JOIN sub_sub_masalah ssm ON ai.id_subsub = ssm.id_subsub
    $where
    ORDER BY ai.nomor_berkas ASC, ia.nomor_item ASC
    LIMIT $limit OFFSET $offset
";
$result = mysqli_query($conn, $query);

// Ambil data dropdown untuk filter
$kode_result = mysqli_query($conn, "SELECT DISTINCT kode_subsub FROM sub_sub_masalah ORDER BY kode_subsub ASC");
$tk_result = mysqli_query($conn, "SELECT DISTINCT tingkat_perkembangan FROM item_arsip_inaktif ORDER BY tingkat_perkembangan ASC");
$lok_result = mysqli_query($conn, "SELECT DISTINCT lokasi_simpan FROM item_arsip_inaktif ORDER BY lokasi_simpan ASC");
$kat_result = mysqli_query($conn, "SELECT DISTINCT kategori_arsip FROM item_arsip_inaktif ORDER BY kategori_arsip ASC");
?>

<div class="flex h-screen overflow-x-auto">
    <!-- Include sidebar -->
    <?php include_once "../layouts/components/sidebar_dynamic.php"; ?>

    <div class="flex-1 flex flex-col ml-64">
        <!-- Include topbar -->
        <?php include_once "../layouts/components/topbar.php"; ?>

        <!-- Main content -->
        <div class="p-6 mt-16 overflow-y-auto max-w-[calc(100vw-16rem)] flex-1">
            <!-- Header with search and add user button -->
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-3xl font-medium text-gray-900">Arsip Inaktif</h2>
            </div>

            <!-- Action Buttons -->
            <!-- Filters -->
            <div class="flex justify-between items-center mb-5">
                <div class="flex items-center gap-x-4">
                    <a href="tambah_inaktif.php" class="bg-cyan-600 hover:bg-cyan-600/90 text-white px-3 py-2 gap-1 rounded-md flex items-center">
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
                    <div class="relative flex items-center">
                        <button id="filtersBtn" class="border border-gray-300 bg-white text-slate-700 px-4 py-2 rounded-md flex items-center hover:bg-gray-100">
                            <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd" />
                            </svg>
                            Filters
                        </button>
                        <!-- Anchored Dropdown -->
                        <div id="filterDropdown" class="absolute right-0 top-[calc(100%+8px)] w-80 bg-white border border-gray-200 rounded-md shadow-lg p-4 transition-transform duration-150 ease-out transform opacity-0 scale-95 pointer-events-none" style="z-index: 50;">
                            <div class="space-y-3">
                                <div>
                                    <label for="filterKode" class="block text-sm font-medium text-gray-700">Kode Klasifikasi</label>
                                    <input id="filterKode" type="text" placeholder="mis. 201.1" class="mt-1 w-full border border-gray-300 rounded-md px-2 py-1 text-sm" />
                                </div>
                                <div>
                                    <label for="filterTk" class="block text-sm font-medium text-gray-700">Tingkat Perkembangan</label>
                                    <select id="filterTk" class="mt-1 w-full border border-gray-300 rounded-md px-2 py-1 text-sm">
                                        <option value="">Semua</option>
                                        <option value="Asli">Asli</option>
                                        <option value="Copy">Copy</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="filterLok" class="block text-sm font-medium text-gray-700">Lokasi Simpan</label>
                                    <select id="filterLok" class="mt-1 w-full border border-gray-300 rounded-md px-2 py-1 text-sm">
                                        <option value="">Semua</option>
                                        <option value="Gudang Arsip Rak A-1">Gudang Arsip Rak A-1</option>
                                        <option value="Gudang Arsip Rak A-2">Gudang Arsip Rak A-2</option>
                                        <option value="Gudang Arsip Rak B-1">Gudang Arsip Rak B-1</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="filterKat" class="block text-sm font-medium text-gray-700">Kategori Arsip</label>
                                    <select id="filterKat" class="mt-1 w-full border border-gray-300 rounded-md px-2 py-1 text-sm">
                                        <option value="">Semua</option>
                                        <option value="Keuangan">Keuangan</option>
                                        <option value="Pajak">Pajak</option>
                                        <option value="Proyek">Proyek</option>
                                        <option value="Surat Menyurat">Surat Menyurat</option>
                                    </select>
                                </div>
                            </div>
                            <div class="flex justify-between mt-4">
                                <button id="resetFilters" class="text-sm px-3 py-2 border border-gray-300 rounded-md bg-white hover:bg-gray-50">Reset</button>
                                <button id="applyFilters" class="text-sm px-3 py-2 rounded-md bg-[#0092B8] text-white hover:bg-[#0092B8]/90">Terapkan</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm px-6 py-3 overflow-x-auto max-w-[calc(100vw-16rem)]">
                <div class="flex flex-col space-y-4">
                    <!-- Table -->
                    <div class="overflow-x-auto mt-3">
                        <table class="max-w-screen border border-gray-200 divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr class="divide-x divide-gray-200 text-center">
                                    <th class="px-3 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Nomor Berkas</th>
                                    <th class="px-3 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Nomor Item Arsip</th>
                                    <th class="px-3 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Kode Klasifikasi Arsip</th>
                                    <th class="px-3 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Uraian Informasi Arsip</th>
                                    <th class="px-3 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Kurun Waktu</th>
                                    <th class="px-3 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Tingkat Perkembangan</th>
                                    <th class="px-3 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Item Arsip</th>
                                    <th class="px-3 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Keterangan</th>
                                    <th class="px-3 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Nomor Definitif Folder dan Boks</th>
                                    <th class="px-3 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Lokasi Simpan</th>
                                    <th class="px-3 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Jangka Simpan dan Nasib Akhir</th>
                                    <th class="px-3 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori Arsip</th>
                                    <th class="px-3 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Opsi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php
                                require_once "../config/database.php";

                                // Ambil data arsip_inaktif dan item_arsip_inaktif
                                $query = "
                                    SELECT 
                                        ai.id_arsip,
                                        ai.nomor_berkas,
                                        ai.jumlah_item,
                                        ssm.kode_subsub as kode_klasifikasi,
                                        ia.id_item,
                                        ia.nomor_item,
                                        ia.uraian_informasi,
                                        ia.kurun_waktu,
                                        ia.tingkat_perkembangan,
                                        ia.keterangan,
                                        ia.nomor_boks,
                                        ia.lokasi_simpan,
                                        ia.jangka_simpan,
                                        ia.kategori_arsip
                                    FROM arsip_inaktif ai
                                    LEFT JOIN item_arsip_inaktif ia ON ai.id_arsip = ia.id_arsip
                                    LEFT JOIN sub_sub_masalah ssm ON ai.id_subsub = ssm.id_subsub
                                    ORDER BY ai.nomor_berkas ASC, ia.nomor_item ASC
                                ";

                                $result = mysqli_query($conn, $query);

                                if (!$result) {
                                    echo "<tr><td colspan='13' class='text-center text-red-500 py-4'>Gagal mengambil data: " . mysqli_error($conn) . "</td></tr>";
                                    exit;
                                }

                                // Kelompokkan data berdasarkan nomor_berkas
                                $arsip_data = [];
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $arsip_data[$row['nomor_berkas']][] = $row;
                                }

                                foreach ($arsip_data as $nomor_berkas => $items) {
                                    $rowspan = count($items);
                                    $first = true;

                                    foreach ($items as $item) {
                                        echo "<tr class='divide-x divide-gray-200 text-center'>";

                                        // Nomor Berkas rowspan
                                        if ($first) {
                                            echo "<td class='px-3 py-4 whitespace-nowrap text-sm text-gray-900 align-top font-medium' rowspan='{$rowspan}'>" . htmlspecialchars($nomor_berkas) . "</td>";
                                            $first = false;
                                        }

                                        echo "
                                            <td class='px-3 py-4 whitespace-nowrap text-sm text-gray-500'>" . htmlspecialchars($item['nomor_item']) . "</td>
                                            <td class='px-3 py-4 whitespace-nowrap text-sm text-gray-900'>" . htmlspecialchars($item['kode_klasifikasi']) . "</td>
                                            <td class='px-3 py-4 whitespace-nowrap text-left text-sm text-gray-900'>" . htmlspecialchars($item['uraian_informasi']) . "</td>
                                            <td class='px-3 py-4 whitespace-nowrap text-sm text-gray-900'>" . htmlspecialchars($item['kurun_waktu']) . "</td>
                                            <td class='px-3 py-4 whitespace-nowrap text-sm text-gray-900'>" . htmlspecialchars($item['tingkat_perkembangan']) . "</td>
                                            <td class='px-3 py-4 whitespace-nowrap text-sm text-gray-900'>" . htmlspecialchars($item['jumlah_item']) . "</td>
                                            <td class='px-3 py-4 whitespace-nowrap text-sm text-gray-900'>" . htmlspecialchars($item['keterangan']) . "</td>
                                            <td class='px-3 py-4 whitespace-nowrap text-sm text-gray-900'>" . htmlspecialchars($item['nomor_boks']) . "</td>
                                            <td class='px-3 py-4 whitespace-nowrap text-sm text-gray-900'>" . htmlspecialchars($item['lokasi_simpan']) . "</td>
                                            <td class='px-3 py-4 whitespace-nowrap text-sm text-gray-900'>" . htmlspecialchars($item['jangka_simpan']) . "</td>
                                            <td class='px-3 py-4 whitespace-nowrap text-sm text-gray-900'>" . htmlspecialchars($item['kategori_arsip']) . "</td>
                                            <td class='px-3 py-4 whitespace-nowrap text-center text-sm font-medium'>
                                                <a href='detail_inaktif.php?id=" . urlencode($item['id_item']) . "' class='border border-gray-300 inline-flex bg-white hover:bg-gray-100 rounded-md p-1 shadow-sm' title='Lihat Detail'>
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
        filterDropdown.classList.remove('hidden', 'opacity-0', 'translate-y-2', 'pointer-events-none');
        filterDropdown.classList.add('opacity-100', 'translate-y-0');
    } else {
        filterDropdown.classList.add('hidden', 'opacity-0', 'translate-y-2', 'pointer-events-none');
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
document.getElementById('applyFilters').addEventListener('click', () => {
    const kode = document.getElementById('filterKode').value.trim();
    const lokasi = document.getElementById('filterLok').value;
    const kategori = document.getElementById('filterKat').value;
    const params = new URLSearchParams(window.location.search);

    // set atau hapus parameter sesuai input
    if(kode) params.set('kode', kode); else params.delete('kode');
    if(lokasi) params.set('lokasi', lokasi); else params.delete('lokasi');
    if(kategori) params.set('kategori', kategori); else params.delete('kategori');

    params.set('page', 1); // reset ke halaman 1
    window.location.search = params.toString();
});

// Reset filter button
document.getElementById('resetFilters').addEventListener('click', () => {
    document.getElementById('filterKode').value = '';
    document.getElementById('filterTk').value = '';
    document.getElementById('filterLok').value = '';
    document.getElementById('filterKat').value = '';

    const params = new URLSearchParams(window.location.search);
    params.delete('kode');
    params.delete('lokasi');
    params.delete('kategori');
    params.delete('tk');
    params.set('page', 1);

    window.location.search = params.toString();
});
</script>

<?php
// Include footer
include_once "../layouts/master/footer.php";
?>
