<?php
// login.php
// Login page for archiving system
include_once __DIR__ . '/config/session.php';

// Handle logout request
if (isset($_GET['logout']) && $_GET['logout'] === 'true') {
    logout();
}

// Handle login form submission (fallback for non-JS users)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (login($username, $password)) {
        header('Location: pages/dashboard.php');
        exit();
    } else {
        $error = 'Username atau password salah';
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
        const usernameInput = document.getElementById('username');
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
        
        // Form validation and API submission
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            let isValid = true;
            
            // Reset error states
            document.getElementById('emailError').classList.add('hidden');
            document.getElementById('passwordError').classList.add('hidden');
            usernameInput.classList.remove('border-red-500', 'ring-red-500');
            passwordInput.classList.remove('border-red-500', 'ring-red-500');
            
            // Validate username
            if (!usernameInput.value.trim()) {
                showError(usernameInput, 'emailError', 'Username harus diisi');
                isValid = false;
            }
            
            // Validate password
            if (!passwordInput.value) {
                showError(passwordInput, 'passwordError', 'Password harus diisi');
                isValid = false;
            }
            
            if (isValid) {
                // Show loading state
                const submitButton = loginForm.querySelector('button[type="submit"]');
                const originalText = submitButton.textContent;
                submitButton.textContent = 'Memproses...';
                submitButton.disabled = true;
                
                // Call API endpoint
                fetch('http://localhost:3003/arsip/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        username: usernameInput.value.trim(),
                        password: passwordInput.value
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Store user data in sessionStorage for client-side usage
                        sessionStorage.setItem('user', JSON.stringify(data.data.user));
                        sessionStorage.setItem('token', data.data.token);
                        
                        // Redirect to dashboard
                        window.location.href = 'pages/dashboard.php';
                    } else {
                        // Show error message
                        showApiError(data.message || 'Login gagal');
                    }
                })
                .catch(error => {
                    console.error('Login error:', error);
                    showApiError('Terjadi kesalahan koneksi. Silakan coba lagi.');
                })
                .finally(() => {
                    // Restore button state
                    submitButton.textContent = originalText;
                    submitButton.disabled = false;
                });
            }
        });
        
        // Show error message
        function showError(inputElement, errorId, message) {
            inputElement.classList.add('border-red-500', 'ring-red-500');
            const errorElement = document.getElementById(errorId);
            errorElement.textContent = message;
            errorElement.classList.remove('hidden');
        }
        
        // Show API error message
        function showApiError(message) {
            // Create or update error message div
            let errorDiv = document.getElementById('apiError');
            if (!errorDiv) {
                errorDiv = document.createElement('div');
                errorDiv.id = 'apiError';
                errorDiv.className = 'mb-4 p-3 bg-red-50 border border-red-200 rounded-md';
                const errorMessage = document.createElement('p');
                errorMessage.className = 'text-sm text-red-600';
                errorDiv.appendChild(errorMessage);
                
                // Insert before the form
                loginForm.parentNode.insertBefore(errorDiv, loginForm);
            }
            
            errorDiv.querySelector('p').textContent = message;
            
            // Auto-hide after 5 seconds
            setTimeout(() => {
                errorDiv.remove();
            }, 5000);
        }
    });
</script>

<?php
include __DIR__ . '/layouts/master/footer.php';
?>
