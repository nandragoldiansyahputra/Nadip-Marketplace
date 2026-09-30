<?php

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

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        <?= htmlspecialchars($produk['nama_barang']); ?> - Nadip
    </title>

    <link rel="stylesheet" href="assets/css/style.css">

    <style>

        .detail-container {
            width: 86%;
            margin: 40px auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            display: grid;
            grid-template-columns: 45% 55%;
            gap: 40px;
        }

        .detail-image {
            width: 100%;
            height: 420px;
            border-radius: 10px;
            overflow: hidden;
            background: #eee;
        }

        .detail-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .detail-no-image {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #777;
        }

        .detail-info h1 {
            font-size: 32px;
            margin-bottom: 20px;
        }

        .detail-price {
            color: #ee4d2d;
            font-size: 30px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .detail-info p {
            margin: 12px 0;
            line-height: 1.6;
        }

        .detail-description {
            margin-top: 25px;
        }

        .back-btn {
            display: inline-block;
            margin-top: 25px;
            padding: 12px 20px;
            background: #ee4d2d;
            color: white;
            text-decoration: none;
            border-radius: 6px;
        }

        @media (max-width: 768px) {

            .detail-container {
                grid-template-columns: 1fr;
            }

            .detail-image {
                height: 350px;
            }

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

            <a href="index.php">Beranda</a>

            <a href="produk.php"> 🛍️ Produk</a>

            <a href="admin/index.php">Admin</a>

        </div>

    </header>


    <main class="detail-container">

        <div class="detail-image">

            <?php if (!empty($produk['gambar'])) { ?>

                <img
                    src="assets/img/<?= htmlspecialchars($produk['gambar']); ?>"
                    alt="<?= htmlspecialchars($produk['nama_barang']); ?>"
                >

            <?php } else { ?>

                <div class="detail-no-image">
                    Tidak ada gambar
                </div>

            <?php } ?>

        </div>

        <div class="detail-info">

            <h1>
                <?= htmlspecialchars($produk['nama_barang']); ?>
            </h1>

            <div class="detail-price">

                Rp <?= number_format(
                    $produk['harga'],
                    0,
                    ',',
                    '.'
                ); ?>

            </div>

            <p>
                <strong>Kategori:</strong>
                <?= htmlspecialchars($produk['kategori']); ?>
            </p>

            <p>
                <strong>Stok:</strong>
                <?= htmlspecialchars($produk['jumlah']); ?>
                <?= htmlspecialchars($produk['satuan']); ?>
            </p>

            <p>
                <strong>Lokasi:</strong>
                <?= htmlspecialchars($produk['lokasi']); ?>
            </p>

            <div class="detail-description">

                <h3>
                    Deskripsi Produk
                </h3>

                <p>
                    <?= nl2br(
                        htmlspecialchars($produk['deskripsi'])
                    ); ?>
                </p>

            </div>

            <a
                href="index.php"
                class="back-btn"
            >
                ← Kembali ke Beranda
            </a>

        </div>

    </main>

    <footer>

        <h3>Nadip</h3>

        <p>
            Marketplace sederhana untuk memenuhi
            kebutuhan belanja Anda.
        </p>

        <p>
            © 2026 NadiP
        </p>

    </footer>

</body>

</html>