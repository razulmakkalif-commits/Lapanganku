<?php

session_start();
include 'koneksi.php';

$id = $_GET['id'];

$nama_file = $_FILES['bukti']['name'];
$tmp_file = $_FILES['bukti']['tmp_name'];

move_uploaded_file(
    $tmp_file,
    "pembayaran/" . $nama_file
);

mysqli_query(
    $koneksi,
    "UPDATE pemesanan
    SET bukti='$nama_file'
    WHERE id='$id'"
);

header("Location: riwayat.php");

?>