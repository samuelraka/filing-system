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
        <div class="p-6 mt-16 overflow-y-auto flex flex-col gap-y-4">
            <h2 class="text-3xl font-medium text-gray-900 mb-4">Detail/Edit Arsip</h2>
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex flex-col space-y-4">
                    <!-- Header -->
                    <div class="flex justify-between items-center mb-8">
                        <a href="aktif.php" class="flex items-center text-2xl border-b">
                            <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                            </svg>
                            Kembali
                        </a>
                        <button id="editBtn" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">Edit</button>
                    </div>

                    <!-- Form -->
                    <form id="archiveForm" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="nomorBerkas" class="block text-sm font-medium text-gray-700 mb-1">Nomor Berkas</label>
                                <input type="text" id="nomorBerkas" name="nomorBerkas" class="w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500" required disabled>
                            </div>
                            <div>
                                <label for="nomorItemArsip" class="block text-sm font-medium text-gray-700 mb-1">Nomor Item Arsip</label>
                                <input type="text" id="nomorItemArsip" name="nomorItemArsip" class="w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500" required disabled>
                            </div>
                            <div>
                                <label for="kodeKlasifikasi" class="block text-sm font-medium text-gray-700 mb-1">Kode Klasifikasi</label>
                                <input type="text" id="kodeKlasifikasi" name="kodeKlasifikasi" class="w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500" required disabled>
                            </div>
                            <div>
                                <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                                <input type="date" id="tanggal" name="tanggal" class="w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500" required disabled>
                            </div>
                            <div>
                                <label for="jumlahItemArsip" class="block text-sm font-medium text-gray-700 mb-1">Jumlah Item Arsip</label>
                                <input type="number" id="jumlahItemArsip" name="jumlahItemArsip" min="1" class="w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500" required disabled>
                            </div>
                            <div>
                                <label for="keteranganSKAAD" class="block text-sm font-medium text-gray-700 mb-1">Keterangan SKAAD</label>
                                <input type="text" id="keteranganSKAAD" name="keteranganSKAAD" class="w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500" required disabled>
                            </div>
                        </div>
                        <div>
                            <label for="uraianInformasi" class="block text-sm font-medium text-gray-700 mb-1">Uraian Informasi</label>
                            <textarea id="uraianInformasi" name="uraianInformasi" rows="3" class="w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500" required disabled></textarea>
                        </div>
                        <div>
                            <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
                            <textarea id="keterangan" name="keterangan" rows="2" class="w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500" disabled></textarea>
                        </div>
                        <div>
                            <label for="statusArsip" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select id="statusArsip" name="statusArsip" class="w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500" required disabled>
                                <option value="Aktif">Aktif</option>
                                <option value="Inaktif">Inaktif</option>
                            </select>
                        </div>
                        <!-- File Upload (view only, not editable) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Upload Dokumen PDF</label>
                            <div id="fileList" class="mt-2 flex flex-row gap-2 overflow-x-auto"></div>
                        </div>
                        <div class="flex justify-end space-x-3">
                            <button type="button" id="cancelBtn" class="bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-50 hidden">Batal</button>
                            <button type="submit" id="saveBtn" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md hidden">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Dummy data for demonstration
    const archiveData = {
        nomorBerkas: 'A-001',
        nomorItemArsip: 'IT-1001',
        kodeKlasifikasi: '101.2',
        tanggal: '2023-01-10',
        jumlahItemArsip: 5,
        keteranganSKAAD: 'Lengkap',
        uraianInformasi: 'Surat Keputusan Direktur',
        keterangan: 'Arsip penting',
        statusArsip: 'Aktif',
        files: [
            { name: 'SuratKeputusan.pdf', size: 123456, url: '#' },
            { name: 'Lampiran.pdf', size: 654321, url: '#' }
        ]
    };

    // Populate form with data
    for (const key in archiveData) {
        if (document.getElementById(key)) {
            if (document.getElementById(key).tagName === 'SELECT') {
                document.getElementById(key).value = archiveData[key];
            } else if (document.getElementById(key).tagName === 'TEXTAREA') {
                document.getElementById(key).value = archiveData[key];
            } else {
                document.getElementById(key).value = archiveData[key];
            }
        }
    }
    // File list rendering
    const fileList = document.getElementById('fileList');
    archiveData.files.forEach(file => {
        const fileItem = document.createElement('a');
        fileItem.href = file.url;
        fileItem.target = '_blank';
        fileItem.className = 'flex flex-col items-center min-w-[180px] max-w-xs bg-gray-50 rounded border border-gray-200 p-2 relative group';
        fileItem.innerHTML = `
            <div class="text-red-500 mb-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="text-sm truncate max-w-[140px]">${file.name}</div>
            <div class="text-xs text-gray-500">${formatFileSize(file.size)}</div>
        `;
        fileList.appendChild(fileItem);
    });
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    // Enable editing
    const editBtn = document.getElementById('editBtn');
    const saveBtn = document.getElementById('saveBtn');
    const cancelBtn = document.getElementById('cancelBtn');
    const form = document.getElementById('archiveForm');
    editBtn.addEventListener('click', function() {
        form.querySelectorAll('input, select, textarea').forEach(el => el.disabled = false);
        editBtn.classList.add('hidden');
        saveBtn.classList.remove('hidden');
        cancelBtn.classList.remove('hidden');
    });
    cancelBtn.addEventListener('click', function() {
        // Reset to original data
        for (const key in archiveData) {
            if (document.getElementById(key)) {
                if (document.getElementById(key).tagName === 'SELECT') {
                    document.getElementById(key).value = archiveData[key];
                } else if (document.getElementById(key).tagName === 'TEXTAREA') {
                    document.getElementById(key).value = archiveData[key];
                } else {
                    document.getElementById(key).value = archiveData[key];
                }
            }
        }
        form.querySelectorAll('input, select, textarea').forEach(el => el.disabled = true);
        editBtn.classList.remove('hidden');
        saveBtn.classList.add('hidden');
        cancelBtn.classList.add('hidden');
    });
    // Save changes (demo only)
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        alert('Perubahan berhasil disimpan!');
        form.querySelectorAll('input, select, textarea').forEach(el => el.disabled = true);
        editBtn.classList.remove('hidden');
        saveBtn.classList.add('hidden');
        cancelBtn.classList.add('hidden');
    });
});
</script>

<?php
// Include footer
include_once "../layouts/master/footer.php";
?>
