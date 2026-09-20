<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../Login/login.php");
    exit();
}
$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Berbagi Informasi</title>

<style>
:root{
  --bg-app:#0f172a;
  --bg-header:#0b5c6b;
  --bg-chat:#111827;
  --bg-msg:#1f2937;
  --bg-self:#2563eb;
  --bg-input:#0b5c6b;
  --text:#e5e7eb;
  --text-dim:#9ca3af;
  --radius:10px;
  --avatar:28px;
  --font:system-ui,-apple-system,"Segoe UI",Roboto,Arial,sans-serif;
}
*{box-sizing:border-box}
html,body{height:100%;width:100%;overflow:hidden;margin:0;padding:0}
body{font-family:var(--font);color:var(--text);background:var(--bg-app)}

.page{height:100dvh;width:100vw;display:grid;grid-template-rows:auto 1fr auto;overflow:hidden}

/* Header */
.header{background:var(--bg-header);color:#fff;padding:12px 16px;box-shadow:0 2px 12px rgba(0,0,0,.25)}
.header h2{margin:0;font-size:18px}

/* Chat area */
#chatBox{
  background:var(--bg-chat);
  padding:12px 10px;
  padding-bottom:32px;
  overflow-y:auto;overflow-x:hidden;
  scrollbar-width:none;
  position: relative;
}
#chatBox::-webkit-scrollbar{width:0;height:0}

