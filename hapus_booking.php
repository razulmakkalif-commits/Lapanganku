<?php

session_start();
require_once 'koneksi.php';

if (!isset($koneksi) || !$koneksi) {
    $koneksi = mysqli_connect("localhost", "root", "", "lapanganku");
}

$id = $_GET['id'];

mysqli_query(
    $koneksi,
    "UPDATE pemesanan
    SET status='Dibatalkan'
    WHERE id='$id'"
);

header("Location: riwayat.php");
