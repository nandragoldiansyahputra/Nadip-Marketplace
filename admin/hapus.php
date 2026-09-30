<?php

require_once "../config/koneksi.php";

$id = $_GET['id'] ?? 0;

$stmt = mysqli_prepare(
    $koneksi,
    "DELETE FROM produk WHERE id = ?"
);

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

header("Location: index.php");
exit;

?>