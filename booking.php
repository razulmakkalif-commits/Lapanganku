<?php

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
ahahahahahahahah
<h2>Booking Lapangan</h2>

<p>
Nama Lapangan :
<b>
<?php echo $lapangan['nama_lapangan']; ?>
</b>
</p>

</body>
</html>