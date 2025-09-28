<?php
// sidebar.php
// Sidebar component for the archiving system
?>
<!-- Sidebar - Fixed -->
<aside class="w-64 bg-white border-r border-gray-200 flex flex-col pt-6 pb-3 px-4 h-screen fixed left-0 top-0">
    <div class="flex items-center gap-2 mb-8 px-2">
        <span class="material-icons text-orange-500">inventory_2</span>
        <span class="font-bold text-lg tracking-wide">ArsipOnline</span>
    </div>
    <nav class="flex-1 space-y-2 overflow-y-auto">
        <a href="<?php echo dirname($_SERVER['PHP_SELF']) . '/dashboard.php'; ?>" class="flex items-center gap-3 px-3 py-2 rounded-lg <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'bg-orange-50 text-orange-600 font-medium' : 'hover:bg-gray-100'; ?>">
            <span class="material-icons">dashboard</span>Dashboard
        </a>
        
        <!-- Collapsible Menu for Semua Arsip -->
        <div class="menu-item">
            <button class="w-full flex items-center justify-between px-3 py-2 rounded-lg hover:bg-gray-100 focus:outline-none" id="arsipMenu">
                <div class="flex items-center gap-3">
                    <span class="material-icons">folder</span>Semua Arsip
                </div>
                <span class="material-icons transform transition-transform" id="arsipMenuIcon">expand_more</span>
            </button>
            <div class="pl-9 mt-1 hidden space-y-1" id="arsipSubmenu">
                <a href="<?php echo dirname($_SERVER['PHP_SELF']) . '/aktif.php'; ?>" class="block py-2 px-3 rounded-lg hover:bg-gray-100 <?php echo basename($_SERVER['PHP_SELF']) == 'aktif.php' ? 'text-orange-600 font-medium' : ''; ?>">Aktif</a>
                <a href="<?php echo dirname($_SERVER['PHP_SELF']) . '/inaktif.php'; ?>" class="block py-2 px-3 rounded-lg hover:bg-gray-100 <?php echo basename($_SERVER['PHP_SELF']) == 'inaktif.php' ? 'text-orange-600 font-medium' : ''; ?>">Inaktif</a>
            </div>
        </div>
    </nav>
    <div class="mt-2 pt-4">
        <a href="#" class="flex items-center gap-4 p-2 rounded-lg text-red-500 hover:bg-red-50"><span class="material-icons">logout</span>Keluar</a>
    </div>
</aside>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get the menu button and submenu
        const arsipMenu = document.getElementById('arsipMenu');
        const arsipSubmenu = document.getElementById('arsipSubmenu');
        const arsipMenuIcon = document.getElementById('arsipMenuIcon');
        
        // Check if current page is aktif.php or inaktif.php to auto-expand the menu
        const currentPage = window.location.pathname.split('/').pop();
        if (currentPage === 'aktif.php' || currentPage === 'inaktif.php') {
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