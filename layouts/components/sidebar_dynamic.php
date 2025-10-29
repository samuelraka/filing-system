<?php
include_once __DIR__ . '/../../config/session.php';

// Cek role user dan load sidebar yang sesuai
if (isAdminOrSuperAdmin()) {
    include_once __DIR__ . '/sidebar_admin.php';
} else {
    include_once __DIR__ . '/sidebar.php';
}
?>
