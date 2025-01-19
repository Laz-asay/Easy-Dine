<?php
session_start();

// Set session timeout to 30 minutes
$timeout = 30 * 60; // 30 minutes in seconds

// Check if the session is active
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout) {
    // Session has expired
    session_unset();
    session_destroy();
    header("Location: error.php?error=session_expired"); // Redirect to an error page
    exit();
}

// Update last activity time
$_SESSION['last_activity'] = time();

// Exclude specific pages (like table_page.php) from session validation
$excludedPages = ['table_page.php'];
$currentPage = basename($_SERVER['PHP_SELF']);

if (!in_array($currentPage, $excludedPages)) {
    // Check if table session exists
    if (!isset($_SESSION['table_number'])) {
        // Redirect to an error page if session does not exist
        header("Location: error.php?error=table_not_set");
        exit();
    }
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}