<?php
$page_title = 'Manajemen Kategori';
require_once '../config/koneksi.php';
require_once '../auth.php';
require_admin();

$kategori = mysqli_query($conn, "SELECT * FROM kategori ORDER BY id_kategori DESC");
?>

<?php include '../inc/header.php'; ?>

  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h4 class="text-gold"><i class="bi bi-folder2"></i> Manajemen Kategori</h4>
      <div>
        <a href="dashboard.php" class="btn btn-secondary btn-sm"><i class="bi bi-arrow-left"></i> Kembali</a>
        <a href="tambah_kategori.php" class="btn btn-gold btn-sm"><i class="bi bi-plus-circle"></i> Tambah Kategori</a>
      </div>
    </div>

    <div class="table-responsive bg-dark p-3 rounded shadow" style="background-color: rgba(0,0,0,0.4);">
      <table class="table table-dark table-bordered align-middle">
        <thead class="table-light text-dark">
          <tr>
            <th>No</th>
            <th>Nama Kategori</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php $no=1; while ($row = mysqli_fetch_assoc($kategori)): ?>
          <tr>
            <td><?= $no++ ?></td>
            <td><?= $row['nama_kategori'] ?></td>
            <td>
              <a href="edit_kategori.php?id=<?= $row['id_kategori'] ?>" class="btn btn-warning btn-sm"><i class="bi bi-pencil-square"></i></a>
              <a href="hapus_kategori.php?id=<?= $row['id_kategori'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus kategori ini?')"><i class="bi bi-trash"></i></a>
            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php include '../inc/footer.php'; ?>
