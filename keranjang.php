<?php

session_start();

require_once "config/koneksi.php";

if (!isset($_SESSION['keranjang'])) {
    $_SESSION['keranjang'] = [];
}

$keranjang = $_SESSION['keranjang'];

$total = 0; ?>
<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Keranjang - Nadip</title>

    <link rel="stylesheet" href="assets/css/style.css">

    <style>

        .cart-container {
            width: 86%;
            margin: 40px auto;
        }

        .cart-container h1 {
            margin-bottom: 25px;
        }

        .cart-item {
            background: white;
            padding: 20px;
            margin-bottom: 15px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .cart-image {
            width: 100px;
            height: 100px;
            flex-shrink: 0;
        }

        .cart-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 8px;
        }

        .cart-info {
            flex: 1;
        }

        .cart-info h3 {
            margin-bottom: 8px;
        }

        .cart-price {
            color: #ee4d2d;
            font-weight: bold;
        }

        .cart-total {
            background: white;
            padding: 25px;
            border-radius: 10px;
            text-align: right;
        }

        .cart-total h2 {
            color: #ee4d2d;
            margin-bottom: 15px;
        }

        .empty-cart {
            background: white;
            padding: 50px;
            text-align: center;
            border-radius: 10px;
        }

        .back-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 18px;
            background: #ee4d2d;
            color: white;
            text-decoration: none;
            border-radius: 6px;
        }

    </style>

</head>

<body>

<header class="navbar">

    <div class="logo">
        Nadip
    </div>

    <div class="search">

        <input
            type="text"
            placeholder="Cari produk di nadip..."
        >

        <button>
            🔍
        </button>

    </div>

    <div class="nav-menu">

        <a href="index.php">
            Beranda
        </a>

        <a href="produk.php">
            Produk
        </a>

        <a href="keranjang.php">
            🛒 Keranjang
        </a>

        <a href="admin/index.php">
            Admin
        </a>

    </div>

</header>

<main class="cart-container">

    <h1>
        🛒 Keranjang Belanja
    </h1>

    <?php if (empty($keranjang)) { ?>

        <div class="empty-cart">

            <h2>
                Keranjang masih kosong 😭
            </h2>

            <p>
                Yuk cari produk yang ingin kamu masukkan ke keranjang.
            </p>

            <a href="produk.php" class="back-btn">
                Lihat Produk
            </a>

        </div>

    <?php } else { ?>


        <?php foreach ($keranjang as $id => $jumlah) { ?>

            <?php

            $stmt = mysqli_prepare(
                $koneksi,
                "SELECT * FROM produk WHERE id = ?"
            );

            mysqli_stmt_bind_param(
                $stmt,
                "i",
                $id
            );

            mysqli_stmt_execute($stmt);

            $result = mysqli_stmt_get_result($stmt);
            $produk = mysqli_fetch_assoc($result);

            mysqli_stmt_close($stmt);

            if (!$produk) {
                continue;
            }

            $subtotal = $produk['harga'] * $jumlah;

            $total += $subtotal;

            ?>

            <div class="cart-item">

                <div class="cart-image">

                    <?php if (!empty($produk['gambar'])) { ?>

                        <img
                            src="assets/img/<?= htmlspecialchars($produk['gambar']); ?>"
                            alt="<?= htmlspecialchars($produk['nama_barang']); ?>"
                        >

                    <?php } ?>

                </div>

                <div class="cart-info">

                    <h3>
                        <?= htmlspecialchars($produk['nama_barang']); ?>
                    </h3>

                    <p class="cart-price">

                        Rp <?= number_format(
                            $produk['harga'],
                            0,
                            ',',
                            '.'
                        ); ?>

                    </p>

            <div class="quantity">

               <a
                    href="ubah_keranjang.php?id=<?= $id; ?>&aksi=kurang"
                    class="quantity-btn"
                >
                   −
               </a>

               <span>
                   <?= $jumlah; ?>
               </span>

               <a
                    href="ubah_keranjang.php?id=<?= $id; ?>&aksi=tambah"
                    class="quantity-btn"
                >
                    +
               </a>

            </div>

                <a
                    href="ubah_keranjang.php?id=<?= $id; ?>&aksi=hapus"
                    class="delete-cart"
                    onclick="return confirm('Hapus produk ini dari keranjang?');"
                >
                    🗑️ Hapus
                </a>

                    <p>
                        Subtotal:
                        <strong>
                            Rp <?= number_format(
                                $subtotal,
                                0,
                                ',',
                                '.'
                            ); ?>
                        </strong>
                    </p>

                </div>

            </div>

        <?php } ?>

        <div class="cart-total">

            <h2>
                Total:
                Rp <?= number_format(
                    $total,
                    0,
                    ',',
                    '.'
                ); ?>
            </h2>

            <a href="produk.php" class="back-btn">
                ← Lanjut Belanja
            </a>

            <a href="checkout.php" class="checkout-btn">
                 🛒 Checkout Sekarang
            </a>

        </div>

    <?php } ?>

</main>
<footer>

    <h3>Nadip</h3>

    <p>
        Marketplace sederhana untuk memenuhi kebutuhan belanja Anda.
    </p>

    <p>
        © 2026 Nadip
    </p>

</footer>

</body>

</html>