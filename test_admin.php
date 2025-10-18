<?php
// test_admin.php
// Test file to demonstrate admin sidebar functionality
include_once __DIR__ . '/config/session.php';

// Auto-login as admin for testing
if (!isLoggedIn()) {
    $_SESSION['user_id'] = 1;
    $_SESSION['user_name'] = 'Administrator';
    $_SESSION['user_email'] = 'admin@example.com';
    $_SESSION['user_role'] = 'admin';
}

include_once __DIR__ . '/layouts/master/header.php';
?>

<div class="min-h-screen bg-[#fafbfc] flex overflow-hidden">
    <!-- Main Content -->
    <div class="flex-1 flex flex-col ml-64">
        <?php include __DIR__ . '/layouts/components/sidebar_dynamic.php'; ?>
        <?php include __DIR__ . '/layouts/components/topbar.php'; ?>
        
        <!-- Test Content -->
        <main class="flex-1 p-8 mt-16 overflow-y-auto">
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h1 class="text-2xl font-bold mb-4">Admin Sidebar Test</h1>
                
                <div class="space-y-4">
                    <div class="p-4 bg-green-50 border border-green-200 rounded-lg">
                        <h2 class="font-semibold text-green-800 mb-2">Current User Information:</h2>
                        <p><strong>Name:</strong> <?php echo getUserName(); ?></p>
                        <p><strong>Role:</strong> <?php echo getUserRole(); ?></p>
                        <p><strong>Is Admin:</strong> <?php echo isAdmin() ? 'Yes' : 'No'; ?></p>
                    </div>
                    
                    <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <h2 class="font-semibold text-blue-800 mb-2">Admin Sidebar Features:</h2>
                        <ul class="list-disc list-inside space-y-1 text-gray-700">
                            <li>Admin badge in sidebar header</li>
                            <li>Admin Panel section with expanded menu options</li>
                            <li>User Management access</li>
                            <li>Archive Management tools</li>
                            <li>Reports and Audit Logs</li>
                            <li>System Settings</li>
                        </ul>
                    </div>
                    
                    <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <h2 class="font-semibold text-yellow-800 mb-2">Test Different User Roles:</h2>
                        <div class="space-x-4">
                            <a href="?role=admin" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Login as Admin</a>
                            <a href="?role=user" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Login as User</a>
                            <a href="?role=manager" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Login as Manager</a>
                            <a href="login.php?logout=true" class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">Logout</a>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<?php
// Handle role switching for testing
if (isset($_GET['role'])) {
    $role = $_GET['role'];
    
    switch($role) {
        case 'admin':
            $_SESSION['user_id'] = 1;
            $_SESSION['user_name'] = 'Administrator';
            $_SESSION['user_email'] = 'admin@example.com';
            $_SESSION['user_role'] = 'admin';
            break;
        case 'user':
            $_SESSION['user_id'] = 2;
            $_SESSION['user_name'] = 'Regular User';
            $_SESSION['user_email'] = 'user@example.com';
            $_SESSION['user_role'] = 'user';
            break;
        case 'manager':
            $_SESSION['user_id'] = 3;
            $_SESSION['user_name'] = 'Manager';
            $_SESSION['user_email'] = 'manager@example.com';
            $_SESSION['user_role'] = 'manager';
            break;
    }
    
    // Redirect to remove query params
    header('Location: test_admin.php');
    exit();
}

include_once __DIR__ . '/layouts/master/footer.php';
?>
