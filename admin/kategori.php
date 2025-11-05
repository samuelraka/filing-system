<?php
// kelola_arsip.php
// Page for managing archive categories (Pokok Masalah, Sub Masalah, Sub-Sub Masalah)
include_once __DIR__ . '/../config/session.php';
include_once __DIR__ . '/../config/database.php'; // koneksi mysqli

// Ambil data unit pengolah dari database
$result = $conn->query("SELECT * FROM pokok_masalah ORDER BY kode_pokok ASC");
$pokokMasalah = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $pokokMasalah[] = $row;
    }
}

$query = "
    SELECT 
        s.id_sub,
        s.kode_sub,
        s.topik_sub,
        s.id_pokok,
        p.kode_pokok
    FROM sub_masalah s
    LEFT JOIN pokok_masalah p ON s.id_pokok = p.id_pokok
    ORDER BY s.id_sub ASC
";
$result = mysqli_query($conn, $query);
$subMasalah = mysqli_fetch_all($result, MYSQLI_ASSOC);

$querySubSub = "
    SELECT 
        s.id_subsub,
        s.kode_subsub,
        s.topik_subsub,
        s.id_sub,
        p.kode_sub
    FROM sub_sub_masalah s
    LEFT JOIN sub_masalah p ON s.id_sub = p.id_sub
    ORDER BY s.id_subsub ASC
