<?php
$page_title = 'Daftar Transaksi';
require_once '../config/koneksi.php';
require_once '../auth.php';
require_admin();

$transaksi = mysqli_query($conn, "
  SELECT t.*, u.nama AS nama_user 
  FROM transaksi t
  JOIN user u ON t.id_user = u.id_user
  ORDER BY t.tanggal_transaksi DESC
");
?>

<?php include '../inc/header.php'; ?>

<main class="container">
  <div class="card-transaksi">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h4><i class="bi bi-receipt-cutoff me-2"></i> Daftar Transaksi</h4>
      <a href="dashboard.php" class="btn btn-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
    </div>

    <div class="table-responsive">
      <table class="table table-hover table-bordered align-middle shadow-sm rounded-3">
        <thead class="text-center">
          <tr>
            <th style="width: 5%;">#</th>
            <th>Nama User</th>
            <th>Tanggal</th>
            <th>Total</th>
            <th>Status</th>
            <th style="width: 12%;">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php $no = 1; while ($row = mysqli_fetch_assoc($transaksi)): ?>
          <tr class="text-center">
            <td><?= $no++ ?></td>
            <td><?= htmlspecialchars($row['nama_user']) ?></td>
            <td><?= date('d M Y H:i', strtotime($row['tanggal_transaksi'])) ?></td>
            <td>Rp<?= number_format($row['total'], 0, ',', '.') ?></td>
            <td>
              <span class="badge <?= $row['status'] == 'selesai' ? 'badge-selesai' : 'badge-pending' ?>">
                <?= ucfirst($row['status']) ?>
              </span>
            </td>
            <td>
              <a href="detail_transaksi.php?id=<?= $row['id_transaksi'] ?>" class="btn btn-sm btn-gold"><i class="bi bi-eye-fill"></i> Detail</a>
            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</main>

<?php include '../inc/footer.php'; ?>
