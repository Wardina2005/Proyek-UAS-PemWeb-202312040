<?php
require_once '../config/koneksi.php';
require_once '../auth.php';
require_user();

$id_user = $_SESSION['id_user'];

// Ambil isi keranjang
$keranjang = mysqli_query($conn, "
  SELECT k.*, p.harga 
  FROM keranjang k 
  JOIN produk p ON k.id_produk = p.id_produk 
  WHERE k.id_user = '$id_user'
");

// Hitung total belanja
$total = 0;
$data_keranjang = [];
while ($row = mysqli_fetch_assoc($keranjang)) {
  $subtotal = $row['jumlah'] * $row['harga'];
  $total += $subtotal;
  $data_keranjang[] = $row;
}

// Jika tombol checkout diklik
if ($_SERVER['REQUEST_METHOD'] === 'POST' && count($data_keranjang) > 0) {
  $tgl = date('Y-m-d H:i:s');

  // Tambah transaksi
  mysqli_query($conn, "
    INSERT INTO transaksi (id_user, total, status, tanggal_transaksi) 
    VALUES ('$id_user', '$total', 'selesai', '$tgl')
  ");
  $id_transaksi = mysqli_insert_id($conn);

  // Tambah detail transaksi
  foreach ($data_keranjang as $item) {
    $subtotal = $item['jumlah'] * $item['harga'];
    mysqli_query($conn, "
      INSERT INTO detail_transaksi (id_transaksi, id_produk, jumlah, subtotal)
      VALUES ('$id_transaksi', '{$item['id_produk']}', '{$item['jumlah']}', '$subtotal')
    ");
  }

  // Hapus isi keranjang
  mysqli_query($conn, "DELETE FROM keranjang WHERE id_user = '$id_user'");

  // Redirect
  echo "<script>alert('Checkout berhasil!'); location.href='riwayat_pembelian.php';</script>";
  exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Checkout - Hii Style</title>
  <link rel="stylesheet" href="../assets/css/style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: url('../assets/img/wallpaper.png') no-repeat center center fixed;
      background-size: cover;
      font-family: 'Segoe UI', sans-serif;
      color: #f5f5f5;
    }

    .checkout-container {
      max-width: 700px;
      margin: 80px auto;
      background-color: rgba(0,0,0,0.88);
      padding: 40px;
      border-radius: 20px;
      border: 2px solid #d4af37;
      box-shadow: 0 0 25px rgba(212, 175, 55, 0.3);
    }

    .checkout-title {
      color: #d4af37;
      font-weight: bold;
      font-size: 1.8rem;
      margin-bottom: 25px;
      text-align: center;
    }

    .checkout-card {
      background-color: #121212;
      border: 1px solid #444;
      padding: 20px;
      border-radius: 12px;
      color: #f5f5f5;
    }

    .total-text {
      font-size: 1.3rem;
      color: #d4af37;
      font-weight: bold;
    }

    .checkout-btn {
      background-color: #d4af37;
      color: #121212;
      font-weight: bold;
      border: none;
      padding: 14px 30px;
      border-radius: 30px;
      box-shadow: 0 0 12px #d4af37;
      transition: all 0.3s ease-in-out;
      width: 100%;
    }

    .checkout-btn:hover {
      background-color: #b8962e;
      color: #fff;
      transform: scale(1.05);
    }

    .alert-custom {
      background-color: #1e1e1e;
      border-left: 5px solid #d4af37;
      color: #fff;
      padding: 15px;
      border-radius: 8px;
      text-align: center;
    }
  </style>
</head>
<body>

<?php include '../inc/header.php'; ?>

<div class="checkout-container">
  <div class="checkout-title">
    <i class="fas fa-credit-card glow-icon me-2"></i>Konfirmasi Checkout
  </div>

  <?php if (count($data_keranjang) == 0): ?>
    <div class="alert-custom">Keranjang kamu kosong. Silakan belanja terlebih dahulu.</div>
  <?php else: ?>
    <div class="checkout-card mb-4">
      <p class="total-text">Total Belanja: Rp<?= number_format($total, 0, ',', '.') ?></p>
      <p>Pastikan produk dan jumlah pesanan kamu sudah benar sebelum checkout.</p>
      <form method="POST">
        <button type="submit" class="checkout-btn"><i class="fas fa-shopping-bag me-2"></i>Konfirmasi & Checkout</button>
      </form>
    </div>
  <?php endif; ?>
</div>

<?php include '../inc/footer.php'; ?>
</body>
</html>
