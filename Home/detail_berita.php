<?php
session_start();
include "../db.php";

$flash_message = null;
$username = $_SESSION['username'] ?? null;

// =====================
// HANDLE KOMENTAR DENGAN KATA TERLARANG
// =====================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['isi_komentar']) && isset($_POST['berita_id'])) {
    $isi = $_POST['isi_komentar'];
    $berita_id_post = intval($_POST['berita_id']);

    $badWords = ["sex","seks","bokep","porno","porn","xnxx","xvideos","ml","making love","hentai","jav","memek","kontol","ngentot","colmek","masturbasi","penis","vagina","anal","bdsm","fetish","anjing","babi","tolol","doggystyle"];
    function containsBadWord($text, $badWords){
        if(!$text) return false;
        $text = mb_strtolower($text,'UTF-8');
        foreach($badWords as $w){
            if($w === '' || mb_stripos($text,$w,0,'UTF-8')===false) continue;
            return true;
        }
        return false;
    }

    if (!$username) {
        $flash_message = "Anda harus login untuk mengirim komentar.";
    } elseif (containsBadWord($isi, $badWords)) {
        $flash_message = "Komentar mengandung kata terlarang dan tidak dapat dikirim!";
    } else {
        $isi_safe = mysqli_real_escape_string($koneksi, $isi);
        mysqli_query($koneksi, "INSERT INTO komentar (berita_id, username, isi, tanggal) VALUES ($berita_id_post, '$username', '$isi_safe', NOW())");
        $flash_message = "Komentar berhasil dikirim!";
    }
}

// =====================
// AMBIL DATA BERITA & POLLING
// =====================
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

// Ambil polling
$pollingQuery = mysqli_query($koneksi, "SELECT * FROM polling WHERE berita_id = $id");
$polling = null;
$pollingOptions = [];
if ($pollingQuery && mysqli_num_rows($pollingQuery) > 0) {
    $polling = mysqli_fetch_assoc($pollingQuery);
    $pollingOptions = json_decode($polling['options'], true);
}

// Polling tetap seperti kode asli
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

