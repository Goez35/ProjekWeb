<?php
include "../auth.php";
include "../koneksi.php";
require_login();

$session_id = intval($_POST['session_id']);
$quiz_id = $_POST['quiz_id'] ?? null;

// Jika tombol NEXT ditekan
if (isset($_POST['next'])) {

    $koneksi->query("
        UPDATE quiz_sessions 
        SET current_question_index = current_question_index + 1
        WHERE id=$session_id
    ");

    header("Location: teacher_next_question.php?session_id=$session_id&quiz_id=$quiz_id");
    exit;
}

// Jika tombol FINISH quiz
if (isset($_POST['finish'])) {

    $koneksi->query("
        UPDATE quiz_sessions 
        SET status='finished'
        WHERE id=$session_id
    ");

    header("Location: finish_quiz.php?session_id=$session_id");
    exit;
}

?>
