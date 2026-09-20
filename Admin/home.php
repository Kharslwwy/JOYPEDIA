<?php
include "../db.php";

if (isset($_GET['ajax'])) {
    $keyword = mysqli_real_escape_string($koneksi, $_GET['q'] ?? '');
    $sql = "SELECT * FROM berita WHERE judul LIKE '%$keyword%' OR deskripsi LIKE '%$keyword%' ORDER BY id DESC";
    $query = mysqli_query($koneksi, $sql);

    if (mysqli_num_rows($query) > 0) {
        while ($row = mysqli_fetch_assoc($query)) {
            ?>
            <div class="news-item" data-category="<?= htmlspecialchars($row['kategori']) ?>">
                <div class="image-wrapper">
                    <img src="../uploads/<?= htmlspecialchars($row['gambar']) ?>" alt="Gambar Berita" class="news-image" />
                </div>
                <div class="news-content">
                    <h4><?= htmlspecialchars($row['judul']) ?></h4>
                    <p style="font-style: italic; margin-bottom: 5px;">Tanggal: <?= htmlspecialchars($row['tanggal']); ?></p>
                    <p class="category" style="margin-bottom: 5px;">Kategori: <?= htmlspecialchars($row['kategori']) ?></p>
                    <p class="description">
                        <?= mb_substr(strip_tags($row['deskripsi']), 0, 150) . (strlen(strip_tags($row['deskripsi'])) > 150 ? '...' : ''); ?>
                    </p>
                    <div class="btn-group">
                        <a href="detail_berita.php?id=<?= $row['id'] ?>" class="read-more-btn">Baca Selengkapnya</a>
                    </div>
                </div>
            </div>
            <?php
        }
    } else {
        echo "<p style='text-align:center; font-style:italic;'>Tidak ada hasil untuk <strong>$keyword</strong>.</p>";

    }

    exit();
}

// Daftar kategori yang tersedia
$kategoriList = ['Teknologi','Nasional','Internasional','Ekonomi', 'Pendidikan', 'Olahraga', 'Hiburan', 'Seni', 'Kesehatan', 'Game'];

// Ambil kategori yang dipilih dari GET, default Semua
$kategoriDipilih = isset($_GET['kategori']) ? $_GET['kategori'] : 'Semua';

// Ambil keyword pencarian dari POST jika ada
$keyword = "";
if (isset($_POST['cari'])) {
    $keyword = mysqli_real_escape_string($koneksi, $_POST['keyword']);
}

// Bangun query berita
$sql = "SELECT * FROM berita WHERE 1=1 ";

// Filter kategori jika bukan Semua
if ($kategoriDipilih !== 'Semua' && in_array($kategoriDipilih, $kategoriList)) {
    $kategoriEsc = mysqli_real_escape_string($koneksi, $kategoriDipilih);
    $sql .= " AND kategori = '$kategoriEsc' ";
}

// Filter keyword jika ada
if (!empty($keyword)) {
    $sql .= " AND (judul LIKE '%$keyword%' OR tanggal LIKE '%$keyword%' OR deskripsi LIKE '%$keyword%') ";
}

$sql .= " ORDER BY id DESC ";

// Batasi 6 berita hanya jika kategori Semua dan keyword kosong
if (empty($keyword) && $kategoriDipilih === 'Semua') {
    $sql .= "LIMIT 12";
}

