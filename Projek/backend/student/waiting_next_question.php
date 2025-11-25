<?php
session_start();
require_once "../koneksi.php";
require_once "../auth.php";
require_login();

$user = current_user();

if (!isset($_SESSION['session_id'])) {
    header("Location: join_room.php");
    exit;
}

$session_id = $_SESSION['session_id'];

// Ambil participant yang sesuai user login
$part = $koneksi->prepare("SELECT id FROM participants WHERE user_id = ? AND session_id = ?");
$part->bind_param("ii", $user['id'], $session_id);
$part->execute();
$participant = $part->get_result()->fetch_assoc();

if (!$participant) {
    die("Participant tidak ditemukan.");
}

$participant_id = $participant['id'];

// cek status session
$session = $koneksi->query("SELECT * FROM quiz_sessions WHERE id=$session_id")->fetch_assoc();

if ($session['status'] === 'finished') {
    header("Location: finish.php?session_id=$session_id");
    exit;
}

// cek apakah teacher sudah NEXT
// jika current index bertambah, balik ke quiz_play
$next_index = $session['current_question_index'] + 1;
header("Location: quiz_play.php");
exit;
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="refresh" content="3">
    <title>Menunggu Soal Berikutnya</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body { background:#ece6ff; font-family:Poppins; }
        .box {
            margin-top:100px; background:white;
            padding:40px; border-radius:20px; text-align:center;
        }
    </style>
</head>

<body class="container">
    <div class="box shadow">
        <h2>Tunggu ya...</h2>
        <p>Guru sedang menampilkan soal berikutnya</p>
    </div>
</body>
</html>
