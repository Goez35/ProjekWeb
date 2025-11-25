<?php
include "../auth.php";
include "../koneksi.php";
require_login();

$session_id = intval($_GET['session_id']);

$session = $koneksi->query("
    SELECT qs.*, q.title 
    FROM quiz_sessions qs
    JOIN quizzes q ON q.id = qs.quiz_id
    WHERE qs.id=$session_id
")->fetch_assoc();

$participants = $koneksi->query("
    SELECT 
        p.nickname,
        p.score,
        p.user_id,
        p.id AS participant_id
    FROM participants p
    WHERE p.session_id=$session_id
    ORDER BY p.score DESC
");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Hasil Kuis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="teacher_style.css">
</head>

<body class="container mt-4">

<h2>Hasil: <?= htmlspecialchars($session['title']) ?></h2>
<p class="text-muted"><?= $session['created_at'] ?></p>

<table class="table table-bordered table-striped mt-3">
    <tr>
        <th>Rank</th>
        <th>Nama</th>
        <th>Skor</th>
        <th>Detail Jawaban</th>
    </tr>

    <?php $rank = 1; while ($p = $participants->fetch_assoc()): ?>
    <tr>
        <td><?= $rank++ ?></td>
        <td><?= htmlspecialchars($p['nickname']) ?></td>
        <td><?= $p['score'] ?></td>
        <td>
            <a href="view_student_answer.php?participant_id=<?= $p['participant_id'] ?>" class="btn btn-sm btn-info">
                Lihat Jawaban
            </a>
        </td>
    </tr>
    <?php endwhile; ?>

</table>

</body>
</html>
