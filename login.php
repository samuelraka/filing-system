<?php
// login.php
// Login page for archiving system
include __DIR__ . '/layouts/master/header.php';
?>

<div class="min-h-screen bg-[#fafbfc] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <!-- Logo and Title -->
        <div class="text-center flex flex-col items-center justify-center gap-2 mb-5 pb-5">
            <div class="flex items-center justify-center gap-2 mb-2">
                <span class="material-icons text-orange-500 text-4xl">inventory_2</span>
                <span class="font-bold text-2xl tracking-wide text-gray-900">ArsipOnline</span>
            </div>
            <h2 class="text-center text-4xl font-bold text-gray-700">LOGIN</h2>
        </div>

        <!-- Login Form -->
        <div class="bg-white rounded-xl shadow-sm p-8">
            <form class="space-y-6" id="loginForm">
                <!-- Username/Email Field -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="material-icons text-gray-400 text-sm">person</span>
                        </div>
                        <input id="email" name="email" type="text" required class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500" placeholder="Masukkan Email">
                    </div>
                    <p id="emailError" class="mt-1 text-sm text-red-600 hidden"></p>
                </div>

                <!-- Password Field -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="material-icons text-gray-400 text-sm">lock</span>
                        </div>
                        <input id="password" name="password" type="password" required class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500" placeholder="Masukkan Password">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <button type="button" id="togglePassword" class="text-gray-400 hover:text-gray-600 focus:outline-none" style="line-height: 0;">
                                <span class="material-icons text-sm">visibility</span>
                            </button>
                        </div>
                    </div>
                    <p id="passwordError" class="mt-1 text-sm text-red-600 hidden"></p>
                </div>

                <!-- Login Button -->
                <div>
                    <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-orange-500 hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                        Masuk
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const loginForm = document.getElementById('loginForm');
        const emailInput = document.getElementById('email');
        const passwordInput = document.getElementById('password');
        const togglePassword = document.getElementById('togglePassword');
        
        // Toggle password visibility
        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            // Toggle icon
            const icon = this.querySelector('.material-icons');
            icon.textContent = type === 'password' ? 'visibility' : 'visibility_off';
        });
        
        // Form validation
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            let isValid = true;
            
            // Reset error states
            document.getElementById('emailError').classList.add('hidden');
            document.getElementById('passwordError').classList.add('hidden');
            emailInput.classList.remove('border-red-500', 'ring-red-500');
            passwordInput.classList.remove('border-red-500', 'ring-red-500');
            
            // Validate email/username
            if (!emailInput.value.trim()) {
                showError(emailInput, 'emailError', 'Email atau username harus diisi');
                isValid = false;
            }
            
            // Validate password
            if (!passwordInput.value) {
                showError(passwordInput, 'passwordError', 'Password harus diisi');
                isValid = false;
            }
            
            if (isValid) {
                // For demo purposes, redirect to dashboard
                window.location.href = 'pages/dashboard.php';
            }
        });
        
        // Show error message
        function showError(inputElement, errorId, message) {
            inputElement.classList.add('border-red-500', 'ring-red-500');
            const errorElement = document.getElementById(errorId);
            errorElement.textContent = message;
            errorElement.classList.remove('hidden');
        }
    });
</script>

<?php
include __DIR__ . '/layouts/master/footer.php';
?>