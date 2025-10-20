<?php
// kelola_pengguna.php
// Page for managing users
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
        <!-- Kelola Pengguna Content - Scrollable -->
        <main class="flex-1 p-8 space-y-8 mt-16 overflow-y-auto">
            <!-- Header -->
            <div class="flex justify-between items-center">
                <div class="font-bold text-3xl">Kelola Pengguna</div>
                <button onclick="openModal('tambahPenggunaModal')" class="bg-cyan-600 hover:bg-cyan-700 text-white font-bold py-2 px-4 rounded">
                    Tambah Pengguna
                </button>
            </div>

            <!-- User Table Section -->
            <section class="bg-white rounded-xl shadow-sm p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="text-gray-500 border-b">
                                <th class="py-3 px-4 text-left font-medium text-xs uppercase tracking-wider">Name</th>
                                <th class="py-3 px-4 text-left font-medium text-xs uppercase tracking-wider">Unit Pengolah</th>
                                <th class="py-3 px-4 text-left font-medium text-xs uppercase tracking-wider">Email</th>
                                <th class="py-3 px-4 text-left font-medium text-xs uppercase tracking-wider">Username</th>
                                <th class="py-3 px-4 text-left font-medium text-xs uppercase tracking-wider">Password</th>
                                <th class="py-3 px-4 text-left font-medium text-xs uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr class="hover:bg-gray-50">
                                <td class="py-4 px-4 whitespace-nowrap text-sm font-medium text-gray-900">John Doe</td>
                                <td class="py-4 px-4 whitespace-nowrap text-sm text-gray-500">Unit A</td>
                                <td class="py-4 px-4 whitespace-nowrap text-sm text-gray-500">john.doe@example.com</td>
                                <td class="py-4 px-4 whitespace-nowrap text-sm text-gray-500">johndoe</td>
                                <td class="py-4 px-4 whitespace-nowrap text-sm text-gray-500">********</td>
                                <td class="py-4 px-4 whitespace-nowrap text-sm font-medium">
                                    <button class="text-cyan-600 hover:text-cyan-900 mr-3">
                                        <span class="material-icons text-base">edit</span>
                                    </button>
                                    <button class="text-red-600 hover:text-red-900">
                                        <span class="material-icons text-base">delete</span>
                                    </button>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50">
                                <td class="py-4 px-4 whitespace-nowrap text-sm font-medium text-gray-900">Jane Smith</td>
                                <td class="py-4 px-4 whitespace-nowrap text-sm text-gray-500">Unit B</td>
                                <td class="py-4 px-4 whitespace-nowrap text-sm text-gray-500">jane.smith@example.com</td>
                                <td class="py-4 px-4 whitespace-nowrap text-sm text-gray-500">janesmith</td>
                                <td class="py-4 px-4 whitespace-nowrap text-sm text-gray-500">********</td>
                                <td class="py-4 px-4 whitespace-nowrap text-sm font-medium">
                                    <button class="text-cyan-600 hover:text-cyan-900 mr-3">
                                        <span class="material-icons text-base">edit</span>
                                    </button>
                                    <button class="text-red-600 hover:text-red-900">
                                        <span class="material-icons text-base">delete</span>
                                    </button>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50">
                                <td class="py-4 px-4 whitespace-nowrap text-sm font-medium text-gray-900">Peter Jones</td>
                                <td class="py-4 px-4 whitespace-nowrap text-sm text-gray-500">Unit C</td>
                                <td class="py-4 px-4 whitespace-nowrap text-sm text-gray-500">peter.jones@example.com</td>
                                <td class="py-4 px-4 whitespace-nowrap text-sm text-gray-500">peterjones</td>
                                <td class="py-4 px-4 whitespace-nowrap text-sm text-gray-500">********</td>
                                <td class="py-4 px-4 whitespace-nowrap text-sm font-medium">
                                    <button class="text-cyan-600 hover:text-cyan-900 mr-3">
                                        <span class="material-icons text-base">edit</span>
                                    </button>
                                    <button class="text-red-600 hover:text-red-900">
                                        <span class="material-icons text-base">delete</span>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>
</div>

<!-- Tambah Pengguna Modal -->
<div id="tambahPenggunaModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Tambah Pengguna Baru</h3>
                <button onclick="closeModal('tambahPenggunaModal')" class="text-gray-400 hover:text-gray-600">
                    <span class="material-icons">close</span>
                </button>
            </div>
            <form action="#" method="POST">
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" name="name" id="name" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-cyan-500 focus:border-cyan-500 sm:text-sm">
                </div>
              <div class="mb-4">
                    <label for="unit_pengolah" class="block text-sm font-medium text-gray-700">Unit Pengolah</label>
                    <select name="unit_pengolah" id="unit_pengolah" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-cyan-500 focus:border-cyan-500 sm:text-sm">
                        <option value="Unit A">Unit A</option>
                        <option value="Unit B">Unit B</option>
                        <option value="Unit C">Unit C</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" id="email" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-cyan-500 focus:border-cyan-500 sm:text-sm">
                </div>
                <div class="mb-4">
                    <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                    <input type="text" name="username" id="username" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-cyan-500 focus:border-cyan-500 sm:text-sm">
                </div>
                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" name="password" id="password" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-cyan-500 focus:border-cyan-500 sm:text-sm">
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeModal('tambahPenggunaModal')" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-cyan-600 text-white rounded-md hover:bg-cyan-700 focus:outline-none">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openModal(modalId) {
        document.getElementById(modalId).classList.remove('hidden');
    }

    function closeModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
    }
</script>

<?php
include __DIR__ . '/../layouts/master/footer.php';
?>
