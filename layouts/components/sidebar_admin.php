<?php
// sidebar_admin.php
include_once __DIR__ . '/../../config/session.php';

?>
<!-- Admin Sidebar - Fixed -->
<aside class="w-64 bg-slate-700 border-r border-slate-800 flex flex-col pt-6 pb-3 px-4 h-screen fixed left-0 top-0 text-slate-200">
    <div class="flex items-center justify-center mb-8 gap-3 px-2">
        <img src="../assets/images/kemenkes-logo.png" alt="Logo" class="h-8 w-8 object-contain">
        <span class="font-bold text-3xl tracking-wide text-slate-100">ARDIPOL</span>
    </div>
    <nav class="flex-1 space-y-2 overflow-y-auto">
        <!-- Dashboard -->
        <a href="<?php echo dirname($_SERVER['PHP_SELF']) . '/../pages/dashboard.php'; ?>" class="flex items-center gap-3 px-3 py-2 rounded-lg <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'bg-slate-600 text-white font-medium' : 'hover:bg-slate-600/60 text-slate-200'; ?>">
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
                <a href="<?php echo dirname($_SERVER['PHP_SELF']) . '/../pages/aktif.php'; ?>" class="block py-2 px-3 rounded-lg hover:bg-slate-600/50 <?php echo basename($_SERVER['PHP_SELF']) == 'aktif.php' ? 'bg-slate-600 text-white font-medium' : 'text-slate-200'; ?>">Aktif</a>
                <a href="<?php echo dirname($_SERVER['PHP_SELF']) . '/../pages/inaktif.php'; ?>" class="block py-2 px-3 rounded-lg hover:bg-slate-600/50 <?php echo basename($_SERVER['PHP_SELF']) == 'inaktif.php' ? 'bg-slate-600 text-white font-medium' : 'text-slate-200'; ?>">Inaktif</a>
                <a href="<?php echo dirname($_SERVER['PHP_SELF']) . '/../pages/vital.php'; ?>" class="block py-2 px-3 rounded-lg hover:bg-slate-600/50 <?php echo basename($_SERVER['PHP_SELF']) == 'vital.php' ? 'bg-slate-600 text-white font-medium' : 'text-slate-200'; ?>">Vital</a>
                <a href="<?php echo dirname($_SERVER['PHP_SELF']) . '/../pages/statis.php'; ?>" class="block py-2 px-3 rounded-lg hover:bg-slate-600/50 <?php echo basename($_SERVER['PHP_SELF']) == 'statis.php' ? 'bg-slate-600 text-white font-medium' : 'text-slate-200'; ?>">Statis</a>
            </div>
        </div>

        <!-- Admin Management Section -->
        <div class="pt-4 mt-4 border-t border-slate-600">
            <div class="px-3 py-1 text-xs font-semibold text-slate-300 uppercase tracking-wider">Admin Panel</div>
        </div>

        <!-- User Management -->
        <div class="menu-item">
            <button class="w-full flex items-center justify-between px-3 py-2 rounded-lg hover:bg-slate-600/60 focus:outline-none text-slate-200" id="arsipUsrMenu">
                <div class="flex items-center gap-3">
                    <span class="material-icons">people</span>Kelola Pengguna
                </div>
                <span class="material-icons transform transition-transform" id="arsipUsrMenuIcon">expand_more</span>
            </button>
            <div class="pl-9 mt-1 hidden space-y-1" id="arsipUsrSubmenu">
                <a href="<?php echo dirname($_SERVER['PHP_SELF']) . '/../admin/kelola_pengguna.php'; ?>" class="block py-2 px-3 rounded-lg hover:bg-slate-600/50 <?php echo basename($_SERVER['PHP_SELF']) == 'kelola_pengguna.php' ? 'bg-slate-600 text-white font-medium' : 'text-slate-200'; ?>">Kelola Pengguna</a>
                <a href="<?php echo dirname($_SERVER['PHP_SELF']) . '/../admin/unit_pengolah.php'; ?>" class="block py-2 px-3 rounded-lg hover:bg-slate-600/50 <?php echo basename($_SERVER['PHP_SELF']) == 'unit_pengolah.php' ? 'bg-slate-600 text-white font-medium' : 'text-slate-200'; ?>">Unit Pengolah</a>
            </div>
        </div>

        <!-- Collapsible Menu for Arsip Management -->
        <div class="menu-item">
            <button class="w-full flex items-center justify-between px-3 py-2 rounded-lg hover:bg-slate-600/60 focus:outline-none text-slate-200" id="arsipMgmtMenu">
                <div class="flex items-center gap-3">
                    <span class="material-icons">settings_applications</span>Kelola Arsip
                </div>
                <span class="material-icons transform transition-transform" id="arsipMgmtMenuIcon">expand_more</span>
            </button>
            <div class="pl-9 mt-1 hidden space-y-1" id="arsipMgmtSubmenu">
                <a href="<?php echo dirname($_SERVER['PHP_SELF']) . '/../admin/kategori.php'; ?>" class="block py-2 px-3 rounded-lg hover:bg-slate-600/50 <?php echo basename($_SERVER['PHP_SELF']) == 'kategori.php' ? 'bg-slate-600 text-white font-medium' : 'text-slate-200'; ?>">Kategori Arsip</a>
            </div>
        </div>
    </nav>
    <div class="mt-2 pt-4 space-y-1">
        <a href="<?php echo dirname($_SERVER['PHP_SELF']) . '/../pages/profile.php'; ?>" class="flex items-center gap-4 p-2 rounded-lg <?php echo basename($_SERVER['PHP_SELF']) == 'profile.php' ? 'bg-slate-600 text-white font-medium' : 'hover:bg-slate-600/60 text-slate-200'; ?>">
            <span class="material-icons">settings</span>Pengaturan
        </a>
        <a href="../api/logout.php" class="flex items-center gap-4 p-2 rounded-lg text-red-300 hover:bg-red-700/30">
            <span class="material-icons">logout</span>Keluar
        </a>
    </div>