";
$result = mysqli_query($conn, $querySubSub);
$subSubMasalah = mysqli_fetch_all($result, MYSQLI_ASSOC);

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
                            <button onclick="openModal('tambahPokokMasalahModal')" class="bg-cyan-600 hover:bg-cyan-700 text-white font-bold py-2 px-4 rounded">
                                Tambah Pokok Masalah
                            </button>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white border border-gray-200 table-centered">
                                <thead>
                                    <tr>
                                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
                                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-xs font-medium text-gray-500 uppercase tracking-wider">Uraian</th>
                                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($pokokMasalah as $index => $pokok) : ?>
                                    <tr>
                                        <td class="py-2 px-4 border-b border-gray-200 text-center"><span class="single-text"><?= $index + 1 ?></span></td>
                                        <td class="py-2 px-4 border-b border-gray-200 text-center"><span class="single-text"><?= $pokok['kode_pokok'] ?></span></td>
                                        <td class="py-2 px-4 border-b border-gray-200 text-center"><span class="single-text"><?= $pokok['topik_pokok'] ?></span></td>
                                        <td class="py-2 px-4 border-b border-gray-200 text-center">
                                            <div class="action-buttons">
                                                <button class="text-cyan-600 hover:text-cyan-900 mr-3 btnEdit"
                                                data-id="<?= $pokok['id_pokok'] ?>"
                                                data-kode="<?= $pokok['kode_pokok'] ?>"
                                                data-nama="<?= $pokok['topik_pokok'] ?>">
                                                    <span class="material-icons text-base">edit</span>
                                                </button>
                                                <button class="text-red-600 hover:text-red-900 btnDelete"
                                                data-id="<?= $pokok['id_pokok'] ?>">
                                                    <span class="material-icons text-base">delete</span>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Sub Masalah Content -->
                    <div id="content-sub-masalah" class="tab-content hidden">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-xl font-semibold text-gray-700">Daftar Sub Masalah</h2>
                            <button onclick="openModal('tambahSubMasalahModal')" class="bg-cyan-600 hover:bg-cyan-700 text-white font-bold py-2 px-4 rounded">
                                Tambah Sub Masalah
                            </button>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white border border-gray-200 table-centered">
                                <thead>
                                    <tr>
                                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
                                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-xs font-medium text-gray-500 uppercase tracking-wider">Pokok Masalah</th>
                                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-xs font-medium text-gray-500 uppercase tracking-wider">Uraian</th>
                                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($subMasalah as $index => $sub) : ?>
                                    <tr>
                                        <td class="py-2 px-4 border-b border-gray-200 text-center"><span class="single-text"><?= $index + 1 ?></span></td>
                                        <td class="py-2 px-4 border-b border-gray-200 text-center"><span class="single-text"><?= $sub['kode_sub'] ?></span></td>
                                        <td class="py-2 px-4 border-b border-gray-200 text-center"><span class="single-text"><?= $sub['kode_pokok'] ?></span></td>
                                        <td class="py-2 px-4 border-b border-gray-200 text-center"><span class="single-text"><?= $sub['topik_sub'] ?></span></td>
                                        <td class="py-2 px-4 border-b border-gray-200 text-center">
                                            <div class="action-buttons">
                                                <button class="text-cyan-600 hover:text-cyan-900 mr-3 btnSubEdit"
                                                data-id="<?= $sub['id_sub'] ?>"
                                                data-kode="<?= $sub['kode_sub'] ?>"
                                                data-pokok="<?= $sub['id_pokok'] ?>"
                                                data-nama="<?= $sub['topik_sub'] ?>">
                                                    <span class="material-icons text-base">edit</span>
                                                </button>
                                                <button class="text-red-600 hover:text-red-900 btnSubDelete"
                                                data-id="<?= $sub['id_sub'] ?>">
                                                    <span class="material-icons text-base">delete</span>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Sub-Sub Masalah Content -->
                    <div id="content-sub-sub-masalah" class="tab-content hidden">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-xl font-semibold text-gray-700">Daftar Sub-Sub Masalah</h2>
                            <button onclick="openModal('tambahSubSubMasalahModal')" class="bg-cyan-600 hover:bg-cyan-700 text-white font-bold py-2 px-4 rounded">
                                Tambah Sub-Sub Masalah
                            </button>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white border border-gray-200 table-centered">
                                <thead>
                                    <tr>
                                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
                                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-xs font-medium text-gray-500 uppercase tracking-wider">Sub Masalah</th>
                                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-xs font-medium text-gray-500 uppercase tracking-wider">Uraian</th>
                                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($subSubMasalah as $index => $subSub): ?>
                                    <tr>
                                        <td class="py-2 px-4 border-b border-gray-200 text-center"><span class="single-text"><?= $index + 1 ?></span></td>
                                        <td class="py-2 px-4 border-b border-gray-200 text-center"><span class="single-text"><?= $subSub['kode_subsub'] ?></span></td>
                                        <td class="py-2 px-4 border-b border-gray-200 text-center"><span class="single-text"><?= $subSub['kode_sub'] ?></span></td>
                                        <td class="py-2 px-4 border-b border-gray-200 text-center"><span class="single-text"><?= $subSub['topik_subsub'] ?></span></td>
                                        <td class="py-2 px-4 border-b border-gray-200 text-center">
                                            <div class="action-buttons">
                                                <button class="text-cyan-600 hover:text-cyan-900 mr-3 btnSubSubEdit"
                                                data-id="<?= $subSub['id_subsub'] ?>"
                                                data-kode="<?= $subSub['kode_subsub'] ?>"
                                                data-sub="<?= $subSub['id_sub'] ?>"
                                                data-nama="<?= $subSub['topik_subsub'] ?>">
                                                    <span class="material-icons text-base">edit</span>
                                                </button>
                                                <button class="text-red-600 hover:text-red-900 btnSubSubDelete"
                                                data-id="<?= $subSub['id_subsub'] ?>">
                                                    <span class="material-icons text-base">delete</span>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>
</div>

