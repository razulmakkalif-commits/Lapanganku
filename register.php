<?php
include 'koneksi.php';

if(isset($_POST['daftar'])){

    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    mysqli_query(
        $koneksi,
        "INSERT INTO users(nama,email,password)
        VALUES('$nama','$email','$password')"
    );

    echo "Pendaftaran Berhasil";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
</head>
<body>

<h2>Daftar Akun</h2>

<form method="POST">

    Nama <br>
    <input type="text" name="nama" required>
    <br><br>

    Email <br>
    <input type="email" name="email" required>
    <br><br>

    Password <br>
    <input type="password" name="password" required>
    <br><br>

    <button type="submit" name="daftar">
        Daftar
    </button>

</form>

</body>
</html>