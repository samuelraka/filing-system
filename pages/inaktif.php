<?php
// Include header
include_once "../layouts/master/header.php";
?>

<div class="flex h-screen bg-gray-100">
    <!-- Include sidebar -->
    <?php include_once "../layouts/components/sidebar.php"; ?>

    <div class="flex-1 flex flex-col ml-64">
        <!-- Include topbar -->
        <?php include_once "../layouts/components/topbar.php"; ?>

        <!-- Main content -->
        <div class="p-6 mt-16 overflow-y-auto flex flex-col">
            <!-- Header with search and add user button -->
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-3xl font-medium text-gray-900">Arsip Inaktif</h2>
            </div>

            <!-- Filters -->
            <div class="flex justify-between items-center mb-5">
                <div class="flex items-center gap-x-4">
                    <a href="tambah_inaktif.php" class="bg-cyan-600 hover:bg-cyan-600/90 text-white px-4 py-2 rounded-md flex items-center">
                        <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                        Tambah Arsip
                    </a>
                    <a href="#" class="bg-slate-700 hover:bg-slate-700/90 text-white px-4 py-2 rounded-md flex items-center">
                        <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 9V4a1 1 0 011-1h10a1 1 0 011 1v5m-1 6h2a2 2 0 002-2v-3a2 2 0 00-2-2H5a2 2 0 00-2 2v3a2 2 0 002 2h2m10 0v4a1 1 0 01-1 1H7a1 1 0 01-1-1v-4h10z" />
                        </svg>
                        Cetak Tabel Arsip
                    </a>
                </div>
                <div class="flex items-center gap-x-4">
                    <div class="flex items-center">
                        <button id="filtersBtn" class="border border-gray-300 bg-white text-slate-700 px-4 py-2 rounded-md flex items-center hover:bg-gray-100">
                            <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd" />
                            </svg>
                            Filters
                        </button>
                    </div>
                    <div class="relative w-64">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input type="text" id="searchInput" placeholder="Nomor Berkas, Kode Klasifikasi, Uraian Arsip, Nomor Kotak" class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-sm px-6 py-3">
                <div class="flex flex-col space-y-4">

                    <!-- Table -->
                    <div class="overflow-x-auto mt-3">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nomor Berkas</th>
                                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nomor Item Arsip</th>
                                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode Klasifikasi Arsip</th>
                                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Uraian Informasi Arsip</th>
                                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kurun Waktu</th>
                                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tingkat Perkembangan</th>
                                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Item Arsip</th>
                                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keterangan</th>
                                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nomor Definitif Folder dan Boks</th>
                                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lokasi Simpan</th>
                                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jangka Simpan dan Nasib Akhir</th>
                                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori Arsip</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200" id="userTableBody">
                                <!-- Table rows will be populated by JavaScript -->
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="flex items-center justify-between mt-4">
                        <div class="flex items-center">
                            <span class="text-sm text-gray-700 mr-2">Show</span>
                            <select id="perPage" class="border border-gray-300 rounded-md text-sm py-1 px-2">
                                <option value="12">10</option>
                                <option value="24">25</option>
                                <option value="36">50</option>
                                <option value="36">100</option>
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

<!-- Action Menu Template (Hidden by default) -->
<div id="actionMenu" class="hidden absolute bg-white shadow-lg rounded-md py-1 w-40 z-50">
    <button class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" data-action="edit">
        <svg class="inline-block w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
        </svg>
        Edit user
    </button>
    <button class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" data-action="delete">
        <svg class="inline-block w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
        </svg>
        Delete user
    </button>
    <button class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" data-action="audit">
        <svg class="inline-block w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
        </svg>
        Audit logs
    </button>
    <button class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" data-action="report">
        <svg class="inline-block w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 0l-2 2a1 1 0 101.414 1.414L8 10.414l1.293 1.293a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
        </svg>
        Reporting
    </button>
</div>