<!-- Modal Tambah Pokok Masalah -->
<div id="tambahPokokMasalahModal" class="fixed inset-0 bg-gray-600/50 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Tambah Pokok Masalah Baru</h3>
                <button onclick="closeModal('tambahPokokMasalahModal')" class="text-gray-400 hover:text-gray-600">
                    <span class="material-icons">close</span>
                </button>
            </div>
            <form id="tambahPokokMasalahForm">
                <div class="mb-4">
                    <label for="kode_pokok_masalah" class="block text-sm font-medium text-gray-700">Kode Pokok Masalah</label>
                    <input type="text" name="kode_pokok_masalah" id="kode_pokok_masalah" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-cyan-500 focus:border-cyan-500 sm:text-sm">
                </div>
                <div class="mb-6">
                    <label for="nama_pokok_masalah" class="block text-sm font-medium text-gray-700">Nama Pokok Masalah</label>
                    <input type="text" name="nama_pokok_masalah" id="nama_pokok_masalah" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-cyan-500 focus:border-cyan-500 sm:text-sm">
                </div>
                <div class="flex justify-between space-x-3">
                    <button type="button" onclick="closeModal('tambahPokokMasalahModal')" class="px-4 py-2 bg-neutral-50 text-red-500 border border-red-500 rounded-md hover:bg-red-600 hover:text-neutral-50  focus:outline-none">
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

<!-- Modal Edit Pokok Masalah -->
<div id="modalEditPokokMasalah" class="fixed inset-0 bg-gray-600/50 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 w-96 shadow-lg rounded-md bg-white">
    <h2 class="text-lg font-semibold mb-4">Edit Pokok Masalah</h2>
    <form id="formEditPokokMasalah">
      <input type="hidden" id="edit_id_pokok_masalah" />
      <div class="mb-4">
        <label for="edit_kode_pokok_masalah" class="block text-sm font-medium text-gray-700 mb-1">Kode Pokok Masalah</label>
        <input type="text" id="edit_kode_pokok_masalah" class="border rounded w-full p-2" required />
      </div>
      <div class="mb-4">
        <label for="edit_nama_pokok_masalah" class="block text-sm font-medium text-gray-700 mb-1">Nama Pokok Masalah</label>
        <input type="text" id="edit_nama_pokok_masalah" class="border rounded w-full p-2" required />
      </div>
      <div class="flex justify-end gap-2">
        <button type="button" id="closeEdit" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Batal</button>
        <button type="submit" class="px-4 py-2 bg-cyan-600 text-white rounded hover:bg-cyan-700">Simpan</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal Delete -->
<div id="modalDeletePokokMasalah" class="fixed inset-0 bg-gray-600/50 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
  <div class="relative top-20 mx-auto p-5 w-96 shadow-lg rounded-md bg-white">
    <h2 class="text-lg font-semibold mb-4 text-red-600">Hapus Pokok Masalah?</h2>
    <p class="text-gray-600 mb-6">Apakah kamu yakin ingin menghapus pokok masalah ini?</p>
    <div class="flex justify-center gap-3">
      <button id="cancelDelete" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Batal</button>
      <button id="confirmDelete" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Hapus</button>
    </div>
  </div>
</div>

<!-- Modal Tambah Sub Masalah -->
<div id="tambahSubMasalahModal" class="fixed inset-0 bg-gray-600/50 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
  <div class="relative top-20 mx-auto p-5 w-96 shadow-lg rounded-md bg-white">
    <h2 class="text-lg font-semibold mb-4">Tambah Sub Masalah</h2>
    <form id="formTambahSubMasalah">
      <div class="mb-4">
        <label for="kode_sub_masalah" class="block text-sm font-medium text-gray-700">Kode Sub Masalah</label>
        <input type="text" id="kode_sub_masalah" name="kode_sub_masalah" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-cyan-500 focus:border-cyan-500 sm:text-sm" required>
      </div>
      <div class="mb-4">
        <label for="id_pokok_masalah" class="block text-sm font-medium text-gray-700">Pokok Masalah</label>
        <select id="id_pokok_masalah" name="id_pokok_masalah" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-cyan-500 focus:border-cyan-500 sm:text-sm" required>
          <option value="">-- Pilih Pokok Masalah --</option>
          <?php foreach ($pokokMasalah as $pokok): ?>
            <option value="<?= $pokok['id_pokok'] ?>"><?= $pokok['kode_pokok'] ?> - <?= $pokok['topik_pokok'] ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="mb-6">
        <label for="uraian_sub_masalah" class="block text-sm font-medium text-gray-700">Uraian Sub Masalah</label>
        <input type="text" id="uraian_sub_masalah" name="uraian_sub_masalah" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-cyan-500 focus:border-cyan-500 sm:text-sm" required>
      </div>
      <div class="flex justify-between">
        <button type="button" onclick="closeModal('tambahSubMasalahModal')" class="px-4 py-2 bg-neutral-50 text-red-500 border border-red-500 rounded-md hover:bg-red-600 hover:text-white">
          Batal
        </button>
        <button type="submit" class="px-4 py-2 bg-cyan-600 text-white rounded-md hover:bg-cyan-700">
          Simpan
        </button>
      </div>
    </form>
  </div>
