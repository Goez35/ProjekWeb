<?php
include "../koneksi.php";
session_start();

if (!isset($_SESSION['participant_id'])) {
    die("Belum join sesi.");
}

$participant_id = $_SESSION['participant_id'];

// Ambil session ID peserta
$get_part = $koneksi->query("SELECT * FROM participants WHERE id=$participant_id");
$participant = $get_part->fetch_assoc();

$session_id = $participant['session_id'];

// Ambil session info
$get_session = $koneksi->query("SELECT * FROM quiz_sessions WHERE id=$session_id");
$session = $get_session->fetch_assoc();

// Kalau teacher belum mulai
if ($session['status'] !== 'in_progress') {
    header("Location: waiting_room.php");
    exit;
}

$quiz_id = $session['quiz_id'];
$current_index = $session['current_question_index'];

// Ambil semua soal
$q_sql = $koneksi->query("SELECT * FROM questions WHERE quiz_id=$quiz_id ORDER BY id ASC");
$questions = [];
while ($q = $q_sql->fetch_assoc()) {
    $questions[] = $q;
}

$total_questions = count($questions);

// Cek apakah soal habis → ke halaman selesai
if ($current_index >= $total_questions) {
    header("Location: student_finished.php");
    exit;
}

$current_question = $questions[$current_index];

// Ambil pilihan jawaban
$qid = $current_question['id'];
$choices = $koneksi->query("SELECT * FROM choices WHERE question_id=$qid");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Soal Sedang Berlangsung</title>
    <meta http-equiv="refresh" content="3">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body { background:#f3f0ff; font-family:Poppins; }
        .question-box {
            background:white; padding:30px;
            border-radius:20px; margin-top:30px;
        }
        .choice-btn {
            width:100%; text-align:left;
            border-radius:12px; padding:15px;
            font-size:18px; margin-bottom:10px;
        }
    </style>
</head>

<body class="container">

    <div class="question-box shadow">

        <h4>Soal <?= $current_index + 1 ?> / <?= $total_questions ?></h4>

        <h3 class="mt-3"><?= nl2br(htmlspecialchars($current_question['text'])) ?></h3>

        <hr>

        <form action="submit_answer.php" method="POST">
            <input type="hidden" name="question_id" value="<?= $current_question['id'] ?>">
            <input type="hidden" name="session_id" value="<?= $session_id ?>">

            <?php while ($c = $choices->fetch_assoc()): ?>

                <button class="btn btn-outline-primary choice-btn"
                        name="choice_id"
                        value="<?= $c['id'] ?>">
                    <?= htmlspecialchars($c['text']) ?>
                </button>

            <?php endwhile; ?>
        </form>

    </div>

</body>
</html>
