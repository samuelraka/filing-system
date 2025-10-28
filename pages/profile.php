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
        <div class="p-6 mt-16 flex flex-col items-center">
            <div class="bg-white rounded-lg shadow-sm p-8 w-full max-w-3xl">
                <h2 class="text-2xl font-medium text-gray-900 mb-6 text-center">Pengaturan Profil</h2>
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
                        <label class="block text-sm font-medium text-gray-700 mb-1">Unit Pengelola</label>
                        <input type="text" class="w-full border border-gray-300 rounded-md py-2 px-3 bg-gray-100" value="UPT Arsip" disabled />
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="button" id="changePasswordBtn" class="bg-slate-600 hover:bg-slate-600/90 text-white px-4 py-2 rounded-md mt-6">Ubah Password</button>
                    </div>
                    <div id="passwordChangeSection" class="space-y-2 hidden">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" class="w-full border border-gray-300 rounded-md py-2 px-3 bg-gray-100" value="john.doe@email.com" disabled />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                            <input type="username" class="w-full border border-gray-300 rounded-md py-2 px-3 bg-gray-100" value="j0hn_doe" disabled />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Password Lama</label>
                            <input type="password" id="newPassword" class="w-full border border-gray-300 rounded-md py-2 px-3" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                            <input type="password" id="newPassword" class="w-full border border-gray-300 rounded-md py-2 px-3" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password Baru</label>
                            <input type="password" id="confirmPassword" class="w-full border border-gray-300 rounded-md py-2 px-3" />
                        </div>
                        <div class="flex justify-end gap-2">
                            <button type="button" id="cancelPasswordBtn" class="bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-50">Batal</button>
                            <button type="button" id="savePasswordBtn" class="bg-slate-600 hover:bg-slate-600/90 text-white px-4 py-2 rounded-md">Simpan Password</button>
                        </div>
                    </div>
                </form>
            </div>
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
});
</script>

<?php
// Include footer
include_once "../layouts/master/footer.php";
?>