</div>

<!-- Modal Edit Sub Masalah -->
<div id="editSubMasalahModal" class="fixed inset-0 bg-gray-600/50 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
  <div class="relative top-20 mx-auto p-5 w-96 shadow-lg rounded-md bg-white">
    <h2 class="text-lg font-semibold mb-4">Edit Sub Masalah</h2>
    <form id="formEditSubMasalah">
      <input type="hidden" id="edit_id_sub_masalah">
      <div class="mb-4">
        <label for="edit_kode_sub_masalah" class="block text-sm font-medium text-gray-700">Kode Sub Masalah</label>
        <input type="text" id="edit_kode_sub_masalah" class="border rounded w-full p-2" required>
      </div>
      <div class="mb-4">
        <label for="edit_id_pokok" class="block text-sm font-medium text-gray-700">Pokok Masalah</label>
        <select id="edit_id_pokok" class="border rounded w-full p-2" required>
          <option value="">-- Pilih Pokok Masalah --</option>
          <?php foreach ($pokokMasalah as $pokok): ?>
            <option value="<?= $pokok['id_pokok'] ?>"><?= $pokok['kode_pokok'] ?> - <?= $pokok['topik_pokok'] ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="mb-4">
        <label for="edit_uraian_sub_masalah" class="block text-sm font-medium text-gray-700">Uraian Sub Masalah</label>
        <input type="text" id="edit_uraian_sub_masalah" class="border rounded w-full p-2" required>
      </div>
      <div class="flex justify-end gap-2">
        <button type="button" onclick="closeModal('editSubMasalahModal')" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Batal</button>
        <button type="submit" class="px-4 py-2 bg-cyan-600 text-white rounded hover:bg-cyan-700">Simpan</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal Delete -->
<div id="modalDeleteSubMasalah" class="fixed inset-0 bg-gray-600/50 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
  <div class="relative top-20 mx-auto p-5 w-96 shadow-lg rounded-md bg-white">
    <h2 class="text-lg font-semibold mb-4 text-red-600">Hapus Sub Masalah?</h2>
    <p class="text-gray-600 mb-6">Apakah kamu yakin ingin menghapus sub masalah ini?</p>
    <div class="flex justify-center gap-3">
      <button id="cancelSubDelete" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Batal</button>
      <button id="confirmSubDelete" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Hapus</button>
    </div>
  </div>
</div>

