<?php

require_once "../config/koneksi.php";

$query = mysqli_query($koneksi, "SELECT * FROM produk");

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Admin Nadip</title>

    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            color: #333;
        }

        .navbar {
            background: white;
            min-height: 65px;
            padding: 0 7%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        .logo {
            font-size: 28px;
            font-weight: bold;
            color: #ee4d2d;
        }

        .nav-menu {
            display: flex;
            align-items: center;
            gap: 25px;
        }

        .nav-menu a {
            text-decoration: none;
            color: #333;
            font-weight: 500;
        }

        .nav-menu a:hover {
            color: #ee4d2d;
        }

        .container {
            width: 90%;
            margin: 35px auto;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .page-header h1 {
            color: #333;
        }

        .buttons {
            display: flex;
            gap: 10px;
            margin-bottom: 25px;
        }

        .btn {
            display: inline-block;
            padding: 11px 18px;
            background: #ee4d2d;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
        }

        .btn:hover {
            background: #d84324;
        }

        .btn-secondary {
            background: #ff9f43;
        }

        .btn-secondary:hover {
            background: #e88d32;
        }

        .table-container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            overflow-x: auto;
            box-shadow: 0 3px 12px rgba(0,0,0,0.08);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #ee4d2d;
            color: white;
            padding: 13px;
            text-align: left;
        }

        td {
            padding: 12px;
            border-bottom: 1px solid #eee;
        }

        tr:hover {
            background: #fafafa;
        }

        .product-image {
            width: 70px;
            height: 70px;
            object-fit: cover;
            border-radius: 6px;
        }

        .no-image {
            color: #888;
            font-size: 13px;
        }

        .edit {
            color: #1976d2;
            text-decoration: none;
            font-weight: bold;
        }

        .delete {
            color: #e53935;
            text-decoration: none;
            font-weight: bold;
        }

        footer {
            margin-top: 50px;
            background: #222;
            color: white;
            text-align: center;
            padding: 30px;
        }

        footer h3 {
            margin-bottom: 8px;
        }

        footer p {
            color: #bbb;
            margin-top: 8px;
            font-size: 14px;
        }

        @media (max-width: 768px) {

            .navbar {
                padding: 15px 5%;
                flex-direction: column;
                gap: 15px;
            }

            .nav-menu {
                flex-wrap: wrap;
                justify-content: center;
                gap: 15px;
            }

            .container {
                width: 94%;
            }

            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .buttons {
                flex-wrap: wrap;
            }

        }

    </style>

</head>

<body>

<header class="navbar">

    <div class="logo">
        Nadip
    </div>

    <nav class="nav-menu">

        <a href="../index.php">
            Beranda </a>

        <a href="../produk.php">
            🛍️ Produk </a>

        <a href="index.php">
            Admin </a>

        <a href="pesanan.php">
            📋 Pesanan </a>

    </nav>

</header>

<main class="container">

    <div class="page-header">

        <h1>Admin Nadip</h1>

    </div>

    <div class="buttons">

        <a href="tambah.php" class="btn">
            ➕ Tambah Produk </a>

        <a href="pesanan.php" class="btn btn-secondary">
            📦 Data Pesanan </a>

    </div>

    <h2 style="margin-bottom: 15px;">
        Data Produk
    </h2>

    <div class="table-container">

        <table>

            <tr>

                <th>No</th>
                <th>Gambar</th>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th>Kategori</th>
                <th>Harga</th>
                <th>Jumlah</th>
                <th>Satuan</th>
                <th>Lokasi</th>
                <th>Aksi</th>

            </tr>

            <?php

            $no = 1;

            while ($produk = mysqli_fetch_assoc($query)) {

            ?>

            <tr>

                <td>
                    <?= $no++; ?>
                </td>

                <td>

                    <?php if (!empty($produk['gambar'])) { ?>

                        <img
                            src="../assets/img/<?= htmlspecialchars($produk['gambar']); ?>"
                            class="product-image"
                        >

                    <?php } else { ?>

                        <span class="no-image">
                            Tidak ada gambar
                        </span>

                    <?php } ?>

                </td>

                <td>
                    <?= htmlspecialchars($produk['kode_barang']); ?>
                </td>

                <td>
                    <?= htmlspecialchars($produk['nama_barang']); ?>
                </td>

                <td>
                    <?= htmlspecialchars($produk['kategori']); ?>
                </td>

                <td>
                    Rp <?= number_format(
                        $produk['harga'],
                        0,
                        ',',
                        '.'
                    ); ?>
                </td>

                <td>
                    <?= $produk['jumlah']; ?>
                </td>

                <td>
                    <?= htmlspecialchars($produk['satuan']); ?>
                </td>

                <td>
                    <?= htmlspecialchars($produk['lokasi']); ?>
                </td>

                <td>

                    <a
                        href="edit.php?id=<?= $produk['id']; ?>"
                        class="edit"
                    >
                        ✏️ Edit
                    </a>

                    <br><br>

                    <a
                        href="hapus.php?id=<?= $produk['id']; ?>"
                        class="delete"
                        onclick="return confirm('Apakah kamu yakin ingin menghapus produk ini?');"
                    >
                        🗑️ Hapus
                    </a>

                </td>

            </tr>

            <?php } ?>

        </table>

    </div>

</main>

<footer>

    <h3>Nadip</h3>

    <p>
        Sistem Marketplace dan Manajemen Produk
    </p>

    <p>
        © 2026 Nadip. All Rights Reserved.
    </p>

</footer>

</body>

</html>