</aside>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get all menu buttons and submenus
        const arsipMenu = document.getElementById('arsipMenu');
        const arsipSubmenu = document.getElementById('arsipSubmenu');
        const arsipMenuIcon = document.getElementById('arsipMenuIcon');
        
        const arsipMgmtMenu = document.getElementById('arsipMgmtMenu');
        const arsipMgmtSubmenu = document.getElementById('arsipMgmtSubmenu');
        const arsipMgmtMenuIcon = document.getElementById('arsipMgmtMenuIcon');
        
        const arsipUsrMenu = document.getElementById('arsipUsrMenu');
        const arsipUsrSubmenu = document.getElementById('arsipUsrSubmenu');
        const arsipUsrMenuIcon = document.getElementById('arsipUsrMenuIcon');
        
        const systemMenu = document.getElementById('systemMenu');
        const systemSubmenu = document.getElementById('systemSubmenu');
        const systemMenuIcon = document.getElementById('systemMenuIcon');
        
        // Auto-expand Arsip menu
        const currentPage = window.location.pathname.split('/').pop();
        if (currentPage === 'aktif.php' || currentPage === 'inaktif.php' || currentPage === 'vital.php' || currentPage === 'statis.php' || currentPage === 'tambah_aktif.php' || currentPage === 'tambah_inaktif.php' || currentPage === 'tambah_vital.php' || currentPage === 'tambah_statis.php') {
            arsipMenu.classList.add('bg-slate-600', 'text-white', 'font-medium');
            arsipMenu.classList.remove('text-slate-200');
            arsipSubmenu.classList.remove('hidden');
            arsipMenuIcon.classList.add('rotate-180');
        }

        // Auto-expand Kelola Pengguna menu
        if (currentPage === 'kelola_pengguna.php' || currentPage === 'unit_pengolah.php') {
            arsipUsrMenu.classList.add('bg-slate-600', 'text-white', 'font-medium');
            arsipUsrMenu.classList.remove('text-slate-200');
            arsipUsrSubmenu.classList.remove('hidden');
            arsipUsrMenuIcon.classList.add('rotate-180');
        }
        
        // Auto-expand Kelola Arsip menu
        if (currentPage === 'kategori.php') {
            arsipMgmtMenu.classList.add('bg-slate-600', 'text-white', 'font-medium');
            arsipMgmtMenu.classList.remove('text-slate-200');
            arsipMgmtSubmenu.classList.remove('hidden');
            arsipMgmtMenuIcon.classList.add('rotate-180');
        }
        
        // Toggle arsip submenu
        arsipMenu.addEventListener('click', function() {
            arsipSubmenu.classList.toggle('hidden');
            arsipMenuIcon.classList.toggle('rotate-180');
        });
        
        // Toggle arsip user submenu
        arsipUsrMenu.addEventListener('click', function() {
            arsipUsrSubmenu.classList.toggle('hidden');
            arsipUsrMenuIcon.classList.toggle('rotate-180');
        });
        
        // Toggle arsip management submenu
        arsipMgmtMenu.addEventListener('click', function() {
            arsipMgmtSubmenu.classList.toggle('hidden');
            arsipMgmtMenuIcon.classList.toggle('rotate-180');
        });
        
        // Toggle system submenu
        systemMenu.addEventListener('click', function() {
            systemSubmenu.classList.toggle('hidden');
            systemMenuIcon.classList.toggle('rotate-180');
        });
    });
</script>
