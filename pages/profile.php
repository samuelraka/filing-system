<?php
// Include header
include_once "../layouts/master/header.php";
?>

<div class="min-h-screen bg-gray-100">
    <!-- Include sidebar -->
    <?php include_once "../layouts/components/sidebar.php"; ?>

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
                            <input type="text" class="w-full border border-gray-300 rounded-md py-2 px-3 bg-gray-100" value="John Doe" disabled />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" class="w-full border border-gray-300 rounded-md py-2 px-3 bg-gray-100" value="john.doe@email.com" disabled />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Unit Pengelola</label>
                            <input type="text" class="w-full border border-gray-300 rounded-md py-2 px-3 bg-gray-100" value="UPT Arsip" disabled />
                        </div>
                    </form>
                </div>

                <!-- Change Email Section -->
                <div class="bg-white rounded-lg shadow-sm p-8 w-full max-w-3xl mt-6">
                    <h2 class="text-2xl font-medium text-gray-900 mb-6 border-b-1 pb-3 border-b-neutral-500">Ubah Email</h2>
                    <form id="changeEmailForm" class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email Saat Ini</label>
                            <input type="email" class="w-full border border-gray-300 rounded-md py-2 px-3 bg-gray-100" value="john.doe@email.com" disabled />
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
                            <input type="text" class="w-full border border-gray-300 rounded-md py-2 px-3 bg-gray-100" value="j0hn_doe" disabled />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Username Baru</label>
                            <input type="text" id="newUsername" name="newUsername" class="w-full border border-gray-300 rounded-md py-2 px-3" placeholder="Masukkan username baru" />
                        </div>
                    </form>
                </div>
                
                <!-- Account Settings Section -->
                <div class="bg-white rounded-lg shadow-sm p-8 w-full max-w-3xl mt-6">
                    <h2 class="text-2xl font-medium text-gray-900 mb-6 border-b-1 pb-3 border-b-neutral-500">Ubah Password</h2>
                    <form id="passwordForm" class="space-y-6">
                        <!-- Password Section -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Password Saat Ini</label>
                                    <input type="password" id="currentPassword" name="currentPassword" class="w-full border border-gray-300 rounded-md py-2 px-3" placeholder="Masukkan password saat ini" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                                    <input type="password" id="newAccountPassword" name="newPassword" class="w-full border border-gray-300 rounded-md py-2 px-3" placeholder="Masukkan password baru" />
                                    <div class="text-xs text-gray-500 text-left">
                                        <p>Minimal 8 karakter dengan kombinasi huruf, angka, dan karakter spesial.</p>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password Baru</label>
                                    <input type="password" id="confirmAccountPassword" name="confirmPassword" class="w-full border border-gray-300 rounded-md py-2 px-3" placeholder="Konfirmasi password baru" />
                                </div>                        
                        <div class="flex justify-end gap-2">
                            <button type="button" id="cancelAccountBtn" class="bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-50">Batal</button>
                            <button type="button" id="saveAccountBtn" class="bg-slate-600 hover:bg-slate-600/90 text-white px-4 py-2 rounded-md">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            <div class="pb-8"></div>
        </div>
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

    // Password change logic
    const changePasswordBtn = document.getElementById('changePasswordBtn');
    const passwordChangeSection = document.getElementById('passwordChangeSection');
    const cancelPasswordBtn = document.getElementById('cancelPasswordBtn');
    const savePasswordBtn = document.getElementById('savePasswordBtn');
    const passwordField = document.getElementById('passwordField');
    changePasswordBtn.addEventListener('click', function() {
        passwordChangeSection.classList.remove('hidden');
        changePasswordBtn.classList.add('hidden');
    });
    cancelPasswordBtn.addEventListener('click', function() {
        passwordChangeSection.classList.add('hidden');
        changePasswordBtn.classList.remove('hidden');
    });
    savePasswordBtn.addEventListener('click', function() {
        const newPassword = document.getElementById('newPassword').value;
        const confirmPassword = document.getElementById('confirmPassword').value;
        if (!newPassword || !confirmPassword) {
            alert('Silakan isi semua field password baru.');
            return;
        }
        if (newPassword !== confirmPassword) {
            alert('Password baru dan konfirmasi tidak cocok.');
            return;
        }
        // Simulate password change
        passwordField.value = newPassword;
        passwordChangeSection.classList.add('hidden');
        changePasswordBtn.classList.remove('hidden');
        alert('Password berhasil diubah!');
    });

    // Change Email logic
    const saveEmailBtn = document.getElementById('saveEmailBtn');
    const cancelEmailBtn = document.getElementById('cancelEmailBtn');
    const changeEmailForm = document.getElementById('changeEmailForm');

    saveEmailBtn.addEventListener('click', function() {
        const newEmail = document.getElementById('newEmail').value;
        const confirmEmail = document.getElementById('confirmEmail').value;
        const password = document.getElementById('emailPassword').value;

        if (!newEmail || !confirmEmail || !password) {
            alert('Silakan isi semua field.');
            return;
        }

        if (newEmail !== confirmEmail) {
            alert('Email baru dan konfirmasi email tidak cocok.');
            return;
        }

        // Email validation
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(newEmail)) {
            alert('Format email tidak valid.');
            return;
        }

        // Simulate email change
        alert('Email berhasil diubah!');
        changeEmailForm.reset();
    });

    cancelEmailBtn.addEventListener('click', function() {
        changeEmailForm.reset();
    });

    // Account Settings logic
    const saveAccountBtn = document.getElementById('saveAccountBtn');
    const cancelAccountBtn = document.getElementById('cancelAccountBtn');
    const accountSettingsForm = document.getElementById('accountSettingsForm');

    saveAccountBtn.addEventListener('click', function() {
        const newUsername = document.getElementById('newUsername').value;
        const currentPassword = document.getElementById('currentPassword').value;
        const newAccountPassword = document.getElementById('newAccountPassword').value;
        const confirmAccountPassword = document.getElementById('confirmAccountPassword').value;

        // Check if at least one field is filled
        if (!newUsername && !currentPassword && !newAccountPassword && !confirmAccountPassword) {
            alert('Silakan isi username baru atau password untuk melakukan perubahan.');
            return;
        }

        // Validate username change
        if (newUsername) {
            if (newUsername.length < 3) {
                alert('Username minimal harus 3 karakter.');
                return;
            }
            if (!/^[a-zA-Z0-9_]+$/.test(newUsername)) {
                alert('Username hanya boleh mengandung huruf, angka, dan underscore.');
                return;
            }
        }

        // Validate password change
        if (currentPassword || newAccountPassword || confirmAccountPassword) {
            if (!currentPassword || !newAccountPassword || !confirmAccountPassword) {
                alert('Untuk mengubah password, semua field password harus diisi.');
                return;
            }

            if (newAccountPassword.length < 6) {
                alert('Password baru minimal harus 6 karakter.');
                return;
            }

            if (newAccountPassword !== confirmAccountPassword) {
                alert('Password baru dan konfirmasi password tidak cocok.');
                return;
            }
        }

        // Collect account data
        const accountData = {
            newUsername: newUsername || null,
            currentPassword: currentPassword || null,
            newPassword: newAccountPassword || null
        };

        // Simulate saving account changes
        console.log('Saving account changes:', accountData);
        
        let successMessage = 'Perubahan berhasil disimpan!';
        if (newUsername && newAccountPassword) {
            successMessage = 'Username dan password berhasil diubah!';
        } else if (newUsername) {
            successMessage = 'Username berhasil diubah!';
        } else if (newAccountPassword) {
            successMessage = 'Password berhasil diubah!';
        }

        alert(successMessage);
        accountSettingsForm.reset();
    });

    cancelAccountBtn.addEventListener('click', function() {
        if (confirm('Apakah Anda yakin ingin membatalkan perubahan?')) {
            accountSettingsForm.reset();
        }
    });
});
</script>

<?php
// Include footer
include_once "../layouts/master/footer.php";
?>