<!-- JavaScript for the page functionality -->
<script>
// Sample data for the table
const userData = [
    {
        id: 1,
        nomorBerkas: 'IN-001',
        nomorItem: 'INA-1001',
        kodeKlasifikasi: '201.1',
        uraian: 'Nota Dinas',
        kurunWaktu: '2021-2023',
        tingkatPerkembangan: 'Asli',
        jumlahItem: 2,
        keterangan: 'Sudah dipindahkan',
        nomorDefinitif: 'F-01/B-01',
        lokasiSimpan: 'Rak 1',
        jangkaSimpan: '10 tahun - Musnah',
        kategoriArsip: 'Keuangan'
    },
    {
        id: 2,
        nomorBerkas: 'IN-002',
        nomorItem: 'INA-1002',
        kodeKlasifikasi: '202.2',
        uraian: 'Laporan Proyek',
        kurunWaktu: '2020-2022',
        tingkatPerkembangan: 'Copy',
        jumlahItem: 4,
        keterangan: 'Perlu pengecekan',
        nomorDefinitif: 'F-02/B-02',
        lokasiSimpan: 'Rak 2',
        jangkaSimpan: '5 tahun - Permanen',
        kategoriArsip: 'Proyek'
    },
    {
        id: 3,
        nomorBerkas: 'IN-003',
        nomorItem: 'INA-1003',
        kodeKlasifikasi: '203.3',
        uraian: 'Dokumen Pajak',
        kurunWaktu: '2019-2021',
        tingkatPerkembangan: 'Asli',
        jumlahItem: 3,
        keterangan: 'Lengkap',
        nomorDefinitif: 'F-03/B-03',
        lokasiSimpan: 'Rak 3',
        jangkaSimpan: '15 tahun - Musnah',
        kategoriArsip: 'Pajak'
    },
    {
        id: 4,
        nomorBerkas: 'IN-004',
        nomorItem: 'INA-1004',
        kodeKlasifikasi: '204.4',
        uraian: 'Surat Masuk',
        kurunWaktu: '2018-2020',
        tingkatPerkembangan: 'Copy',
        jumlahItem: 1,
        keterangan: 'Arsip lama',
        nomorDefinitif: 'F-04/B-04',
        lokasiSimpan: 'Rak 4',
        jangkaSimpan: 'Permanen',
        kategoriArsip: 'Surat'
    },
    {
        id: 5,
        nomorBerkas: 'IN-005',
        nomorItem: 'INA-1005',
        kodeKlasifikasi: '205.5',
        uraian: 'Memo Eksternal',
        kurunWaktu: '2022',
        tingkatPerkembangan: 'Asli',
        jumlahItem: 2,
        keterangan: 'Butuh verifikasi',
        nomorDefinitif: 'F-05/B-05',
        lokasiSimpan: 'Rak 5',
        jangkaSimpan: '7 tahun - Musnah',
        kategoriArsip: 'Memo'
    }
];

// Variables for pagination
let currentPage = 1;
let itemsPerPage = 12;
let filteredData = [...userData];

// DOM elements
const tableBody = document.getElementById('userTableBody');
const paginationContainer = document.getElementById('pagination');
const prevPageBtn = document.getElementById('prevPage');
const nextPageBtn = document.getElementById('nextPage');
const perPageSelect = document.getElementById('perPage');
const searchInput = document.getElementById('searchInput');
const actionMenu = document.getElementById('actionMenu');

