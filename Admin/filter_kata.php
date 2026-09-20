<?php

function checkBadWords($text) {
    // Daftar kata terlarang (bisa ditambah sewaktu waktu)
    $badWords = [
        // Pornografi
        "bokep","porno","pornografi","seks","ngentot","colmek","masturbasi",
        "bugil","telanjang","nude","nudity","hentai","jav","ml",

        // Kekerasan seksual
        "perkosa","pemerkosaan","pelecehan","cabuli",

        // Kata kasar
        "anjing","bangsat","kontol","memek","goblok","tolol","kampret","bajingan",

        // Rasisme (opsional)
        "rasis","rasisme","kafir"
    ];

    $text = strtolower($text);

    foreach ($badWords as $word) {
        if (strpos($text, $word) !== false) {
            return $word; // temukan kata yang melanggar
        }
    }

    return false; // aman
}

?>
