<?php
// Include header
include_once "../layouts/master/header.php";
?>

<div class="flex h-screen">
    <?php include_once "../layouts/components/sidebar_dynamic.php"; ?>

    <div class="flex-1 flex flex-col ml-64">
        <?php include_once "../layouts/components/topbar.php"; ?>

        <div class="p-6 mt-16 overflow-y-auto">
            <h2 class="text-3xl font-medium text-gray-900 mb-4">Tambah Arsip Statis</h2>
            <div class="bg-white rounded-lg shadow-sm p-6 max-w-screen">
                <div class="flex flex-col space-y-4">
                    <!-- Header -->
                    <div class="flex justify-between items-center mb-8">
                        <a href="statis.php" class="flex items-center text-2xl border-b">
                            <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                            </svg>
                            Kembali
                        </a>
                    </div>
                    <form action="#" method="post" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">No</label>
                                <input type="text" class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-cyan-600" placeholder="Contoh: 1">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Kode Klasifikasi Arsip</label>
                                <input type="text" class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-cyan-600" placeholder="Contoh: 001.1">
                            </div>
    
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Jenis/Series Arsip</label>
                                <input type="text" class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-cyan-600" placeholder="Contoh: Surat Keputusan, Laporan Tahunan">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tahun</label>
                                <input type="number" class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-cyan-600" placeholder="Contoh: 2022">
                            </div>
    
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Jumlah</label>
                                <input type="number" class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-cyan-600" placeholder="Contoh: 3">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tingkat Perkembangan</label>
                                <select class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-cyan-600">
                                    <option>Asli</option>
                                    <option>Salinan</option>
                                    <option>Lengkap</option>
                                </select>
                            </div>
    
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Keterangan</label>
                                <textarea rows="3" class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-cyan-600" placeholder="Tambahkan catatan tambahan"></textarea>
                            </div>
                        </div>
    
                        <div class="flex justify-end space-x-3">
                            <button type="reset" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-md">Reset</button>
                            <button type="submit" class="bg-cyan-600 hover:bg-cyan-600/90 text-white px-4 py-2 rounded-md">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include_once "../layouts/master/footer.php";
?>