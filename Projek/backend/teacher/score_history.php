<?php
include "../auth.php";
include "../koneksi.php";
require_login();

$teacher_id = $_SESSION['user']['id'];

$sessions = $koneksi->query("
    SELECT 
        qs.id AS session_id,
        q.title,
        qs.created_at,
        qs.status
    FROM quiz_sessions qs
    JOIN quizzes q ON q.id = qs.quiz_id
    WHERE qs.host_id = $teacher_id AND qs.status = 'finished'
    ORDER BY qs.created_at DESC
");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Riwayat Kuis (Teacher)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container mt-4">

<h2>Riwayat Kuis</h2>

<?php if ($sessions->num_rows == 0): ?>
    <p class="text-muted mt-3">Belum ada riwayat kuis selesai.</p>
<?php endif; ?>

<?php while ($s = $sessions->fetch_assoc()): ?>

    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <h4><?= htmlspecialchars($s['title']) ?></h4>
            <p class="text-muted"><?= $s['created_at'] ?></p>

            <a href="view_session_result.php?session_id=<?= $s['session_id'] ?>" 
               class="btn btn-primary">
               Lihat Nilai Peserta
            </a>
        </div>
    </div>

<?php endwhile; ?>

</body>
</html>
