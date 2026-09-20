<?php
session_start();
include "../db.php";

$CF_ACCOUNT_ID = getenv('CF_ACCOUNT_ID'); 
$CF_API_KEY = getenv('CF_API_KEY'); 

$badWords = [
    "sex","seks","bokep","porno","porn","xnxx","xvideos",
    "ml","making love","hentai","jav","memek","kontol","ngentot","colmek","masturbasi",
    "penis","vagina","anal","bdsm","fetish"
];

function containsBadWord($text,$badWords){
    if($text===null) return false;
    $text=mb_strtolower($text,'UTF-8');
    foreach($badWords as $w){
        if($w==='') continue;
        if(mb_stripos($text,$w,0,'UTF-8')!==false) return $w;
    }
    return false;
}

function censorText($text,$badWords){
    if($text===null) return $text;
    foreach($badWords as $w){
        if($w==='') continue;
        $pattern='/'.preg_quote($w,'/').'/iu';
        $replacement=str_repeat('*',mb_strlen($w,'UTF-8'));
        $text=preg_replace($pattern,$replacement,$text);
    }
    return $text;
}

// ---------- AJAX NSFW check ----------
if(isset($_POST['action']) && $_POST['action']==='check_images'){
    $response=['nsfw'=>['sampul'=>false,'isi'=>false,'isi2'=>false]];
    $files=['gambarSampul'=>'sampul','gambar'=>'isi','gambar2'=>'isi2'];
    foreach($files as $key=>$nsfwKey){
        if(isset($_FILES[$key]) && $_FILES[$key]['tmp_name']!==''){
            $tmpFile=$_FILES[$key]['tmp_name'];
            $bytes=file_get_contents($tmpFile);
            $b64=base64_encode($bytes);
            $model="@cf/vision/image-classification";
            $url="https://api.cloudflare.com/client/v4/accounts/{$CF_ACCOUNT_ID}/ai/run/{$model}";
            $payload=json_encode(['input'=>[['type'=>'image','data'=>$b64]]]);
            $ch=curl_init($url);
            curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
            curl_setopt($ch,CURLOPT_POST,true);
            curl_setopt($ch,CURLOPT_POSTFIELDS,$payload);
            curl_setopt($ch,CURLOPT_HTTPHEADER,[
                "Content-Type: application/json",
                "Authorization: Bearer {$CF_API_KEY}"
            ]);
            curl_setopt($ch,CURLOPT_TIMEOUT,20);
            $resp=curl_exec($ch); curl_close($ch);
            $json=json_decode($resp,true);
            $threshold=0.55;
            if(isset($json['result']) && is_array($json['result'])){
                foreach($json['result'] as $r){
                    if(isset($r['predictions'])){
                        foreach($r['predictions'] as $p){
                            $label=strtolower($p['label']??'');
                            $score=floatval($p['score']??0);
                            if(in_array($label,['nsfw','porn','nudity','explicit','sexual']) && $score >= $threshold){
                                $response['nsfw'][$nsfwKey]=true; break 2;
                            }
                        }
                    }
                }
            }
        }
    }
    echo json_encode($response); exit;
}

