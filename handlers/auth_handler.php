<?php
// auth_handler.php
// Handle authentication requests (login & logout)

header('Content-Type: application/json');

// Include session configuration and database
include_once __DIR__ . '/../config/session.php';
include_once __DIR__ . '/../config/database.php';

// Handle action
$action = $_POST['action'] ?? '';

switch ($action) {
    case 'login':
        handleLogin();
        break;
    case 'logout':
        handleLogout();
        break;
    default:
        echo json_encode([
            'success' => false,
            'message' => 'Invalid action'
        ]);
        break;
}

function handleLogin()
{
    global $conn;

    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Validasi input
    if (empty($username) || empty($password)) {
        echo json_encode([
            'success' => false,
            'message' => 'Username dan password harus diisi'
        ]);
        return;
    }

    // Ambil user berdasarkan username
    $stmt = $conn->prepare("SELECT * FROM user WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Cek password (gunakan password_hash() di database)
        if (password_verify($password, $user['password'])) {
            // Set session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['user_email'] = $user['email'] ?? null; // optional

            // Tentukan redirect berdasarkan role
            $redirectUrl = 'pages/dashboard.php';
            if ($user['role'] === 'superadmin') {
                $redirectUrl = 'pages/dashboard.php';
            } elseif ($user['role'] === 'admin') {
                $redirectUrl = 'pages/dashboard.php';
            } elseif ($user['role'] === 'user') {
                $redirectUrl = 'pages/dashboard.php';
            }

            echo json_encode([
                'success' => true,
                'message' => 'Login berhasil! Mengalihkan ke dashboard...',
                'redirect' => $redirectUrl,
                'user' => [
                    'id' => $user['id'],
                    'username' => $user['username'],
                    'role' => $user['role']
                ]
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Password salah'
            ]);
        }
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Username tidak ditemukan'
        ]);
    }

    $stmt->close();
}

function handleLogout()
{
    logout();
    echo json_encode([
        'success' => true,
        'message' => 'Logout berhasil'
    ]);
}
?>
