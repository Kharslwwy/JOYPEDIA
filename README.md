# Website Artikel PKK

## Deskripsi Proyek

Website Artikel PKK adalah aplikasi berbasis web untuk menampilkan
artikel dan informasi. Proyek ini berjalan secara lokal dan diakses
melalui:

    http://localhost/PKK/header.php

## Struktur Folder

    PKK/
    │── header.php
    │── koneksi.php
    │── style.css
    │── /img
    │── /Home
    │── /Utama
    │── /Admin
    └── /vendor

## Persyaratan Sistem

-   PHP ≥ 7.4
-   Apache (XAMPP/Laragon)
-   MySQL ≥ 5.7
-   Browser modern

## Cara Menjalankan

1.  Pindahkan folder `PKK` ke dalam `htdocs/`.
2.  Jalankan Apache & MySQL.
3.  Import database (jika ada file `.sql`) - joyedia.sql.
4.  Akses melalui:

```{=html}
<!-- -->
```
    http://localhost/PKK/header.php

## Konfigurasi Database

Pastikan file `koneksi.php` sudah sesuai:

``` php
<?php
$koneksi = mysqli_connect("localhost", "root", "", "joypedia");

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
```

## Developer

Nama: \[Wahyuddin Fakhar & Rama Bramantya Aprilian\]\
Email: \[wahyuddinfakhar4@gmail.com\]
