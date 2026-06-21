<?php
session_start();

if(!isset($_SESSION['id'])){
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body>

<h1>Selamat Datang</h1>

<h2>
<?php echo $_SESSION['nama']; ?>
</h2>

<a href="lapangan.php">
Lihat Lapangan
</a>

<br><br>

<a href="riwayat.php">
Riwayat Pemesanan
</a>

<br><br>

<a href="logout.php">
Logout
</a>

</body>
</html>