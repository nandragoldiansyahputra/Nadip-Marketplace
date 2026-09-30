<?php

require_once "../config/koneksi.php";

if (isset($_POST['simpan'])) {

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

        $gambar = "";

        if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {

            $namaFile = $_FILES['gambar']['name'];
            $tmpFile = $_FILES['gambar']['tmp_name'];

            $ekstensi = strtolower(
                pathinfo($namaFile, PATHINFO_EXTENSION)
            );

            $ekstensiValid = ['jpg', 'jpeg', 'png', 'webp'];

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
            "INSERT INTO produk
            (kode_barang, nama_barang, kategori, harga, jumlah, satuan, lokasi, deskripsi, gambar)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "sssiissss",
            $kode,
            $nama,
            $kategori,
            $harga,
            $jumlah,
            $satuan,
            $lokasi,
            $deskripsi,
            $gambar
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

    <title>Tambah Produk - Nadip</title>

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
            Beranda
        </a>

        <a href="../produk.php">
            🛍️ Produk
        </a>

        <a href="index.php">
            Admin
        </a>

        <a href="pesanan.php">
            📋 Pesanan
        </a>

    </nav>

</header>

<main class="container">

    <div class="form-card">

        <h1>➕ Tambah Produk</h1>

        <p> Masukkan informasi produk yang ingin ditambahkan ke nadip. </p>


        <?php if (isset($pesan)) { ?>

            <div class="pesan">
                <?= htmlspecialchars($pesan); ?>
            </div>

        <?php } ?>


        <form method="POST" enctype="multipart/form-data">

            <div class="form-group">

                <label>Kode Barang</label>

                <input
                    type="text"
                    name="kode_barang"
                    placeholder="Contoh: BRG001"
                >

            </div>

            <div class="form-group">

                <label>Nama Barang</label>

                <input
                    type="text"
                    name="nama_barang"
                    placeholder="Contoh: Sepatu Sport"
                >

            </div>

            <div class="form-group">

                <label>Kategori</label>

                <select name="kategori">

                    <option value="">
                        -- Pilih Kategori --
                    </option>

                    <option value="Fashion">
                        Fashion
                    </option>

                    <option value="Elektronik">
                        Elektronik
                    </option>

                    <option value="Perabotan Rumah">
                        Perabotan Rumah
                    </option>

                    <option value="Aksesoris">
                        Aksesoris
                    </option>

                    <option value="Mainan">
                        Mainan
                    </option>

                    <option value="Mekanik">
                        Mekanik
                    </option>

                </select>

            </div>

            <div class="form-group">

                <label>Harga</label>

                <input
                    type="number"
                    name="harga"
                    placeholder="Contoh: 150000"
                    min="0"
                >

            </div>

            <div class="form-group">

                <label>Jumlah</label>

                <input
                    type="number"
                    name="jumlah"
                    placeholder="Contoh: 10"
                    min="0"
                >

            </div>

            <div class="form-group">

                <label>Satuan</label>

                <select name="satuan">

                    <option value="">
                        -- Pilih Satuan --
                    </option>

                    <option value="pcs">
                        pcs
                    </option>

                    <option value="unit">
                        unit
                    </option>

                    <option value="buah">
                        buah
                    </option>

                </select>

            </div>

            <div class="form-group">

                <label>Lokasi Penyimpanan</label>

                <input
                    type="text"
                    name="lokasi"
                    placeholder="Contoh: Rak A1"
                >

            </div>

            <div class="form-group">

                <label>Deskripsi</label>

                <textarea
                    name="deskripsi"
                    rows="5"
                    placeholder="Deskripsi produk..."
                ></textarea>

            </div>

            <div class="form-group">

                <label>Gambar Produk</label>

                <input
                    type="file"
                    name="gambar"
                    accept="image/*"
                >

            </div>

            <div class="form-actions">

                <button
                    type="submit"
                    name="simpan"
                    class="btn btn-save"
                >
                    Simpan Produk
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

    <p>
        Sistem Marketplace dan Manajemen Produk </p>

    <p>
        © 2026 Nadip. All Rights Reserved. </p>

</footer>

</body>

</html>