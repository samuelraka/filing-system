<?php
// Sidebar component for the archiving system
include_once __DIR__ . '/../../config/session.php';
?>
<!-- Sidebar - Fixed -->
<aside class="w-64 bg-slate-700 border-r border-slate-800 flex flex-col pt-6 pb-3 px-4 h-screen fixed left-0 top-0 text-slate-200">
    <div class="flex items-center gap-2 mb-8 px-2">
        <img src="../assets/images/kemenkes-logo.png" alt="Logo" class="h-8 w-8 object-contain">
        <span class="font-bold text-lg tracking-wide text-slate-100">ArsipOnline</span>
    </div>
    <nav class="flex-1 space-y-2 overflow-y-auto">
        <a href="<?php echo dirname($_SERVER['PHP_SELF']) . '/dashboard.php'; ?>" class="flex items-center gap-3 px-3 py-2 rounded-lg <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'bg-slate-600 text-white font-medium' : 'hover:bg-slate-600/60 text-slate-200'; ?>">
            <span class="material-icons">dashboard</span>Dashboard
        </a>
        
        <!-- Collapsible Menu for Semua Arsip -->
        <div class="menu-item">
            <button class="w-full flex items-center justify-between px-3 py-2 rounded-lg hover:bg-slate-600/60 focus:outline-none text-slate-200" id="arsipMenu">
                <div class="flex items-center gap-3">
                    <span class="material-icons">folder</span>Semua Arsip
                </div>
                <span class="material-icons transform transition-transform" id="arsipMenuIcon">expand_more</span>
            </button>
            <div class="pl-9 mt-1 hidden space-y-1" id="arsipSubmenu">
                <a href="<?php echo dirname($_SERVER['PHP_SELF']) . '/aktif.php'; ?>" class="block py-2 px-3 rounded-lg hover:bg-slate-600/50 <?php echo (basename($_SERVER['PHP_SELF']) == 'aktif.php' || basename($_SERVER['PHP_SELF']) == 'tambah_aktif.php' || basename($_SERVER['PHP_SELF']) == 'detail_aktif.php' || basename($_SERVER['PHP_SELF']) == 'edit_aktif.php') ? 'bg-slate-600 text-white font-medium' : 'text-slate-200'; ?>" id="aktifSubmenu">Aktif</a>
                <a href="<?php echo dirname($_SERVER['PHP_SELF']) . '/inaktif.php'; ?>" class="block py-2 px-3 rounded-lg hover:bg-slate-600/50 <?php echo (basename($_SERVER['PHP_SELF']) == 'inaktif.php' || basename($_SERVER['PHP_SELF']) == 'tambah_inaktif.php' || basename($_SERVER['PHP_SELF']) == 'detail_inaktif.php' || basename($_SERVER['PHP_SELF']) == 'edit_inaktif.php') ? 'bg-slate-600 text-white font-medium' : 'text-slate-200'; ?>" id="inaktifSubmenu">Inaktif</a>
                <a href="<?php echo dirname($_SERVER['PHP_SELF']) . '/vital.php'; ?>" class="block py-2 px-3 rounded-lg hover:bg-slate-600/50 <?php echo (basename($_SERVER['PHP_SELF']) == 'vital.php' || basename($_SERVER['PHP_SELF']) == 'tambah_vital.php' || basename($_SERVER['PHP_SELF']) == 'detail_vital.php' || basename($_SERVER['PHP_SELF']) == 'edit_vital.php') ? 'bg-slate-600 text-white font-medium' : 'text-slate-200'; ?>" id="vitalSubmenu">Vital</a>
                <a href="<?php echo dirname($_SERVER['PHP_SELF']) . '/statis.php'; ?>" class="block py-2 px-3 rounded-lg hover:bg-slate-600/50 <?php echo (basename($_SERVER['PHP_SELF']) == 'statis.php' || basename($_SERVER['PHP_SELF']) == 'tambah_statis.php' || basename($_SERVER['PHP_SELF']) == 'detail_statis.php' || basename($_SERVER['PHP_SELF']) == 'edit_statis.php') ? 'bg-slate-600 text-white font-medium' : 'text-slate-200'; ?>" id="statisSubmenu">Statis</a>
            </div>
        </div>
    </nav>
    <div class="mt-2 pt-4">
        <a href="<?php echo dirname($_SERVER['PHP_SELF']) . '/profile.php'; ?>" class="flex items-center gap-4 p-2 rounded-lg <?php echo basename($_SERVER['PHP_SELF']) == 'profile.php' ? 'bg-slate-600 text-white font-medium' : 'hover:bg-slate-600/60 text-slate-200'; ?>">
            <span class="material-icons">settings</span>Pengaturan
        </a>
        <a href="../api/logout.php" class="flex items-center gap-4 p-2 rounded-lg text-red-300 hover:bg-red-700/30"><span class="material-icons">logout</span>Keluar</a>
    </div>
</aside>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get the menu button and submenu
        const arsipMenu = document.getElementById('arsipMenu');
        const arsipSubmenu = document.getElementById('arsipSubmenu');
        const arsipMenuIcon = document.getElementById('arsipMenuIcon');
        
        // Check if current page is related to arsip to auto-expand the menu
        const currentPage = window.location.pathname.split('/').pop();
        if (currentPage === 'aktif.php' || currentPage === 'tambah_aktif.php' || currentPage === 'detail_aktif.php' || currentPage === 'edit_aktif.php' ||
        currentPage === 'inaktif.php' || currentPage === 'tambah_inaktif.php' || currentPage === 'detail_inaktif.php' || currentPage === 'edit_inaktif.php' ||
        currentPage === 'vital.php' || currentPage === 'tambah_vital.php' || currentPage === 'detail_vital.php' || currentPage === 'edit_vital.php' ||
        currentPage === 'statis.php' || currentPage === 'tambah_statis.php' || currentPage === 'detail_statis.php' || currentPage === 'edit_statis.php') 
        {
            arsipMenu.classList.add('bg-slate-600', 'text-white', 'font-medium');
            arsipMenu.classList.remove('text-slate-200');
            arsipSubmenu.classList.remove('hidden');
            arsipMenuIcon.classList.add('rotate-180');
        }
        
        // Toggle submenu on click
        arsipMenu.addEventListener('click', function() {
            arsipSubmenu.classList.toggle('hidden');
            arsipMenuIcon.classList.toggle('rotate-180');
        });
    });
</script>
