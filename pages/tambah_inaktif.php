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
        <div class="p-6 mt-16 overflow-y-auto">
            <h2 class="text-lg font-medium text-gray-900">Tambah Arsip</h2>
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex flex-col space-y-4">
                    <!-- Header -->
                    <div class="flex justify-between items-center mb-4">
                        <a href="semua-arsip.php" class="flex items-center">
                            <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                            </svg>
                            Kembali
                        </a>
                    </div>

                    <!-- Form -->
                    <form id="archiveForm" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nomor Berkas -->
                            <div>
                                <label for="nomorBerkas" class="block text-sm font-medium text-gray-700 mb-1">Nomor Berkas</label>
                                <input type="text" id="nomorBerkas" name="nomorBerkas" class="w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            </div>

                            <!-- Nomor Item Arsip -->
                            <div>
                                <label for="nomorItemArsip" class="block text-sm font-medium text-gray-700 mb-1">Nomor Item Arsip</label>
                                <input type="text" id="nomorItemArsip" name="nomorItemArsip" class="w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            </div>

                            <!-- Kode Klasifikasi -->
                            <div>
                                <label for="kodeKlasifikasi" class="block text-sm font-medium text-gray-700 mb-1">Kode Klasifikasi</label>
                                <select id="kodeKlasifikasi" name="kodeKlasifikasi" class="w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                    <option value="">Pilih Kode Klasifikasi</option>
                                    <option value="KP">KP - Kepegawaian</option>
                                    <option value="KU">KU - Keuangan</option>
                                    <option value="PL">PL - Perlengkapan</option>
                                    <option value="HK">HK - Hukum</option>
                                    <option value="PR">PR - Perencanaan</option>
                                </select>
                            </div>

                            <!-- Tanggal -->
                            <div>
                                <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                                <input type="date" id="tanggal" name="tanggal" class="w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            </div>

                            <!-- Jumlah Item Arsip -->
                            <div>
                                <label for="jumlahItemArsip" class="block text-sm font-medium text-gray-700 mb-1">Jumlah Item Arsip</label>
                                <input type="number" id="jumlahItemArsip" name="jumlahItemArsip" min="1" class="w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            </div>

                            <!-- Keterangan SKAAD -->
                            <div>
                                <label for="keteranganSKAAD" class="block text-sm font-medium text-gray-700 mb-1">Keterangan SKAAD</label>
                                <select id="keteranganSKAAD" name="keteranganSKAAD" class="w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                    <option value="">Pilih Keterangan SKAAD</option>
                                    <option value="Permanen">Permanen</option>
                                    <option value="Musnah">Musnah</option>
                                    <option value="Dinilai Kembali">Dinilai Kembali</option>
                                </select>
                            </div>
                        </div>

                        <!-- Uraian Informasi -->
                        <div>
                            <label for="uraianInformasi" class="block text-sm font-medium text-gray-700 mb-1">Uraian Informasi</label>
                            <textarea id="uraianInformasi" name="uraianInformasi" rows="3" class="w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500" required></textarea>
                        </div>

                        <!-- Keterangan -->
                        <div>
                            <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
                            <textarea id="keterangan" name="keterangan" rows="2" class="w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex justify-end space-x-3">
                            <button type="button" id="resetBtn" class="bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-50">
                                Reset
                            </button>
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for form functionality -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const archiveForm = document.getElementById('archiveForm');
    const resetBtn = document.getElementById('resetBtn');

    // Form submission handler
    archiveForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Validate form
        if (validateForm()) {
            // Here you would normally send the data to the server
            // For now, we'll just show an alert
            alert('Form submitted successfully!');
            
            // Redirect back to semua-arsip page after submission
            window.location.href = 'semua-arsip.php';
        }
    });

    // Reset button handler
    resetBtn.addEventListener('click', function() {
        archiveForm.reset();
    });

    // Form validation function
    function validateForm() {
        let isValid = true;
        const requiredFields = archiveForm.querySelectorAll('[required]');
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                isValid = false;
                field.classList.add('border-red-500');
                
                // Add error message if it doesn't exist
                let errorMsg = field.parentNode.querySelector('.error-message');
                if (!errorMsg) {
                    errorMsg = document.createElement('p');
                    errorMsg.className = 'error-message text-red-500 text-xs mt-1';
                    errorMsg.textContent = 'Field ini harus diisi';
                    field.parentNode.appendChild(errorMsg);
                }
            } else {
                field.classList.remove('border-red-500');
                
                // Remove error message if it exists
                const errorMsg = field.parentNode.querySelector('.error-message');
                if (errorMsg) {
                    errorMsg.remove();
                }
            }
        });
        
        return isValid;
    }

    // Add input event listeners to clear error styling when user types
    const allInputs = archiveForm.querySelectorAll('input, select, textarea');
    allInputs.forEach(input => {
        input.addEventListener('input', function() {
            this.classList.remove('border-red-500');
            
            const errorMsg = this.parentNode.querySelector('.error-message');
            if (errorMsg) {
                errorMsg.remove();
            }
        });
    });
});
</script>

<?php
// Include footer
include_once "../layouts/master/footer.php";
?>