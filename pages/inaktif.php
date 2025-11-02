<?php
// Include header
include_once "../layouts/master/header.php";
?>

<div class="flex h-screen overflow-x-auto">
    <!-- Include sidebar -->
    <?php include_once "../layouts/components/sidebar_dynamic.php"; ?>

    <div class="flex-1 flex flex-col ml-64">
        <!-- Include topbar -->
        <?php include_once "../layouts/components/topbar.php"; ?>

        <!-- Main content -->
        <div class="p-6 mt-16 overflow-y-auto max-w-[calc(100vw-16rem)]">
            <!-- Header with search and add user button -->
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-3xl font-medium text-gray-900">Arsip Inaktif</h2>
            </div>

            <!-- Action Buttons -->
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
                                <!-- Archive 1 with 3 documents -->
                                <tr class="divide-x divide-gray-200 text-center">
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900 align-top" rowspan="3">IN-001</td>
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-500">INA-1001</td>
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900">201.1</td>
                                    <td class="px-3 py-4 text-left whitespace-nowrap">Nota Dinas Internal</td>
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900">2021-2023</td>
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900">Asli</td>
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900">2</td>
                                    <td class="px-3 py-4 text-left whitespace-nowrap text-sm text-gray-900">Sudah dipindahkan ke gudang</td>
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900">F-01/B-01</td>
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900">Gudang Arsip Rak A-1</td>
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900">10 tahun - Musnah</td>
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900">Keuangan</td>
                                    <td class="px-3 py-4 whitespace-nowrap text-center text-sm">
                                        <button class="action-button border border-gray-300 bg-white hover:bg-gray-100 rounded-md p-1 shadow-sm" title="Lihat Detail">
                                            <span class="material-symbols-outlined text-gray-700 text-xs">quick_reference_all</span>
                                        </button>
                                    </td>
                                </tr>
                                <tr class="divide-x divide-gray-200 text-center">
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-500">INA-1002</td>
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900">201.2</td>
                                    <td class="px-3 py-4 text-left whitespace-nowrap">Laporan Keuangan Triwulan</td>
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900">2022-2023</td>
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900">Copy</td>
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900">4</td>
                                    <td class="px-3 py-4 text-left whitespace-nowrap text-sm text-gray-900">Perlu pengecekan kelengkapan</td>
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900">F-01/B-02</td>
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900">Gudang Arsip Rak A-1</td>
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900">5 tahun - Permanen</td>
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900">Keuangan</td>
                                    <td class="px-3 py-4 whitespace-nowrap text-center text-sm">
                                        <button class="action-button border border-gray-300 bg-white hover:bg-gray-100 rounded-md p-1 shadow-sm" title="Lihat Detail">
                                            <span class="material-symbols-outlined text-gray-700 text-xs">quick_reference_all</span>
                                        </button>
                                    </td>
                                </tr>
                                <tr class="divide-x divide-gray-200 text-center">
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-500">INA-1003</td>
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900">201.3</td>
                                    <td class="px-3 py-4 text-left whitespace-nowrap">Dokumen Pajak Tahunan</td>
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900">2019-2021</td>
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900">Asli</td>
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900">3</td>
                                    <td class="px-3 py-4 text-left whitespace-nowrap text-sm text-gray-900">Lengkap dan tersusun rapi</td>
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900">F-01/B-03</td>
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900">Gudang Arsip Rak A-1</td>
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900">15 tahun - Musnah</td>
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900">Pajak</td>
                                    <td class="px-3 py-4 whitespace-nowrap text-center text-sm">
                                        <button class="action-button border border-gray-300 bg-white hover:bg-gray-100 rounded-md p-1 shadow-sm" title="Lihat Detail">
                                            <span class="material-symbols-outlined text-gray-700 text-xs">quick_reference_all</span>
                                        </button>
                                    </td>
                                </tr>
                                
                                <!-- Archive 2 with 2 documents -->
                                <tr class="divide-x divide-gray-200 text-center">
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900 align-top" rowspan="2">IN-002</td>
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-500">INA-1004</td>
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900">202.1</td>
                                    <td class="px-3 py-4 text-left whitespace-nowrap">Laporan Proyek Selesai</td>
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900">2020-2022</td>
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900">Copy</td>
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900">4</td>
                                    <td class="px-3 py-4 text-left whitespace-nowrap text-sm text-gray-900">Perlu pengecekan kelengkapan</td>
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900">F-02/B-01</td>
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900">Gudang Arsip Rak A-2</td>
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900">5 tahun - Permanen</td>
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900">Proyek</td>
                                    <td class="px-3 py-4 whitespace-nowrap text-center text-sm">
                                        <button class="action-button border border-gray-300 bg-white hover:bg-gray-100 rounded-md p-1 shadow-sm" title="Lihat Detail">
                                            <span class="material-symbols-outlined text-gray-700 text-xs">quick_reference_all</span>
                                        </button>
                                    </td>
                                </tr>
                                <tr class="divide-x divide-gray-200 text-center">
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-500">INA-1005</td>
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900">202.2</td>
                                    <td class="px-3 py-4 text-left whitespace-nowrap">Dokumen Kontrak Proyek</td>
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900">2019-2021</td>
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900">Asli</td>
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900">6</td>
                                    <td class="px-3 py-4 text-left whitespace-nowrap text-sm text-gray-900">Kontrak sudah selesai</td>
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900">F-02/B-02</td>
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900">Gudang Arsip Rak A-2</td>
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900">10 tahun - Musnah</td>
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900">Proyek</td>
                                    <td class="px-3 py-4 whitespace-nowrap text-center text-sm">
                                        <button class="action-button border border-gray-300 bg-white hover:bg-gray-100 rounded-md p-1 shadow-sm" title="Lihat Detail">
                                            <span class="material-symbols-outlined text-gray-700 text-xs">quick_reference_all</span>
                                        </button>
                                    </td>
                                </tr>
                                
                                <!-- Archive 3 with 1 document -->
                                <tr class="divide-x divide-gray-200 text-center">
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900">IN-003</td>
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-500">INA-1006</td>
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900">203.1</td>
                                    <td class="px-3 py-4 text-left whitespace-nowrap">Surat Masuk Eksternal</td>
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900">2018-2020</td>
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900">Copy</td>
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900">1</td>
                                    <td class="px-3 py-4 text-left whitespace-nowrap text-sm text-gray-900">Arsip lama yang sudah digitalisasi</td>
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900">F-03/B-01</td>
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900">Gudang Arsip Rak B-1</td>
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900">Permanen</td>
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900">Surat Menyurat</td>
                                    <td class="px-3 py-4 whitespace-nowrap text-center text-sm">
                                        <button class="action-button border border-gray-300 bg-white hover:bg-gray-100 rounded-md p-1 shadow-sm" title="Lihat Detail">
                                            <span class="material-symbols-outlined text-gray-700 text-xs">quick_reference_all</span>
                                        </button>
                                    </td>
                                </tr>
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


<?php
// Include footer
include_once "../layouts/master/footer.php";
?>
