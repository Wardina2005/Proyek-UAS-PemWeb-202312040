<?php
$page_title = 'Edit Kategori';
require_once '../config/koneksi.php';
require_once '../auth.php';
require_admin();

if (!isset($_GET['id'])) {
    header("Location: kategori.php");
    exit;
}

$id = intval($_GET['id']);
$query = mysqli_query($conn, "SELECT * FROM kategori WHERE id_kategori = $id");
$kategori = mysqli_fetch_assoc($query);

if (!$kategori) {
    echo "Kategori tidak ditemukan.";
    exit;
}

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nama_kategori = mysqli_real_escape_string($conn, $_POST['nama_kategori']);

    if (!empty($nama_kategori)) {
        $update = mysqli_query($conn, "UPDATE kategori SET nama_kategori='$nama_kategori' WHERE id_kategori=$id");
        if ($update) {
            $success = "✅ Kategori berhasil diperbarui.";
            $kategori['nama_kategori'] = $nama_kategori;
        } else {
            $error = "❌ Gagal memperbarui kategori.";
        }
    } else {
        $error = "⚠️ Nama kategori tidak boleh kosong.";
    }
}
?>

<?php include '../inc/header.php'; ?>

  <!-- Form Edit -->
  <div class="container py-4">
    <div class="bg-light-gold p-4 rounded shadow" style="max-width: 500px; margin: auto;">
      <h4 class="text-gold mb-4"><i class="bi bi-pencil-square"></i> Edit Kategori</h4>

      <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
      <?php elseif ($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
      <?php endif; ?>

      <form method="POST">
        <div class="mb-3">
          <label class="form-label">Nama Kategori</label>
          <input type="text" name="nama_kategori" class="form-control" value="<?= htmlspecialchars($kategori['nama_kategori']) ?>" required>
        </div>
        <button type="submit" class="btn btn-gold w-100"><i class="bi bi-save"></i> Simpan Perubahan</button>
        <a href="kategori.php" class="btn btn-secondary w-100 mt-2"><i class="bi bi-arrow-left"></i> Kembali</a>
      </form>
    </div>
  </div>
</div>

<?php include '../inc/footer.php'; ?>