<!-- Modal Tambah Sub Sub Masalah -->
<div id="tambahSubSubMasalahModal" class="fixed inset-0 bg-gray-600/50 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Tambah Sub Sub Masalah Baru</h3>
                <button onclick="closeModal('tambahSubSubMasalahModal')" class="text-gray-400 hover:text-gray-600">
                    <span class="material-icons">close</span>
                </button>
            </div>
            <form id="tambahSubSubMasalahForm">
                <div class="mb-4">
                    <label for="kode_sub_sub_masalah" class="block text-sm font-medium text-gray-700">Kode Sub Sub Masalah</label>
                    <input type="text" name="kode_sub_sub_masalah" id="kode_sub_sub_masalah" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-cyan-500 focus:border-cyan-500 sm:text-sm">
                </div>
                <div class="mb-6">
                    <label for="id_sub_masalah" class="block text-sm font-medium text-gray-700">Sub Masalah</label>
                    <select name="id_sub_masalah" id="id_sub_masalah" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-cyan-500 focus:border-cyan-500 sm:text-sm">
                        <option value="">-- Pilih Sub Masalah --</option>
                        <?php foreach ($subMasalah as $sub): ?>
                            <option value="<?= $sub['id_sub'] ?>"><?= $sub['kode_sub'] ?> - <?= $sub['topik_sub'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-6">
                    <label for="uraian_sub_sub_masalah" class="block text-sm font-medium text-gray-700">Uraian Sub Sub Masalah</label>
                    <input type="text" name="uraian_sub_sub_masalah" id="uraian_sub_sub_masalah" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-cyan-500 focus:border-cyan-500 sm:text-sm">
                </div>
                <div class="flex justify-between space-x-3">
                    <button type="button" onclick="closeModal('tambahSubSubMasalahModal')" class="px-4 py-2 bg-neutral-50 text-red-500 border border-red-500 rounded-md hover:bg-red-600 hover:text-neutral-50  focus:outline-none">
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

<!-- Modal Edit Sub Sub Masalah -->
<div id="modalEditSubSubMasalah" class="fixed inset-0 bg-gray-600/50 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 w-96 shadow-lg rounded-md bg-white">
    <h2 class="text-lg font-semibold mb-4">Edit Sub Sub Masalah</h2>
    <form id="formEditSubSubMasalah">
      <input type="hidden" id="edit_id_sub_sub_masalah" />
      <div class="mb-4">
        <label for="edit_kode_sub_sub_masalah" class="block text-sm font-medium text-gray-700 mb-1">Kode Sub Sub Masalah</label>
        <input type="text" id="edit_kode_sub_sub_masalah" class="border rounded w-full p-2" required />
      </div>
      <div class="mb-4">
        <label for="edit_id_sub" class="block text-sm font-medium text-gray-700 mb-1">Sub Masalah</label>
        <select name="edit_id_sub" id="edit_id_sub" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-cyan-500 focus:border-cyan-500 sm:text-sm">
            <option value="">-- Pilih Sub Masalah --</option>
            <?php foreach ($subMasalah as $sub): ?>
                <option value="<?= $sub['id_sub'] ?>"><?= $sub['kode_sub'] ?> - <?= $sub['topik_sub'] ?></option>
            <?php endforeach; ?>
        </select>
      </div>
      <div class="mb-6">
        <label for="edit_topik_sub_sub_masalah" class="block text-sm font-medium text-gray-700 mb-1">Topik Sub Sub Masalah</label>
        <input type="text" id="edit_topik_sub_sub_masalah" class="border rounded w-full p-2" required />
      </div>
      <div class="flex justify-end gap-2">
        <button type="button" id="closeSubSubEdit" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Batal</button>
        <button type="submit" class="px-4 py-2 bg-cyan-600 text-white rounded hover:bg-cyan-700">Simpan</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal Delete Sub Sub Masalah -->
<div id="modalDeleteSubSubMasalah" class="fixed inset-0 bg-gray-600/50 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
  <div class="relative top-20 mx-auto p-5 w-96 shadow-lg rounded-md bg-white">
    <h2 class="text-lg font-semibold mb-4 text-red-600">Hapus Sub Sub Masalah?</h2>
    <p class="text-gray-600 mb-6">Apakah kamu yakin ingin menghapus sub sub masalah ini?</p>
    <div class="flex justify-center gap-3">
      <button id="cancelSubSubDelete" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Batal</button>
      <button id="confirmSubSubDelete" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Hapus</button>
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

//Bagian Pokok Masalah
document.getElementById('tambahPokokMasalahForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const kode = document.getElementById('kode_pokok_masalah').value.trim();
    const nama = document.getElementById('nama_pokok_masalah').value.trim();

    if (!kode || !nama) {
        alert('Semua field wajib diisi.');
        return;
    }

    try {
        const response = await fetch('../api/pokok_masalah/tambah_pokok_masalah.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                kode_pokok_masalah: kode,
                nama_pokok_masalah: nama
            })
        });

        const data = await response.json();
        alert(data.message);

        if (data.success) {
            // reset form & tutup modal
            e.target.reset();
            closeModal('tambahPokokMasalahModal');
            // reload data tabel jika perlu
            if (typeof loadPokokMasalah === 'function') loadPokokMasalah();
        }
    } catch (error) {
        console.error(error);
        alert('Terjadi kesalahan saat menambahkan data.');
    }
});

    document.addEventListener("DOMContentLoaded", () => {
        const modalEdit = document.getElementById("modalEditPokokMasalah");
        const modalDelete = document.getElementById("modalDeletePokokMasalah");
        const formEdit = document.getElementById("formEditPokokMasalah");

        const editId = document.getElementById("edit_id_pokok_masalah");
        const editKode = document.getElementById("edit_kode_pokok_masalah");
        const editNama = document.getElementById("edit_nama_pokok_masalah");

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
            id_pokok_masalah: editId.value,
            kode_pokok_masalah: editKode.value,
            nama_pokok_masalah: editNama.value
            };
            const res = await fetch("../api/pokok_masalah/edit_pokok_masalah.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(data)
            });
            const result = await res.json();
            alert(result.message);
            if (result.success) location.reload();
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
            const res = await fetch("../api/pokok_masalah/delete_pokok_masalah.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ id_pokok_masalah: deleteId })
            });
            const result = await res.json();
            alert(result.message);
            if (result.success) location.reload();
        });
    });

