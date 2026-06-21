<?php

session_start();
include 'koneksi.php';

if(!isset($_SESSION['admin']))
{
    header("Location: admin_login.php");
    exit;
}

$data = mysqli_query(
    $koneksi,
    "SELECT
        pemesanan.*,
        users.nama,
        lapangan.nama_lapangan
    FROM pemesanan
    JOIN users
        ON pemesanan.user_id = users.id
    JOIN lapangan
        ON pemesanan.lapangan_id = lapangan.id"
);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Pemesanan</title>
</head>
<body>

<h2>Data Pemesanan</h2>

<table border="1" cellpadding="10">

<tr>
    <th>No</th>
    <th>Nama User</th>
    <th>Lapangan</th>
    <th>Tanggal</th>
    <th>Jam Mulai</th>
    <th>Jam Selesai</th>
    <th>Status</th>
</tr>

<?php

$no = 1;

while($row = mysqli_fetch_assoc($data))
{

?>

<tr>

<td><?php echo $no; ?></td>

<td><?php echo $row['nama']; ?></td>

<td><?php echo $row['nama_lapangan']; ?></td>

<td><?php echo $row['tanggal']; ?></td>

<td><?php echo $row['jam_mulai']; ?></td>

<td><?php echo $row['jam_selesai']; ?></td>

<td><?php echo $row['status']; ?></td>

</tr>

<?php

$no++;

}

?>

</table>

<br>

<a href="admin_dashboard.php">
Kembali ke Dashboard Admin
</a>

</body>
</html> 