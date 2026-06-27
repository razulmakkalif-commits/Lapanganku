<?php

session_start();
include 'koneksi.php';

// Proteksi halaman: Pastikan hanya admin yang bisa mengakses script ini
if(!isset($_SESSION['admin']))
{
    header("Location: admin_login.php");
    exit;
}

// Pastikan data ID dan Status ada di URL sebelum dieksekusi
if(isset($_GET['id']) && isset($_GET['status']))
{
    $id = $_GET['id'];
    $status = $_GET['status'];

    /** @var mysqli $koneksi */ // Menghilangkan garis merah pengganggu di text editor
    
    // Menggunakan Prepared Statement agar aman dari SQL Injection
    $stmt = mysqli_prepare($koneksi, "UPDATE pemesanan SET status = ? WHERE id = ?");
    
    if($stmt) {
        // "si" berarti parameter pertama berbentuk string (status), kedua berbentuk integer (id)
        mysqli_stmt_bind_param($stmt, "si", $status, $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
}

// Kembalikan admin ke halaman data pemesanan setelah status berhasil diubah
header("Location: data_pemesanan.php");
exit;

?>