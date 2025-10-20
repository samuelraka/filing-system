<?php
// dashboard.php
// Dashboard page for archiving system
// include_once __DIR__ . '/../config/session.php';

// Require login to access this page
// requireLogin();

// Dummy data for now (replace with DB queries later)
$activeArchives = 12;
$inactiveArchives = 7;
$monthlyArchiveActivity = [
    '2025-09' => [
        'aktif' => 5,      // Jumlah arsip Aktif yang masuk pada September 2025
        'inaktif' => 3,  // Jumlah arsip yang Inaktif
        'abadi' => 3,  // Jumlah arsip yang Abadi
        'musnah' => 2,     // Jumlah arsip yang Musnah pada September 2025
    ],
    '2025-08' => [
        'aktif' => 8,
        'inaktif' => 4,
        'abadi' => 4,
        'musnah' => 3,
    ],
    '2025-07' => [
        'aktif' => 6,
        'inaktif' => 2,
        'abadi' => 2,
        'musnah' => 4,
    ],
    '2025-06' => [
        'aktif' => 7,
        'inaktif' => 5,
        'abadi' => 5,
        'musnah' => 3,
    ],
    '2025-05' => [
        'aktif' => 9,
        'inaktif' => 4,
        'abadi' => 4,
        'musnah' => 5,
    ],
];

$archiveStats = [
    'aktif' => 50,       // Jumlah arsip yang Aktif
    'inaktif' => 30,  // Jumlah arsip yang Inaktif
    'abadi' => 30,  // Jumlah arsip yang Abadi
    'musnah' => 20,     // Jumlah arsip yang sudah Musnah
];

