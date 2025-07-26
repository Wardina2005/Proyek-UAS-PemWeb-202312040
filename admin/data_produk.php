<?php
$page_title = 'Manajemen Produk';
require_once '../config/koneksi.php';
require_once '../auth.php';
require_admin();

$produk = mysqli_query($conn, "SELECT p.*, k.nama_kategori FROM produk p 
JOIN kategori k ON p.id_kategori = k.id_kategori ORDER BY p.id_produk DESC");
?>

<?php include '../inc/header.php'; ?>

  <!-- Konten -->
  <div class="container-fluid py-4 px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h3 class="dashboard-header"><i class="bi bi-bag"></i> Daftar Produk</h3>
      <a href="tambah_produk.php" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Tambah Produk
      </a>
    </div>

    <div class="table-responsive">
      <table class="table table-dark table-bordered table-striped table-hover align-middle rounded">
        <thead class="table-dark text-warning">
          <tr>
            <th>#</th>
            <th>Gambar</th>
            <th>Nama Produk</th>
            <th>Kategori</th>
            <th>Harga</th>
            <th>Stok</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php $no=1; while ($row = mysqli_fetch_assoc($produk)): ?>
          <tr>
            <td><?= $no++ ?></td>
            <td><img src="<?= $row['gambar'] ?>" style="height:80px;width:80px;object-fit:cover;border-radius:10px;" onerror="this.src='https://via.placeholder.com/80x80?text=No+Image';"></td>
            <td><?= $row['nama_produk'] ?></td>
            <td><?= $row['nama_kategori'] ?></td>
            <td>Rp<?= number_format($row['harga'], 0, ',', '.') ?></td>
            <td><?= $row['stok'] ?></td>
            <td>
              <div class="btn-group" role="group">
                <a href="edit_produk.php?id=<?= $row['id_produk'] ?>" class="btn btn-warning btn-sm" title="Edit Produk">
                  <i class="bi bi-pencil"></i>
                </a>
                <?php if ($row['stok'] > 0): ?>
                  <a href="sembunyikan_produk.php?id=<?= $row['id_produk'] ?>" class="btn btn-secondary btn-sm" 
                    onclick="return confirm('Sembunyikan produk ini dengan mengubah stok menjadi 0?')" title="Sembunyikan Produk">
                    <i class="bi bi-eye-slash"></i>
                  </a>
                <?php endif; ?>
                <a href="hapus_produk.php?id=<?= $row['id_produk'] ?>" class="btn btn-danger btn-sm" 
                  onclick="return confirm('Yakin ingin menghapus produk ini? Produk yang sudah ada dalam transaksi tidak dapat dihapus.')" title="Hapus Produk">
                  <i class="bi bi-trash"></i>
                </a>
              </div>
            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>

    <div class="mt-4">
      <a href="dashboard.php" class="btn btn-secondary"><i class="bi bi-arrow-left-circle"></i> Kembali ke Dashboard</a>
    </div>
  </div>
</div>

<?php include '../inc/footer.php'; ?>
