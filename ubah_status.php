<?php

session_start();
include 'koneksi.php';

if(!isset($_SESSION['admin']))
{
    header("Location: admin_login.php");
    exit;
}

$id = $_GET['id'];
$status = $_GET['status'];

mysqli_query(
    $koneksi,
    "UPDATE pemesanan
    SET status='$status'
    WHERE id='$id'"
);

header("Location: data_pemesanan.php");

?>