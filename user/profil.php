<?php
require_once '../config/koneksi.php';
require_once '../auth.php';
require_user();

$id_user = $_SESSION['id_user'];
$query = mysqli_query($conn, "SELECT * FROM user WHERE id_user = '$id_user'");
$user = mysqli_fetch_assoc($query);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nama = mysqli_real_escape_string($conn, $_POST['nama']);
  $username = mysqli_real_escape_string($conn, $_POST['username']);

  // Jika password diisi, update juga
  if (!empty($_POST['password'])) {
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $update = mysqli_query($conn, "UPDATE user SET nama='$nama', username='$username', password='$password' WHERE id_user='$id_user'");
  } else {
    $update = mysqli_query($conn, "UPDATE user SET nama='$nama', username='$username' WHERE id_user='$id_user'");
  }

  if ($update) {
    $_SESSION['nama'] = $nama;
    echo "<script>alert('Profil berhasil diperbarui!'); location.href='profil.php';</script>";
  } else {
    echo "<script>alert('Gagal memperbarui profil.');</script>";
  }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Profil Saya - HiiStyle</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background: #f8f9fb; font-family: 'Segoe UI', sans-serif; }
    .form-control:focus {
      border-color: #007bff;
      box-shadow: 0 0 0 .2rem rgba(0,123,255,.25);
    }
  </style>
</head>
<body>

<?php include '../inc/header.php'; ?>

<div class="container mt-4">
  <h4 class="text-primary mb-4">👤 Profil Saya</h4>

  <form method="POST">
    <div class="mb-3">
      <label class="form-label">Nama Lengkap</label>
      <input type="text" name="nama" value="<?= $user['nama'] ?>" class="form-control" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Username</label>
      <input type="text" name="username" value="<?= $user['username'] ?>" class="form-control" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Password Baru (opsional)</label>
      <input type="password" name="password" class="form-control" placeholder="Biarkan kosong jika tidak ingin mengubah">
    </div>
    <button type="submit" class="btn btn-success">💾 Simpan Perubahan</button>
  </form>
</div>

<?php include '../inc/footer.php'; ?>
</body>
</html>
