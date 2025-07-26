<?php
require_once '../config/koneksi.php';
require_once '../auth.php';
require_admin();

if (!isset($_GET['id'])) {
  header("Location: data_user.php");
  exit;
}

$id = $_GET['id'];
$query = mysqli_query($conn, "SELECT * FROM user WHERE id_user=$id");
$user = mysqli_fetch_assoc($query);

if (!$user) {
  echo "User tidak ditemukan.";
  exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $nama = $_POST['nama'];
  $username = $_POST['username'];
  $role = $_POST['role'];

  $update = mysqli_query($conn, "UPDATE user SET nama='$nama', username='$username', role='$role' WHERE id_user=$id");

  if ($update) {
    header("Location: data_user.php");
    exit;
  } else {
    $error = "Gagal update user!";
  }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit User - HiiStyle</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include '../inc/header.php'; ?>
<div class="container mt-4">
  <h4 class="mb-4 text-primary">✏️ Edit Pengguna</h4>
  <?php if (isset($error)): ?>
    <div class="alert alert-danger"><?= $error ?></div>
  <?php endif; ?>
  <form method="POST">
    <div class="mb-3">
      <label>Nama</label>
      <input type="text" name="nama" value="<?= $user['nama'] ?>" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Username</label>
      <input type="text" name="username" value="<?= $user['username'] ?>" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Role</label>
      <select name="role" class="form-select" required>
        <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
        <option value="user" <?= $user['role'] == 'user' ? 'selected' : '' ?>>User</option>
      </select>
    </div>
    <button type="submit" class="btn btn-primary">Update</button>
    <a href="data_user.php" class="btn btn-secondary">Kembali</a>
  </form>
</div>
<?php include '../inc/footer.php'; ?>
</body>
</html>
