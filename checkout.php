<?php

session_start();

require_once "config/koneksi.php";

if (!isset($_SESSION['keranjang']) || empty($_SESSION['keranjang'])) {
    header("Location: keranjang.php");
    exit;
}

$keranjang = $_SESSION['keranjang'];

$total = 0;

foreach ($keranjang as $id => $jumlah) {

    $stmt = mysqli_prepare(
        $koneksi,
        "SELECT harga FROM produk WHERE id = ?"
    );

    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $produk = mysqli_fetch_assoc($result);

    mysqli_stmt_close($stmt);

    if ($produk) {
        $total += $produk['harga'] * $jumlah;
    }
}

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Checkout - Nadip</title>

    <link rel="stylesheet" href="assets/css/style.css">

    <style>
    .checkout-container {
        width: 86%;
        max-width: 900px;
        margin: 40px auto;
        background: white;
        padding: 35px;
        border-radius: 12px;
        box-shadow: 0 3px 12px rgba(0,0,0,0.08);
    }

    .checkout-container h1 {
        font-size: 30px;
        margin-bottom: 10px;
    }

    .checkout-container > p {
        color: #777;
        margin-bottom: 25px;
    }

    .checkout-form {
        width: 100%;
    }

    .checkout-form label {
        display: block;
        margin-top: 18px;
        margin-bottom: 8px;
        font-weight: bold;
    }

    .checkout-form input,
    .checkout-form textarea {
        display: block;
        width: 100%;
        padding: 13px;
        border: 1px solid #ddd;
        border-radius: 7px;
        font-size: 14px;
        font-family: Arial, sans-serif;
        box-sizing: border-box;
    }

    .checkout-form textarea {
        min-height: 120px;
        resize: vertical;
    }

    .checkout-total {
        margin-top: 25px;
        padding: 18px;
        background: #fff3ef;
        border-radius: 8px;
    }

    .checkout-total h2 {
        color: #ee4d2d;
    }

    .checkout-submit {
        width: 100%;
        margin-top: 20px;
        padding: 14px;
        border: none;
        border-radius: 7px;
        background: #ee4d2d;
        color: white;
        font-size: 16px;
        font-weight: bold;
        cursor: pointer;
    }

    .checkout-container .back-btn {
        display: inline-block;
        margin-top: 18px;
        color: #ee4d2d;
        text-decoration: none;
    }

    .navbar {
        width: 100%;
        min-height: 65px;
        padding: 0 7%;
        display: flex;
        align-items: center;
        box-sizing: border-box;
    }

    .nav-menu {
        display: flex;
        align-items: center;
        gap: 18px;
    }

    .nav-menu a {
        display: flex;
        align-items: center;
        text-decoration: none;
        color: #333;
        white-space: nowrap;
    }

    @media (max-width: 768px) {
    .checkout-container {
        width: 90%;
        padding: 25px;
        box-sizing: border-box;
    }
}
</style>

</head>

<body>

<header class="navbar">

    <div class="logo">
        Nadip
    </div>

    <div class="nav-menu">

        <a href="index.php">Beranda</a>

        <a href="produk.php">Produk</a>

        <a href="keranjang.php">🛒 Keranjang</a>

        <a href="admin/index.php">Admin</a>

    </div>

</header>


<main class="checkout-container">

    <h1>Checkout</h1>

    <p>Silakan isi data pembeli sebelum menyelesaikan pesanan.</p>


    <form method="POST" action="proses_pesanan.php" class="checkout-form">

        <label>
            Nama Lengkap
        </label>

        <input
            type="text"
            name="nama"
            placeholder="Masukkan nama lengkap"
            required
        >


        <label>
            Nomor HP
        </label>

        <input
            type="text"
            name="no_hp"
            placeholder="Masukkan nomor HP"
            required
        >


        <label>
            Alamat Pengiriman
        </label>

        <textarea
            name="alamat"
            placeholder="Masukkan alamat lengkap"
            rows="5"
            required
        ></textarea>


        <div class="checkout-total">

            <h2>
                Total Pesanan:
                Rp <?= number_format($total, 0, ',', '.'); ?>
            </h2>

        </div>


        <button type="submit" class="checkout-submit">
            ✅ Buat Pesanan
        </button>

    </form>


    <a href="keranjang.php" class="back-btn">
        ← Kembali ke Keranjang
    </a>

</main>

</body>

</html>