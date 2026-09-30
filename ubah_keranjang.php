<?php

session_start();

require_once "config/koneksi.php";

$id = $_GET['id'] ?? 0;
$aksi = $_GET['aksi'] ?? '';

if (!isset($_SESSION['keranjang'][$id])) {
    header("Location: keranjang.php");
    exit;
}

if ($aksi == 'tambah') {

    $stmt = mysqli_prepare(
        $koneksi,
        "SELECT jumlah FROM produk WHERE id = ?"
    );

    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $produk = mysqli_fetch_assoc($result);

    mysqli_stmt_close($stmt);

    if (
        $produk &&
        $_SESSION['keranjang'][$id] < $produk['jumlah']
    ) {
        $_SESSION['keranjang'][$id]++;
    }

} elseif ($aksi == 'kurang') {

    $_SESSION['keranjang'][$id]--;

    if ($_SESSION['keranjang'][$id] <= 0) {
        unset($_SESSION['keranjang'][$id]);
    }

} elseif ($aksi == 'hapus') {

    unset($_SESSION['keranjang'][$id]);
}

header("Location: keranjang.php");
exit;

?>