<?php
require_once '../config/koneksi.php';
require_once '../auth.php';
require_admin();

// Buat PDF menggunakan HTML dengan CSS untuk print
$tanggal_sekarang = date('d F Y');

// Query data yang sama seperti di laporan
$laporan = mysqli_query($conn, "
  SELECT t.*, u.nama AS nama_user
  FROM transaksi t
  JOIN user u ON t.id_user = u.id_user
  WHERE t.status = 'selesai'
ORDER BY t.tanggal_transaksi DESC
");

$produk_terlaris = mysqli_query($conn, "
  SELECT 
    p.nama_produk,
    p.harga,
    k.nama_kategori,
    SUM(dt.jumlah) as total_terjual,
    SUM(dt.subtotal) as total_pendapatan
  FROM detail_transaksi dt
  JOIN produk p ON dt.id_produk = p.id_produk
  JOIN kategori k ON p.id_kategori = k.id_kategori
  JOIN transaksi t ON dt.id_transaksi = t.id_transaksi
  WHERE t.status = 'selesai'
  GROUP BY dt.id_produk, p.nama_produk, p.harga, k.nama_kategori
  ORDER BY total_terjual DESC
  LIMIT 10
");

$kategori_terlaris = mysqli_query($conn, "
  SELECT 
    k.nama_kategori,
    COUNT(DISTINCT dt.id_produk) as jenis_produk,
    SUM(dt.jumlah) as total_terjual,
    SUM(dt.subtotal) as total_pendapatan
  FROM detail_transaksi dt
  JOIN produk p ON dt.id_produk = p.id_produk
  JOIN kategori k ON p.id_kategori = k.id_kategori
  JOIN transaksi t ON dt.id_transaksi = t.id_transaksi
  WHERE t.status = 'selesai'
  GROUP BY k.id_kategori, k.nama_kategori
  ORDER BY total_terjual DESC
");

// Set header untuk HTML yang bisa dicetak
header('Content-Type: text/html; charset=UTF-8');

// Buat konten HTML untuk PDF
ob_start();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan HiiStyle</title>
    <style>
        @page {
            margin: 20mm;
            size: A4;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #d4af37;
            padding-bottom: 15px;
        }
        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #d4af37;
            margin: 0;
        }
        .report-title {
            font-size: 16px;
            color: #666;
            margin: 5px 0;
        }
        .date {
            font-size: 14px;
            color: #888;
            margin: 10px 0;
        }
        .section {
            margin: 20px 0;
        }
        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #d4af37;
            margin-bottom: 10px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
            font-size: 11px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #333;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .total-row {
            background-color: #e9ecef !important;
            font-weight: bold;
        }
        .tier-s { background-color: #ffc107; color: #000; }
        .tier-a { background-color: #28a745; color: #fff; }
        .tier-b { background-color: #17a2b8; color: #fff; }
        .perf-excellent { background-color: #28a745; color: #fff; }
        .perf-good { background-color: #007bff; color: #fff; }
        .perf-fair { background-color: #ffc107; color: #000; }
        .perf-poor { background-color: #6c757d; color: #fff; }
        .badge {
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
        }
        .summary {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1 class="company-name">HiiStyle Fashion Store</h1>
        <div class="report-title">Laporan Transaksi & Analisis Penjualan</div>
        <div class="date">Dicetak pada: <?= $tanggal_sekarang ?></div>
    </div>

    <!-- Laporan Transaksi -->
    <div class="section">
        <h2 class="section-title">🧾 Laporan Transaksi Selesai</h2>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Customer</th>
                    <th>Tanggal Transaksi</th>
                    <th>Total Pembayaran</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                $totalSemua = 0;
                mysqli_data_seek($laporan, 0);
                while ($row = mysqli_fetch_assoc($laporan)) :
                    $totalSemua += $row['total'];
                ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($row['nama_user']) ?></td>
                    <td><?= date('d M Y H:i', strtotime($row['tanggal_transaksi'])) ?></td>
                    <td>Rp<?= number_format($row['total'], 0, ',', '.') ?></td>
                    <td>Selesai</td>
                </tr>
                <?php endwhile; ?>
                <tr class="total-row">
                    <td colspan="3">TOTAL PENDAPATAN</td>
                    <td colspan="2">Rp<?= number_format($totalSemua, 0, ',', '.') ?></td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Produk Terlaris -->
    <div class="section">
        <h2 class="section-title">🏆 Top 10 Produk Terlaris</h2>
        <table>
            <thead>
                <tr>
                    <th>Rank</th>
                    <th>Nama Produk</th>
                    <th>Kategori</th>
                    <th>Harga Satuan</th>
                    <th>Terjual</th>
                    <th>Pendapatan</th>
                    <th>Tier</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $rank = 1;
                mysqli_data_seek($produk_terlaris, 0);
                while ($produk = mysqli_fetch_assoc($produk_terlaris)) :
                    $tier = '';
                    $tier_class = '';
                    if ($rank <= 3) {
                        $tier = 'S-Tier';
                        $tier_class = 'tier-s';
                    } elseif ($rank <= 6) {
                        $tier = 'A-Tier';
                        $tier_class = 'tier-a';
                    } else {
                        $tier = 'B-Tier';
                        $tier_class = 'tier-b';
                    }
                ?>
                <tr>
                    <td><?= $rank ?></td>
                    <td><?= htmlspecialchars($produk['nama_produk']) ?></td>
                    <td><?= htmlspecialchars($produk['nama_kategori']) ?></td>
                    <td>Rp<?= number_format($produk['harga'], 0, ',', '.') ?></td>
                    <td><?= $produk['total_terjual'] ?> unit</td>
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

    <!-- Kategori Terlaris -->
    <div class="section">
        <h2 class="section-title">📊 Kategori Terlaris</h2>
        <table>
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
                mysqli_data_seek($kategori_terlaris, 0);
                while ($kategori = mysqli_fetch_assoc($kategori_terlaris)) :
                    $performance = '';
                    $perf_class = '';
                    if ($kategori['total_terjual'] >= 50) {
                        $performance = 'Excellent';
                        $perf_class = 'perf-excellent';
                    } elseif ($kategori['total_terjual'] >= 20) {
                        $performance = 'Good';
                        $perf_class = 'perf-good';
                    } elseif ($kategori['total_terjual'] >= 10) {
                        $performance = 'Fair';
                        $perf_class = 'perf-fair';
                    } else {
                        $performance = 'Poor';
                        $perf_class = 'perf-poor';
                    }
                ?>
                <tr>
                    <td><?= $rank_kat ?></td>
                    <td><?= htmlspecialchars($kategori['nama_kategori']) ?></td>
                    <td><?= $kategori['jenis_produk'] ?> produk</td>
                    <td><?= $kategori['total_terjual'] ?> unit</td>
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

    <!-- Summary -->
    <div class="summary">
        <h3>📈 Ringkasan Laporan</h3>
        <p><strong>Total Pendapatan:</strong> Rp<?= number_format($totalSemua, 0, ',', '.') ?></p>
        <p><strong>Jumlah Transaksi Selesai:</strong> <?= mysqli_num_rows($laporan) ?> transaksi</p>
        <p><strong>Periode Laporan:</strong> Semua transaksi yang telah selesai</p>
        <p><strong>Dicetak pada:</strong> <?= date('d F Y, H:i') ?> WIB</p>
    </div>
</body>
</html>
<?php
$html = ob_get_clean();

// Output HTML dan auto print
echo $html;
echo '<script>
  window.onload = function() {
    window.print();
    setTimeout(function() {
      if (confirm("Tutup halaman ini?")) {
        window.close();
      }
    }, 1000);
  }
</script>';
?>
