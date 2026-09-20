<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>JoyPedia Top‑Up | Demo</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
  <style>
    /* ====== Reset & Base ====== */
    *, *::before, *::after { box-sizing: border-box; }
    html, body { height: 100%; }
    body {
      margin: 0;
      font-family: "Inter", system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, "Apple Color Emoji", "Segoe UI Emoji";
      color: #e8ecff;
      background: radial-gradient(1200px 600px at 10% -10%, #5a64d8 0%, rgba(90,100,216,0) 60%),
                  radial-gradient(1000px 500px at 110% 10%, #3b82f6 0%, rgba(59,130,246,0) 60%),
                  #0b1021;
      overflow-x: hidden;
    }

    a { color: inherit; text-decoration: none; }
    img { display: block; max-width: 100%; height: auto; }

    .container { width: min(1200px, 92%); margin-inline: auto; }

    /* ====== Header ====== */
    .header {
      position: sticky; top: 0; z-index: 50;
      background: rgba(11,16,33,.7);
      backdrop-filter: blur(10px);
      border-bottom: 1px solid rgba(255,255,255,.06);
    }
    .nav { display: flex; align-items: center; justify-content: space-between; gap: 16px; padding: 14px 0; }

    .brand { display: flex; align-items: center; gap: 10px; font-weight: 800; letter-spacing: .3px; }
    .brand .logo {
      width: 40px; height: 40px; border-radius: 12px;
      background: linear-gradient(135deg, #6d77ff, #22d3ee);
      box-shadow: 0 10px 25px rgba(109,119,255,.35);
    }

    .nav-links {
      display: flex; gap: 18px; align-items: center;
      font-weight: 600; font-size: .95rem;
    }
    .nav-links a { opacity: .9; padding: 8px 10px; border-radius: 10px; }
    .nav-links a:hover { background: rgba(255,255,255,.06); opacity: 1; }

    .cta {
      display: inline-flex; align-items: center; gap: 8px; font-weight: 700;
      padding: 10px 14px; border-radius: 14px; border: 1px solid rgba(255,255,255,.08);
      background: linear-gradient(135deg, rgba(99,102,241,.25), rgba(56,189,248,.2));
      box-shadow: 0 8px 24px rgba(59,130,246,.25);
    }

    /* ====== Hero / Poster ====== */
    .hero { padding: 26px 0 32px; }

    .poster-wrap {
      position: relative; display: grid; place-items: center;
    }

    .poster {
      width: min(1100px, 100%);
      aspect-ratio: 16 / 6; /* mirip 1000x390 */
      border-radius: 22px;
      overflow: hidden;
      border: 1px solid rgba(255,255,255,.08);
      background: linear-gradient(180deg, rgba(255,255,255,.04), rgba(255,255,255,.02));
      box-shadow: 0 20px 60px rgba(0,0,0,.45), 0 10px 30px rgba(59,130,246,.25);
    }
    .poster img { width: 100%; height: 100%; object-fit: cover; }

    /* Floating badges (optional garnish) */
    .badge {
      position: absolute; inset: auto auto -14px 20px; translate: 0 50%;
      background: #0f172a; border: 1px solid rgba(255,255,255,.08);
      padding: 10px 14px; border-radius: 999px; font-weight: 700; font-size: .9rem;
      box-shadow: 0 10px 30px rgba(0,0,0,.35);
    }

    /* ====== Category Grid ====== */
    .section { padding: 8px 0 50px; }
    .section h2 { font-size: clamp(1.2rem, 1.2rem + 1vw, 1.6rem); margin: 22px 0 16px; }

    .grid {
      display: grid; gap: 16px;
      grid-template-columns: repeat(2, 1fr);
    }
    @media (min-width: 640px) { .grid { grid-template-columns: repeat(3, 1fr); } }
    @media (min-width: 900px) { .grid { grid-template-columns: repeat(6, 1fr); } }

    .card {
      position: relative;
      background: rgba(255,255,255,.04);
      border: 1px solid rgba(255,255,255,.08);
      border-radius: 18px;
      overflow: hidden;
      transition: transform .25s ease, box-shadow .25s ease;
    }
    .card:hover { transform: translateY(-4px); box-shadow: 0 18px 40px rgba(0,0,0,.35); }

    .card-thumb { aspect-ratio: 1 / 1; background: #111827; }
    .card-thumb img { width: 100%; height: 100%; object-fit: cover; }

    .card-body { padding: 10px 12px 12px; display: grid; gap: 4px; }
    .card-title { font-weight: 700; font-size: .95rem; }
    .card-meta { font-size: .8rem; opacity: .8; }

    .price-chip {
      position: absolute; top: 10px; right: 10px;
      padding: 6px 10px; font-size: .78rem; font-weight: 800; border-radius: 999px;
      background: rgba(59,130,246,.9);
      box-shadow: 0 10px 20px rgba(59,130,246,.45);
    }

    /* ====== Footer ====== */
    .footer { padding: 40px 0; border-top: 1px solid rgba(255,255,255,.06); opacity: .9; font-size: .95rem; }

    /* ====== Mobile menu ====== */
    .hamburger { display: none; width: 34px; height: 26px; position: relative; }
    .hamburger span { position: absolute; left: 0; right: 0; height: 3px; background: #e5e7eb; border-radius: 8px; transition: .25s ease; }
    .hamburger span:nth-child(1){ top: 0; }
    .hamburger span:nth-child(2){ top: 11px; }
    .hamburger span:nth-child(3){ bottom: 0; }
    @media (max-width: 860px){
      .nav-links { display:none; }
      .hamburger { display:block; }
    }

        /* Poster slider */
        .poster-slider {
        position: relative;
        width: min(1100px, 100%);
        margin: auto;
        border-radius: 22px;
        overflow: hidden;
        }
        .slides {
        display: flex;
        transition: transform .5s ease;
        }
        .slides img {
        width: 100%;
        flex: 0 0 100%;
        object-fit: cover;
        aspect-ratio: 16/6;
        }
        .slider-nav {
        position: absolute;
        bottom: 12px;
        left: 50%;
        transform: translateX(-50%);
        display: flex;
        gap: 8px;
        }
        .slider-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: rgba(255,255,255,.4);
        cursor: pointer;
        transition: .3s;
        }
        .slider-dot.active {
        background: #fff;
        }
  </style>
</head>
<body>
  <!-- ====== Header ====== -->
  <header class="header">
    <div class="container nav">
      <a href="#" class="brand" aria-label="homepage">
        <div class="logo" aria-hidden="true"></div>
        <span>TopUp<span style="opacity:.7">.Store</span></span>
      </a>
      <nav class="nav-links" aria-label="primary">
        <a href="#layanan">Layanan</a>
        <a href="#promo">Promo</a>
        <a href="#bantuan">Bantuan</a>
        <a href="#cek-transaksi">Cek Transaksi</a>
      </nav>
      <button class="hamburger" aria-label="menu">
        <span></span><span></span><span></span>
      </button>
    </div>
  </header>

  <!-- ====== Hero / Poster ====== -->
  <section class="hero">
    <div class="container poster-wrap">
      <div class="poster-slider" id="posterSlider">
        <div class="slides">
            <img src="poster.webp" alt="Promo 1" />
            <img src="img/poster2.jpeg" alt="Promo 2" />
            <img src="img/poster3.jpeg" alt="Promo 3" />
        </div>
        <div class="slider-nav"></div>
    </div>
      <div class="badge">⚡ Proses otomatis • 24/7</div>
    </div>
  </section>

  <!-- ====== Category Grid (contoh) ====== -->
  <section class="section container" id="layanan">
    <h2>Pilihan Game Populer</h2>
    <div class="grid">
      <!-- Item 1 -->
      <article class="card">
        <div class="card-thumb"><img src="img/mlbb.jpeg" alt="MLBB" /></div>
        <div class="price-chip">Mulai 1k</div>
        <div class="card-body">
          <div class="card-title">Mobile Legends</div>
          <div class="card-meta">Diamond & Weekly Pass</div>
        </div>
      </article>
      <!-- Item 2 -->
      <article class="card">
        <div class="card-thumb"><img src="img/freefire.jpeg" alt="Free Fire" /></div>
        <div class="price-chip">Mulai 1k</div>
        <div class="card-body">
          <div class="card-title">Free Fire</div>
          <div class="card-meta">Diamond & Membership</div>
        </div>
      </article>
      <!-- Item 3 -->
      <article class="card">
        <div class="card-thumb"><img src="img/gi.jpeg" alt="Genshin Impact" /></div>
        <div class="price-chip">Mulai 10k</div>
        <div class="card-body">
          <div class="card-title">Genshin Impact</div>
          <div class="card-meta">Genesis Crystal</div>
        </div>
      </article>
      <!-- Item 4 -->
      <article class="card">
        <div class="card-thumb"><img src="img/valo.jpeg" alt="Valorant" /></div>
        <div class="price-chip">Mulai 15k</div>
        <div class="card-body">
          <div class="card-title">Valorant</div>
          <div class="card-meta">VP & Battle Pass</div>
        </div>
      </article>
      <!-- Item 5 -->
      <article class="card">
        <div class="card-thumb"><img src="img/hsr.jpeg" alt="Honkai Star Rail" /></div>
        <div class="price-chip">Mulai 10k</div>
        <div class="card-body">
          <div class="card-title">Honkai: Star Rail</div>
          <div class="card-meta">Oneiric Shard</div>
        </div>
      </article>
      <!-- Item 6 -->
      <article class="card">
        <div class="card-thumb"><img src="img/pubg.jpeg" alt="PUBG Mobile" /></div>
        <div class="price-chip">Mulai 12k</div>
        <div class="card-body">
          <div class="card-title">PUBG Mobile</div>
          <div class="card-meta">UC & Royale Pass</div>
        </div>
      </article>
    </div>
  </section>

  <!-- ====== Footer ====== -->
  <footer class="footer">
    <div class="container">
      © 2025 TopUp.Store — Metode pembayaran lengkap. CS 24/7.
    </div>
  </footer>

  <!-- (Opsional) Script interaksi hamburger sederhana -->
  <script>
    const hamburger = document.querySelector('.hamburger');
    const links = document.querySelector('.nav-links');
    hamburger?.addEventListener('click', () => {
      const open = links.style.display === 'flex';
      links.style.display = open ? 'none' : 'flex';
      if (!open) links.style.flexDirection = 'column';
    });
    
    // ===== Poster Slider =====
    (function(){
      const slider = document.getElementById('posterSlider');
      const slides = slider.querySelector('.slides');
      const images = slides.children;
      const nav = slider.querySelector('.slider-nav');
      let index = 0;

      // Buat dot indikator
      for(let i=0; i<images.length; i++){
        const dot = document.createElement('div');
        dot.className = 'slider-dot' + (i===0?' active':'');
        dot.addEventListener('click', ()=>goTo(i));
        nav.appendChild(dot);
      }

      function goTo(i){
        index = i;
        slides.style.transform = `translateX(${-100*index}%)`;
        nav.querySelectorAll('.slider-dot').forEach((d,j)=>d.classList.toggle('active', j===index));
      }

      // Auto-play setiap 5 detik
      setInterval(()=>{ goTo((index+1)%images.length); }, 5000);
    })();
  </script>
</body>
</html>