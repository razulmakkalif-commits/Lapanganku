<?php

session_start();

if(!isset($_SESSION['admin']))
{
    header("Location: admin_login.php");
    exit;
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Admin</title>
</head>
<body>

<h2>Dashboard Admin</h2>

<h3>
Selamat datang,
<?php echo $_SESSION['admin']; ?>
</h3>

<a href="data_pemesanan.php">
Lihat Data Pemesanan
</a>

<br><br>

<a href="logout.php">
Logout
</a>

</body>
</html>