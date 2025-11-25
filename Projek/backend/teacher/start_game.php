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
<?php include __DIR__ . '/../includes/header.php'; ?>
<body>

<div class="title-box">
    <h1 class="mb-4">Game Dimulai!</h1>
    <p class="text-muted mb-4">Siswa sedang diarahkan ke halaman soal pertama...</p>

    <a href="teacher_next_question.php?session_id=<?= $session_id ?>&quiz_id=<?= $quiz_id ?>" 
       class="btn btn-success btn-lg">
       Mulai Tampilkan Soal Pertama
    </a>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>
