<?php
session_start();
include "../db.php";

// Pastikan user sudah login
if (!isset($_SESSION['username'])) {
    echo "<script>alert('Anda harus login terlebih dahulu.'); window.location='../login.php';</script>";
    exit();
}

// Validasi data
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['berita_id'], $_POST['isi_komentar'])) {
    $berita_id = intval($_POST['berita_id']);
    $isi_komentar = trim($_POST['isi_komentar']);
    $username = $_SESSION['username'];

    if ($isi_komentar !== '') {
        $isi_komentar = mysqli_real_escape_string($koneksi, $isi_komentar);

        // Simpan komentar ke database
        $query = "INSERT INTO komentar (berita_id, username, isi, tanggal) 
                  VALUES ($berita_id, '$username', '$isi_komentar', NOW())";
        
        if (mysqli_query($koneksi, $query)) {
            header("Location: detail_berita.php?id=$berita_id#commentForm");
            exit();
        } else {
            echo "<script>alert('Gagal menyimpan komentar.'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('Komentar tidak boleh kosong.'); window.history.back();</script>";
    }
} else {
    echo "<script>alert('Permintaan tidak valid.'); window.history.back();</script>";
}
