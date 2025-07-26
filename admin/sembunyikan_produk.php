<?php
require_once '../config/koneksi.php';
require_once '../auth.php';
require_admin();

if (isset($_GET['id'])) {
  $id = $_GET['id'];

  // Update stok menjadi 0 untuk menyembunyikan produk
  $update = mysqli_query($conn, "UPDATE produk SET stok = 0 WHERE id_produk = '$id'");

  if ($update) {
    echo "<script>alert('✅ Produk berhasil disembunyikan (stok = 0)!'); window.location='data_produk.php';</script>";
  } else {
    echo "<script>alert('❌ Gagal menyembunyikan produk: " . mysqli_error($conn) . "'); window.location='data_produk.php';</script>";
  }
} else {
  echo "<script>alert('ID tidak ditemukan!'); window.location='data_produk.php';</script>";
}
?>
