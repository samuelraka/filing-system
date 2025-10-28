<?php
// login.php
// Clean login page using AJAX handler
include_once __DIR__ . '/config/session.php';

// Handle logout request
if (isset($_GET['logout']) && $_GET['logout'] === 'true') {
    logout();
}

// Redirect if already logged in
if (isLoggedIn()) {
    header('Location: pages/dashboard.php');
    exit();
}

include __DIR__ . '/layouts/master/header.php';
?>

<div class="min-h-screen bg-gradient-to-br from-cyan-50 via-blue-50 to-indigo-100 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative overflow-hidden">
    <!-- Background decorative elements -->
    <div class="absolute inset-0">
        <div class="absolute top-0 left-0 w-96 h-96 bg-cyan-200 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-blob"></div>
        <div class="absolute top-0 right-0 w-96 h-96 bg-blue-200 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-blob animation-delay-2000"></div>
        <div class="absolute bottom-0 left-1/2 w-96 h-96 bg-indigo-200 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-blob animation-delay-4000"></div>
    </div>
    
    <div class="max-w-md w-full space-y-8 relative z-10">
        <!-- Logo and Title -->
        <div class="text-center flex flex-col items-center justify-center gap-5 mb-5">
            <div class="flex items-center justify-center gap-2 mb-5">
                <span class="material-icons text-cyan-600 text-5xl">inventory_2</span>
                <span class="font-bold text-4xl tracking-wide text-slate-800">ArsipOnline</span>
            </div>
            <h2 class="text-center text-3xl font-bold text-slate-700">Login Admin</h2>
        </div>

        <!-- Login Form with Glass Effect -->
        <div class="backdrop-blur rounded-2xl shadow-xl border border-white/20 p-8">
            <!-- Error/Success Messages -->
            <div id="messageContainer"></div>
            
            <form class="space-y-6" id="loginForm">
                <!-- Email Field -->
                <div>
                    <label for="username" class="block text-sm font-medium mb-1">Username</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="material-icons">person</span>
                        </div>
                        <input id="username" name="username" type="text" required class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 bg-white/70 backdrop-blur-sm" placeholder="Masukkan Username">
                    </div>
                    <p id="emailError" class="mt-1 text-sm text-red-600 hidden"></p>
                </div>

                <!-- Password Field -->
                <div>
                    <label for="password" class="block text-sm font-medium mb-1">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="material-icons">lock</span>
                        </div>
                        <input id="password" name="password" type="password" required class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 bg-white/70 backdrop-blur-sm" placeholder="Masukkan Password">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <button type="button" id="togglePassword" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                                <span class="material-icons text-sm">visibility</span>
                            </button>
                        </div>
                    </div>
                    <p id="passwordError" class="mt-1 text-sm text-red-600 hidden"></p>
                </div>

                <!-- Login Button -->
                <div>
                    <button type="submit" id="loginBtn" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-md text-sm font-medium text-white bg-cyan-600 hover:bg-cyan-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500 transition-all duration-200 transform hover:scale-[1.02]">
                        <span id="loginBtnText">Masuk</span>
                    </button>
                </div>
            </form>

            <!-- Test Credentials Info -->
            <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <p class="text-sm text-blue-800 font-medium mb-2">Test Credentials:</p>
                <div class="space-y-1 text-xs text-blue-700">
                    <p><strong>Admin:</strong> admin@example.com / admin123</p>
                    <p><strong>User:</strong> user@example.com / user123</p>
                    <p><strong>Manager:</strong> manager@example.com / manager123</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('loginForm');
    const usernameInput = document.getElementById('username');
    const passwordInput = document.getElementById('password');
    const togglePassword = document.getElementById('togglePassword');
    const loginBtn = document.getElementById('loginBtn');
    const loginBtnText = document.getElementById('loginBtnText');
    const messageContainer = document.getElementById('messageContainer');
    
    // Toggle password visibility
    togglePassword.addEventListener('click', function() {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        
        const icon = this.querySelector('.material-icons');
        icon.textContent = type === 'password' ? 'visibility' : 'visibility_off';
    });
    
    // Show message function
    function showMessage(message, type = 'error') {
        const bgColor = type === 'success' ? 'bg-green-50 border-green-200 text-green-800' : 'bg-red-50 border-red-200 text-red-800';
        messageContainer.innerHTML = `
            <div class="p-3 ${bgColor} border rounded-md">
                <p class="text-sm">${message}</p>
            </div>
        `;
        
        // Auto-hide after 5 seconds
        setTimeout(() => {
            messageContainer.innerHTML = '';
        }, 5000);
    }
    
    // Show error for field
    function showFieldError(inputElement, errorId, message) {
        inputElement.classList.add('border-red-500', 'ring-red-500');
        const errorElement = document.getElementById(errorId);
        errorElement.textContent = message;
        errorElement.classList.remove('hidden');
    }
    
    // Clear field errors
    function clearFieldErrors() {
        document.getElementById('emailError').classList.add('hidden');
        document.getElementById('passwordError').classList.add('hidden');
        usernameInput.classList.remove('border-red-500', 'ring-red-500');
        passwordInput.classList.remove('border-red-500', 'ring-red-500');
    }
    
    // Set loading state
    function setLoading(loading) {
        if (loading) {
            loginBtn.disabled = true;
            loginBtnText.textContent = 'Loading...';
        } else {
            loginBtn.disabled = false;
            loginBtnText.textContent = 'Masuk';
        }
    }
    
    // Form submission
    loginForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        clearFieldErrors();
        
        const username = document.getElementById('username').value.trim();
        const password = passwordInput.value;
        
        // Client-side validation
        let isValid = true;
        
        if (!username) {
            showFieldError(document.getElementById('username'), 'emailError', 'Username harus diisi');
            isValid = false;
        }
        
        if (!password) {
            showFieldError(passwordInput, 'passwordError', 'Password harus diisi');
            isValid = false;
        }
        
        if (!isValid) {
            return;
        }
        
        // Show loading state
        setLoading(true);
        messageContainer.innerHTML = '';
        
        // Send AJAX request to Node.js API
        fetch('api/login.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                username: username,
                password: password
            })
        })
        .then(response => response.json())
        .then(data => {
            setLoading(false);
            
            if (data.success) {
                showMessage(data.message, 'success');
                
                // Store user info in session storage (for demo purposes)
                sessionStorage.setItem('user', JSON.stringify(data.user));
                
                // Redirect after successful login
                setTimeout(() => {
                    window.location.href = 'pages/dashboard.php';
                }, 1000);
            } else {
                showMessage(data.message);
            }
        })
        .catch(error => {
            setLoading(false);
            console.error('Login error:', error);
            showMessage('Terjadi kesalahan. Silakan coba lagi.');
        });
    });
});
</script>

<style>
@keyframes blob {
    0% {
        transform: translate(0px, 0px) scale(1);
    }
    33% {
        transform: translate(30px, -50px) scale(1.1);
    }
    66% {
        transform: translate(-20px, 20px) scale(0.9);
    }
    100% {
        transform: translate(0px, 0px) scale(1);
    }
}

.animate-blob {
    animation: blob 7s infinite;
}

.animation-delay-2000 {
    animation-delay: 2s;
}

.animation-delay-4000 {
    animation-delay: 4s;
}
</style>

<?php
include __DIR__ . '/layouts/master/footer.php';
?>
