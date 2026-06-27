<?php
session_start();

// Proteksi halaman: Jika belum login, tendang ke login.php
if(!isset($_SESSION['id'])){
    header("Location: login.php");
    exit;
}

include 'koneksi.php';

// Mengambil ID Lapangan dari URL
if(!isset($_GET['id'])){
    header("Location: lapangan.php");
    exit;
}

$id_lapangan = $_GET['id'];

/** @var mysqli $koneksi */
$query = mysqli_query($koneksi, "SELECT * FROM lapangan WHERE id_lapangan = '$id_lapangan'");

if(mysqli_num_rows($query) === 0){
    die("Lapangan tidak ditemukan.");
}

$lapangan = mysqli_fetch_assoc($query);

// VALIDASI KETAT: Hanya berjalan jika metode request-nya POST dan tombol book_now ditekan
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['book_now'])){
    
    if(!empty($_POST['tanggal_pesan']) && !empty($_POST['id_slot'])) {
        
        $user_id       = $_SESSION['id'];
        $tanggal_pesan = $_POST['tanggal_pesan'];
        $id_slot       = intval($_POST['id_slot']); 
        $catatan       = isset($_POST['catatan']) ? $_POST['catatan'] : '';
        $total_harga   = $lapangan['harga_per_jam']; 

        // --- SISTEM DETEKSI OTOMATIS TIPE DATA STATUS ---
        // Mencari tahu apakah kolom status adalah ENUM dan apa saja nilainya
        $cek_status = mysqli_query($koneksi, "SHOW COLUMNS FROM pemesanan LIKE 'status'");
        $status_info = mysqli_fetch_assoc($cek_status);
        $status_type = $status_info ? $status_info['Type'] : '';

        // Tentukan nilai status default yang paling aman berdasarkan tipe kolomnya
        $status_default = 'Menunggu'; // Bawaan awal

        if (strpos($status_type, 'enum') !== false) {
            // Jika ENUM, kita ambil nilai pertama yang tersedia di dalam kurung enum('...','...')
            preg_match("/^enum\(\'(.*)\'\)$/", $status_type, $matches);
            if (isset($matches[1])) {
                $enum_values = explode("','", $matches[1]);
                
                // Cari kata yang mirip dengan antrean/tertunda
                if (in_array('Menunggu', $enum_values)) { $status_default = 'Menunggu'; }
                elseif (in_array('Pending', $enum_values)) { $status_default = 'Pending'; }
                elseif (in_array('pending', $enum_values)) { $status_default = 'pending'; }
                elseif (in_array('proses', $enum_values)) { $status_default = 'proses'; }
                elseif (in_array('0', $enum_values)) { $status_default = '0'; }
                else { $status_default = $enum_values[0]; } // Jika tidak ada yang mirip, pakai opsi pertama dari enum
            }
        } elseif (strpos($status_type, 'int') !== false || strpos($status_type, 'tinyint') !== false) {
            // Jika kolom status ternyata bertipe angka (Integer)
            $status_default = 0; 
        }

        // Eksekusi query INSERT menggunakan $status_default yang sudah disesuaikan otomatis
        $insert = mysqli_query(
            $koneksi,
            "INSERT INTO pemesanan (id_user, id_slot, id_lapangan, tanggal_pesan, total_harga, status, catatan) 
             VALUES ('$user_id', '$id_slot', '$id_lapangan', '$tanggal_pesan', '$total_harga', '$status_default', '$catatan')"
        );

        if($insert){
            echo "<script>
                    alert('Booking berhasil diajukan! Menunggu persetujuan admin.');
                    window.location='riwayat.php';
                  </script>";
            exit;
        } else {
            echo "<script>alert('Booking gagal: " . mysqli_error($koneksi) . "');</script>";
        }
    } else {
        echo "<script>alert('Harap isi tanggal dan slot waktu bermain!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Booking - LapanganKu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(rgba(0,0,0,0.75), rgba(0,0,0,0.75)),
                        url('https://images.unsplash.com/photo-1508098682722-e99c43a406b2?auto=format&fit=crop&w=1600&q=80');
            background-size: cover;
            background-position: center;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
        }
        .booking-box {
            width: 500px;
            padding: 35px;
            border-radius: 25px;
            background: rgba(255,255,255,0.10);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border: 1px solid rgba(255,255,255,0.2);
            box-shadow: 0px 10px 40px rgba(0,0,0,0.5);
        }
        .form-control, .form-select {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            color: black;
        }
    </style>
</head>
<body>

<div class="booking-box">
    <h2 class="text-center mb-2">📅 Form Booking</h2>
    <p class="text-center text-light bg-dark bg-opacity-50 py-2 rounded-3 fw-bold">
        ⚽ <?= htmlspecialchars($lapangan['nama_lapangan']); ?>
    </p>
    <p class="text-center text-success fw-bold fs-5 mb-4">
        Rp <?= number_format($lapangan['harga_per_jam']); ?>/jam
    </p>

    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Tanggal Main</label>
            <input type="date" name="tanggal_pesan" class="form-control" min="<?= date('Y-m-d'); ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Pilih Jam / Slot Waktu</label>
            <select name="id_slot" class="form-select" required>
                <option value="">-- Pilih Jam Main --</option>
                <option value="1">Pagi (08:00 - 10:00)</option>
                <option value="2">Siang (13:00 - 15:00)</option>
                <option value="3">Sore (16:00 - 18:00)</option>
                <option value="4">Malam (19:00 - 21:00)</option>
                <option value="5">Malam (21:00 - 23:00)</option>
            </select>
        </div>

        <div class="mb-4">
            <label class="form-label">Catatan Tambahan</label>
            <textarea name="catatan" class="form-control" rows="2" placeholder="Contoh: Sewa rompi / bola tambahan"></textarea>
        </div>

        <button type="submit" name="book_now" class="btn btn-success w-100 fw-bold py-2 mb-2">
            Konfirmasi Booking
        </button>
        <a href="lapangan.php" class="btn btn-light w-100 py-2">Batal</a>
    </form>
</div>

</body>
</html>