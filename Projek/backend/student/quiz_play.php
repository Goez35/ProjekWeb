<?php
require_once "../auth.php";
require_login();
require_once "../koneksi.php";

$user = current_user();

// pastikan session aktif
if (!isset($_SESSION['session_id'])) {
    header("Location: ../student/join_room.php");
    exit;
}

$session_id = $_SESSION['session_id'];

// ambil info session
$session = $koneksi->prepare("SELECT * FROM quiz_sessions WHERE id = ?");
$session->bind_param("i", $session_id);
$session->execute();
$session = $session->get_result()->fetch_assoc();

if (!$session) {
    die("Sesi tidak ditemukan.");
}

// ambil soal berdasarkan index
$current_index = intval($_GET['q'] ?? 1);

// Ambil pertanyaan (urut berdasarkan ID)
$q = $koneksi->prepare("SELECT * FROM questions WHERE quiz_id = ? ORDER BY id ASC");
$q->bind_param("i", $session['quiz_id']);
$q->execute();
$questions = $q->get_result();

$total_questions = $questions->num_rows;

if ($total_questions < 1) {
    die("Belum ada soal pada kuis ini.");
}

// ambil soal saat ini
$questions->data_seek($current_index - 1);
$current_question = $questions->fetch_assoc();

// proses submit jawaban
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $answer = $_POST['answer'] ?? "";
    $correct = ($answer == $current_question['correct_answer']) ? 1 : 0;

    // simpan jawaban
    $save = $koneksi->prepare("INSERT INTO user_answers (user_id, session_id, question_id, answer, is_correct)
                               VALUES (?,?,?,?,?)");
    $save->bind_param("iiisi", $user['id'], $session_id, $current_question['id'], $answer, $correct);
    $save->execute();

    // pindah ke soal berikutnya
    if ($current_index >= $total_questions) {
        header("Location: finish.php?session_id=" . $session_id);
        exit;
    } else {
        header("Location: quiz_play.php?q=" . ($current_index + 1));
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kuis Sedang Berjalan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light d-flex align-items-center justify-content-center" style="min-height:100vh">

<div class="card p-4 shadow" style="max-width:600px; width:100%;">
    <h4 class="mb-3">Soal <?= $current_index ?> / <?= $total_questions ?></h4>
    <p class="mb-3"><?= htmlspecialchars($current_question['question_text']) ?></p>

    <form method="POST">
        <div class="list-group mb-3">
            <?php
            $options = [
                'A' => $current_question['option_a'],
                'B' => $current_question['option_b'],
                'C' => $current_question['option_c'],
                'D' => $current_question['option_d'],
            ];
            foreach ($options as $key => $text):
            ?>
                <label class="list-group-item">
                    <input type="radio" name="answer" value="<?= $key ?>" required>
                    <strong><?= $key ?>.</strong> <?= htmlspecialchars($text) ?>
                </label>
            <?php endforeach; ?>
        </div>

        <button class="btn btn-primary w-100">Jawab</button>
    </form>
</div>

</body>
</html>
