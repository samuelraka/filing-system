<?php
// dashboard.php
// Dashboard page for archiving system

// Dummy data for now (replace with DB queries later)
$activeArchives = 12;
$inactiveArchives = 7;
$monthlyArchiveActivity = [
    '2025-09' => [
        'new' => 5,      // Jumlah arsip baru yang masuk pada September 2025
        'in_progress' => 3,  // Jumlah arsip yang sedang diproses
        'completed' => 2,     // Jumlah arsip yang selesai pada September 2025
    ],
    '2025-08' => [
        'new' => 8,
        'in_progress' => 4,
        'completed' => 3,
    ],
    '2025-07' => [
        'new' => 6,
        'in_progress' => 2,
        'completed' => 4,
    ],
    '2025-06' => [
        'new' => 7,
        'in_progress' => 5,
        'completed' => 3,
    ],
    '2025-05' => [
        'new' => 9,
        'in_progress' => 4,
        'completed' => 5,
    ],
];

$archiveStats = [
    'new' => 50,       // Jumlah arsip yang baru
    'in_progress' => 30,  // Jumlah arsip yang sedang diproses
    'completed' => 20,     // Jumlah arsip yang sudah selesai
];

include __DIR__ . '/../layouts/master/header.php';
include __DIR__ . '/../layouts/components/sidebar.php';
?>
<div class="min-h-screen bg-[#fafbfc] flex overflow-hidden">
    <!-- Main Content -->
    <div class="flex-1 flex flex-col ml-64">
        <?php include __DIR__ . '/../layouts/components/topbar.php'; ?>
        <!-- Dashboard Content - Scrollable -->
        <main class="flex-1 p-8 space-y-8 mt-16 overflow-y-auto">
            <!-- Header -->
            <div class="flex justify-between items-center">
                <div class="font-bold text-xl">Dashboard</div>
                <div class="flex gap-3 items-center">
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
                    <canvas id="archiveActivityChart" width="400" height="200"></canvas>
                    <script>
                        var ctx = document.getElementById('archiveActivityChart').getContext('2d');
                        var archiveActivityChart = new Chart(ctx, {
                            type: 'bar',  // Jenis chart: Bar chart
                            data: {
                                labels: <?php echo json_encode(array_keys($monthlyArchiveActivity)); ?>,  // Label berdasarkan bulan
                                datasets: [{
                                    label: 'Arsip Baru',
                                    data: <?php echo json_encode(array_column($monthlyArchiveActivity, 'new')); ?>,  // Data arsip baru
                                    backgroundColor: 'rgba(255, 159, 64, 0.2)',
                                    borderColor: 'rgba(255, 159, 64, 1)',
                                    borderWidth: 1
                                }, {
                                    label: 'Proses',
                                    data: <?php echo json_encode(array_column($monthlyArchiveActivity, 'in_progress')); ?>,  // Data arsip yang sedang diproses
                                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                    borderColor: 'rgba(75, 192, 192, 1)',
                                    borderWidth: 1
                                }, {
                                    label: 'Selesai',
                                    data: <?php echo json_encode(array_column($monthlyArchiveActivity, 'completed')); ?>,  // Data arsip yang selesai
                                    backgroundColor: 'rgba(153, 102, 255, 0.2)',
                                    borderColor: 'rgba(153, 102, 255, 1)',
                                    borderWidth: 1
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
                        <div class="font-semibold">Statistik Arsip</div>
                        <div class="text-xs text-gray-400">Minggu Ini</div>
                    </div>
                    <canvas id="archiveStatsChart" width="200" height="200"></canvas>
                    <script>
                        var ctx = document.getElementById('archiveStatsChart').getContext('2d');
                        var archiveStatsChart = new Chart(ctx, {
                            type: 'doughnut',  // Jenis chart: Doughnut chart
                            data: {
                                labels: ['Baru', 'Proses', 'Selesai'],
                                datasets: [{
                                    label: 'Status Arsip',
                                    data: [<?php echo $archiveStats['new']; ?>, <?php echo $archiveStats['in_progress']; ?>, <?php echo $archiveStats['completed']; ?>],
                                    backgroundColor: [
                                        'rgba(255, 159, 64, 0.2)',   // Warna untuk arsip baru
                                        'rgba(75, 192, 192, 0.2)',   // Warna untuk arsip yang sedang diproses
                                        'rgba(153, 102, 255, 0.2)'   // Warna untuk arsip yang selesai
                                    ],
                                    borderColor: [
                                        'rgba(255, 159, 64, 1)',
                                        'rgba(75, 192, 192, 1)',
                                        'rgba(153, 102, 255, 1)'
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
                    <div class="font-semibold">Aktivitas Arsip Terbaru</div>
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