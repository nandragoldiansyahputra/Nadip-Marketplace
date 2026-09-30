<?php

session_start();

require_once "config/koneksi.php";


if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header("Location: index.php");
    exit;
}

$nama = trim($_POST['nama'] ?? '');
$no_hp = trim($_POST['no_hp'] ?? '');
$alamat = trim($_POST['alamat'] ?? '');


if ($nama == '' || $no_hp == '' || $alamat == '') {
    die("Data pembeli belum lengkap!");
}

if (
    !isset($_SESSION['keranjang']) ||
    empty($_SESSION['keranjang'])
) {
    header("Location: keranjang.php");
    exit;
}

$keranjang = $_SESSION['keranjang'];

$total = 0;

mysqli_begin_transaction($koneksi);

foreach ($keranjang as $id => $jumlah) {

    $stmt = mysqli_prepare(
        $koneksi,
        "SELECT harga, jumlah
         FROM produk
         WHERE id = ?
         FOR UPDATE"
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

        mysqli_rollback($koneksi);

        die("Produk tidak ditemukan!");

    }

    if ($produk['jumlah'] < $jumlah) {

        mysqli_rollback($koneksi);

        die(
            "Stok produk tidak mencukupi. " .
            "Stok tersedia: " .
            $produk['jumlah']
        );

    }

    $total += $produk['harga'] * $jumlah;


    $stmt = mysqli_prepare(
        $koneksi,
        "UPDATE produk
         SET jumlah = jumlah - ?
         WHERE id = ?"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "ii",
        $jumlah,
        $id
    );

    if (!mysqli_stmt_execute($stmt)) {

        mysqli_stmt_close($stmt);

        mysqli_rollback($koneksi);

        die(
            "Gagal mengurangi stok: " .
            mysqli_error($koneksi)
        );

    }

    mysqli_stmt_close($stmt);

}

$stmt = mysqli_prepare(
    $koneksi,
    "INSERT INTO pesanan
    (nama, no_hp, alamat, total)
    VALUES (?, ?, ?, ?)"
);


if (!$stmt) {

    mysqli_rollback($koneksi);

    die(
        "Gagal membuat query pesanan: " .
        mysqli_error($koneksi)
    );

}


mysqli_stmt_bind_param(
    $stmt,
    "sssi",
    $nama,
    $no_hp,
    $alamat,
    $total
);


if (!mysqli_stmt_execute($stmt)) {

    mysqli_stmt_close($stmt);

    mysqli_rollback($koneksi);

    die(
        "Gagal menyimpan pesanan: " .
        mysqli_error($koneksi)
    );

}


mysqli_stmt_close($stmt);

mysqli_commit($koneksi);

$_SESSION['keranjang'] = [];

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Pesanan Berhasil - Nadip</title>

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

        .success-container {
            width: 90%;
            max-width: 650px;
            margin: 80px auto;
            background: white;
            padding: 40px;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 3px 15px rgba(0,0,0,0.08);
        }

        .success-icon {
            font-size: 60px;
            margin-bottom: 15px;
        }

        h1 {
            color: #28a745;
            margin-bottom: 10px;
        }

        .message {
            color: #777;
            margin-bottom: 30px;
        }

        .order-info {
            background: #fafafa;
            padding: 20px;
            border-radius: 8px;
            text-align: left;
            margin-bottom: 25px;
        }

        .order-info p {
            margin: 10px 0;
            line-height: 1.5;
        }

        .total {
            color: #ee4d2d;
            font-size: 22px;
            font-weight: bold;
        }

        .buttons {
            display: flex;
            justify-content: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-block;
            padding: 12px 20px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
        }

        .btn-primary {
            background: #ee4d2d;
            color: white;
        }

        .btn-secondary {
            background: #eee;
            color: #333;
        }

        .btn:hover {
            opacity: 0.9;
        }

    </style>

</head>

<body>

<div class="success-container">

    <div class="success-icon">
        ✅
    </div>

    <h1>Pesanan Berhasil!</h1>

    <p class="message">
        Terima kasih, pesanan kamu berhasil dibuat.
    </p>


    <div class="order-info">

        <p>
            <strong>Nama:</strong>
            <?= htmlspecialchars($nama); ?>
        </p>

        <p>
            <strong>No. HP:</strong>
            <?= htmlspecialchars($no_hp); ?>
        </p>

        <p>
            <strong>Alamat:</strong><br>
            <?= nl2br(htmlspecialchars($alamat)); ?>
        </p>

        <p>
            <strong>Total:</strong><br>

            <span class="total">
                Rp <?= number_format(
                    $total,
                    0,
                    ',',
                    '.'
                ); ?>
            </span>

        </p>

    </div>


    <div class="buttons">

        <a
            href="pesanan.php"
            class="btn btn-primary"
        >
            📦 Lihat Pesanan
        </a>

        <a
            href="produk.php"
            class="btn btn-secondary"
        >
            🛍️ Belanja Lagi
        </a>

    </div>

</div>

</body>

</html>