include __DIR__ . '/../layouts/master/header.php';
include __DIR__ . '/../layouts/components/sidebar_dynamic.php';
?>
<div class="min-h-screen bg-[#fafbfc] flex overflow-hidden">
    <!-- Main Content -->
    <div class="flex-1 flex flex-col ml-64">
        <?php include __DIR__ . '/../layouts/components/topbar.php'; ?>
        <!-- Dashboard Content - Scrollable -->
        <main class="flex-1 p-8 space-y-8 mt-16 overflow-y-auto">
            <!-- Header -->
            <div class="flex justify-between items-center">
                <div class="font-bold text-3xl">Dashboard</div>
            </div>
            <!-- Stats Cards -->
            <section class="grid grid-cols-4 gap-6">
                <div class="bg-white rounded-xl shadow-sm p-6 flex flex-col items-start">
                    <div class="text-slate-500 font-semibold mb-1">Aktif</div>
                    <div class="flex items-end gap-2">
                        <span class="text-3xl font-bold text-slate-700">109</span>
                        <span class="bg-green-50 text-green-600 px-2 py-0.5 rounded-full text-xs">+2%</span>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-6 flex flex-col items-start">
                    <div class="text-slate-500 font-semibold mb-1">Inaktif</div>
                    <div class="flex items-end gap-2">
                        <span class="text-3xl font-bold text-slate-700">8</span>
                        <span class="bg-green-50 text-green-600 px-2 py-0.5 rounded-full text-xs">+10%</span>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-6 flex flex-col items-start">
                    <div class="text-slate-500 font-semibold mb-1">Abadi</div>
                    <div class="flex items-end gap-2">
                        <span class="text-3xl font-bold text-slate-700">21</span>
                        <span class="bg-red-50 text-red-600 px-2 py-0.5 rounded-full text-xs">-5%</span>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-6 flex flex-col items-start">
                    <div class="text-slate-500 font-semibold mb-1">Dimusnahkan</div>
                    <div class="flex items-end gap-2">
                        <span class="text-3xl font-bold text-slate-700">39</span>
                        <span class="bg-red-50 text-red-600 px-2 py-0.5 rounded-full text-xs">-5%</span>
                    </div>
                </div>
            </section>
            <!-- Chart Section (Placeholder) -->
            <section class="grid grid-cols-3 gap-6">
                <div class="col-span-2 bg-white rounded-xl shadow-sm p-6">
                    <div class="flex justify-between items-center mb-4">
                        <div class="text-2xl font-semibold">Aktivitas Arsip</div>
                        <div class="text-sm text-slate-500 border px-3 py-1 rounded hover:bg-gray-50">Minggu Ini</div>
                    </div>
                    <canvas id="archiveActivityChart" width="400" height="200"></canvas>
                    <script>
                        var ctx = document.getElementById('archiveActivityChart').getContext('2d');
                        var archiveActivityChart = new Chart(ctx, {
                            type: 'bar',  // Jenis chart: Bar chart
                            data: {
                                labels: <?php echo json_encode(array_keys($monthlyArchiveActivity)); ?>,  // Label berdasarkan bulan
                                datasets: [{
                                    label: 'Arsip Aktif',
                                    data: <?php echo json_encode(array_column($monthlyArchiveActivity, 'aktif')); ?>,  // Data arsip Aktif
                                    backgroundColor: 'rgba(0, 146, 184, 1)',
                                }, {
                                    label: 'Inaktif',
                                    data: <?php echo json_encode(array_column($monthlyArchiveActivity, 'inaktif')); ?>,  // Data arsip yang sedang diInaktif
                                    backgroundColor: 'rgba(254, 184, 34, 1)',
                                }, {
                                    label: 'Abadi',
                                    data: <?php echo json_encode(array_column($monthlyArchiveActivity, 'abadi')); ?>,  // Data arsip yang Abadi
                                    backgroundColor: 'rgba(98, 116, 142, 1)',
                                }, {
                                    label: 'Musnah',
                                    data: <?php echo json_encode(array_column($monthlyArchiveActivity, 'musnah')); ?>,  // Data arsip yang Musnah
                                    backgroundColor: 'rgba(49, 65, 88, 1)',
                                }]
                            },
                            options: {
                                responsive: true,
                                scales: {
                                    y: {
                                        beginAtZero: true
                                    }
                                }
                            }
                        });
                    </script>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-6 flex flex-col">
                    <div class="flex justify-between items-center mb-4">
                        <div class="text-2xl font-semibold">Statistik Arsip</div>
                        <div class="text-sm text-slate-500 border px-3 py-1 rounded hover:bg-gray-50">Minggu Ini</div>
                    </div>
                    <canvas id="archiveStatsChart" width="200" height="200"></canvas>
                    <script>
                        var ctx = document.getElementById('archiveStatsChart').getContext('2d');
                        var archiveStatsChart = new Chart(ctx, {
                            type: 'doughnut',  // Jenis chart: Doughnut chart
                            data: {
                                labels: ['Aktif', 'Inaktif', 'Abadi', 'Musnah'],
                                datasets: [{
                                    label: 'Status Arsip',
                                    data: [<?php echo $archiveStats['aktif']; ?>, <?php echo $archiveStats['inaktif']; ?>, <?php echo $archiveStats['abadi']; ?>, <?php echo $archiveStats['musnah']; ?>],
                                    backgroundColor: [
                                        'rgba(0, 146, 184, 1)',   // Warna untuk arsip Aktif
                                        'rgba(254, 184, 34, 1)',   // Warna untuk arsip yang Inaktif
                                        'rgba(98, 116, 142, 1)',   // Warna untuk arsip yang Abadi
                                        'rgba(49, 65, 88, 1)'   // Warna untuk arsip yang Musnah
                                    ],
                                    borderColor: [
                                        'rgba(0, 146, 184, 1)',
                                        'rgba(254, 184, 34, 1)',
                                        'rgba(98, 116, 142, 1)',
                                        'rgba(49, 65, 88, 1)'
                                    ],
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                responsive: true
                            }
                        });
                    </script>
                </div>
            </section>
            <!-- Archive History Table -->
            <section class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex justify-between items-center mb-4">
                    <div class="text-2xl font-semibold">Aktivitas Arsip Terbaru</div>
                    <button class="text-sm text-slate-500 border px-3 py-1 rounded hover:bg-gray-50">Lihat Semua</button>
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
                            <td class="py-2"><span class="bg-orange-50 text-orange-600 px-2 py-1 rounded text-xs">Aktif</span></td>
                        </tr>
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-2">647</td>
                            <td class="py-2 font-semibold">Nota Dinas</td>
                            <td class="py-2">Nota dinas perjalanan dinas</td>
                            <td class="py-2">Sep 12</td>
                            <td class="py-2"><span class="bg-yellow-50 text-yellow-700 px-2 py-1 rounded text-xs">Inaktif</span></td>
                        </tr>
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-2">453</td>
                            <td class="py-2 font-semibold">Memo</td>
                            <td class="py-2">Memo internal divisi</td>
                            <td class="py-2">Sep 10</td>
                            <td class="py-2"><span class="bg-green-50 text-green-700 px-2 py-1 rounded text-xs">Musnah</span></td>
                        </tr>
                    </tbody>
                </table>
            </section>
        </main>
    </div>
</div>

<?php
include __DIR__ . '/../layouts/master/footer.php';
?>
