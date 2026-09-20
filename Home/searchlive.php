<?php
include "../db.php";

$keyword = isset($_GET['q']) ? mysqli_real_escape_string($koneksi, $_GET['q']) : '';

if (!empty($keyword)) {
    $sql = "SELECT * FROM berita WHERE judul LIKE '%$keyword%' OR deskripsi LIKE '%$keyword%' ORDER BY id DESC LIMIT 6";
    $query = mysqli_query($koneksi, $sql);

    if (mysqli_num_rows($query) > 0) {
        while ($row = mysqli_fetch_assoc($query)) {
            ?>
            <div class="news-item">
                <div class="image-wrapper">
                    <img src="../uploads/<?= htmlspecialchars($row['gambar']) ?>" alt="Gambar Berita" class="news-image" loading="lazy" />
                </div>
                <div class="news-content">
                    <h4><?= htmlspecialchars($row['judul']) ?></h4>
                    <p style="font-style: italic; margin-bottom: 5px;">Tanggal: <?= htmlspecialchars($row['tanggal']); ?></p>
                    <p class="category">Kategori: <?= htmlspecialchars($row['kategori']) ?></p>
                    <p class="description">
                        <?= mb_substr(strip_tags($row['deskripsi']), 0, 150) . (strlen(strip_tags($row['deskripsi'])) > 150 ? '...' : ''); ?>
                    </p>
                    <div class="btn-group">
                        <a href="detail_berita.php?id=<?= $row['id'] ?>" class="read-more-btn" title="Baca Selengkapnya">Baca Selengkapnya</a>
                    </div>
                </div>
            </div>
            <?php
        }
    } else {
        echo "<p style='text-align:center;'>Tidak ditemukan berita dengan kata kunci tersebut.</p>";
    }
}
?>

