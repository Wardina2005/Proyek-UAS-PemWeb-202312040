<?php
$page_title = 'Tambah Kategori';
require_once '../config/koneksi.php';
require_once '../auth.php';
require_admin();

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $nama_kategori = mysqli_real_escape_string($conn, $_POST['nama_kategori']);

  // Cek apakah kategori sudah ada
  $cek = mysqli_query($conn, "SELECT * FROM kategori WHERE nama_kategori = '$nama_kategori'");
  if (mysqli_num_rows($cek) > 0) {
    $error = "❌ Kategori sudah ada!";
  } else {
    $query = mysqli_query($conn, "INSERT INTO kategori (nama_kategori) VALUES ('$nama_kategori')");
    if ($query) {
      $success = "✅ Kategori berhasil ditambahkan!";
    } else {
      $error = "❌ Gagal menambahkan kategori!";
    }
  }
}
?>

<?php include '../inc/header.php'; ?>

<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-6 form-box">
      <h4 class="mb-4 text-primary">➕ Tambah Kategori Baru</h4>

      <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
      <?php endif; ?>

      <?php if ($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
      <?php endif; ?>

      <form method="POST" action="">
        <div class="mb-3">
          <label for="nama_kategori" class="form-label">Nama Kategori</label>
          <input type="text" name="nama_kategori" id="nama_kategori" class="form-control" required autofocus>
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="kategori.php" class="btn btn-secondary">Kembali</a>
      </form>
    </div>
  </div>
</div>

<?php include '../inc/footer.php'; ?>
