<?php
session_start();
include '../db.php';

if (!isset($_SESSION['username'])) {
    header("Location: ../Login/login.php");
    exit;
}

$currentUser = $_SESSION['username'];
$isAdminView = (($_SESSION['role'] ?? 'user') === 'admin');

$meEsc = mysqli_real_escape_string($koneksi, $currentUser);

$sql = "
  SELECT c.*, u.role, u.profile_pic
  FROM chat c
  LEFT JOIN users u ON c.username = u.username
  WHERE (c.deleted_for_all = 0 OR c.deleted_for_all IS NULL)
    AND NOT EXISTS (
      SELECT 1 FROM message_deletions d
      WHERE d.message_id = c.id AND d.username = '$meEsc'
    )
  ORDER BY c.timestamp ASC
";

$result = mysqli_query($koneksi, $sql);
if ($result === false) {
  $sql = "
    SELECT c.*, u.role, u.profile_pic
    FROM chat c
    LEFT JOIN users u ON c.username = u.username
    ORDER BY c.timestamp ASC
  ";
  $result = mysqli_query($koneksi, $sql);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>JOY FAM'S Chat</title>

<style>
:root {
  --bg-body: #f5f6f8;
  --bubble-other: #f0f2f5;
  --bubble-self: #e0ecff;
  --text-other: #1f2937;
  --text-self: #1f2937;
  --menu-hover: rgba(0,0,0,0.05);
  --dropdown-bg: #ffffff;
  --dropdown-text: #111827;
}

@media (prefers-color-scheme: dark) {
  :root {
    --bg-body: #111827;
    --bubble-other: #1f2937;
    --bubble-self: #2563eb;
    --text-other: #e5e7eb;
    --text-self: #ffffff;
    --menu-hover: rgba(255,255,255,0.1);
    --dropdown-bg: #1f2937;
    --dropdown-text: #e5e7eb;
  }
}

body {
  background: var(--bg-body);
  margin: 0;
  font-family: system-ui, sans-serif;
}

.chat-row {
  display: flex;
  align-items: flex-start;
  margin: 6px 0;
}

.chat-row.self {
  flex-direction: row-reverse;
}

.avatar {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  overflow: hidden;
  flex-shrink: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  background: #ddd;
  margin-right: 8px;
  cursor: pointer;
}

.chat-row.self .avatar {
  margin-left: 8px;
  margin-right: 0;
}

.avatar img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  display: block;
}

.bubble {
  max-width: 70%;
  padding: 8px 12px;
  border-radius: 12px;
  background: var(--bubble-other);
  color: var(--text-other);
  position: relative;
  word-wrap: break-word;
  line-height: 1.4;
  box-shadow: 0 1px 3px rgba(0,0,0,0.06);
}

.chat-row.self .bubble {
  background: var(--bubble-self);
  color: var(--text-self);
  font-weight: 500;
}

.name {
  font-weight: bold;
  cursor: pointer;
  display: inline-block;
}

.menu-btn {
  position: absolute;
  top: 6px;
  right: -36px;
  background: transparent;
  border: none;
  cursor: pointer;
  font-size: 16px;
  line-height: 1;
  width: 28px;
  height: 28px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 50%;
  transition: background 0.2s;
}
.menu-btn:hover {
  background: var(--menu-hover);
}

.dropdown-portal {
  background: var(--dropdown-bg);
  color: var(--dropdown-text);
  border: 1px solid rgba(0,0,0,0.1);
  border-radius: 8px;
  box-shadow: 0 4px 16px rgba(0,0,0,0.2);
  min-width: 180px;
  max-width: 90vw;
  padding: 6px;
  display: none;
  position: absolute;
  z-index: 999;
}
.dropdown-portal.show { display: block; }

.dropdown-portal button {
  width: 100%;
  text-align: left;
  background: transparent;
  border: none;
  padding: 8px 10px;
  border-radius: 6px;
  font-size: 13px;
  cursor: pointer;
  color: inherit;
}
.dropdown-portal button:hover {
  background: var(--menu-hover);
}

/* === FILE PREVIEW === */
.msg-img {
  max-width: 200px;
  border-radius: 10px;
  margin-top: 6px;
  cursor: pointer;
  transition: 0.2s;
}
.msg-img:hover {
  opacity: 0.9;
}

.pdf-frame {
  width:180px;
  height:240px;
  border:0;
  border-radius:8px;
  margin-top:6px;
}

/* === FULLSCREEN IMAGE VIEWER === */
#imgViewer {
  display:none;
  position:fixed;
  top:0;left:0;
  width:100vw;height:100vh;
  background:rgba(0,0,0,0.8);
  backdrop-filter: blur(3px);
  z-index:9999;
  justify-content:center;
  align-items:center;
}

#imgViewer img {
  max-width:90%;
  max-height:90%;
  border-radius:12px;
}

#imgViewerClose {
  position:absolute;
  top:20px; right:30px;
  font-size:32px;
  color:white;
  cursor:pointer;
  font-weight:bold;
}
</style>

