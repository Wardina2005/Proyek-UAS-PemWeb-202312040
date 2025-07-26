<?php
/**
 * Authentication System
 * Sistem autentikasi yang aman untuk HiiStyle
 */

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

/**
 * Fungsi untuk mengecek apakah user sudah login
 */
function is_logged_in() {
    return isset($_SESSION['id_user']) && isset($_SESSION['username']) && isset($_SESSION['role']);
}

/**
 * Fungsi untuk mengecek apakah user adalah admin
 */
function is_admin() {
    return is_logged_in() && $_SESSION['role'] === 'admin';
}

/**
 * Fungsi untuk mengecek apakah user adalah user biasa
 */
function is_user() {
    return is_logged_in() && $_SESSION['role'] === 'user';
}

/**
 * Redirect ke login jika belum login
 */
function require_login() {
    if (!is_logged_in()) {
        header("Location: " . get_base_url() . "/login.php");
        exit;
    }
}

/**
 * Redirect jika bukan admin
 */
function require_admin() {
    if (!is_admin()) {
        header("Location: " . get_base_url() . "/login.php?error=access_denied");
        exit;
    }
}

/**
 * Redirect jika bukan user biasa
 */
function require_user() {
    if (!is_user()) {
        header("Location: " . get_base_url() . "/login.php?error=access_denied");
        exit;
    }
}

/**
 * Get base URL
 */
function get_base_url() {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    $script = $_SERVER['SCRIPT_NAME'];
    $path = dirname($script);
    
    // Remove /admin or /user from path
    $path = preg_replace('/\/(admin|user)$/', '', $path);
    
    return $protocol . '://' . $host . $path;
}

/**
 * Set user session
 */
function set_user_session($user_data) {
    $_SESSION['id_user'] = $user_data['id_user'];
    $_SESSION['nama'] = $user_data['nama'];
    $_SESSION['username'] = $user_data['username'];
    $_SESSION['email'] = $user_data['email'];
    $_SESSION['role'] = $user_data['role'];
    $_SESSION['login_time'] = time();
    
    // Regenerate session ID untuk keamanan
    session_regenerate_id(true);
}

/**
 * Destroy user session
 */
function destroy_user_session() {
    session_unset();
    session_destroy();
    session_start();
    session_regenerate_id(true);
}

/**
 * Check session timeout (30 minutes)
 */
function check_session_timeout() {
    if (isset($_SESSION['login_time'])) {
        $timeout = 30 * 60; // 30 minutes
        if (time() - $_SESSION['login_time'] > $timeout) {
            destroy_user_session();
            header("Location: " . get_base_url() . "/login.php?error=session_timeout");
            exit;
        }
        // Update login time
        $_SESSION['login_time'] = time();
    }
}

/**
 * CSRF Protection
 */
function generate_csrf_token() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verify_csrf_token($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// Check session timeout on every request
if (is_logged_in()) {
    check_session_timeout();
}
?>
