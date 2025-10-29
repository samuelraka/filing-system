<?php
// session.php
// Session management for the archiving system

session_start();

// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'archives_db';

// Create database connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

/**
 * Check if user is logged in
 */
function isLoggedIn() {
    return isset($_SESSION['id_user']);
}

/**
 * Check if user is admin
 */
function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

/**
 * Check if user is admin or superadmin
 */
function isAdminOrSuperAdmin() {
    return isset($_SESSION['role']) && ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'superadmin');
}

/**
 * Get current user role
 */
function getUserRole() {
    return $_SESSION['role'] ?? 'guest';
}

/**
 * Get current username
 */
function getUserName() {
    return $_SESSION['username'] ?? 'Guest';
}

/**
 * Authenticate user with username + password
 * Returns user array if valid, false if invalid
 */
function authenticateUser($username, $password) {
    global $conn;

    $stmt = $conn->prepare("SELECT * FROM user WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            return $user; // valid user
        }
    }

    return false; // invalid user
}

/**
 * Login user (set session)
 */
function login($username, $password) {
    $user = authenticateUser($username, $password);

    if ($user) {
        $_SESSION['id_user'] = $user['id_user'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['nama'] = $user['nama'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['user_email'] = $user['email'] ?? null;

        return true;
    }

    return false;
}

function getFullName() {
    return $_SESSION['nama'] ?? getUserName();
}


/**
 * Logout and destroy session
 */
function logout() {
    session_unset();
    session_destroy();
}

/**
 * Require login (redirect if not logged in)
 */
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: ../login.php');
        exit();
    }
}

/**
 * Require admin access
 */
function requireAdmin() {
    if (!isAdmin()) {
        header('Location: ../pages/dashboard.php');
        exit();
    }
}
?>
