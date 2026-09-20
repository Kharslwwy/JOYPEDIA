<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Joypedia - Settings</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background:#f5f7fa; }
        .settings-card { border-radius:15px; }
        .form-switch .form-check-input { cursor:pointer; }
    </style>
</head>
<body>
<div class="container mt-5 mb-5">
    <div class="card shadow settings-card p-4">
        <h2 class="mb-4 text-primary fw-bold">⚙️ Pengaturan Joypedia</h2>

        <!-- Tema Website -->
        <div class="mb-4">
            <h4 class="fw-semibold">🎨 Tema Website</h4>
            <p class="text-muted">Pilih tampilan yang Anda sukai.</p>
            <select id="themeSelect" class="form-select">
                <option value="light">Mode Terang (Default)</option>
                <option value="dark">Mode Gelap</option>
                <option value="blue">Mode Biru Joypedia</option>
            </select>
        </div>

        <!-- Notifikasi -->
        <div class="mb-4">
            <h4 class="fw-semibold">🔔 Notifikasi</h4>
            <p class="text-muted">Aktifkan atau nonaktifkan notifikasi.</p>

            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="notifKomentar">
                <label class="form-check-label" for="notifKomentar">Komentar Baru</label>
            </div>

            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="notifArtikel">
                <label class="form-check-label" for="notifArtikel">Pembaruan Artikel</label>
            </div>

            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="notifTrending">
                <label class="form-check-label" for="notifTrending">Berita Trending</label>
            </div>
        </div>

        <!-- Reset & Save Buttons -->
        <div class="d-flex gap-3">
            <button class="btn btn-secondary" onclick="resetSettings()">Reset ke Default</button>
            <button class="btn btn-primary" onclick="saveSettings()">Simpan Pengaturan</button>
        </div>
    </div>
</div>

<script>
// Theme Preview
const themeSelect = document.getElementById("themeSelect");
themeSelect.addEventListener("change", function() {
    if (this.value === "dark") {
        document.body.style.background = "#1b1b1b";
        document.body.style.color = "#fff";
    } else if (this.value === "blue") {
        document.body.style.background = "#d8e8ff";
        document.body.style.color = "#000";
    } else {
        document.body.style.background = "#f5f7fa";
        document.body.style.color = "#000";
    }
});

// Reset Settings
function resetSettings() {
    themeSelect.value = "light";
    document.getElementById("notifKomentar").checked = false;
    document.getElementById("notifArtikel").checked = false;
    document.getElementById("notifTrending").checked = false;
    themeSelect.dispatchEvent(new Event("change"));
    alert("Pengaturan dikembalikan ke default.");
}

// Save Settings
function saveSettings() {
    alert("Pengaturan berhasil disimpan (database menyusul).");
}
</script>

</body>
</html>