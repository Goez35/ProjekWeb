<?php
require_once "../auth.php";
require_login();
require_once "../koneksi.php";

$user = current_user();

if (!isset($_SESSION['session_id'])) {
    header("Location: join_room.php");
    exit;
}

$session_id = $_SESSION['session_id'];

$session_sql = $koneksi->prepare("SELECT * FROM quiz_sessions WHERE id = ?");
$session_sql->bind_param("i", $session_id);
$session_sql->execute();
$session = $session_sql->get_result()->fetch_assoc();
if (!$session) die("Sesi tidak ditemukan.");

$current_index = $session['current_question_index'] + 1;

$q = $koneksi->prepare("SELECT * FROM questions WHERE quiz_id = ? ORDER BY id ASC");
$q->bind_param("i", $session['quiz_id']);
$q->execute();
$questions = $q->get_result();
$total_questions = $questions->num_rows;
if ($total_questions < 1) die("Belum ada soal.");

$questions->data_seek($current_index - 1);
$current_question = $questions->fetch_assoc();

$choices = $koneksi->prepare("SELECT * FROM choices WHERE question_id = ?");
$choices->bind_param("i", $current_question['id']);
$choices->execute();
$choices = $choices->get_result();

$part = $koneksi->prepare("SELECT id FROM participants WHERE user_id = ? AND session_id = ?");
$part->bind_param("ii", $user['id'], $session_id);
$part->execute();
$participant = $part->get_result()->fetch_assoc();
if (!$participant) die("Participant tidak ditemukan.");

$participant_id = $participant['id'];

$sub = $koneksi->prepare("SELECT id FROM submissions WHERE participant_id = ? AND session_id = ?");
$sub->bind_param("ii", $participant_id, $session_id);
$sub->execute();
$submission = $sub->get_result()->fetch_assoc();
if (!$submission) die("Submission tidak ditemukan.");

$submission_id = $submission['id'];

$check_answer = $koneksi->prepare("SELECT choice_id FROM submission_answers WHERE submission_id = ? AND question_id = ?");
$check_answer->bind_param("ii", $submission_id, $current_question['id']);
$check_answer->execute();
$answer_row = $check_answer->get_result()->fetch_assoc();

$already_answered = $answer_row ? true : false;
$selected_choice = $answer_row['choice_id'] ?? null;

if ($already_answered) {
    header("Location: waiting_next_question.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $choice_id = $_POST['answer'];

    $check = $koneksi->prepare("SELECT is_correct FROM choices WHERE id = ?");
    $check->bind_param("i", $choice_id);
    $check->execute();
    $result = $check->get_result()->fetch_assoc();
    $correct = ($result && $result['is_correct'] == 1) ? 1 : 0;

    $points = $correct ? $current_question['points'] : 0;

    $save = $koneksi->prepare("INSERT INTO submission_answers
        (submission_id, question_id, choice_id, typed_answer, is_correct, points_awarded, answered_at)
        VALUES (?, ?, ?, NULL, ?, ?, NOW())");
    $save->bind_param("iiiii", $submission_id, $current_question['id'], $choice_id, $correct, $points);
    $save->execute();

    header("Location: waiting_next_question.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Kuis Sedang Berjalan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: url('../backgroundquiz.png') center/cover no-repeat fixed;
            background-color: #2a0055; /* fallback kalau gambar ga load */
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: Poppins, sans-serif;
        }

        .quiz-card {
            width: 800px;
            background: rgba(60, 0, 110, 0.75); /* dark purple glass */
            border-radius: 24px;
            padding: 45px;
            backdrop-filter: blur(10px);
            box-shadow: 0 10px 35px rgba(0,0,0,0.45);
            color: #fff;
            animation: fadeIn .5s ease-out;
        }

        @keyframes fadeIn {
            from { opacity:0; transform: translateY(15px); }
            to { opacity:1; transform: translateY(0); }
        }

        .list-group-item {
            background: rgba(255,255,255,0.08);
            color: #fff;
            border: 1px solid rgba(255,255,255,0.12);
            border-radius: 12px !important;
            margin-bottom: 12px;
            padding: 14px;
            cursor: pointer;
            transition: background .25s, transform .2s;
        }

        .list-group-item:hover {
            background: rgba(255,255,255,0.18);
            transform: scale(1.02);
        }

        .list-group-item input {
            margin-right: 10px;
        }

        button {
            padding: 15px;
            border-radius: 18px;
            font-size: 1.2rem;
            font-weight: 700;
            background-color: #3e1737;
            color: #dac9d7;
            border: none;
            transition: .25s;
        }

        button:hover {
            background-color: #7a1e69;
            transform: scale(1.03);
        }
    </style>

</head>

<body>

<div class="quiz-card">
    <h3 class="mb-3 fw-bold">Soal <?= $current_index ?> / <?= $total_questions ?></h3>
    <p class="mb-3 fs-5"><?= htmlspecialchars($current_question['text']) ?></p>

    <form method="POST">
        <div class="list-group mb-3">
            <?php while ($ch = $choices->fetch_assoc()): ?>
                <label class="list-group-item">
                    <input type="radio" name="answer" value="<?= $ch['id'] ?>" <?= ($selected_choice == $ch['id']) ? 'checked' : '' ?>>
                    <?= htmlspecialchars($ch['text']) ?>
                </label>
            <?php endwhile; ?>
        </div>

        <button class="button w-100">Jawab 🚀</button>
    </form>
</div>

</body>
</html>
