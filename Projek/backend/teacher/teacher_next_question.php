<?php
include "../auth.php";
include "../koneksi.php";
require_login();

$user = current_user();
if ($user['role'] !== 'teacher') {
    die("Hanya teacher.");
}

if (!isset($_GET['session_id']) || !isset($_GET['quiz_id'])) {
    die("Parameter tidak lengkap.");
}

$session_id = intval($_GET['session_id']);
$quiz_id = intval($_GET['quiz_id']);

// Ambil session
$session_sql = $koneksi->query("SELECT * FROM quiz_sessions WHERE id=$session_id");
$session = $session_sql->fetch_assoc();

$current_index = $session['current_question_index'];

// Ambil semua pertanyaan untuk kuis ini
$questions = $koneksi->query("
    SELECT * FROM questions WHERE quiz_id=$quiz_id ORDER BY id ASC
");

$total_questions = $questions->num_rows;

// Ambil pertanyaan yang sedang aktif
$questions_array = [];
while ($q = $questions->fetch_assoc()) {
    $questions_array[] = $q;
}

if ($current_index < $total_questions) {
    $current_question = $questions_array[$current_index];
} else {
    $current_question = null;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Kontrol Soal - Teacher</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body { background:#f3f0ff; font-family:Poppins; }
        .box { background:white; padding:25px; border-radius:15px; }
    </style>
</head>

<body class="p-4">

<h3>Kontrol Soal - Teacher</h3>

<?php if ($current_question): ?>

    <div class="box shadow mt-3">
        <h4>Soal <?= $current_index + 1 ?> dari <?= $total_questions ?></h4>
        <p class="mt-3"><?= nl2br(htmlspecialchars($current_question['text'])) ?></p>

        <h5 class="mt-4">Pilihan Jawaban:</h5>
        <ul>
            <?php
            $qid = $current_question['id'];
            $choices = $koneksi->query("SELECT * FROM choices WHERE question_id=$qid");
            while ($c = $choices->fetch_assoc()):
            ?>
            <li><?= htmlspecialchars($c['text']) ?></li>
            <?php endwhile; ?>
        </ul>

        <div class="mt-4 d-flex gap-2">

            <?php if ($current_index + 1 < $total_questions): ?>
                <form action="teacher_next_question_process.php" method="POST">
                    <input type="hidden" name="session_id" value="<?= $session_id ?>">
                    <input type="hidden" name="quiz_id" value="<?= $quiz_id ?>">
                    <button name="next" value="1" class="btn btn-primary">Next Question</button>
                </form>
            <?php endif; ?>

            <?php if ($current_index + 1 == $total_questions): ?>
                <form action="teacher_next_question_process.php" method="POST">
                    <input type="hidden" name="session_id" value="<?= $session_id ?>">
                    <button name="finish" value="1" class="btn btn-danger">Finish Quiz</button>
                </form>
            <?php endif; ?>

        </div>

    </div>

<?php else: ?>

    <div class="box shadow mt-3 text-center">
        <h4>Semua soal telah ditampilkan!</h4>
        <a href="finish_quiz.php?session_id=<?= $session_id ?>" class="btn btn-success mt-3">
            Lihat Hasil & Leaderboard
        </a>
    </div>

<?php endif; ?>

</body>
</html>
