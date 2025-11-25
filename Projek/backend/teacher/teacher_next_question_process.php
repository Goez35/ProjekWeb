<?php
include "../auth.php";
include "../koneksi.php";
require_login();

$session_id = intval($_POST['session_id']);
$quiz_id = intval($_POST['quiz_id'] ?? 0);

$session = $koneksi->query("SELECT current_question_index FROM quiz_sessions WHERE id=$session_id")->fetch_assoc();
$current_index = $session['current_question_index'];

$q = $koneksi->query("SELECT COUNT(*) AS total FROM questions WHERE quiz_id=$quiz_id")->fetch_assoc();
$total_questions = $q['total'];

if (isset($_POST['next'])) {

    if ($current_index + 1 >= $total_questions) {
        // otomatis finish
        $koneksi->query("UPDATE quiz_sessions SET status='finished' WHERE id=$session_id");
        header("Location: finish_quiz.php?session_id=$session_id");
        exit;
    }

    $koneksi->query("UPDATE quiz_sessions SET current_question_index = current_question_index + 1 WHERE id=$session_id");
    header("Location: teacher_next_question.php?session_id=$session_id&quiz_id=$quiz_id");
    exit;
}

if (isset($_POST['finish'])) {
    $koneksi->query("UPDATE quiz_sessions SET status='finished' WHERE id=$session_id");
    header("Location: finish_quiz.php?session_id=$session_id");
    exit;
}
