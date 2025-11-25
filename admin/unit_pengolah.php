<?php
// unit_pengolah.php
// Page for managing unit pengolah
include_once __DIR__ . '/../config/session.php';
include_once __DIR__ . '/../config/database.php'; // koneksi mysqli

// Ambil data unit pengolah dari database
$result = $conn->query("SELECT * FROM unit_pengolah ORDER BY kode_unit ASC");
$units = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $units[] = $row;
    }
}

// Require login to access this page
// requireLogin();

include __DIR__ . '/../layouts/master/header.php';
include __DIR__ . '/../layouts/components/sidebar_dynamic.php';
?>
<div class="min-h-screen bg-[#fafbfc] flex overflow-hidden">
    <!-- Main Content -->
    <div class="flex-1 flex flex-col ml-64">
        <?php include __DIR__ . '/../layouts/components/topbar.php'; ?>
        <!-- Unit Pengolah Content - Scrollable -->
        <main class="flex-1 p-8 space-y-8 mt-16 overflow-y-auto">
            <!-- Header -->
            <div class="flex justify-between items-center">
                <div class="font-bold text-3xl">Unit Pengolah</div>
                <button onclick="openModal('tambahUnitPengolahModal')" class="bg-cyan-600 hover:bg-cyan-700 text-white font-bold py-2 px-4 rounded">
                    Tambah Unit Pengolah
                </button>
            </div>

            <!-- Unit Pengolah Table Section -->
            <section class="bg-white rounded-xl shadow-sm p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="text-gray-500 border-b">
                                <th class="py-3 px-4 text-left font-medium text-xs uppercase tracking-wider">Kode Unit Pengolah</th>
                                <th class="py-3 px-4 text-left font-medium text-xs uppercase tracking-wider">Nama Unit Pengolah</th>
                                <th class="py-3 px-4 text-left font-medium text-xs uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($units as $unit) : ?>
                            <tr class="hover:bg-gray-50">
                                <td class="py-4 px-4 whitespace-nowrap text-sm font-medium text-gray-900"><?= $unit['kode_unit'] ?></td>
                                <td class="py-4 px-4 whitespace-nowrap text-sm text-gray-500"><?= $unit['nama_unit'] ?></td>
                                <td class="py-4 px-4 whitespace-nowrap text-sm font-medium">
                                    <button 
                                        class="text-cyan-600 hover:text-cyan-900 mr-3 btnEdit" 
                                        data-id="<?= $unit['id_unit'] ?>" 
                                        data-kode="<?= $unit['kode_unit'] ?>" 
                                        data-nama="<?= $unit['nama_unit'] ?>">
                                        <span class="material-icons text-base">edit</span>
                                    </button>
                                    <button 
                                        class="text-red-600 hover:text-red-900 btnDelete" 
                                        data-id="<?= $unit['id_unit'] ?>">
                                        <span class="material-icons text-base">delete</span>
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>
</div>

<!-- Modal Tambah Unit Pengolah -->
<div id="tambahUnitPengolahModal" class="fixed inset-0 bg-gray-600/50 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Tambah Unit Pengolah Baru</h3>
                <button onclick="closeModal('tambahUnitPengolahModal')" class="text-gray-400 hover:text-gray-600">
                    <span class="material-icons">close</span>
                </button>
            </div>
            <form id="addUnitForm">
                <div class="mb-4">
                    <label for="kode_unit" class="block text-sm font-medium text-gray-700">Kode Unit Pengolah</label>
                    <input type="text" name="kode_unit" id="kode_unit" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-cyan-500 focus:border-cyan-500 sm:text-sm">
                </div>
                <div class="mb-6">
                    <label for="nama_unit" class="block text-sm font-medium text-gray-700">Nama Unit Pengolah</label>
                    <input type="text" name="nama_unit" id="nama_unit" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-cyan-500 focus:border-cyan-500 sm:text-sm">
                </div>
                <div class="flex justify-between space-x-3">
                    <button type="button" onclick="closeModal('tambahUnitPengolahModal')" class="px-4 py-2 bg-neutral-50 text-red-500 border border-red-500 rounded-md hover:bg-red-600 hover:text-neutral-50  focus:outline-none">
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

<!-- Modal Edit -->
<div id="modalEdit" class="fixed inset-0 bg-gray-600/50 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 w-96 shadow-lg rounded-md bg-white">
    <h2 class="text-lg font-semibold mb-4">Edit Unit Pengolah</h2>
    <form id="formEdit">
      <input type="hidden" id="edit_id_unit" />
      <div class="mb-4">
        <label for="edit_kode_unit" class="block text-sm font-medium text-gray-700 mb-1">Kode Unit</label>
        <input type="text" id="edit_kode_unit" class="border rounded w-full p-2" required />
      </div>
      <div class="mb-4">
        <label for="edit_nama_unit" class="block text-sm font-medium text-gray-700 mb-1">Nama Unit</label>
        <input type="text" id="edit_nama_unit" class="border rounded w-full p-2" required />
      </div>
      <div class="flex justify-end gap-2">
        <button type="button" id="closeEdit" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Batal</button>
        <button type="submit" class="px-4 py-2 bg-cyan-600 text-white rounded hover:bg-cyan-700">Simpan</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal Delete -->
