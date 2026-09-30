<?php

require_once "config/koneksi.php";

$query = mysqli_query($koneksi, "SELECT * FROM produk ORDER BY id DESC");

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Nadip - Marketplace</title>

    <link rel="stylesheet" href="assets/css/style.css">

</head>

<body>

    <header class="navbar">

        <div class="logo">
            Nadip
        </div>

        <div class="search">

        <form class="search"  action="produk.php"  method="GET">

            <input
                type="text"
                name="q"
                placeholder="Cari produk di nadip..."
            >

            <button type="submit">
                🔍
            </button>

        </div>

        <div class="nav-menu">

            <a href="index.php"> Beranda</a>

            <a href="produk.php"> 🛍️ Produk</a>

            <a href="admin/index.php"> Admin</a>

        </div>

    </header>



    <section class="hero">

        <div class="hero-text">

            <h1>
                Selamat Datang di Nadip
            </h1>

            <p>
                Temukan berbagai produk menarik dengan mudah,
                cepat, dan praktis.
            </p>

            <a href="produk.php" class="btn">
                Belanja Sekarang </a>

        </div>

    </section>



    <section class="container">

        <h2>
            Kategori Produk
        </h2>

        <div class="categories">

            <div class="category">
                👕
                <span>Fashion</span>
            </div>

            <div class="category">
                📱
                <span>Elektronik</span>
            </div>

            <div class="category">
                🏠
                <span>Perabotan Rumah</span>
            </div>

            <div class="category">
                🎒
                <span>Aksesoris</span>
            </div>

            <div class="category">
                🧸
                <span>Mainan</span>
            </div>

            <div class="category">
                🛠️
                <span>Mekanik</span>
            </div>

        </div>

    </section>


    <section class="container">

        <div class="section-title">

            <h2>
                Produk Terbaru
            </h2>

            <a href="produk.php">
                Lihat Semua →
            </a>

        </div>


        <div class="products">

            <?php while ($produk = mysqli_fetch_assoc($query)) { ?>

                <div class="product-card">

                    <div class="product-image">

                        <?php if (!empty($produk['gambar'])) { ?>

                            <img
                                src="assets/img/<?= htmlspecialchars($produk['gambar']); ?>"
                                alt="<?= htmlspecialchars($produk['nama_barang']); ?>"
                            >

                        <?php } else { ?>

                            <div class="no-image">
                                Tidak ada gambar
                            </div>

                        <?php } ?>

                    </div>


                    <div class="product-info">

                        <h3>
                            <?= htmlspecialchars($produk['nama_barang']); ?>
                        </h3>

                        <p class="price">
                            Rp <?= number_format($produk['harga'], 0, ',', '.'); ?>
                        </p>

                        <p class="stock">
                            Stok: <?= $produk['jumlah']; ?>
                            <?= htmlspecialchars($produk['satuan']); ?>
                        </p>

                        <a
                            href="detail.php?id=<?= $produk['id']; ?>"
                            class="detail-btn"
                        >
                            Lihat Detail
                        </a>

                    </div>

                </div>

            <?php } ?>

        </div>

    </section>


    <footer>

        <h3>Nadip</h3>

        <p>
            Marketplace sederhana untuk memenuhi
            kebutuhan belanja Anda.
        </p>

        <p>
            © 2026 Nadip
        </p>

    </footer>

</body>

</html>