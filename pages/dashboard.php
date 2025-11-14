<?php
// dashboard.php
// Dashboard page for archiving system
include_once __DIR__ . '/../config/session.php';
include_once __DIR__ . '/../config/database.php';
include __DIR__ . '/../layouts/master/header.php';
include __DIR__ . '/../layouts/components/sidebar_dynamic.php';
?>

<div class="flex h-screen bg-gray-100">
    <!-- Main Content -->
    <div class="flex-1 flex flex-col ml-64">
        <?php include __DIR__ . '/../layouts/components/topbar.php'; ?>
        <!-- Dashboard Content - Scrollable -->
        <main class="flex-1 p-6 space-y-8 mt-16 overflow-y-auto">
            <!-- Header -->
            <div class="flex justify-between items-center">
                <div class="font-bold text-3xl">Dashboard</div>
            </div>
            <!-- Stats Cards -->
            <section class="grid grid-cols-4 gap-6">
                <div class="bg-white rounded-xl shadow-sm p-6 flex flex-col items-start">
                    <div class="text-slate-500 font-semibold mb-1">Aktif</div>
                    <div class="flex items-end gap-2">
                        <span id="countAktif" class="text-3xl font-bold text-slate-700">0</span>
                        <span class="bg-green-50 text-green-600 px-2 py-0.5 rounded-full text-xs">Aktif</span>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-6 flex flex-col items-start">
                    <div class="text-slate-500 font-semibold mb-1">Inaktif</div>
                    <div class="flex items-end gap-2">
                        <span id="countInaktif" class="text-3xl font-bold text-slate-700">0</span>
                        <span class="bg-yellow-50 text-yellow-600 px-2 py-0.5 rounded-full text-xs">Inaktif</span>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-6 flex flex-col items-start">
                    <div class="text-slate-500 font-semibold mb-1">Statis</div>
                    <div class="flex items-end gap-2">
                        <span id="countStatis" class="text-3xl font-bold text-slate-700">0</span>
                        <span class="bg-blue-50 text-blue-600 px-2 py-0.5 rounded-full text-xs">Statis</span>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-6 flex flex-col items-start">
                    <div class="text-slate-500 font-semibold mb-1">Vital</div>
                    <div class="flex items-end gap-2">
                        <span id="countVital" class="text-3xl font-bold text-slate-700">0</span>
                        <span class="bg-red-50 text-red-600 px-2 py-0.5 rounded-full text-xs">Vital</span>
                    </div>
                </div>
            </section>
            <!-- Chart Section (Placeholder) -->
            <section class="grid grid-cols-3 gap-6">
                <!-- Perkembangan Data Arsip -->
                <div class="col-span-2 bg-white rounded-xl shadow-sm p-6">
                    <div class="flex justify-between items-center mb-4">
                        <div class="text-2xl font-semibold">Aktivitas Arsip</div>
                        <select id="chartRangeSelect" class="text-sm text-slate-700 border px-3 py-1 rounded">
                            <option value="weekly">Weekly</option>
                            <option value="monthly">Monthly</option>
                            <option value="yearly">Yearly</option>
                        </select>
                    </div>
                    <canvas id="archiveActivityChart" width="400" height="200"></canvas>
                    <script>
                        
                    </script>
                </div>

                <!-- Summary Data Arsip -->
                <div class="bg-white rounded-xl shadow-sm p-6 flex flex-col relative">
                    <div class="flex justify-between items-center mb-4">
                        <div class="text-2xl font-semibold">Summary Arsip</div>
                        <button id="statsFilterToggle" class="flex items-center gap-2 text-sm text-slate-700 border px-3 py-1 rounded">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4"><path d="M3 5h18v2l-7 7v5l-4-2v-3L3 7V5z"/></svg>
                            Filter
                        </button>
                    </div>

                    <div id="statsTitle" class="text-sm text-slate-700 mb-2 text-center">Data from</div>
                    <div id="statsNoData" class="text-sm text-slate-500 mb-2 hidden text-center">No Data for this Week</div>

                    <!-- Filter Panel -->
                    <div id="statsFilterPanel" class="hidden absolute z-10 top-12 right-6 w-80 bg-white border rounded p-4 shadow-lg">
                        <div class="flex items-center gap-4 mb-3">
                            <label class="flex items-center gap-2 text-sm"><input type="radio" name="statsMode" value="single" checked> Specific Date</label>
                            <label class="flex items-center gap-2 text-sm"><input type="radio" name="statsMode" value="range"> Date Range</label>
                        </div>
                        <div id="statsSingle" class="flex items-center gap-2 mb-3">
                            <input type="date" id="statsDate" class="border rounded px-2 py-1 text-sm">
                            <button id="statsApplySingle" class="text-sm bg-slate-700 text-white px-3 py-1 rounded">Apply</button>
                        </div>
                        <div id="statsRange" class="hidden items-center gap-2 mb-3">
                            <input type="date" id="statsStart" class="border rounded px-2 py-1 text-sm">
                            <span class="text-sm">to</span>
                            <input type="date" id="statsEnd" class="border rounded px-2 py-1 text-sm">
                            <button id="statsApplyRange" class="text-sm bg-slate-700 text-white px-3 py-1 rounded">Apply</button>
                        </div>
                    </div>

                    <canvas id="archiveStatsChart" width="200" height="200"></canvas>
                    <script>
                        // Inisialisasi Chart.js
                        var ctx = document.getElementById('archiveActivityChart').getContext('2d');
                        var currentRange = 'monthly';
                        var rangeSelect = document.getElementById('chartRangeSelect');
                        rangeSelect.value = 'monthly';
                        fetch('../api/dashboard_stats.php?activity=1').then(function(res){ return res.json(); }).then(function(d){
                            var activityData = d.activityData;
                            window.statsData = d.statsData;
                            document.getElementById('countAktif').textContent = d.cards.aktif;
                            document.getElementById('countInaktif').textContent = d.cards.inaktif;
                            document.getElementById('countStatis').textContent = d.cards.statis;
                            document.getElementById('countVital').textContent = d.cards.vital;

                            // Chart Aktivitas Arsip
                            var archiveActivityChart = new Chart(ctx, {
                                type: 'bar',
                                data: {
                                    labels: activityData[currentRange].labels,
                                    datasets: [{
                                        label: 'Aktif',
                                        data: activityData[currentRange].aktif,
                                        backgroundColor: 'rgba(0, 146, 184, 1)'
                                    }, {
                                        label: 'Inaktif',
                                        data: activityData[currentRange].inaktif,
                                        backgroundColor: 'rgba(254, 184, 34, 1)'
                                    }, {
                                        label: 'Statis',
                                        data: activityData[currentRange].statis,
                                        backgroundColor: 'rgba(98, 116, 142, 1)'
                                    }, {
                                        label: 'Vital',
                                        data: activityData[currentRange].vital,
                                        backgroundColor: 'rgba(49, 65, 88, 1)'
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    scales: { y: { beginAtZero: true } },
                                    plugins: { legend: { position: 'bottom', labels: { padding: 12 } } },
                                    layout: { padding: { bottom: 12 } }
                                }
                            });
                            
                            rangeSelect.addEventListener('change', function() {
                                currentRange = this.value;
                                archiveActivityChart.data.labels = activityData[currentRange].labels;
                                archiveActivityChart.data.datasets[0].data = activityData[currentRange].aktif;
                                archiveActivityChart.data.datasets[1].data = activityData[currentRange].inaktif;
                                archiveActivityChart.data.datasets[2].data = activityData[currentRange].statis;
                                archiveActivityChart.data.datasets[3].data = activityData[currentRange].vital;
                                archiveActivityChart.update();
                            });

                            var ctx2 = document.getElementById('archiveStatsChart').getContext('2d');
                            var statsCurrentRange = 'weekly';
                            window.archiveStatsChart = new Chart(ctx2, {
                                type: 'doughnut',
                                data: {
                                    labels: ['Aktif', 'Inaktif', 'Statis', 'Vital'],
                                    datasets: [{
                                        label: 'Status Arsip',
                                        data: [window.statsData[statsCurrentRange].aktif, window.statsData[statsCurrentRange].inaktif, window.statsData[statsCurrentRange].statis, window.statsData[statsCurrentRange].vital],
                                        backgroundColor: [
                                            'rgba(0, 146, 184, 1)',
                                            'rgba(254, 184, 34, 1)',
                                            'rgba(98, 116, 142, 1)',
                                            'rgba(49, 65, 88, 1)'
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
                                options: { responsive: true, plugins: { legend: { position: 'bottom', labels: { padding: 16 } } }, layout: { padding: { bottom: 12 } } }
                            });
                            function formatLabel(s){ return s.replace(/(\d{4}-\d{2}-\d{2})/g, '<strong>$1<\/strong>'); }
                            var t = document.getElementById('statsTitle');
                            t.innerHTML = formatLabel(d.weeklyLabel);
                            var noDataEl = document.getElementById('statsNoData');
                            var w = d.statsData.weekly;
                            var totalW = (w.aktif||0) + (w.inaktif||0) + (w.statis||0) + (w.vital||0);
                            if (totalW === 0) { noDataEl.classList.remove('hidden'); } else { noDataEl.classList.add('hidden'); }
                        });
                        
                        // Filter Logic
                        var statsFilterToggle = document.getElementById('statsFilterToggle');
                        var statsFilterPanel = document.getElementById('statsFilterPanel');
                        statsFilterToggle.addEventListener('click', function(){
                            if (statsFilterPanel.classList.contains('hidden')) { statsFilterPanel.classList.remove('hidden'); } else { statsFilterPanel.classList.add('hidden'); }
                        });
                        var modeInputs = document.querySelectorAll('input[name="statsMode"]');
                        var statsSingle = document.getElementById('statsSingle');
                        var statsRange = document.getElementById('statsRange');
                        modeInputs.forEach(function(r){ r.addEventListener('change', function(){
                            if (this.value === 'single') { statsSingle.classList.remove('hidden'); statsRange.classList.add('hidden'); }
                            else { statsSingle.classList.add('hidden'); statsRange.classList.remove('hidden'); }
                        }); });
                        function applyStats(start, end){
                            fetch('../api/dashboard_stats.php?start='+encodeURIComponent(start)+'&end='+encodeURIComponent(end))
                                .then(function(res){ return res.json(); })
                                .then(function(d){
                                    if (window.archiveStatsChart) {
                                        window.archiveStatsChart.data.datasets[0].data = [d.aktif, d.inaktif, d.statis, d.vital];
                                        window.archiveStatsChart.update();
                                    }
                                    var t = document.getElementById('statsTitle');
                                    function formatLabel(s){ return s.replace(/(\d{4}-\d{2}-\d{2})/g, '<strong>$1<\/strong>'); }
                                    t.innerHTML = formatLabel(d.label);
                                    var noDataEl = document.getElementById('statsNoData');
                                    noDataEl.classList.add('hidden');
                                });
                        }
                        document.getElementById('statsApplySingle').addEventListener('click', function(){
                            var sd = document.getElementById('statsDate').value;
                            if (!sd) return;
                            applyStats(sd, sd);
                        });
                        document.getElementById('statsApplyRange').addEventListener('click', function(){
                            var s = document.getElementById('statsStart').value;
                            var e = document.getElementById('statsEnd').value;
                            if (!s || !e) return;
                            applyStats(s, e);
                        });
                    </script>
                </div>
            </section>
        </main>
    </div>
</div>

<?php
include __DIR__ . '/../layouts/master/footer.php';
?>
