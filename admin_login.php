<?php
session_start();

// 1. WAJIB DI PALING ATAS agar variabel $koneksi langsung aktif
include 'koneksi.php'; 

$error = '';

if (isset($_POST['login_admin'])) {
    
    /** @var mysqli $koneksi */
    
    $username_input = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password_input = $_POST['password']; 

    // =========================================================================
    // 🚀 FITUR BYPASS DARURAT ADMIN (Solusi Instan Jika Bcrypt Databasemu Terpotong)
    // Silakan ganti email dan password di bawah ini untuk login langsung
    // =========================================================================
    $email_darurat    = "admin@gmail.com";
    $password_darurat = "admin123";

    if ($username_input === $email_darurat && $password_input === $password_darurat) {
        $_SESSION['admin'] = $email_darurat;
        $_SESSION['admin_id'] = 1; // Default ID Admin

        echo "<script>
                alert('Login Admin Berhasil (Via Bypass Secure)!');
                window.location='admin_dashboard.php'; 
              </script>"; 
        exit;
    }
    // =========================================================================

    // 2. JIKA BUKAN BYPASS, JALANKAN VERIFIKASI STANDAR DATABASE
    $cek_tabel = mysqli_query($koneksi, "SHOW TABLES");
    $list_tabel = [];
    while ($t = mysqli_fetch_array($cek_tabel)) {
        $list_tabel[] = $t[0];
    }

    $tabel_admin = in_array('user', $list_tabel) ? 'user' : (in_array('users', $list_tabel) ? 'users' : 'tb_admin');

    $cek_kolom = mysqli_query($koneksi, "SHOW COLUMNS FROM $tabel_admin");
    $list_kolom = [];
    while ($k = mysqli_fetch_assoc($cek_kolom)) { $list_kolom[] = $k['Field']; }

    $kolom_login = in_array('email', $list_kolom) ? 'email' : 'username';
    $kolom_password = in_array('password', $list_kolom) ? 'password' : 'pass';

    $where_clause = "WHERE $kolom_login = '$username_input'";
    if (in_array('role', $list_kolom)) { $where_clause .= " AND role = 'admin'"; }

    $query = mysqli_query($koneksi, "SELECT * FROM $tabel_admin $where_clause");

    if ($query && mysqli_num_rows($query) === 1) {
        $data_admin = mysqli_fetch_assoc($query);
        
        if (password_verify($password_input, $data_admin[$kolom_password])) {
            $_SESSION['admin'] = $data_admin[$kolom_login];
            $_SESSION['admin_id'] = $data_admin['id_user'] ?? $data_admin['id'] ?? 1;

            echo "<script>
                    alert('Login Admin Berhasil!');
                    window.location='admin_dashboard.php'; 
                  </script>"; 
            exit;
        } else {
            $error = 'Password yang Anda masukkan salah!';
        }
    } else {
        $error = 'Akun Admin tidak ditemukan atau role Anda bukan Admin!';
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - LapanganKu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(rgba(0,0,0,0.8), rgba(0,0,0,0.8)),
                        url('https://images.unsplash.com/photo-1508098682722-e99c43a406b2?auto=format&fit=crop&w=1600&q=80');
            background-size: cover;
            background-position: center;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
        }
        .login-box {
            width: 400px;
            padding: 40px;
            border-radius: 20px;
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border: 1px solid rgba(255,255,255,0.2);
            box-shadow: 0 15px 35px rgba(0,0,0,0.5);
        }
        .form-control {
            background: rgba(255,255,255,0.9);
            border-radius: 10px;
        }
    </style>
</head>
<body>

<div class="login-box">
    <h2 class="text-center mb-4">🔐 Admin Login</h2>
    
    <?php if(!empty($error)): ?>
        <div class="alert alert-danger text-center py-2" role="alert">
            <?= $error; ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Username / Email Admin</label>
            <input type="text" name="username" class="form-control" placeholder="Masukkan email admin" required>
        </div>

        <div class="mb-4">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
        </div>

        <button type="submit" name="login_admin" class="btn btn-danger w-100 fw-bold py-2 mb-2">
            Masuk Sebagai Admin
        </button>
        <a href="login.php" class="btn btn-light w-100 py-2 btn-sm text-decoration-none text-center d-block">
            Kembali ke Login User
        </a>
    </form>
</div>

</body>
</html>