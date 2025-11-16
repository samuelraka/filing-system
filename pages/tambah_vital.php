<?php
// Include header
include_once "../layouts/master/header.php";
?>

<div class="flex h-screen">
    <?php include_once "../layouts/components/sidebar_dynamic.php"; ?>

    <div class="flex-1 flex flex-col ml-64">
        <?php include_once "../layouts/components/topbar.php"; ?>

        <div class="p-6 mt-16 overflow-y-auto">
            <h2 class="text-3xl font-medium text-gray-900 mb-4">Tambah Arsip Vital</h2>
            <div class="bg-white rounded-lg shadow-sm p-6 max-w-screen">
                <div class="flex flex-col space-y-4">
                    <!-- Header -->
                    <div class="flex justify-between items-center mb-8">
                        <a href="vital.php" class="flex items-center text-2xl border-b">
                            <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                            </svg>
                            Kembali
                        </a>
                    </div>
                    <form action="#" method="post" class="space-y-6" enctype="multipart/form-data">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Uraian Arsip -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Uraian Arsip</label>
                                <input 
                                    type="text" 
                                    class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2 
                                        focus:outline-none focus:ring-2 focus:ring-cyan-600" 
                                    placeholder="Deskripsi singkat arsip" 
                                    name="jenis_arsip" required>
                            </div>

                            <!-- Unit Kerja -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Unit Kerja</label>
                                <input 
                                    type="text" 
                                    class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2 
                                        focus:outline-none focus:ring-2 focus:ring-cyan-600" 
                                    placeholder=""
                                    name="tingkat_perkembangan" required>
                            </div>

                            <!-- Kurun Waktu -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Kurun Waktu</label>
                                <input 
                                    type="text" 
                                    class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2 
                                        focus:outline-none focus:ring-2 focus:ring-cyan-600" 
                                    placeholder="Contoh: 2022–2024"
                                    name="kurun_tahun" required>
                            </div>

                            <!-- Media (ubah ke input manual) -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Media</label>
                                <input 
                                    type="text" 
                                    class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2 
                                        focus:outline-none focus:ring-2 focus:ring-cyan-600" 
                                    placeholder="Contoh: Kertas, Digital, Microfilm"
                                    name="media" required>
                            </div>

                            <!-- Jumlah -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Jumlah</label>
                                <input 
                                    type="number" 
                                    class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2 
                                        focus:outline-none focus:ring-2 focus:ring-cyan-600" 
                                    placeholder="Contoh: 3"
                                    name="jumlah" min="1" required>
                            </div>

                            <!-- Jangka Simpan -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Jangka Simpan</label>
                                <input 
                                    type="text" 
                                    class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2 
                                        focus:outline-none focus:ring-2 focus:ring-cyan-600" 
                                    placeholder="Contoh: Permanen, 5 Tahun"
                                    name="jangka_simpan" required>
                            </div>

                            <!-- Lokasi Simpan -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Lokasi Simpan</label>
                                <input 
                                    type="text" 
                                    class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2 
                                        focus:outline-none focus:ring-2 focus:ring-cyan-600" 
                                    placeholder="Contoh: Ruang Arsip 1, Vault Digital"
                                    name="lokasi_simpan" required>
                            </div>

                            <!-- Metode Perlindungan -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Metode Perlindungan</label>
                                <input 
                                    type="text" 
                                    class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2 
                                        focus:outline-none focus:ring-2 focus:ring-cyan-600" 
                                    placeholder="Contoh: Laminasi, Enkripsi, Backup Harian"
                                    name="metode_perlindungan">
                            </div>

                            <!-- Keterangan -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Keterangan</label>
                                <textarea 
                                    rows="3" 
                                    class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2 
                                        focus:outline-none focus:ring-2 focus:ring-cyan-600" 
                                    placeholder="Tambahkan catatan tambahan"
                                    name="keterangan"></textarea>
                            </div>
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

                        <!-- Tombol -->
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

<!-- jQuery (harus sebelum script AJAX) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- SweetAlert (opsional tapi direkomendasikan) -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('fileUpload');
    const fileList = document.getElementById('fileList');

    fileInput.addEventListener('change', function() {
        fileList.innerHTML = '';
        const files = Array.from(this.files);
        files.forEach((file, index) => {
            const div = document.createElement('div');
            div.classList.add('flex', 'items-center', 'justify-between', 'p-2', 'border', 'rounded', 'bg-gray-50');
            div.innerHTML = `
                <span class="text-sm text-gray-700">${index + 1}. ${file.name}</span>
                <span class="text-xs text-gray-500">${(file.size / 1024).toFixed(1)} KB</span>
            `;
            fileList.appendChild(div);
        });
    });
});

$(document).ready(function() {
    $("form").on("submit", function(e) {
        e.preventDefault();

        const formData = new FormData(this);

        $.ajax({
            url: "../api/arsip/arsip_vital/tambah_arsip_vital.php",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function(res) {
                if (res.success) {
                    Swal.fire({
                        icon: "success",
                        title: "Berhasil!",
                        text: res.message,
                        timer: 2000,
                        showConfirmButton: false
                    });
                    $("form")[0].reset();
                    fileList.innerHTML = '';
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Gagal!",
                        text: res.message
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Terjadi kesalahan saat mengirim data!"
                });
            }
        });
    });
});
</script>

<?php
include_once "../layouts/master/footer.php";
?>