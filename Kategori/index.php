<?php
// Koneksi ke database
require_once '../db.php'; 

// Ambil kategori unik dari tabel berita
$stmt = $koneksi->prepare("SELECT DISTINCT kategori FROM berita");
$stmt->execute();
$categories = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Halaman Utama</title>
    <style>
        .category-list {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }
        .category-card {
            background: #f0f0f0;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            cursor: pointer;
            width: 150px;
        }
        .category-card img {
            width: 100%;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
        }
        .category-card h3 {
            margin-top: 10px;
            font-size: 16px;
        }
    </style>
</head>
<body>

    <h1>Daftar Kategori</h1>

    <div class="category-list">
        <?php while ($category = $categories->fetch_assoc()): ?>
            <a href="kategori.php?kategori=<?= urlencode($category['kategori']) ?>">
                <div class="category-card">
                    <!-- Gambar kategori, kamu bisa ganti dengan gambar sesuai kategori -->
                    <img src="path/to/<?= htmlspecialchars($category['kategori']) ?>.jpg" alt="<?= htmlspecialchars($category['kategori']) ?>">
                    <h3><?= htmlspecialchars($category['kategori']) ?></h3>
                </div>
            </a>
        <?php endwhile; ?>
    </div>

</body>
</html>