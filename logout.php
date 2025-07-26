<?php
/**
 * Logout Handler
 * Menangani proses logout untuk semua user
 */

require_once __DIR__ . '/config/koneksi.php';
require_once __DIR__ . '/auth.php';

// Log activity sebelum logout
if (is_logged_in()) {
    log_activity($conn, $_SESSION['id_user'], "Logout dari sistem");
}

// Destroy session
destroy_user_session();

// Redirect ke halaman utama
header("Location: index.php?message=logged_out");
exit;
?>