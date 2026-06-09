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

if(isset($_POST['simpan'])){

    $user_id = $_SESSION['id'];

    $tanggal = $_POST['tanggal'];

    $jam_mulai = $_POST['jam_mulai'];

    $jam_selesai = $_POST['jam_selesai'];

    mysqli_query(
        $koneksi,
        "INSERT INTO pemesanan
        (
            user_id,
            lapangan_id,
            tanggal,
            jam_mulai,
            jam_selesai,
            status
        )
        VALUES
        (
            '$user_id',
            '$id_lapangan',
            '$tanggal',
            '$jam_mulai',
            '$jam_selesai',
            'Menunggu'
        )"
    );

    echo "<h3>Booking Berhasil Disimpan</h3>";
}

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