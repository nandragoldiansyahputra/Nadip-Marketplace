<?php

require_once "../config/koneksi.php";

if (isset($_POST['update_status'])) {

    $id = $_POST['id'] ?? 0;
    $status = $_POST['status'] ?? '';

    $status_valid = [
        'Diproses',
        'Dikirim',
        'Selesai',
        'Dibatalkan'
    ];

    if (
        in_array($status, $status_valid) &&
        is_numeric($id)
    ) {

        $stmt = mysqli_prepare(
            $koneksi,
            "UPDATE pesanan SET status = ? WHERE id = ?"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "si",
            $status,
            $id
        );

        mysqli_stmt_execute($stmt);

        mysqli_stmt_close($stmt);
    }

    header("Location: pesanan.php");
    exit;
}

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

    <title>Data Pesanan - Admin nadip</title>

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
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .back-btn {
            text-decoration: none;
            color: #ee4d2d;
            font-weight: bold;
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
            padding: 13px;
            border-bottom: 1px solid #eee;
            vertical-align: middle;
        }

        tr:hover {
            background: #fafafa;
        }

        .total {
            color: #ee4d2d;
            font-weight: bold;
        }


        .status-form {
            display: flex;
            gap: 6px;
            align-items: center;
        }

        .status-select {
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 6px;
            background: white;
            cursor: pointer;
        }

        .status-btn {
            padding: 8px 12px;
            border: none;
            border-radius: 6px;
            background: #ee4d2d;
            color: white;
            cursor: pointer;
            font-weight: bold;
        }

        .status-btn:hover {
            background: #d63f22;
        }

        .empty {
            text-align: center;
            padding: 40px;
            color: #777;
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
                gap: 12px;
            }

            .status-form {
                flex-direction: column;
                align-items: flex-start;
            }

        }

    </style>

</head>

<body>

<header class="navbar">

    <div class="logo">
        nadip
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

    <div class="page-header">

        <h1>📦 Data Pesanan</h1>

        <a href="index.php" class="back-btn">
            ← Kembali ke Admin
        </a>

    </div>


    <div class="table-container">

        <?php if (mysqli_num_rows($query) > 0): ?>

            <table>

                <thead>

                    <tr>

                        <th>No</th>
                        <th>Nama</th>
                        <th>No. HP</th>
                        <th>Alamat</th>
                        <th>Total</th>
                        <th>Tanggal</th>
                        <th>Status</th>

                    </tr>

                </thead>


                <tbody>

                    <?php $no = 1; ?>

                    <?php while ($pesanan = mysqli_fetch_assoc($query)): ?>

                        <tr>

                            <td>
                                <?= $no++; ?>
                            </td>

                            <td>
                                <?= htmlspecialchars(
                                    $pesanan['nama']
                                ); ?>
                            </td>

                            <td>
                                <?= htmlspecialchars(
                                    $pesanan['no_hp']
                                ); ?>
                            </td>

                            <td>
                                <?= nl2br(
                                    htmlspecialchars(
                                        $pesanan['alamat']
                                    )
                                ); ?>
                            </td>

                            <td class="total">

                                Rp <?= number_format(
                                    $pesanan['total'],
                                    0,
                                    ',',
                                    '.'
                                ); ?>

                            </td>

                            <td>

                                <?= htmlspecialchars(
                                    $pesanan['tanggal']
                                ); ?>

                            </td>


                            <td>

                                <form
                                    method="POST"
                                    class="status-form"
                                >

                                    <input
                                        type="hidden"
                                        name="id"
                                        value="<?= $pesanan['id']; ?>"
                                    >

                                    <select
                                        name="status"
                                        class="status-select"
                                    >

                                        <option
                                            value="Diproses"
                                            <?= $pesanan['status'] == 'Diproses' ? 'selected' : ''; ?>
                                        >
                                            Diproses
                                        </option>

                                        <option
                                            value="Dikirim"
                                            <?= $pesanan['status'] == 'Dikirim' ? 'selected' : ''; ?>
                                        >
                                            Dikirim
                                        </option>

                                        <option
                                            value="Selesai"
                                            <?= $pesanan['status'] == 'Selesai' ? 'selected' : ''; ?>
                                        >
                                            Selesai
                                        </option>

                                        <option
                                            value="Dibatalkan"
                                            <?= $pesanan['status'] == 'Dibatalkan' ? 'selected' : ''; ?>
                                        >
                                            Dibatalkan
                                        </option>

                                    </select>

                                    <button
                                        type="submit"
                                        name="update_status"
                                        class="status-btn"
                                    >
                                        Update
                                    </button>

                                </form>

                            </td> </tr>

                    <?php endwhile; ?>

                </tbody>

            </table>

        <?php else: ?>

            <div class="empty">
                📭 Belum ada pesanan. </div>

        <?php endif; ?>

    </div>

</main>

<footer>

    <h3>Nadip</h3>

    <p>
        Sistem Marketplace dan Manajemen Produk
    </p>

    <p>
        © 2026 Nadip. All Rights Reserved. </p>

</footer>

</body>

</html>