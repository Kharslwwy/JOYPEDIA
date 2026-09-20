<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Joypedia Merchandise - Coming Soon</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        header {
            background: #0077ff;
            padding: 20px;
            text-align: center;
            color: #fff;
        }
        .container {
            max-width: 900px;
            margin: 40px auto;
            background: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 0 12px rgba(0,0,0,0.1);
            text-align: center;
        }
        h1 {
            margin-bottom: 10px;
        }
        .coming-soon-box {
            margin-top: 40px;
            padding: 40px;
            border: 2px dashed #0077ff;
            border-radius: 12px;
            background: #eef5ff;
        }
        .coming-soon-text {
            font-size: 24px;
            font-weight: bold;
            color: #0077ff;
        }
        footer {
            text-align: center;
            padding: 20px;
            margin-top: 40px;
            background: #0077ff;
            color: #fff;
        }
    </style>
</head>
<body>
    

    <div class="container">
        <h1>Merchandise Resmi Joypedia</h1>
        <p>Halaman ini akan menampilkan berbagai merchandise eksklusif dari Joypedia. Saat ini produk masih dalam tahap persiapan.</p>

        <div class="coming-soon-box">
            <p class="coming-soon-text">COMING SOON</p>
            <p>Produk akan segera hadir! Nantikan pembaruan terbaru kami.</p>
        </div>
            <div id="countdown" style="margin-top:30px; font-size:22px; font-weight:bold; color:#0077ff;"></div>
    </div>

    <script>
        const targetDate = new Date();
        targetDate.setFullYear(targetDate.getFullYear() + 1);

        function updateCountdown() {
            const now = new Date();
            const diff = targetDate - now;

            if (diff <= 0) {
                document.getElementById('countdown').innerHTML = "Merchandise sudah tersedia!";
                return;
            }

            const days = Math.floor(diff / (1000 * 60 * 60 * 24));
            const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((diff % (1000 * 60)) / 1000);

            document.getElementById('countdown').innerHTML =
                `Hitung Mundur: ${days} Hari, ${hours} Jam, ${minutes} Menit, ${seconds} Detik`;
        }

        updateCountdown();
        setInterval(updateCountdown, 1000);
    </script>
</body>
</html>
