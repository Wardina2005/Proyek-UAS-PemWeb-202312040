<?php
$page_title = 'Manajemen User';
require_once '../config/koneksi.php';
require_once '../auth.php';
require_admin();

$users = mysqli_query($conn, "SELECT * FROM user ORDER BY created_at DESC");
?>

<?php include '../inc/header.php'; ?>

<div class="container my-5 flex-grow-1">
  <div class="card shadow-lg">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h4 class="text-gold mb-0"><i class="bi bi-people-fill me-2"></i> Manajemen Pengguna</h4>
      <div>
        <a href="tambah_user.php" class="btn btn-gold me-2"><i class="bi bi-person-plus"></i> Tambah User</a>
        <a href="dashboard.php" class="btn btn-secondary"><i class="bi bi-house-door-fill"></i> Dashboard</a>
      </div>
    </div>

    <div class="table-responsive">
      <table class="table table-hover table-bordered align-middle shadow-sm rounded-3">
        <thead class="text-center">
          <tr>
            <th style="width: 5%;">No</th>
            <th>Nama Lengkap</th>
            <th>Username</th>
            <th>Role</th>
            <th>Tanggal Daftar</th>
            <th style="width: 15%;">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php $no = 1; while ($user = mysqli_fetch_assoc($users)): ?>
          <tr class="text-center">
            <td><?= $no++ ?></td>
            <td><?= htmlspecialchars($user['nama']) ?></td>
            <td>@<?= htmlspecialchars($user['username']) ?></td>
            <td>
              <?php if ($user['role'] == 'admin'): ?>
                <span class="badge bg-dark"><i class="bi bi-shield-lock-fill me-1"></i> Admin</span>
              <?php else: ?>
                <span class="badge bg-success"><i class="bi bi-person-fill me-1"></i> User</span>
              <?php endif; ?>
            </td>
            <td><?= date('d M Y', strtotime($user['created_at'])) ?></td>
            <td>
              <?php if ($user['username'] !== 'admin'): ?>
                <a href="edit_user.php?id=<?= $user['id_user'] ?>" class="btn btn-sm btn-warning me-1" title="Edit"><i class="bi bi-pencil-fill"></i></a>
                <a href="hapus_user.php?id=<?= $user['id_user'] ?>" class="btn btn-sm btn-danger" title="Hapus" onclick="return confirm('Yakin ingin menghapus user ini?')">
                  <i class="bi bi-trash-fill"></i>
                </a>
              <?php else: ?>
                <span class="text-muted">-</span>
              <?php endif; ?>
            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php include '../inc/footer.php'; ?>
