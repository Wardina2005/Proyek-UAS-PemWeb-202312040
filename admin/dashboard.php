<?php
$page_title = 'Dashboard Admin';
require_once '../config/koneksi.php';
require_once '../auth.php';
require_admin();

// Ambil statistik dashboard
$stats = [
    'total_produk' => $db->get_count('produk'),
    'total_user' => $db->get_count('users', 'role = ?', ['user']),
    'total_transaksi' => $db->get_count('transaksi'),
    'total_pendapatan' => $db->get_row('SELECT SUM(total) as total FROM transaksi WHERE status = ?', ['selesai'])['total'] ?? 0
];

// Ambil transaksi terbaru
$transaksi_terbaru = $db->get_results('
    SELECT t.*, u.nama as nama_user 
    FROM transaksi t 
    JOIN user u ON t.id_user = u.id_user 
    ORDER BY t.tanggal_transaksi DESC 
    LIMIT 5
');
?>

<?php include '../inc/header.php'; ?>

<div class="container-fluid">
    <!-- Welcome Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-gradient-primary text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="bi bi-shield-check display-4"></i>
                        </div>
                        <div>
                            <h2 class="card-title mb-1">Selamat Datang, <?= htmlspecialchars($_SESSION['nama']) ?>!</h2>
                            <p class="card-text mb-0">Kelola toko fashion HiiStyle dengan mudah dari dashboard admin ini.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card stats-card bg-gradient-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stats-number"><?= number_format($stats['total_produk']) ?></div>
                            <div class="stats-label">Total Produk</div>
                        </div>
                        <div class="stats-icon">
                            <i class="bi bi-box"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card stats-card bg-gradient-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stats-number"><?= number_format($stats['total_user']) ?></div>
                            <div class="stats-label">Total User</div>
                        </div>
                        <div class="stats-icon">
                            <i class="bi bi-people"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card stats-card bg-gradient-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stats-number"><?= number_format($stats['total_transaksi']) ?></div>
                            <div class="stats-label">Total Transaksi</div>
                        </div>
                        <div class="stats-icon">
                            <i class="bi bi-receipt"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card stats-card bg-gradient-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stats-number"><?= format_rupiah($stats['total_pendapatan']) ?></div>
                            <div class="stats-label">Total Pendapatan</div>
                        </div>
                        <div class="stats-icon">
                            <i class="bi bi-currency-dollar"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-lightning me-2"></i>Aksi Cepat
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-3 col-md-6 mb-3">
                            <a href="data_produk.php" class="btn btn-primary w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3">
                                <i class="bi bi-plus-circle display-6 mb-2"></i>
                                <span>Tambah Produk</span>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-3">
                            <a href="data_user.php" class="btn btn-success w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3">
                                <i class="bi bi-person-plus display-6 mb-2"></i>
                                <span>Kelola User</span>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-3">
                            <a href="transaksi.php" class="btn btn-warning w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3">
                                <i class="bi bi-receipt-cutoff display-6 mb-2"></i>
                                <span>Lihat Transaksi</span>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-3">
                            <a href="laporan.php" class="btn btn-info w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3">
                                <i class="bi bi-bar-chart display-6 mb-2"></i>
                                <span>Laporan</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-clock-history me-2"></i>Transaksi Terbaru
                    </h5>
                    <a href="transaksi.php" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                </div>
                <div class="card-body">
                    <?php if (empty($transaksi_terbaru)): ?>
                        <div class="text-center py-4">
                            <i class="bi bi-inbox display-1 text-muted"></i>
                            <p class="text-muted mt-2">Belum ada transaksi</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Customer</th>
                                        <th>Tanggal</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($transaksi_terbaru as $transaksi): ?>
                                    <tr>
                                        <td>#<?= $transaksi['id_transaksi'] ?></td>
                                        <td><?= htmlspecialchars($transaksi['nama_user']) ?></td>
                                        <td><?= format_tanggal($transaksi['tanggal_transaksi']) ?></td>
                                        <td><?= format_rupiah($transaksi['total']) ?></td>
                                        <td>
                                            <?php
                                            $badge_class = match($transaksi['status']) {
                                                'pending' => 'bg-warning',
                                                'proses' => 'bg-info',
                                                'selesai' => 'bg-success',
                                                'dibatalkan' => 'bg-danger',
                                                default => 'bg-secondary'
                                            };
                                            ?>
                                            <span class="badge <?= $badge_class ?>"><?= ucfirst($transaksi['status']) ?></span>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../inc/footer.php'; ?>
