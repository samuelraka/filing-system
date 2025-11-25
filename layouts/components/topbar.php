<?php
// topbar.php
// Topbar component for the archiving system
include_once __DIR__ . '/../../config/session.php';
?>
<!-- Top Navbar - Fixed -->
<header class="bg-white border-b border-gray-200 px-8 py-4 flex items-center justify-end fixed top-0 right-0 left-64 z-10">
    <div class="flex items-center gap-3 ml-6">
        <span class="font-semibold">
            <?php
            $displayName = htmlspecialchars($_SESSION['username'] ?? getUserName());
            $unitName = '';
            $uid = null;
            if (isset($_SESSION['id_user'])) { $uid = intval($_SESSION['id_user']); }
            elseif (isset($_SESSION['user_id'])) { $uid = intval($_SESSION['user_id']); }
            if ($uid && isset($conn)) {
                $stmt = $conn->prepare("SELECT u.nama_unit FROM profil p JOIN unit_pengolah u ON p.id_unit = u.id_unit WHERE p.id_user = ? LIMIT 1");
                if ($stmt) {
                    $stmt->bind_param("i", $uid);
                    $stmt->execute();
                    $res = $stmt->get_result();
                    if ($row = $res->fetch_assoc()) { $unitName = $row['nama_unit'] ?? ''; }
                    $stmt->close();
                }
            }
            echo $displayName . ' | ' . htmlspecialchars($unitName ?: '-');
            ?>
        </span>
        <img src="https://ui-avatars.com/api/?name=<?php echo getFullName(); ?>" class="w-9 h-9 rounded-full border" />
    </div>
</header>