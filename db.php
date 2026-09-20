<?php
$koneksi = mysqli_connect("localhost:3307", "root", "", "joypedia");

if (!$koneksi){
    echo "Koneksi ke database gagal";
}
?>