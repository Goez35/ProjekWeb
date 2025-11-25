<?php
$session = $koneksi->query("SELECT current_question_index, status FROM quiz_sessions WHERE id=$session_id")->fetch_assoc();

if ($session['status'] === 'finished') {
    header("Location: finish.php?session_id=$session_id");
    exit;
}

$current_index = $session['current_question_index'] + 1;

if ($current_index != $_SESSION['last_question_index']) {
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
