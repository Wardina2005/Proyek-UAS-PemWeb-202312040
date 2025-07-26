<?php
require_once '../config/koneksi.php';
require_once '../auth.php';
require_admin();

$id_admin = $_SESSION['id_user'];
$admin = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM user WHERE id_user = $id_admin"));

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $nama     = mysqli_real_escape_string($conn, $_POST['nama']);
  $username = mysqli_real_escape_string($conn, $_POST['username']);

  $query = "UPDATE user SET nama = '$nama', username = '$username' WHERE id_user = $id_admin";
  if (mysqli_query($conn, $query)) {
    $_SESSION['nama'] = $nama;
    $_SESSION['username'] = $username;
    $success = "✅ Profil berhasil diperbarui!";
  } else {
    $error = "❌ Gagal memperbarui profil!";
  }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Pengaturan Admin - HiiStyle</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/style.css">
  <style>
    html, body {
      height: 100%;
      margin: 0;
      display: flex;
      flex-direction: column;
    }

    body {
      background: url('../assets/img/wallpaper.png') no-repeat center center fixed;
      background-size: cover;
      font-family: 'Segoe UI', sans-serif;
      color: #f5f5f5;
    }

    main {
      flex: 1;
    }

    .form-box {
      background-color: rgba(18, 18, 18, 0.95);
      padding: 2.5rem;
      border-radius: 14px;
      box-shadow: 0 0 18px rgba(0,0,0,0.3);
      color: #fff;
      border: 2px solid #d4af37;
    }

    .form-box h4 {
      color: #d4af37;
      font-weight: bold;
      margin-bottom: 1.5rem;
    }

    .form-control {
      background-color: #121212;
      border: 1px solid #d4af37;
      color: #f5f5f5;
    }

    .form-control:focus {
      background-color: #1c1c1c;
      color: #fff;
      border-color: #ffd700;
      box-shadow: 0 0 8px rgba(212, 175, 55, 0.5);
    }

    .btn-gold {
      background-color: #d4af37;
      border: none;
      color: #121212;
      font-weight: 600;
      box-shadow: 0 0 6px #d4af37;
      transition: all 0.3s ease-in-out;
    }

    .btn-gold:hover {
      background-color: #b8962e;
      color: #fff;
      box-shadow: 0 0 10px #d4af37;
      transform: scale(1.03);
    }

    .btn-secondary:hover {
      background-color: #333;
      color: #d4af37;
    }
  </style>
</head>
<body>

<?php include '../inc/header.php'; ?>

<main class="container mt-5 mb-5">
  <div class="row justify-content-center">
    <div class="col-md-6 form-box">
      <h4><i class="bi bi-person-gear me-2"></i>Pengaturan Profil Admin</h4>

      <?php if (!empty($success)): ?>
        <div class="alert alert-success"><?= $success ?></div>
      <?php elseif (!empty($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
      <?php endif; ?>

      <form method="POST">
        <div class="mb-3">
          <label class="form-label">Nama Lengkap</label>
          <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($admin['nama']) ?>" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Username</label>
          <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($admin['username']) ?>" required>
        </div>

        <button type="submit" class="btn btn-gold w-100 mb-2"><i class="bi bi-check-circle me-1"></i> Simpan Perubahan</button>
        <a href="dashboard.php" class="btn btn-secondary w-100"><i class="bi bi-arrow-left"></i> Kembali</a>
      </form>
    </div>
  </div>
</main>

<?php include '../inc/footer.php'; ?>
</body>
</html>
