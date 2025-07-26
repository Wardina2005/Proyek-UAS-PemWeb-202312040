<?php
// Database Configuration
$host = "localhost";
$user = "root";
$pass = "";
$db   = "hii_style";

// Create connection
$conn = mysqli_connect($host, $user, $pass, $db);

// Check connection
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Set charset to UTF-8
mysqli_set_charset($conn, "utf8");

// Include core files
require_once __DIR__ . '/../core/functions.php';
require_once __DIR__ . '/../core/Database.php';

// Initialize Database helper
$db = new Database($conn);
?>