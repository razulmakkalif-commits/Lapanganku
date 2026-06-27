<?php
session_start();

// Proteksi halaman admin
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit;
}

include 'koneksi.php';

/** @var mysqli $koneksi */

// PROSES UPDATE STATUS (Dibuat adaptif sesuai tipe data ENUM/Integer di database)
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id_pemesanan = mysqli_real_escape_string($koneksi, $_GET['id']);
    $action = mysqli_real_escape_string($koneksi, $_GET['action']);
    
    // 1. DETEKSI OTOMATIS OPSI YANG DIIZINKAN OLEH KOLOM STATUS
    $cek_status = mysqli_query($koneksi, "SHOW COLUMNS FROM pemesanan LIKE 'status'");
    $status_info = mysqli_fetch_assoc($cek_status);
    $status_type = $status_info ? $status_info['Type'] : '';

    // Nilai default cadangan jika deteksi gagal
    $status_baru = ($action === 'setuju') ? 'Disetujui' : 'Ditolak';

    if (strpos($status_type, 'enum') !== false) {
        preg_match("/^enum\(\'(.*)\'\)$/", $status_type, $matches);
        if (isset($matches[1])) {
            $enum_values = explode("','", $matches[1]);
            
            if ($action === 'setuju') {
                if (in_array('Disetujui', $enum_values)) { $status_baru = 'Disetujui'; }
                elseif (in_array('Approved', $enum_values)) { $status_baru = 'Approved'; }
                elseif (in_array('approved', $enum_values)) { $status_baru = 'approved'; }
                elseif (in_array('Berhasil', $enum_values)) { $status_baru = 'Berhasil'; }
                elseif (in_array('Sukses', $enum_values)) { $status_baru = 'Sukses'; }
                elseif (in_array('1', $enum_values)) { $status_baru = '1'; }
                else { $status_baru = $enum_values[1] ?? $enum_values[0]; } // Opsi alternatif kedua
            } else { // Jika aksi = tolak
                if (in_array('Ditolak', $enum_values)) { $status_baru = 'Ditolak'; }
                elseif (in_array('Rejected', $enum_values)) { $status_baru = 'Rejected'; }
                elseif (in_array('rejected', $enum_values)) { $status_baru = 'rejected'; }
                elseif (in_array('Gagal', $enum_values)) { $status_baru = 'Gagal'; }
                elseif (in_array('Batal', $enum_values)) { $status_baru = 'Batal'; }
                elseif (in_array('2', $enum_values)) { $status_baru = '2'; }
                else { $status_baru = $enum_values[2] ?? $enum_values[0]; }
            }
        }
    } elseif (strpos($status_type, 'int') !== false || strpos($status_type, 'tinyint') !== false) {
        // Jika kolom status ternyata tipe angka angka (0=menunggu, 1=setuju, 2=tolak)
        $status_baru = ($action === 'setuju') ? 1 : 2;
    }

    // Eksekusi update menggunakan status baru hasil deteksi pintar
    $update = mysqli_query($koneksi, "UPDATE pemesanan SET status = '$status_baru' WHERE id_pemesanan = '$id_pemesanan'");
    
    if ($update) {
        echo "<script>alert('Status pesanan berhasil diperbarui menjadi [$status_baru]!'); window.location='data_pemesanan.php';</script>";
        exit;
    } else {
        echo "<script>alert('Gagal update status: " . mysqli_error($koneksi) . "'); window.location='data_pemesanan.php';</script>";
        exit;
    }
}

// 2. QUERY UTAMA (Bersih dari join jadwal_slot)
$query_text = "
    SELECT 
        p.id_pemesanan,
        p.tanggal_pesan,
        p.total_harga,
        p.status,
        p.catatan,
        p.id_slot,
        u.nama AS nama_user,
        u.no_hp AS no_hp_user,
        l.nama_lapangan
    FROM pemesanan p
    LEFT JOIN users u ON p.id_user = u.id_user
    LEFT JOIN lapangan l ON p.id_lapangan = l.id_lapangan
    ORDER BY p.id_pemesanan DESC
";

