<?php
// Include header
include_once "../layouts/master/header.php";
?>

<div class="flex h-screen bg-gray-100">
    <!-- Include sidebar -->
    <?php include_once "../layouts/components/sidebar_dynamic.php"; ?>

    <div class="flex-1 flex flex-col ml-64">
        <!-- Include topbar -->
        <?php include_once "../layouts/components/topbar.php"; ?>

        <!-- Main content -->
        <div class="p-6 mt-16 overflow-y-auto flex flex-col gap-y-4">
            <h2 class="text-3xl font-medium text-gray-900 mb-4">Tambah Arsip Aktif</h2>
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

                            <!-- Kode Klasifikasi Correct One -->

                            <div>
                                <label for="kodeKlasifikasi" class="block text-sm font-medium text-gray-700 mb-1">Kode Klasifikasi</label>
                                <input type="text" id="kodeKlasifikasi" name="kodeKlasifikasi" class="w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            </div>

                            <!-- <div>
                                <label for="kodeKlasifikasi" class="block text-sm font-medium text-gray-700 mb-1">Kode Klasifikasi</label>
                                <select id="kodeKlasifikasi" name="kodeKlasifikasi" class="w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                    <option value="">Pilih Kode Klasifikasi</option>
                                    <option value="KP">KP - Kepegawaian</option>
                                    <option value="KU">KU - Keuangan</option>
                                    <option value="PL">PL - Perlengkapan</option>
                                    <option value="HK">HK - Hukum</option>
                                    <option value="PR">PR - Perencanaan</option>
                                </select>
                            </div> -->

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
                                <input type="text" id="keteranganSKAAD" name="keteranganSKAAD" class="w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            </div>
                            <!-- <div>
                                <label for="keteranganSKAAD" class="block text-sm font-medium text-gray-700 mb-1">Keterangan SKAAD</label>
                                <select id="keteranganSKAAD" name="keteranganSKAAD" class="w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                    <option value="">Pilih Keterangan SKAAD</option>
                                    <option value="Permanen">Permanen</option>
                                    <option value="Musnah">Musnah</option>
                                    <option value="Dinilai Kembali">Dinilai Kembali</option>
                                </select>
                            </div> -->
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

                        <!-- File Upload -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Upload Dokumen PDF</label>
                            <div class="flex flex-col space-y-2">
                                <div class="flex items-center justify-center w-full">
                                    <label for="fileUpload" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                            <svg class="w-8 h-8 mb-3 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                            </svg>
                                            <p class="mb-1 text-sm text-gray-500">Klik area ini untuk memulai upload file</p>
                                            <p class="text-xs text-gray-500">PDF (Maksimal 10 file)</p>
                                        </div>
                                        <input id="fileUpload" name="files[]" type="file" class="hidden" accept=".pdf" multiple />
                                    </label>
                                </div>
                                <div id="fileList" class="mt-2 space-y-2"></div>
                            </div>
                        </div>

                        <!-- Form Actions -->
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

