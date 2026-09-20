<?php
include "../db.php";

$kategoriDipilih = isset($_GET['kategori']) ? $_GET['kategori'] : 'Semua';
$keyword = "";
if (isset($_POST['cari'])) {
    $keyword = mysqli_real_escape_string($koneksi, $_POST['keyword']);
}

$sql = "SELECT * FROM berita WHERE 1=1 ";
if ($kategoriDipilih !== 'Semua') {
    $kategoriEsc = mysqli_real_escape_string($koneksi, $kategoriDipilih);
    $sql .= " AND kategori = '$kategoriEsc' ";
}
if (!empty($keyword)) {
    $sql .= " AND (judul LIKE '%$keyword%' OR tanggal LIKE '%$keyword%' OR deskripsi LIKE '%$keyword%') ";
}
$sql .= " ORDER BY id DESC LIMIT 20";
$query = mysqli_query($koneksi, $sql);
$kategoriList = ['Teknologi', 'Pendidikan', 'Olahraga', 'Hiburan', 'Seni', 'Kesehatan', 'Game'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Home - Berita Terbaru</title>
<style>
  /* Reset & basic */
  * {
    box-sizing: border-box;
  }
  body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    margin: 0; padding: 0;
    background: #f5f8ff;
    color: #222;
    line-height: 1.5;
  }
  a {
    text-decoration: none;
  }

  /* Hero Section */
  .hero {
    background: linear-gradient(135deg, #2575fc, #6a8aff);
    color: #fff;
    padding: 60px 20px;
    text-align: center;
    position: sticky;
    top: 0;
    z-index: 100;
    transition: opacity 0.5s ease;
  }
  .hero h1 {
    font-size: 2.8rem;
    margin-bottom: 0.2em;
  }
  .hero p {
    font-size: 1.1rem;
    font-weight: 500;
    margin-bottom: 1.2em;
  }

  /* Search Form */
  .search-form {
    max-width: 450px;
    margin: 0 auto;
    display: flex;
    gap: 10px;
  }
  .search-form input[type="text"] {
    flex: 1;
    padding: 10px 15px;
    font-size: 1rem;
    border: none;
    border-radius: 30px;
    outline: none;
  }
  .search-form button {
    background: #fff;
    color: #2575fc;
    border: none;
    padding: 10px 20px;
    border-radius: 30px;
    font-weight: 600;
    cursor: pointer;
    transition: background-color 0.3s ease;
  }
  .search-form button:hover {
    background: #d7e3ff;
  }

  /* Container */
  .container {
    max-width: 1024px;
    margin: 40px auto 80px;
    padding: 0 15px;
  }

  /* Kategori Button Group */
  .kategori-btn-group {
    text-align: center;
    margin-bottom: 30px;
  }
  .kategori-btn {
    display: inline-block;
    padding: 10px 22px;
    margin: 5px 8px;
    border-radius: 30px;
    border: 2px solid #2575fc;
    background-color: white;
    color: #2575fc;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    user-select: none;
  }
  .kategori-btn.active,
  .kategori-btn:hover {
    background-color: #2575fc;
    color: white;
    box-shadow: 0 4px 12px rgb(37 117 252 / 0.35);
  }

  /* Header */
  .header h2 {
    font-weight: 700;
    font-size: 2rem;
    margin-bottom: 5px;
    color: #222;
  }
  .header p {
    font-weight: 500;
    color: #555;
    margin-bottom: 25px;
  }

  /* News Scroll (list berita) */
  .news-scroll {
    display: grid;
    grid-template-columns: repeat(auto-fit,minmax(320px,1fr));
    gap: 28px;
  }

  /* Card Berita */
  .news-item {
    background: white;
    border-radius: 12px;
    box-shadow: 0 6px 15px rgb(0 0 0 / 0.08);
    overflow: hidden;
    display: flex;
    flex-direction: column;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
  }
  .news-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 14px 30px rgb(0 0 0 / 0.12);
  }

  /* Gambar Berita */
  .image-wrapper {
    flex-shrink: 0;
    height: 180px;
    overflow: hidden;
  }
  .news-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: scale 0.3s ease;
  }
  .news-item:hover .news-image {
    scale: 1.05;
  }

  /* Konten Berita */
  .news-content {
    padding: 20px 22px 30px;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
  }
  .news-content h4 {
    margin: 0 0 8px;
    font-size: 1.3rem;
    font-weight: 700;
    color: #2575fc;
  }
  .news-content p {
    margin: 4px 0;
    color: #444;
    font-size: 0.95rem;
  }
  .news-content p.category {
    font-weight: 600;
    color: #6a8aff;
    margin-top: 10px;
    font-style: normal;
  }
  .news-content p.description {
    margin-top: 12px;
    color: #333;
    flex-grow: 1;
  }
  .news-content p[style*="italic"] {
    font-style: italic;
    color: #888;
    margin-bottom: 0;
  }

  /* Tombol Baca, Edit, Hapus */
  .btn-group {
    margin-top: 18px;
    display: flex;
    gap: 10px;
  }
  .read-more-btn {
    flex: 1;
    padding: 10px 12px;
    border: none;
    border-radius: 30px;
    font-weight: 600;
    background-color: #2575fc;
    color: white;
    cursor: pointer;
    transition: background-color 0.3s ease;
    user-select: none;
  }
  .read-more-btn:hover {
    background-color: #1a54cc;
  }
  form.read-more-btn {
    margin: 0;
  }

  /* Footer */
  footer {
    text-align: center;
    padding: 20px 15px;
    font-size: 0.9rem;
    color: #666;
    background-color: #f0f2ff;
  }

  /* Responsive */
  @media (max-width: 480px) {
    .hero h1 {
      font-size: 2rem;
    }
    .hero p {
      font-size: 1rem;
    }
    .news-content h4 {
      font-size: 1.1rem;
    }
  }
