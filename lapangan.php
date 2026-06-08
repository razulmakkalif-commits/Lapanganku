<?php

include 'koneksi.php';

$data = mysqli_query(
    $koneksi,
    "SELECT * FROM lapangan"
);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Daftar Lapangan</title>
</head>
<body>

<h2>Daftar Lapangan</h2>

<table border="1" cellpadding="10">

<tr>
    <th>No</th>
    <th>Nama Lapangan</th>
    <th>Harga</th>
    <th>Aksi</th>
</tr>

<?php

$no = 1;

while($row = mysqli_fetch_assoc($data))
{
?>

<tr>

    <td><?php echo $no; ?></td>

    <td>
        <?php echo $row['nama_lapangan']; ?>
    </td>

    <td>
        Rp <?php echo number_format($row['harga']); ?>
    </td>

    <td>

<a href="booking.php?id=<?php echo $row['id']; ?>">

Booking

</a>

</td>
</tr>

<?php

$no++;

}

?>

</table>

</body>
</html>