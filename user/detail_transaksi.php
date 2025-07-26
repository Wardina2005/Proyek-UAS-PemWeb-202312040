<?php
require_once '../config/koneksi.php';
require_once '../auth.php';
require_user();

$id = $_GET['id'];

// Ambil data transaksi
$query = mysqli_query($conn, "SELECT * FROM transaksi WHERE id_transaksi = '$id'");
$data = mysqli_fetch_assoc($query);

// Ambil detail transaksi (produk)
$query_detail = mysqli_query($conn, "SELECT dt.*, p.nama_produk, p.harga 
                                     FROM detail_transaksi dt 
                                     JOIN produk p ON dt.id_produk = p.id_produk 
                                     WHERE dt.id_transaksi = '$id'");
$jumlah_detail = mysqli_num_rows($query_detail);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Transaksi - HiiStyle</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <div class="container">
        <div class="detail-box">
            <h2>Detail Transaksi</h2>
            <div class="info-box">
                <p><strong>Tanggal:</strong> <?= date('d M Y H:i', strtotime($data['tanggal_transaksi'])) ?></p>
                <p><strong>Status:</strong> <?= ucfirst($data['status']) ?></p>
                <p><strong>Total:</strong> Rp<?= number_format($data['total'], 0, ',', '.') ?></p>
            </div>

            <?php if ($jumlah_detail > 0): ?>
                <table class="tabel-produk">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Harga</th>
                            <th>Jumlah</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($query_detail)) : ?>
                        <tr>
                            <td><?= $row['nama_produk'] ?></td>
                            <td>Rp<?= number_format($row['harga'], 0, ',', '.') ?></td>
                            <td><?= $row['jumlah'] ?></td>
                            <td>Rp<?= number_format($row['subtotal'], 0, ',', '.') ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php endif; ?>

            <a href="riwayat_pembelian.php" class="btn-back">← Kembali ke Riwayat</a>
        </div>
    </div>
</body>
</html>
