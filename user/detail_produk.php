<?php
require_once '../config/koneksi.php';
require_once '../auth.php';
require_user();

$id = intval($_GET['id'] ?? 0);
$query = mysqli_query($conn, "SELECT * FROM produk WHERE id_produk = '$id'") or die(mysqli_error($conn));
$produk = mysqli_fetch_assoc($query);

if (!$produk) {
  echo "Produk tidak ditemukan.";
  exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($produk['nama_produk']) ?> | Detail Produk - HiiStyle</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/style.css">
  <style>
    body {
      background: url('../assets/img/wallpaper.png') no-repeat center center fixed;
      background-size: cover;
      font-family: 'Segoe UI', sans-serif;
      color: #f5f5f5;
      min-height: 100vh;
    }

    .detail-box {
      background-color: rgba(0, 0, 0, 0.85);
      border-radius: 20px;
      padding: 40px;
      box-shadow: 0 0 30px rgba(212,175,55,0.3);
      position: relative;
    }

    .produk-img {
      width: 100%;
      border-radius: 16px;
      object-fit: cover;
      box-shadow: 0 4px 20px rgba(0,0,0,0.6);
    }

    h3 {
      color: #d4af37;
      font-weight: 600;
    }

    .harga {
      color: #eee;
      font-size: 1.3rem;
      margin-bottom: 20px;
    }

    .btn-cart {
      background-color: #d4af37;
      border: none;
      color: #121212;
      font-weight: bold;
      padding: 12px 28px;
      border-radius: 30px;
      box-shadow: 0 0 10px #d4af37;
      transition: 0.3s;
    }

    .btn-cart:hover {
      background-color: #b8962e;
      color: #fff;
    }

    .btn-kembali-top-outer {
      display: inline-block;
      margin: 30px 0 -10px 12px;
    }

    .btn-kembali-top-outer a {
      background-color: transparent;
      color: #d4af37;
      border: 1px solid #d4af37;
      padding: 6px 16px;
      border-radius: 25px;
      font-size: 15px;
      text-decoration: none;
      transition: 0.3s;
    }

    .btn-kembali-top-outer a:hover {
      background-color: #d4af37;
      color: #121212;
    }

    .form-control {
      max-width: 100px;
      display: inline-block;
    }

    footer {
      margin-top: 60px;
      padding: 20px 0;
      text-align: center;
      background-color: #111;
      color: #ccc;
      border-top: 1px solid #444;
    }

    footer .text-gold {
      color: #d4af37;
      font-weight: 600;
    }
  </style>
</head>
<body>

<?php include '../inc/header.php'; ?>

<div class="container my-5">

  <!-- Tombol kembali di luar kotak -->
  <div class="btn-kembali-top-outer">
    <a href="produk.php"><i class="bi bi-arrow-left-circle me-2"></i> Kembali</a>
  </div>

  <div class="row justify-content-center mt-2">
    <div class="col-lg-10 detail-box">

      <div class="row g-5 mt-2">
        <div class="col-md-6">
          <img src="<?= htmlspecialchars($produk['gambar']) ?>" alt="<?= htmlspecialchars($produk['nama_produk']) ?>" class="produk-img" onerror="this.src='https://via.placeholder.com/400x400?text=Gambar+Tidak+Ditemukan';">
        </div>
        <div class="col-md-6">
          <h3><?= htmlspecialchars($produk['nama_produk']) ?></h3>
          <p class="harga">Rp<?= number_format($produk['harga'], 0, ',', '.') ?></p>
          <p><?= nl2br(htmlspecialchars($produk['deskripsi'])) ?></p>

          <form action="keranjang.php" method="post" class="mt-4">
            <input type="hidden" name="id_produk" value="<?= $produk['id_produk'] ?>">
            <div class="mb-3">
              <label for="jumlah" class="form-label">Jumlah:</label>
              <input type="number" name="jumlah" value="1" min="1" class="form-control" required>
            </div>
            <button type="submit" name="tambah" class="btn btn-cart">
              <i class="bi bi-cart-plus me-2"></i> Tambahkan ke Keranjang
            </button>
          </form>
        </div>
      </div>

    </div>
  </div>
</div>

<?php include '../inc/footer.php'; ?>
</body>
</html>
