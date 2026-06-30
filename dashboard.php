<?php
session_start();
include 'koneksi.php';
// Ensure $koneksi is defined (fallback if koneksi.php didn't set it)
if (!isset($koneksi) || !$koneksi) {
    // adjust these credentials if your database uses different values
    $koneksi = mysqli_connect('localhost', 'root', '', 'lapanganku');
    if (!$koneksi) {
        die('Database connection failed: ' . mysqli_connect_error());
    }
}

$admin = mysqli_query(
    $koneksi,
    "SELECT * FROM admin LIMIT 1"
);

$data_admin = mysqli_fetch_assoc($admin);

if(!isset($_SESSION['id'])){
    header("Location: login.php");
    exit;
}

// Ambil data admin untuk link WA
$query_admin = mysqli_query($koneksi, "SELECT no_hp FROM admin LIMIT 1");
$data_admin = mysqli_fetch_assoc($query_admin);
$no_hp = ($data_admin && isset($data_admin['no_hp'])) ? $data_admin['no_hp'] : '#';

// Ambil data user untuk foto profil
$id_user = $_SESSION['id'];
$query_user = mysqli_query($koneksi, "SELECT foto FROM users WHERE id='$id_user'");
$data_user = mysqli_fetch_assoc($query_user);
$foto_user = (!empty($data_user['foto'])) ? $data_user['foto'] : 'default.jpg';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - LapanganKu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(rgba(0,0,0,0.65), rgba(0,0,0,0.65)),
            url('https://images.unsplash.com/photo-1574629810360-7efbbe195018?auto=format&fit=crop&w=1600&q=80');
            background-size: cover;
            min-height: 100vh;
            color: white;
        }
        /* Style untuk Foto Profil Pojok Kanan Atas */
        .profile-top {
            position: absolute;
            top: 20px;
            right: 20px;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            border: 3px solid white;
            object-fit: cover;
            transition: 0.3s;
        }
        .profile-top:hover { transform: scale(1.1); }
        
        .glass { background: rgba(255,255,255,0.10); backdrop-filter: blur(12px); border-radius: 20px; border: 1px solid rgba(255,255,255,0.2); }
        .menu-card { transition: 0.3s; padding: 20px; }
        .menu-card:hover { transform: translateY(-8px); }
    </style>
</head>
<body>

<a href="profil.php">
    <img src="uploads/<?php echo $foto_user; ?>" class="profile-top" title="Klik untuk edit profil">
</a>

<div class="container py-5">
    <div class="text-center mb-5">
        <h1>⚽ LapanganKu</h1>
        <h4>Selamat Datang, <?php echo $_SESSION['nama']; ?></h4>
    </div>

    <div class="row g-4 justify-content-center">
        <div class="col-md-3">
            <div class="glass text-center menu-card">
                <h3>🏟</h3>
                <h4>Lihat Lapangan</h4>
                <p>Lihat semua lapangan futsal tersedia</p>
                <a href="lapangan.php" class="btn btn-success w-100">Masuk</a>
            </div>
        </div>
        <div class="col-md-3">
            <div class="glass text-center menu-card">
                <h3>📜</h3>
                <h4>Riwayat Booking</h4>
                <p>Cek semua booking yang pernah dibuat</p>
                <a href="riwayat.php" class="btn btn-warning w-100">Lihat</a>
            </div>
        </div>
        <div class="col-md-3">
            <div class="glass text-center menu-card">
                <h3>📞</h3>
                <h4>Hubungi Admin</h4>
                <p>Hubungi admin untuk bantuan</p>
                <a href="https://wa.me/<?php echo $no_hp; ?>" class="btn btn-info w-100">Chat Admin</a>
            </div>
        </div>
        <div class="col-md-3">
            <div class="glass text-center menu-card">
                <h3>🚪</h3>
                <h4>Logout</h4>
                <p>Keluar dari akun saat ini</p>
                <a href="logout.php" class="btn btn-danger w-100">Logout</a>
            </div>
        </div>
    </div>
</div>
</body>
</html>