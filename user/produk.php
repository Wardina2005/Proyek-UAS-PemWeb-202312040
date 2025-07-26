<?php
require_once '../config/koneksi.php';
require_once '../auth.php';
require_user();

$produk = mysqli_query($conn, "SELECT * FROM produk ORDER BY id_produk DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Produk - HiiStyle</title>
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
      display: flex;
      flex-direction: column;
    }

    .top-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 30px;
    }

    .btn-kembali {
      background-color: #d4af37;
      color: #121212;
      font-weight: 600;
      padding: 8px 20px;
      border-radius: 30px;
      text-decoration: none;
      box-shadow: 0 0 10px #d4af37;
      transition: 0.3s ease;
    }

    .btn-kembali:hover {
      background-color: #fff0c2;
      color: #121212;
      transform: scale(1.05);
    }

    h4 {
      color: #d4af37;
      font-weight: bold;
      margin: 0 auto;
    }

    .card {
      background-color: rgba(0, 0, 0, 0.85);
      color: #fff;
      border-radius: 16px;
      overflow: hidden;
      box-shadow: 0 6px 16px rgba(0,0,0,0.4);
      transition: transform 0.3s ease;
    }

    .card:hover {
      transform: translateY(-5px);
    }

    .card-img-top {
      height: 240px;
      object-fit: cover;
    }

    .card-title {
      font-size: 1.2rem;
      color: #d4af37;
      font-weight: 600;
    }

    .card-text {
      font-size: 0.95rem;
      color: #ccc;
    }

    .btn-detail {
      background-color: #d4af37;
      border: none;
      color: #121212;
      font-weight: bold;
    }

    .btn-detail:hover {
      background-color: #b8962e;
      color: #fff;
    }

    footer {
      margin-top: auto;
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
  <div class="top-row">
    <a href="dashboard.php" class="btn-kembali">← Kembali</a>
    <h4><i class="bi bi-bag-fill me-2"></i>Koleksi Produk Kami</h4>
    <div style="width: 140px;"></div> <!-- untuk keseimbangan posisi kanan -->
  </div>

  <div class="row">
    <?php while ($row = mysqli_fetch_assoc($produk)): ?>
    <div class="col-md-4 mb-4">
      <div class="card h-100">
        <img src="<?= $row['gambar'] ?>" class="card-img-top" alt="<?= htmlspecialchars($row['nama_produk']) ?>" onerror="this.src='https://via.placeholder.com/300x240?text=Gambar+Tidak+Ditemukan';">
        <div class="card-body text-center">
          <h5 class="card-title"><?= htmlspecialchars($row['nama_produk']) ?></h5>
          <p class="card-text">Rp<?= number_format($row['harga'], 0, ',', '.') ?></p>
          <a href="detail_produk.php?id=<?= $row['id_produk'] ?>" class="btn btn-detail btn-sm mt-2">
            <i class="bi bi-eye-fill me-1"></i> Lihat Detail
          </a>
        </div>
      </div>
    </div>
    <?php endwhile; ?>
  </div>
</div>

<?php include '../inc/footer.php'; ?>
</body>
</html>
