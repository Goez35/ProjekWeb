<?php
require_once "../auth.php";
require_login();
require_once "../koneksi.php";

$user = current_user();

if (!isset($_GET['session_id'])) {
    die("Session ID tidak ditemukan.");
}

$session_id = intval($_GET['session_id']);

// Ambil data quiz (opsional kalau mau tampil nama quiz)
$session = $koneksi->query("SELECT quiz_id FROM quiz_sessions WHERE id=$session_id")->fetch_assoc();
$quiz_id = $session['quiz_id'];

$quiz = $koneksi->query("SELECT title FROM quizzes WHERE id=$quiz_id")->fetch_assoc();
?>
<?php include __DIR__ . '/../includes/header.php'; ?>
<body>

<div class="card shadow-lg bg-white" style="max-width:600px; width:100%;">
    <h1>🎉 Quiz Completed!</h1>
    <p>
        Selamat! Semua soal untuk quiz <strong><?= htmlspecialchars($quiz['title']) ?></strong> telah selesai.
        Sekarang saatnya melihat siapa yang paling jago.
    </p>

    <a href="leaderboard.php?session_id=<?= $session_id ?>" class="btn btn-success btn-lg">
        Lihat Leaderboard 🏆
    </a>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>