$query = mysqli_query($koneksi, $query_text);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pemesanan - Admin LapanganKu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; font-family: sans-serif; }
        .main-card { border-radius: 15px; border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        .table th { background-color: #343a40; color: white; }
        .badge-waiting { background-color: #ffc107; color: black; }
        .badge-success { background-color: #198754; color: white; }
        .badge-danger { background-color: #dc3545; color: white; }
    </style>
</head>
<body>

<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>📋 Data Pemesanan Lapangan</h2>
        <a href="admin_dashboard.php" class="btn btn-secondary">Kembali ke Dashboard</a>
    </div>

    <div class="card main-card">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle text-center">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama Pelanggan</th>
                            <th>Lapangan</th>
                            <th>Tanggal Main</th>
                            <th>Jam / Slot</th>
                            <th>Total Harga</th>
                            <th>Catatan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if ($query && mysqli_num_rows($query) > 0) {
                            while ($row = mysqli_fetch_assoc($query)) {
                                $status = $row['status'];
                                
                                // Penentuan warna badge yang adaptif dengan nilai database apa pun
                                $badge_class = 'badge-waiting';
                                if (in_array(strtolower($status), ['disetujui', 'approved', 'sukses', 'berhasil', '1'])) {
                                    $badge_class = 'badge-success';
                                } elseif (in_array(strtolower($status), ['ditolak', 'rejected', 'batal', 'gagal', '2'])) {
                                    $badge_class = 'badge-danger';
                                }

                                // Mengambil data nama jam/slot via PHP terpisah
                                $id_slot_pesanan = $row['id_slot'];
                                $jam_tampil = "Slot " . $id_slot_pesanan;
                                
                                $cek_slot_db = mysqli_query($koneksi, "SELECT * FROM jadwal_slot WHERE id_slot = '$id_slot_pesanan'");
                                if ($cek_slot_db && mysqli_num_rows($cek_slot_db) > 0) {
                                    $data_slot = mysqli_fetch_assoc($cek_slot_db);
                                    $jam_tampil = $data_slot['jam'] ?? $data_slot['waktu'] ?? $data_slot['nama_slot'] ?? "Slot " . $id_slot_pesanan;
                                }
                        ?>
                                <tr>
                                    <td><strong>#<?= $row['id_pemesanan']; ?></strong></td>
                                    <td>
                                        <?= htmlspecialchars($row['nama_user'] ?? 'User Dihapus'); ?><br>
                                        <small class="text-muted"><?= htmlspecialchars($row['no_hp_user'] ?? '-'); ?></small>
                                    </td>
                                    <td><?= htmlspecialchars($row['nama_lapangan'] ?? 'Lapangan Dihapus'); ?></td>
                                    <td><?= date('d-m-Y', strtotime($row['tanggal_pesan'])); ?></td>
                                    <td><span class="badge bg-secondary"><?= htmlspecialchars($jam_tampil); ?></span></td>
                                    <td class="fw-bold text-success">Rp <?= number_format($row['total_harga']); ?></td>
                                    <td><small class="text-muted"><?= htmlspecialchars($row['catatan'] ?? '-'); ?></small></td>
                                    <td><span class="badge <?= $badge_class; ?> px-3 py-2"><?= $status; ?></span></td>
                                    <td>
                                        <?php if (in_array(strtolower($status), ['menunggu', 'pending', '0'])) : ?>
                                            <a href="data_pemesanan.php?action=setuju&id=<?= $row['id_pemesanan']; ?>" 
                                               class="btn btn-success btn-sm fw-bold mb-1" 
                                               onclick="return confirm('Setujui pesanan ini?')">Setuju</a>
                                            <a href="data_pemesanan.php?action=tolak&id=<?= $row['id_pemesanan']; ?>" 
                                               class="btn btn-danger btn-sm fw-bold mb-1" 
                                               onclick="return confirm('Tolak pesanan ini?')">Tolak</a>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                        <?php 
                            }
                        } else {
                            echo "<tr><td colspan='9' class='text-center py-4 text-muted'>Belum ada data pemesanan masuk.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

</body>
</html>