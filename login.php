<?php
include __DIR__ . '/layouts/master/header.php';
?>

<div class="min-h-screen grid grid-cols-1 lg:grid-cols-2 bg-white">
    <div class="bg-white text-gray-900 p-10 md:p-12 flex flex-col justify-center">  
        <!-- Main Title -->
        <div class="flex items-center justify-between mb-10">
            <img src="assets/images/kemenkes-logo.png" alt="Kemenkes Logo" class="h-20 md:h-20 xl:h-30 object-contain">
            <div class="flex flex-col items-center gap-2">
                <span class="font-bold text-3xl text-gray-900">Arsip Digital Online</span>
                <span class="font-bold text-3xl text-gray-900">Politeknik Kesehatan</span>
                <span class="font-bold text-3xl text-gray-900">Palangka Raya</span>
            </div>
            <img src="assets/images/polkesraya-logo.png" alt="Polkesraya Logo" class="h-20 md:h-20 xl:h-30 object-contain">
        </div>
        <?php if (isset($error)): ?>
            <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-md text-red-700">
                <p class="text-sm"><?php echo $error; ?></p>
            </div>
        <?php endif; ?>
        <div id="messageContainer" class="mb-2"></div>
        <form class="space-y-5" id="loginForm" method="POST">
            <div>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                        <span class="material-icons text-gray-400 text-base">person</span>
                    </div>
                    <input id="username" name="username" type="text" required class="w-full pl-10 pr-4 py-3 rounded-lg bg-white border border-gray-300 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500" placeholder="Masukkan Username">
                </div>
                <p id="emailError" class="mt-1 text-sm text-red-400 hidden"></p>
            </div>
            <div>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                        <span class="material-icons text-gray-400 text-base">lock</span>
                    </div>
                    <input id="password" name="password" type="password" required class="w-full pl-10 pr-4 py-3 rounded-lg bg-white border border-gray-300 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500" placeholder="Masukkan Password">
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                        <button type="button" id="togglePassword" class="text-gray-500 hover:text-gray-700 focus:outline-none" style="line-height: 0;">
                            <span class="material-icons text-sm">visibility</span>
                        </button>
                    </div>
                </div>
                <p id="passwordError" class="mt-1 text-sm text-red-400 hidden"></p>
            </div>
            <div class="space-y-3">
                <button id="loginBtn" type="submit" class="w-full flex justify-center py-3 px-4 rounded-lg text-sm font-medium text-white bg-cyan-600 hover:bg-cyan-700 focus:outline-none focus:ring-2 focus:ring-cyan-400">
                    <span id="loginBtnText">Masuk</span>
                </button>
                <div class="text-xs text-gray-500">Lupa password? Hubungi administrator.</div>
            </div>
        </form>
        <div class="mt-6 p-4 bg-gray-50 border border-gray-200 rounded-lg">
            <p class="text-sm text-gray-700 font-medium mb-2">Test Credentials:</p>
            <div class="space-y-1 text-xs text-gray-600">
                <p><strong>User:</strong> user / User123!</p>
            </div>
        </div>
    </div>
    <div class="relative hidden lg:block">
        <img src="assets/images/poltekkes-bg.jpeg" alt="Background" class="absolute inset-0 w-full h-full object-cover">
        <!-- <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-black/10 to-transparent"></div> -->
        <div class="absolute inset-0 bg-cyan-600/25"></div>
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

<?php
include __DIR__ . '/layouts/master/footer.php';
?>
