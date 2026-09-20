<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="Utama/JOY.png" type="image/png">
    <title> Alternatif Artikel | HSR </title>
    <style>
         * {
            box-sizing: border-box;
        }

        /* Reset & base */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            overflow-x: hidden;
            color: #fff;

            background: 
            linear-gradient(rgba(0,0,0,0.91), rgba(0,0,0,0.91)), /* overlay gelap transparan */
            url('../hsr.jpg') no-repeat center center/cover;

            background-attachment: fixed; 
        }


        /* Hapus horizontal scroll sepenuhnya */
        body, html {
            overflow-x: hidden;
            width: 100%;
            max-width: 100vw;
        }

        .container {
        max-width: 900px;
        margin: auto;
        padding: 20px;
      }
      .breadcrumb {
        font-size: 14px;
        color: #aaa;
        margin-bottom: 10px;
      }
      .breadcrumb a {
        color: #aaa;
        text-decoration: none;
      }
      .header {
        display: flex;
        align-items: center;
        gap: 15px;
      }
      .header img {
        width: 60px;
        height: 60px;
        border-radius: 5px;
      }
      .header-text h1 {
        margin: 0;
        font-size: 26px;
        font-weight: bold;
        color: #fff;
      }
      .header-text p {
        margin: 5px 0;
        font-size: 14px;
        color: #ccc;
      }
      .header-text .date {
        font-size: 13px;
        color: #6ab7ff;
        font-weight: bold;
      }
      .section-title {
        margin-top: 25px;
        padding: 10px;
        background-color: #111d2d;
        border-top: 3px solid #2b7fff;
        font-weight: bold;
        text-transform: uppercase;
      }
      .card {
        background-color: #111;
        border: 1px solid #222;
        margin-top: 10px;
        padding: 15px;
        border-radius: 3px;
      }
      .card p {
        margin: 8px 0;
        font-size: 14px;
        color: #ccc;
        line-height: 1.6;
      }

      .card h3 {
        border-bottom: 3px solid #2b7fff;
      }

      .card img {
        width: 100%;
        height: 50%;
        align-items: center;
      }
      .highlight {
        font-weight: bold;
        color: #fff;
      }

      /* Tab Utama */
    .tab button, .subtab button {
      background-color: #111d2d;
      border: none;
      outline: none;
      cursor: pointer;
      padding: 12px 18px;
      transition: 0.3s;
      color: #fff;
      font-weight: bold;
    }
    .tab button:hover, .subtab button:hover {
      background-color: #2b7fff;
    }
    .tab button.active, .subtab button.active {
      background-color: #2b7fff;
    }

    .tabcontent, .subtabcontent {
      display: none;
    }

        /* Title Section */
    .tier-title {
      display: flex;
      align-items: center;
      text-align: center;
      font-size: 20px;
      font-weight: bold;
      color: #e84d4d;
      margin: 30px 0;
      text-transform: uppercase;
      letter-spacing: 1px;
    }

    .tier-title::before,
    .tier-title::after {
      content: "";
      flex: 1;
      border-bottom: 2px solid #e84d4d;
      margin: 0 10px;
    }

    /* Tier Row */
    .tier-row {
      display: flex;
      margin-bottom: 20px;
      position: relative;
      z-index: 1;
    }

    .tier-label {
      width: 60px;
      background: #f79646;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: bold;
      font-size: 16px;
      color: black;
    }

    .tier-grid {
      flex: 1;
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
      gap: 15px;
      background: #222;
      padding: 15px;
    }

    /* Card */
    .card-char {
      background: #2c2c2c;
      border-radius: 10px;
      padding: 6px;
      text-align: center;
      position: relative;
      cursor: pointer;
      transition: transform 0.2s;
    }

    .card-char img {
      width: 100%;
      border-radius: 6px;
    }

    .card-char p {
      margin: 6px 0 0;
      font-size: 12px;
      font-weight: bold;
    }

    .role {
      font-size: 11px;
      margin-top: 4px;
      color: #aaa;
    }

    /* Tooltip (floating di body) */
    #tooltip {
      display: none;
      position: absolute;
      background: #2c2c2c;
      border-radius: 8px;
      padding: 10px;
      width: 240px;
      box-shadow: 0 3px 10px rgba(0,0,0,0.6);
      text-align: left;
      z-index: 999999; /* paling atas */
    }

    #tooltip h4 {
      margin: 0 0 8px;
      font-size: 14px;
    }

    #tooltip .tags {
      display: flex;
      gap: 5px;
      flex-wrap: wrap;
      margin-bottom: 10px;
    }

    .tag {
      padding: 2px 6px;
      border-radius: 4px;
      font-size: 11px;
      background: #3a3a3a;
    }

    .tag.star { background: #666; }
    .tag.fire { background: #e84d4d; }
    .tag.hunt { background: #2a84ff; }

    .ratings {
      display: flex;
      justify-content: space-between;
      margin-top: 8px;
    }

    .rating-box {
      flex: 1;
      text-align: center;
      padding: 4px 6px;
      border-radius: 4px;
      font-size: 12px;
      font-weight: bold;
      margin: 0 2px;
    }

    .hsr-update-container {
        margin-top: 20px;
    }

    .hsr-card {
        background: #ffffff;
        padding: 20px;
        border-radius: 14px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        margin-bottom: 25px;
    }

    .hsr-title {
        font-size: 22px;
        margin-bottom: 10px;
        color: #0077ff;
    }

    .hsr-desc {
        font-size: 15px;
        margin-bottom: 15px;
        color: #333;
    }

    .video-box {
        width: 100%;
        border-radius: 12px;
        overflow: hidden;
        background: #000;
    }

    .video-box video {
        width: 100%;
        height: auto;
        border-radius: 12px;
    }

    .tier1 { background: #f79646; color: black; }
    .tier2 { background: #ffd966; color: black; }
    .tier3 { background: #9999ff; color: white; }
    .tier4 { background: #66cc66; color: black; }
    </style>
</head>
<body>
    <div class="container">
    <!-- Breadcrumb -->
    <div class="breadcrumb">
      <a href="#">Honkai: Star Rail</a> / <a href="#">information</a>
    </div>

    <!-- Header -->
    <div class="header">
      <img src="../hsricon.webp" alt="character">
      <div class="header-text">
        <h1>Honkai: Star Rail  (August 2025)</h1>
        <p>The most popular for Honkai: Star Rail that rates all available characters by their performance in Memory of Chaos, Pure Fiction and Apocalyptic Shadow.</p>
        <p class="date">Last updated: 13/08/2025</p>
      </div>
    </div>

          <!-- Tab Utama -->
    <div class="tab">
      <button class="tablinks" onclick="openTab(event, 'intro')" id="defaultOpen">Introduction</button>
      <button class="tablinks" onclick="openTab(event, 'update')">Update</button>
      <button class="tablinks" onclick="openTab(event, 'tier')">Tier List</button>
    </div>

    <!-- Konten Introduction -->
    <div id="intro" class="tabcontent">
      <div class="section-title">🔷 HONKAI: STAR RAIL INTRODUCTION</div>
        <div class="card">
          <h3> Introduction to the game </h3>
          <p> <span class="highight">Honkai: Star Rail is a turn-based RPG </span>very similar to old-school JRPGs (especially the Final Fantasy series). The game is split into two modes: overworld and battle. In the overworld, you run around the map and when you encounter an enemy group, you will 'transfer' into the battle mode where you have to defeat the enemies in turn-based combat.</p>
          <p >We will talk about the overworld (also known as exploration) and combat modes in a separate section further down the guide.</p>

          <h3>Characters</h3>
          <p>Karakter adalah unit yang dapat dimainkan. Umumnya diperoleh lewat Warp (gacha), sebagian bisa gratis dari misi/event.</p>

          <h3>Character Stats</h3>
          <ul>
            <li><strong>HP</strong> – Ketahanan sebelum tumbang.</li>
            <li><strong>ATK</strong> – Besar damage yang diberikan.</li>
            <li><strong>DEF</strong> – Mengurangi damage yang diterima.</li>
            <li><strong>Speed</strong> – Seberapa cepat/sering giliran datang.</li>
            <li><strong>Crit Rate</strong> – Peluang serangan kritikal.</li>
            <li><strong>Crit DMG</strong> – Multiplier saat kritikal.</li>
            <li><strong>Break Effect</strong> – Memperkuat Weakness Break, DoT per turn, dan delay aksi musuh.</li>
            <li><strong>Outgoing Healing Boost</strong> – Bonus penyembuhan yang diberikan.</li>
            <li><strong>Energy Regeneration Rate</strong> – Kecepatan pengisian energi Ultimate.</li>
            <li><strong>Effect Hit Rate</strong> – Peluang menerapkan debuff.</li>
            <li><strong>Effect RES</strong> – Resistensi terhadap debuff.</li>
            <li><strong>Elemental Damage Boost</strong> – Peningkatan damage untuk elemen tertentu.</li>
          </ul>

          <h3>Character Elements & Paths</h3>
          <p><strong>Elemen (7):</strong> Physical, Fire, Ice, Lightning, Wind, Quantum, Imaginary.</p>
          <p><strong>Path (8):</strong></p>
          <ul>
            <li><strong>Destruction</strong> – Single/Blast DMG.</li>
            <li><strong>Hunt</strong> – Single-target DMG.</li>
            <li><strong>Erudition</strong> – AoE DMG.</li>
            <li><strong>Harmony</strong> – Buff & dukungan tim.</li>
            <li><strong>Nihility</strong> – Debuff & DoT.</li>
            <li><strong>Preservation</strong> – Mitigasi damage.</li>
            <li><strong>Abundance</strong> – Healing.</li>
            <li><strong>Remembrance</strong> – Gunakan Memosprite untuk asist.</li>
          </ul>

          <h3>Character Skills</h3>
          <ul>
            <li><strong>Basic Attack</strong> – Selalu tersedia; generator Skill Point (SP).</li>
            <li><strong>Skill</strong> – Butuh SP; efek damage/buff/debuff.</li>
            <li><strong>Ultimate</strong> – Paling kuat; dipakai saat energi penuh; dapat digunakan di luar giliran.</li>
            <li><strong>Talent</strong> – Pasif unik (conditional bonus).</li>
            <li><strong>Technique</strong> – Kemampuan overworld; pembuka tempur atau buff pra-tempur.</li>
          </ul>

          <h3>Character Progression</h3>
          <img src="../img aset/guide_character.webp" alt="">
          <p>Cara meningkatkan kekuatan:</p>
          <ul>
            <li>Menaikkan <strong>Level</strong> & <strong>Ascension</strong>.</li>
            <li>Upgrade <strong>Traces</strong>.</li>
            <li>Unlock <strong>Eidolons</strong>.</li>
            <li>Mengenakan <strong>Light Cones</strong>.</li>
            <li>Memasang <strong>Relics & Planetary Ornaments</strong>.</li>
          </ul>

          <h3>Level & Ascension</h3>
          <p>Level karakter 1–80. Setiap Ascension menaikkan batas level (+10) hingga 80 dan butuh material (Stagnant Shadow & mob). Batas Ascension juga dikunci oleh <em>Trailblaze Level</em> akun.</p>

          <h3>Traces</h3>
          <img src="../img aset/guide_traces.webp" alt="">
          <p>Pohon upgrade yang memberi bonus status, pasif tambahan, serta peningkatan 4 skill. Material dari Crimson Calyx, misi, atau toko; dibuka sesuai tahap Ascension.</p>

          <h3>Eidolons</h3>
          <img src="../img aset/guide_eidolon.webp" alt="">
          <p>6 tahap peningkatan yang memperkuat kemampuan atau memberi pasif baru. Didapat dari duplikat di Warp atau hadiah misi/event (Trailblazer lewat progres misi/Trailblaze Level).</p>

          <h3>Light Cones</h3>
          <img src="../img aset/guide_cone.webp" alt="">
          <p>“Senjata” yang memberi stat dan <em>Light Cone Ability</em> (pasif terkait Path). Level 1–80, bisa di-ascend. <em>Superimpose</em> menaikkan peringkat Ability (R1→R5) dengan mengorbankan duplikat. Hanya karakter dengan Path yang cocok yang bisa memakai Ability-nya; selain itu hanya mendapatkan stat.</p>

          <h3>Relics</h3>
          <img src="../img aset/guide_relics.webp" alt="">
          <p>Perlengkapan utama untuk stat (armor/aksesori). Terdiri dari 6 slot:</p>
          <ul>
            <li><strong>Head</strong> – HP flat (main stat tetap).</li>
            <li><strong>Hands</strong> – ATK flat (main stat tetap).</li>
            <li><strong>Body</strong> – HP%/ATK%/DEF%/Crit Rate%/Crit DMG%/Healing%/Effect Hit Rate.</li>
            <li><strong>Feet</strong> – HP%/ATK%/DEF%/Speed.</li>
            <li><strong>Planar Sphere</strong> – HP%/ATK%/DEF%/Elemental DMG Boost (satu elemen).</li>
            <li><strong>Link Rope</strong> – HP%/ATK%/DEF%/Break Effect%/Energy Regen Rate%.</li>
          </ul>
          <p><em>Catatan:</em> Planar Sphere & Link Rope sering disebut <strong>Planetary Ornaments</strong> (fungsi sama dengan Relics secara gameplay).</p>

          <h3>Exploration</h3>
          <p>Dunia terbagi zona (ada loading antar area). Pilih satu karakter aktif untuk bergerak; bisa ganti di luar combat. Musuh di luar kota akan mengejar saat kita mendekat. Ada <em>Interactive Map</em> resmi untuk navigasi & chest.</p>

          <h3>Initiating Combat</h3>
          <ul>
            <li>Serang musuh dengan <strong>Basic Attack</strong> untuk memulai pertarungan.</li>
            <li><strong>Technique</strong> bisa membuka pertarungan dengan efek khusus; memakai <em>Technique charge</em> (dipulihkan dari peti ungu dekat Space Anchor/Calyx).</li>
            <li>Jika musuh menyentuh duluan, status <strong>Ambushed</strong> (musuh bergerak lebih dulu).</li>
            <li>Jika membuka dengan elemen yang menjadi kelemahan musuh, tim memulai dengan efek <strong>Weakness</strong> (Toughness musuh berkurang).</li>
          </ul>

          <h3>Combat Singkat</h3>
          <p>Sistem turn-based dengan tim 4 karakter. Urutan aksi ditentukan oleh Speed.</p>
          <ul>
            <li><strong>Basic</strong> – Menghasilkan SP & 20 Energy.</li>
            <li><strong>Skill</strong> – Mengonsumsi SP & menghasilkan 30 Energy.</li>
            <li><strong>Ultimate</strong> – Biaya Energy (gunakan saat penuh), memberi 5 Energy saat dipakai, bisa dipicu di luar giliran dan tidak mengakhiri giliran saat dipakai di giliran.</li>
            <li><strong>Tambahan Energi</strong> – Kalahkan musuh: +10/target; terkena pukulan: bervariasi; beberapa efek karakter/Light Cone memodifikasi nilai ini.</li>
            <li><strong>Manajemen SP</strong> – SP maksimal 5 dan dibagi seluruh tim.</li>
          </ul>
      </div>
    </div>


    <div id="update" class="tabcontent">
        <div class="section-title">🔷 HONKAI: STAR RAIL UPDATE</div>
        <div class="card">
          <div class="hsr-update-container">
            <!-- Update Card -->
            <div class="hsr-card">
                <h3 class="hsr-title">Patch 2.5 – "Malam Terpanjang" | Cerita Pengantar Tidur Cyrene</h3>
                <p class="hsr-desc">
                    Update besar dengan karakter baru, event musiman, serta area eksplorasi tambahan. 
                    Saksikan trailer resminya di bawah ini.
                </p>

                <!-- Video Container -->
                <div class="video-box">
                    <video controls>
                        <source src="cyrene.mp4" type="video/mp4" />
                        Browser Anda tidak mendukung pemutar video.
                    </video>
                </div>
            </div>
            <div class="hsr-card">
                <h3 class="hsr-title">Patch 2.5 – "Malam Terpanjang" | Halo, Dunia!</h3>
                <p class="hsr-desc">
                    Update besar dengan karakter baru, event musiman, serta area eksplorasi tambahan. 
                    Saksikan trailer resminya di bawah ini.
                    <br>
                    Setelah tiga belas detak jantung ....
                    Terang akan menciptakan langit dan bumi.
                </p>

                <!-- Video Container -->
                <div class="video-box">
                    <video controls>
                        <source src="malam.mp4" type="video/mp4" />
                        Browser Anda tidak mendukung pemutar video.
                    </video>
                </div>
            </div>
        </div>
        </div>
    </div>


        <!-- Konten Tier List -->
    <div id="tier" class="tabcontent">
      <div class="section-title">🔷 HONKAI: STAR RAIL TIER LIST</div>
      <div class="card">
        <h3>About the Tier List</h3>
        <p>Please keep in mind that Honkai: Star Rail is <span class="highlight">a game where team building matters most</span> and while our tier list takes the optimal setup into account, a lot of characters can work and do well - even those ranked lower - when you invest into them.</p>
        <p>Also for story or lower difficulties of Simulated Universe, you don't need to worry about ratings and tiers. You can safely clear content with any of your favorite characters.</p>
        <p>These tier lists rate characters based on their average performance in Memory of Chaos, Pure Fiction and Apocalyptic Shadow regardless of turbulence, whimsicality and cacophony (last 3 phases specifically). Characters rated higher will perform well without the need to rely on these and will only benefit more from receiving them. As new mechanics, characters and challenges are introduced each tier list will be updated.
          Important! Characters are ordered alphabetically within a tier.</p>
          <p>Available tier lists:</p>

          <span> 
              - Memory of Chaos (MoC) - how the character performs in the Memory of Chaos. Blast and single target damage are important here while AoE has niche uses against some bosses.
              <br>- Pure Fiction (PF) - how the character performs in the Pure Fiction. AoE is king here, followed by Blast while single target damage is mostly useless.
              <br>- Apocalyptic Shadow (AS) - how the character performs in the Apocalyptic Shadow. Single target and Break potential are heavily favored here.
          </span>
          <p>About the ratings: To decide the ratings we use a combination of the data we gather for every mode (the Analytics for every mode you can find in the left menu), our own testing that's done inline with the criteria (check below) and the last element is community feedback we receive - either from Reddit, YouTube or our own Discord.</p>
      </div>

      <!-- Sub Tab di dalam Tier List -->
      <div class="subtab">
        <button class="subtablinks" onclick="openSubTab(event, 'MoC')" id="defaultSub">Memory of Chaos</button>
        <button class="subtablinks" onclick="openSubTab(event, 'PF')">Pure Fiction</button>
        <button class="subtablinks" onclick="openSubTab(event, 'AS')">Apocalyptic Shadow</button>
      </div>

      <!-- Konten Sub Tab -->
      <div id="MoC" class="subtabcontent">
        <div class="card">
          <h3>Memory of Chaos Tier List</h3>
          <!-- Apex Characters -->
          <div class="tier-section">
            <div class="tier-title">Apex Characters</div>

            <!-- T0 -->
            <div class="tier-row">
              <div class="tier-label">T0</div>
              <div class="tier-grid">
                <!-- Example Card -->
                <div class="card-char" data-tooltip='
                  <h4>Kafka</h4>
                  <div class="tags">
                    <span class="tag star">5★</span>
                    <span class="tag fire">Lightning</span>
                    <span class="tag hunt">Nihility</span>
                  </div>
                  <p style="font-size:12px; color:#ccc;">
                    Belongs to <span style="color:#e84d4d;">DPS</span> role.
                  </p>
                  <div class="ratings">
                    <div class="rating-box tier1">T1<br>MoC (E0S0)</div>
                    <div class="rating-box tier2">T2<br>PF (E0S0)</div>
                    <div class="rating-box tier1">T1<br>AS (E0S0)</div>
                  </div>'>
                  <img src="https://placehold.co/100x100" alt="Kafka">
                  <p>Kafka</p>
                  <div class="role">Debuff</div>
                </div>
              </div>
            </div>
          </div>

          <!-- Meta Characters -->
          <div class="tier-section">
            <div class="tier-title">Meta Characters</div>

            <!-- T1 -->
            <div class="tier-row">
              <div class="tier-label">T1</div>
              <div class="tier-grid">
                <!-- Example Card -->
                <div class="card-char" data-tooltip='
                  <h4>Topaz & Numby</h4>
                  <div class="tags">
                    <span class="tag star">5★</span>
                    <span class="tag fire">Fire</span>
                    <span class="tag hunt">Hunt</span>
                  </div>
                  <p style="font-size:12px; color:#ccc;">
                    Belongs to <span style="color:#bb66ff;">Support DPS</span> role.
                  </p>
                  <div class="ratings">
                    <div class="rating-box tier1">T1<br>MoC (E0S0)</div>
                    <div class="rating-box tier4">T4<br>PF (E0S0)</div>
                    <div class="rating-box tier1">T1<br>AS (E0S0)</div>
                  </div>'>
                  <img src="https://placehold.co/100x100" alt="Topaz">
                  <p>Topaz & Numby</p>
                  <div class="role">Debuff, FUA, Summon</div>
                </div>
              </div>
            </div>
          </div>

          <!-- Tooltip floating -->
          <div id="tooltip"></div>
      </div>
  </div>
      <div id="PF" class="subtabcontent">
          <div class="card">
          <h3>Pure Fiction Tier List</h3>
          <div class="tier-section">
            <div class="tier-title">Apex Character</div>

            <!-- T0 -->
            <div class="tier-row">
              <div class="tier-label">T0</div>
              <div class="tier-grid">
                <!-- Example Card -->
                <div class="card-char" data-tooltip='
                  <h4>Kafka</h4>
                  <div class="tags">
                    <span class="tag star">5★</span>
                    <span class="tag fire">Lightning</span>
                    <span class="tag hunt">Nihility</span>
                  </div>
                  <p style="font-size:12px; color:#ccc;">
                    Belongs to <span style="color:#e84d4d;">DPS</span> role.
                  </p>
                  <div class="ratings">
                    <div class="rating-box tier1">T1<br>MoC (E0S0)</div>
                    <div class="rating-box tier2">T2<br>PF (E0S0)</div>
                    <div class="rating-box tier1">T1<br>AS (E0S0)</div>
                  </div>'>
                  <img src="https://placehold.co/100x100" alt="Kafka">
                  <p>Kafka</p>
                  <div class="role">Debuff</div>
                </div>
              </div>
            </div>
          </div>

          <!-- Meta Characters -->
          <div class="tier-section">
            <div class="tier-title">Meta Characters</div>

            <!-- T1 -->
            <div class="tier-row">
              <div class="tier-label">T1</div>
              <div class="tier-grid">
                <!-- Example Card -->
                <div class="card-char" data-tooltip='
                  <h4>Topaz & Numby</h4>
                  <div class="tags">
                    <span class="tag star">5★</span>
                    <span class="tag fire">Fire</span>
                    <span class="tag hunt">Hunt</span>
                  </div>
                  <p style="font-size:12px; color:#ccc;">
                    Belongs to <span style="color:#bb66ff;">Support DPS</span> role.
                  </p>
                  <div class="ratings">
                    <div class="rating-box tier1">T1<br>MoC (E0S0)</div>
                    <div class="rating-box tier4">T4<br>PF (E0S0)</div>
                    <div class="rating-box tier1">T1<br>AS (E0S0)</div>
                  </div>'>
                  <img src="https://placehold.co/100x100" alt="Topaz">
                  <p>Topaz & Numby</p>
                  <div class="role">Debuff, FUA, Summon</div>
                </div>
              </div>
            </div>
          </div>

          <!-- Tooltip floating -->
          <div id="tooltip"></div>
        </div>
      </div>
      <div id="AS" class="subtabcontent">
        <div class="card"><h3>Apocalyptic Shadow Tier List</h3><p>... isi AS ...</p></div>
      </div>
      </div>
  </div>
</body>
</html>

<script>
function openTab(evt, tabName) {
  let i, tabcontent, tablinks;
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) tabcontent[i].style.display = "none";
  
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) tablinks[i].className = tablinks[i].className.replace(" active", "");

  document.getElementById(tabName).style.display = "block";
  evt.currentTarget.className += " active";
}

// Sub Tab
function openSubTab(evt, subTabName) {
  let i, subtabcontent, subtablinks;
  subtabcontent = document.getElementsByClassName("subtabcontent");
  for (i = 0; i < subtabcontent.length; i++) subtabcontent[i].style.display = "none";
  
  subtablinks = document.getElementsByClassName("subtablinks");
  for (i = 0; i < subtablinks.length; i++) subtablinks[i].className = subtablinks[i].className.replace(" active", "");

  document.getElementById(subTabName).style.display = "block";
  evt.currentTarget.className += " active";
}

// buka default tab saat load
document.getElementById("defaultOpen").click();
document.getElementById("defaultSub").click();


const cards = document.querySelectorAll('.card-char');
    const tooltip = document.getElementById('tooltip');

    cards.forEach(card => {
      card.addEventListener('mouseenter', e => {
        tooltip.innerHTML = card.getAttribute('data-tooltip');
        tooltip.style.display = 'block';

        // posisi tooltip
        const rect = card.getBoundingClientRect();
        tooltip.style.top = (rect.bottom + window.scrollY + 8) + "px";
        tooltip.style.left = (rect.left + rect.width/2 - tooltip.offsetWidth/2 + window.scrollX) + "px";
      });

      card.addEventListener('mousemove', e => {
        const rect = card.getBoundingClientRect();
        tooltip.style.top = (rect.bottom + window.scrollY + 8) + "px";
        tooltip.style.left = (rect.left + rect.width/2 - tooltip.offsetWidth/2 + window.scrollX) + "px";
      });

      card.addEventListener('mouseleave', () => {
        tooltip.style.display = 'none';
      });
    });
</script>
