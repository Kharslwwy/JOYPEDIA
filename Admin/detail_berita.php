<?php
session_start();
include "../db.php";

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<p><b>Error:</b> ID berita tidak ditemukan di URL.</p>";
    exit();
}

$id = intval($_GET['id']);
if ($id <= 0) {
    echo "<p><b>Error:</b> ID berita tidak valid.</p>";
    exit();
}

$query = mysqli_query($koneksi, "SELECT * FROM berita WHERE id = $id");
if (!$query || mysqli_num_rows($query) === 0) {
    echo "<p><b>Error:</b> Berita dengan ID $id tidak ditemukan.</p>";
    exit();
}

$berita = mysqli_fetch_assoc($query);

// Ambil polling berita
$pollingQuery = mysqli_query($koneksi, "SELECT * FROM polling WHERE berita_id = $id");
$polling = null;
$pollingOptions = [];

if ($pollingQuery && mysqli_num_rows($pollingQuery) > 0) {
    $polling = mysqli_fetch_assoc($pollingQuery);
    $pollingOptions = json_decode($polling['options'], true);
}

$pollingResults = [];
$totalVotes = 0;
if ($polling) {
    $resultQuery = mysqli_query($koneksi, "SELECT jawaban, COUNT(*) as jumlah FROM polling_responses WHERE polling_id = {$polling['id']} GROUP BY jawaban");
    if ($resultQuery) {
        while ($row = mysqli_fetch_assoc($resultQuery)) {
            $pollingResults[$row['jawaban']] = (int)$row['jumlah'];
            $totalVotes += (int)$row['jumlah'];
        }
    }
}

// YouTube ID
$videoId = '';
if (!empty($berita['youtube_link'])) {
    $urlParts = parse_url($berita['youtube_link']);
    if (isset($urlParts['query'])) {
        parse_str($urlParts['query'], $queryParams);
        if (isset($queryParams['v'])) {
            $videoId = htmlspecialchars($queryParams['v']);
        }
    }
}

// Cek user login admin
$username = $_SESSION['username'] ?? null;

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($berita['judul']); ?> - Detail Berita (Admin)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f9f9f9;
            font-family: Arial, sans-serif;
        }
        .content-container {
            animation: fadeIn 0.6s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .polling-section {
            background: #ffffff;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .progress-bar {
            transition: width 0.4s ease;
        }
    </style>
</head>
<body>
<div class="container mt-5 content-container">
    <div class="col-lg-8 mx-auto">
        <h1 class="mb-3 fw-bold"><?= htmlspecialchars($berita['judul']); ?></h1>
        <p class="text-muted">
            <strong>Kategori:</strong> <?= htmlspecialchars($berita['kategori']); ?> &middot;
            <strong>Tanggal:</strong> <?= htmlspecialchars($berita['tanggal']); ?>
        </p>

        <?php if (!empty($berita['gambar'])): ?>
            <div class="mb-4 text-center">
                <img src="../uploads/<?= htmlspecialchars($berita['gambar']); ?>" class="img-fluid rounded shadow">
            </div>
        <?php endif; ?>

        <p class="lead text-dark"><?= nl2br(htmlspecialchars($berita['deskripsi'])); ?></p>
        <div><?= $berita['isi']; ?></div>

        <?php if (!empty($berita['gambar2'])): ?>
            <div class="mb-4 mt-4 text-center">
                <img src="../uploads/<?= htmlspecialchars($berita['gambar2']); ?>" class="img-fluid rounded shadow">
            </div>
        <?php endif; ?>

        <div><?= $berita['isi2']; ?></div>

        <?php if (!empty($videoId)): ?>
            <div class="mt-4 text-center">
                <iframe width="100%" height="315" src="https://www.youtube.com/embed/<?= $videoId; ?>" frameborder="0" allowfullscreen></iframe>
            </div>
        <?php endif; ?>

        <hr>

        <?php if (isset($_GET['msg'])): ?>
            <div class="alert alert-success" role="alert">
                <?= htmlspecialchars($_GET['msg'] === 'polling_deleted' ? 'Polling berhasil dihapus.' : 'Komentar berhasil dihapus.'); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($polling)): ?>
            <div class="polling-section mt-5 p-4">
                <h4 class="mb-4 text-primary"><?= htmlspecialchars($polling['question']); ?></h4>
                <?php foreach ($pollingOptions as $option): 
                    $jumlah = $pollingResults[$option] ?? 0;
                    $persen = $totalVotes > 0 ? round(($jumlah / $totalVotes) * 100) : 0;
                ?>
                    <div class="mb-3">
                        <strong><?= htmlspecialchars($option); ?> (<?= $jumlah; ?> suara)</strong>
                        <div class="progress">
                            <div class="progress-bar bg-success" style="width: <?= $persen; ?>%;">
                                <?= $persen; ?>%
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                <p class="text-muted mt-2"><em>Total suara: <?= $totalVotes; ?></em></p>

                <form method="POST" action="hapus_polling.php" onsubmit="return confirm('Yakin ingin menghapus polling ini?')">
                    <input type="hidden" name="polling_id" value="<?= $polling['id']; ?>">
                    <button type="submit" class="btn btn-danger mt-3">🗑 Hapus Polling</button>
                </form>
            </div>
        <?php endif; ?>

        <div class="comment-section mt-5">
            <h4 class="mb-3 fw-bold">Komentar</h4>
            <div id="commentList">
                <?php
                $komentarQuery = mysqli_query($koneksi, "SELECT * FROM komentar WHERE berita_id = $id ORDER BY tanggal DESC");
                if ($komentarQuery && mysqli_num_rows($komentarQuery) > 0):
                    while ($komentar = mysqli_fetch_assoc($komentarQuery)):
                ?>
                    <div class="mb-3 border-bottom pb-2 d-flex justify-content-between align-items-start">
                        <div>
                            <strong><?= htmlspecialchars($komentar['username']); ?></strong>
                            <small class="text-muted ms-2"><?= htmlspecialchars($komentar['tanggal']); ?></small>
                            <p class="mb-0"><?= nl2br(htmlspecialchars($komentar['isi'])); ?></p>
                        </div>
                        <?php if ($username): ?>
                            <form method="POST" action="hapus_komentar.php" style="margin-left: 10px;" onsubmit="return confirm('Yakin ingin menghapus komentar ini?')">
                                <input type="hidden" name="komentar_id" value="<?= $komentar['id']; ?>">
                                <input type="hidden" name="berita_id" value="<?= $id; ?>">
                                <button type="submit" class="btn btn-sm btn-danger" title="Hapus komentar">🗑 Hapus</button>
                            </form>
                        <?php endif; ?>
                    </div>
                <?php endwhile; else: ?>
                    <p class="text-muted">Belum ada komentar.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
</body>
</html>
