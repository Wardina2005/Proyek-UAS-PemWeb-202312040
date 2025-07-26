<?php
require_once '../config/koneksi.php';
require_once '../auth.php';
require_user();

$id_user = $_SESSION['id_user'];
$id_produk = $_GET['id'] ?? 0;

// Cek apakah produk pernah dibeli user
$cek = mysqli_query($conn, "
  SELECT dt.* FROM detail_transaksi dt 
  JOIN transaksi t ON dt.id_transaksi = t.id_transaksi 
  WHERE t.id_user = '$id_user' AND dt.id_produk = '$id_produk'
");

if (mysqli_num_rows($cek) == 0) {
  echo "<script>alert('Kamu belum membeli produk ini!'); location.href='riwayat_pembelian.php';</script>";
  exit;
}

// Simpan ulasan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $isi = mysqli_real_escape_string($conn, $_POST['isi']);
  $rating = (int)$_POST['rating'];

  mysqli_query($conn, "INSERT INTO ulasan (id_user, id_produk, isi, rating, tanggal) 
    VALUES ('$id_user', '$id_produk', '$isi', '$rating', NOW())");

  echo "<script>alert('Ulasan berhasil dikirim!'); location.href='produk.php';</script>";
  exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Kirim Ulasan - HiiStyle</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background-color: #f9f9ff; font-family: 'Segoe UI', sans-serif; }
    textarea { resize: vertical; }
  </style>
</head>
<body>

<?php include '../inc/header.php'; ?>

<div class="container mt-4">
  <h4 class="mb-4 text-primary">📝 Kirim Ulasan</h4>

  <form method="post">
    <div class="mb-3">
      <label for="isi" class="form-label">Ulasan Kamu:</label>
      <textarea name="isi" id="isi" rows="4" class="form-control" required></textarea>
    </div>
    <div class="mb-3">
      <label class="form-label">Rating:</label>
      <select name="rating" class="form-select" required style="width: 150px;">
        <option value="">Pilih Rating</option>
        <option value="5">⭐⭐⭐⭐⭐ - Sangat Baik</option>
        <option value="4">⭐⭐⭐⭐ - Baik</option>
        <option value="3">⭐⭐⭐ - Cukup</option>
        <option value="2">⭐⭐ - Kurang</option>
        <option value="1">⭐ - Buruk</option>
      </select>
    </div>
    <button type="submit" class="btn btn-primary">Kirim Ulasan</button>
  </form>
</div>

<?php include '../inc/footer.php'; ?>
</body>
</html>
