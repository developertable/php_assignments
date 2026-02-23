<?php
/**
 * Session Helper Functions
 * Centralized session management
 */

// Check if customer is logged in
function is_customer_logged_in() {
    return isset($_SESSION['customer_id']);
}

// Check if technician is logged in
function is_technician_logged_in() {
    return isset($_SESSION['tech_id']);
}

// Require customer login (redirect if not logged in)
function require_customer_login() {
    if (!is_customer_logged_in()) {
        header("Location: customer_login.php");
        exit();
    }
}

// Require technician login (redirect if not logged in)
function require_technician_login() {
    if (!is_technician_logged_in()) {
        header("Location: technician_login.php");
        exit();
    }
}

// Get current customer ID
function get_customer_id() {
    return $_SESSION['customer_id'] ?? null;
}

// Get current technician ID
function get_technician_id() {
    return $_SESSION['tech_id'] ?? null;
}

// Destroy session and logout
function logout($redirect_url = 'index.php') {
    session_destroy();
    header("Location: $redirect_url");
    exit();
}
?>