$query = mysqli_query($koneksi, $sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Home - Berita Terbaru</title>
    <style>
        /* Reset & base */
        body {
            font-family: Arial, sans-serif;
            background: #f7f7f7;
            margin: 0;
            overflow-x: hidden;
        }
        h1, h2, p {
            margin: 0;
        }

        /* Hero Section */
        .hero {
            position: relative;
            width: 100dvw;
            height: 50dvh;
            background: url('../BG.jpg') no-repeat center center/cover;
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            flex-direction: column;
            padding: 20px;
            margin-bottom: 10px;
        }
        .hero .hero-content {
            background: rgba(0, 0, 0, 0.6);
            padding: 20px 40px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .hero h1 {
            font-size: 3rem;
            margin-bottom: 10px;
        }
        .hero p {
            font-size: 1.5rem;
        }

        /* Search Form */
        .search-form {
            display: flex;
            gap: 8px;
            justify-content: center;
            margin-top: 10px;
        }
        .search-form input[type="text"] {
            padding: 8px 12px;
            font-size: 1rem;
            border-radius: 4px;
            border: 1px solid #ccc;
            width: 250px;
        }
        .search-form button {
            padding: 8px 16px;
            font-size: 1rem;
            border-radius: 4px;
            border: none;
            background-color: #2575fc;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .search-form button:hover {
            background-color: #1a54d8;
        }

        /* Kategori Button Group */
        .kategori-btn-group {
            text-align: center;
            margin: 20px 0;
        }
        .kategori-btn {
            padding: 8px 16px;
            margin: 5px;
            border-radius: 30px;
            border: 1.5px solid #2575fc;
            background-color: white;
            color: #2575fc;
            cursor: pointer;
            font-weight: bold;
            transition: all 0.3s ease;
            display: inline-block;
            text-decoration: none;
        }
        .kategori-btn.active,
        .kategori-btn:hover {
            background-color: #2575fc;
            color: white;
        }

        /* Container */
        .container {
            max-width: 1300px;
            overflow-x: hidden;
            overflow-y: hidden;
            margin: 30px auto;
            padding: 0 15px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h2 {
            margin-bottom: 8px;
            font-size: 2.3rem;
        }
        .header p {
            margin-bottom: 0;
        }

        /* News Grid */
        .news-scroll {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            opacity: 0;
            transform: translateY(50px);
            transition: all 0.6s ease-out;
        }
        .news-scroll.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* News Item Card */
        .news-item {
            background: #fff;
            border-radius: 10px;
            overflow: visible;  /* supaya konten panjang tidak terpotong */
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            max-width: 350px;
            width: 100%;
            justify-self: center;
            height: auto;  /* supaya tinggi mengikuti isi */
            display: flex;
            flex-direction: column;
        }
        .news-item:hover {
            transform: scale(1.05);
            box-shadow: 0 16px 32px rgba(0,0,0,0.3);
        }

        /* Image */
        .image-wrapper {
            height: 200px;
            overflow: hidden;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }
        .news-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        /* Content */
        .news-content {
            padding: 15px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }
        .news-content h4 {
            margin-bottom: 10px;
            font-size: 1.2rem;
            line-height: 1.3;
        }
        .news-content p.category {
            font-style: italic;
            color: #555;
            margin-bottom: 10px;
        }
        .news-content p.description {
            margin-bottom: 15px;
            max-height: none;
            overflow: visible;
            color: #333;
            white-space: normal;
            flex-grow: 1;
            word-wrap: break-word;
        }

        /* Tombol grup */
        .btn-group {
            display: flex;
            gap: 10px; /* jarak antar tombol */
            margin-top: auto; /* supaya tombol tetap di bawah */
            flex-wrap: wrap; /* supaya tombol tidak tumpuk kalau sempit */
        }

        /* Button umum dengan transisi keren */
        .read-more-btn {
            padding: 10px 18px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            white-space: nowrap;
            border: none;

            transition: 
                background-color 0.35s cubic-bezier(0.4, 0, 0.2, 1), 
                box-shadow 0.35s cubic-bezier(0.4, 0, 0.2, 1), 
                transform 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .read-more-btn.read {
            background-color: #2575fc;
            color: #fff;
            box-shadow: 0 6px 15px rgba(37, 117, 252, 0.35);
        }
        .read-more-btn.read:hover {
            background-color: #1a54d8;
            box-shadow: 0 10px 25px rgba(26, 84, 216, 0.6);
            transform: scale(1.07) rotateZ(-1deg);
        }

        .read-more-btn.edit {
            background-color: #28a745;
            color: #fff;
            box-shadow: 0 6px 15px rgba(40, 167, 69, 0.35);
        }
        .read-more-btn.edit:hover {
            background-color: #218838;
            box-shadow: 0 10px 25px rgba(33, 136, 56, 0.6);
            transform: scale(1.07) rotateZ(1deg);
        }

        .read-more-btn.delete {
            background-color: #dc3545;
            color: #fff;
            box-shadow: 0 6px 15px rgba(220, 53, 69, 0.35);
        }
        .read-more-btn.delete:hover {
            background-color: #b02a37;
            box-shadow: 0 10px 25px rgba(176, 42, 55, 0.6);
            transform: scale(1.07) rotateZ(-2deg);
        }

         /* Horizontal Grid */
        .container-horizontal {
        position: relative;
        padding: 20px;
        }

        .header-h {
        text-align: center;
        margin-bottom: 15px;
        }

        .news-scroll-horizontal {
        display: flex;
        gap: 15px;
        overflow-x: auto;
        scroll-behavior: smooth;
        scrollbar-width: none; /* sembunyikan scrollbar */
        }
        .news-scroll-horizontal::-webkit-scrollbar {
        display: none;
        }

       /* Card dasar */
        .news-card {
        position: relative;
        width: 650px;
        height: 320px;
        flex: 0 0 auto; /* supaya tetap bisa scroll horizontal */
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }

        /* Gambar cover */
        .news-card img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
         transition: transform 0.6s ease; 
        }

        .news-card img:hover {
           transform: scale(1.1);
        }

        /* Overlay judul, deskripsi, read more */
        .news-content-horizontal {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        background: linear-gradient(to top, rgba(0,0,0,0.7), transparent);
        color: #fff;
        padding: 15px;
        transition: all 0.4s ease;
        max-height: 70px;  /* default hanya judul kelihatan */
        overflow: hidden;
        }

        /* Judul */
        .news-content-horizontal h4 {
        margin: 0;
        font-size: 20px;
        }

        /* Deskripsi + read more disembunyikan dulu */
        .news-content-horizontal .desc,
        .news-content-horizontal .read-more {
        opacity: 0;
        transform: translateY(20px);
        transition: all 0.4s ease;
        margin-top: 8px;
        display: block;
        }

        /* Efek hover: overlay naik */
        .news-card:hover .news-content-horizontal {
        max-height: 100%;
        background: rgba(0,0,0,0.75);
        }

        /* Munculkan deskripsi + read more */
        .news-card:hover .desc,
        .news-card:hover .read-more {
        opacity: 1;
        transform: translateY(0);
        }

        /* Tombol read more */
        .news-content-horizontal .read-more {
        background: #ff6600;
        color: #fff;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 14px;
        text-decoration: none;
        transition: background 0.3s;
        }

        .news-content-horizontal .read-more:hover {
        background: #e55a00;
        }

        .read-more {
        font-size: 20px;
        color: #007BFF;
        text-decoration: none;
        margin-top: auto;
        }
        .read-more:hover {
        text-decoration: underline;
        }

        /* Tombol kiri-kanan */
        .scroll-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: #333;
        color: #fff;
        border: none;
        padding: 10px;
        border-radius: 50%;
        cursor: pointer;
        z-index: 10;
        }
        .scroll-btn:hover {
        background: #555;
        }
        .scroll-btn.prev { left: 5px; }
        .scroll-btn.next { right: 5px; }

        /* Responsive tweaks */
        @media (max-width: 600px) {
            .hero h1 {
                font-size: 2rem;
            }
            .hero p {
                font-size: 1.2rem;
            }
            .search-form input[type="text"] {
                width: 180px;
            }
        }

        /* Tablet */
        @media (max-width: 1024px) {
        .news-card {
            width: 450px;
            height: 280px;
        }
        }

        /* Mobile landscape */
        @media (max-width: 768px) {
        .news-card {
            width: 100%;
            height: 250px;
        }
        .news-scroll-horizontal {
            gap: 10px;
        }
        }

        /* Mobile kecil */
        @media (max-width: 480px) {
        .news-card {
            width: 100%;
            height: auto;   
        }
        .news-content-horizontal {
            max-height: none;
        }
        }

        /* Tablet / layar sedang */
        @media (max-width: 1024px) {
            #newsScroll {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        /* Mobile landscape */
        @media (max-width: 768px) {
            #newsScroll {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        /* Mobile kecil */
        @media (max-width: 480px) {
            #newsScroll {
                grid-template-columns: 1fr;
            }
        }

        /* Responsive tweaks */
        @media (max-width: 768px) {
            .container {
                padding: 10px;
            }

            .hero h1 {
                font-size: 24px;
            }

            .search-form input[type="text"] {
                width: 100%;
                max-width: 300px;
            }

        }

    </style>
</head>
<body>

    <!-- Hero Section -->
    <div class="hero" id="hero">
        <div class="hero-content">
            <h1>Selamat Datang di JOYPEDIA</h1>
            <p>Dapatkan informasi terkini dan terpercaya dari berbagai kategori.</p>
        </div>

        <!-- Search Form -->
        <form class="search-form" method="POST" id="liveSearchForm" action="?kategori=<?= urlencode($kategoriDipilih) ?>">
            <input type="text" name="keyword" id="search-input" placeholder="Cari Berita" value="<?= htmlspecialchars($keyword) ?>" autocomplete="off" />
            <button type="submit" name="cari" id="search-button">Search</button>
        </form>
    </div>

    <!-- Container -->
    <div class="container">

        <!-- Tombol Kategori -->
        <div class="kategori-btn-group">
            <a href="?kategori=Semua" class="kategori-btn <?= $kategoriDipilih === 'Semua' ? 'active' : '' ?>">Semua</a>
            <?php foreach ($kategoriList as $kat): ?>
                <a href="?kategori=<?= urlencode($kat) ?>" class="kategori-btn <?= $kategoriDipilih === $kat ? 'active' : '' ?>"><?= htmlspecialchars($kat) ?></a>
            <?php endforeach; ?>
        </div>

        <!-- Header Berita -->
        <div class="header">
            <?php if (!empty($keyword)) : ?>
                <h2>Hasil Berita <?= ($kategoriDipilih !== 'Semua') ? "Kategori: " . htmlspecialchars($kategoriDipilih) : "" ?></h2>
                <p>Menampilkan hasil pencarian untuk "<strong><?= htmlspecialchars($keyword) ?></strong>".</p>
            <?php else: ?>
                <h2>Berita Terbaru <?= ($kategoriDipilih !== 'Semua') ? "Kategori: " . htmlspecialchars($kategoriDipilih) : "" ?></h2>
                <p>Temukan berita hangat yang sedang menjadi perbincangan.</p>
            <?php endif; ?>
        </div>

        <!-- Daftar Berita -->
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
                            <br> 
                            <p class="category">Kategori: <?= htmlspecialchars($row['kategori']) ?></p>
                            <p class="description">
                                <?= mb_substr(strip_tags($row['deskripsi']), 0, 150) . (strlen(strip_tags($row['deskripsi'])) > 150 ? '...' : ''); ?>
                            </p>


                            <div class="btn-group">
                                <button class="read-more-btn read" onclick="location.href='detail_berita.php?id=<?= $row['id'] ?>'">Baca Selengkapnya</button>

                                <button class="read-more-btn edit"
                                    onclick="location.href='edit_berita.php?id=<?= $row['id'] ?>'">
                                    Edit
                                </button>

                                <form method="POST" action="hapus.php" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus berita ini?');">
                                    <input type="hidden" name="id" value="<?= $row['id'] ?>" />
                                    <button type="submit" class="read-more-btn delete">
                                        Hapus
                                    </button>
                                </form>
                            </div>

                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>Tidak ada berita yang ditemukan untuk kata kunci "<strong><?= htmlspecialchars($keyword) ?></strong>".</p>
            <?php endif; ?>
        </div>
    </div>

    <div class="container-horizontal">
        <div class="header-h">
            <h2>Alternatif Artikel</h2>
            <p style="margin-bottom: 20px;">Temukan Alternatif Artikel yang Sedang Anda Cari</p>
        </div>

        <!-- Tombol Scroll -->
        <button class="scroll-btn prev" onclick="scrollCards(-1)">&#10094;</button>
        <div class="news-scroll-horizontal" id="alt-artikel">
            <!-- Card 1 -->
            <div class="news-card">
            <div class="image-wrapper-horizontal">
                <a href="">
                    <img src="../hsr.jpg" alt="">
                </a>
            </div>
            <div class="news-content-horizontal">
                <h4>Honkai Star Rail</h4>
                <p class="desc">Honkai: Star Rail adalah game RPG berbasis turn-based yang dikembangkan oleh HoYoverse (developer Genshin Impact dan Honkai Impact 3rd). Game ini resmi dirilis pada 26 April 2023 untuk PC, iOS, Android, dan kemudian tersedia juga di PlayStation 5.
                    Dalam game ini, pemain berperan sebagai Trailblazer, seorang karakter yang melakukan perjalanan dengan kereta luar angkasa bernama Astral Express, menjelajahi berbagai planet dan dunia yang dipenuhi misteri, konflik, serta ancaman dari kekuatan kosmik yang dikenal sebagai Stellaron.</p>
                <a href="../Alternative/hsr.php" target="_self" class="read-more">Read More</a>
            </div>
            </div>

            <!-- Card 2 -->
            <div class="news-card">
            <div class="image-wrapper-horizontal">
                <a href="">
                    <img src="../imax.jpg" alt="">
                </a>
            </div>
            <div class="news-content-horizontal">
                <h4>IMAX</h4>
                <p class="desc">DStudio Imax di beberapa bioskop menjadi pilihan menarik buat para penonton film yang ingin merasakan sensasi layar lebar dengan audio menggelegar. Untuk itu daftar penayangan dan lokasi bioskop Imax di Indonesia merupakan informasi yang dibutuhkan.</p>
                <a href="#" class="read-more">Read More</a>
            </div>
            </div>

             <!-- Card 3 -->
            <div class="news-card">
                <div class="image-wrapper-horizontal">
                    <a href="">
                        <img src="../Assets/NNNDESU.png" alt="">
                    </a>
                </div>
                <div class="news-content-horizontal">
                    <h4>NNNDESU</h4>
                    <p class="desc">Platform Ongoing untuk Menonton Anime dan Lainnya</p>
                    <a href="http://localhost/NNEDESU/index.php" target="_parent" class="read-more">Read More</a>
                </div>
            </div>

            <!-- Card 4 -->
            <div class="news-card">
            <div class="image-wrapper-horizontal">
                <a href="">
                    <img src="https://via.placeholder.com/300x150" alt="">
                </a>
            </div>
            <div class="news-content-horizontal">
                <h4>Judul Artikel 4</h4>
                <p class="desc">Deskripsi artikel 4</p>
                <a href="#" class="read-more">Read More</a>
            </div>
            </div>
        </div>
        <button class="scroll-btn next" onclick="scrollCards(1)">&#10095;</button>
    </div>


   

    <script>
        window.addEventListener('scroll', function () {
            const newsScroll = document.getElementById('newsScroll');
            const hero = document.getElementById('hero');
            const position = newsScroll.getBoundingClientRect();

            if (position.top < window.innerHeight) {
                newsScroll.classList.add('visible');
            }

            const scrollY = window.scrollY;
            const maxScroll = window.innerHeight;
            let opacity = 1 - (scrollY / maxScroll);
            if (opacity < 0) opacity = 0;
            hero.style.opacity = opacity;
        });

        // Search live
        document.addEventListener("DOMContentLoaded", function () {
        const form = document.getElementById('liveSearchForm');
        const searchInput = document.getElementById('search-input');
        const newsContainer = document.getElementById('newsScroll');
        const headerTitle = document.querySelector('.header h2');
        const headerDesc = document.querySelector('.header p');

        // Cegah form dari submit
        form.addEventListener('submit', function(e) {
            e.preventDefault();
        });

        // Jalankan live search saat user mengetik
        searchInput.addEventListener('input', function () {
            const keyword = this.value.trim();
            const xhr = new XMLHttpRequest();
            xhr.open("GET", "home.php?ajax=1&q=" + encodeURIComponent(keyword), true);
            xhr.onload = function () {
                if (xhr.status === 200) {
                    newsContainer.innerHTML = xhr.responseText;

                    if (keyword.length > 0) {
                        headerTitle.textContent = "Hasil Berita";
                        headerDesc.innerHTML = 'Menampilkan hasil pencarian untuk "<strong>' + keyword + '</strong>".';
                    } else {
                        headerTitle.textContent = "Berita Terbaru";
                        headerDesc.textContent = "Temukan berita hangat yang sedang menjadi perbincangan.";
                    }
                }
            };
            xhr.send();
        });
    });

        function scrollCards(direction) {
            const container = document.getElementById("alt-artikel");
            const card = container.querySelector(".news-card");
            const cardWidth = card.offsetWidth + 15; // lebar card + gap
            const scrollAmount = cardWidth * 1; // geser 2 card

            container.scrollBy({
                left: direction * scrollAmount,
                behavior: "smooth"
            });
        }
    </script>
</body>
</html>
