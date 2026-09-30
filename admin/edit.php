<?php

require_once "../config/koneksi.php";

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

if (isset($_POST['update'])) {

    $kode = $_POST['kode_barang'];
    $nama = $_POST['nama_barang'];
    $kategori = $_POST['kategori'];
    $harga = $_POST['harga'];
    $jumlah = $_POST['jumlah'];
    $satuan = $_POST['satuan'];
    $lokasi = $_POST['lokasi'];
    $deskripsi = $_POST['deskripsi'];

    if (
        empty($kode) ||
        empty($nama) ||
        empty($kategori) ||
        empty($harga) ||
        empty($jumlah) ||
        empty($satuan) ||
        empty($lokasi)
    ) {

        $pesan = "Semua data wajib diisi!";

    } else {

        $gambar = $produk['gambar'];

        if (
            isset($_FILES['gambar']) &&
            $_FILES['gambar']['error'] == 0
        ) {

            $namaFile = $_FILES['gambar']['name'];
            $tmpFile = $_FILES['gambar']['tmp_name'];

            $ekstensi = strtolower(
                pathinfo($namaFile, PATHINFO_EXTENSION)
            );

            $ekstensiValid = [
                'jpg',
                'jpeg',
                'png',
                'webp'
            ];

            if (in_array($ekstensi, $ekstensiValid)) {

                $namaBaru = uniqid() . "." . $ekstensi;

                $tujuan = "../assets/img/" . $namaBaru;

                if (move_uploaded_file($tmpFile, $tujuan)) {
                    $gambar = $namaBaru;
                }
            }
        }

        $stmt = mysqli_prepare(
            $koneksi,
            "UPDATE produk SET
            kode_barang = ?,
            nama_barang = ?,
            kategori = ?,
            harga = ?,
            jumlah = ?,
            satuan = ?,
            lokasi = ?,
            deskripsi = ?,
            gambar = ?
            WHERE id = ?"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "sssiissssi",
            $kode,
            $nama,
            $kategori,
            $harga,
            $jumlah,
            $satuan,
            $lokasi,
            $deskripsi,
            $gambar,
            $id
        );

        mysqli_stmt_execute($stmt);

        mysqli_stmt_close($stmt);

        header("Location: index.php");
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="id">
<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Produk - Nadip</title>

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
            max-width: 900px;
            margin: 40px auto;
        }

        .form-card {
            background: white;
            padding: 35px;
            border-radius: 12px;
            box-shadow: 0 3px 12px rgba(0,0,0,0.08);
        }

        .form-card h1 {
            margin-bottom: 8px;
        }

        .form-card > p {
            color: #777;
            margin-bottom: 25px;
        }

        .pesan {
            background: #ffe8e3;
            color: #d84324;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 7px;
            font-family: Arial, sans-serif;
            font-size: 14px;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #ee4d2d;
        }

        .form-group textarea {
            resize: vertical;
        }

        .form-group input[type="file"] {
            padding: 10px;
            background: #fafafa;
        }

        .current-image {
            margin-bottom: 12px;
        }

        .current-image img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #ddd;
        }

        .image-info {
            color: #777;
            font-size: 13px;
            margin-top: 5px;
        }

        .form-actions {
            display: flex;
            gap: 10px;
            margin-top: 25px;
        }

        .btn {
            display: inline-block;
            padding: 12px 20px;
            border: none;
            border-radius: 7px;
            text-decoration: none;
            font-weight: bold;
            cursor: pointer;
            font-size: 14px;
        }

        .btn-save {
            background: #ee4d2d;
            color: white;
        }

        .btn-save:hover {
            background: #d84324;
        }

        .btn-back {
            background: #eee;
            color: #333;
        }

        .btn-back:hover {
            background: #ddd;
        }

        footer {
            margin-top: 60px;
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

            .form-card {
                padding: 25px;
            }

            .form-actions {
                flex-direction: column;
            }

            .btn {
                text-align: center;
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
            🏠 Beranda
        </a>

        <a href="../produk.php">
            🛍️ Produk
        </a>

        <a href="index.php">
            📦 Admin
        </a>

        <a href="pesanan.php">
            📋 Pesanan
        </a>

    </nav>

</header>

<main class="container">

    <div class="form-card">

        <h1>✏️ Edit Produk</h1>

        <p> Ubah informasi produk yang ingin diperbarui. </p>

        <?php if (isset($pesan)) { ?>

            <div class="pesan">
                <?= htmlspecialchars($pesan); ?>
            </div>

        <?php } ?>

        <form
            method="POST"
            enctype="multipart/form-data"
        >

            <div class="form-group">

                <label>Kode Barang</label>

                <input
                    type="text"
                    name="kode_barang"
                    value="<?= htmlspecialchars($produk['kode_barang']); ?>"
                >

            </div>

            <div class="form-group">

                <label>Nama Barang</label>

                <input
                    type="text"
                    name="nama_barang"
                    value="<?= htmlspecialchars($produk['nama_barang']); ?>"
                >

            </div>

            <div class="form-group">

                <label>Kategori</label>

                <input
                    type="text"
                    name="kategori"
                    value="<?= htmlspecialchars($produk['kategori']); ?>"
                >

            </div>

            <div class="form-group">

                <label>Harga</label>

                <input
                    type="number"
                    name="harga"
                    value="<?= $produk['harga']; ?>"
                    min="0"
                >
            </div>

            <div class="form-group">

                <label>Jumlah</label>

                <input
                    type="number"
                    name="jumlah"
                    value="<?= $produk['jumlah']; ?>"
                    min="0"
                >
            </div>

            <div class="form-group">

                <label>Satuan</label>

                <select name="satuan">

                    <option value="pcs"
                        <?= $produk['satuan'] == 'pcs' ? 'selected' : ''; ?>>
                        pcs
                    </option>

                    <option value="unit"
                        <?= $produk['satuan'] == 'unit' ? 'selected' : ''; ?>>
                        unit
                    </option>

                    <option value="buah"
                        <?= $produk['satuan'] == 'buah' ? 'selected' : ''; ?>>
                        buah
                    </option>

                </select>

            </div>

            <div class="form-group">

                <label>Lokasi Penyimpanan</label>

                <input
                    type="text"
                    name="lokasi"
                    value="<?= htmlspecialchars($produk['lokasi']); ?>"
                >

            </div>

            <div class="form-group">

                <label>Deskripsi</label>

                <textarea
                    name="deskripsi"
                    rows="5"
                ><?= htmlspecialchars($produk['deskripsi']); ?></textarea>

            </div>

            <div class="form-group">

                <label>Gambar Produk</label>

                <?php if (!empty($produk['gambar'])) { ?>

                    <div class="current-image">

                        <img
                            src="../assets/img/<?= htmlspecialchars($produk['gambar']); ?>"
                            alt="Gambar Produk"
                        >

                        <div class="image-info">
                            Gambar saat ini
                        </div>

                    </div>

                <?php } else { ?>

                    <p class="image-info">
                        Produk belum memiliki gambar. </p>

                <?php } ?>

                <input
                    type="file"
                    name="gambar"
                    accept="image/*"
                >

                <p class="image-info">
                    Kosongkan jika tidak ingin mengganti gambar. </p>

            </div>

            <div class="form-actions">

                <button
                    type="submit"
                    name="update"
                    class="btn btn-save"
                >
                    Simpan Perubahan
                </button>

                <a
                    href="index.php"
                    class="btn btn-back"
                >
                    ← Kembali
                </a>

            </div>

        </form>

    </div>

</main>
 
<footer>

    <h3>Nadip</h3>

    <p> Sistem Marketplace dan Manajemen Produk </p>

    <p> © 2026 Nadip. All Rights Reserved. </p>

</footer>

</body>

</html>