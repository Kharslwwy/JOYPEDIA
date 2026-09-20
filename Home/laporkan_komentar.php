<?php
session_start();
include "../db.php";

if (!isset($_SESSION['username'])) {
    die("Anda harus login untuk melaporkan komentar.");
}

$username = $_SESSION['username'];
$komentar_id = $_POST['komentar_id'] ?? null;
$berita_id = $_POST['berita_id'] ?? null;

if (!$komentar_id || !$berita_id) {
    die("Data tidak lengkap.");
}

// Cek apakah user sudah lapor komentar ini
$cek = mysqli_query($koneksi, "SELECT * FROM laporan_komentar WHERE username='$username' AND komentar_id=$komentar_id");
if (mysqli_num_rows($cek) > 0) {
    $_SESSION['flash_message'] = "Anda sudah melaporkan komentar ini.";
    header("Location: detail_berita.php?id=$berita_id");
    exit;
}

// Simpan laporan
$query = mysqli_query($koneksi, "INSERT INTO laporan_komentar (username, komentar_id) VALUES ('$username', $komentar_id)");

if ($query) {
    $_SESSION['flash_message'] = "Komentar berhasil dilaporkan.";
    header("Location: detail_berita.php?id=$berita_id");
    exit;
} else {
    $_SESSION['flash_message'] = "Gagal melaporkan komentar.";
    header("Location: detail_berita.php?id=$berita_id");
    exit;
}
