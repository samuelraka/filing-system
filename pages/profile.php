<?php
// Include header
include_once "../layouts/master/header.php";

include_once '../config/session.php';
include_once '../config/database.php'; // pastikan koneksi $conn tersedia

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

// Ambil data user berdasarkan session
$id_user = $_SESSION['user_id'];
$stmt = $conn->prepare("
    SELECT u.nama, u.username, u.email, up.nama_unit
    FROM user u
    LEFT JOIN unit_pengolah up ON up.id_unit = (
        SELECT uu.id_unit
        FROM profil uu
        WHERE uu.id_user = u.id_user
        LIMIT 1
    )
    WHERE u.id_user = ?
");
$stmt->bind_param("i", $id_user);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    $user = [
        'nama' => 'Tidak diketahui',
        'username' => '-',
        'email' => '-',
        'nama_unit' => '-'
    ];
}

$stmt->close();
?>
    <!-- Include sidebar -->
    <?php include_once "../layouts/components/sidebar_dynamic.php"; ?>

    <!-- Main content area -->
    <div class="ml-64">
        <!-- Include topbar -->
        <?php include_once "../layouts/components/topbar.php"; ?>

        <!-- Main content -->
        <div class="p-6 pt-20">
            <div class="flex flex-col items-center space-y-6 max-w-4xl mx-auto">
            <div class="bg-white rounded-lg shadow-sm p-8 w-full max-w-3xl">
                    <h2 class="text-2xl font-medium text-gray-900 mb-6 border-b-1 pb-3 border-b-neutral-500">Pengaturan Profil</h2>
                    <form id="profileForm" class="space-y-6">
                        <div class="flex items-center mb-6">
                            <!-- Left side: Profile Picture -->
                            <div class="flex-shrink-0">
                                <img id="profilePic" src="https://ui-avatars.com/api/?name=John+Doe" alt="Profile Picture" class="w-24 h-24 rounded-md object-cover border border-gray-300" />
                            </div>
                            
                            <!-- Right side: Button and Info -->
                            <div class="flex flex-col items-start space-y-2 ml-6">
                                <input type="file" id="profilePicInput" name="profilePic" accept="image/*" class="hidden"/>
                                <button type="button" id="changePicBtn" class="bg-slate-600 hover:bg-slate-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">Ganti Foto Profil</button>
                                <div class="text-xs text-gray-500 text-left max-w-xs">
                                    <p>Gambar profile sebaiknya memiliki resolusi 1:1 dan berukuran tidak lebih dari 2MB.</p>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                            <input type="text" class="w-full border border-gray-300 rounded-md py-2 px-3 bg-gray-100" value="<?php echo $user['nama']; ?>" disabled />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" class="w-full border border-gray-300 rounded-md py-2 px-3 bg-gray-100" value="<?php echo $user['email']; ?>" disabled />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Unit Pengelola</label>
                            <input type="text"
                                class="w-full border border-gray-300 rounded-md py-2 px-3 bg-gray-100"
                                value="<?php echo !empty($user['nama_unit']) ? $user['nama_unit'] : 'Belum Ditambahkan'; ?>"
                                disabled />
                        </div>
                    </form>
                </div>

                <!-- Change Email Section -->
                <div class="bg-white rounded-lg shadow-sm p-8 w-full max-w-3xl mt-6">
                    <h2 class="text-2xl font-medium text-gray-900 mb-6 border-b-1 pb-3 border-b-neutral-500">Ubah Email</h2>
                    <form id="changeEmailForm" class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email Saat Ini</label>
                            <input type="email" class="w-full border border-gray-300 rounded-md py-2 px-3 bg-gray-100" value="<?php echo $user['email']; ?>" disabled />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email Baru</label>
                            <input type="email" id="newEmail" name="newEmail" class="w-full border border-gray-300 rounded-md py-2 px-3" placeholder="Masukkan email baru" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Email Baru</label>
                            <input type="email" id="confirmEmail" name="confirmEmail" class="w-full border border-gray-300 rounded-md py-2 px-3" placeholder="Konfirmasi email baru" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                            <input type="password" id="emailPassword" name="password" class="w-full border border-gray-300 rounded-md py-2 px-3" placeholder="Masukkan password untuk konfirmasi" />
                        </div>
                        <div class="flex justify-end gap-2">
                            <button type="button" id="cancelEmailBtn" class="bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-50">Batal</button>
                            <button type="button" id="saveEmailBtn" class="bg-slate-600 hover:bg-slate-600/90 text-white px-4 py-2 rounded-md">Simpan Email</button>
                        </div>
                    </form>
                </div>

                <!-- Account Settings Section -->
                <div class="bg-white rounded-lg shadow-sm p-8 w-full max-w-3xl mt-6">
                    <h2 class="text-2xl font-medium text-gray-900 mb-6 border-b-1 pb-3 border-b-neutral-500">Ubah Username</h2>
                    <form id="usernameForm" class="space-y-6">
                        <!-- Username Section -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Username Saat Ini</label>
                            <input type="text" class="w-full border border-gray-300 rounded-md py-2 px-3 bg-gray-100" value="<?php echo $user['username']; ?>" disabled />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Username Baru</label>
                            <input type="text" id="newUsername" name="newUsername" class="w-full border border-gray-300 rounded-md py-2 px-3" placeholder="Masukkan username baru" />
                        </div>
                        
                        <!-- Tombol Simpan & Batal -->
                        <div class="flex justify-end gap-2">
                            <button type="button" id="cancelUsernameBtn" 
                                    class="bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-50">
                                Batal
                            </button>
                            <button type="button" id="saveUsernameBtn" 
                                    class="bg-slate-600 hover:bg-slate-600/90 text-white px-4 py-2 rounded-md">
                                Simpan Username
                            </button>
                        </div>
                    </form>
                </div>
                <!-- ====== Ubah Password Section ====== -->
                <div class="bg-white rounded-lg shadow-sm p-8 w-full max-w-3xl mt-6">
                    <h2 class="text-2xl font-medium text-gray-900 mb-6 border-b-1 pb-3 border-b-neutral-500">Ubah Password</h2>

                    <!-- Tombol toggle tampil/hidden -->
                    <button type="button" id="changePasswordBtn"
                            class="bg-slate-600 hover:bg-slate-600/90 text-white px-4 py-2 rounded-md mb-4">
                        Ganti Password
                    </button>

                    <div id="passwordChangeSection" class="hidden">
                        <form id="passwordForm" class="space-y-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Password Saat Ini</label>
                                <input type="password" id="currentPassword" name="currentPassword" 
                                    class="w-full border border-gray-300 rounded-md py-2 px-3" 
                                    placeholder="Masukkan password saat ini" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                                <input type="password" id="newPassword" name="newPassword" 
                                    class="w-full border border-gray-300 rounded-md py-2 px-3" 
                                    placeholder="Masukkan password baru" />
                                <div class="text-xs text-gray-500 text-left">
                                    <p>Minimal 8 karakter dengan kombinasi huruf, angka, dan karakter spesial.</p>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password Baru</label>
                                <input type="password" id="confirmPassword" name="confirmPassword" 
                                    class="w-full border border-gray-300 rounded-md py-2 px-3" 
                                    placeholder="Konfirmasi password baru" />
                            </div>

                            <!-- Tombol -->
                            <div class="flex justify-end gap-2">
                                <button type="button" id="cancelPasswordBtn"
                                        class="bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-50">
                                    Batal
                                </button>
                                <button type="button" id="savePasswordBtn"
                                        class="bg-slate-600 hover:bg-slate-600/90 text-white px-4 py-2 rounded-md">
                                    Simpan Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            <div class="pb-8"></div>
        </div>
    </div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const changePicBtn = document.getElementById('changePicBtn');
    const profilePicInput = document.getElementById('profilePicInput');
    const profilePic = document.getElementById('profilePic');
    changePicBtn.addEventListener('click', function() {
        profilePicInput.click();
    });
    profilePicInput.addEventListener('change', function(e) {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                profilePic.src = e.target.result;
            };
            reader.readAsDataURL(this.files[0]);
        }
    });

    // ========== GANTI PASSWORD ==========
    const changePasswordBtn = document.getElementById('changePasswordBtn');
    const passwordChangeSection = document.getElementById('passwordChangeSection');
    const cancelPasswordBtn = document.getElementById('cancelPasswordBtn');
    const savePasswordBtn = document.getElementById('savePasswordBtn');

    changePasswordBtn.addEventListener('click', () => {
    passwordChangeSection.classList.remove('hidden');
    changePasswordBtn.classList.add('hidden');
    });

    cancelPasswordBtn.addEventListener('click', () => {
    passwordChangeSection.classList.add('hidden');
    changePasswordBtn.classList.remove('hidden');
    });

    savePasswordBtn.addEventListener('click', async () => {
    const currentPassword = document.getElementById('currentPassword').value;
    const newPassword = document.getElementById('newPassword').value;
    const confirmPassword = document.getElementById('confirmPassword').value;

    if (!currentPassword || !newPassword || !confirmPassword) {
        alert('Semua field password wajib diisi.');
        return;
    }
    if (newPassword !== confirmPassword) {
        alert('Password baru dan konfirmasi tidak cocok.');
        return;
    }

    // Kirim ke backend
    try {
        const res = await fetch('../api/profile/update_password.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            currentPassword,
            newPassword,
            confirmPassword
        })
        });
        const data = await res.json();
        alert(data.message);

        if (data.success) {
        passwordChangeSection.classList.add('hidden');
        changePasswordBtn.classList.remove('hidden');
        document.getElementById('currentPassword').value = '';
        document.getElementById('newPassword').value = '';
        document.getElementById('confirmPassword').value = '';
        }
    } catch (err) {
        console.error(err);
        alert('Terjadi kesalahan saat mengubah password.');
    }
    });


    // ========== GANTI EMAIL ==========
    const saveEmailBtn = document.getElementById('saveEmailBtn');
    const cancelEmailBtn = document.getElementById('cancelEmailBtn');
    const changeEmailForm = document.getElementById('changeEmailForm');

    saveEmailBtn.addEventListener('click', async () => {
    const newEmail = document.getElementById('newEmail').value;
    const confirmEmail = document.getElementById('confirmEmail').value;
    const password = document.getElementById('emailPassword').value;

    if (!newEmail || !confirmEmail || !password) {
        alert('Semua field wajib diisi.');
        return;
    }
    if (newEmail !== confirmEmail) {
        alert('Email baru dan konfirmasi tidak cocok.');
        return;
    }

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(newEmail)) {
        alert('Format email tidak valid.');
        return;
    }

    try {
        const res = await fetch('../api/profile/update_email.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            newEmail,
            confirmEmail,
            password
        })
        });

        const data = await res.json();
        alert(data.message);

        if (data.success) changeEmailForm.reset();
    } catch (err) {
        console.error(err);
        alert('Terjadi kesalahan saat mengubah email.');
    }
    });

    cancelEmailBtn.addEventListener('click', () => changeEmailForm.reset());


    // ========== GANTI USERNAME ==========
    const saveUsernameBtn = document.getElementById('saveUsernameBtn');
    const cancelUsernameBtn = document.getElementById('cancelUsernameBtn');
    const accountSettingsForm = document.getElementById('accountSettingsForm');

    saveUsernameBtn.addEventListener('click', async () => {
    const newUsername = document.getElementById('newUsername').value;

    if (!newUsername) {
        alert('Silakan masukkan username baru.');
        return;
    }

    if (newUsername.length < 3) {
        alert('Username minimal 3 karakter.');
        return;
    }

    if (!/^[a-zA-Z0-9_]+$/.test(newUsername)) {
        alert('Username hanya boleh huruf, angka, dan underscore.');
        return;
    }

    try {
        const res = await fetch('../api/profile/update_username.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ newUsername })
        });

        const data = await res.json();
        alert(data.message);

        if (data.success) accountSettingsForm.reset();
    } catch (err) {
        console.error(err);
        alert('Terjadi kesalahan saat mengubah username.');
    }
    });

    cancelUsernameBtn.addEventListener('click', () => {
    if (confirm('Yakin ingin membatalkan perubahan?')) {
        accountSettingsForm.reset();
    }
    });
});
</script>

<?php
// Include footer
include_once "../layouts/master/footer.php";
?>