/* Bubble */
.chat-row{display:flex;gap:8px;margin:6px 0}
.chat-row.self{flex-direction:row-reverse}
.avatar{width:var(--avatar);height:var(--avatar);border-radius:50%;flex-shrink:0;background:#374151}
.bubble{
  position:relative;
  max-width:min(480px,58%);
  background:var(--bg-msg);
  border-radius:var(--radius);
  padding:8px 10px;padding-right:36px;
  word-wrap:break-word;overflow-wrap:anywhere;
  font-size:13.5px;line-height:1.35;
}
.chat-row.self .bubble{background:var(--bg-self)}
.name{font-size:12.5px;font-weight:600}
.time{font-size:10px;color:var(--text-dim);margin-left:6px}
.chat-reply{font-size:11px;background:#0b1220;padding:6px;border-left:3px solid #22d3ee;border-radius:6px;margin:6px 0;color:#d1d5db}
.msg-img,.bubble img,.bubble video{max-width:180px;height:auto;border-radius:8px;display:block}
a.msg-pdf{color:#22d3ee;text-decoration:none;font-weight:600}

/* Tombol balas */
.bubble .reply-button,
.bubble .btn-reply,
.bubble button.reply{
  display:inline-flex;align-items:center;gap:6px;
  padding:3px 9px;min-height:24px;
  font-size:12px;font-weight:600;
  background:transparent;color:#e5e7eb;
  border:1px solid rgba(255,255,255,.25);
  border-radius:999px;
  cursor:pointer;
  transition:background .15s,border-color .15s,transform .05s;
  margin-top:4px;
}
.bubble .reply-button::before,
.bubble .btn-reply::before,
.bubble button.reply::before{
  content:"↩";font-size:12px;opacity:.9;
}
.bubble .reply-button:hover,
.bubble .btn-reply:hover,
.bubble button.reply:hover{
  background:rgba(255,255,255,.08);
  border-color:rgba(255,255,255,.35);
}
.bubble .reply-button:active,
.bubble .btn-reply:active,
.bubble button.reply:active{
  transform:translateY(1px);
}

/* Tombol ⋮ */
.menu-btn{
  position:absolute;top:4px;right:4px;background:transparent;border:none;color:#d1d5db;
  cursor:pointer;font-size:16px;line-height:1;width:24px;height:24px;display:flex;align-items:center;justify-content:center;border-radius:6px
}
.menu-btn:hover{background:#303339}

.chat-row.self .menu-btn{
  right: auto !important;
  left: -28px !important;   /* semakin negatif → semakin menjauh dari bubble */
}



/* Input bar */
.inputbar{
  position:fixed;left:0;right:0;bottom:0;
  background:var(--bg-input);
  padding:10px;
  border-top:1px solid rgba(255,255,255,.14);
  box-shadow:0 -6px 16px rgba(0,0,0,.25);
  z-index:1500;
}
#chatForm{display:grid;grid-template-columns:auto 1fr auto;gap:8px;align-items:center;margin:0}
#file{background:#083d46;color:#e5e7eb;border:1px solid rgba(255,255,255,.15);border-radius:8px;padding:4px 6px;min-width:120px;max-width:40vw;font-size:12px}
#message{background:#0f172a;color:#e5e7eb;border:1px solid rgba(255,255,255,.15);border-radius:16px;padding:8px 12px;outline:none;width:100%;min-height:36px;max-height:100px;overflow-y:auto;font-size:13.5px}
#message::placeholder{color:#9ca3af}
#sendBtn{background:#f59e0b;color:#111827;border:none;border-radius:16px;padding:8px 14px;font-weight:700;cursor:pointer;transition:transform .05s ease;font-size:13px}
#sendBtn:disabled{opacity:.6;cursor:not-allowed}
#sendBtn:active{transform:translateY(1px)}

/* Panel balasan */
.reply-indicator{display:none;align-items:center;gap:6px;background:#0b1220;padding:5px 8px;border-left:3px solid #22d3ee;border-radius:6px;margin:0 0 6px 0;color:#d1d5db;font-size:12px}
.reply-indicator button{background:transparent;border:none;color:#b9bbbe;cursor:pointer;font-size:12px}
.reply-indicator button:hover{color:#ff5c5c}

/* Dropdown */
.dropdown-portal{
  position:absolute;
  background:#ffffff;color:#111827;
  border:1px solid #e5e7eb;border-radius:8px;
  box-shadow:0 6px 16px rgba(0,0,0,.25);
  min-width:180px;max-width:90vw;max-height:50vh;overflow:auto;
  z-index:900;
  padding:6px;display:none
}
.dropdown-portal.show{display:block}
.dropdown-portal button{width:100%;text-align:left;background:transparent;border:none;padding:8px 10px;border-radius:6px;font-size:13px;cursor:pointer;color:inherit}
.dropdown-portal button:hover{background:#f3f4f6}

/* image overlay zoom */
#imgViewer {
  display:none;
  position:fixed;inset:0;background:rgba(0,0,0,0.85);
  z-index:9999;align-items:center;justify-content:center;
}
#imgViewer img{max-width:95%;max-height:95%;border-radius:12px}
#imgViewerClose{position:fixed;top:18px;right:24px;color:#fff;font-size:28px;cursor:pointer}
</style>
</head>
<body>
  
  <?php if (isset($_GET['deleted']) && $_GET['deleted'] == 1): ?>
  <div style="position:fixed;top:10px;left:50%;transform:translateX(-50%);
              background:#22c55e;color:white;padding:10px 20px;border-radius:8px;
              font-weight:600;z-index:3000;box-shadow:0 4px 12px rgba(0,0,0,.3);" 
       id="deleteAlert">
    ✅ Chat berhasil dihapus
  </div>
  <script>
    setTimeout(()=>{const a=document.getElementById('deleteAlert'); if(a){a.style.transition='opacity 0.5s ease'; a.style.opacity='0'; setTimeout(()=>a.remove(),500);} },3000);
  </script>
<?php endif; ?>

<div class="page">
  <div class="header"><h2>JOY FAM'S</h2></div>

  <div id="chatBox"></div>

  <button id="scrollDownBtn" 
    style="
      position:fixed;
      bottom:80px;
      right:20px;
      background:#2563eb;
      color:white;
      border:none;
      padding:10px 14px;
      font-size:20px;
      border-radius:50%;
      box-shadow:0 4px 14px rgba(0,0,0,.3);
      cursor:pointer;
      display:none;
      z-index:2000;
    ">
    ⬇
  </button>


  <div class="inputbar">
    <div id="replyIndicator" class="reply-indicator">
      Membalas: <span id="replyLabel"></span>
      <button type="button" id="cancelReply">✕</button>
    </div>

    <form id="chatForm" enctype="multipart/form-data">
      <!-- NOTE: name changed to files[] and multiple added — visual layout kept identical -->
      <input type="file" id="file" name="files[]" accept=".jpg,.jpeg,.png,.gif,.webp,.pdf" multiple>
      <input type="text" id="message" name="message" placeholder="Ketik pesan..." autocomplete="off">
      <button type="submit" id="sendBtn">Kirim</button>
    </form>
  </div>
</div>

<div id="dropdownPortal" class="dropdown-portal" role="menu" aria-hidden="true"></div>

<script>
let replyTo = null;
const indicator   = document.getElementById('replyIndicator');
const replyLabel  = document.getElementById('replyLabel');
const cancelReply = document.getElementById('cancelReply');
function reply(id, msg){
  replyTo = id;
  indicator.style.display = 'flex';
  replyLabel.textContent = msg.length > 30 ? msg.slice(0,30)+'…' : msg;
  document.getElementById('message').focus();
}
function clearReply(){
  replyTo = null;
  indicator.style.display = 'none';
  replyLabel.textContent = '';
}
if (cancelReply) cancelReply.addEventListener('click', clearReply);

const chatBox = document.getElementById('chatBox');
let menuOpen = false;

let firstLoad = true;
let lastHash = ""; // mencegah re-render jika isi sama

async function loadChat() {
    if (menuOpen) return;

    try {
        const r = await fetch("chat_fetch.php", { cache: "no-store" });
        const html = await r.text();

        // Buat hash sederhana
        const newHash = String(html.length) + "_" + String(html.charCodeAt(0) || 0);

        // Jika isi tidak berubah → JANGAN re-render DOM
        if (newHash === lastHash) return;
        lastHash = newHash;

        // Cek apakah user di bawah
        const wasAtBottom =
            chatBox.scrollTop + chatBox.clientHeight >= chatBox.scrollHeight - 100;

        // Render isi baru
        chatBox.innerHTML = html;

        // Auto-scroll hanya jika user berada di bawah sebelumnya
        if (firstLoad || wasAtBottom) {
            forceScrollBottom();
        }

        firstLoad = false;
    } catch (err) {
        console.error(err);
    }
}

setInterval(loadChat, 1500);
loadChat();


const form=document.getElementById('chatForm');
const fileEl=document.getElementById('file');
const sendBtn=document.getElementById('sendBtn');
const messageEl=document.getElementById('message');
form.addEventListener('submit',async e=>{
  e.preventDefault();

  const files = fileEl.files;
  const text = messageEl.value.trim();
  if(!text && (!files || files.length === 0)) return;

  sendBtn.disabled=true; sendBtn.textContent='Mengirim...';

  const data=new FormData();
  data.append('message',text);
  if(replyTo) data.append('reply_to',replyTo);

  // append all files as files[]
  if (files && files.length > 0){
    for (let i = 0; i < files.length; i++){
      data.append('files[]', files[i]);
    }
  }

  try{
    const r=await fetch('chat_send.php',{method:'POST',body:data});
    const t=await r.text();
    try{ JSON.parse(t); } catch(e){ console.warn('Non-JSON response:', t); }
  }catch(e){
    alert('Koneksi gagal');
    console.error(e);
  }finally{
    // reset inputs but keep visual layout unchanged
    messageEl.value='';
    fileEl.value='';
    replyTo = null;
    indicator.style.display = 'none';
    sendBtn.disabled=false;
    sendBtn.textContent='Kirim';
    loadChat();
    messageEl.focus();
  }
});

const portal = document.getElementById('dropdownPortal');
const box    = document.getElementById('chatBox');

function openMenu(ev, btn){
  ev.preventDefault();
  ev.stopPropagation();
  menuOpen = true;

  if (portal.parentElement !== box) {
    box.appendChild(portal);
  }

  const row    = btn.closest('.chat-row');
  const id     = row ? parseInt(row.dataset.id, 10) : 0;
  const canAll = btn.getAttribute('data-can-all') === '1';
  const isSelf = row && row.classList.contains('self');

  portal.innerHTML = '';

  const meBtn = document.createElement('button');
  meBtn.textContent = 'Hapus untuk saya';
  meBtn.onclick = () => confirmDelete(id, 'me');
  portal.appendChild(meBtn);

  // Hapus untuk semua (Admin boleh semua, User hanya milik sendiri)
  if (isSelf || canAll) {
    const allBtn = document.createElement('button');
    allBtn.textContent = 'Hapus untuk semua';
    allBtn.onclick = () => confirmDelete(id, 'all');
    portal.appendChild(allBtn);
  }


  if (!isSelf) {
    const reportBtn = document.createElement('button');
    reportBtn.textContent = 'Laporkan chat';
    reportBtn.onclick = () => reportChat(id);
    portal.appendChild(reportBtn);
  }

  portal.style.left = '-9999px';
  portal.style.top  = '-9999px';
  portal.classList.add('show');

  const btnRect = btn.getBoundingClientRect();
  const boxRect = box.getBoundingClientRect();
  const pw = portal.offsetWidth, ph = portal.offsetHeight;
  const margin = 6;

  let top = box.scrollTop + (btnRect.bottom - boxRect.top) + margin;
  if (top + ph > box.scrollHeight - 10) {
    top = box.scrollTop + (btnRect.top - boxRect.top) - ph - margin;
    if (top < 0) top = 0;
  }

  let left = box.scrollLeft + (btnRect.right - boxRect.left) - pw;
  const maxLeft = Math.max(0, box.scrollWidth - pw - 10);
  if (left < 10) left = 10;
  if (left > maxLeft) left = maxLeft;

  portal.style.top  = top  + 'px';
  portal.style.left = left + 'px';
}

function closePortal(){
  if (portal.classList.contains('show')) {
    portal.classList.remove('show');
    menuOpen = false;
  }
}
document.addEventListener('click', (e)=>{
  if (!portal.contains(e.target) && !e.target.closest('.menu-btn')) closePortal();
});
document.addEventListener('keydown', (e)=>{
  if (e.key === 'Escape') closePortal();
});

function confirmDelete(id, mode){
  if (!id) return;
  const msg = mode === 'all' ? 'Yakin hapus untuk SEMUA?' : 'Hapus untuk SAYA saja?';
  if (!confirm(msg)) return;

  fetch('chat_delete.php', {
    method: 'POST',
    headers: {'Content-Type':'application/json'},
    body: JSON.stringify({message_id: id, mode})
  })
  .then(r => r.json().catch(()=>({ok:false, error:'Bad JSON'})).then(data => ({status:r.status, data})))
  .then(({status, data}) => {
    if (status === 200 && data.ok) {
      closePortal();
      loadChat();
    } else if (status === 403) {
      alert('Tidak diizinkan: hanya admin bisa hapus untuk semua');
    } else {
      alert(data.error || 'Gagal menghapus');
    }
  })
  .catch(() => alert('Koneksi gagal'));
}

// === Tambahan: Fungsi Laporkan Chat ===
function reportChat(id){
  if (!id) return;
  const alasan = prompt('Masukkan alasan laporan (contoh: spam, kata kasar, dll):');
  if (!alasan) return;

  fetch('chat_report.php', {
    method: 'POST',
    headers: {'Content-Type':'application/json'},
    body: JSON.stringify({message_id:id, alasan})
  })
  .then(r => r.json().catch(()=>({ok:false,error:'Bad JSON'})).then(data=>({status:r.status,data})))
  .then(({status,data})=>{
    if (status===200 && data.ok){
      alert('Pesan berhasil dilaporkan');
      closePortal();
    }else{
      alert(data.error||'Gagal melaporkan');
    }
  })
  .catch(()=>alert('Koneksi gagal'));
}

const scrollBtn = document.getElementById("scrollDownBtn");

// cek posisi scroll
chatBox.addEventListener("scroll", () => {
    const atBottom =
      chatBox.scrollTop + chatBox.clientHeight >= chatBox.scrollHeight - 50;

    if (atBottom) {
        scrollBtn.style.display = "none";
    } else {
        scrollBtn.style.display = "block";
    }
});

// Klik tombol → scroll ke bawah
scrollBtn.addEventListener("click", () => {
    chatBox.scrollTo({
        top: chatBox.scrollHeight,
        behavior: "smooth"
    });
});

// Saat chat baru muncul, sembunyikan tombol jika auto-scroll
function forceScrollBottom() {
    chatBox.scrollTop = chatBox.scrollHeight;
    scrollBtn.style.display = "none";
}

</script>
</body>
</html>
