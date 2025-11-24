<?php
include "../koneksi.php";
session_start();

if (!isset($_SESSION['participant_id'])) {
    die("Belum join.");
}

$participant_id = $_SESSION['participant_id'];

// ambil data peserta
$sql = $koneksi->query("SELECT * FROM participants WHERE id=$participant_id");
$participant = $sql->fetch_assoc();

$session_id = $participant['session_id'];

// ambil data session
$session = $koneksi->query("SELECT * FROM quiz_sessions WHERE id=$session_id")->fetch_assoc();

// ambil quiz_id
$quiz_id = $session['quiz_id'];

// ambil semua jawaban peserta
$answers = $koneksi->query("
    SELECT pa.*, q.points, c.is_correct
    FROM participant_answers pa
    JOIN questions q ON pa.question_id = q.id
    LEFT JOIN choices c ON pa.choice_id = c.id
    WHERE pa.session_id=$session_id AND pa.participant_id=$participant_id
");

// hitung skor
$total_score = 0;
$total_questions = 0;
$correct_count = 0;

while ($row = $answers->fetch_assoc()) {
    $total_questions++;
    if ($row['is_correct'] == 1) {
        $total_score += $row['points'];
        $correct_count++;
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Kuis Selesai</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #efe6ff;
            font-family: Poppins, sans-serif;
        }
        .finish-box {
            margin-top: 80px;
            background: #fff;
            padding: 40px;
            border-radius: 20px;
            text-align: center;
        }
        .score {
            font-size: 48px;
            font-weight: bold;
            color: #6A0DAD;
        }
    </style>
</head>

<body class="container">

<div class="finish-box shadow">

    <h1 class="mb-3">🎉 Kuis Selesai!</h1>
    <p class="text-muted mb-4">Terima kasih sudah berpartisipasi!</p>

    <div class="score mb-2">
        <?= $total_score ?>
    </div>
    <p class="mb-4">
        Skor Kamu
    </p>

    <h5><?= $correct_count ?> benar dari <?= $total_questions ?> soal</h5>

    <hr class="my-4">

    <a href="../join_room.php" class="btn btn-primary btn-lg">
        Kembali ke Menu Join
    </a>

</div>

</body>
</html>
