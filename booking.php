<?php

session_start();
include 'koneksi.php';

if(!isset($_SESSION['id'])){
    header("Location: login.php");
    exit;
}

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

    $cek = mysqli_query(
        $koneksi,
        "SELECT * FROM pemesanan
        WHERE lapangan_id='$id_lapangan'
        AND tanggal='$tanggal'
        AND status != 'Ditolak'
        AND (
            ('$jam_mulai' BETWEEN jam_mulai AND jam_selesai)
            OR
            ('$jam_selesai' BETWEEN jam_mulai AND jam_selesai)
            OR
            (jam_mulai BETWEEN '$jam_mulai' AND '$jam_selesai')
        )"
    );

    if(mysqli_num_rows($cek) > 0){

        echo "<script>
            alert('Jadwal bentrok! Pilih jam lain.');
        </script>";

    }else{

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

        echo "<script>
            alert('Booking berhasil dibuat!');
            window.location='riwayat.php';
        </script>";

    }
}

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

    echo "<script>
        alert('Booking berhasil dibuat!');
        window.location='riwayat.php';
    </script>";
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Booking Lapangan</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>

        body{
            background:
            linear-gradient(rgba(0,0,0,0.75), rgba(0,0,0,0.75)),
            url('https://images.unsplash.com/photo-1574629810360-7efbbe195018?auto=format&fit=crop&w=1600&q=80');

            background-size: cover;
            background-position: center;
            min-height: 100vh;
            color: white;
        }

        .booking-card{
            background: rgba(255,255,255,0.10);
            backdrop-filter: blur(15px);
            border-radius: 25px;
            border: 1px solid rgba(255,255,255,0.2);
            padding: 40px;
        }

        .lapangan-title{
            font-size: 35px;
            font-weight: bold;
        }

        .price{
            font-size: 28px;
            color: #00ff88;
            font-weight: bold;
        }

        .form-control{
            border-radius: 12px;
            height: 50px;
        }

        .btn-book{
            height: 50px;
            border-radius: 12px;
            font-weight: bold;
        }

    </style>
</head>
<body>

<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-md-7">

            <div class="booking-card">

                <div class="text-center mb-4">

                    <h1>⚽ Booking Lapangan</h1>

                    <p>Lengkapi detail bookingmu</p>

                </div>

                <div class="mb-4 text-center">

                    <div class="lapangan-title">
                        <?php echo $lapangan['nama_lapangan']; ?>
                    </div>

                    <div class="price">
                        Rp <?php echo number_format($lapangan['harga']); ?>/jam
                    </div>

                </div>

                <form method="POST">

                    <div class="mb-3">
                        <label>Tanggal Bermain</label>
                        <input type="date" name="tanggal" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Jam Mulai</label>
                        <input type="time" name="jam_mulai" class="form-control" required>
                    </div>

                    <div class="mb-4">
                        <label>Jam Selesai</label>
                        <input type="time" name="jam_selesai" class="form-control" required>
                    </div>

                    <button type="submit" name="simpan"
                    class="btn btn-success btn-book w-100">
                        Konfirmasi Booking
                    </button>

                </form>

                <div class="text-center mt-4">

                    <a href="lapangan.php" class="btn btn-light">
                        Kembali
                    </a>

                </div>

            </div>

        </div>

    </div>

</div>

</body>
</html>