<?php
session_start();
include "../db.php";

$flash_message = null;
if (isset($_SESSION['flash_message'])) {
    $flash_message = $_SESSION['flash_message'];
    unset($_SESSION['flash_message']);
}

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

$hasVoted = false;
if ($polling && isset($_SESSION['polling'][$polling['id']])) {
    $hasVoted = true;
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

$username = $_SESSION['username'] ?? null;

// Ambil daftar komentar yang sudah dilaporkan oleh user ini
$laporanKomentarUser = [];
if ($username) {
    $laporanQuery = mysqli_query($koneksi, "SELECT komentar_id FROM laporan_komentar WHERE username = '$username'");
    while ($row = mysqli_fetch_assoc($laporanQuery)) {
        $laporanKomentarUser[] = $row['komentar_id'];
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?= htmlspecialchars($berita['judul']); ?> - Detail Berita</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" />
        <style>
        .ad-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            margin-top: 30px;
        }
        .ad-slot {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
            padding: 15px;
        }
        .ad-slot img {
            max-width: 100%;
            height: auto;
        }
        .polling-section {
            background-color: #fff;
            box-shadow: 0 4px 8px rgb(0 0 0 / 0.1);
            padding: 20px;
            border-radius: 12px;
            margin-top: 30px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .polling-section h4 {
            font-weight: 700;
            color: #0d6efd;
            border-bottom: 3px solid #0d6efd;
            padding-bottom: 12px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .polling-section h4 i {
            font-size: 24px;
        }

        .poll-options .form-check {
            margin-bottom: 15px;
        }

        .poll-options .form-check-input {
            display: none;
        }

        .poll-options .form-check-label {
            display: block;
            background-color: #f8f9fa;
            border: 2px solid #0d6efd;
            border-radius: 30px;
            padding: 10px 20px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            user-select: none;
        }

        .poll-options .form-check-input:checked + .form-check-label {
            background-color: #0d6efd;
            color: white;
            box-shadow: 0 0 10px rgba(13, 110, 253, 0.6);
        }

        button.btn-primary {
            border-radius: 30px;
            padding: 10px 25px;
            font-weight: 600;
            transition: background-color 0.3s ease;
        }

        button.btn-primary:hover {
            background-color: #084298;
        }

        .progress {
            height: 25px;
            border-radius: 30px;
            overflow: hidden;
            background-color: #e9ecef;
        }

        .progress-bar {
            font-size: 0.9rem;
            font-weight: 600;
            line-height: 25px;
            color: #fff;
            background-color: #0dcaf0;
            transition: width 0.8s ease-in-out;
            text-align: center;
        }

        .alert-success {
            font-weight: 600;
            font-size: 1rem;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <div class="col-lg-8 mx-auto">
        <h1 class="mb-3 fw-bold"><?= htmlspecialchars($berita['judul']); ?></h1>

        <?php if ($flash_message): ?>
            <div class="alert alert-info mt-3">
                <?= htmlspecialchars($flash_message); ?>
            </div>
        <?php endif; ?>

        <p class="text-muted"><strong>Kategori:</strong> <?= htmlspecialchars($berita['kategori']); ?> | <strong>Tanggal:</strong> <?= htmlspecialchars($berita['tanggal']); ?></p>

        <?php if (!empty($berita['gambar'])): ?>
            <img src="../uploads/<?= htmlspecialchars($berita['gambar']); ?>" class="img-fluid rounded mb-4">
        <?php endif; ?>

        <p class="lead"><?= mb_substr(strip_tags($berita['deskripsi']), 0, 1000) . (strlen(strip_tags($berita['deskripsi'])) > 150 ? '...' : ''); ?></p>
        <div><?= $berita['isi']; ?></div>

        <?php if (!empty($berita['gambar2'])): ?>
            <img src="../uploads/<?= htmlspecialchars($berita['gambar2']); ?>" class="img-fluid rounded mt-4">
        <?php endif; ?>

        <div><?= $berita['isi2']; ?></div>

        <?php if (!empty($videoId)): ?>
            <div class="mt-4">
                <iframe width="100%" height="315" src="https://www.youtube.com/embed/<?= $videoId; ?>" frameborder="0" allowfullscreen></iframe>
            </div>
        <?php endif; ?>

        <hr>

        <?php if (!empty($polling)): ?>
            <div class="polling-section">
            <h4>
                <i class="bi bi-bar-chart-fill"></i>
                <?= htmlspecialchars($polling['question']); ?>
            </h4>

            <?php if ($hasVoted): ?>
                <p class="alert alert-success">Anda sudah mengikuti polling ini.</p>
                <?php foreach ($pollingOptions as $option):
                    $jumlah = $pollingResults[$option] ?? 0;
                    $persen = $totalVotes > 0 ? round(($jumlah / $totalVotes) * 100) : 0;
                ?>
                    <div class="mb-3">
                        <strong><?= htmlspecialchars($option); ?> (<?= $jumlah; ?> suara)</strong>
                        <div class="progress">
                            <div class="progress-bar" style="width: <?= $persen; ?>%;" aria-valuenow="<?= $persen; ?>" aria-valuemin="0" aria-valuemax="100">
                                <?= $persen; ?>%
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                <p class="text-muted"><em>Total suara: <?= $totalVotes; ?></em></p>
            <?php else: ?>
                <form method="POST" action="submit_polling.php" class="poll-options">
                    <input type="hidden" name="polling_id" value="<?= $polling['id']; ?>">
                    <?php foreach ($pollingOptions as $index => $option): ?>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="jawaban" id="opt<?= $index; ?>" value="<?= htmlspecialchars($option); ?>" required>
                            <label class="form-check-label" for="opt<?= $index; ?>">
                                <?= htmlspecialchars($option); ?>
                            </label>
                        </div>
                    <?php endforeach; ?>
                    <button type="submit" class="btn btn-primary mt-3">Kirim Jawaban</button>
                </form>
            <?php endif; ?>
        </div>

        <?php endif; ?>

        <hr>

        <h4>Komentar</h4>

        <?php if ($username): ?>
            <form method="POST" action="simpan_komentar.php">
                <input type="hidden" name="berita_id" value="<?= $id; ?>">
                <textarea class="form-control mb-2" name="isi_komentar" rows="3" placeholder="Tulis komentar..." required></textarea>
                <button type="submit" class="btn btn-success">Kirim</button>
            </form>
        <?php else: ?>
            <p><a href="../Login/login.php" target="_parent">Login</a> untuk mengirim komentar.</p>
        <?php endif; ?>

        <hr>

        <?php
        $komentarQuery = mysqli_query($koneksi, "SELECT * FROM komentar WHERE berita_id = $id ORDER BY tanggal DESC");
        if ($komentarQuery && mysqli_num_rows($komentarQuery) > 0):
            while ($komentar = mysqli_fetch_assoc($komentarQuery)):
        ?>
            <div class="mb-3 border-bottom pb-2 d-flex justify-content-between">
                <div>
                    <strong><?= htmlspecialchars($komentar['username']); ?></strong>
                    <small class="text-muted ms-2"><?= htmlspecialchars($komentar['tanggal']); ?></small>
                    <p><?= nl2br(htmlspecialchars($komentar['isi'])); ?></p>
                </div>
                <?php if ($username && !in_array($komentar['id'], $laporanKomentarUser)): ?>
                    <form method="POST" action="laporkan_komentar.php">
                        <input type="hidden" name="komentar_id" value="<?= $komentar['id']; ?>">
                        <input type="hidden" name="berita_id" value="<?= $id; ?>">
                        <button type="submit" class="btn btn-sm btn-outline-danger">🚩 Laporkan</button>
                    </form>
                <?php endif; ?>
            </div>
        <?php
            endwhile;
        else:
            echo "<p class='text-muted'>Belum ada komentar.</p>";
        endif;
        ?>
    </div>
    <br>
    <hr>

    <h4>Berita Terkait</h4>
    <div class="row">
        <?php
        $relatedQuery = mysqli_query($koneksi, "SELECT id, judul, gambar, deskripsi, tanggal FROM berita WHERE kategori = '" . mysqli_real_escape_string($koneksi, $berita['kategori']) . "' AND id != $id ORDER BY tanggal DESC LIMIT 4");
        if ($relatedQuery && mysqli_num_rows($relatedQuery) > 0):
            while ($related = mysqli_fetch_assoc($relatedQuery)):
        ?>
            <div class="col-md-3">
                <div class="card mb-3 shadow-sm">
                    <?php if (!empty($related['gambar'])): ?>
                        <img src="../uploads/<?= htmlspecialchars($related['gambar']); ?>" class="card-img-top" alt="<?= htmlspecialchars($related['judul']); ?>">
                    <?php else: ?>
                        <img src="https://via.placeholder.com/150x100?text=No+Image" class="card-img-top" alt="No Image">
                    <?php endif; ?>
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($related['judul']); ?></h5>
                        <p class="card-text text-truncate"><?= htmlspecialchars(mb_substr(strip_tags($related['deskripsi']), 0, 100)) . (strlen(strip_tags($related['deskripsi'])) > 100 ? '...' : ''); ?></p>
                        <p class="card-text"><small class="text-muted"><?= htmlspecialchars($related['tanggal']); ?></small></p>
                        <a href="detail_berita.php?id=<?= $related['id']; ?>" class="btn btn-primary btn-sm">Baca Selengkapnya</a>
                    </div>
                </div>
            </div>
        <?php
            endwhile;
        else:
            echo "<p class='text-muted'>Tidak ada berita terkait ditemukan.</p>";
        endif;
        ?>
    </div>

    <br>

</div>
<div class="ad-grid mt-5">
        <div class="ad-slot">
            <h6 class="mb-2"></h6>
            <img src="https://th.bing.com/th/id/OIP.oxseVWF075rFNzQFHbloqgHaEc?cb=iwc2&rs=1&pid=ImgDetMain" alt="Iklan 1">
        </div>
        <div class="ad-slot">
            <h6 class="mb-2"></h6>
            <img src="https://www.minimeinsights.com/wp-content/uploads/2016/09/Soyou-500x261.jpg" alt="Iklan 2">
        </div>
        <div class="ad-slot">
            <h6 class="mb-2"></h6>
            <img src="https://th.bing.com/th/id/OIP.oxseVWF075rFNzQFHbloqgHaEc?cb=iwc2&rs=1&pid=ImgDetMain" alt="Iklan 3">
        </div>
        <div class="ad-slot">
            <h6 class="mb-2"></h6>
            <img src="https://www.minimeinsights.com/wp-content/uploads/2016/09/Soyou-500x261.jpg" alt="Iklan 4">
        </div>
    </div>
</body>
</html>
