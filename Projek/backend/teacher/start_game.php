<?php
include "../auth.php";
include "../koneksi.php";
require_login();

$user = current_user();
if ($user['role'] !== 'teacher') {
    die("Hanya teacher.");
}

if (!isset($_GET['session_id'])) {
    die("Session ID hilang.");
}

$session_id = intval($_GET['session_id']);

// ubah status menjadi in_progress
$koneksi->query("
    UPDATE quiz_sessions SET status='in_progress'
    WHERE id=$session_id
");

// ambil quiz id
$get = $koneksi->query("SELECT quiz_id FROM quiz_sessions WHERE id=$session_id");
$session = $get->fetch_assoc();
$quiz_id = $session['quiz_id'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Game Dimulai!</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="teacher_style.css">

    <style>
        body {
            background: #f5f0ff;
            font-family: Poppins, sans-serif;
        }
        .title-box {
            margin-top: 80px;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="title-box">
    <h1 class="mb-4">Game Dimulai!</h1>
    <p class="text-muted mb-4">Siswa sedang diarahkan ke halaman soal pertama...</p>

    <a href="teacher_next_question.php?session_id=<?= $session_id ?>&quiz_id=<?= $quiz_id ?>" 
       class="btn btn-success btn-lg">
       Mulai Tampilkan Soal Pertama
    </a>
</div>

</body>
</html>
