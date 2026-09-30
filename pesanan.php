<?php

require_once "config/koneksi.php";

$query = mysqli_query(
    $koneksi,
    "SELECT * FROM pesanan ORDER BY id DESC"
);

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Pesanan Saya - Nadip</title>

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
            min-height: 500px;
        }

        .page-header {
            margin-bottom: 25px;
        }

        .page-header h1 {
            margin-bottom: 8px;
        }

        .page-header p {
            color: #777;
        }

        .order-card {
            background: white;
            padding: 22px;
            margin-bottom: 20px;
            border-radius: 10px;
            box-shadow: 0 3px 12px rgba(0,0,0,0.08);
        }

        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #eee;
            padding-bottom: 15px;
            margin-bottom: 18px;
        }

        .order-number {
            font-weight: bold;
            color: #555;
        }

        .order-date {
            color: #777;
            font-size: 14px;
        }

        .order-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 20px;
        }

        .info-box {
            background: #fafafa;
            padding: 15px;
            border-radius: 8px;
        }

        .info-box h4 {
            margin-bottom: 7px;
            color: #555;
        }

        .info-box p {
            color: #666;
            line-height: 1.5;
        }

        .order-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid #eee;
            padding-top: 18px;
        }

        .total-label {
            color: #777;
            font-size: 14px;
        }

        .total {
            color: #ee4d2d;
            font-size: 20px;
            font-weight: bold;
            margin-top: 5px;
        }

        .status {
            padding: 9px 15px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 14px;
        }

        .status-diproses {
            background: #fff3cd;
            color: #856404;
        }

        .status-dikirim {
            background: #cfe2ff;
            color: #084298;
        }

        .status-selesai {
            background: #d1e7dd;
            color: #0f5132;
        }

        .status-dibatalkan {
            background: #f8d7da;
            color: #842029;
        }

        .empty {
            background: white;
            text-align: center;
            padding: 60px 20px;
            border-radius: 10px;
            box-shadow: 0 3px 12px rgba(0,0,0,0.08);
        }

        .empty-icon {
            font-size: 50px;
            margin-bottom: 15px;
        }

        .empty h2 {
            margin-bottom: 10px;
        }

        .empty p {
            color: #777;
            margin-bottom: 20px;
        }

        .shop-btn {
            display: inline-block;
            padding: 11px 20px;
            background: #ee4d2d;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
        }

        .shop-btn:hover {
            background: #d63f22;
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

            .order-info {
                grid-template-columns: 1fr;
            }

            .order-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
            }

            .order-footer {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
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

        <a href="index.php">
            🏠 Beranda
        </a>

        <a href="produk.php">
            🛍️ Produk
        </a>

        <a href="keranjang.php">
            🛒 Keranjang
        </a>

        <a href="pesanan.php">
            📦 Pesanan Saya
        </a>

    </nav>

</header>

<main class="container">

    <div class="page-header">

        <h1>📦 Pesanan Saya</h1>

        <p>
            Lihat informasi dan status pesanan Anda.
        </p>

    </div>


    <?php if (mysqli_num_rows($query) > 0): ?>

        <?php while ($pesanan = mysqli_fetch_assoc($query)): ?>

            <?php

            $status = $pesanan['status'];

            $status_class = 'status-diproses';

            if ($status == 'Dikirim') {
                $status_class = 'status-dikirim';
            } elseif ($status == 'Selesai') {
                $status_class = 'status-selesai';
            } elseif ($status == 'Dibatalkan') {
                $status_class = 'status-dibatalkan';
            }

            ?>


            <div class="order-card">

                <div class="order-header">

                    <div class="order-number">
                        📋 Pesanan #<?= $pesanan['id']; ?>
                    </div>

                    <div class="order-date">
                        <?= htmlspecialchars($pesanan['tanggal']); ?>
                    </div>

                </div>


                <div class="order-info">

                    <div class="info-box">

                        <h4>👤 Nama Pembeli</h4>

                        <p>
                            <?= htmlspecialchars($pesanan['nama']); ?>
                        </p>

                    </div>


                    <div class="info-box">

                        <h4>📱 Nomor HP</h4>

                        <p>
                            <?= htmlspecialchars($pesanan['no_hp']); ?>
                        </p>

                    </div>


                    <div class="info-box">

                        <h4>📍 Alamat Pengiriman</h4>

                        <p>
                            <?= nl2br(
                                htmlspecialchars($pesanan['alamat'])
                            ); ?>
                        </p>

                    </div>


                    <div class="info-box">

                        <h4>🚚 Status Pesanan</h4>

                        <p>

                            <span class="status <?= $status_class; ?>">
                                <?= htmlspecialchars($status); ?>
                            </span>

                        </p>

                    </div>

                </div>

                <div class="order-footer">

                    <div>

                        <div class="total-label">
                            Total Pesanan
                        </div>

                        <div class="total">

                            Rp <?= number_format(
                                $pesanan['total'],
                                0,
                                ',',
                                '.'
                            ); ?>

                        </div>

                    </div>

                </div>

            </div>


        <?php endwhile; ?>


    <?php else: ?>


        <div class="empty">

            <div class="empty-icon">
                📭
            </div>

            <h2>Belum Ada Pesanan</h2>

            <p>
                Kamu belum memiliki pesanan di Nadip.
            </p>

            <a href="produk.php" class="shop-btn">
                🛍️ Mulai Belanja
            </a>

        </div>

    <?php endif; ?>

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