<?php
// login.php
// Login page for archiving system
include_once __DIR__ . '/config/session.php';

// Handle logout request
if (isset($_GET['logout']) && $_GET['logout'] === 'true') {
    logout();
}

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (login($email, $password)) {
        header('Location: pages/dashboard.php');
        exit();
    } else {
        $error = 'Email atau password salah';
    }
}

include __DIR__ . '/layouts/master/header.php';
?>

<div class="min-h-screen bg-gradient-to-br from-cyan-50 via-blue-50 to-indigo-100 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative overflow-hidden">

    <div class="max-w-md w-full space-y-8 relative z-10">
        <!-- Logo and Title -->
        <div class="text-center flex flex-col items-center justify-center gap-2 mb-8">
            <div class="flex items-center justify-center gap-2 mb-2">
                <span class="material-icons text-cyan-600 text-5xl">inventory_2</span>
                <span class="font-bold text-4xl tracking-wide text-slate-800">ArsipOnline</span>
            </div>
        </div>

        <!-- Login Form with Glass Effect -->
        <div class="bg-white/80 backdrop-blur-lg rounded-2xl shadow-xl border border-white/20 p-8">

            <h2 class="text-center text-2xl font-bold text-slate-700 mb-8">LOGIN USER</h2>
             
             <!-- Error Message -->
            <?php if (isset($error)): ?>
                <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-md">
                    <p class="text-sm text-red-600"><?php echo $error; ?></p>
                </div>
            <?php endif; ?>
            
            <form class="space-y-5" id="loginForm" method="POST">
                <!-- Username -->
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                            <span class="material-icons text-gray-400 text-lg">person</span>
                        </div>
                        <input id="username" name="username" type="text" required class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 bg-white/70 backdrop-blur-sm" placeholder="Masukkan Username">
                    </div>
                    <p id="emailError" class="mt-1 text-sm text-red-600 hidden"></p>
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                            <span class="material-icons text-gray-400 text-lg">lock</span>
                        </div>
                        <input id="password" name="password" type="password" required class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 bg-white/70 backdrop-blur-sm" placeholder="Masukkan Password">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <button type="button" id="togglePassword" class="text-gray-400 hover:text-gray-600 focus:outline-none" style="line-height: 0;">
                                <span class="material-icons text-sm">visibility</span>
                            </button>
                        </div>
                    </div>
                    <p id="passwordError" class="mt-1 text-sm text-red-600 hidden"></p>
                </div>

                <!-- Button -->
                <div>
                    <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-md text-sm font-medium text-white bg-cyan-600 hover:bg-cyan-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500 transition-all duration-200 transform hover:scale-[1.02]">
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
                // Submit the form
                loginForm.submit();
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
