<?php
session_start();

$jumlahKeranjang = 0;
if (isset($_SESSION['keranjang'])) {
    $jumlahKeranjang = array_sum($_SESSION['keranjang']);
}

require_once "config/koneksi.php";

$keyword = $_GET['q'] ?? '';

$keyword = trim($keyword);

if ($keyword != '') {

    $stmt = mysqli_prepare(
        $koneksi,
        "SELECT * FROM produk
         WHERE nama_barang LIKE ?
         OR kategori LIKE ?
         OR deskripsi LIKE ?
         ORDER BY id DESC"
    );

    $cari = "%" . $keyword . "%";

    mysqli_stmt_bind_param(
        $stmt,
        "sss",
        $cari,
        $cari,
        $cari
    );

    mysqli_stmt_execute($stmt);

    $query = mysqli_stmt_get_result($stmt);

} else {

    $query = mysqli_query(
        $koneksi,
        "SELECT * FROM produk ORDER BY id DESC"
    );

}

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Semua Produk - Nadip</title>

    <link rel="stylesheet" href="assets/css/style.css">

    <style>

        .catalog {
            width: 86%;
            margin: 40px auto;
        }

        .catalog-header {
            margin-bottom: 25px;
        }

        .catalog-header h1 {
            margin-bottom: 8px;
        }

        .catalog-header p {
            color: #777;
        }

        .product-card {
            position: relative;
        }

    </style>

</head>

<body>

    <header class="navbar">

        <div class="logo">
            Nadip
        </div>

        <div class="search"  method="GET">

            <input
                type="text"
                name="q"
                placeholder="Cari produk di NadiP..."
                value="<?= htmlspecialchars($keyword); ?>"
            >

            <button>
                🔍
            </button>

        </div>

        <div class="nav-menu">

            <a href="index.php"> Beranda </a>
            <a href="produk.php"> 🛍️ Produk </a>
            <a href="keranjang.php"> 🛒 Keranjang  (<?= $jumlahKeranjang; ?>)</a>
            <a href="admin/index.php"> Admin </a>

        </div>

    </header>

    <main class="catalog">

        <div class="catalog-header">

    <h1>
       <?php if ($keyword != '') { ?>

           Hasil pencarian: "<?= htmlspecialchars($keyword); ?>"

       <?php } else { ?>

           Semua Produk

       <?php } ?>
    </h1>

    <p>
       <?php if ($keyword != '') { ?>

           Produk yang relevan dengan pencarian kamu.

       <?php } else { ?>

           Temukan berbagai produk yang tersedia di Nadip.

       <?php } ?>
    </p>
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
                            <?= htmlspecialchars(
                                $produk['nama_barang']
                            ); ?>
                        </h3>

                        <p class="price">

                            Rp <?= number_format(
                                $produk['harga'],
                                0,
                                ',',
                                '.'
                            ); ?>

                        </p>

                        <p class="stock">

                            Stok:
                            <?= htmlspecialchars($produk['jumlah']); ?>
                            <?= htmlspecialchars($produk['satuan']); ?>

                        </p>

                        <a
                            href="detail.php?id=<?= $produk['id']; ?>"
                            class="detail-btn"
                        >
                            Lihat Detail
                        </a>

                        <a
                        href="tambah_keranjang.php?id=<?=  $produk['id']; ?>"
                        class="cart-btn"
                        >
                           🛒 Tambah ke Keranjang

                        </a>

                    </div>

                </div>

            <?php } ?>

        </div>

    </main>


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