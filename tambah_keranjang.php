<?php

session_start();

require_once "config/koneksi.php";

$id = $_GET['id'] ?? 0;

$stmt = mysqli_prepare(
    $koneksi,
    "SELECT * FROM produk WHERE id = ?"
);

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$produk = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

if (!$produk) {
    die("Produk tidak ditemukan!");
}

if ($produk['jumlah'] <= 0) {
    die("Stok produk habis!");
}

if (!isset($_SESSION['keranjang'])) {
    $_SESSION['keranjang'] = [];
}

if (isset($_SESSION['keranjang'][$id])) {

    if ($_SESSION['keranjang'][$id] < $produk['jumlah']) {
        $_SESSION['keranjang'][$id]++;
    }

} else {

    $_SESSION['keranjang'][$id] = 1;

}

header("Location: keranjang.php");
exit;

?>