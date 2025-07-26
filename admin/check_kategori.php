<?php
require_once '../config/koneksi.php';

echo "<h3>Cek Data Kategori</h3>";

// Cek data kategori yang ada
$result = mysqli_query($conn, "SELECT * FROM kategori");

if (mysqli_num_rows($result) == 0) {
    echo "<p>Tidak ada data kategori. Menambahkan data kategori...</p>";
    
    // Insert data kategori default
    $kategori_data = [
        'Pakaian Pria',
        'Pakaian Wanita', 
        'Aksesoris',
        'Sepatu',
        'Tas'
    ];
    
    foreach ($kategori_data as $nama_kategori) {
        $query = "INSERT INTO kategori (nama_kategori) VALUES ('$nama_kategori')";
        if (mysqli_query($conn, $query)) {
            echo "✅ Kategori '$nama_kategori' berhasil ditambahkan.<br>";
        } else {
            echo "❌ Gagal menambahkan kategori '$nama_kategori'.<br>";
        }
    }
} else {
    echo "<p>Data kategori sudah ada:</p>";
    echo "<ul>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<li>ID: {$row['id_kategori']} - {$row['nama_kategori']}</li>";
    }
    echo "</ul>";
}

echo "<br><a href='tambah_produk.php'>Kembali ke Tambah Produk</a>";
?>
