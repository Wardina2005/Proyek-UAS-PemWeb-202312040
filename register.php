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

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $csrf_token = $_POST['csrf_token'] ?? '';
    
    // Verify CSRF token
    if (!verify_csrf_token($csrf_token)) {
        $error = "⚠️ Token keamanan tidak valid!";
    } else {
        $nama = sanitize_input($_POST['nama']);
        $username = sanitize_input($_POST['username']);
        $email = sanitize_input($_POST['email']);
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];
        $role = 'user'; // default user biasa

        // Validasi input
        if (empty($nama) || empty($username) || empty($email) || empty($password)) {
            $error = "⚠️ Semua field harus diisi!";
        } elseif (!validate_email($email)) {
            $error = "⚠️ Format email tidak valid!";
        } elseif (strlen($password) < 6) {
            $error = "⚠️ Password minimal 6 karakter!";
        } elseif ($password !== $confirm_password) {
            $error = "⚠️ Konfirmasi password tidak cocok!";
        } else {
            // Cek apakah username sudah digunakan
            $existing_user = $db->get_row("SELECT id_user FROM user WHERE username = ? OR email = ?", [$username, $email]);
            
            if ($existing_user) {
                $error = "⚠️ Username atau email sudah digunakan!";
            } else {
                $user_data = [
                    'nama' => $nama,
                    'username' => $username,
                    'email' => $email,
                    'password' => $password, // Menggunakan plain text password
                    'role' => $role
                ];
                
                if ($db->insert('user', $user_data)) {
                    $success = "✅ Registrasi berhasil! Silakan login.";
                    header("Refresh:2; url=login.php?success=registered");
                } else {
                    $error = "❌ Gagal menyimpan data. Silakan coba lagi.";
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Daftar Akun - HiiStyle</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/css/main.css" rel="stylesheet">
  <link href="assets/css/hamburger-admin.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #1C1C28 0%, #181823 100%);
      font-family: 'Segoe UI', sans-serif;
      min-height: 100vh;
      color: #FFFFFF;
    }
    .register-box {
      margin-top: 80px;
      background-color: #23232E;
      padding: 30px;
      border-radius: 20px;
      box-shadow: 0 0 30px rgba(244, 196, 48, 0.3);
      border: 1px solid rgba(244, 196, 48, 0.2);
      color: #FFFFFF;
    }
    .form-label {
      color: #F4C430;
      font-weight: 500;
    }
    .form-control {
      background-color: #1C1C28;
      border: 1px solid rgba(244, 196, 48, 0.3);
      color: #FFFFFF;
      border-radius: 10px;
    }
    .form-control:focus {
      background-color: #26263A;
      border-color: #F4C430;
      box-shadow: 0 0 12px rgba(244, 196, 48, 0.2);
      color: #FFFFFF;
    }
    .btn-primary {
      background-color: #F4C430;
      border: none;
      color: #1C1C28;
      font-weight: 600;
      box-shadow: 0 0 12px rgba(244, 196, 48, 0.3);
      transition: all 0.3s ease;
    }
    .btn-primary:hover {
      background-color: #FFD95E;
      color: #1C1C28;
      box-shadow: 0 0 20px rgba(244, 196, 48, 0.5);
      transform: translateY(-1px);
    }
    .text-primary {
      color: #F4C430 !important;
      text-shadow: 0 0 4px #F4C430, 0 0 8px #FFD95E;
    }
    .alert-danger {
      background-color: rgba(255, 92, 92, 0.1);
      border-color: #FF5C5C;
      color: #FF5C5C;
      border-radius: 10px;
    }
    .alert-success {
      background-color: rgba(76, 217, 100, 0.1);
      border-color: #4CD964;
      color: #4CD964;
      border-radius: 10px;
    }
    .text-muted {
      color: #A0A0B0 !important;
    }
    a {
      color: #F4C430;
      text-decoration: none;
    }
    a:hover {
      color: #FFD95E;
    }
  </style>
</head>
<body>

<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-5 register-box">
      <h3 class="text-center mb-4 text-primary">Daftar Akun Baru</h3>

      <?php if ($error): ?>
        <div class="alert alert-danger text-center"><?= $error ?></div>
      <?php elseif ($success): ?>
        <div class="alert alert-success text-center"><?= $success ?></div>
      <?php endif; ?>

      <form method="POST" action="">
        <input type="hidden" name="csrf_token" value="<?= generate_csrf_token() ?>">
        
        <div class="mb-3">
          <label for="nama" class="form-label">Nama Lengkap</label>
          <input type="text" name="nama" class="form-control" required>
        </div>

        <div class="mb-3">
          <label for="username" class="form-label">Username</label>
          <input type="text" name="username" class="form-control" required>
        </div>
        
        <div class="mb-3">
          <label for="email" class="form-label">Email</label>
          <input type="email" name="email" class="form-control" required>
        </div>

        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <input type="password" name="password" class="form-control" required minlength="6">
          <small class="form-text text-muted">Minimal 6 karakter</small>
        </div>
        
        <div class="mb-3">
          <label for="confirm_password" class="form-label">Konfirmasi Password</label>
          <input type="password" name="confirm_password" class="form-control" required minlength="6">
        </div>

        <div class="d-grid">
          <button type="submit" class="btn btn-primary">Daftar</button>
        </div>
      </form>

      <p class="mt-3 text-center">Sudah punya akun? <a href="login.php" class="text-decoration-none">Login di sini</a></p>
    </div>
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
