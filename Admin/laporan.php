<?php
session_start();
include "../db.php";

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../Login/login.php");
    exit();
}

// Ambil laporan komentar
$queryKomentar = mysqli_query($koneksi, "
    SELECT 
        lc.id AS laporan_id, lc.komentar_id, lc.username AS pelapor, lc.status, lc.tanggal_lapor,
        k.isi AS isi_komentar, k.username AS komentator, k.tanggal AS tanggal_komentar,
        b.judul AS judul_berita
    FROM laporan_komentar lc
    LEFT JOIN komentar k ON lc.komentar_id = k.id
    LEFT JOIN berita b ON k.berita_id = b.id
    ORDER BY lc.tanggal_lapor DESC
");

// Ambil laporan chat
$queryChat = mysqli_query($koneksi, "
    SELECT * FROM laporan_chat ORDER BY waktu_lapor DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Laporan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <style>
        body {
            background: #f0f4f8;
            min-height: 100vh;
            padding-top: 40px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .card {
            box-shadow: 0 8px 20px rgb(0 0 0 / 0.1);
            border-radius: 12px;
        }
        .table thead tr {
            background: linear-gradient(90deg, #4facfe, #00f2fe);
            color: white;
            position: sticky;
            top: 0;
            z-index: 10;
        }
        .table tbody tr:hover {
            background-color: #dbefff;
            cursor: pointer;
        }
        .badge {
            font-weight: 600;
            font-size: 0.9rem;
            cursor: default;
        }
        .table-responsive {
            max-height: 65vh;
            overflow-y: auto;
        }
        .header-title {
            font-weight: 700;
            color: #004f9e;
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
        }
        .header-title i {
            font-size: 2.4rem;
            color: #007bff;
            animation: bounce 2s infinite;
        }
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
    </style>
</head>
<body>

<div class="container">
    <h1 class="header-title">
        <i class="bi bi-flag-fill"></i> Laporan
    </h1>

    <ul class="nav nav-tabs" id="laporanTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="komentar-tab" data-bs-toggle="tab" data-bs-target="#komentar" type="button" role="tab">
                Laporan Komentar
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="chat-tab" data-bs-toggle="tab" data-bs-target="#chat" type="button" role="tab">
                Laporan Chat
            </button>
        </li>
    </ul>

    <div class="tab-content card p-3">
        <!-- Tab Komentar -->
        <div class="tab-pane fade show active" id="komentar" role="tabpanel">
            <?php if (!$queryKomentar || mysqli_num_rows($queryKomentar) === 0): ?>
                <p class="text-center text-secondary mt-3">Belum ada laporan komentar.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered align-middle">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Judul Berita</th>
                                <th>Isi Komentar</th>
                                <th>Komentator</th>
                                <th>Pelapor</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($laporan = mysqli_fetch_assoc($queryKomentar)): ?>
                                <?php
                                    $status = $laporan['status'] ?? 'pending';
                                    $badgeClass = $status === 'pending' ? 'warning' : ($status === 'ditindaklanjuti' ? 'info' : 'success');
                                ?>
                                <tr>
                                    <td><?= htmlspecialchars($laporan['laporan_id']); ?></td>
                                    <td><?= htmlspecialchars($laporan['judul_berita'] ?? '-'); ?></td>
                                    <td><?= nl2br(htmlspecialchars($laporan['isi_komentar'] ?? '-')); ?></td>
                                    <td><?= htmlspecialchars($laporan['komentator'] ?? '-'); ?></td>
                                    <td><?= htmlspecialchars($laporan['pelapor']); ?></td>
                                    <td><span class="badge bg-<?= $badgeClass; ?>"><?= ucfirst($status); ?></span></td>
                                    <td><?= htmlspecialchars($laporan['tanggal_lapor']); ?></td>
                                    <td>
                                        <?php if ($status !== 'selesai' && !empty($laporan['komentar_id'])): ?>
                                            <form method="POST" action="hapus_komentar.php" onsubmit="return confirm('Hapus komentar ini?');">
                                                <input type="hidden" name="laporan_id" value="<?= $laporan['laporan_id']; ?>">
                                                <input type="hidden" name="komentar_id" value="<?= $laporan['komentar_id']; ?>">
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="bi bi-trash-fill"></i> Hapus
                                                </button>
                                            </form>
                                        <?php else: ?>
                                            <span class="text-success">✅ Selesai</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

        <!-- Tab Chat -->
        <div class="tab-pane fade" id="chat" role="tabpanel">
            <?php if (!$queryChat || mysqli_num_rows($queryChat) === 0): ?>
                <p class="text-center text-secondary mt-3">Belum ada laporan chat.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered align-middle">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Message ID</th>
                                <th>Pelapor</th>
                                <th>Alasan</th>
                                <th>Waktu</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($chat = mysqli_fetch_assoc($queryChat)): ?>
                                <tr>
                                    <td><?= htmlspecialchars($chat['id']); ?></td>
                                    <td><?= htmlspecialchars($chat['message_id']); ?></td>
                                    <td><?= htmlspecialchars($chat['pelapor']); ?></td>
                                    <td><?= nl2br(htmlspecialchars($chat['alasan'])); ?></td>
                                    <td><?= htmlspecialchars($chat['waktu_lapor']); ?></td>
                                    <td>
                                        <form method="POST" action="hapus_chat.php" onsubmit="return confirm('Hapus chat ini?');">
                                            <input type="hidden" name="laporan_id" value="<?= $chat['id']; ?>">
                                            <input type="hidden" name="message_id" value="<?= $chat['message_id']; ?>">
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="bi bi-trash-fill"></i> Hapus Chat
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