// Function to render table rows
function renderTable() {
    tableBody.innerHTML = '';
    
    const startIndex = (currentPage - 1) * itemsPerPage;
    const endIndex = startIndex + itemsPerPage;
    const paginatedData = filteredData.slice(startIndex, endIndex);
    
    paginatedData.forEach(user => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900">${user.nomorBerkas}</td>
            <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-500">${user.nomorItem}</td>
            <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900">${user.kodeKlasifikasi}</td>
            <td class="px-3 py-4 whitespace-nowrap">${user.uraian}</td>
            <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900">${user.kurunWaktu}</td>
            <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900">${user.tingkatPerkembangan}</td>
            <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900">${user.jumlahItem}</td>
            <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900">${user.keterangan}</td>
            <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900">${user.nomorDefinitif}</td>
            <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900">${user.lokasiSimpan}</td>
            <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900">${user.jangkaSimpan}</td>
            <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900">${user.kategoriArsip}</td>
        `;
        tableBody.appendChild(row);
    });
    
    updatePagination();
}

// Function to update pagination controls
function updatePagination() {
    const totalPages = Math.ceil(filteredData.length / itemsPerPage);
    
    // Clear existing page buttons
    const pageButtons = paginationContainer.querySelectorAll('.page-button');
    pageButtons.forEach(button => button.remove());
    
    // Add page buttons
    for (let i = 1; i <= totalPages; i++) {
        const pageButton = document.createElement('button');
        pageButton.classList.add('page-button', 'px-3', 'py-1', 'border', 'border-gray-300', 'rounded-md', 'text-sm');
        
        if (i === currentPage) {
            pageButton.classList.add('bg-blue-600', 'text-white');
        } else {
            pageButton.classList.add('hover:bg-gray-50');
        }
        
        pageButton.textContent = i;
        pageButton.addEventListener('click', () => {
            currentPage = i;
            renderTable();
        });
        
        // Insert before the next button
        paginationContainer.insertBefore(pageButton, nextPageBtn);
    }
    
    // Update prev/next buttons state
    prevPageBtn.disabled = currentPage === 1;
    nextPageBtn.disabled = currentPage === totalPages;
}

// Function to filter data
function filterData() {
    const searchTerm = searchInput.value.toLowerCase();
    filteredData = userData.filter(user => {
        // Search filter for all fields
        return (
            (user.nomorBerkas && user.nomorBerkas.toLowerCase().includes(searchTerm)) ||
            (user.nomorItem && user.nomorItem.toLowerCase().includes(searchTerm)) ||
            (user.kodeKlasifikasi && user.kodeKlasifikasi.toLowerCase().includes(searchTerm)) ||
            (user.uraian && user.uraian.toLowerCase().includes(searchTerm)) ||
            (user.kurunWaktu && user.kurunWaktu.toLowerCase().includes(searchTerm)) ||
            (user.tingkatPerkembangan && user.tingkatPerkembangan.toLowerCase().includes(searchTerm)) ||
            (user.jumlahItem && String(user.jumlahItem).toLowerCase().includes(searchTerm)) ||
            (user.keterangan && user.keterangan.toLowerCase().includes(searchTerm)) ||
            (user.nomorDefinitif && user.nomorDefinitif.toLowerCase().includes(searchTerm)) ||
            (user.lokasiSimpan && user.lokasiSimpan.toLowerCase().includes(searchTerm)) ||
            (user.jangkaSimpan && user.jangkaSimpan.toLowerCase().includes(searchTerm)) ||
            (user.kategoriArsip && user.kategoriArsip.toLowerCase().includes(searchTerm))
        );
    });
    currentPage = 1;
    renderTable();
}

// Event listeners
prevPageBtn.addEventListener('click', () => {
    if (currentPage > 1) {
        currentPage--;
        renderTable();
    }
});

nextPageBtn.addEventListener('click', () => {
    const totalPages = Math.ceil(filteredData.length / itemsPerPage);
    if (currentPage < totalPages) {
        currentPage++;
        renderTable();
    }
});

perPageSelect.addEventListener('change', () => {
    itemsPerPage = parseInt(perPageSelect.value);
    currentPage = 1;
    renderTable();
});

searchInput.addEventListener('input', filterData);

// Handle action menu
document.addEventListener('click', (e) => {
    if (!e.target.closest('.action-button') && !e.target.closest('#actionMenu')) {
        actionMenu.classList.add('hidden');
    }
});

document.addEventListener('click', (e) => {
    const actionButton = e.target.closest('.action-button');
    if (actionButton) {
        const userId = actionButton.dataset.userId;
        const rect = actionButton.getBoundingClientRect();
        
        actionMenu.style.top = `${rect.bottom + window.scrollY}px`;
        actionMenu.style.left = `${rect.left - 120 + window.scrollX}px`;
        actionMenu.classList.remove('hidden');
        
        // Store the current user ID for action handlers
        actionMenu.dataset.userId = userId;
        
        e.stopPropagation();
    }
});

// Action menu button handlers
document.querySelectorAll('#actionMenu button').forEach(button => {
    button.addEventListener('click', (e) => {
        const action = button.dataset.action;
        const userId = actionMenu.dataset.userId;
        
        // Handle different actions
        switch(action) {
            case 'edit':
                console.log(`Edit user ${userId}`);
                break;
            case 'delete':
                console.log(`Delete user ${userId}`);
                break;
            case 'audit':
                console.log(`View audit logs for user ${userId}`);
                break;
            case 'report':
                console.log(`Generate report for user ${userId}`);
                break;
        }
        
        actionMenu.classList.add('hidden');
    });
});

// Toggle switch styling
document.head.insertAdjacentHTML('beforeend', `
    <style>
        .toggle-checkbox:checked {
            right: 0;
            border-color: #68D391;
        }
        .toggle-checkbox:checked + .toggle-label {
            background-color: #68D391;
        }
    </style>
`);

// Initialize the table
renderTable();
</script>

<?php
// Include footer
include_once "../layouts/master/footer.php";
?>