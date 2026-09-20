<?php
session_start();
include "../db.php";

header('Content-Type: application/json');

// Validasi input
if (!isset($_POST['polling_id']) || !isset($_POST['jawaban'])) {
    echo json_encode(['status'=>'error','msg'=>'Data tidak valid.']);
    exit();
}

$polling_id = intval($_POST['polling_id']);
$jawaban = mysqli_real_escape_string($koneksi, $_POST['jawaban']);

// Cek polling
$pollingQuery = mysqli_query($koneksi, "SELECT * FROM polling WHERE id = $polling_id");
if (!$pollingQuery || mysqli_num_rows($pollingQuery) === 0) {
    echo json_encode(['status'=>'error','msg'=>'Polling tidak ditemukan.']);
    exit();
}
$polling = mysqli_fetch_assoc($pollingQuery);
$berita_id = intval($polling['berita_id']);

// Cek apakah user sudah voting
if (isset($_SESSION['polling'][$polling_id])) {
    // Ambil hasil polling saja
    $resultQuery = mysqli_query($koneksi, "SELECT jawaban, COUNT(*) as jumlah FROM polling_responses WHERE polling_id = $polling_id GROUP BY jawaban");
    $totalVotes = 0;
    $results = [];
    while($row = mysqli_fetch_assoc($resultQuery)){
        $results[$row['jawaban']] = (int)$row['jumlah'];
        $totalVotes += (int)$row['jumlah'];
    }
    echo json_encode(['status'=>'voted','results'=>$results,'total'=>$totalVotes]);
    exit();
}

// Simpan jawaban
$query = "INSERT INTO polling_responses (polling_id, jawaban) VALUES ($polling_id, '$jawaban')";
if (mysqli_query($koneksi, $query)) {
    $_SESSION['polling'][$polling_id] = true;

    // Ambil hasil polling terbaru
    $resultQuery = mysqli_query($koneksi, "SELECT jawaban, COUNT(*) as jumlah FROM polling_responses WHERE polling_id = $polling_id GROUP BY jawaban");
    $totalVotes = 0;
    $results = [];
    while($row = mysqli_fetch_assoc($resultQuery)){
        $results[$row['jawaban']] = (int)$row['jumlah'];
        $totalVotes += (int)$row['jumlah'];
    }

    echo json_encode(['status'=>'success','results'=>$results,'total'=>$totalVotes]);
    exit();
} else {
    echo json_encode(['status'=>'error','msg'=>'Gagal menyimpan jawaban.']);
    exit();
}
?>
