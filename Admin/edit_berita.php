<?php
include "../db.php";

// Periksa apakah parameter ID ada di URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<div class='alert alert-danger text-center mt-5'>ID berita tidak ditemukan.</div>";
    exit();
}

$id = intval($_GET['id']);
$query = mysqli_query($koneksi, "SELECT * FROM berita WHERE id = $id");

// Periksa apakah query berhasil dan data ditemukan
if (!$query || mysqli_num_rows($query) === 0) {
    echo "<div class='alert alert-danger text-center mt-5'>Berita dengan ID $id tidak ditemukan.</div>";
    exit();
}

$berita = mysqli_fetch_assoc($query);

$success = false;

// Proses update data jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = mysqli_real_escape_string($koneksi, $_POST['judul']);
    $kategori = mysqli_real_escape_string($koneksi, $_POST['kategori']);
    $deskripsi = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);
    $isi = mysqli_real_escape_string($koneksi, $_POST['isi']);
    $isi2 = mysqli_real_escape_string($koneksi, $_POST['isi2']);
    $youtube_link = mysqli_real_escape_string($koneksi, $_POST['youtube_link']);

    $gambar = $berita['gambar'];
    $gambar2 = $berita['gambar2'];

    if (!empty($_FILES['gambar']['name'])) {
        $gambar = time() . '_' . $_FILES['gambar']['name'];
        move_uploaded_file($_FILES['gambar']['tmp_name'], "../uploads/$gambar");
    }

    if (!empty($_FILES['gambar2']['name'])) {
        $gambar2 = time() . '_' . $_FILES['gambar2']['name'];
        move_uploaded_file($_FILES['gambar2']['tmp_name'], "../uploads/$gambar2");
    }

    $updateQuery = "UPDATE berita SET judul = '$judul', kategori = '$kategori', deskripsi = '$deskripsi', isi = '$isi', isi2 = '$isi2', gambar = '$gambar', gambar2 = '$gambar2', youtube_link = '$youtube_link' WHERE id = $id";

    if (mysqli_query($koneksi, $updateQuery)) {
        $success = true; // notifikasi akan muncul
        // perbarui data berita agar form menampilkan data terbaru
        $berita['judul'] = $judul;
        $berita['kategori'] = $kategori;
        $berita['deskripsi'] = $deskripsi;
        $berita['isi'] = $isi;
        $berita['isi2'] = $isi2;
        $berita['gambar'] = $gambar;
        $berita['gambar2'] = $gambar2;
        $berita['youtube_link'] = $youtube_link;
    } else {
        echo "<div class='alert alert-danger mt-3'>Terjadi kesalahan: " . mysqli_error($koneksi) . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Berita</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <script src="https://cdn.ckeditor.com/4.21.0/standard/ckeditor.js"></script>
    <style>
        body {
            background-color: #f4f4f9;
        }
        .card {
            animation: fadeIn 0.8s ease;
            border: none;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }
        .card-header {
            background: url('https://png.pngtree.com/background/20210712/original/pngtree-vast-universe-space-beautiful-nebula-galaxy-background-picture-image_1179109.jpg') center/cover no-repeat;
            color: #fff;
            text-align: center;
            font-weight: bold;
            padding: 1.5rem;
            border-radius: 10px 10px 0 0;
        }
        .form-control:focus {
            box-shadow: 0 0 10px rgba(37, 117, 252, 0.6);
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
<div class="container mt-5">

    <?php if ($success): ?>
        <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
            ✅ <strong>Berhasil!</strong> Berita telah diperbarui.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header">
            <h1>- EDIT BERITA -</h1>
        </div>
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="judul" class="form-label">Judul</label>
                    <input type="text" name="judul" id="judul" class="form-control" value="<?= htmlspecialchars($berita['judul']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="kategori" class="form-label">Kategori</label>
                    <input type="text" name="kategori" id="kategori" class="form-control" value="<?= htmlspecialchars($berita['kategori']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="deskripsi" class="form-label">Deskripsi</label>
                    <textarea name="deskripsi" id="deskripsi" class="form-control" rows="3" required><?= htmlspecialchars($berita['deskripsi']); ?></textarea>
                </div>
                <div class="mb-3">
                    <label for="isi" class="form-label">Isi Berita 1</label>
                    <textarea name="isi" id="isi" class="form-control" rows="5" required><?= htmlspecialchars($berita['isi']); ?></textarea>
                </div>
                <div class="mb-3">
                    <label for="isi2" class="form-label">Isi Berita 2</label>
                    <textarea name="isi2" id="isi2" class="form-control" rows="5"><?= htmlspecialchars($berita['isi2']); ?></textarea>
                </div>

                <div class="mb-3">
                    <label for="gambar" class="form-label">&lt; Gambar Sampul &gt;</label>
                    <input type="file" name="gambar" id="gambar" class="form-control">
                    <?php if (!empty($berita['gambar'])): ?>
                        <p class="mt-2" style="font-style: italic;">Gambar Saat Ini: 
                            <img src="../uploads/<?= htmlspecialchars($berita['gambar']); ?>" alt="Gambar Saat Ini" class="img-thumbnail" style="max-width: 200px;">
                        </p>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label for="gambar2" class="form-label">&lt; Gambar Tambahan &gt;</label>
                    <input type="file" name="gambar2" id="gambar2" class="form-control">
                    <?php if (!empty($berita['gambar2'])): ?>
                        <p class="mt-2" style="font-style: italic;">Gambar Tambahan: 
                            <img src="../uploads/<?= htmlspecialchars($berita['gambar2']); ?>" alt="Gambar Tambahan" class="img-thumbnail" style="max-width: 200px;">
                        </p>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label for="youtube_link" class="form-label">YouTube Link</label>
                    <input type="url" name="youtube_link" id="youtube_link" class="form-control" value="<?= htmlspecialchars($berita['youtube_link']); ?>">
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary me-2">💾 Simpan</button>
                    <a href="home.php" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    CKEDITOR.replace('deskripsi');
    CKEDITOR.replace('isi');
    CKEDITOR.replace('isi2');
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
