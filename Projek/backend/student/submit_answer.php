<?php
include "../koneksi.php";
session_start();

if (!isset($_SESSION['participant_id'])) {
    die("Belum join.");
}

$participant_id = $_SESSION['participant_id'];
$question_id = $_POST['question_id'];
$choice_id = $_POST['choice_id'];
$session_id = $_POST['session_id'];

// Ambil apakah jawaban benar
$get = $koneksi->query("SELECT is_correct FROM choices WHERE id=$choice_id");
$row = $get->fetch_assoc();
$is_correct = $row['is_correct'];

// Simpan jawaban
$koneksi->query("
    INSERT INTO participant_answers(session_id, participant_id, question_id, choice_id, is_correct)
    VALUES($session_id, $participant_id, $question_id, $choice_id, $is_correct)
");

header("Location: waiting_next_question.php");
exit;
