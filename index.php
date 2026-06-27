<?php
session_start();

// Proteksi Halaman: Jika pengguna belum login, langsung alihkan ke login.php
if(!isset($_SESSION['id'])){
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - LapanganKu</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background:
            linear-gradient(rgba(0,0,0,0.65), rgba(0,0,0,0.65)),
            url('https://images.unsplash.com/photo-1574629810360-7efbbe195018?auto=format&fit=crop&w=1600&q=80');
            background-size: cover;
            background-position: center;
            min-height: 100vh;
            color: white;
            font-family: sans-serif;
        }

        .glass {
            background: rgba(255,255,255,0.10);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-radius: 20px;
            border: 1px solid rgba(255,255,255,0.2);
        }

        .menu-card {
            transition: 0.3s;
        }

        .menu-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.3);
        }

        .title-big {
            font-size: 50px;
            font-weight: bold;
        }

        .subtitle {
            color: #dcdcdc;
        }
    </style>
</head>
<body>

<div class="container py-5">

    <div class="text-center mb-5">
        <h1 class="title-big">⚽ LapanganKu</h1>
        <h4>Selamat Datang, <?php echo htmlspecialchars($_SESSION['nama']); ?> 👋</h4>
        <p class="subtitle">
            Booking lapangan futsal lebih cepat dan lebih mudah
        </p>
    </div>

    <div class="row g-4 justify-content-center">

        <div class="col-md-3">
            <div class="glass p-4 text-center menu-card">
                <div class="fs-1 mb-2">🏟</div>
                <h4>Lihat Lapangan</h4>
                <p class="small text-white-50 mb-3">Lihat semua lapangan futsal tersedia</p>
                <a href="lapangan.php" class="btn btn-success w-100 fw-bold">
                    Masuk
                </a>
            </div>
        </div>

        <div class="col-md-3">
            <div class="glass p-4 text-center menu-card">
                <div class="fs-1 mb-2">📜</div>
                <h4>Riwayat Booking</h4>
                <p class="small text-white-50 mb-3">Cek semua booking yang pernah dibuat</p>
                <a href="riwayat.php" class="btn btn-warning w-100 fw-bold text-dark">
                    Lihat
                </a>
            </div>
        </div>

        <div class="col-md-3">
            <div class="glass p-4 text-center menu-card">
                <div class="fs-1 mb-2">🚪</div>
                <h4>Logout</h4>
                <p class="small text-white-50 mb-3">Keluar dari akun saat ini</p>
                <a href="logout.php" class="btn btn-danger w-100 fw-bold" onclick="return confirm('Yakin ingin logout?')">
                    Logout
                </a>
            </div>
        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>