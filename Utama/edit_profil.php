<?php
session_start();
include '../db.php';

// Cek apakah sudah login
if (!isset($_SESSION['username'])) {
    header("Location: ../Login/login.php");
    exit();
}

// Ambil data user saat ini
$username = $_SESSION['username'];
$query = "SELECT bio FROM users WHERE username = '$username'";
$result = mysqli_query($koneksi, $query);
$data = mysqli_fetch_assoc($result);
$currentBio = $data['bio'] ?? '';

// Jika form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $newBio = mysqli_real_escape_string($koneksi, $_POST['bio']);
    $update = mysqli_query($koneksi, "UPDATE users SET bio = '$newBio' WHERE username = '$username'");
    
    if ($update) {
        $_SESSION['bio'] = $newBio;
        header("Location: profil.php");
        exit();
    } else {
        $error = "Gagal mengupdate bio!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Profil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Edit Bio</h2>
    <?php if (!empty($error)) : ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="POST">
        <div class="mb-3">
            <label for="bio" class="form-label">Bio Anda</label>
            <textarea class="form-control" id="bio" name="bio" rows="4"><?= htmlspecialchars($currentBio) ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="profil.php" class="btn btn-secondary">Kembali</a>
    </form>
</div>
</body>
</html>