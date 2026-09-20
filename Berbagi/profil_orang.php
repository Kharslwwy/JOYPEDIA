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
:root {
  /* Warna default (tema terang) */
  --bg-body: #f5f6f8;
  --bubble-other: #f0f2f5;
  --bubble-self: #e0ecff;
  --text-other: #ffffffff;
  --text-dim: #f0f2f5;      /* abu-abu medium utk tanggal & tombol balas */
  --text-self: #1f2937;
  --menu-hover: rgba(0,0,0,0.05);
  --dropdown-bg: #ffffff;
  --dropdown-text: #111827;
}

/* Tema gelap browser → UI terang */
@media (prefers-color-scheme: dark) {
  :root {
    --bg-app:#f3f4f6;
    --bg-header:#e5e7eb;
    --bg-chat:#f9fafb;
    --bg-msg:#ffffff;
    --bg-self:#2563eb; /* tetap biru, supaya konsisten */
    --bg-input:#e5e7eb;
    --text:#111827;
    --text-dim:#4b5563;
    --menu-bg:#111827;
    --menu-text:#ffffff;
    --btn-border:rgba(0,0,0,.25);
    --btn-hover-bg:rgba(0,0,0,.08);
  }
}

body {
  font-family: var(--font, sans-serif);
  color: var(--text);
  background: var(--bg-app);
  margin: 0;
  padding: 0;
}

.chat-container {
  background: var(--bg-chat);
  padding: 10px;
}

/* Bubble umum */
.bubble {
  background: var(--bg-msg);
  color: var(--text);
  padding: 10px 14px;
  border-radius: 12px;
  max-width: 70%;
  position: relative;
  margin-bottom: 8px;
  word-wrap: break-word;
}

/* Bubble kita */
.chat-row.self .bubble {
  background: var(--bg-self);
  color: #fff; /* supaya kontras */
}

/* Titik tiga (dropdown trigger) */
.bubble .menu-trigger {
  position: absolute;
  top: 8px;
  right: -32px; /* beri jarak agar tidak mepet bubble */
  cursor: pointer;
  font-size: 18px;
  padding: 4px;
  border-radius: 50%;
  transition: background 0.2s;
}
.bubble .menu-trigger:hover {
  background: var(--btn-hover-bg);
}

/* Dropdown menu */
.dropdown-portal {
  position: absolute;
  top: 0;
  right: -130px; /* geser keluar supaya tidak menempel */
  background: var(--menu-bg);
  color: var(--menu-text);
  border: 1px solid rgba(0,0,0,.1);
  border-radius: 8px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.15);
  padding: 6px 0;
  min-width: 110px;
  z-index: 1000;
}

.dropdown-portal button {
  display: block;
  width: 100%;
  background: none;
  border: none;
  text-align: left;
  padding: 8px 12px;
  cursor: pointer;
  color: inherit;
}
.dropdown-portal button:hover {
  background: rgba(0,0,0,0.05);
}

/* Reply button */
.bubble .reply-button,
.bubble .btn-reply,
.bubble button.reply {
  border: 1px solid var(--btn-border);
  background: transparent;
  border-radius: 8px;
  font-size: 12px;
  padding: 2px 6px;
  cursor: pointer;
}
.bubble .reply-button:hover,
.bubble .btn-reply:hover,
.bubble button.reply:hover {
  background: var(--btn-hover-bg);
}

*{box-sizing:border-box}
html,body{height:100%;width:100%;overflow:hidden;margin:0;padding:0}
body{font-family:var(--font);color:var(--text);background:var(--bg-app)}

.page{
  height:100dvh;
  width:100vw;
  display:grid;
  grid-template-rows:auto 1fr auto;
  overflow:hidden
}

/* Header */
.header{
  background:var(--bg-header);
  color:#fff;
  padding:12px 16px;
  box-shadow:0 2px 12px rgba(0,0,0,.25)
}
.header h2{margin:0;font-size:18px}