// ---------- HANDLE FORM SUBMIT ----------
$successMessage=""; $textWarning="";
if($_SERVER["REQUEST_METHOD"]==='POST' && !isset($_POST['action'])){
    $judul=mysqli_real_escape_string($koneksi,$_POST["judul"]??'');
    $kategori=mysqli_real_escape_string($koneksi,$_POST["kategori"]??'');
    $deskripsi=mysqli_real_escape_string($koneksi,$_POST["deskripsi"]??'');
    $isi=$_POST["isi"]??'';
    $isi2=$_POST["isi2"]??'';
    $youtubeLink=mysqli_real_escape_string($koneksi,$_POST["youtubeLink"]??'');
    $pollingQuestion=mysqli_real_escape_string($koneksi,$_POST['polling_question']??'');
    $pollingOptions=isset($_POST['polling_options'])?array_map('trim',explode(',',$_POST['polling_options'])):[];
    $pollingJson=!empty($pollingOptions)?json_encode($pollingOptions,JSON_UNESCAPED_UNICODE):null;

    // Cek teks
    $badTitle=containsBadWord($judul,$badWords);
    $badDesc=containsBadWord($deskripsi,$badWords);
    $badIsi=containsBadWord(strip_tags($isi),$badWords);
    $badIsi2=containsBadWord(strip_tags($isi2),$badWords);
    if($badTitle||$badDesc||$badIsi||$badIsi2){
        $textWarning="<div class='alert alert-warning'>Peringatan: beberapa kata tersensor otomatis.</div>";
        $judul=censorText($judul,$badWords);
        $deskripsi=censorText($deskripsi,$badWords);
        $isi=censorText($isi,$badWords);
        $isi2=censorText($isi2,$badWords);
    }

    $uploadDir="../uploads/";
    if(!is_dir($uploadDir)) mkdir($uploadDir,0755,true);

    $files=['gambarSampul','gambar','gambar2'];
    $savedFiles=[]; $blocked=false;

    foreach($files as $f){
        if(isset($_FILES[$f]) && $_FILES[$f]['name']!==''){
            $ext=pathinfo($_FILES[$f]['name'],PATHINFO_EXTENSION);
            $name=bin2hex(random_bytes(8)).($ext?'.'.$ext:'');
            $tmp=$_FILES[$f]['tmp_name'];
            if(!move_uploaded_file($tmp,$uploadDir.$name)){
                $successMessage.="<div class='alert alert-danger'>Gagal upload $f.</div>"; $blocked=true;
            }
            $savedFiles[$f]=$name;
        }else{$savedFiles[$f]='';}
    }

    if(!$blocked){
        $sql="INSERT INTO berita (judul,kategori,deskripsi,gambar_sampul,isi,isi2,youtube_link,gambar,gambar2,tanggal) 
              VALUES ('$judul','$kategori','$deskripsi','{$savedFiles['gambarSampul']}','$isi','$isi2','$youtubeLink','{$savedFiles['gambar']}','{$savedFiles['gambar2']}',NOW())";
        if(mysqli_query($koneksi,$sql)){
            $beritaId=mysqli_insert_id($koneksi);
            if(!empty($pollingQuestion) && !empty($pollingOptions)){
                $pollingQuestion_q=mysqli_real_escape_string($koneksi,$pollingQuestion);
                mysqli_query($koneksi,"INSERT INTO polling (berita_id, question, options) VALUES ($beritaId,'$pollingQuestion_q','".mysqli_real_escape_string($koneksi,$pollingJson)."')");
            }
            $successMessage.="<div class='alert alert-success'>Berita berhasil disimpan.</div>";
        }else{
            $successMessage.="<div class='alert alert-danger'>Gagal simpan ke DB: ".htmlspecialchars(mysqli_error($koneksi))."</div>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1"/>
<title>Tambah Berita</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"/>
<script src="https://cdn.ckeditor.com/4.21.0/standard/ckeditor.js"></script>
<style>
body{background:linear-gradient(135deg,#eef2f3,#d9e4ec);min-height:100vh;display:flex;align-items:center;justify-content:center;padding:30px;}
.container{max-width:950px;background:#fff;padding:35px 40px;border-radius:16px;box-shadow:0 8px 25px rgba(0,0,0,0.08);animation:fadeIn 0.5s ease;}
h1{font-weight:700;color:#2c3e50;margin-bottom:30px;text-align:center;}
label{font-weight:600;margin-bottom:6px;}
.form-control,.form-select{border-radius:10px;padding:10px 14px;border:1px solid #ced4da;transition:.2s;}
.form-control:focus,.form-select:focus{border-color:#007bff;box-shadow:0 0 0 .15rem rgba(0,123,255,.2);}
.preview-img{margin-top:8px;max-height:120px;border-radius:10px;display:none;}
.btn-primary{background:linear-gradient(90deg,#007bff,#0069d9);border:none;padding:12px 35px;font-size:1rem;border-radius:10px;font-weight:600;transition:.3s;}
.btn-primary:hover{background:linear-gradient(90deg,#0056b3,#004494);transform:translateY(-2px);}
@keyframes fadeIn{from{opacity:0;transform:translateY(15px);}to{opacity:1;transform:translateY(0);}}
.text-danger-small{font-size:0.9rem;margin-top:4px;}
</style>
</head>
<body>
<div class="container shadow-sm">
<h1>Tambah Berita</h1>
<?= $textWarning.$successMessage; ?>
<form id="formBerita" action="" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
<div class="row g-3">
<div class="col-md-12">
<label for="judul" class="form-label">Judul Berita <span class="text-danger">*</span></label>
<input type="text" id="judul" name="judul" class="form-control" placeholder="Masukkan judul berita" required />
<div class="invalid-feedback">Judul berita wajib diisi.</div>
</div>
<div class="col-md-6">
<label for="kategori" class="form-label">Kategori Berita <span class="text-danger">*</span></label>
<select id="kategori" name="kategori" class="form-select" required>
<option value="" disabled selected>Pilih kategori</option>
<option value="Teknologi">Teknologi</option>
<option value="Nasional">Nasional</option>
<option value="Internasional">Internasional</option>
<option value="Ekonomi">Ekonomi</option>
<option value="Pendidikan">Pendidikan</option>
<option value="Olahraga">Olahraga</option>
<option value="Hiburan">Hiburan</option>
<option value="Seni">Seni</option>
<option value="Kesehatan">Kesehatan</option>
<option value="Game">Game</option>
</select>
</div>
<div class="col-md-6">
<label for="youtubeLink" class="form-label">Link YouTube (Opsional)</label>
<input type="url" id="youtubeLink" name="youtubeLink" class="form-control" placeholder="https://www.youtube.com/watch?v=..." />
</div>
<div class="col-md-12">
<label for="deskripsi" class="form-label">Deskripsi Singkat <span class="text-danger">*</span></label>
<textarea id="deskripsi" name="deskripsi" class="form-control" rows="3" placeholder="Tulis deskripsi singkat berita" required></textarea>
</div>
<div class="col-md-6">
<label for="gambarSampul" class="form-label">Gambar Sampul <span class="text-danger">*</span></label>
<input type="file" id="gambarSampul" name="gambarSampul" class="form-control" accept="image/*" required />
<img id="previewSampul" class="preview-img" />
<div id="nsfwSampul" class="text-danger mt-1"></div>
</div>
<div class="col-md-6">
<label for="gambar" class="form-label">Gambar Isi Artikel <span class="text-danger">*</span></label>
<input type="file" id="gambar" name="gambar" class="form-control" accept="image/*" required />
<img id="previewIsi" class="preview-img" />
<div id="nsfwIsi" class="text-danger mt-1"></div>
</div>
<div class="col-md-12">
<label for="gambar2" class="form-label">Gambar Tambahan (Opsional)</label>
<input type="file" id="gambar2" name="gambar2" class="form-control" accept="image/*" />
<img id="previewIsi2" class="preview-img" />
<div id="nsfwIsi2" class="text-danger mt-1"></div>
</div>
<div class="col-md-12">
<label for="isi" class="form-label">Isi Berita 1 <span class="text-danger">*</span></label>
<textarea id="isi" name="isi" class="form-control" rows="10" required></textarea>
</div>
<div class="col-md-12">
<label for="isi2" class="form-label">Isi Berita 2 (Opsional)</label>
<textarea id="isi2" name="isi2" class="form-control" rows="10"></textarea>
</div>
<hr class="my-4">
<div class="col-md-12">
<h5 class="mb-3">Polling (Opsional)</h5>
</div>
<div class="col-md-12">
<label for="polling_question" class="form-label">Pertanyaan Polling</label>
<input type="text" id="polling_question" name="polling_question" class="form-control" placeholder="Masukkan pertanyaan polling" />
</div>
<div class="col-md-12">
<label for="polling_options" class="form-label">Opsi Polling</label>
<small class="form-text">Pisahkan opsi dengan tanda koma (,)</small>
<input type="text" id="polling_options" name="polling_options" class="form-control" placeholder="Contoh: Pilihan 1, Pilihan 2, Pilihan 3" />
</div>
<div class="col-md-12 text-center mt-4">
<button type="submit" id="btnSubmit" class="btn btn-primary px-5">Simpan Berita</button>
</div>
</div>
</form>
</div>
<script>
CKEDITOR.replace('isi'); CKEDITOR.replace('isi2');
let nsfwStatus={sampul:false,isi:false,isi2:false};

function handlePreviews(){
    const files={sampul:'gambarSampul',isi:'gambar',isi2:'gambar2'};
    for(const key in files){
        const input=document.getElementById(files[key]);
        const preview=document.getElementById('preview'+key.charAt(0).toUpperCase()+key.slice(1));
        const nsfwText=document.getElementById('nsfw'+key.charAt(0).toUpperCase()+key.slice(1));
        input.addEventListener('change',()=>{
            const f=input.files[0];
            if(!f){ preview.style.display='none'; nsfwText.textContent=''; nsfwStatus[key]=false; return;}
            const reader=new FileReader();
            reader.onload=e=>{preview.src=e.target.result; preview.style.display='block';}
            reader.readAsDataURL(f);

            // cek NSFW instan saat file dipilih
            const formData=new FormData();
            formData.append('action','check_images');
            formData.append(files[key],f);
            fetch('',{method:'POST',body:formData}).then(r=>r.json()).then(j=>{
                nsfwStatus[key]=j.nsfw[key];
                nsfwText.textContent=j.nsfw[key]?'Gambar terdeteksi 18+ / NSFW!':'';
            });
        });
    }
}
handlePreviews();

document.getElementById('formBerita').addEventListener('submit',function(e){
    if(nsfwStatus.sampul||nsfwStatus.isi||nsfwStatus.isi2){
        e.preventDefault();
        alert('Form tidak bisa dikirim karena ada gambar yang terdeteksi 18+ / NSFW!');
    }
});

// Bootstrap validation
(function(){'use strict';
var forms=document.querySelectorAll('.needs-validation');
Array.prototype.slice.call(forms).forEach(function(form){
    form.addEventListener('submit',function(event){
        if(!form.checkValidity()){ event.preventDefault(); event.stopPropagation(); }
        form.classList.add('was-validated');
    },false);
});})();
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

