<?php
$page_title = "Tambah User";
require_once '../config/koneksi.php';
require_once '../auth.php';
require_admin();

$error = '';
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $nama     = mysqli_real_escape_string($conn, $_POST['nama']);
  $username = mysqli_real_escape_string($conn, $_POST['username']);
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
  $role     = $_POST['role'];

  $cek = mysqli_query($conn, "SELECT * FROM user WHERE username = '$username'");
  if (mysqli_num_rows($cek) > 0) {
    $error = "⚠️ Username sudah digunakan!";
  } else {
    $query = "INSERT INTO user (nama, username, password, role, created_at) 
              VALUES ('$nama', '$username', '$password', '$role', NOW())";
    if (mysqli_query($conn, $query)) {
      header("Location: data_user.php");
      exit;
    } else {
      $error = "❌ Gagal menyimpan user.";
    }
  }
}

include '../inc/header.php';
?>

<style>
    body {
      background: url('../assets/img/wallpaper.png') no-repeat center center fixed;
      background-size: cover;
      font-family: 'Segoe UI', sans-serif;
      color: #f5f5f5;
    }

    .form-container {
      background-color: rgba(18, 18, 18, 0.95);
      border-radius: 12px;
      padding: 2.5rem;
      max-width: 650px; /* Lebih lebar */
      margin: 4rem auto;
      border: 1px solid #d4af37;
      box-shadow: 0 0 12px rgba(212, 175, 55, 0.2);
    }

    label {
      color: #f5f5f5;
      font-weight: 500;
    }

    .form-control, .form-select {
      background-color: #121212;
      border: 1px solid #d4af37;
      color: #fff;
    }

    .form-control:focus, .form-select:focus {
      border-color: #ffd700;
      box-shadow: 0 0 8px rgba(212, 175, 55, 0.5);
      background-color: #1c1c1c;
      color: #fff;
    }

    .btn-gold {
      background-color: #d4af37;
      color: #121212;
      border: none;
      font-weight: bold;
    }

    .btn-gold:hover {
      background-color: #b8962e;
      color: #fff;
    }
  </style>

  <div class="form-container">
    <h4 class="text-gold mb-4"><i class="bi bi-person-plus me-2"></i> Tambah Pengguna Baru</h4>

    <?php if (!empty($error)): ?>
      <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST">
      <div class="mb-3">
        <label for="nama">Nama Lengkap</label>
        <input type="text" name="nama" id="nama" class="form-control" required>
      </div>
      <div class="mb-3">
        <label for="username">Username</label>
        <input type="text" name="username" id="username" class="form-control" required>
      </div>
      <div class="mb-3">
        <label for="password">Password</label>
        <input type="password" name="password" id="password" class="form-control" required>
      </div>
      <div class="mb-4">
        <label for="role">Role</label>
        <select name="role" id="role" class="form-select" required>
          <option value="">-- Pilih Role --</option>
          <option value="admin">Admin</option>
          <option value="user">User</option>
        </select>
      </div>
      <button type="submit" class="btn btn-gold w-100 mb-2"><i class="bi bi-check-circle me-1"></i> Simpan</button>
      <a href="data_user.php" class="btn btn-secondary w-100"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
    </form>
  </div>

<?php include '../inc/footer.php'; ?>
