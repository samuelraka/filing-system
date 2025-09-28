<?php
// dashboard.php
// Dashboard page for archiving system

// Dummy data for now (replace with DB queries later)
$activeArchives = 12;
$inactiveArchives = 7;
$monthlyArchiveStats = [
    '2025-09' => 5,
    '2025-08' => 8,
    '2025-07' => 3,
    '2025-06' => 6,
    '2025-05' => 9,
];

include __DIR__ . '/layouts/master/header.php';
?>
<div class="min-h-screen bg-[#fafbfc] flex overflow-hidden">
    <!-- Sidebar - Fixed -->
    <aside class="w-64 bg-white border-r border-gray-200 flex flex-col py-6 px-4 h-screen fixed left-0 top-0">
        <div class="flex items-center gap-2 mb-8 px-2">
            <span class="material-icons text-orange-500">inventory_2</span>
            <span class="font-bold text-lg tracking-wide">ArsipOnline</span>
        </div>
        <nav class="flex-1 space-y-2 overflow-y-auto">
            <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-lg bg-orange-50 text-orange-600 font-medium"><span class="material-icons">dashboard</span>Dashboard</a>
            <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-100"><span class="material-icons">folder</span>Semua Arsip</a>
            <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-100"><span class="material-icons">history</span>Riwayat</a>
            <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-100"><span class="material-icons">settings</span>Pengaturan</a>
        </nav>
        <div class="mt-8 border-t pt-4">
            <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-lg text-red-500 hover:bg-red-50"><span class="material-icons">logout</span>Keluar</a>
        </div>
    </aside>
    <!-- Main Content -->
    <div class="flex-1 flex flex-col ml-64">
        <!-- Top Navbar - Fixed -->
        <header class="bg-white border-b border-gray-200 px-8 py-4 flex items-center justify-end fixed top-0 right-0 left-64 z-10">
            <div class="flex items-center gap-3 ml-6">
                <span class="font-semibold">Ulum</span>
                <img src="https://ui-avatars.com/api/?name=Ulum" class="w-9 h-9 rounded-full border" />
            </div>
        </header>
        <!-- Dashboard Content - Scrollable -->
        <main class="flex-1 p-8 space-y-8 mt-16 overflow-y-auto">
            <!-- Header -->
            <div class="flex justify-between items-center">
                <div class="font-bold text-xl">Dashboard</div>
                <div class="flex gap-3 items-center">
                    <button class="bg-orange-50 text-orange-600 px-4 py-2 rounded-lg font-semibold border border-orange-100 hover:bg-orange-100">Lihat Arsip Terbaru</button>
                    <button class="bg-orange-500 text-white px-4 py-2 rounded-lg font-semibold hover:bg-orange-600 flex items-center gap-2"><span class="material-icons text-sm">add</span>Tambah Arsip</button>
                </div>
            </div>
            <!-- Stats Cards -->
            <section class="grid grid-cols-4 gap-6">
                <div class="bg-white rounded-xl shadow-sm p-6 flex flex-col items-start">
                    <div class="text-gray-400 text-xs mb-1">Aktif</div>
                    <div class="flex items-end gap-2">
                        <span class="text-2xl font-bold text-orange-500">109</span>
                        <span class="bg-green-50 text-green-600 px-2 py-0.5 rounded-full text-xs">+2%</span>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-6 flex flex-col items-start">
                    <div class="text-gray-400 text-xs mb-1">Inaktif</div>
                    <div class="flex items-end gap-2">
                        <span class="text-2xl font-bold text-orange-500">8</span>
                        <span class="bg-green-50 text-green-600 px-2 py-0.5 rounded-full text-xs">+10%</span>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-6 flex flex-col items-start">
                    <div class="text-gray-400 text-xs mb-1">Abadi</div>
                    <div class="flex items-end gap-2">
                        <span class="text-2xl font-bold text-orange-500">21</span>
                        <span class="bg-red-50 text-red-600 px-2 py-0.5 rounded-full text-xs">-5%</span>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-6 flex flex-col items-start">
                    <div class="text-gray-400 text-xs mb-1">Dimusnahkan</div>
                    <div class="flex items-end gap-2">
                        <span class="text-2xl font-bold text-orange-500">39</span>
                        <span class="bg-red-50 text-red-600 px-2 py-0.5 rounded-full text-xs">-5%</span>
                    </div>
                </div>
            </section>
            <!-- Chart Section (Placeholder) -->
            <section class="grid grid-cols-3 gap-6">
                <div class="col-span-2 bg-white rounded-xl shadow-sm p-6">
                    <div class="flex justify-between items-center mb-4">
                        <div class="font-semibold">Aktivitas Arsip</div>
                        <div class="text-xs text-gray-400">Minggu Ini</div>
                    </div>
                    <div class="h-40 flex items-center justify-center text-gray-300">[Chart Placeholder]</div>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-6 flex flex-col">
                    <div class="flex justify-between items-center mb-4">
                        <div class="font-semibold">Statistik Arsip</div>
                        <div class="text-xs text-gray-400">Minggu Ini</div>
                    </div>
                    <div class="h-40 flex items-center justify-center text-gray-300">[Donut Chart Placeholder]</div>
                    <div class="flex justify-center gap-4 mt-4 text-xs">
                        <span class="flex items-center gap-1"><span class="inline-block w-3 h-3 rounded-full bg-orange-500"></span>Baru</span>
                        <span class="flex items-center gap-1"><span class="inline-block w-3 h-3 rounded-full bg-yellow-400"></span>Proses</span>
                        <span class="flex items-center gap-1"><span class="inline-block w-3 h-3 rounded-full bg-green-500"></span>Selesai</span>
                    </div>
                </div>
            </section>
            <!-- Archive History Table -->
            <section class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex justify-between items-center mb-4">
                    <div class="font-semibold">Riwayat Arsip Masuk</div>
                    <button class="text-xs text-gray-500 border px-3 py-1 rounded hover:bg-gray-50">Lihat Semua</button>
                </div>
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="text-gray-500 border-b">
                            <th class="py-2 text-left">#</th>
                            <th class="py-2 text-left">Judul Arsip</th>
                            <th class="py-2 text-left">Keterangan</th>
                            <th class="py-2 text-left">Tanggal</th>
                            <th class="py-2 text-left">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-2">492</td>
                            <td class="py-2 font-semibold">Surat Masuk</td>
                            <td class="py-2">Surat undangan rapat bulanan</td>
                            <td class="py-2">Sep 12</td>
                            <td class="py-2"><span class="bg-orange-50 text-orange-600 px-2 py-1 rounded text-xs">Baru</span></td>
                        </tr>
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-2">647</td>
                            <td class="py-2 font-semibold">Nota Dinas</td>
                            <td class="py-2">Nota dinas perjalanan dinas</td>
                            <td class="py-2">Sep 12</td>
                            <td class="py-2"><span class="bg-yellow-50 text-yellow-700 px-2 py-1 rounded text-xs">Proses</span></td>
                        </tr>
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-2">453</td>
                            <td class="py-2 font-semibold">Memo</td>
                            <td class="py-2">Memo internal divisi</td>
                            <td class="py-2">Sep 10</td>
                            <td class="py-2"><span class="bg-green-50 text-green-700 px-2 py-1 rounded text-xs">Selesai</span></td>
                        </tr>
                    </tbody>
                </table>
            </section>
        </main>
    </div>
</div>

<?php
include __DIR__ . '/layouts/master/footer.php';
