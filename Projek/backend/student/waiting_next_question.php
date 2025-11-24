<?php
include "../koneksi.php";
session_start();

$participant_id = $_SESSION['participant_id'];
$get = $koneksi->query("SELECT * FROM participants WHERE id=$participant_id");
$participant = $get->fetch_assoc();

$session_id = $participant['session_id'];
$session = $koneksi->query("SELECT * FROM quiz_sessions WHERE id=$session_id")->fetch_assoc();

if ($session['status'] === 'finished') {
    header("Location: student_finished.php");
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
