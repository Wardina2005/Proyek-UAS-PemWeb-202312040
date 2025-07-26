<?php
require_once '../config/koneksi.php';
require_once '../auth.php';
require_admin();

if (isset($_GET['id'])) {
  $id = $_GET['id'];

  // Cek apakah produk ada dalam detail_transaksi
  $check_detail = mysqli_query($conn, "SELECT COUNT(*) as count FROM detail_transaksi WHERE id_produk = '$id'");
  $detail_count = mysqli_fetch_assoc($check_detail)['count'];
  
  // Cek apakah produk ada dalam keranjang
  $check_keranjang = mysqli_query($conn, "SELECT COUNT(*) as count FROM keranjang WHERE id_produk = '$id'");
  $keranjang_count = mysqli_fetch_assoc($check_keranjang)['count'];

  if ($detail_count > 0 || $keranjang_count > 0) {
    // Jika ada transaksi atau keranjang yang menggunakan produk ini
    $message = "❌ Produk tidak dapat dihapus karena masih digunakan dalam:";
    if ($detail_count > 0) {
      $message .= "\\n- $detail_count transaksi";
    }
    if ($keranjang_count > 0) {
      $message .= "\\n- $keranjang_count keranjang belanja";
    }
    $message .= "\\n\\nAlternatif: Ubah stok menjadi 0 untuk menyembunyikan produk.";
    
    echo "<script>alert('$message'); window.location='data_produk.php';</script>";
  } else {
    // Aman untuk dihapus
    // Ambil data produk dulu untuk tahu nama file gambar
    $query = mysqli_query($conn, "SELECT gambar FROM produk WHERE id_produk = '$id'");
    $data = mysqli_fetch_assoc($query);

    // Hapus gambar dari folder jika ada
    if ($data && file_exists("../assets/img/produk/" . $data['gambar'])) {
      unlink("../assets/img/produk/" . $data['gambar']);
    }

    // Hapus data produk dari database
    $delete = mysqli_query($conn, "DELETE FROM produk WHERE id_produk = '$id'");

    if ($delete) {
      echo "<script>alert('✅ Produk berhasil dihapus!'); window.location='data_produk.php';</script>";
    } else {
      echo "<script>alert('❌ Gagal menghapus produk: " . mysqli_error($conn) . "'); window.location='data_produk.php';</script>";
    }
  }
} else {
  echo "<script>alert('ID tidak ditemukan!'); window.location='data_produk.php';</script>";
}
?>
