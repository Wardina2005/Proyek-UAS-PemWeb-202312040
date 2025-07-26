<?php
require_once '../config/koneksi.php';
require_once '../auth.php';
require_admin();

if (!isset($_GET['id'])) {
  header("Location: transaksi.php");
  exit;
}

$id = intval($_GET['id']);

// Ambil data transaksi utama
$transaksi = mysqli_query($conn, "
  SELECT t.*, u.nama AS nama_user 
  FROM transaksi t 
  JOIN user u ON t.id_user = u.id_user 
  WHERE t.id_transaksi = $id
");

$data = mysqli_fetch_assoc($transaksi);
if (!$data) {
  echo "<p>Data transaksi tidak ditemukan.</p>";
  exit;
}

// Ambil detail item produk dalam transaksi
$items = mysqli_query($conn, "
  SELECT dt.*, p.nama_produk, p.harga 
  FROM detail_transaksi dt
  JOIN produk p ON dt.id_produk = p.id_produk
  WHERE dt.id_transaksi = $id
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Detail Transaksi - HiiStyle</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="../assets/css/style.css" rel="stylesheet">
  <style>
    html, body {
      height: 100%;
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background: url('../assets/img/wallpaper.png') no-repeat center center fixed;
      background-size: cover;
      color: #f5f5f5;
      display: flex;
      flex-direction: column;
    }

    main {
      flex: 1;
    }

    .container {
      margin-top: 4rem;
      margin-bottom: 4rem;
    }

    .card-detail {
      background-color: rgba(18, 18, 18, 0.95);
      border-radius: 12px;
      padding: 2rem;
      box-shadow: 0 0 12px rgba(212, 175, 55, 0.2);
    }

    .table thead th {
      background-color: #1e1e1e;
      color: #d4af37;
      border-color: #333;
    }

    .table tbody tr {
      background-color: #121212;
      color: #fff;
      border-color: #2b2b2b;
    }

    .badge-status {
      font-size: 0.9rem;
      padding: 5px 12px;
      border-radius: 12px;
      font-weight: 600;
    }

    .badge-pending {
      background-color: #ffc107;
      color: #000;
    }

    .badge-selesai {
      background-color: #28a745;
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

    .info-transaksi p {
      margin: 0.2rem 0;
    }

    footer {
      background-color: #1a1a1a;
      text-align: center;
      padding: 1rem;
      font-size: 0.9rem;
      color: #999;
      border-top: 1px solid #2e2e2e;
    }
  </style>
</head>
<body>

<?php include '../inc/header.php'; ?>

<main class="container">
  <div class="card-detail">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h4><i class="bi bi-file-earmark-text me-2"></i> Detail Transaksi</h4>
      <a href="transaksi.php" class="btn btn-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
    </div>

    <div class="info-transaksi mb-4">
      <p><strong>Nama User:</strong> <?= htmlspecialchars($data['nama_user']) ?></p>
      <p><strong>Tanggal:</strong> <?= date('d M Y H:i', strtotime($data['tanggal_transaksi'])) ?></p>
      <p><strong>Total:</strong> Rp<?= number_format($data['total'], 0, ',', '.') ?></p>
      <p><strong>Status:</strong>
        <span class="badge-status <?= $data['status'] == 'selesai' ? 'badge-selesai' : 'badge-pending' ?>">
          <?= ucfirst($data['status']) ?>
        </span>
      </p>
    </div>

    <div class="table-responsive">
      <table class="table table-hover table-bordered align-middle">
        <thead class="text-center">
          <tr>
            <th>#</th>
            <th>Nama Produk</th>
            <th>Harga</th>
            <th>Jumlah</th>
            <th>Subtotal</th>
          </tr>
        </thead>
        <tbody>
          <?php $no = 1; while ($item = mysqli_fetch_assoc($items)): ?>
          <tr class="text-center">
            <td><?= $no++ ?></td>
            <td><?= htmlspecialchars($item['nama_produk']) ?></td>
            <td>Rp<?= number_format($item['harga'], 0, ',', '.') ?></td>
            <td><?= $item['jumlah'] ?></td>
            <td>Rp<?= number_format($item['harga'] * $item['jumlah'], 0, ',', '.') ?></td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</main>

<?php include '../inc/footer.php'; ?>
</body>
</html>
