<?php
session_start();
include "../db.php";

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../Login/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $laporan_id = intval($_POST['laporan_id']);
    $message_id = intval($_POST['message_id']);

    // Hapus pesan chat dari tabel chat
    $deleteMessage = mysqli_query($koneksi, "DELETE FROM chat WHERE id = $message_id");

    // Hapus laporan dari tabel laporan_chat
    $deleteReport = mysqli_query($koneksi, "DELETE FROM laporan_chat WHERE id = $laporan_id");

    if ($deleteMessage && $deleteReport) {
        // Jika berhasil, redirect ke berbagi.php dengan pesan sukses
        header("Location: ../Berbagi/berbagi.php?deleted=1");
    } else {
        // Jika gagal, kembali ke halaman laporan chat
        header("Location: laporan_chat_admin.php?error=1");
    }
    exit();
}
?>
