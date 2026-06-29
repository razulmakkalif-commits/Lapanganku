<?php
session_start();
$koneksi = null;
require_once 'koneksi.php';
if (!$koneksi) {
    die('Koneksi database gagal.');
}

// Pastikan user sudah login
if(!isset($_SESSION['id'])){
    header("Location: login.php");
    exit;
}

$id = $_SESSION['id'];

// Proses Update Profil
if(isset($_POST['update_profil'])){
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $pass_lama = $_POST['pass_lama'];
    $pass_baru = $_POST['pass_baru'];
    
    // Ambil data user untuk validasi password lama
    $cek_user = mysqli_query($koneksi, "SELECT password, foto FROM users WHERE id='$id'");
    $d_lama = mysqli_fetch_assoc($cek_user);

    $sql_pass = "";
    // Validasi jika user ingin ganti password
    if(!empty($pass_baru)){
        if($pass_lama == $d_lama['password']){
            $sql_pass = ", password='$pass_baru'";
        } else {
            echo "<script>alert('Gagal! Password lama salah.'); window.location='profil.php';</script>";
            exit;
        }
    }

    // Proses Upload Foto
    if(!empty($_FILES['foto']['name'])){
        $nama_file = time().'_'.$_FILES['foto']['name'];
        $folder = 'uploads/';
        if(move_uploaded_file($_FILES['foto']['tmp_name'], $folder . $nama_file)){
            $sql = "UPDATE users SET nama='$nama', foto='$nama_file' $sql_pass WHERE id='$id'";
        } else {
            $sql = "UPDATE users SET nama='$nama' $sql_pass WHERE id='$id'";
        }
    } else {
        $sql = "UPDATE users SET nama='$nama' $sql_pass WHERE id='$id'";
    }

    if(mysqli_query($koneksi, $sql)){
        echo "<script>alert('Profil berhasil diupdate!'); window.location='dashboard.php';</script>";
    } else {
        echo "<script>alert('Gagal update: " . mysqli_error($koneksi) . "');</script>";
    }
}

// Ambil data untuk ditampilkan di form
$d = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM users WHERE id='$id'"));
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Profil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .profile-img { width: 150px; height: 150px; object-fit: cover; border-radius: 50%; border: 3px solid #ddd; display: block; margin: 0 auto; }
        .bg-card { background: #f8f9fa; border-radius: 15px; }
    </style>
</head>
<body class="bg-light p-4">
    <div class="card p-4 shadow-sm bg-card" style="max-width: 500px; margin: auto;">
        <h3 class="text-center mb-4">Edit Profil Saya</h3>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-4 text-center">
                <img src="uploads/<?php echo !empty($d['foto']) ? $d['foto'] : 'default.jpg'; ?>" class="profile-img">
            </div>
            <div class="mb-3">
                <label>Foto Profil:</label>
                <input type="file" name="foto" class="form-control" accept="image/*">
            </div>
            <div class="mb-3">
                <label>Nama Lengkap:</label>
                <input type="text" name="nama" class="form-control" value="<?php echo htmlspecialchars($d['nama']); ?>" required>
            </div>
            <hr>
            <p class="text-muted small">* Isi bagian bawah hanya jika ingin mengganti password</p>
            <div class="mb-3">
                <label>Password Lama:</label>
                <input type="password" name="pass_lama" class="form-control" placeholder="Masukkan password lama">
            </div>
            <div class="mb-3">
                <label>Password Baru:</label>
                <input type="password" name="pass_baru" class="form-control" placeholder="Masukkan password baru">
            </div>
            <button type="submit" name="update_profil" class="btn btn-success w-100">Simpan Perubahan</button>
            <a href="dashboard.php" class="btn btn-secondary w-100 mt-2">Kembali</a>
        </form>
    </div>
</body>
</html>