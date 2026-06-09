<?php

session_start();

include 'koneksi.php';

$user_id = $_SESSION['id'];

$data = mysqli_query(
    $koneksi,
    "SELECT
        pemesanan.*,
        lapangan.nama_lapangan
    FROM pemesanan
    JOIN lapangan
        ON pemesanan.lapangan_id = lapangan.id
    WHERE user_id='$user_id'
"
);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Riwayat Pemesanan</title>
</head>
<body>

<h2>Riwayat Pemesanan</h2>

<table border="1" cellpadding="10">

<tr>
    <th>No</th>
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

<a href="dashboard.php">
Kembali ke Dashboard
</a>

</body>
</html>