<?php 
require_once '../config/koneksi.php';
require_once '../auth.php';
require_user();

$id_user = $_SESSION['id_user'];

// Tambah ke keranjang (dari detail_produk)
if (isset($_POST['tambah'])) {
    $id_produk = $_POST['id_produk'];
    $jumlah = $_POST['jumlah'];

    // Cek apakah produk sudah ada di keranjang
    $cek = mysqli_query($conn, "SELECT * FROM keranjang WHERE id_user='$id_user' AND id_produk='$id_produk'");
    if (mysqli_num_rows($cek) > 0) {
        mysqli_query($conn, "UPDATE keranjang SET jumlah = jumlah + $jumlah WHERE id_user='$id_user' AND id_produk='$id_produk'");
    } else {
        mysqli_query($conn, "INSERT INTO keranjang (id_user, id_produk, jumlah) VALUES ('$id_user', '$id_produk', '$jumlah')");
    }

    header("Location: keranjang.php");
    exit;
}

// Hapus produk dari keranjang
if (isset($_GET['hapus'])) {
    $id_keranjang = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM keranjang WHERE id_keranjang='$id_keranjang'");
    header('Location: keranjang.php');
    exit;
}

// Ambil data keranjang
$query = mysqli_query($conn, "SELECT k.*, p.nama_produk, p.harga, p.gambar 
    FROM keranjang k 
    JOIN produk p ON k.id_produk = p.id_produk 
    WHERE k.id_user = '$id_user'");

$total = 0;
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Keranjang Belanja - Hii Style</title>
  <link rel="stylesheet" href="../assets/css/style.css">
  <link rel="stylesheet" href="../assets/css/hamburger-user.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body {
      background: linear-gradient(135deg, #1C1C28 0%, #181823 100%);
      font-family: 'Segoe UI', sans-serif;
      color: #f5f5f5;
      min-height: 100vh;
      margin: 0;
      padding-top: 80px;
    }

    .keranjang-container {
      max-width: 1000px;
      margin: 20px auto;
      background: rgba(35, 35, 46, 0.9);
      backdrop-filter: blur(10px);
      padding: 30px;
      border: 1px solid rgba(102, 126, 234, 0.2);
      border-radius: 20px;
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
    }

    .keranjang-title {
      font-size: 2rem;
      font-weight: bold;
      color: #d4af37;
      margin-bottom: 25px;
      text-align: center;
    }

    .btn-kembali {
      display: inline-block;
      margin-bottom: 20px;
      padding: 10px 20px;
      border-radius: 25px;
      background-color: transparent;
      color: #ccc;
      border: 1px solid #ccc;
      text-decoration: none;
      font-size: 14px;
      transition: 0.3s;
    }

    .btn-kembali:hover {
      background-color: #333;
      color: #fff;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 25px;
    }

    th, td {
      padding: 14px;
      text-align: center;
      border: 1px solid #444;
    }

    th {
      background-color: #1e1e1e;
      color: #d4af37;
    }

    td {
      background-color: #121212;
      color: #f5f5f5;
    }

    td img {
      width: 60px;
      height: 60px;
      border-radius: 10px;
      object-fit: cover;
    }

    .total-row td {
      background-color: #f8f8f8;
      color: #121212;
      font-weight: bold;
    }

    .btn-hapus {
      background-color: #dc3545;
      border: none;
      color: #fff;
      padding: 8px 12px;
      border-radius: 8px;
      cursor: pointer;
      transition: background 0.3s ease;
    }

    .btn-hapus:hover {
      background-color: #c82333;
    }

    .checkout-btn {
      background-color: #d4af37;
      color: #121212;
      font-weight: bold;
      padding: 14px 30px;
      border: none;
      border-radius: 50px;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      box-shadow: 0 0 12px #d4af37;
      transition: all 0.3s ease-in-out;
      margin: auto;
    }

    .checkout-btn:hover {
      background-color: #fff0c2;
      transform: scale(1.05);
    }
  </style>
</head>
<body>

<div class="keranjang-container">
  <h2 class="keranjang-title"><i class="fas fa-cart-shopping glow-icon"></i> Keranjang Belanja</h2>

  <!-- Tombol kembali -->
  <a href="produk.php" class="btn-kembali"><i class="fas fa-arrow-left"></i> Kembali ke Produk</a>

  <table>
    <thead>
      <tr>
        <th>Produk</th>
        <th>Harga</th>
        <th>Jumlah</th>
        <th>Subtotal</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = mysqli_fetch_assoc($query)) :
        $subtotal = $row['harga'] * $row['jumlah'];
        $total += $subtotal;
      ?>
        <tr>
          <td>
            <div style="display:flex;align-items:center;gap:10px;">
              <img src="../assets/img/produk/<?= htmlspecialchars($row['gambar']) ?>" alt="<?= htmlspecialchars($row['nama_produk']) ?>">
              <div style="text-align:left;">
                <strong><?= htmlspecialchars($row['nama_produk']) ?></strong>
              </div>
            </div>
          </td>
          <td>Rp<?= number_format($row['harga'], 0, ',', '.') ?></td>
          <td><?= $row['jumlah'] ?></td>
          <td>Rp<?= number_format($subtotal, 0, ',', '.') ?></td>
          <td>
            <a href="?hapus=<?= $row['id_keranjang'] ?>" onclick="return confirm('Yakin ingin menghapus item ini?');">
              <button class="btn-hapus"><i class="fas fa-trash"></i></button>
            </a>
          </td>
        </tr>
      <?php endwhile; ?>
      <tr class="total-row">
        <td colspan="3" style="text-align:right;">Total:</td>
        <td colspan="2">Rp<?= number_format($total, 0, ',', '.') ?></td>
      </tr>
    </tbody>
  </table>

  <a href="checkout.php">
    <button class="checkout-btn"><i class="fas fa-credit-card"></i> Checkout Sekarang</button>
  </a>
</div>

  <script src="../assets/js/hamburger-user.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const userPanel = new HamburgerUserPanel();
    });
  </script>
</body>
</html>
