<?php
// kelola_pengguna.php
include_once __DIR__ . '/../config/session.php';
include_once __DIR__ . '/../config/database.php';

// Pastikan user login
if (!isset($_SESSION['user_role'])) {
    header("Location: ../login.php");
    exit;
}

$role = $_SESSION['user_role'];

// Query dasar ambil semua user
$query = "SELECT u.id_user, u.nama, u.email, u.username, u.role, COALESCE(up.nama_unit, '') AS nama_unit, COALESCE(p.id_unit, '') AS id_unit FROM user u LEFT JOIN profil p ON p.id_user = u.id_user LEFT JOIN unit_pengolah up ON p.id_unit = up.id_unit";

// Filter sesuai role login
if ($role === 'admin') {
    // Admin tidak boleh lihat superadmin
    $query .= " WHERE role IN ('admin', 'user')";
} elseif ($role === 'superadmin') {
    // Superadmin bisa lihat semua
    $query .= " WHERE role IN ('superadmin', 'admin', 'user')";
} else {
    // Kalau user biasa coba akses halaman ini → redirect
    header("Location: dashboard.php");
    exit;
}

$result = $conn->query($query);
$users = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}

// Ambil semua unit pengolah
$units = [];
$sql = "SELECT id_unit, nama_unit FROM unit_pengolah ORDER BY nama_unit ASC";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $units[] = $row;
    }
}

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
                            <?php if (!empty($users)) : ?>
                                <?php foreach ($users as $user) : ?>
                                    <tr class="hover:bg-gray-50">
                                        <td class="py-4 px-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            <?= htmlspecialchars($user['nama']) ?>
                                        </td>
                                        <td class="py-4 px-4 whitespace-nowrap text-sm text-gray-500">
                                            <?= htmlspecialchars($user['nama_unit'] ?: '-') ?>
                                        </td>
                                        <td class="py-4 px-4 whitespace-nowrap text-sm text-gray-500">
                                            <?= htmlspecialchars($user['email']) ?>
                                        </td>
                                        <td class="py-4 px-4 whitespace-nowrap text-sm text-gray-500">
                                            <?= htmlspecialchars($user['username']) ?>
                                        </td>
                                        <td class="py-4 px-4 whitespace-nowrap text-sm text-gray-500">
                                            ********
                                        </td>
                                        <td class="py-4 px-4 whitespace-nowrap text-sm font-medium">
                                            <button class="text-cyan-600 hover:text-cyan-900 mr-3"
                                                onClick="editPengguna(<?= $user['id_user'] ?>)">
                                                <span class="material-icons text-base">edit</span>
                                            </button>
                                            <button class="text-red-600 hover:text-red-900 btnDelete"
                                                data-id="<?= $user['id_user'] ?>">
                                                <span class="material-icons text-base">delete</span>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="6" class="py-4 px-4 text-center text-gray-500">Tidak ada pengguna ditemukan.</td>
                                </tr>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
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

            <form id="formTambahPengguna">
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Nama</label>
                    <input type="text" name="name" id="name" required
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-cyan-500 focus:border-cyan-500 sm:text-sm">
                </div>

                <div class="mb-4">
                    <label for="unit_pengolah" class="block text-sm font-medium text-gray-700">Unit Pengolah</label>
                    <select name="unit_pengolah" id="unit_pengolah"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm 
                        focus:outline-none focus:ring-cyan-500 focus:border-cyan-500 sm:text-sm"
                        <?= empty($units) ? 'disabled' : '' ?>>
                        
                        <?php if (empty($units)): ?>
                            <option value="">Belum ada unit pengolah terdaftar</option>
                        <?php else: ?>
                            <option value="">-- Pilih Unit Pengolah --</option>
                            <?php foreach ($units as $unit): ?>
                                <option value="<?= htmlspecialchars($unit['id_unit']) ?>">
                                    <?= htmlspecialchars($unit['nama_unit']) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>

                    <?php if (empty($units)): ?>
                        <p class="text-xs text-red-500 mt-1">
                            ⚠️ Tidak ada unit pengolah di database. Tambahkan unit pengolah terlebih dahulu sebelum menambah pengguna.
                        </p>
                    <?php endif; ?>
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" id="email" required
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-cyan-500 focus:border-cyan-500 sm:text-sm">
                </div>

                <!-- FIELD ROLE DINAMIS -->
                <div class="mb-6">
                    <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                    <select name="role" id="role"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-cyan-500 focus:border-cyan-500 sm:text-sm">
                        <?php if ($_SESSION['user_role'] === 'superadmin') : ?>
                            <option value="admin">Admin</option>
                            <option value="user">User</option>
                        <?php elseif ($_SESSION['user_role'] === 'admin') : ?>
                            <option value="user">User</option>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                    <div class="flex gap-2">
                        <input type="text" name="username" id="username" readonly
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-100 focus:outline-none focus:ring-cyan-500 focus:border-cyan-500 sm:text-sm">
                        <button type="button" onclick="generateUsername()" 
                            class="px-3 py-2 bg-cyan-600 text-white rounded-md hover:bg-cyan-700">
                            Buat
                        </button>
                    </div>
                </div>

                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <div class="flex gap-2">
                        <input type="text" name="password" id="password" readonly
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-100 focus:outline-none focus:ring-cyan-500 focus:border-cyan-500 sm:text-sm">
                        <button type="button" onclick="generatePassword()" 
                            class="px-3 py-2 bg-cyan-600 text-white rounded-md hover:bg-cyan-700">
                            Buat
                        </button>
                    </div>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeModal('tambahPenggunaModal')"
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-cyan-600 text-white rounded-md hover:bg-cyan-700 focus:outline-none">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Pengguna Modal -->
<div id="editPenggunaModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
  <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
    <div class="mt-3">
      <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-medium text-gray-900">Edit Pengguna</h3>
        <button onclick="closeModal('editPenggunaModal')" class="text-gray-400 hover:text-gray-600">
          <span class="material-icons">close</span>
        </button>
      </div>
      <form id="formEditPengguna" method="POST">
        <input type="hidden" id="edit_id_user" name="id_user">

        <div class="mb-4">
          <label for="edit_name" class="block text-sm font-medium text-gray-700">Name</label>
          <input type="text" id="edit_name" name="name" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-cyan-500 focus:border-cyan-500 sm:text-sm">
        </div>

        <div class="mb-4">
          <label for="edit_unit_pengolah" class="block text-sm font-medium text-gray-700">Unit Pengolah</label>
          <select id="edit_unit_pengolah" name="unit_pengolah" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-cyan-500 focus:border-cyan-500 sm:text-sm">
            <option value="">Pilih Unit Pengolah</option>
            <?php foreach ($units as $unit): ?>
              <option value="<?= $unit['id_unit'] ?>"><?= htmlspecialchars($unit['nama_unit']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="mb-4">
          <label for="edit_email" class="block text-sm font-medium text-gray-700">Email</label>
          <input type="email" id="edit_email" name="email" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-cyan-500 focus:border-cyan-500 sm:text-sm">
        </div>

        <div class="mb-4">
          <label for="edit_username" class="block text-sm font-medium text-gray-700">Username</label>
          <input type="text" id="edit_username" name="username" readonly
            class="bg-gray-100 cursor-not-allowed mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm sm:text-sm">
        </div>

        <div class="mb-4">
          <label for="edit_role" class="block text-sm font-medium text-gray-700">Role</label>
            <select id="edit_role" name="role" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-cyan-500 focus:border-cyan-500 sm:text-sm">
                <?php if ($_SESSION['user_role'] === 'superadmin'): ?>
                    <option value="superadmin">Superadmin</option>
                    <option value="admin">Admin</option>
                    <option value="user">User</option>
                <?php elseif ($_SESSION['user_role'] === 'admin'): ?>
                    <option value="admin">Admin</option>
                    <option value="user">User</option>
                <?php endif; ?>
            </select>
        </div>

        <div class="mb-6">
            <label for="edit_password" class="block text-sm font-medium text-gray-700">Password</label>
            <div class="flex gap-2">
                <input type="text" name="password" id="edit_password" readonly
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-100 focus:outline-none focus:ring-cyan-500 focus:border-cyan-500 sm:text-sm">
                <button type="button" onclick="generatePassword()"
                    class="px-3 py-2 bg-cyan-600 text-white rounded-md hover:bg-cyan-700">
                    Reset
                </button>
            </div>
        </div>

        <div class="flex justify-end space-x-3">
          <button type="button" onclick="closeModal('editPenggunaModal')" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none">
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
        if (modalId === 'tambahPenggunaModal') {
            generateUsername();
            generatePassword();
        }
    }

    function closeModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
    }
    function generateUsername() {
    const randomPart = Math.random().toString(36).substring(2, 7).toUpperCase(); // contoh: "A7C3F"
    const username = "usr" + randomPart; // hasil: usrA7C3F
    document.getElementById("username").value = username;
    }

    function generatePassword() {
        const upper = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        const lower = "abcdefghijklmnopqrstuvwxyz";
        const numbers = "0123456789";
        const symbols = "!@#$%^&*()_+{}[]<>?";
        const all = upper + lower + numbers + symbols;

        let password = "";
        password += upper[Math.floor(Math.random() * upper.length)];
        password += lower[Math.floor(Math.random() * lower.length)];
        password += numbers[Math.floor(Math.random() * numbers.length)];
        password += symbols[Math.floor(Math.random() * symbols.length)];

        // isi sisa karakter acak hingga panjang 8
        while (password.length < 8) {
            password += all[Math.floor(Math.random() * all.length)];
        }

        // acak ulang urutan agar tidak berurutan jenisnya
        password = password.split('').sort(() => 0.5 - Math.random()).join('');

        document.getElementById("password").value = password;
    }

    document.getElementById("formTambahPengguna").addEventListener("submit", async function (e) {
        e.preventDefault(); // mencegah reload form

        const formData = {
            name: document.getElementById("name").value.trim(),
            email: document.getElementById("email").value.trim(),
            username: document.getElementById("username").value.trim(),
            password: document.getElementById("password").value.trim(),
            role: document.getElementById("role").value.trim(),
            unit_pengolah: document.getElementById("unit_pengolah").value.trim()
        };

        try {
            const res = await fetch("../api/user/add_user.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify(formData)
            });

            const data = await res.json();
            alert(data.message);

            if (data.success) {
                closeModal('tambahPenggunaModal');
                // optional: refresh halaman agar tabel update
                setTimeout(() => location.reload(), 800);
            }
        } catch (error) {
            alert("Terjadi kesalahan saat menambah pengguna.");
            console.error(error);
        }
    });

    async function editPengguna(id_user) {
        try {
            const res = await fetch(`../api/user/get_user.php?id_user=${id_user}`);
            const data = await res.json();

            if (!data.success) {
                alert(data.message);
                return;
            }

            const user = data.user;

            // isi form edit
            document.getElementById("edit_id_user").value = user.id_user;
            document.getElementById("edit_name").value = user.nama;
            document.getElementById("edit_email").value = user.email;
            document.getElementById("edit_username").value = user.username;

            // pastikan role cocok dengan value <option>
            if (user.role) {
                document.getElementById("edit_role").value = user.role.toLowerCase();
            }

            // isi unit_pengolah kalau ada
            if (user.id_unit) {
                document.getElementById("edit_unit_pengolah").value = user.id_unit;
            } else {
                document.getElementById("edit_unit_pengolah").value = "";
            }

            // tampilkan modal
            openModal("editPenggunaModal");

        } catch (error) {
            console.error("Error saat mengambil data pengguna:", error);
            alert("Terjadi kesalahan saat mengambil data pengguna.");
        }
    }


    document.getElementById("formEditPengguna").addEventListener("submit", async function (e) {
    e.preventDefault();

    const formData = {
        id_user: document.getElementById("edit_id_user").value,
        name: document.getElementById("edit_name").value.trim(),
        email: document.getElementById("edit_email").value.trim(),
        unit_pengolah: document.getElementById("edit_unit_pengolah").value.trim(),
        role: document.getElementById("edit_role").value.trim(),
        password: document.getElementById("edit_password").value.trim()
    };

    try {
        const res = await fetch("../api/user/edit_user.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(formData)
        });

        const data = await res.json();
        alert(data.message);

        if (data.success) {
        closeModal("editPenggunaModal");
        setTimeout(() => location.reload(), 800);
        }
    } catch (err) {
        console.error("Error:", err);
        alert("Terjadi kesalahan saat mengupdate pengguna.");
    }
    });

    document.querySelectorAll('.btnDelete').forEach(button => {
        button.addEventListener('click', async (e) => {
            const id = e.currentTarget.getAttribute('data-id');

            if (!confirm('Apakah Anda yakin ingin menghapus pengguna ini?')) return;

            try {
            const res = await fetch('../api/user/delete_user.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id_user: id })
            });

            const data = await res.json();
            alert(data.message);

            if (data.success) {
                setTimeout(() => location.reload(), 800);
            }
            } catch (err) {
            console.error('❌ Error saat menghapus:', err);
            alert('Terjadi kesalahan saat menghapus pengguna.');
            }
        });
        });

</script>

<?php
include __DIR__ . '/../layouts/master/footer.php';
?>
