<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['username'])) {
    echo json_encode(["ok" => false, "error" => "Belum login"]);
    exit();
}

require "../db.php"; // Pastikan $koneksi terdefinisi di sini

// Ambil data JSON
$input = json_decode(file_get_contents("php://input"), true);
$message_id = intval($input["message_id"] ?? 0);
$alasan     = trim($input["alasan"] ?? "");

if ($message_id <= 0 || $alasan === "") {
    echo json_encode(["ok" => false, "error" => "Data laporan tidak lengkap"]);
    exit();
}

$username = $_SESSION["username"];
$alasan   = mysqli_real_escape_string($koneksi, $alasan);

// Simpan laporan
$sql = "INSERT INTO laporan_chat (message_id, pelapor, alasan, waktu_lapor) 
        VALUES ('$message_id', '$username', '$alasan', NOW())";

if (mysqli_query($koneksi, $sql)) {
    echo json_encode(["ok" => true]);
} else {
    echo json_encode(["ok" => false, "error" => mysqli_error($koneksi)]);
}
