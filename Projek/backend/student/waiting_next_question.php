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