/* Chat area */
#chatBox{
  background:var(--bg-chat);
  padding:12px 10px;
  padding-bottom:92px;
  overflow-y:auto;
  overflow-x:hidden;
  scrollbar-width:none;
  position:relative;
}
#chatBox::-webkit-scrollbar{width:0;height:0}

/* Chat bubble */
.chat-row{display:flex;gap:8px;margin:6px 0}
.chat-row.self{flex-direction:row-reverse}
.avatar{
  width:var(--avatar);
  height:var(--avatar);
  border-radius:50%;
  flex-shrink:0;
  background:#374151
}
.bubble{
  position:relative;
  max-width:min(480px,58%);
  background:var(--bg-msg);
  border-radius:var(--radius);
  padding:8px 10px;
  padding-right:36px;
  word-wrap:break-word;
  overflow-wrap:anywhere;
  font-size:13.5px;
  line-height:1.35;
  color:var(--text);
}
.chat-row.self .bubble{
  background:var(--bg-self);
  color:#fff;
}
.name{font-size:12.5px;font-weight:600}
.time{font-size:10px;color:var(--text-dim);margin-left:6px}
.chat-reply{
  font-size:11px;
  background:#0b1220;
  padding:6px;
  border-left:3px solid #22d3ee;
  border-radius:6px;
  margin:6px 0;
  color:#d1d5db
}
.msg-img,.bubble img,.bubble video{
  max-width:180px;
  height:auto;
  border-radius:8px;
  display:block
}
a.msg-pdf{color:#22d3ee;text-decoration:none;font-weight:600}

/* Tombol balas */
.bubble .reply-button,
.bubble .btn-reply,
.bubble button.reply{
  display:inline-flex;
  align-items:center;
  gap:6px;
  padding:3px 9px;
  min-height:24px;
  font-size:12px;
  font-weight:600;
  background:transparent;
  color:var(--text);
  border:1px solid var(--btn-border);
  border-radius:999px;
  cursor:pointer;
  transition:background .15s,border-color .15s,transform .05s;
  margin-top:4px;
}
.bubble .reply-button::before,
.bubble .btn-reply::before,
.bubble button.reply::before{
  content:"↩";
  font-size:12px;
  opacity:.9;
}
.bubble .reply-button:hover,
.bubble .btn-reply:hover,
.bubble button.reply:hover{
  background:var(--btn-hover-bg);
  border-color:rgba(255,255,255,.35);
}
.bubble .reply-button:active,
.bubble .btn-reply:active,
.bubble button.reply:active{
  transform:translateY(1px);
}

/* Tombol titik tiga */
.menu-btn{
  position:absolute;
  top:4px;
  right:4px;
  background:transparent;
  border:none;
  color:var(--text-dim);
  cursor:pointer;
  font-size:16px;
  line-height:1;
  width:24px;
  height:24px;
  display:flex;
  align-items:center;
  justify-content:center;
  border-radius:6px;
}
.menu-btn:hover{background:rgba(0,0,0,.1)}

/* Input bar */
.inputbar{
  position:fixed;
  left:0;right:0;bottom:0;
  background:var(--bg-input);
  padding:10px;
  border-top:1px solid rgba(255,255,255,.14);
  box-shadow:0 -6px 16px rgba(0,0,0,.25);
  z-index:1500;
}
#chatForm{
  display:grid;
  grid-template-columns:auto 1fr auto;
  gap:8px;
  align-items:center;
  margin:0
}
#file{
  background:#083d46;
  color:#e5e7eb;
  border:1px solid rgba(255,255,255,.15);
  border-radius:8px;
  padding:4px 6px;
  min-width:120px;
  max-width:40vw;
  font-size:12px
}
#message{
  background:#0f172a;
  color:#e5e7eb;
  border:1px solid rgba(255,255,255,.15);
  border-radius:16px;
  padding:8px 12px;
  outline:none;
  width:100%;
  min-height:36px;
  max-height:100px;
  overflow-y:auto;
  font-size:13.5px
}
#message::placeholder{color:#9ca3af}
#sendBtn{
  background:#f59e0b;
  color:#111827;
  border:none;
  border-radius:16px;
  padding:8px 14px;
  font-weight:700;
  cursor:pointer;
  transition:transform .05s ease;
  font-size:13px
}
#sendBtn:disabled{opacity:.6;cursor:not-allowed}
#sendBtn:active{transform:translateY(1px)}

