<?php

session_start();
include 'koneksi.php';

$id = $_GET['id'];

mysqli_query(
    $koneksi,
    "UPDATE pemesanan
    SET status='Dibatalkan'
    WHERE id='$id'"
);

header("Location: riwayat.php");

?>
