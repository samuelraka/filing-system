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
                    <form id="archiveForm" class="space-y-6" enctype="multipart/form-data">
                        <input type="hidden" id="idArsip" name="idArsip">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Kode Klasifikasi -->
                            <div>
                                <label for="id_subsub" class="block text-sm font-medium text-gray-700 mb-1">Kode Klasifikasi</label>
                                <select id="id_subsub" name="id_subsub" class="w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                    <option value="">Pilih Kode Klasifikasi</option>
                                    <?php
                                    include "../config/database.php";
                                    $query = $conn->query("SELECT id_subsub, kode_subsub, topik_subsub FROM sub_sub_masalah ORDER BY kode_subsub ASC");
                                    while ($row = $query->fetch_assoc()) {
                                        echo "<option value='{$row['id_subsub']}'>{$row['kode_subsub']} - {$row['topik_subsub']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <!-- Checkbox Buat Berkas Baru -->
                            <div class="flex items-center mt-2">
                                <input id="buatBerkasBaru" name="buatBerkasBaru" type="checkbox"
                                    class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                <label for="buatBerkasBaru" class="ml-2 block text-sm text-gray-700">
                                    Buat nomor berkas baru untuk kode klasifikasi ini
                                </label>
                            </div>

                            <!-- Nomor Berkas -->
                            <div>
                                <label for="nomorBerkas" class="block text-sm font-medium text-gray-700 mb-1">Nomor Berkas</label>
                                <input type="text" id="nomorBerkas" name="nomorBerkas" class="w-full border border-gray-300 rounded-md py-2 px-3" readonly>
                            </div>

                            <!-- Nomor Item Arsip -->
                            <div>
                                <label for="nomorItemArsip" class="block text-sm font-medium text-gray-700 mb-1">Nomor Item Arsip</label>
                                <input type="text" id="nomorItemArsip" name="nomorItemArsip" class="w-full border border-gray-300 rounded-md py-2 px-3" readonly>
                            </div>

                            <!-- Tanggal -->
                            <div>
                                <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                                <input type="date" id="tanggal" name="tanggal" class="w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            </div>

                            <!-- Keterangan SKAAD -->
                            <div>
                                <label for="keteranganSKAAD" class="block text-sm font-medium text-gray-700 mb-1">Keterangan SKAAD</label>
                                <select id="keteranganSKAAD" name="keteranganSKAAD" class="w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                    <option value="">Pilih Keterangan SKAAD</option>
                                    <option value="Biasa">Biasa</option>
                                    <option value="Rahasia">Rahasia</option>
                                    <option value="Terbatas">Terbatas</option>
                                </select>
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

                        <!-- Uraian Singkat -->
                        <div>
                            <label for="uraianSingkat" class="block text-sm font-medium text-gray-700 mb-1">Uraian Singkat</label>
                            <textarea id="uraianSingkat" name="uraianSingkat" rows="3" class="w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500" required></textarea>
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
    const idSubsub = document.getElementById('id_subsub');
    const nomorBerkas = document.getElementById('nomorBerkas');
    const nomorItemArsip = document.getElementById('nomorItemArsip');
    const fileInput = document.getElementById('fileUpload');
    const fileList = document.getElementById('fileList');
    const buatBerkasBaru = document.getElementById('buatBerkasBaru');
    const idArsipInput = document.getElementById('idArsip'); // hidden input untuk id_arsip

    // ✅ Preview nama file
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

    // ✅ Ambil nomor berkas & item arsip
    function getNomorBerkas() {
        const id_subsub = idSubsub.value;
        const buatBaru = buatBerkasBaru.checked ? 1 : 0;
        const nomorBerkasValue = nomorBerkas.value ? `&nomor_berkas=${nomorBerkas.value}` : "";

        if (!id_subsub) return;

        fetch(`../api/arsip/arsip_aktif/get_nomor_berkas.php?id_subsub=${id_subsub}&buatBaru=${buatBaru}${nomorBerkasValue}`)
            .then(res => res.json())
            .then(data => {
                console.log("Response get_nomor_berkas:", data);

                if (data.status === 'existing') {
                    nomorBerkas.value = data.nomor_berkas;
                    nomorItemArsip.value = data.nomor_item;
                    idArsipInput.value = data.id_arsip || '';
                } else {
                    // Jika buat baru atau belum ada arsip, reset item ke 1
                    nomorBerkas.value = data.nomor_berkas || '';
                    nomorItemArsip.value = 1;
                    idArsipInput.value = '';
                }
            })
            .catch(err => console.error("Error get_nomor_berkas:", err));
    }

    // 🔄 Trigger perubahan ketika user pilih kode klasifikasi
    idSubsub.addEventListener('change', getNomorBerkas);

    // 🔄 Ketika checkbox buat berkas baru dicentang
    buatBerkasBaru.addEventListener("change", function() {
        if (this.checked) {
            nomorBerkas.removeAttribute('readonly');
            nomorBerkas.focus();
            nomorItemArsip.value = 1; // reset item arsip
            idArsipInput.value = '';  // kosongkan id arsip
        } else {
            nomorBerkas.setAttribute('readonly', true);
            getNomorBerkas(); // ambil ulang data default
        }
    });

    // ✅ Saat form disubmit
    archiveForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(archiveForm);

        fetch('../api/arsip/arsip_aktif/proses_tambah_arsip.php', {
            method: 'POST',
            body: formData
        })
        .then(async (res) => {
            const text = await res.text();
            try {
                const data = JSON.parse(text);
                alert(data.message);
                if (data.success) {
                    window.location.href = "aktif.php";
                }
            } catch (err) {
                console.error("Response bukan JSON:", text);
                alert("Terjadi kesalahan saat menyimpan data (cek log).");
            }
        })
        .catch(err => {
            console.error('Error:', err);
            alert('Terjadi kesalahan saat mengirim data.');
        });
    });
});
</script>

<?php
// Include footer
include_once "../layouts/master/footer.php";
?>