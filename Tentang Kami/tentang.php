<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Tentang Kami - JOYPEDIA</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />
  <style>
    :root {
      --primary: #7f8c8d;
      --primary-dark: #2c3e50;
      --light-bg: #f9f9fc;
      --text-dark: #2c3e50;
      --text-muted: #000000ff;
      --card-bg: #fff;
    }

    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
      font-family: 'Inter', sans-serif;
      background-color: var(--light-bg);
      color: var(--text-dark);
      line-height: 1.6;
      min-height: 100vh;
    }

    header {
      background: linear-gradient(to right, var(--primary), var(--primary-dark));
      color: white;
      padding: 90px 20px 70px;
      text-align: center;
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
      position: relative;
      overflow: hidden;
    }

    header h1 {
      font-size: 3rem;
      margin-bottom: 15px;
      opacity: 0;
      transform: translateY(40px) scale(0.95);
      animation: fadeUp 1s ease forwards;
    }

    header p {
      font-size: 1.3rem;
      opacity: 0;
      transform: translateY(30px) scale(0.97);
      animation: fadeUp 1s ease forwards;
      animation-delay: 0.3s;
    }

    header::after {
      content: "";
      position: absolute;
      top: -50%;
      left: -50%;
      width: 200%;
      height: 200%;
      background: radial-gradient(circle, rgba(255,255,255,0.2), transparent 70%);
      animation: float 12s infinite linear;
    }

    main { max-width: 1100px; margin: 60px auto 100px; padding: 0 20px; }
    section { margin-bottom: 80px; }

    /* Reveal animasi */
    .reveal {
      opacity: 0;
      transform: translateY(50px) scale(0.95);
      transition: all 0.8s ease;
    }
    .reveal.left { transform: translateX(-60px) scale(0.95); }
    .reveal.right { transform: translateX(60px) scale(0.95); }
    .reveal.active { opacity: 1; transform: translateX(0) translateY(0) scale(1); }

    section h2 {
      text-align: center;
      color: var(--primary-dark);
      margin-bottom: 35px;
      font-weight: 700;
      font-size: 2.2rem;
      letter-spacing: 1px;
    }

    p, li {
      font-size: 1.1rem;
      color: var(--text-muted);
      opacity: 0;
      transform: translateY(30px);
      transition: all 0.8s ease;
    }
    p.active, li.active { opacity: 1; transform: translateY(0); }

    ul {
      max-width: 750px;
      margin: 0 auto 20px;
      list-style-type: disc;
      padding-left: 25px;
      line-height: 1.7;
    }

    /* Tim */
    .team-members {
      display: flex;
      justify-content: center;
      gap: 35px;
      flex-wrap: wrap;
    }
    .team-card {
      background: var(--card-bg);
      padding: 28px 20px;
      border-radius: 14px;
      box-shadow: 0 6px 18px rgba(0, 0, 0, 0.12);
      text-align: center;
      width: 250px;
      opacity: 0;
      transform: translateY(40px) scale(0.95);
      transition: all 0.8s ease;
    }
    .team-card.active {
      opacity: 1;
      transform: translateY(0) scale(1);
    }
    .team-card:hover {
      transform: translateY(-12px) scale(1.05);
      box-shadow: 0 14px 28px rgba(0, 0, 0, 0.18);
    }
    .team-card img {
      width: 110px;
      height: 110px;
      border-radius: 50%;
      object-fit: cover;
      margin-bottom: 15px;
      border: 3px solid var(--primary);
      transition: transform 0.4s ease;
    }
    .team-card:hover img { transform: rotate(4deg) scale(1.07); }
    .team-card h4 { color: var(--primary-dark); font-weight: 600; margin-bottom: 8px; font-size: 1.2rem; }
    .team-card p { font-size: 0.95rem; color: var(--text-muted); opacity: 1; transform: none; }

    /* Sosial */
    .social-links { text-align: center; }
    .social-links h2 { margin-bottom: 20px; color: var(--primary-dark); }
    .social-links a {
      display: inline-block;
      margin: 0 15px;
      padding: 14px 28px;
      border-radius: 10px;
      font-size: 1.1rem;
      font-weight: 600;
      color: white;
      text-decoration: none;
      position: relative;
      overflow: hidden;
      transition: transform 0.3s ease;
    }
    .social-links a::after {
      content: "";
      position: absolute;
      top: 50%; left: 50%;
      width: 0; height: 0;
      background: rgba(255,255,255,0.3);
      border-radius: 50%;
      transform: translate(-50%, -50%);
      transition: width 0.6s ease, height 0.6s ease;
    }
    .social-links a:hover { transform: scale(1.05); }
    .social-links a:hover::after { width: 250%; height: 250%; }
    .social-links a.instagram { background: #E1306C; }
    .social-links a.whatsapp { background: #25D366; }

    /* Keyframes */
    @keyframes fadeUp { to { opacity: 1; transform: translateY(0) scale(1); } }
    @keyframes float { from { transform: rotate(0); } to { transform: rotate(360deg); } }

    @media (max-width: 768px) {
      header h1 { font-size: 2.2rem; }
      section h2 { font-size: 1.6rem; }
      .team-card { width: 200px; }
    }
  </style>
</head>
<body>
  <header>
    <h1>Tentang Kami</h1>
    <p>JOYPEDIA — Platform edukatif dan interaktif untuk semua kalangan</p>
  </header>

  <main>
    <section class="reveal left">
      <h2>Profil Singkat</h2>
      <p>Selamat datang di <strong>JOYPEDIA</strong>, platform yang kami rancang untuk menyebarkan pengetahuan secara interaktif. Kami percaya bahwa pembelajaran harus dapat diakses, menyenangkan, dan memberdayakan setiap individu.</p>
    </section>

    <section class="reveal right">
      <h2>Visi dan Misi</h2>
      <p><strong>Visi:</strong> Menjadi platform edukasi interaktif yang inovatif dan informatif bagi semua kalangan.</p>
      <p><strong>Misi:</strong></p>
      <ul>
        <li>Menyediakan konten yang informatif, menarik, dan mudah dipahami.</li>
        <li>Memfasilitasi pembelajaran berbasis komunitas dan interaksi.</li>
        <li>Mengintegrasikan teknologi untuk pengalaman belajar yang efektif dan menyenangkan.</li>
      </ul>
    </section>      

    <section>
      <h2 class="reveal">Tim Kami</h2>
      <div class="team-members">
        <div class="team-card">
          <img src="rama.jpg" alt="Rama Bramantya A" />
          <h4>Rama Bramantya A</h4>
          <p>Fullstack Developer</p>
        </div>
        <div class="team-card">
          <img src="me.jpg" alt="Wahyuddin Fakhar" />
          <h4>Wahyuddin Fakhar</h4>
          <p>Fullstack Developer <br> Official Suami Waguri</p>
        </div>
      </div>
    </section>

    <section class="reveal left">
      <h2>Ikuti Kami</h2>
      <div class="social-links">
        <a href="https://www.instagram.com/kharslwwy/" target="_blank" rel="noopener" class="instagram">Instagram</a>
        <a href="https://wa.me/6283674214926" target="_blank" rel="noopener" class="whatsapp">WhatsApp</a>
      </div>
    </section>
  </main>

  <script>
    const observer = new IntersectionObserver(entries => {
      entries.forEach(entry => {
        const el = entry.target;

        if (entry.isIntersecting) {
          // delay animasi untuk card tim
          if (el.classList.contains('team-card')) {
            const index = [...el.parentNode.children].indexOf(el);
            el.style.transitionDelay = `${index * 0.25}s`;
          }
          // delay animasi untuk paragraf dan list item
          if (el.tagName === 'P' || el.tagName === 'LI') {
            const siblings = [...el.parentNode.children].filter(elm => elm.tagName === el.tagName);
            const index = siblings.indexOf(el);
            el.style.transitionDelay = `${index * 0.15}s`;
          }
          el.classList.add('active');
        } else {
          // reset supaya bisa animasi keluar
          el.classList.remove('active');
          el.style.transitionDelay = "0s";
        }
      });
    }, { threshold: 0.2 });

    document.querySelectorAll('.reveal, .team-card, p, li').forEach(el => observer.observe(el));
  </script>
</body>
</html>