<!-- JavaScript for form functionality -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const archiveForm = document.getElementById('archiveForm');
    const resetBtn = document.getElementById('resetBtn');
    const fileUpload = document.getElementById('fileUpload');
    const fileList = document.getElementById('fileList');

    // Form submission handler
    archiveForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Validate form
        if (validateForm()) {
            // Here you would normally send the data to the server
            // For now, we'll just show an alert
            alert('Form submitted successfully!');
            
            // Redirect back to aktif page after submission
            window.location.href = 'aktif.php';
        }
    });

    // Reset button handler
    resetBtn.addEventListener('click', function() {
        archiveForm.reset();
        // Clear file list
        fileList.innerHTML = '';
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
        
        // Validate file uploads if any
        if (fileUpload.files.length > 10) {
            isValid = false;
            let errorMsg = fileUpload.parentNode.parentNode.querySelector('.error-message');
            if (!errorMsg) {
                errorMsg = document.createElement('p');
                errorMsg.className = 'error-message text-red-500 text-xs mt-1';
                errorMsg.textContent = 'Maksimal 10 file yang dapat diunggah';
                fileUpload.parentNode.parentNode.appendChild(errorMsg);
            }
        }
        
        // Check if all files are PDFs
        if (fileUpload.files.length > 0) {
            for (let i = 0; i < fileUpload.files.length; i++) {
                const file = fileUpload.files[i];
                if (!file.type.match('application/pdf')) {
                    isValid = false;
                    let errorMsg = fileUpload.parentNode.parentNode.querySelector('.error-message');
                    if (!errorMsg) {
                        errorMsg = document.createElement('p');
                        errorMsg.className = 'error-message text-red-500 text-xs mt-1';
                        errorMsg.textContent = 'Hanya file PDF yang diperbolehkan';
                        fileUpload.parentNode.parentNode.appendChild(errorMsg);
                    }
                    break;
                }
            }
        }
        
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
    
    // File upload handling
    fileUpload.addEventListener('change', function(e) {
        fileList.innerHTML = '';
        
        // Check if files are selected
        if (this.files.length > 0) {
            // Clear any existing error messages
            const errorMsg = this.parentNode.parentNode.querySelector('.error-message');
            if (errorMsg) {
                errorMsg.remove();
            }
            
            // Check if number of files exceeds limit
            if (this.files.length > 10) {
                let errorMsg = document.createElement('p');
                errorMsg.className = 'error-message text-red-500 text-xs mt-1';
                errorMsg.textContent = 'Maksimal 10 file yang dapat diunggah';
                this.parentNode.parentNode.appendChild(errorMsg);
                this.value = ''; // Clear the file input
                return;
            }
            
            // Display selected files
            for (let i = 0; i < this.files.length; i++) {
                const file = this.files[i];
                
                // Validate file type
                if (!file.type.match('application/pdf')) {
                    let errorMsg = document.createElement('p');
                    errorMsg.className = 'error-message text-red-500 text-xs mt-1';
                    errorMsg.textContent = 'Hanya file PDF yang diperbolehkan';
                    this.parentNode.parentNode.appendChild(errorMsg);
                    fileList.innerHTML = '';
                    this.value = ''; // Clear the file input
                    return;
                }
                
                // Create file item
                const fileItem = document.createElement('div');
                fileItem.className = 'flex items-center justify-between p-2 bg-gray-50 rounded border border-gray-200 mb-2';
                
                // File info
                const fileInfo = document.createElement('div');
                fileInfo.className = 'flex items-center';
                
                // PDF icon
                const fileIcon = document.createElement('div');
                fileIcon.className = 'mr-2 text-red-500';
                fileIcon.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
                    </svg>
                `;
                
                // File name and size
                const fileName = document.createElement('div');
                fileName.className = 'text-sm';
                fileName.textContent = file.name;
                
                const fileSize = document.createElement('div');
                fileSize.className = 'text-xs text-gray-500';
                fileSize.textContent = formatFileSize(file.size);
                
                const fileNameContainer = document.createElement('div');
                fileNameContainer.appendChild(fileName);
                fileNameContainer.appendChild(fileSize);
                
                fileInfo.appendChild(fileIcon);
                fileInfo.appendChild(fileNameContainer);
                
                // Remove button
                const removeBtn = document.createElement('button');
                removeBtn.type = 'button';
                removeBtn.className = 'text-gray-400 hover:text-red-500';
                removeBtn.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                `;
                removeBtn.dataset.index = i;
                
                fileItem.appendChild(fileInfo);
                fileItem.appendChild(removeBtn);
                fileList.appendChild(fileItem);
            }
            
            // Add event listeners to remove buttons
            const removeButtons = fileList.querySelectorAll('button');
            removeButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Since we can't directly modify the FileList object, we need to reset the file input
                    fileUpload.value = '';
                    fileList.innerHTML = '';
                    
                    // Clear error message if exists
                    const errorMsg = fileUpload.parentNode.parentNode.querySelector('.error-message');
                    if (errorMsg) {
                        errorMsg.remove();
                    }
                });
            });
        }
    });
    
    // Format file size
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }
});
</script>

<?php
// Include footer
include_once "../layouts/master/footer.php";
?>