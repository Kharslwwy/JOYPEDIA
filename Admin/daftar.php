<?php
include "../db.php";

// Pastikan koneksi ke database berhasil
if (!$koneksi) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}

// Proses pencarian
$searchKeyword = isset($_GET['search']) ? mysqli_real_escape_string($koneksi, $_GET['search']) : "";
$query = "SELECT id, judul, kategori, tanggal FROM berita WHERE 
          judul LIKE '%$searchKeyword%' OR 
          kategori LIKE '%$searchKeyword%' OR 
          tanggal LIKE '%$searchKeyword%'";

$result = mysqli_query($koneksi, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Berita</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f7fc;
            font-family: 'Arial', sans-serif;
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            background: #ffffff;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease-in-out;
        }

        .container:hover {
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        }

        h1 {
            font-weight: bold;
            color: #333;
            margin-bottom: 20px;
            text-align: center;
        }

        .search-bar {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        .table {
            border-radius: 8px;
            overflow: hidden;
            margin-top: 20px;
        }

        .table thead {
            background-color: #007bff;
            color: white;
        }

        .table tbody tr {
            transition: all 0.2s ease-in-out;
        }

        .table tbody tr:hover {
            background-color: #f1f9ff;
        }

        .btn-primary, .btn-secondary, .btn-warning, .btn-danger {
            transition: all 0.3s ease-in-out;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .btn-danger:hover {
            background-color: #d9534f;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Daftar Berita</h1>

        <!-- Search Bar -->
        <form method="GET" action="" class="search-bar">
            <input type="text" name="search" class="form-control" placeholder="Cari judul, kategori, atau tanggal..." value="<?php echo htmlspecialchars($searchKeyword); ?>" />
            <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Cari</button>
            <a href="daftar.php" class="btn btn-secondary"><i class="fas fa-sync-alt"></i> Reset</a>
        </form>

        <!-- Data Table -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th>Judul</th>
                    <th>Kategori</th>
                    <th style="width: 15%;">Tanggal</th>
                    <th style="width: 20%;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result && mysqli_num_rows($result) > 0) {
                    $no = 1;
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $no++ . "</td>";
                        echo "<td>" . htmlspecialchars($row['judul']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['kategori']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['tanggal']) . "</td>";
                        echo "<td>
                                <a href='edit_berita.php?id=" . $row['id'] . "' class='btn btn-warning btn-sm'><i class='fas fa-edit'></i> Edit</a>
                                <a href='hapus.php?id=" . $row['id'] . "' class='btn btn-danger btn-sm' onclick=\"return confirm('Yakin ingin menghapus berita ini?');\"><i class='fas fa-trash'></i> Hapus</a>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5' class='text-center'>Tidak ada berita yang ditemukan.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
</body>
</html>
