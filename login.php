<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'config/koneksi.php';
require_once 'auth.php';

// Redirect jika sudah login
if (is_logged_in()) {
    if (is_admin()) {
        header("Location: admin/dashboard.php");
    } else {
        header("Location: user/dashboard.php");
    }
    exit;
}

$error = '';
$success = '';

// Handle error messages
if (isset($_GET['error'])) {
    switch ($_GET['error']) {
        case 'access_denied':
            $error = "⚠️ Akses ditolak! Anda tidak memiliki izin.";
            break;
        case 'session_timeout':
            $error = "⚠️ Sesi Anda telah berakhir. Silakan login kembali.";
            break;
    }
}

if (isset($_GET['success']) && $_GET['success'] == 'registered') {
    $success = "✅ Registrasi berhasil! Silakan login.";
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $csrf_token = $_POST['csrf_token'] ?? '';
    
    // Verify CSRF token
    if (!verify_csrf_token($csrf_token)) {
        $error = "⚠️ Token keamanan tidak valid!";
    } else {
        $username = sanitize_input($_POST['username']);
        $password = $_POST['password'];

        if (empty($username) || empty($password)) {
            $error = "⚠️ Username dan password harus diisi!";
        } else {
            // Use prepared statement untuk keamanan
            $user = $db->get_row("SELECT * FROM user WHERE username = ?", [$username]);

            if ($user) {
                if ($password === $user['password']) { // Menggunakan plain text password
                    // Set session
                    set_user_session($user);
                    
                    // Log activity
                    log_activity($conn, $user['id_user'], "Login ke sistem");

                    if ($user['role'] === 'admin') {
                        header("Location: admin/dashboard.php");
                    } else {
                        header("Location: user/dashboard.php");
                    }
                    exit;
                } else {
                    $error = "⚠️ Password salah!";
                }
            } else {
                $error = "⚠️ Username tidak ditemukan!";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Login - HiiStyle</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/css/main.css" rel="stylesheet">
  <link href="assets/css/hamburger-admin.css" rel="stylesheet">
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(135deg, #1C1C28 0%, #181823 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      justify-content: center;
      height: 100vh;
    }

    .login-card {
      background-color: #23232E;
      padding: 40px;
      border-radius: 20px;
      box-shadow: 0 0 30px rgba(244, 196, 48, 0.3);
      width: 100%;
      max-width: 400px;
      color: #FFFFFF;
      border: 1px solid rgba(244, 196, 48, 0.2);
    }

    .login-card h3 {
      color: #F4C430;
      font-weight: bold;
      text-align: center;
      margin-bottom: 30px;
      text-transform: uppercase;
      letter-spacing: 2px;
      text-shadow: 0 0 4px #F4C430, 0 0 8px #FFD95E;
    }

    .form-label {
      color: #F4C430;
      font-weight: 500;
    }

    .form-control {
      border-radius: 10px;
      background-color: #1C1C28;
      border: 1px solid rgba(244, 196, 48, 0.3);
      color: #FFFFFF;
    }

    .form-control:focus {
      background-color: #26263A;
      border-color: #F4C430;
      box-shadow: 0 0 12px rgba(244, 196, 48, 0.2);
    }

    .btn-login {
      background-color: #F4C430;
      border: none;
      border-radius: 12px;
      padding: 12px;
      font-size: 16px;
      font-weight: 600;
      color: #1C1C28;
      transition: all 0.3s ease;
      box-shadow: 0 0 12px rgba(244, 196, 48, 0.3);
    }

    .btn-login:hover {
      background-color: #FFD95E;
      color: #1C1C28;
      box-shadow: 0 0 20px rgba(244, 196, 48, 0.5);
      transform: translateY(-1px);
    }

    .alert-danger {
      font-size: 14px;
      border-radius: 10px;
      background-color: rgba(255, 92, 92, 0.1);
      border-color: #FF5C5C;
      color: #FF5C5C;
    }

    .alert-success {
      font-size: 14px;
      border-radius: 10px;
      background-color: rgba(76, 217, 100, 0.1);
      border-color: #4CD964;
      color: #4CD964;
    }

    .signup-text {
      text-align: center;
      margin-top: 20px;
    }

    .signup-text a {
      color: #F4C430;
      text-decoration: none;
      font-weight: 600;
    }

    .signup-text a:hover {
      text-decoration: underline;
      color: #FFD95E;
    }
  </style>
</head>
<body>

<div class="login-card">
  <h3>HiiStyle</h3>

  <?php if ($error): ?>
    <div class="alert alert-danger text-center"><?= $error ?></div>
  <?php endif; ?>
  
  <?php if ($success): ?>
    <div class="alert alert-success text-center"><?= $success ?></div>
  <?php endif; ?>

  <form method="POST" action="">
    <input type="hidden" name="csrf_token" value="<?= generate_csrf_token() ?>">
    <div class="mb-3">
      <label for="username" class="form-label">Username</label>
      <input type="text" name="username" class="form-control" required autofocus>
    </div>

    <div class="mb-3">
      <label for="password" class="form-label">Password</label>
      <input type="password" name="password" class="form-control" required>
    </div>

    <div class="d-grid">
      <button type="submit" class="btn btn-login">Login</button>
    </div>
  </form>

  <div class="signup-text">
    Belum punya akun? <a href="register.php">Daftar di sini</a>
  </div>
</div>

  <script src="assets/js/main.js"></script>
  <script src="assets/js/hamburger-admin.js"></script>
  <script>
    // Initialize hamburger panel based on role
    document.addEventListener('DOMContentLoaded', function() {
      // Panel will be initialized automatically by main.js and hamburger-admin.js
    });
  </script>
</body>
</html>
