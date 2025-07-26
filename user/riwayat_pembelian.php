<?php
require_once '../config/koneksi.php';
require_once '../auth.php';
require_user();

$id_user = $_SESSION['id_user'];

$transaksi = mysqli_query($conn, "
  SELECT * FROM transaksi 
  WHERE id_user = '$id_user' 
  ORDER BY tanggal_transaksi DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Riwayat Pembelian - HiiStyle</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/style.css">
  <style>
    html, body {
      height: 100%;
      margin: 0;
    }

    body {
      background: url('../assets/img/wallpaper.png') no-repeat center center fixed;
      background-size: cover;
      font-family: 'Segoe UI', sans-serif;
      color: #f5f5f5;
      display: flex;
      flex-direction: column;
    }

    main {
      flex: 1;
      display: flex;
      justify-content: center;
      align-items: start;
      padding-top: 50px;
      padding-bottom: 60px;
    }

    .riwayat-wrapper {
      width: 100%;
      max-width: 1100px;
      background-color: rgba(0,0,0,0.85);
      padding: 40px;
      border: 2px solid #d4af37;
      border-radius: 20px;
      box-shadow: 0 0 25px rgba(212, 175, 55, 0.3);
    }

    .top-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
    }

    .riwayat-title {
      font-size: 1.8rem;
      font-weight: bold;
      color: #d4af37;
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

    table {
      width: 100%;
      border-collapse: collapse;
      color: #f5f5f5;
    }

    th, td {
      padding: 12px;
      text-align: center;
      border: 1px solid #333;
    }

    thead {
      background-color: #1e1e1e;
      color: #d4af37;
    }

    tbody tr {
      background-color: #121212;
    }

    tbody tr:hover {
      background-color: #1c1c1c;
    }

    .badge-status {
      padding: 5px 12px;
      border-radius: 20px;
      font-weight: bold;
      font-size: 0.9rem;
    }

    .badge-pending {
      background-color: #ffc107;
      color: #212529;
    }

    .badge-selesai {
      background-color: #198754;
    }

    .alert-custom {
      background-color: #1e1e1e;
      color: #f5f5f5;
      border-left: 4px solid #d4af37;
      padding: 15px;
      border-radius: 10px;
      text-align: center;
    }

    footer {
      background-color: #1a1a1a;
      color: #ccc;
      padding: 15px;
      text-align: center;
      border-top: 1px solid #2c2c2c;
    }
  </style>
</head>
<body>

<?php include '../inc/header.php'; ?>

<main>
  <div class="riwayat-wrapper">
    <div class="top-row">
      <a href="dashboard.php" class="btn-kembali">← Kembali</a>
      <div class="riwayat-title">Riwayat Pembelian</div>
      <div style="width: 100px;"></div>
    </div>

    <?php if (mysqli_num_rows($transaksi) == 0): ?>
      <div class="alert-custom">Kamu belum melakukan pembelian apa pun.</div>
    <?php else: ?>
      <div class="table-responsive">
        <table>
          <thead>
            <tr>
              <th>No</th>
              <th>Tanggal</th>
              <th>Total</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
          <?php $no = 1; while ($row = mysqli_fetch_assoc($transaksi)): ?>
            <tr>
              <td><?= $no++ ?></td>
              <td><?= date('d M Y H:i', strtotime($row['tanggal_transaksi'])) ?></td>
              <td>Rp<?= number_format($row['total'], 0, ',', '.') ?></td>
              <td>
                <span class="badge-status <?= $row['status'] == 'selesai' ? 'badge-selesai' : 'badge-pending' ?>">
                  <?= ucfirst($row['status']) ?>
                </span>
              </td>
            </tr>
          <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>
</main>

<?php include '../inc/footer.php'; ?>
</body>
</html>
