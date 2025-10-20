<?php
// kelola_arsip.php
// Page for managing archive categories (Pokok Masalah, Sub Masalah, Sub-Sub Masalah)
include_once __DIR__ . '/../config/session.php';

// Require login to access this page
// requireLogin();

include __DIR__ . '/../layouts/master/header.php';
include __DIR__ . '/../layouts/components/sidebar_dynamic.php';
?>
<div class="min-h-screen bg-[#fafbfc] flex overflow-hidden">
    <!-- Main Content -->
    <div class="flex-1 flex flex-col ml-64">
        <?php include __DIR__ . '/../layouts/components/topbar.php'; ?>
        <!-- Kelola Arsip Content - Scrollable -->
        <main class="flex-1 p-8 space-y-8 mt-16 overflow-y-auto">
            <!-- Header -->
            <div class="flex justify-between items-center">
                <div class="font-bold text-3xl">Kelola Arsip</div>
            </div>

            <!-- Tabs Section -->
            <section class="bg-white rounded-xl shadow-sm p-6">
                <!-- Tabs -->
                <div class="border-b border-gray-200">
                    <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                        <button onclick="showTab('pokok-masalah')" id="tab-pokok-masalah" class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm active">
                            Pokok Masalah
                        </button>
                        <button onclick="showTab('sub-masalah')" id="tab-sub-masalah" class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            Sub Masalah
                        </button>
                        <button onclick="showTab('sub-sub-masalah')" id="tab-sub-sub-masalah" class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            Sub-Sub Masalah
                        </button>
                    </nav>
                </div>

                <!-- Tab Content -->
                <div class="p-6">
                    <!-- Pokok Masalah Content -->
                    <div id="content-pokok-masalah" class="tab-content">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-xl font-semibold text-gray-700">Daftar Pokok Masalah</h2>
                            <button class="bg-cyan-600 hover:bg-cyan-700 text-white font-bold py-2 px-4 rounded">
                                Tambah Pokok Masalah
                            </button>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white border border-gray-200">
                                <thead>
                                    <tr>
                                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
                                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Uraian</th>
                                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="py-2 px-4 border-b border-gray-200">1</td>
                                        <td class="py-2 px-4 border-b border-gray-200">PM-001</td>
                                        <td class="py-2 px-4 border-b border-gray-200">Contoh Pokok Masalah 1</td>
                                        <td class="py-2 px-4 border-b border-gray-200">
                                            <button class="text-yellow-600 hover:text-yellow-900 mr-2">Edit</button>
                                            <button class="text-red-600 hover:text-red-900">Hapus</button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="py-2 px-4 border-b border-gray-200">2</td>
                                        <td class="py-2 px-4 border-b border-gray-200">PM-002</td>
                                        <td class="py-2 px-4 border-b border-gray-200">Contoh Pokok Masalah 2</td>
                                        <td class="py-2 px-4 border-b border-gray-200">
                                            <button class="text-yellow-600 hover:text-yellow-900 mr-2">Edit</button>
                                            <button class="text-red-600 hover:text-red-900">Hapus</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Sub Masalah Content -->
                    <div id="content-sub-masalah" class="tab-content hidden">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-xl font-semibold text-gray-700">Daftar Sub Masalah</h2>
                            <button class="bg-cyan-600 hover:bg-cyan-700 text-white font-bold py-2 px-4 rounded">
                                Tambah Sub Masalah
                            </button>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white border border-gray-200">
                                <thead>
                                    <tr>
                                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
                                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pokok Masalah</th>
                                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Uraian</th>
                                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="py-2 px-4 border-b border-gray-200">1</td>
                                        <td class="py-2 px-4 border-b border-gray-200">SM-001</td>
                                        <td class="py-2 px-4 border-b border-gray-200">PM-001</td>
                                        <td class="py-2 px-4 border-b border-gray-200">Contoh Sub Masalah 1</td>
                                        <td class="py-2 px-4 border-b border-gray-200">
                                            <button class="text-yellow-600 hover:text-yellow-900 mr-2">Edit</button>
                                            <button class="text-red-600 hover:text-red-900">Hapus</button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="py-2 px-4 border-b border-gray-200">2</td>
                                        <td class="py-2 px-4 border-b border-gray-200">SM-002</td>
                                        <td class="py-2 px-4 border-b border-gray-200">PM-001</td>
                                        <td class="py-2 px-4 border-b border-gray-200">Contoh Sub Masalah 2</td>
                                        <td class="py-2 px-4 border-b border-gray-200">
                                            <button class="text-yellow-600 hover:text-yellow-900 mr-2">Edit</button>
                                            <button class="text-red-600 hover:text-red-900">Hapus</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Sub-Sub Masalah Content -->
                    <div id="content-sub-sub-masalah" class="tab-content hidden">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-xl font-semibold text-gray-700">Daftar Sub-Sub Masalah</h2>
                            <button class="bg-cyan-600 hover:bg-cyan-700 text-white font-bold py-2 px-4 rounded">
                                Tambah Sub-Sub Masalah
                            </button>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white border border-gray-200">
                                <thead>
                                    <tr>
                                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
                                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sub Masalah</th>
                                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Uraian</th>
                                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="py-2 px-4 border-b border-gray-200">1</td>
                                        <td class="py-2 px-4 border-b border-gray-200">SSM-001</td>
                                        <td class="py-2 px-4 border-b border-gray-200">SM-001</td>
                                        <td class="py-2 px-4 border-b border-gray-200">Contoh Sub-Sub Masalah 1</td>
                                        <td class="py-2 px-4 border-b border-gray-200">
                                            <button class="text-yellow-600 hover:text-yellow-900 mr-2">Edit</button>
                                            <button class="text-red-600 hover:text-red-900">Hapus</button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="py-2 px-4 border-b border-gray-200">2</td>
                                        <td class="py-2 px-4 border-b border-gray-200">SSM-002</td>
                                        <td class="py-2 px-4 border-b border-gray-200">SM-001</td>
                                        <td class="py-2 px-4 border-b border-gray-200">Contoh Sub-Sub Masalah 2</td>
                                        <td class="py-2 px-4 border-b border-gray-200">
                                            <button class="text-yellow-600 hover:text-yellow-900 mr-2">Edit</button>
                                            <button class="text-red-600 hover:text-red-900">Hapus</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>
</div>

<script>
function showTab(tabName) {
    // Hide all tab contents
    const tabContents = document.querySelectorAll('.tab-content');
    tabContents.forEach(content => {
        content.classList.add('hidden');
    });

    // Remove active class from all tab buttons
    const tabButtons = document.querySelectorAll('.tab-button');
    tabButtons.forEach(button => {
        button.classList.remove('active', 'border-blue-500', 'text-blue-600');
        button.classList.add('border-transparent', 'text-gray-500');
    });

    // Show the selected tab content
    document.getElementById(`content-${tabName}`).classList.remove('hidden');

    // Add active class to the clicked tab button
    const activeButton = document.getElementById(`tab-${tabName}`);
    activeButton.classList.add('active', 'border-cyan-600', 'text-cyan-600');
    activeButton.classList.remove('border-transparent', 'text-gray-500');
}

// Set initial active tab styles
document.addEventListener('DOMContentLoaded', function() {
    const initialActiveTab = document.getElementById('tab-pokok-masalah');
    initialActiveTab.classList.add('border-cyan-600', 'text-cyan-600');
    initialActiveTab.classList.remove('border-transparent', 'text-gray-500');
});
</script>

<?php
include __DIR__ . '/../layouts/master/footer.php';
?>