<div id="modalDelete" class="fixed inset-0 bg-gray-600/50 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
  <div class="relative top-20 mx-auto p-5 w-96 shadow-lg rounded-md bg-white">
    <h2 class="text-lg font-semibold mb-4 text-red-600">Hapus Unit Pengolah?</h2>
    <p class="text-gray-600 mb-6">Apakah kamu yakin ingin menghapus unit ini?</p>
    <div class="flex justify-center gap-3">
      <button id="cancelDelete" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Batal</button>
      <button id="confirmDelete" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Hapus</button>
    </div>
  </div>
</div>


<script>
    // Buka modal berdasarkan ID
    function openModal(modalId) {
        document.getElementById(modalId).classList.remove('hidden');
    }

    // Tutup modal berdasarkan ID
    function closeModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
    }

    // Tangani submit Form Tambah Unit Pengolah via AJAX, tampilkan notifikasi SweetAlert
    document.getElementById('addUnitForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const kode_unit = document.getElementById('kode_unit').value.trim();
        const nama_unit = document.getElementById('nama_unit').value.trim();

        if (!kode_unit || !nama_unit) {
            Swal.fire({
                icon: 'error',
                title: 'Form belum lengkap',
                text: 'Kode unit dan nama unit wajib diisi.',
                confirmButtonColor: '#0092B8'
            });
            return;
        }

        fetch('../api/unit_pengolah/add_unit_pengolah.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ kode_unit, nama_unit })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: data.message,
                    confirmButtonColor: '#0092B8'
                }).then(() => {
                    closeModal('tambahUnitPengolahModal');
                    location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: data.message,
                    confirmButtonColor: '#0092B8'
                });
            }
        })
        .catch(err => {
            console.error('Error:', err);
            Swal.fire({
                icon: 'error',
                title: 'Oops…',
                text: 'Terjadi kesalahan saat menyimpan data.',
                confirmButtonColor: '#0092B8'
            });
        });
    });
    // Ikat handler edit/hapus saat DOM siap
    document.addEventListener("DOMContentLoaded", () => {
        const modalEdit = document.getElementById("modalEdit");
        const modalDelete = document.getElementById("modalDelete");
        const formEdit = document.getElementById("formEdit");

        const editId = document.getElementById("edit_id_unit");
        const editKode = document.getElementById("edit_kode_unit");
        const editNama = document.getElementById("edit_nama_unit");

        let deleteId = null;

        // === OPEN EDIT MODAL ===
        document.querySelectorAll(".btnEdit").forEach(btn => {
            btn.addEventListener("click", () => {
            editId.value = btn.dataset.id;
            editKode.value = btn.dataset.kode;
            editNama.value = btn.dataset.nama;
            modalEdit.classList.remove("hidden");
            });
        });

        // === CLOSE EDIT MODAL ===
        document.getElementById("closeEdit").addEventListener("click", () => {
            modalEdit.classList.add("hidden");
        });

        // === SUBMIT EDIT FORM ===
        formEdit.addEventListener("submit", async (e) => {
            e.preventDefault();
            const data = {
            id_unit: editId.value,
            kode_unit: editKode.value,
            nama_unit: editNama.value
            };
            const res = await fetch("../api/unit_pengolah/edit_unit_pengolah.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(data)
            });
            const result = await res.json();
            if (result.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: result.message,
                    confirmButtonColor: '#0092B8'
                }).then(() => { location.reload(); });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: result.message,
                    confirmButtonColor: '#0092B8'
                });
            }
        });

        // === OPEN DELETE MODAL ===
        document.querySelectorAll(".btnDelete").forEach(btn => {
            btn.addEventListener("click", () => {
            deleteId = btn.dataset.id;
            modalDelete.classList.remove("hidden");
            });
        });

        // === CANCEL DELETE ===
        document.getElementById("cancelDelete").addEventListener("click", () => {
            modalDelete.classList.add("hidden");
        });

        // === CONFIRM DELETE ===
        document.getElementById("confirmDelete").addEventListener("click", async () => {
            const res = await fetch("../api/unit_pengolah/delete_unit_pengolah.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ id_unit: deleteId })
            });
            const result = await res.json();
            if (result.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: result.message,
                    confirmButtonColor: '#0092B8'
                }).then(() => { location.reload(); });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: result.message,
                    confirmButtonColor: '#0092B8'
                });
            }
        });
    });
</script>

<?php
include __DIR__ . '/../layouts/master/footer.php';
?>
