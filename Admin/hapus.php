<?php
include "../db.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id'])) {
        $id = intval($_POST['id']);

        // Hapus data berita berdasarkan id
        $query = "DELETE FROM berita WHERE id = $id";

        if (mysqli_query($koneksi, $query)) {
            // Jika berhasil hapus, redirect ke halaman utama dengan pesan sukses (bisa lewat GET param)
            header("Location: home.php?hapus=berhasil");
            exit;
        } else {
            // Jika gagal hapus, bisa tampilkan error atau redirect dengan pesan gagal
            echo "Error saat menghapus berita: " . mysqli_error($koneksi);
        }
    } else {
        echo "ID berita tidak ditemukan.";
    }
} else {
    // Jika bukan POST, arahkan kembali ke halaman utama
    header("Location: home.php");
    exit;
}
