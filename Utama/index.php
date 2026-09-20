<?php
session_start();
include "../db.php"; // Pastikan file ini mendefinisikan $koneksi dengan benar

if (isset($_GET['ajax'])) {
    $keyword = mysqli_real_escape_string($koneksi, $_GET['q'] ?? '');
    $sql = "SELECT * FROM berita WHERE judul LIKE '%$keyword%' OR deskripsi LIKE '%$keyword%' ORDER BY id DESC";
    $query = mysqli_query($koneksi, $sql);

    if (mysqli_num_rows($query) > 0) {
        while ($row = mysqli_fetch_assoc($query)) {
            ?>
            <div class="news-item" data-category="<?= htmlspecialchars($row['kategori']) ?>">
                <div class="image-wrapper">
                    <img src="../uploads/<?= htmlspecialchars($row['gambar']) ?>" alt="Gambar Berita" class="news-image" />
                </div>
                <div class="news-content">
                    <h4><?= htmlspecialchars($row['judul']) ?></h4>
                    <p style="font-style: italic; margin-bottom: 5px;">Tanggal: <?= htmlspecialchars($row['tanggal']); ?></p>
                    <p class="category" style="margin-bottom: 5px;">Kategori: <?= htmlspecialchars($row['kategori']) ?></p>
                    <p class="description">
                        <?= mb_substr(strip_tags($row['deskripsi']), 0, 150) . (strlen(strip_tags($row['deskripsi'])) > 150 ? '...' : ''); ?>
                    </p>
                    <div class="btn-group">
                        <a href="detail_berita.php?id=<?= $row['id'] ?>" class="read-more-btn">Baca Selengkapnya</a>
                    </div>
                </div>
            </div>
            <?php
        }
    } else {
        echo "<p style='text-align:center; font-style:italic;'>Tidak ada hasil untuk <strong>$keyword</strong>.</p>";
    }

    exit();
}
?>

