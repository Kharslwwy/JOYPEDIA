<?php
session_start();
include "../db.php";

if (!isset($_POST['polling_id'])) {
    header("Location: detail_berita.php?msg=polling_not_found");
    exit();
}

$polling_id = intval($_POST['polling_id']);
mysqli_query($koneksi, "DELETE FROM polling_responses WHERE polling_id = $polling_id");
mysqli_query($koneksi, "DELETE FROM polling WHERE id = $polling_id");

header("Location: " . $_SERVER['HTTP_REFERER'] . "&msg=polling_deleted");
exit();