// Video YouTube
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
        /* ==========================
           ANTI COPY CSS
        ========================== */
        .no-copy {
            user-select: none !important;
            -webkit-user-select: none !important;
            -ms-user-select: none !important;
        }
        .no-copy img, .no-copy p, .no-copy div {
            -webkit-user-drag: none !important;
        }
        .ad-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            margin-top: 30px;
        }
        .ad-slot {
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            text-align: center;
            padding: 15px;
        }
        .ad-slot img { max-width: 100%; height: auto; }
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
        .polling-section h4 i { font-size: 24px; }
        .poll-options .form-check { margin-bottom: 15px; }
        .poll-options .form-check-input { display: none; }
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
            box-shadow: 0 0 10px rgba(13,110,253,0.6);
        }
        button.btn-primary {
            border-radius: 30px;
            padding: 10px 25px;
            font-weight: 600;
            transition: background-color 0.3s ease;
        }
        button.btn-primary:hover { background-color: #084298; }
        .progress { height: 25px; border-radius: 30px; overflow: hidden; background-color: #e9ecef; }
        .progress-bar {
            font-size: 0.9rem;
            font-weight: 600;
            line-height: 25px;
            color: #fff;
            background-color: #0dcaf0;
            transition: width 0.8s ease-in-out;
            text-align: center;
        }
        .alert-success { font-weight: 600; font-size: 1rem; }

        .related-container {
            display: flex;
            margin-top: 20px;
            gap: 20px;
            overflow-x: auto;
            padding-bottom: 12px;
            scrollbar-width: thin;
        }

        .related-container::-webkit-scrollbar {
            height: 8px;
        }

        .related-container::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 10px;
        }

        /* CARD LEBIH BESAR */
        .related-card {
            position: relative;
            width: 300px;             /* ukuran card diperbesar */
            min-width: 300px;
            height: 200px;            /* tinggi card diperbesar */
            border-radius: 12px;
            overflow: hidden;
            flex-shrink: 0;
            background: #000;
            cursor: pointer;

            /* efek halus saat hover */
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .related-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 24px rgba(0,0,0,0.25);
        }

        /* Gambar */
        .related-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Judul */
        .title-box {
            position: absolute;
            bottom: 0;
            width: 100%;
            padding: 10px 12px;
            background: linear-gradient(to top, rgba(0,0,0,0.7), rgba(0,0,0,0.3));
            color: #fff;
            z-index: 2;

            transition: opacity 0.25s ease-in-out;
        }

        .title-box h5 {
            font-size: 16px;
            margin: 0;
            font-weight: 600;
        }

        /* Overlay lebih smooth */
        .overlay {
            position: absolute;
            bottom: 0;
            width: 100%;
            padding: 18px;
            background: rgba(0,0,0,0.85);
            color: #fff;
            z-index: 3;

            opacity: 0;
            transform: translateY(30px) scale(1.02);
            pointer-events: none;

            transition: 
                opacity 0.35s ease,
                transform 0.35s ease;
        }

        .related-card:hover .overlay {
            opacity: 1;
            transform: translateY(0) scale(1);
            pointer-events: auto;
        }

        .related-card:hover .title-box {
            opacity: 0; /* hilangkan judul ketika overlay muncul */
        }

        /* Deskripsi */
        .overlay p {
            margin: 0;
            font-size: 14px;
            line-height: 1.35;
        }

        /* Tanggal */
        .overlay .tgl {
            margin-top: 6px;
            font-size: 12px;
            opacity: 0.8;
        }

        /* Tombol */
        .overlay a {
            margin-top: 10px;
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

        <div class="no-copy">
            <?php if (!empty($berita['gambar'])): ?>
                <img src="../uploads/<?= htmlspecialchars($berita['gambar']); ?>" class="img-fluid rounded mb-4">
            <?php endif; ?>
            <p class="lead"><?= mb_substr(strip_tags($berita['deskripsi']), 0, 1000) . (strlen(strip_tags($berita['deskripsi'])) > 150 ? '...' : ''); ?></p>
            <div><?= $berita['isi']; ?></div>
            <?php if (!empty($berita['gambar2'])): ?>
                <img src="../uploads/<?= htmlspecialchars($berita['gambar2']); ?>" class="img-fluid rounded mt-4">
            <?php endif; ?>
            <div><?= $berita['isi2']; ?></div>
        </div>

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
                            <label class="form-check-label" for="opt<?= $index; ?>"><?= htmlspecialchars($option); ?></label>
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
            <form method="POST" action="" >
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

        <hr>

        <h4>Berita Terkait</h4>
        <div class="related-container">
            <?php
            $relatedQuery = mysqli_query($koneksi, 
                "SELECT id, judul, gambar, deskripsi, tanggal 
                FROM berita 
                WHERE kategori = '" . mysqli_real_escape_string($koneksi, $berita['kategori']) . "' 
                AND id != $id 
                ORDER BY tanggal DESC 
                LIMIT 10"
            );

            if ($relatedQuery && mysqli_num_rows($relatedQuery) > 0):
                while ($related = mysqli_fetch_assoc($relatedQuery)):

                    // ======== FIX &nbsp; dan HTML ENTITY ========
                    $raw = htmlspecialchars_decode($related['deskripsi'], ENT_QUOTES);
                    $clean = str_replace("&nbsp;", " ", $raw);
                    $clean = strip_tags($clean);
                    $limit = mb_substr($clean, 0, 80) . (mb_strlen($clean) > 80 ? "..." : "");
                    $limit = htmlspecialchars($limit, ENT_QUOTES);
            ?>
                <div class="related-card">

                    <img src="../uploads/<?= $related['gambar']; ?>" 
                        alt="<?= htmlspecialchars($related['judul']); ?>">

                    <div class="title-box">
                        <h5><?= htmlspecialchars($related['judul']); ?></h5>
                    </div>

                    <div class="overlay">
                        <p><?= $limit ?></p>
                        <p class="tgl"><?= htmlspecialchars($related['tanggal']); ?></p>
                        <a href="detail_berita.php?id=<?= $related['id']; ?>" 
                        class="btn btn-primary btn-sm">Baca Selengkapnya</a>
                    </div>

                </div>
            <?php 
                endwhile; 
            endif; 
            ?>
        </div>



        <div class="ad-grid mt-5">
            <div class="ad-slot">
                <img src="https://th.bing.com/th/id/OIP.oxseVWF075rFNzQFHbloqgHaEc?cb=iwc2&rs=1&pid=ImgDetMain" alt="Iklan 1">
            </div>
            <div class="ad-slot">
                <img src="https://www.minimeinsights.com/wp-content/uploads/2016/09/Soyou-500x261.jpg" alt="Iklan 2">
            </div>
            <div class="ad-slot">
                <img src="https://th.bing.com/th/id/OIP.oxseVWF075rFNzQFHbloqgHaEc?cb=iwc2&rs=1&pid=ImgDetMain" alt="Iklan 3">
            </div>
            <div class="ad-slot">
                <img src="https://www.minimeinsights.com/wp-content/uploads/2016/09/Soyou-500x261.jpg" alt="Iklan 4">
            </div>
        </div>

    </div>
</div>

<script>
    // Matikan klik kanan
    document.addEventListener('contextmenu', e => e.preventDefault());
    // Matikan shortcut CTRL + C/U/S/A/X/P
    document.onkeydown = function(e) {
        if(e.ctrlKey){ let blocked=['c','u','s','a','x','p']; if(blocked.includes(e.key.toLowerCase())) e.preventDefault(); }
    };
    // Mencegah drag
    document.addEventListener('dragstart', e => e.preventDefault());
</script>

<script>
document.addEventListener('DOMContentLoaded', function(){

    const formPolling = document.querySelector('.poll-options');
    if(formPolling){
        formPolling.addEventListener('submit', function(e){
            e.preventDefault(); // cegah reload

            const formData = new FormData(formPolling);

            fetch('submit_polling.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success' || data.status === 'voted'){
                    // Hapus form
                    formPolling.remove();

                    const pollingSection = document.querySelector('.polling-section');

                    // Bangun HTML hasil polling
                    let html = `<p class="alert alert-success">Anda sudah mengikuti polling ini.</p>`;
                    for(const option in data.results){
                        const jumlah = data.results[option];
                        const total = data.total;
                        const persen = total > 0 ? Math.round((jumlah/total)*100) : 0;
                        html += `
                        <div class="mb-3">
                            <strong>${option} (${jumlah} suara)</strong>
                            <div class="progress">
                                <div class="progress-bar" style="width:${persen}%" aria-valuenow="${persen}" aria-valuemin="0" aria-valuemax="100">
                                    ${persen}%
                                </div>
                            </div>
                        </div>`;
                    }
                    html += `<p class="text-muted"><em>Total suara: ${data.total}</em></p>`;
                    pollingSection.insertAdjacentHTML('beforeend', html);
                } else {
                    alert(data.msg || 'Terjadi kesalahan.');
                }
            })
            .catch(err => console.error(err));
        });
    }

});
</script>


</body>
</html>
