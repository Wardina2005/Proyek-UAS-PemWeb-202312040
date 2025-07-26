<?php
require_once '../config/koneksi.php';
require_once '../auth.php';
require_admin();

if (!isset($_GET['id'])) {
    header("Location: kategori.php");
    exit;
}

$id = intval($_GET['id']);
$hapus = mysqli_query($conn, "DELETE FROM kategori WHERE id_kategori = $id");

if ($hapus) {
    header("Location: kategori.php?msg=sukses");
} else {
    header("Location: kategori.php?msg=gagal");
}
exit;
?>
