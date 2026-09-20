<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>iMAX Studio</title>
    <style>
        body {
            margin: 0;
            font-family: 'Helvetica Neue', Arial, sans-serif;
            background: #000;
            color: #fff;
        }
        .hero {
            background: url('../imax.jpg') no-repeat center/cover;
            height: 400px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            position: relative;
        }
        .hero::after {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to top, rgba(0,0,0,0.8), rgba(0,0,0,0.2));
        }
        .hero h1 {
            font-size: 3.5rem;
            text-transform: uppercase;
            letter-spacing: 3px;
            z-index: 1;
            text-shadow: 0 0 15px rgba(0,0,0,0.9);
        }
        .filter {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin: 20px auto;
            flex-wrap: wrap;
            z-index: 2;
        }
        .filter button {
            background: transparent;
            border: 2px solid #0078ff;
            padding: 8px 16px;
            border-radius: 20px;
            color: #fff;
            cursor: pointer;
            font-weight: bold;
            text-transform: uppercase;
            transition: background 0.3s, transform 0.2s;
        }
        .filter button:hover,
        .filter button.active {
            background: #0078ff;
            transform: scale(1.05);
        }
        .schedule-section {
            padding: 2rem;
            max-width: 1200px;
            margin: auto;
        }
        .movie-grid {
            display: grid;   
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            justify-content: center;  
            gap: 1rem;                
            transition: all 0.3s ease;
            max-width: 1125px;        
            margin: 0 auto;           
        }

        /* Saat hanya 1 card */
        .movie-grid.single {
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .movie-grid.single .movie-card {
            max-width: 280px;
            flex: 1;
        }
        .movie-card {
            background: rgba(255,255,255,0.05);
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.5);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            opacity: 1;
            max-width: 250px;
            animation: fadeIn 0.5s ease;
            cursor: pointer;
        }
        .movie-card:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 20px rgba(0,0,0,0.7);
        }
        .movie-card img {
            width: 100%;
            object-fit: cover;
            border-radius: 10px;
        }
        .movie-info {
            padding: 1rem;
        }
        .tags {
            display: flex;
            gap: 5px;
            margin-bottom: 0.5rem;
            flex-wrap: wrap;
        }
        .tag {
            background: #0078ff;
            padding: 4px 10px;
            border-radius: 8px;
            font-size: 0.7rem;
        }
        .showtimes {
            font-size: 0.9rem;
            margin-top: 0.5rem;
            opacity: 0.8;
        }
        .rating {
            font-weight: bold;
            color: #ffc107;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }
        /* Modal Trailer */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.85);
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }
        .modal-content {
            background: #000;
            padding: 10px;
            border-radius: 10px;
            max-width: 80%;
            max-height: 80%;
        }
        .modal video {
            width: 100%;
            border-radius: 10px;
        }
        .close-btn {
            position: absolute;
            top: 20px;
            right: 30px;
            font-size: 2rem;
            cursor: pointer;
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="hero">
        <h1>Now Showing at IMAX</h1>
    </div>
    <div class="filter">
        <button class="active" onclick="filterMovies('all')">All</button>
        <button onclick="filterMovies('action')">Action</button>
        <button onclick="filterMovies('scifi')">Sci-Fi</button>
        <button onclick="filterMovies('adventure')">Adventure</button>
        <button onclick="filterMovies('drama')">Drama</button>
        <button onclick="filterMovies('fantasy')">Fantasy</button>
    </div>
    <section class="schedule-section">
        <h2>Jadwal Film Hari Ini</h2>
        <div class="movie-grid">
            <div class="movie-card" data-genre="action" onclick="openTrailer('trailer1.mp4')">
                <img src="imax/OIP.webp" alt="Movie 1">
                <div class="movie-info">
                    <div class="tags"><span class="tag">Action</span><span class="tag">3D</span></div>
                    <h3>Avengers: Endgame</h3>
                    <p class="rating">IMAX Rating ★★★★★</p>
                    <p class="showtimes">10:00 • 13:00 • 16:30 • 20:00</p>
                </div>
            </div>
            <div class="movie-card" data-genre="scifi" onclick="openTrailer('trailer2.mp4')">
                <img src="imax/is.webp" alt="Movie 2">
                <div class="movie-info">
                    <div class="tags"><span class="tag">Sci-Fi</span><span class="tag">IMAX</span></div>
                    <h3>Interstellar</h3>
                    <p class="rating">IMAX Rating ★★★★☆</p>
                    <p class="showtimes">11:15 • 14:30 • 18:00 • 21:15</p>
                </div>
            </div>
            <div class="movie-card" data-genre="action" onclick="openTrailer('trailer1.mp4')">
                <img src="imax/kny.webp" alt="Movie 1">
                <div class="movie-info">
                    <div class="tags"><span class="tag">Action</span><span class="tag">3D</span></div>
                    <h3>Kimetsu No Yaiba : Infinity Castle</h3>
                    <p class="rating">IMAX Rating ★★★★★</p>
                    <p class="showtimes">10:00 • 13:00 • 16:30 • 20:00</p>
                </div>
            </div>
            <div class="movie-card" data-genre="action" onclick="openTrailer('trailer1.mp4')">
                <img src="imax/sore.jpg" alt="Movie 1">
                <div class="movie-info">
                    <div class="tags"><span class="tag">Action</span><span class="tag">3D</span></div>
                    <h3>SORE</h3>
                    <p class="rating">IMAX Rating ★★★★★</p>
                    <p class="showtimes">10:00 • 13:00 • 16:30 • 20:00</p>
                </div>
            </div>
            <div class="movie-card" data-genre="action" onclick="openTrailer('trailer1.mp4')">
                <img src="imax/dn.webp" alt="Movie 1">
                <div class="movie-info">
                    <div class="tags"><span class="tag">Action</span><span class="tag">3D</span></div>
                    <h3>Death Note</h3>
                    <p class="rating">IMAX Rating ★★★★★</p>
                    <p class="showtimes">10:00 • 13:00 • 16:30 • 20:00</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal Trailer -->
    <div class="modal" id="trailerModal">
        <span class="close-btn" onclick="closeTrailer()">&times;</span>
        <div class="modal-content">
            <video id="trailerVideo" controls></video>
        </div>
    </div>

    <script>
        function filterMovies(genre) {
            const buttons = document.querySelectorAll('.filter button');
            const cards = document.querySelectorAll('.movie-card');
            const grid = document.querySelector('.movie-grid');

            buttons.forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');

            let visibleCount = 0;
            cards.forEach(card => {
                if (genre === 'all' || card.dataset.genre === genre) {
                    card.style.display = 'block';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            // Tambah class single jika hanya 1 card tampil
            if (visibleCount === 1) {
                grid.classList.add('single');
            } else {
                grid.classList.remove('single');
            }
        }

        function openTrailer(src) {
            const modal = document.getElementById('trailerModal');
            const video = document.getElementById('trailerVideo');
            video.src = src;
            modal.style.display = 'flex';
            video.play();
        }

        function closeTrailer() {
            const modal = document.getElementById('trailerModal');
            const video = document.getElementById('trailerVideo');
            video.pause();
            modal.style.display = 'none';
        }
    </script>
</body>
</html>