document.addEventListener("DOMContentLoaded", () => {
  // === TAMBAH SUB MASALAH ===
  const formTambah = document.getElementById("formTambahSubMasalah");
  formTambah.addEventListener("submit", async (e) => {
    e.preventDefault();
    const data = {
      kode_sub_masalah: document.getElementById("kode_sub_masalah").value,
      id_pokok_masalah: document.getElementById("id_pokok_masalah").value,
      uraian_sub_masalah: document.getElementById("uraian_sub_masalah").value,
    };

    const res = await fetch("../api/sub_masalah/tambah_sub_masalah.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(data),
    });

    const result = await res.json();
    alert(result.message);
    if (result.success) location.reload();
  });

  // === EDIT SUB MASALAH ===
  const modalEditSub = document.getElementById("editSubMasalahModal");
  const formEditSub = document.getElementById("formEditSubMasalah");

  document.querySelectorAll(".btnSubEdit").forEach((btn) => {
    btn.addEventListener("click", () => {
      document.getElementById("edit_id_sub_masalah").value = btn.dataset.id;
      document.getElementById("edit_kode_sub_masalah").value = btn.dataset.kode;
      document.getElementById("edit_id_pokok").value = btn.dataset.pokok;
      document.getElementById("edit_uraian_sub_masalah").value = btn.dataset.nama;
      modalEditSub.classList.remove("hidden");
    });
  });

  formEditSub.addEventListener("submit", async (e) => {
    e.preventDefault();
    const data = {
      id_sub_masalah: document.getElementById("edit_id_sub_masalah").value,
      kode_sub_masalah: document.getElementById("edit_kode_sub_masalah").value,
      id_pokok: document.getElementById("edit_id_pokok").value,
      uraian_sub_masalah: document.getElementById("edit_uraian_sub_masalah").value,
    };

    const res = await fetch("../api/sub_masalah/edit_sub_masalah.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(data),
    });

    const result = await res.json();
    alert(result.message);
    if (result.success) location.reload();
  });

  const modalDeleteSub = document.getElementById("modalDeleteSubMasalah");

  // === OPEN DELETE MODAL ===
  document.querySelectorAll(".btnSubDelete").forEach(btn => {
    btn.addEventListener("click", () => {
        deleteId = btn.dataset.id;
        modalDeleteSub.classList.remove("hidden");
        });
    });

    // === CANCEL DELETE ===
    document.getElementById("cancelSubDelete").addEventListener("click", () => {
        modalDeleteSub.classList.add("hidden");
    });

    // === CONFIRM DELETE ===
    document.getElementById("confirmSubDelete").addEventListener("click", async () => {
        const res = await fetch("../api/sub_masalah/delete_sub_masalah.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ id_sub_masalah: deleteId })
        });
        const result = await res.json();
        alert(result.message);
        if (result.success) location.reload();
    });
});

