<?php
include_once '../config/session.php';
include_once '../utils/logging.php';

// Pastikan session dimulai
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Simpan dulu role sebelum session dihapus
$role = $_SESSION['user_role'] ?? null;

// Log the logout activity if user is logged in
if (isset($_SESSION['user_id'])) {
    logLogout($_SESSION['user_id'], "User logged out");
}

// Jalankan fungsi logout() → ini biasanya akan session_destroy()
logout();

// Tentukan arah redirect berdasarkan role
if ($role === 'superadmin' || $role === 'admin') {
    header("Location: ../admin_login.php");
} else {
    header("Location: ../login.php");
}
exit;
