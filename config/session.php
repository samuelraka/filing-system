<?php
// session.php
// Session management for the archiving system

// Start session
session_start();

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Check if user is admin
function isAdmin() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

// Get current user role
function getUserRole() {
    return isset($_SESSION['user_role']) ? $_SESSION['user_role'] : 'guest';
}

// Get current user name
function getUserName() {
    return isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Guest';
}

// Dummy authentication function (replace with real DB authentication)
function authenticateUser($email, $password) {
    // This is a dummy authentication - replace with real database query
    $dummyUsers = [
        'admin@example.com' => [
            'id' => 1,
            'name' => 'Administrator',
            'email' => 'admin@example.com',
            'password' => 'admin123',
            'role' => 'admin'
        ],
        'user@example.com' => [
            'id' => 2,
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'password' => 'user123',
            'role' => 'user'
        ],
        'manager@example.com' => [
            'id' => 3,
            'name' => 'Manager',
            'email' => 'manager@example.com',
            'password' => 'manager123',
            'role' => 'manager'
        ]
    ];
    
    if (isset($dummyUsers[$email]) && $dummyUsers[$email]['password'] === $password) {
        return $dummyUsers[$email];
    }
    
    return false;
}

// Login function
function login($email, $password) {
    $user = authenticateUser($email, $password);
    
    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_role'] = $user['role'];
        return true;
    }
    
    return false;
}

// Logout function
function logout() {
    session_destroy();
    header('Location: ../login.php');
    exit();
}

// Redirect if not logged in
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: ../login.php');
        exit();
    }
}

// Redirect if not admin
function requireAdmin() {
    if (!isAdmin()) {
        header('Location: ../dashboard.php');
        exit();
    }
}
?>
