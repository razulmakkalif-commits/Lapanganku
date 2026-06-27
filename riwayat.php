<?php
session_start();

// Proteksi Halaman
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

include 'koneksi.php';

/** @var mysqli $koneksi */
$user_id = $_SESSION['id'];

// Query Data: Saya menggunakan alias p.catatan dan l.nama_lapangan
// Pastikan nama kolom di database Anda sesuai dengan ini.
$query_text = "
    SELECT 
        p.*, 
        l.nama_lapangan
    FROM pemesanan p
    LEFT JOIN lapangan l ON p.id_lapangan = l.id_lapangan
    WHERE p.id_user = '$user_id'
    ORDER BY p.id_pemesanan DESC
";

$query = mysqli_query($koneksi, $query_text);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Riwayat Booking - LapanganKu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('https://images.unsplash.com/photo-1574629810360-7efbbe195018?auto=format&fit=crop&w=1600&q=80'); background-size: cover; background-attachment: fixed; min-height: 100vh; color: white; font-family: sans-serif; }
        .glass { background: rgba(255, 255, 255, 0.08); backdrop-filter: blur(15px); border-radius: 20px; border: 1px solid rgba(255, 255, 255, 0.15); }
        .status-badge { padding: 5px 15px; border-radius: 20px; font-size: 0.8em; font-weight: bold; }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">📜 Riwayat Booking</h2>
        <a href="index.php" class="btn btn-outline-light">Kembali</a>
    </div>

    <div class="card glass p-4">
        <table class="table table-dark table-hover align-middle text-center" style="background:transparent">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Lapangan</th>
                    <th>Tanggal</th>
                    <th>Jam</th>
                    <th>Total</th>
                    <th>Catatan</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if ($query && mysqli_num_rows($query) > 0) {
                    while ($row = mysqli_fetch_assoc($query)) {
                        // Logika Status
                        $s = strtolower($row['status']);
                        $badge = ($s == '1' || $s == 'disetujui') ? 'bg-success' : (($s == '2' || $s == 'ditolak') ? 'bg-danger' : 'bg-warning text-dark');
                        $teks = ($s == '1' || $s == 'disetujui') ? 'Disetujui' : (($s == '2' || $s == 'ditolak') ? 'Ditolak' : 'Menunggu');
                        
                        // Ambil Data Jam
                        $jam = "Slot " . $row['id_slot'];
                        $cek = mysqli_query($koneksi, "SELECT * FROM jadwal_slot WHERE id_slot = '{$row['id_slot']}'");
                        if ($c = mysqli_fetch_assoc($cek)) { $jam = $c['jam'] ?? $c['waktu'] ?? $jam; }
                ?>
                    <tr>
                        <td>#<?= $row['id_pemesanan'] ?></td>
                        <td class="fw-bold"><?= htmlspecialchars($row['nama_lapangan'] ?? 'Lapangan Tidak Ditemukan') ?></td>
                        <td><?= date('d M Y', strtotime($row['tanggal_pesan'])) ?></td>
                        <td><?= htmlspecialchars($jam) ?></td>
                        <td>Rp <?= number_format($row['total_harga']) ?></td>
                        <td><?= htmlspecialchars($row['catatan'] ?? '-') ?></td>
                        <td><span class="status-badge <?= $badge ?>"><?= $teks ?></span></td>
                    </tr>
                <?php 
                    }
                } else {
                    echo "<tr><td colspan='7' class='py-5'>Belum ada riwayat booking.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>