</head>
<body>

<div id="chatBox">

<?php
while ($row = mysqli_fetch_assoc($result)) {
  $isOwn   = ($row['username'] === $currentUser);
  $rowCls  = $isOwn ? 'chat-row self' : 'chat-row';
  $roleSender = $row['role'] ?? 'user';

  $name = ($roleSender === 'admin')
    ? '👑 ' . $row['username'] . ' (Admin)'
    : $row['username'];

  $foto = !empty($row['profile_pic']) ? $row['profile_pic'] : 'default-profile.jpg';
  $ts   = strtotime($row['timestamp']);
  $time = $ts ? date('H:i', $ts) : '';
  $date = $ts ? date('d M Y', $ts) : '';

  $canAll = $isAdminView ? 1 : 0;

  echo '<div class="'. $rowCls .'" data-id="'. (int)$row['id'] .'">';

    echo '<div class="avatar" onclick="lihatProfil(\''. htmlspecialchars($row['username']) .'\')">
            <img src="../uploads/'. htmlspecialchars($foto) .'" alt="avatar">
          </div>';

    echo '<div class="bubble">';

      echo '<span class="name" onclick="lihatProfil(\''. htmlspecialchars($row['username']) .'\')">'.
            htmlspecialchars($name) .'</span>';
      echo '<span class="time"> '. htmlspecialchars($date) .' • '. htmlspecialchars($time) .'</span><br>';

      // ==== REPLY ====
      if (!empty($row['reply_to'])) {
        $rid = (int)$row['reply_to'];
        $rq  = mysqli_query($koneksi, "SELECT message, username FROM chat WHERE id = $rid");
        if ($rq && mysqli_num_rows($rq) > 0) {
          $rd = mysqli_fetch_assoc($rq);
          echo '<div class="chat-reply"><strong>'.
               htmlspecialchars($rd['username']) . ':</strong> ' .
               htmlspecialchars($rd['message']) . '</div>';
        }
      }

      // === TAMPILKAN TEKS ===
      if ($row['message'] !== '') {
        echo nl2br(htmlspecialchars($row['message']));
      }

      // =============== MULTI FILE PREVIEW ===============
      $files = [];
      if (!empty($row['file_path'])) {
          $decoded = json_decode($row['file_path'], true);
          $files = is_array($decoded) ? $decoded : [$row['file_path']];
      }

      foreach ($files as $file) {
          $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));

          // --- IMAGE ---
          if (in_array($ext, ['jpg','jpeg','png','gif','webp'])) {
              echo '
              <div>
                <img src="'. htmlspecialchars($file) .'" 
                     class="msg-img"
                     onclick="openImgViewer(\''. htmlspecialchars($file) .'\')">

                <br><a href="'. htmlspecialchars($file) .'" download
                   style="color:#38bdf8;display:inline-block;margin-top:4px">
                   ⬇ Download Gambar
                </a>
              </div>';
          }

          // --- PDF ---
          else if ($ext === 'pdf') {
              echo '
              <div style="margin-top:6px">
                <a class="msg-pdf" href="'. htmlspecialchars($file) .'" 
                   target="_blank">📄 Buka PDF</a>
                <br>
                <a href="'. htmlspecialchars($file) .'" download
                   style="color:#38bdf8">⬇ Download PDF</a>
                <iframe class="pdf-frame" src="'. htmlspecialchars($file) .'"></iframe>
              </div>';
          }

          // --- FILE LAIN ---
          else {
              echo '
              <div style="margin-top:6px">
                <a href="'. htmlspecialchars($file) .'" download>
                  📁 Unduh file
                </a>
              </div>';
          }
      }

      // Menu
      echo '<button class="menu-btn" type="button" data-self="'. ($isOwn ? 1 : 0) .'"
            data-can-all="'. $canAll .'" onclick="openMenu(event, this)">&#8942;</button>';

      $label = ($row['message'] !== '') ? $row['message'] :
               (!empty($files) ? '[file]' : '');

      $safe  = htmlspecialchars(addslashes($label));

      echo '<div class="bubble-actions">
              <button class="reply-button" type="button"
                onclick="reply('. (int)$row['id'] .', \''. $safe .'\')">Balas</button>
            </div>';

    echo '</div>'; 
  echo '</div>';
}
?>

</div>

<div id="dropdownPortal" class="dropdown-portal"></div>

<!-- FULLSCREEN IMAGE VIEWER -->
<div id="imgViewer" onclick="closeImgViewer()">
  <div id="imgViewerClose">&times;</div>
  <img id="imgViewerImg" src="">
</div>

<script>
function lihatProfil(username){
  if(!username) return;
  window.location.href = "profil_orang.php?user=" + encodeURIComponent(username);
}

function openImgViewer(src){
  document.getElementById("imgViewerImg").src = src;
  document.getElementById("imgViewer").style.display = "flex";
}

function closeImgViewer(){
  document.getElementById("imgViewer").style.display = "none";
}
</script>

</body>
</html>
