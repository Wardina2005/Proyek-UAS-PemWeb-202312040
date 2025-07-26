<?php
$page_title = "Laporan Transaksi";
require_once '../config/koneksi.php';
require_once '../auth.php';
require_admin();

// Ambil data laporan transaksi yang sudah selesai
$laporan = mysqli_query($conn, "
  SELECT t.id_transaksi, u.nama as nama_user, t.tanggal_transaksi, t.total, t.status
  FROM transaksi t
  JOIN user u ON t.id_user = u.id_user
  WHERE t.status = 'selesai'
  ORDER BY t.tanggal_transaksi DESC
");

// Ambil data produk terlaris
$produk_terlaris = mysqli_query($conn, "
  SELECT p.nama_produk, k.nama_kategori, p.harga, 
         SUM(dt.jumlah) as total_terjual,
         SUM(dt.jumlah * p.harga) as total_pendapatan
  FROM detail_transaksi dt
  JOIN produk p ON dt.id_produk = p.id_produk
  JOIN kategori k ON p.id_kategori = k.id_kategori
  JOIN transaksi t ON dt.id_transaksi = t.id_transaksi
  WHERE t.status = 'selesai'
  GROUP BY p.id_produk, p.nama_produk, k.nama_kategori, p.harga
  ORDER BY total_terjual DESC
  LIMIT 10
");

// Ambil data kategori terlaris
$kategori_terlaris = mysqli_query($conn, "
  SELECT k.nama_kategori, 
         SUM(dt.jumlah) as total_terjual,
         SUM(dt.jumlah * p.harga) as total_pendapatan
  FROM detail_transaksi dt
  JOIN produk p ON dt.id_produk = p.id_produk
  JOIN kategori k ON p.id_kategori = k.id_kategori
  JOIN transaksi t ON dt.id_transaksi = t.id_transaksi
  WHERE t.status = 'selesai'
  GROUP BY k.id_kategori, k.nama_kategori
  ORDER BY total_terjual DESC
");

include '../inc/header.php';
?>

<style>
    html, body {
      height: 100%;
      margin: 0;
      display: flex;
      flex-direction: column;
    }

    body {
      background: url('../assets/img/wallpaper.png') no-repeat center center fixed;
      background-size: cover;
      font-family: 'Segoe UI', sans-serif;
      color: #f5f5f5;
    }

    main {
      flex: 1;
    }

    .card {
      background-color: rgba(18, 18, 18, 0.92);
      border-radius: 12px;
      padding: 2rem;
      box-shadow: 0 0 20px rgba(0,0,0,0.3);
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

    .table tbody tr:hover {
      background-color: #1c1c1c;
    }

    .btn-secondary {
      background-color: #444;
      border: none;
      color: #f5f5f5;
    }

    .btn-secondary:hover {
      background-color: #333;
      color: #d4af37;
    }

    .text-gold {
      color: #d4af37;
      font-weight: 600;
    }

    .badge-selesai {
      background-color: #28a745;
    }

    .fw-bold {
      font-weight: 600;
    }
    
    @media print {
      .btn, .navbar, .print-hide {
        display: none !important;
      }
      
      .card {
        background: white !important;
        color: black !important;
        box-shadow: none !important;
        border: 1px solid #ddd !important;
      }
      
      .table tbody tr {
        background: white !important;
        color: black !important;
      }
      
      .text-gold {
        color: #333 !important;
      }
      
      body {
        background: white !important;
        color: black !important;
      }
    }
  </style>

<main class="container mt-5 mb-5">
  <div class="card shadow-lg">
    <div class="d-flex justify-content-between align-items-center mb-4 print-hide">
      <h4 class="text-gold mb-0"><i class="bi bi-clipboard-data me-2"></i> Laporan Transaksi Selesai</h4>
      <a href="cetak_laporan.php" target="_blank" class="btn btn-warning">
        <i class="bi bi-file-pdf me-1"></i> Cetak PDF
      </a>
    </div>
    <h4 class="text-gold mb-4 d-none d-print-block"><i class="bi bi-clipboard-data me-2"></i> Laporan Transaksi Selesai</h4>

    <div class="table-responsive">
      <table class="table table-bordered align-middle text-center">
        <thead>
          <tr>
            <th>#</th>
            <th>Nama User</th>
            <th>Tanggal</th>
            <th>Total</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $no = 1;
          $totalSemua = 0;
          while ($row = mysqli_fetch_assoc($laporan)) :
            $totalSemua += $row['total'];
          ?>
          <tr>
            <td><?= $no++ ?></td>
            <td><?= htmlspecialchars($row['nama_user']) ?></td>
            <td><?= date('d M Y H:i', strtotime($row['tanggal_transaksi'])) ?></td>
            <td>Rp<?= number_format($row['total'], 0, ',', '.') ?></td>
            <td><span class="badge badge-selesai">Selesai</span></td>
          </tr>
          <?php endwhile; ?>
          <tr class="fw-bold table-secondary">
            <td colspan="3" class="text-end">Total Pendapatan</td>
            <td colspan="2">Rp<?= number_format($totalSemua, 0, ',', '.') ?></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Produk Terlaris -->
  <div class="card shadow-lg mt-4">
    <h4 class="text-gold mb-4"><i class="bi bi-trophy me-2"></i> Top 10 Produk Terlaris</h4>
    
    <div class="table-responsive">
      <table class="table table-bordered align-middle text-center">
        <thead>
          <tr>
            <th>Rank</th>
            <th>Nama Produk</th>
            <th>Kategori</th>
            <th>Harga Satuan</th>
            <th>Total Terjual</th>
            <th>Total Pendapatan</th>
            <th>Tier</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $rank = 1;
          while ($produk = mysqli_fetch_assoc($produk_terlaris)) :
            // Tentukan tier berdasarkan ranking
            $tier = '';
            $tier_class = '';
            if ($rank <= 3) {
              $tier = 'S-Tier';
              $tier_class = 'bg-warning text-dark';
            } elseif ($rank <= 6) {
              $tier = 'A-Tier';
              $tier_class = 'bg-success';
            } else {
              $tier = 'B-Tier';
              $tier_class = 'bg-info';
            }
          ?>
          <tr>
            <td>
              <?php if ($rank == 1): ?>
                <i class="bi bi-trophy-fill text-warning"></i>
              <?php elseif ($rank == 2): ?>
                <i class="bi bi-award-fill text-secondary"></i>
              <?php elseif ($rank == 3): ?>
                <i class="bi bi-award text-danger"></i>
              <?php else: ?>
                <?= $rank ?>
              <?php endif; ?>
            </td>
            <td><?= htmlspecialchars($produk['nama_produk']) ?></td>
            <td><?= htmlspecialchars($produk['nama_kategori']) ?></td>
            <td>Rp<?= number_format($produk['harga'], 0, ',', '.') ?></td>
            <td><span class="fw-bold text-info"><?= $produk['total_terjual'] ?> unit</span></td>
            <td>Rp<?= number_format($produk['total_pendapatan'], 0, ',', '.') ?></td>
            <td><span class="badge <?= $tier_class ?>"><?= $tier ?></span></td>
          </tr>
          <?php
          $rank++;
          endwhile;
          ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Kategori Terlaris -->
  <div class="card shadow-lg mt-4">
    <h4 class="text-gold mb-4"><i class="bi bi-graph-up me-2"></i> Kategori Terlaris</h4>
    
    <div class="table-responsive">
      <table class="table table-bordered align-middle text-center">
        <thead>
          <tr>
            <th>Rank</th>
            <th>Kategori</th>
            <th>Jenis Produk</th>
            <th>Total Terjual</th>
            <th>Total Pendapatan</th>
            <th>Performance</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $rank_kat = 1;
          while ($kategori = mysqli_fetch_assoc($kategori_terlaris)) :
            // Tentukan performance berdasarkan total terjual
            $performance = '';
            $perf_class = '';
            if ($kategori['total_terjual'] >= 50) {
              $performance = 'Excellent';
              $perf_class = 'bg-success';
            } elseif ($kategori['total_terjual'] >= 20) {
              $performance = 'Good';
              $perf_class = 'bg-primary';
            } elseif ($kategori['total_terjual'] >= 10) {
              $performance = 'Fair';
              $perf_class = 'bg-warning text-dark';
            } else {
              $performance = 'Poor';
              $perf_class = 'bg-secondary';
            }
          ?>
          <tr>
            <td><?= $rank_kat ?></td>
            <td class="fw-bold"><?= htmlspecialchars($kategori['nama_kategori']) ?></td>
            <td><?= $kategori['jenis_produk'] ?> produk</td>
            <td><span class="fw-bold text-success"><?= $kategori['total_terjual'] ?> unit</span></td>
            <td>Rp<?= number_format($kategori['total_pendapatan'], 0, ',', '.') ?></td>
            <td><span class="badge <?= $perf_class ?>"><?= $performance ?></span></td>
          </tr>
          <?php
          $rank_kat++;
          endwhile;
          ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Tombol Kembali -->
  <div class="text-center mt-4">
    <a href="dashboard.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali ke Dashboard</a>
  </div>
</main>

<?php include '../inc/footer.php'; ?>
