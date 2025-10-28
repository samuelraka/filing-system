<?php
include_once '../config/session.php';

// Jalankan fungsi logout()
logout();

// Setelah logout, redirect ke halaman login
header("Location: ../admin_login.php");
exit;
