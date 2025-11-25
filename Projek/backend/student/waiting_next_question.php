<?php
session_start();
require_once "../auth.php";
require_login();
require_once "../koneksi.php";

$user = current_user();

if (!isset($_SESSION['session_id'])) {
    header("Location: join_room.php");
    exit;
}

$session_id = $_SESSION['session_id'];

$session = $koneksi->query("
    SELECT current_question_index, status 
    FROM quiz_sessions 
    WHERE id=$session_id
")->fetch_assoc();

// PRIORITAS 1: kalau teacher sudah finish
if ($session['status'] === 'finished') {
    header("Location: finish.php?session_id=$session_id");
    exit;
}

// PRIORITAS 2: cek soal berikut
$current_index = $session['current_question_index'] + 1;

if (!isset($_SESSION['last_question_index']) || $current_index != $_SESSION['last_question_index']) {
    $_SESSION['last_question_index'] = $current_index;
    header("Location: quiz_play.php");
    exit;
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="refresh" content="3">
    <title>Menunggu Soal Berikutnya</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: url('../backgroundquiz.png') center/cover no-repeat fixed;
            background-color: #2a0055;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: Poppins, sans-serif;
            color: #fff;
        }

        .waiting-card {
            width: 600px;
            background: rgba(60, 0, 110, 0.78);
            border-radius: 24px;
            padding: 45px;
            backdrop-filter: blur(10px);
            box-shadow: 0 10px 35px rgba(0,0,0,0.45);
            text-align: center;
        }

    </style>
</head>

<body>

<div class="waiting-card shadow">
    <h2 class="fw-bold mb-3">Tunggu ya… ⏳</h2>
    <p class="fs-5">Guru sedang menampilkan soal berikutnya</p>
</div>

</body>
</html>
