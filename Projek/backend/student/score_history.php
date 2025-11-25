<?php
include "../koneksi.php";
session_start();

if (!isset($_SESSION['participant_id'])) {
    die("Belum join sesi.");
}

$user_id = $_SESSION['user']['id']; // student user id

// ambil semua riwayat peserta
$history = $koneksi->query("
    SELECT 
        qs.id AS session_id,
        q.title AS quiz_title,
        qs.created_at,
        COUNT(pa.id) AS total_answers,
        SUM(pa.is_correct) AS total_correct,
        SUM(qs2.points) AS total_points
    FROM quiz_sessions qs
    JOIN quizzes q ON qs.quiz_id = q.id
    JOIN participants p ON p.session_id = qs.id
    LEFT JOIN participant_answers pa ON pa.participant_id = p.id
    LEFT JOIN questions qs2 ON qs2.id = pa.question_id
    WHERE p.user_id = $user_id AND qs.status = 'finished'
    GROUP BY qs.id
    ORDER BY qs.created_at DESC
");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Riwayat Nilai</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body { background:#f6f0ff; font-family:Poppins; }
        .box { background:white; padding:20px; border-radius:15px; }
    </style>
</head>

<body class="container mt-4">

<h2>Riwayat Nilai</h2>

<div class="mt-3">

<?php if ($history->num_rows == 0): ?>
    <p class="text-muted">Belum ada riwayat nilai.</p>
<?php endif; ?>

<?php while ($row = $history->fetch_assoc()): ?>
    <div class="box shadow-sm mb-3">
        <h4><?= htmlspecialchars($row['quiz_title']) ?></h4>
        <p class="text-muted mb-1"><?= $row['created_at'] ?></p>

        <strong>Skor: <?= $row['total_points'] ?></strong><br>
        <span><?= $row['total_correct'] ?> benar dari <?= $row['total_answers'] ?> soal</span>
    </div>
<?php endwhile; ?>

</div>

</body>
</html>
