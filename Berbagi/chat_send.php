<?php
session_start();
include '../db.php'; // pastikan file ini menginisialisasi $koneksi (mysqli)

header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['username'])) {
    echo json_encode(['ok'=>false,'error'=>'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['ok'=>false,'error'=>'Invalid method']);
    exit;
}

/* -------------------------------
   ANTI-SPAM: pesan (3s) & file (5s)
   ------------------------------- */
if (!isset($_SESSION['last_chat_time'])) $_SESSION['last_chat_time'] = 0;
if (time() - $_SESSION['last_chat_time'] < 3) {
    echo json_encode(['ok'=>false,'error'=>'Terlalu cepat! Kirim pesan setiap 3 detik.']);
    exit;
}
$_SESSION['last_chat_time'] = time();

/* -------------------------------
   INPUT
   ------------------------------- */
$username = $_SESSION['username'];
$message  = isset($_POST['message']) ? trim((string)$_POST['message']) : '';

/* -------------------------------
   BADWORD FILTER (sederhana, bisa ditambah)
   ------------------------------- */
$badwords = [
    'anjing','kontol','memek','babi','tolol','idiot','goblok','bangsat','kampang',
    'asu','pler','lonte','peleq','peler','ngentot','ngentod','entod','entot','jembut',
    'meki','pepek','puki','pukimak','setan','tai','brengsek','bajingan','dancok',
    'dancuk','cuk','titit','tempik','kampret','kntl','kintil','keparat','fuck','fck',
    'shit','anjir','anjay','anjayy','bacot','bct','bodoh','stupid','idiot'
];

foreach ($badwords as $w) {
    $pattern = '/\b' . preg_quote($w, '/') . '\b/i';
    $message = preg_replace($pattern, str_repeat('*', min(5, mb_strlen($w))), $message);
}

/* -------------------------------
   NORMALIZE FILES: accept single 'file' or multi 'files[]'
   ------------------------------- */
$filesArray = [];
if (!empty($_FILES['files']) && isset($_FILES['files']['name']) && is_array($_FILES['files']['name'])) {
    $filesArray = $_FILES['files'];
} elseif (!empty($_FILES['file']) && isset($_FILES['file']['name']) && $_FILES['file']['name'] !== '') {
    // convert single to array-like structure
    $filesArray = [
        'name' => [$_FILES['file']['name']],
        'type' => [$_FILES['file']['type']],
        'tmp_name' => [$_FILES['file']['tmp_name']],
        'error' => [$_FILES['file']['error']],
        'size' => [$_FILES['file']['size']],
    ];
}

/* -------------------------------
   MULTI-FILE PROCESSING (filesArray)
   ------------------------------- */
$filePaths = [];
$contentType = 'text';

if (!empty($filesArray) && isset($filesArray['name']) && is_array($filesArray['name'])) {

    // anti-spam upload (server-side) - 5s cooldown
    if (!isset($_SESSION['last_upload_time'])) $_SESSION['last_upload_time'] = 0;
    if (time() - $_SESSION['last_upload_time'] < 5) {
        echo json_encode(['ok'=>false,'error'=>'Terlalu cepat upload file! Tunggu beberapa detik.']);
        exit;
    }
    $_SESSION['last_upload_time'] = time();

    $allowedExt = ['jpg','jpeg','png','gif','webp','pdf'];
    $count = count($filesArray['name']);

    for ($i = 0; $i < $count; $i++) {
        $err = $filesArray['error'][$i];
        if ($err !== UPLOAD_ERR_OK) {
            if ($err === UPLOAD_ERR_INI_SIZE || $err === UPLOAD_ERR_FORM_SIZE) {
                echo json_encode(['ok'=>false,'error'=>'Salah satu file melebihi batas upload server.']);
                exit;
            }
            // skip other errors gracefully
            continue;
        }

        $tmp  = $filesArray['tmp_name'][$i];
        $name = $filesArray['name'][$i];
        $size = $filesArray['size'][$i];
        $ext  = strtolower(pathinfo($name, PATHINFO_EXTENSION));

        // size check 50MB
        if ($size > 50 * 1024 * 1024) {
            echo json_encode(['ok'=>false,'error'=>'Salah satu file melebihi 50MB.']);
            exit;
        }

        if (!in_array($ext, $allowedExt)) {
            echo json_encode(['ok'=>false,'error'=>'Tipe file tidak didukung: ' . htmlspecialchars($ext)]);
            exit;
        }

        // MIME check (tolerant)
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime  = finfo_file($finfo, $tmp);
        finfo_close($finfo);

        $isValidMime = false;
        if (in_array($ext, ['jpg','jpeg','png','gif','webp'])) {
            if (strpos($mime, 'image/') === 0) $isValidMime = true;
        } elseif ($ext === 'pdf') {
            // accept application/pdf and fallback octet-stream (some setups)
            if ($mime === 'application/pdf' || $mime === 'application/octet-stream') $isValidMime = true;
        }

        if (!$isValidMime) {
            echo json_encode(['ok'=>false,'error'=>'File berbahaya terdeteksi atau MIME tidak valid (' . htmlspecialchars($mime) . ').']);
            exit;
        }

        // simpan file
        $newName = time() . '_' . bin2hex(random_bytes(6)) . '.' . $ext;
        $destDir = __DIR__ . '/uploads/';
        if (!is_dir($destDir)) {
            if (!mkdir($destDir, 0755, true)) {
                echo json_encode(['ok'=>false,'error'=>'Server tidak dapat membuat folder upload.']);
                exit;
            }
        }
        $dest = $destDir . $newName;
        if (!move_uploaded_file($tmp, $dest)) {
            echo json_encode(['ok'=>false,'error'=>'Gagal menyimpan file.']);
            exit;
        }

        // store path relative to this script so front-end can access
        // If chat_fetch.php lives elsewhere, sesuaikan pathnya
        $filePaths[] = 'uploads/' . $newName;
    }

    if (count($filePaths) > 0) $contentType = 'files';
}

/* -------------------------------
   If nothing to save -> exit
   ------------------------------- */
if ($message === '' && count($filePaths) === 0) {
    echo json_encode(['ok'=>false,'error'=>'Pesan kosong']);
    exit;
}

/* -------------------------------
   Reply handling
   ------------------------------- */
$replyTo = 0;
if (isset($_POST['reply_to']) && is_numeric($_POST['reply_to'])) {
    $replyTo = (int) $_POST['reply_to'];
}

/* -------------------------------
   Save into database
   ------------------------------- */
$filePathJson = json_encode($filePaths, JSON_UNESCAPED_SLASHES);

$sql = "INSERT INTO chat (username, message, content_type, file_path, reply_to)
        VALUES (?, ?, ?, ?, ?)";
$stmt = mysqli_prepare($koneksi, $sql);
if (!$stmt) {
    echo json_encode(['ok'=>false,'error'=>'DB prepare error']);
    exit;
}
mysqli_stmt_bind_param($stmt, 'ssssi', $username, $message, $contentType, $filePathJson, $replyTo);
$exec = mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

if (!$exec) {
    echo json_encode(['ok'=>false,'error'=>'Gagal menyimpan ke database']);
    exit;
}

/* success */
echo json_encode(['ok'=>true]);
exit;
