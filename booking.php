<?php
session_abort();
include 'koneksi.php';

$id_lapangan = $_GET['id'];

$data = mysqli_query(
    $koneksi,
    "SELECT * FROM lapangan
    WHERE id='$id_lapangan'"
);

$lapangan = mysqli_fetch_assoc($data);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Booking Lapangan</title>
</head>
<body>

<h2>Booking Lapangan</h2>

<p>
Nama Lapangan :
<b>
<?php echo $lapangan['nama_lapangan']; ?>
</b>
</p>

<form method="POST">

Tanggal Main
<br>
<input type="date" name="tanggal" required>

<br><br>

Jam Mulai
<br>
<input type="time" name="jam_mulai" required>

<br><br>

Jam Selesai
<br>
<input type="time" name="jam_selesai" required>

<br><br>

<button type="submit" name="simpan">
Simpan Booking
</button>

</form>

</body>
</html>