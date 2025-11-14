<?php
include_once '../config/session.php';

// Pastikan session dimulai
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Simpan dulu role sebelum session dihapus
$role = $_SESSION['user_role'] ?? null;


// Jalankan fungsi logout() → ini biasanya akan session_destroy()
logout();

// Tentukan arah redirect berdasarkan role
if ($role === 'superadmin' || $role === 'admin') {
    header("Location: ../admin_login.php");
} else {
    header("Location: ../login.php");
}
exit;
