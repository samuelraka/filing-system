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
        <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-lg bg-orange-50 text-orange-600 font-medium"><span class="material-icons">dashboard</span>Dashboard</a>
        <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-100"><span class="material-icons">folder</span>Semua Arsip</a>
        <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-100"><span class="material-icons">history</span>Riwayat</a>
        <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-100"><span class="material-icons">settings</span>Pengaturan</a>
    </nav>
    <div class="mt-2 pt-4">
        <a href="#" class="flex items-center gap-4 p-2 rounded-lg text-red-500 hover:bg-red-50"><span class="material-icons">logout</span>Keluar</a>
    </div>
</aside>