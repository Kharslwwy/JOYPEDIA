<?php
session_start();
include "../db.php";

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../Login/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $laporan_id = $_POST['laporan_id'] ?? null;
    $komentar_id = $_POST['komentar_id'] ?? null;

    if ($laporan_id && $komentar_id) {
        // Hapus komentar dari database
        $stmt = $koneksi->prepare("DELETE FROM komentar WHERE id = ?");
        $stmt->bind_param("i", $komentar_id);
        $stmt->execute();
        $stmt->close();

        // Update status laporan menjadi selesai
        $stmt2 = $koneksi->prepare("UPDATE laporan_komentar SET status = 'selesai' WHERE id = ?");
        $stmt2->bind_param("i", $laporan_id);
        $stmt2->execute();
        $stmt2->close();
    }
}

header("Location: laporan.php");
exit();
