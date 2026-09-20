<?php
session_start();
header('Content-Type: application/json');
include '../db.php';

if (!isset($_SESSION['username'])) {
    http_response_code(401);
    echo json_encode(['ok' => false, 'error' => 'Unauthorized']);
    exit;
}

$me   = $_SESSION['username'];
$role = $_SESSION['role'] ?? 'user';

$body  = json_decode(file_get_contents('php://input'), true);
$msgId = (int)($body['message_id'] ?? 0);
$mode  = $body['mode'] ?? 'me';

if ($msgId <= 0) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'Bad id']);
    exit;
}

/* ──────────────────────────────────────────────
   MODE: HAPUS UNTUK SAYA (SEMUA USER BISA)
   ────────────────────────────────────────────── */
if ($mode === 'me') {

    // insert catatan penghapusan untuk user ini
    $sql = "INSERT IGNORE INTO message_deletions (message_id, username) VALUES (?, ?)";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param('is', $msgId, $me);
    $stmt->execute();

    echo json_encode([
        'ok'   => true,
        'mode' => 'me',
        'info' => 'Anda menghapus pesan ini'
    ]);
    exit;
}

/* ──────────────────────────────────────────────
   MODE: HAPUS UNTUK SEMUA (ADMIN ATAU PEMILIK PESAN)
   ────────────────────────────────────────────── */
elseif ($mode === 'all') {

    // Ambil info pemilik pesan
    $stmt = $koneksi->prepare("SELECT username FROM chat WHERE id = ?");
    $stmt->bind_param('i', $msgId);
    $stmt->execute();
    $res = $stmt->get_result();
    $row = $res->fetch_assoc();

    if (!$row) {
        http_response_code(404);
        echo json_encode(['ok' => false, 'error' => 'Pesan tidak ditemukan']);
        exit;
    }

    $owner = $row['username'];

    // Cek hak akses
    if ($role !== 'admin' && $owner !== $me) {
        http_response_code(403);
        echo json_encode(['ok' => false, 'error' => 'Forbidden: hanya admin atau pemilik pesan']);
        exit;
    }

    // tandai sebagai terhapus untuk semua
    $sql = "UPDATE chat SET deleted_for_all = 1 WHERE id = ?";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param('i', $msgId);
    $stmt->execute();

    echo json_encode([
        'ok'   => true,
        'mode' => 'all',
        'info' => 'Pesan ini telah dihapus untuk semua'
    ]);
    exit;
}

/* ──────────────────────────────────────────────
   MODE TIDAK VALID
   ────────────────────────────────────────────── */
else {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'Bad mode']);
    exit;
}
?>