/* Panel balasan */
.reply-indicator{
  display:none;
  align-items:center;
  gap:6px;
  background:#0b1220;
  padding:5px 8px;
  border-left:3px solid #22d3ee;
  border-radius:6px;
  margin:0 0 6px 0;
  color:#d1d5db;
  font-size:12px
}
.reply-indicator button{
  background:transparent;
  border:none;
  color:#b9bbbe;
  cursor:pointer;
  font-size:12px
}
.reply-indicator button:hover{color:#ff5c5c}

/* Dropdown portal */
.dropdown-portal{
  position:absolute;
  background:var(--menu-bg);
  color:var(--menu-text);
  border:1px solid rgba(0,0,0,.1);
  border-radius:8px;
  box-shadow:0 6px 16px rgba(0,0,0,.25);
  min-width:180px;
  max-width:90vw;
  max-height:50vh;
  overflow:auto;
  z-index:900;
  padding:6px;
  display:none;
}
.dropdown-portal.show{display:block}
.dropdown-portal button{
  width:100%;
  text-align:left;
  background:transparent;
  border:none;
  padding:8px 12px;
  border-radius:6px;
  font-size:13px;
  cursor:pointer;
  color:inherit;
}
.dropdown-portal button:hover{background:rgba(0,0,0,.05)}

/* === Tambahan override agar teks bubble kita selalu putih di tema terang === */
@media (prefers-color-scheme: dark) {
  .chat-row.self .bubble {
    color: #fff !important;
  }
}

/* === Tambahan untuk tema terang agar teks & tombol balas lebih enak dilihat === */
.chat-row .time {
  color: #6b7280 !important; /* abu-abu sedang */
}

.chat-row .reply-button {
  color: #374151 !important; /* abu-abu gelap agar terbaca */
  border-color: rgba(0, 0, 0, 0.2) !important;
  background: transparent !important;
}

.chat-row .reply-button:hover {
  background: rgba(0, 0, 0, 0.05) !important;
}



</style>
</head>
<body>

<div class="page">
  <div class="header"><h2>JOY FAM'S</h2></div>

  <div id="chatBox"></div>

  <div class="inputbar">
    <div id="replyIndicator" class="reply-indicator">
      Membalas: <span id="replyLabel"></span>
      <button type="button" id="cancelReply">✕</button>
    </div>

    <form id="chatForm" enctype="multipart/form-data">
      <input type="file" id="file" name="file" accept=".jpg,.jpeg,.png,.gif,.pdf">
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

/* Load chat tanpa auto-scroll */
const chatBox = document.getElementById('chatBox');
let menuOpen = false;
function loadChat(){
  if (menuOpen) return;
  fetch('chat_fetch.php',{cache:'no-store'})
    .then(r=>r.text())
    .then(html=>{chatBox.innerHTML=html;})
    .catch(err=>console.error(err));
}
setInterval(loadChat,1500);
loadChat();

/* Submit */
const form=document.getElementById('chatForm');
const fileEl=document.getElementById('file');
const sendBtn=document.getElementById('sendBtn');
const messageEl=document.getElementById('message');
form.addEventListener('submit',async e=>{
  e.preventDefault();
  const f=fileEl.files[0]||null;
  const text=messageEl.value.trim();
  if(!text && !f) return;

  messageEl.value='';
  fileEl.value='';
  sendBtn.disabled=true;
  sendBtn.textContent='Mengirim...';

  const data=new FormData();
  data.append('message',text);
  if(f) data.append('file',f);
  if(replyTo) data.append('reply_to',replyTo);

  try{
    const r=await fetch('chat_send.php',{method:'POST',body:data});
    const t=await r.text();
    try{JSON.parse(t);}catch{console.error('Invalid JSON',t);}
  }catch(e){alert('Koneksi gagal');}
  finally{
    loadChat();
    clearReply();
    sendBtn.disabled=false;
    sendBtn.textContent='Kirim';
    messageEl.focus();
  }
});

/* ====== MENU TITIK TIGA: nempel ke bubble & ikut scroll ====== */
const portal = document.getElementById('dropdownPortal');
const box    = document.getElementById('chatBox');

function openMenu(ev, btn){
  ev.preventDefault();
  ev.stopPropagation();
  menuOpen = true; // supaya loadChat tidak overwrite

  // Pastikan portal berada di dalam #chatBox agar ikut scroll
  if (portal.parentElement !== box) {
    box.appendChild(portal);
  }

  const row    = btn.closest('.chat-row');
  const id     = row ? parseInt(row.dataset.id, 10) : 0;
  const canAll = btn.getAttribute('data-can-all') === '1'; // dari chat_fetch.php

  // Bangun isi menu
  portal.innerHTML = '';

  const meBtn = document.createElement('button');
  meBtn.textContent = 'Hapus untuk saya';
  meBtn.onclick = () => confirmDelete(id, 'me');
  portal.appendChild(meBtn);

  if (canAll) {
    const allBtn = document.createElement('button');
    allBtn.textContent = 'Hapus untuk semua';
    allBtn.onclick = () => confirmDelete(id, 'all');
    portal.appendChild(allBtn);
  }

  // Tampilkan dulu untuk ambil ukuran
  portal.style.left = '-9999px';
  portal.style.top  = '-9999px';
  portal.classList.add('show');

  // Hitung posisi RELATIF terhadap #chatBox
  const btnRect = btn.getBoundingClientRect();
  const boxRect = box.getBoundingClientRect();
  const pw = portal.offsetWidth, ph = portal.offsetHeight;
  const margin = 6;

  // Posisi vertikal: default di bawah tombol
  let top = box.scrollTop + (btnRect.bottom - boxRect.top) + margin;
  // Jika mentok bawah konten, buka ke atas
  if (top + ph > box.scrollHeight - 10) {
    top = box.scrollTop + (btnRect.top - boxRect.top) - ph - margin;
    if (top < 0) top = 0;
  }

  // Posisi horizontal: sejajarkan kanan tombol
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

// Tutup menu jika klik di luar atau tekan ESC
document.addEventListener('click', (e)=>{
  if (!portal.contains(e.target) && !e.target.closest('.menu-btn')) {
    closePortal();
  }
});
document.addEventListener('keydown', (e)=>{
  if (e.key === 'Escape') closePortal();
});

// Hapus pesan (panggil backend kamu)
function confirmDelete(id, mode){
  if (!id) return;
  const msg = mode === 'all'
    ? 'Yakin hapus untuk SEMUA?'
    : 'Hapus untuk SAYA saja?';
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
      loadChat(); // refresh list
    } else if (status === 403) {
      alert('Tidak diizinkan: hanya admin bisa hapus untuk semua');
    } else {
      alert(data.error || 'Gagal menghapus');
    }
  })
  .catch(() => alert('Koneksi gagal'));
}
let firstLoad = true; // <-- tambahkan flag global

function loadChat(){
  if (menuOpen) return;
  fetch('chat_fetch.php',{cache:'no-store'})
    .then(r=>r.text())
    .then(html=>{
      chatBox.innerHTML = html;

      // HANYA scroll ke bawah saat pertama kali halaman dibuka
      if (firstLoad) {
        chatBox.scrollTop = chatBox.scrollHeight;
        firstLoad = false;
      }
    })
    .catch(err=>console.error(err));
}


</script>
</body>
</html>