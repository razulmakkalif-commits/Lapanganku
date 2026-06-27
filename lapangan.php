<?php
session_start();

// Proteksi halaman
if(!isset($_SESSION['id'])){
    header("Location: login.php");
    exit;
}

include 'koneksi.php';

/** @var mysqli $koneksi */ 
$data = mysqli_query($koneksi, "SELECT * FROM lapangan");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Lapangan - LapanganKu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body{
            background: linear-gradient(rgba(0,0,0,0.75), rgba(0,0,0,0.75)),
                        url('https://images.unsplash.com/photo-1556056504-5c7696c4c28d?auto=format&fit=crop&w=1600&q=80');
            background-size: cover;
            background-position: center;
            min-height: 100vh;
            color: white;
        }
        .card-lapangan{
            background: rgba(255,255,255,0.10);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border-radius: 20px;
            overflow: hidden;
            border: 1px solid rgba(255,255,255,0.2);
            transition: 0.3s;
        }
        .card-lapangan:hover{ transform: translateY(-8px); }
        .lapangan-img{ height: 190px; object-fit: cover; }
        .price{ font-size: 28px; font-weight: bold; color: #00ff88; }
        .status-badge{ background: #198754; padding: 8px 15px; border-radius: 20px; font-size: 14px; display: inline-block; }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="text-center mb-5">
        <h1>🏟 Daftar Lapangan</h1>
        <p>Pilih lapangan terbaik untuk pertandinganmu</p>
    </div>

    <div class="row g-4 justify-content-center">
        <?php 
        if(mysqli_num_rows($data) > 0) {
            while($row = mysqli_fetch_assoc($data)){ 
                
                // Penyesuaian ke id_lapangan dan harga_per_jam sesuai database asli kamu
                $id_lapangan = $row['id_lapangan'];
                $link_booking = "booking.php?id=" . $id_lapangan;
                $nama_lapangan = htmlspecialchars($row['nama_lapangan']);
                $harga_lapangan = number_format($row['harga_per_jam']);
        ?>

        <div class="col-md-4">
            <div class="card-lapangan">
                <img src="https://images.unsplash.com/photo-1575361204480-aadea25e6e68?auto=format&fit=crop&w=800&q=80" class="w-100 lapangan-img" alt="Foto Lapangan">
                <div class="p-4">
                    <h3><?php echo $nama_lapangan; ?></h3>
                    <p class="price">Rp <?php echo $harga_lapangan; ?>/jam</p>
                    <span class="status-badge">Tersedia</span>
                    <br><br>
                    <a href="<?php echo $link_booking; ?>" class="btn btn-success w-100 fw-bold py-2">Booking Sekarang</a>
                </div>
            </div>
        </div>

        <?php 
            } 
        } else {
            echo '<div class="col-12 text-center my-5">
                    <div class="alert alert-warning d-inline-block p-4 rounded-4" role="alert">
                        <h4>⚽ Lapangan Belum Tersedia</h4>
                        <p class="mb-0">Belum ada data lapangan.</p>
                    </div>
                  </div>';
        }
        ?>
    </div>

    <div class="text-center mt-5">
        <a href="dashboard.php" class="btn btn-light px-4">⬅ Kembali ke Dashboard</a>
    </div>
</div>

</body>
</html>