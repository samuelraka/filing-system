<?php
// sidebar_admin.php
include_once __DIR__ . '/../../config/session.php';

?>
<!-- Admin Sidebar - Fixed -->
<aside class="w-64 bg-white border-r border-gray-200 flex flex-col pt-6 pb-3 px-4 h-screen fixed left-0 top-0">
    <div class="flex items-center gap-2 mb-8 px-2">
        <span class="material-icons text-cyan-600">inventory_2</span>
        <span class="font-bold text-lg tracking-wide">ArsipOnline</span>
    </div>
    <nav class="flex-1 space-y-2 overflow-y-auto">
        <!-- Dashboard -->
        <a href="<?php echo dirname($_SERVER['PHP_SELF']) . '/dashboard.php'; ?>" class="flex items-center gap-3 px-3 py-2 rounded-lg <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'bg-cyan-600/10 text-cyan-600 font-medium' : 'hover:bg-cyan-600/5 text-slate-700'; ?>">
            <span class="material-icons">dashboard</span>Dashboard
        </a>
        
        <!-- Collapsible Menu for Semua Arsip -->
        <div class="menu-item">
            <button class="w-full flex items-center justify-between px-3 py-2 rounded-lg hover:bg-cyan-600/5 focus:outline-none text-slate-700" id="arsipMenu">
                <div class="flex items-center gap-3">
                    <span class="material-icons">folder</span>Semua Arsip
                </div>
                <span class="material-icons transform transition-transform" id="arsipMenuIcon">expand_more</span>
            </button>
            <div class="pl-9 mt-1 hidden space-y-1" id="arsipSubmenu">
                <a href="<?php echo dirname($_SERVER['PHP_SELF']) . '/aktif.php'; ?>" class="block py-2 px-3 rounded-lg hover:bg-cyan-600/5 <?php echo basename($_SERVER['PHP_SELF']) == 'aktif.php' ? 'text-cyan-600 font-medium' : 'text-slate-700'; ?>">Aktif</a>
                <a href="<?php echo dirname($_SERVER['PHP_SELF']) . '/inaktif.php'; ?>" class="block py-2 px-3 rounded-lg hover:bg-cyan-600/5 <?php echo basename($_SERVER['PHP_SELF']) == 'inaktif.php' ? 'text-cyan-600 font-medium' : 'text-slate-700'; ?>">Inaktif</a>
            </div>
        </div>

        <!-- Admin Management Section -->
        <div class="pt-4 mt-4 border-t border-gray-200">
            <div class="px-3 py-1 text-xs font-semibold text-gray-500 uppercase tracking-wider">Admin Panel</div>
        </div>

        <!-- User Management -->
        <div class="menu-item">
            <button class="w-full flex items-center justify-between px-3 py-2 rounded-lg hover:bg-cyan-600/5 focus:outline-none text-slate-700" id="arsipUsrMenu">
                <div class="flex items-center gap-3">
                    <span class="material-icons">people</span>Kelola Pengguna
                </div>
                <span class="material-icons transform transition-transform" id="arsipUsrMenuIcon">expand_more</span>
            </button>
            <div class="pl-9 mt-1 hidden space-y-1" id="arsipUsrSubmenu">
                <a href="<?php echo dirname($_SERVER['PHP_SELF']) . '/../pages/kelola_pengguna.php'; ?>" class="block py-2 px-3 rounded-lg hover:bg-cyan-600/5 <?php echo basename($_SERVER['PHP_SELF']) == 'kelola_pengguna.php' ? 'text-cyan-600 font-medium' : 'text-slate-700'; ?>">Kelola Pengguna</a>
                <a href="<?php echo dirname($_SERVER['PHP_SELF']) . '/../pages/unit_pengolah.php'; ?>" class="block py-2 px-3 rounded-lg hover:bg-cyan-600/5 <?php echo basename($_SERVER['PHP_SELF']) == 'unit_pengolah.php' ? 'text-cyan-600 font-medium' : 'text-slate-700'; ?>">Unit Pengolah</a>
            </div>
        </div>

        <!-- Collapsible Menu for Arsip Management -->
        <div class="menu-item">
            <button class="w-full flex items-center justify-between px-3 py-2 rounded-lg hover:bg-cyan-600/5 focus:outline-none text-slate-700" id="arsipMgmtMenu">
                <div class="flex items-center gap-3">
                    <span class="material-icons">settings_applications</span>Kelola Arsip
                </div>
                <span class="material-icons transform transition-transform" id="arsipMgmtMenuIcon">expand_more</span>
            </button>
            <div class="pl-9 mt-1 hidden space-y-1" id="arsipMgmtSubmenu">
                <a href="<?php echo dirname($_SERVER['PHP_SELF']) . '/../pages/kelola_arsip.php'; ?>" class="block py-2 px-3 rounded-lg hover:bg-cyan-600/5 <?php echo basename($_SERVER['PHP_SELF']) == 'kelola_arsip.php' ? 'text-cyan-600 font-medium' : 'text-slate-700'; ?>">Kategori Arsip</a>
                <a href="#" class="block py-2 px-3 rounded-lg hover:bg-cyan-600/5 text-slate-700">Retensi Arsip</a>
                <a href="#" class="block py-2 px-3 rounded-lg hover:bg-cyan-600/5 text-slate-700">Pemusnahan</a>
            </div>
        </div>

        <!-- Reports -->
        <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-cyan-600/5 text-slate-700">
            <span class="material-icons">assessment</span>Laporan
        </a>

        <!-- Audit Logs -->
        <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-cyan-600/5 text-slate-700">
            <span class="material-icons">history</span>Audit Log
        </a>

        <!-- System Settings -->
        <div class="menu-item">
            <button class="w-full flex items-center justify-between px-3 py-2 rounded-lg hover:bg-cyan-600/5 focus:outline-none text-slate-700" id="systemMenu">
                <div class="flex items-center gap-3">
                    <span class="material-icons">admin_panel_settings</span>Pengaturan Sistem
                </div>
                <span class="material-icons transform transition-transform" id="systemMenuIcon">expand_more</span>
            </button>
            <div class="pl-9 mt-1 hidden space-y-1" id="systemSubmenu">
                <a href="#" class="block py-2 px-3 rounded-lg hover:bg-cyan-600/5 text-slate-700">Backup & Restore</a>
                <a href="#" class="block py-2 px-3 rounded-lg hover:bg-cyan-600/5 text-slate-700">Database</a>
                <a href="#" class="block py-2 px-3 rounded-lg hover:bg-cyan-600/5 text-slate-700">Integrasi</a>
            </div>
        </div>
    </nav>
    <div class="mt-2 pt-4 space-y-1">
        <a href="<?php echo dirname($_SERVER['PHP_SELF']) . '/profile.php'; ?>" class="flex items-center gap-4 p-2 rounded-lg <?php echo basename($_SERVER['PHP_SELF']) == 'profile.php' ? 'bg-cyan-600/10 text-cyan-600 font-medium' : 'hover:bg-cyan-600/5 text-slate-700'; ?>">
            <span class="material-icons">settings</span>Pengaturan
        </a>
        <a href="../api/logout.php" class="flex items-center gap-4 p-2 rounded-lg text-red-500 hover:bg-red-50">
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
        
        // Check if current page is aktif.php or inaktif.php to auto-expand the arsip menu
        const currentPage = window.location.pathname.split('/').pop();
        if (currentPage === 'aktif.php' || currentPage === 'inaktif.php') {
            arsipSubmenu.classList.remove('hidden');
            arsipMenuIcon.classList.add('rotate-180');
        }

        // Check if current page is kelola_pengguna.php or unit_pengolah.php to auto-expand the kelola pengguna menu
        if (currentPage === 'kelola_pengguna.php' || currentPage === 'unit_pengolah.php') {
            arsipUsrSubmenu.classList.remove('hidden');
            arsipUsrMenuIcon.classList.add('rotate-180');
        }
        
        // Check if current page is kelola_pengguna.php or unit_pengolah.php to auto-expand the kelola pengguna menu
        if (currentPage === 'kelola_arsip.php') {
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