</style>
</head>
<body>

<div class="hero" id="hero">
  <div class="hero-content">
    <h1>Selamat Datang di JOYPEDIA</h1>
    <p>Dapatkan informasi terkini dan terpercaya dari berbagai kategori.</p>
  </div>

  <form class="search-form" method="POST" action="?kategori=<?= htmlspecialchars($kategoriDipilih) ?>">
    <input type="text" name="keyword" placeholder="Cari Berita" value="<?= htmlspecialchars($keyword) ?>" />
    <button type="submit" name="cari">Search</button>
  </form>
</div>

<div class="container">

  <div class="kategori-btn-group">
    <a href="?" class="kategori-btn <?= $kategoriDipilih === 'Semua' ? 'active' : '' ?>">Semua</a>
    <?php foreach ($kategoriList as $kat): ?>
      <a href="?kategori=<?= urlencode($kat) ?>" class="kategori-btn <?= $kategoriDipilih === $kat ? 'active' : '' ?>">
        <?= htmlspecialchars($kat) ?>
      </a>
    <?php endforeach; ?>
  </div>

  <div class="header">
    <h2>Berita Terbaru <?= ($kategoriDipilih !== 'Semua') ? "Kategori: " . htmlspecialchars($kategoriDipilih) : "" ?></h2>
    <p>Temukan berita hangat yang sedang menjadi perbincangan.</p>
  </div>

  <div class="news-scroll" id="newsScroll">
    <?php if(mysqli_num_rows($query) > 0): ?>
      <?php while ($row = mysqli_fetch_assoc($query)): ?>
      <div class="news-item" data-category="<?= htmlspecialchars($row['kategori']) ?>">
        <div class="image-wrapper">
          <img src="../uploads/<?= htmlspecialchars($row['gambar_sampul']) ?>" alt="Gambar Berita" class="news-image" loading="lazy" />
        </div>
        <div class="news-content">
          <h4><?= htmlspecialchars($row['judul']) ?></h4>
          <p style="font-style: italic; margin-bottom: -5px;">Tanggal: <?= htmlspecialchars($row['tanggal']); ?></p>
          <p class="category">Kategori: <?= htmlspecialchars($row['kategori']) ?></p>
          <p class="description"><?= htmlspecialchars($row['deskripsi']) ?></p>

          <div class="btn-group">
            <button class="read-more-btn" onclick="location.href='detail_berita.php?id=<?= $row['id'] ?>'">Baca Selengkapnya</button>
            <button class="read-more-btn" onclick="location.href='edit_berita.php?id=<?= $row['id'] ?>'">Edit</button>
            <form method="POST" action="hapus.php" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus berita ini?');">
              <input type="hidden" name="id" value="<?= $row['id'] ?>" />
              <button type="submit" class="read-more-btn">Hapus</button>
            </form>
          </div>
        </div>
      </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p style="text-align:center; color:#666; font-style: italic;">
        Tidak ada berita yang ditemukan untuk kata kunci "<strong><?= htmlspecialchars($keyword) ?></strong>" di kategori "<strong><?= htmlspecialchars($kategoriDipilih) ?></strong>".
      </p>
    <?php endif; ?>
  </div>

</div>

<footer>
  <p>&copy; 2024 JOYPEDIA Website. All rights reserved.</p>
</footer>

<script>
  window.addEventListener('scroll', function () {
    const hero = document.getElementById('hero');
    const scrollY = window.scrollY;
    const maxScroll = window.innerHeight;
    let opacity = 1 - (scrollY / maxScroll);
    if (opacity < 0) opacity = 0;
    hero.style.opacity = opacity;
  });
</script>

</body>
</html>
