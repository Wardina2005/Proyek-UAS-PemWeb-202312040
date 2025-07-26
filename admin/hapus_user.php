<?php
require_once '../config/koneksi.php';
require_once '../auth.php';
require_admin();

if (!isset($_GET['id'])) {
  echo "⚠️ ID user tidak ditemukan!";
  exit;
}

$id = $_GET['id'];

// Ambil user berdasarkan ID
$user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM user WHERE id_user = $id"));

if (!$user) {
  echo "⚠️ User tidak ditemukan!";
  exit;
}

// Cegah hapus admin
if ($user['role'] === 'admin') {
  echo "<script>alert('❌ Tidak dapat menghapus akun admin!'); window.location='data_user.php';</script>";
  exit;
}

// Cek apakah user memiliki relasi dengan tabel lain
$check_transaksi = mysqli_query($conn, "SELECT COUNT(*) as count FROM transaksi WHERE id_user = $id");
$transaksi_count = mysqli_fetch_assoc($check_transaksi)['count'];

$check_keranjang = mysqli_query($conn, "SELECT COUNT(*) as count FROM keranjang WHERE id_user = $id");
$keranjang_count = mysqli_fetch_assoc($check_keranjang)['count'];

$check_aktivitas = mysqli_query($conn, "SELECT COUNT(*) as count FROM aktivitas_admin WHERE id_admin = $id");
$aktivitas_count = mysqli_fetch_assoc($check_aktivitas)['count'];

if ($transaksi_count > 0 || $keranjang_count > 0 || $aktivitas_count > 0) {
  $message = "❌ User tidak dapat dihapus karena masih memiliki:";
  if ($transaksi_count > 0) {
    $message .= "\\n- $transaksi_count transaksi";
  }
  if ($keranjang_count > 0) {
    $message .= "\\n- $keranjang_count item di keranjang";
  }
  if ($aktivitas_count > 0) {
    $message .= "\\n- $aktivitas_count log aktivitas";
  }
  $message .= "\\n\\nAlternatif: Nonaktifkan user dengan mengubah role atau gunakan fitur suspend.";
  
  echo "<script>alert('$message'); window.location='data_user.php';</script>";
} else {
  // Aman untuk dihapus
  if (mysqli_query($conn, "DELETE FROM user WHERE id_user = $id")) {
    echo "<script>alert('✅ User berhasil dihapus!'); window.location='data_user.php';</script>";
  } else {
    echo "<script>alert('❌ Gagal menghapus user: " . mysqli_error($conn) . "'); window.location='data_user.php';</script>";
  }
}
?>
