<?php
$page_title = 'Dashboard User';
require_once '../config/koneksi.php';
require_once '../auth.php';
require_user();

// Ambil statistik user
$user_id = $_SESSION['id_user'];
$stats = [
'total_transaksi' => $db->get_count('transaksi', 'id_user = ?', [$user_id]),
    'total_belanja' => $db->get_row('SELECT SUM(total) as total FROM transaksi WHERE id_user = ? AND status = ?', [$user_id, 'selesai'])['total'] ?? 0,
    'keranjang_items' => $db->get_count('keranjang', 'id_user = ?', [$user_id]),
    'transaksi_pending' => $db->get_count('transaksi', 'id_user = ? AND status = ?', [$user_id, 'pending'])
];

// Ambil produk terbaru
$produk_terbaru = $db->get_results('SELECT * FROM produk ORDER BY id_produk DESC LIMIT 6');

// Ambil transaksi terbaru user
$transaksi_terbaru = $db->get_results('
    SELECT * FROM transaksi 
WHERE id_user = ?
    ORDER BY tanggal_transaksi DESC 
    LIMIT 3
', [$user_id]);
?>

<?php include '../inc/header.php'; ?>

<div class="container-fluid">
    <!-- Welcome Hero Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-gradient-primary text-white">
                <div class="card-body py-5">
                    <div class="text-center">
                        <i class="bi bi-person-circle display-1 mb-3"></i>
                        <h1 class="display-4 fw-bold mb-3">Selamat Datang, <?= htmlspecialchars($_SESSION['nama']) ?>!</h1>
                        <p class="lead mb-4">Temukan koleksi fashion terbaru dan terbaik di HiiStyle</p>
                        <a href="produk.php" class="btn btn-warning btn-lg">
                            <i class="bi bi-bag-heart me-2"></i>Mulai Belanja
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- User Statistics -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card stats-card bg-gradient-info">
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
            <div class="card stats-card bg-gradient-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stats-number"><?= format_rupiah($stats['total_belanja']) ?></div>
                            <div class="stats-label">Total Belanja</div>
                        </div>
                        <div class="stats-icon">
                            <i class="bi bi-currency-dollar"></i>
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
                            <div class="stats-number"><?= number_format($stats['keranjang_items']) ?></div>
                            <div class="stats-label">Item di Keranjang</div>
                        </div>
                        <div class="stats-icon">
                            <i class="bi bi-cart"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card stats-card bg-gradient-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stats-number"><?= number_format($stats['transaksi_pending']) ?></div>
                            <div class="stats-label">Transaksi Pending</div>
                        </div>
                        <div class="stats-icon">
                            <i class="bi bi-clock"></i>
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
                        <i class="bi bi-lightning me-2"></i>Menu Utama
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-3 col-md-6 mb-3">
                            <a href="produk.php" class="btn btn-primary w-100 h-100 d-flex flex-column align-items-center justify-content-center p-4">
                                <i class="bi bi-bag display-4 mb-2"></i>
                                <span class="fw-bold">Lihat Produk</span>
                                <small class="text-light">Jelajahi koleksi fashion</small>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-3">
                            <a href="keranjang.php" class="btn btn-success w-100 h-100 d-flex flex-column align-items-center justify-content-center p-4">
                                <i class="bi bi-cart display-4 mb-2"></i>
                                <span class="fw-bold">Keranjang</span>
                                <small class="text-light"><?= $stats['keranjang_items'] ?> item menunggu</small>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-3">
                            <a href="riwayat_pembelian.php" class="btn btn-info w-100 h-100 d-flex flex-column align-items-center justify-content-center p-4">
                                <i class="bi bi-clock-history display-4 mb-2"></i>
                                <span class="fw-bold">Riwayat</span>
                                <small class="text-light">Lihat pembelian lama</small>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-3">
                            <a href="profil.php" class="btn btn-warning w-100 h-100 d-flex flex-column align-items-center justify-content-center p-4">
                                <i class="bi bi-person display-4 mb-2"></i>
                                <span class="fw-bold">Profil</span>
                                <small class="text-dark">Kelola akun Anda</small>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Latest Products -->
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-star me-2"></i>Produk Terbaru
                    </h5>
                    <a href="produk.php" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                </div>
                <div class="card-body">
                    <?php if (empty($produk_terbaru)): ?>
                        <div class="text-center py-4">
                            <i class="bi bi-box display-1 text-muted"></i>
                            <p class="text-muted mt-2">Belum ada produk tersedia</p>
                        </div>
                    <?php else: ?>
                        <div class="row">
                            <?php foreach (array_slice($produk_terbaru, 0, 3) as $produk): ?>
                            <div class="col-md-4 mb-3">
                                <div class="card product-card h-100">
                                    <img src="<?= htmlspecialchars($produk['gambar']) ?>" 
                                         class="card-img-top product-image" 
                                         alt="<?= htmlspecialchars($produk['nama_produk']) ?>"
                                         onerror="this.src='https://via.placeholder.com/300x200?text=Gambar+Tidak+Ditemukan';">
                                    <div class="card-body">
                                        <h6 class="card-title"><?= htmlspecialchars($produk['nama_produk']) ?></h6>
                                        <p class="product-price mb-2"><?= format_rupiah($produk['harga']) ?></p>
                                        <a href="produk.php" class="btn btn-sm btn-primary w-100">Lihat Detail</a>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-clock-history me-2"></i>Transaksi Terbaru
                    </h5>
                    <a href="riwayat_pembelian.php" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                </div>
                <div class="card-body">
                    <?php if (empty($transaksi_terbaru)): ?>
                        <div class="text-center py-4">
                            <i class="bi bi-receipt display-4 text-muted"></i>
                            <p class="text-muted mt-2">Belum ada transaksi</p>
                            <a href="produk.php" class="btn btn-sm btn-primary">Mulai Belanja</a>
                        </div>
                    <?php else: ?>
                        <?php foreach ($transaksi_terbaru as $transaksi): ?>
                        <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                            <div>
                                <div class="fw-bold">#<?= $transaksi['id_transaksi'] ?></div>
                                <small class="text-muted"><?= format_tanggal($transaksi['tanggal_transaksi']) ?></small>
                            </div>
                            <div class="text-end">
                                <div class="fw-bold"><?= format_rupiah($transaksi['total']) ?></div>
                                <?php
                                $badge_class = match($transaksi['status']) {
                                    'pending' => 'bg-warning',
                                    'proses' => 'bg-info',
                                    'selesai' => 'bg-success',
                                    'dibatalkan' => 'bg-danger',
                                    default => 'bg-secondary'
                                };
                                ?>
                                <span class="badge <?= $badge_class ?> small"><?= ucfirst($transaksi['status']) ?></span>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../inc/footer.php'; ?>