document.addEventListener("DOMContentLoaded", () => {
  // === TAMBAH SUB MASALAH ===
  const formTambahSubSubMasalah = document.getElementById("tambahSubSubMasalahForm");
  formTambahSubSubMasalah.addEventListener("submit", async (e) => {
    e.preventDefault();
    const data = {
      kode_sub_sub_masalah: document.getElementById("kode_sub_sub_masalah").value,
      id_sub_masalah: document.getElementById("id_sub_masalah").value,
      uraian_sub_sub_masalah: document.getElementById("uraian_sub_sub_masalah").value,
    };

    const res = await fetch("../api/sub_sub_masalah/tambah_sub_sub_masalah.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(data),
    });

    const result = await res.json();
    alert(result.message);
    if (result.success) location.reload();
  });

    // === EDIT SUB SUB MASALAH ===
    const modalEditSubSubMasalah = document.getElementById("modalEditSubSubMasalah");
    const formEditSubSubMasalah = document.getElementById("formEditSubSubMasalah");

    // tombol edit Sub Sub Masalah
    document.querySelectorAll(".btnSubSubEdit").forEach((btn) => {
    btn.addEventListener("click", () => {
        // isi data ke dalam modal
        document.getElementById("edit_id_sub_sub_masalah").value = btn.dataset.id;
        document.getElementById("edit_kode_sub_sub_masalah").value = btn.dataset.kode;
        document.getElementById("edit_id_sub").value = btn.dataset.sub;
        document.getElementById("edit_topik_sub_sub_masalah").value = btn.dataset.nama;

        // tampilkan modal
        modalEditSubSubMasalah.classList.remove("hidden");
    });
    });

    // tombol batal
    document.getElementById("closeSubSubEdit").addEventListener("click", () => {
    modalEditSubSubMasalah.classList.add("hidden");
    });

    // submit edit
    formEditSubSubMasalah.addEventListener("submit", async (e) => {
    e.preventDefault();
    const data = {
        id_sub_sub_masalah: document.getElementById("edit_id_sub_sub_masalah").value,
        kode_sub_sub_masalah: document.getElementById("edit_kode_sub_sub_masalah").value,
        id_sub: document.getElementById("edit_id_sub").value,
        topik_sub_sub_masalah: document.getElementById("edit_topik_sub_sub_masalah").value,
    };

    const res = await fetch("../api/sub_sub_masalah/edit_sub_sub_masalah.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(data),
    });

    const result = await res.json();
    alert(result.message);
    if (result.success) location.reload();
    });

  const modalDeleteSubSubMasalah = document.getElementById("modalDeleteSubSubMasalah");

  // === OPEN DELETE MODAL ===
  document.querySelectorAll(".btnSubSubDelete").forEach(btn => {
    btn.addEventListener("click", () => {
        deleteId = btn.dataset.id;
        modalDeleteSubSubMasalah.classList.remove("hidden");
        });
    });

    // === CANCEL DELETE ===
    document.getElementById("cancelSubSubDelete").addEventListener("click", () => {
        modalDeleteSubSubMasalah.classList.add("hidden");
    });

    // === CONFIRM DELETE ===
    document.getElementById("confirmSubSubDelete").addEventListener("click", async () => {
        const res = await fetch("../api/sub_sub_masalah/delete_sub_sub_masalah.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ id_sub_sub_masalah: deleteId })
        });
        const result = await res.json();
        alert(result.message);
        if (result.success) location.reload();
    });
});

</script>

<?php
include __DIR__ . '/../layouts/master/footer.php';
?>
