<?php
session_start();
include 'koneksi.php';

$id = $_GET['id'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Upload Bukti</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">

    <h2>Upload Bukti Pembayaran</h2>

    <form action="proses_upload.php?id=<?php echo $id; ?>" method="POST" enctype="multipart/form-data">

        <div class="mb-3">
            <label>Pilih gambar bukti transfer</label>
            <input type="file" name="bukti" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-success">
            Upload
        </button>

    </form>

</div>

</body>
</html>