<!DOCTYPE html>
<!-- Created By CodingNepal - www.codingnepalweb.com -->
<html lang="en" dir="ltr">
  <head>
    <meta charset="UTF-8">
    <link rel="icon" href="Utama/JOY.png" type="image/png">
   <title> Website Artike | JoyPedia </title>
    <link rel="stylesheet" href="/css/head.css">
    <!-- Boxicons CDN Link -->
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <style>
      @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap');
    *{
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Poppins', sans-serif;
    }
    body{
      min-height: 100vh;
    }
    nav{
      position: sticky;
      top: 0;
      z-index: 999;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      height: 70px;
      background: #153c4b;
      box-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
      z-index: 99;
    }
    nav .navbar{
      position: sticky;
      top: 0;
      z-index: 999;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
      height: 100%;
      max-width: 1250px;
      width: 100%;
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin: auto;
      /* background: red; */
      padding: 0 50px;
    }
    .navbar .logo a{
      font-size: 30px;
      color: #fff;
      text-decoration: none;
      font-weight: 600;
    }
    nav .navbar .nav-links{
      line-height: 70px;
      height: 100%;
    }
    nav .navbar .links{
      display: flex;
    }
    nav .navbar .links li{
      position: relative;
      display: flex;
      align-items: center;
      justify-content: space-between;
      list-style: none;
      padding: 0 14px;
    }
    nav .navbar .links li a{
      height: 100%;
      text-decoration: none;
      white-space: nowrap;
      color: #fff;
      font-size: 15px;
      font-weight: 500;
    }
    .links li:hover .htmlcss-arrow,
    .links li:hover .js-arrow, .links li:hover .ac-arrow {
      transform: rotate(180deg);
      }
    nav .navbar .links li .arrow{
      /* background: red; */
      height: 100%;
      width: 22px;
      line-height: 70px;
      text-align: center;
      display: inline-block;
      color: #fff;
      transition: all 0.3s ease;
    }
    nav .navbar .links li .sub-menu{
      position: absolute;
      top: 70px;
      left: 0;
      line-height: 40px;
      background: #243438;
      box-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
      border-radius: 0 0 4px 4px;
      display: none;
      z-index: 2;
    }
    nav .navbar .links li:hover .htmlCss-sub-menu,
    nav .navbar .links li:hover .js-sub-menu,
    nav .navbar .links li:hover .acc-sub-menu {
      display: block;
    }
    .navbar .links li .sub-menu li{
      padding: 0 22px;
      border-bottom: 1px solid rgba(255,255,255,0.1);
    }
    .navbar .links li .sub-menu a{
      color: #fff;
      font-size: 15px;
      font-weight: 500;
    }
    .navbar .links li .sub-menu .more-arrow{
      line-height: 40px;
    }
    .navbar .links li .htmlCss-more-sub-menu{
      /* line-height: 40px; */
    }
    .navbar .links li .sub-menu .more-sub-menu{
      position: absolute;
      top: 0;
      left: 100%;
      border-radius: 0 4px 4px 4px;
      z-index: 1;
      display: none;
    }
    .links li .sub-menu .more:hover .more-sub-menu{
      display: block;
    }
    .navbar .search-box{
      position: relative;
      overflow: hidden;
      height: 40px;
      width: 40px;
    }
    .navbar .search-box i{
      position: absolute;
      height: 100%;
      width: 100%;
      line-height: 40px;
      text-align: center;
      font-size: 22px;
      color: #fff;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
    }
    .navbar .search-box .input-box{
      position: absolute;
      right: calc(100% - 40px);
      top: 80px;
      height: 60px;
      width: 300px;
      background: #3E8DA8;
      border-radius: 6px;
      opacity: 0;
      pointer-events: none;
      transition: all 0.4s ease;
    }
    .navbar.showInput .search-box .input-box{
      top: 65px;
      opacity: 1;
      pointer-events: auto;
      background: #223e47;
    }
    .search-box .input-box::before{
      content: '';
      overflow: hidden;
      position: absolute;
      height: 20px;
      width: 20px;
      background: #1c3a44;
      right: 10px;
      top: -6px;
      transform: rotate(45deg);
    }
    .search-box .input-box input{
      position: absolute;
      top: 50%;
      left: 50%;
      border-radius: 4px;
      transform: translate(-50%, -50%);
      height: 35px;
      width: 280px;
      outline: none;
      padding: 0 15px;
      font-size: 16px;
      border: none;
    }
    .navbar .nav-links .sidebar-logo{
      display: none;
    }
    .navbar .bx-menu{
      display: none;
    }
    @media (max-width:920px) {
      nav .navbar{
        max-width: 100%;
        padding: 0 25px;
      }
      nav .navbar .logo a{
        font-size: 27px;
      }
      nav .navbar .links li{
        padding: 0 10px;
        white-space: nowrap;
      }
      nav .navbar .links li a{
        font-size: 15px;
      }
    }
    @media (max-width:800px){
      nav{
        /* position: relative; */
      }
      .navbar .bx-menu{
        display: block;
      }
      nav .navbar .nav-links{
        position: fixed;
        top: 0;
        left: -100%;
        display: block;
        max-width: 270px;
        width: 100%;
        background:  #1d3841;
        line-height: 40px;
        padding: 20px;
        box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
        transition: all 0.5s ease;
        z-index: 1000;
      }
      .navbar .nav-links .sidebar-logo{
        display: flex;
        align-items: center;
        justify-content: space-between;
      }
      .sidebar-logo .logo-name{
        font-size: 25px;
        color: #fff;
      }
        .sidebar-logo  i,
        .navbar .bx-menu{
          font-size: 25px;
          color: #fff;
        }
      nav .navbar .links{
        display: block;
        margin-top: 20px;
      }
      nav .navbar .links li .arrow{
        line-height: 40px;
      }
    nav .navbar .links li{
        display: block;
      }
    nav .navbar .links li .sub-menu{
      position: relative;
      top: 0;
      box-shadow: none;
      display: none;
    }
    nav .navbar .links li .sub-menu li{
      border-bottom: none;
    }
    .navbar .links li .sub-menu .more-sub-menu{
      display: none;
      position: relative;
      left: 0;
    }
    .navbar .links li .sub-menu .more-sub-menu li{
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
    .links li:hover .htmlcss-arrow,
    .links li:hover .js-arrow{
      transform: rotate(0deg);
      }
      .navbar .links li .sub-menu .more-sub-menu{
        display: none;
      }
      .navbar .links li .sub-menu .more span{
        /* background: red; */
        display: flex;
        align-items: center;
        /* justify-content: space-between; */
      }
      .links li .sub-menu .more:hover .more-sub-menu{
        display: none;
      }
      nav .navbar .links li:hover .htmlCss-sub-menu,
      nav .navbar .links li:hover .js-sub-menu,
      nav .navbar .links li:hover .acc-sub-menu{
        display: none;
      }
    .navbar .nav-links.show1 .links .htmlCss-sub-menu,
      .navbar .nav-links.show3 .links .js-sub-menu,
      .navbar .nav-links.show4 .links .acc-sub-menu,
      .navbar .nav-links.show2 .links .more .more-sub-menu{
          display: block;
        }
        .navbar .nav-links.show1 .links .htmlcss-arrow,
        .navbar .nav-links.show3 .links .js-arrow{
            transform: rotate(180deg);
    }
        .navbar .nav-links.show2 .links .more-arrow{
          transform: rotate(90deg);
        }
    }
    .navbar .nav-links.show4 .links .acc-sub-menu {
      display: block;
    }

    @media (max-width:370px){
      nav .navbar .nav-links{
      max-width: 100%;
    } 
    }

     /* Main Content */
        main {
        }

        .content-frame {
            width: 100%;
            height: calc(100dvh - 80px);
            border: none;
            background: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Overlay Profil */
    .profile-overlay{position:fixed;top:0;left:0;width:100%;height:100%;
      background:rgba(0,0,0,0.5);display:none;justify-content:flex-end;z-index:9999;}
    .profile-panel{width:380px;height:100%;background:#fff;box-shadow:-4px 0 10px rgba(0,0,0,0.3);
      border-radius:12px 0 0 12px;overflow-y:auto;animation:slideIn 0.3s ease;}
    .profile-panel iframe{width:100%;height:100%;border:none;}
    .close-btn{position:absolute;top:10px;right:400px;padding:5px 10px;border:none;
      background:#d33;color:#fff;font-size:16px;border-radius:6px;cursor:pointer;}
    @keyframes slideIn{from{transform:translateX(100%);opacity:0;}to{transform:translateX(0);opacity:1;}}

     </style>
   </head>
<body>
  <nav>
    <div class="navbar">
      <i class='bx bx-menu'></i>
      <div class="logo"><a href="#">JoyPedia</a></div>
      <div class="nav-links">
        <div class="sidebar-logo">
          <span class="logo-name">JoyPedia</span>
          <i class='bx bx-x' ></i>
        </div>
        <ul class="links">
          <li><a href="../Home/home.php" target="content-frame">HOME</a></li>
          <li>
            <a href="../Kategori/kategori.php" target="content-frame">KATEGORI</a>
            <i class='bx bxs-chevron-down htmlcss-arrow arrow  '></i>
            <ul class="htmlCss-sub-menu sub-menu">
              <li><a href="#">Kesehatan</a></li>
              <li><a href="#">Politik</a></li>
              <li><a href="#">Social</a></li>
              <li class="more">
                <span><a href="#">More</a>
                <i class='bx bxs-chevron-right arrow more-arrow'></i>
              </span>
                <ul class="more-sub-menu sub-menu">
                  <li><a href="#">Game</a></li>
                  <li><a href="#">Olahraga</a></li>
                  <li><a href="#">Nasional</a></li>
                  <li><a href="#">Internasional</a></li>
                  <li><a href="#">Ekonomi</a></li>
                  <li><a href="#">Hiburan</a></li>
                  <li><a href="#">Seni</a></li>
                </ul>
              </li>
            </ul>
          </li>
          <li><a href="../Berbagi/berbagi.php" target="content-frame">BERBAGI INFORMASI</a></li>
          <li>
            <a href="#">ABOUT US</a>
            <i class='bx bxs-chevron-down js-arrow arrow '></i>
            <ul class="js-sub-menu sub-menu">
              <li><a href="../Tentang Kami/tentang.php" target="content-frame">Tentang Kami</a></li>
              <li><a href="merchan.php" target="content-frame" >Merchandise</a></li>
            </ul>
          </li>
          <li>
            <a href="#">ACCOUNT</a>
            <i class='bx bxs-chevron-down ac-arrow arrow '></i>
            <ul class="acc-sub-menu sub-menu">
              <li><a href="#" class="openProfile">Profile</a></li>
              <!-- <li><a href="setting.php" target="content-frame" >Setting</a></li> -->
              <li><a href="../Login/logout.php">Logout</a></li>
            </ul>
          </li>

        </ul>
      </div>
            <!-- header.php -->
      <form method="POST" action="">
        <div class="search-box">
          <i class='bx bx-search'></i>
          <div class="input-box">
            <input type="text" name="keyword" id="search-input" placeholder="Search..." autocomplete="off" autofocus>
          </div>
          <button type="submit" name="cari" style="display:none;"></button>
        </div>
      </form>

    </div>
  </nav>
    <main>
        <iframe name="content-frame" src="../Home/home.php" class="content-frame"></iframe>
       
    </main>

    <!-- Overlay Profil -->
  <div class="profile-overlay" id="profileOverlay">
    <button class="close-btn" onclick="closeProfile()">✕</button>
    <div class="profile-panel">
      <iframe src="profil.php"></iframe>
    </div>
  </div>
  <script src="script.js"></script>
</body>
</html>

<script>
let navbar = document.querySelector(".navbar");
let searchBox = document.querySelector(".search-box .bx-search");
searchBox.addEventListener("click", ()=>{
  navbar.classList.toggle("showInput");
  if(navbar.classList.contains("showInput")){
    searchBox.classList.replace("bx-search" ,"bx-x");
  }else {
    searchBox.classList.replace("bx-x" ,"bx-search");
  }
});

// Sidebar
let navLinks = document.querySelector(".nav-links");
let menuOpenBtn = document.querySelector(".navbar .bx-menu");
let menuCloseBtn = document.querySelector(".nav-links .bx-x");
menuOpenBtn.onclick = ()=> navLinks.style.left = "0";
menuCloseBtn.onclick = ()=> navLinks.style.left = "-100%";

// Submenu
document.querySelector(".htmlcss-arrow").onclick = ()=> navLinks.classList.toggle("show1");
document.querySelector(".more-arrow").onclick = ()=> navLinks.classList.toggle("show2");
document.querySelector(".js-arrow").onclick = ()=> navLinks.classList.toggle("show3");
document.querySelector(".ac-arrow").onclick = ()=> navLinks.classList.toggle("show4");

// ✅ Perbaikan: Cegah submit hanya kalau form ada
document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector("form");
    if (form) {
        form.addEventListener("submit", function(e){
            e.preventDefault();
        });
    }
});

// ✅ Buka overlay profil
document.querySelectorAll(".openProfile").forEach(link => {
  link.addEventListener("click", function(e){
    e.preventDefault();
    document.getElementById("profileOverlay").style.display = "flex";
  });
});

// ✅ Tutup overlay
function closeProfile(){
  document.getElementById("profileOverlay").style.display = "none";
}
document.getElementById("profileOverlay").addEventListener("click", function(e){
  if (e.target === this) closeProfile();
});
</script>



