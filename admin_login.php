<?php

session_start();
include 'koneksi.php';

if(isset($_POST['login']))
{
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = mysqli_query(
        $koneksi,
        "SELECT * FROM admin
        WHERE username='$username'
        AND password='$password'"
    );

    if(mysqli_num_rows($query) > 0)
    {
        $_SESSION['admin'] = $username;

        header("Location: admin_dashboard.php");
        exit;
    }
    else
    {
        echo "Login Admin Gagal";
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Admin</title>
</head>
<body>

<h2>Login Admin</h2>

<form method="POST">

Username
<br>
<input type="text" name="username" required>

<br><br>

Password
<br>
<input type="password" name="password" required>

<br><br>

<button type="submit" name="login">
Login
</button>

</form>

</body>
</html>