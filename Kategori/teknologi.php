<?php
// Koneksi ke database
$conn = mysqli_connect("localhost", "root", "", "joypedia"); // Ganti dengan nama database "joypedia"

// Cek koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Query untuk mengambil berita dengan kategori 'Teknologi'
$sql = "SELECT * FROM berita WHERE kategori = 'Teknologi'"; // Menampilkan semua artikel dengan kategori Teknologi
$result = mysqli_query($conn, $sql);

// Cek apakah ada hasil
if (mysqli_num_rows($result) > 0) {
    // Ambil data artikel
} else {
    die("Artikel tidak ditemukan.");
}

// Tutup koneksi
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Berita Teknologi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
        }

        .container {
            max-width: 80%;
            margin: 100px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .article-card {
            background-color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            transition: transform 0.3s ease-in-out;
            cursor: pointer;
        }

        .article-card:hover {
            transform: translateY(-10px);
        }

        .article-card img {
            width: 100%;
            max-height: 300px;
            object-fit: cover;
            border-radius: 8px;
        }

        .article-card h3 {
            color: #007bff;
            font-size: 1.6em;
            margin: 20px 0;
        }

        .article-card p {
            color: #666;
            font-size: 1rem;
            line-height: 1.6;
            height: 80px; /* Membatasi tinggi deskripsi */
            overflow: hidden;
        }

        .article-card .read-more {
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
        }

        .article-card .read-more:hover {
            color: #0056b3;
        }

        /* Responsif */
        @media (max-width: 768px) {
            .container {
                max-width: 95%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Berita Kategori Teknologi</h2>

        <?php
        // Menampilkan setiap artikel dalam kategori Teknologi
        while ($row = mysqli_fetch_assoc($result)) {
            echo "
            <div class='article-card'>
                <img src='" . htmlspecialchars($row['gambar']) . "' alt='Gambar Artikel'>
                <h3>" . htmlspecialchars($row['judul']) . "</h3>
                <p>" . htmlspecialchars($row['deskripsi']) . "</p>
                <a href='detail_artikel.php?id=" . $row['id'] . "' class='read-more'>Baca selengkapnya</a>
            </div>";
        }
        ?>
    </div>
</body>
</html>