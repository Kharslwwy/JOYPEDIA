<?php
include "../db.php"; // Koneksi ke database

// Menangani kategori yang dipilih
$kategoriDipilih = isset($_GET['kategori']) ? $_GET['kategori'] : '';

// Daftar kategori
$kategoriList = ['Teknologi', 'Pendidikan', 'Olahraga', 'Hiburan', 'Seni', 'Kesehatan', 'Game'];

// Menangani pencarian keyword (langsung ambil keyword jika ada)
$keyword = "";
if (!empty($_POST['keyword'])) {
    $keyword = mysqli_real_escape_string($koneksi, $_POST['keyword']);
}

// Query
$sql = "SELECT * FROM berita WHERE 1=1 ";
if ($kategoriDipilih !== '') {
    $kategoriEsc = mysqli_real_escape_string($koneksi, $kategoriDipilih);
    $sql .= " AND kategori = '$kategoriEsc' ";
}
if (!empty($keyword)) {
    $sql .= " AND (judul LIKE '%$keyword%' OR tanggal LIKE '%$keyword%' OR deskripsi LIKE '%$keyword%') ";
}
$sql .= " ORDER BY id DESC LIMIT 20";
$query = mysqli_query($koneksi, $sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Home - Berita Terbaru</title>
    <style>
        :root {
            --primary: #2575fc;
            --primary-dark: #1a54cc;
            --bg: #f5f8ff;
            --card-bg: #fff;
            --shadow: 0 6px 18px rgba(0,0,0,0.08);
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }
        
        /* === Layout Flex agar footer selalu di bawah === */
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, sans-serif;
            background: var(--bg);
            color: #222;
        }

        .container {
            flex: 1;
            max-width: 1080px;
            margin: 40px auto;
            padding: 0 20px;
        }

        /* Search Box */
        .search-box {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }
        .search-box input {
            width: 100%;
            max-width: 500px;
            padding: 12px 16px;
            border-radius: 50px;
            border: 2px solid #ddd;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        .search-box input:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 8px rgba(37, 117, 252, 0.3);
        }

        /* Kategori */
        .kategori-btn-group {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 25px;
        }
        .kategori-btn {
            padding: 10px 18px;
            border-radius: 30px;
            border: none;
            font-weight: 600;
            background: #fff;
            color: var(--primary);
            cursor: pointer;
            transition: 0.3s ease;
            box-shadow: var(--shadow);
        }
        .kategori-btn:hover,
        .kategori-btn.active {
            background: var(--primary);
            color: white;
            transform: translateY(-2px);
        }

        /* Grid berita */
        .news-scroll {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 28px;
            margin-top: 10px;
        }

        /* Card berita */
        .news-item {
            display: flex;
            flex-direction: column;
            background: var(--card-bg);
            border-radius: 16px;
            box-shadow: var(--shadow);
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .news-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.12);
        }
        .image-wrapper {
            height: 200px;
            overflow: hidden;
            position: relative;
        }
        .news-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s ease;
        }
        .news-item:hover .news-image {
            transform: scale(1.1);
        }

        .news-content {
            padding: 18px;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }
        .news-content h4 {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--primary);
        }
        .news-content p {
            font-size: 0.95rem;
            color: #444;
            margin: 0;
        }
        .category {
            font-size: 0.85rem;
            font-weight: 600;
            color: #666;
        }
        .description {
            margin-top: 5px;
            flex-grow: 1;
            color: #333;
        }

        .read-more-btn {
            margin-top: 12px;
            padding: 10px 15px;
            border-radius: 25px;
            border: none;
            background: var(--primary);
            color: white;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        .read-more-btn:hover {
            background: var(--primary-dark);
        }

        footer {
            text-align: center;
            padding: 20px;
            color: #666;
            font-size: 0.9rem;
            background: #eef1ff;
            margin-top: auto;
        }
    </style>
</head>
<body>

<div class="container">
    <!-- Search Bar -->
    <form class="search-box" method="POST">
        <input type="text" name="keyword" placeholder="Cari berita..." value="<?= htmlspecialchars($keyword) ?>">
    </form>

    <!-- Kategori -->
    <div class="kategori-btn-group">
        <a href="?" class="kategori-btn <?= empty($kategoriDipilih) ? 'active' : '' ?>">Semua</a>
        <?php foreach ($kategoriList as $kat): ?>
            <a href="?kategori=<?= urlencode($kat) ?>" class="kategori-btn <?= $kategoriDipilih === $kat ? 'active' : '' ?>">
                <?= htmlspecialchars($kat) ?>
            </a>
        <?php endforeach; ?>
    </div>

    <?php if (!empty($kategoriDipilih)): ?>
        <h2 style="text-align:center; margin-bottom: 10px; color: var(--primary);">
            Berita Terbaru <?= htmlspecialchars($kategoriDipilih) ?>
        </h2>
        <p style="text-align:center; color:#555; margin-bottom: 25px;">Temukan berita hangat yang sedang menjadi perbincangan.</p>
    <?php endif; ?>

    <div class="news-scroll">
        <?php if(mysqli_num_rows($query) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($query)): ?>
                <div class="news-item">
                    <div class="image-wrapper">
                        <?php
                        $gambarPath = "../uploads/" . htmlspecialchars($row['gambar_sampul']);
                        if (!file_exists($gambarPath) || empty($row['gambar_sampul'])) {
                            $gambarPath = 'images/default.jpg';
                        }
                        ?>
                        <img src="<?= $gambarPath ?>" alt="Gambar Berita" class="news-image" loading="lazy">
                    </div>
                    <div class="news-content">
                        <h4><?= htmlspecialchars($row['judul']) ?></h4>
                        <p class="category"><?= htmlspecialchars($row['kategori']) ?> • <?= htmlspecialchars($row['tanggal']) ?></p>
                        <p class="description"><?= mb_substr(strip_tags($row['deskripsi']), 0, 120) . (strlen(strip_tags($row['deskripsi'])) > 120 ? '...' : ''); ?></p>
                        <button class="read-more-btn" onclick="location.href='detail_berita.php?id=<?= $row['id'] ?>'">
                            Baca Selengkapnya
                        </button>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p style="text-align:center; color:#666; font-style: italic;">
                Tidak ada berita ditemukan.
            </p>
        <?php endif; ?>
    </div>
</div>

<footer>© <?= date('Y') ?> Portal Berita Interaktif</footer>
</body>
</html>
