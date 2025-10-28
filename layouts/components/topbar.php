<?php
// topbar.php
// Topbar component for the archiving system
include_once __DIR__ . '/../../config/session.php';
?>
<!-- Top Navbar - Fixed -->
<header class="bg-white border-b border-gray-200 px-8 py-4 flex items-center justify-end fixed top-0 right-0 left-64 z-10">
    <div class="flex items-center gap-3 ml-6">
        <span class="font-semibold">
            <?php echo htmlspecialchars($_SESSION['nama'] ?? getUserName()); ?>
        </span>
        <img src="https://ui-avatars.com/api/?name=<?php echo getFullName(); ?>" class="w-9 h-9 rounded-full border" />
    </div>
</header>