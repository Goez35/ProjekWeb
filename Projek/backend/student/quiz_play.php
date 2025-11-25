<?php
require_once "../auth.php";
require_login();
require_once "../koneksi.php";

$user = current_user();

// pastikan session ilham
if (!isset($_SESSION['session_id'])) {
    header("Location: ./quiz_play.php?q=1");
    exit;
}

$session_id = $_SESSION['session_id'];

// ambil session info
$session_sql = $koneksi->prepare("SELECT * FROM quiz_sessions WHERE id = ?");
$session_sql->bind_param("i", $session_id);
$session_sql->execute();
$session = $session_sql->get_result()->fetch_assoc();

if (!$session) die("Sesi tidak ditemukan.");

// Index soal mengikuti teacher
$current_index = $session['current_question_index'] + 1;

$q = $koneksi->prepare("SELECT * FROM questions WHERE quiz_id = ? ORDER BY id ASC");
$q->bind_param("i", $session['quiz_id']);
$q->execute();
$questions = $q->get_result();

$total_questions = $questions->num_rows;
if ($total_questions < 1) die("Belum ada soal.");

// ambil soal saat ini
$questions->data_seek($current_index - 1);
$current_question = $questions->fetch_assoc();

// ambil pilihan jawaban dari tabel choices
$choices = $koneksi->prepare("SELECT * FROM choices WHERE question_id = ?");
$choices->bind_param("i", $current_question['id']);
$choices->execute();
$choices = $choices->get_result();

// submit jawaban
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $choice_id = $_POST['answer'] ?? 0;

    // cek apakah correct
    $check = $koneksi->prepare("SELECT is_correct FROM choices WHERE id = ?");
    $check->bind_param("i", $choice_id);
    $check->execute();
    $result = $check->get_result()->fetch_assoc();

    $correct = ($result && $result['is_correct'] == 1) ? 1 : 0;

    // Ambil participant_id berdasarkan user yang sedang login
    $part = $koneksi->prepare("SELECT id FROM participants WHERE user_id = ? AND session_id = ?");
    $part->bind_param("ii", $user['id'], $session_id);
    $part->execute();
    $participant = $part->get_result()->fetch_assoc();

    if (!$participant) {
        die("Participant tidak ditemukan.");
    }

    $participant_id = $participant['id'];

    // Ambil submission berdasarkan participant_id
    $sub = $koneksi->prepare("SELECT id FROM submissions WHERE participant_id = ? AND session_id = ?");
    $sub->bind_param("ii", $participant_id, $session_id);
    $sub->execute();
    $submission = $sub->get_result()->fetch_assoc();

    if (!$submission) {
        die("Submission tidak ditemukan. Pastikan dibuat ketika student join room.");
    }

    $submission_id = $submission['id'];

    // kalau teacher sudah ganti soal, refresh otomatis menangkap update
    if ($session['status'] === 'finished') {
        header("Location: finish.php?session_id=$session_id");
        exit;
    }

    // kalau user sudah menjawab current question, tampilkan halaman tunggu
    $check_answer = $koneksi->prepare("
        SELECT id FROM submission_answers 
        WHERE submission_id = ? AND question_id = ?
    ");
    $check_answer->bind_param("ii", $submission_id, $current_question['id']);
    $check_answer->execute();
    $already_answered = $check_answer->get_result()->num_rows > 0;

    if ($already_answered) {
        header("Location: waiting_next_question.php");
        exit;
    }


    $points = ($correct == 1) ? $current_question['points'] : 0;

    $save = $koneksi->prepare("INSERT INTO submission_answers 
        (submission_id, question_id, choice_id, typed_answer, is_correct, points_awarded, answered_at)
        VALUES (?, ?, ?, NULL, ?, ?, NOW())"
    );

    $save->bind_param("iiiii", $submission_id, $current_question['id'], $choice_id, $correct, $points);
    $save->execute();


    if ($current_index >= $total_questions) {
        header("Location: finish.php?session_id=" . $session_id);
        exit;
    }

    header("Location: waiting_next_question.php");
    exit;

    }
?>
<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <title>Kuis Sedang Berjalan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light d-flex align-items-center justify-content-center" style="min-height:100vh">

<div class="card p-4 shadow" style="max-width:600px; width:100%;">
    <h4 class="mb-3">Soal <?= $current_index ?> / <?= $total_questions ?></h4>
    <p class="mb-3"><?= htmlspecialchars($current_question['text']) ?></p>

    <form method="POST">
        <div class="list-group mb-3">
            <?php while ($ch = $choices->fetch_assoc()): ?>
                <label class="list-group-item">
                    <input type="radio" name="answer" value="<?= $ch['id'] ?>" required>
                    <?= htmlspecialchars($ch['text']) ?>
                </label>
            <?php endwhile; ?>
        </div>

        <button class="btn btn-primary w-100">Jawab</button>
    </form>
</div>

</body>
</html>
