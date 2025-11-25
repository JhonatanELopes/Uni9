<?php
// Ensure session is started if not already, especially if functions depend on $_SESSION
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

function isLoggedIn() {
    return isset($_SESSION['id']);
}

function isAdmin() {
    return isLoggedIn() && isset($_SESSION['Gerente']) && $_SESSION['Gerente'] === 1;
}

function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: /login.php?error=pleaselogin"); // Adjust path as needed
        exit();
    }
}

function requireAdmin() {
    if (!isAdmin()) {
        // Optionally, redirect to a "not authorized" page or back to index
        header("Location: /index.php?error=unauthorized"); // Adjust path as needed
        exit();
    }
}
?>