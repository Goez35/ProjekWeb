<?php
include "../auth.php";
include "../koneksi.php";
require_login();

$user = current_user();

if ($user['role'] !== 'teacher') {
    die("Hanya teacher.");
}

$quiz_id = intval($_POST['quiz_id']);
$question_text = $_POST['question_text'];
$correct_choice = intval($_POST['correct_choice']);

// Insert pertanyaan
$stmt = $koneksi->prepare("INSERT INTO questions (quiz_id, text, question_type) VALUES (?, ?, 'single')");
$stmt->bind_param("is", $quiz_id, $question_text);
$stmt->execute();
$question_id = $stmt->insert_id;
$stmt->close();

// Insert pilihan jawaban
for ($i = 1; $i <= 4; $i++) {

    $choice_text = $_POST['choice'.$i];
    $is_correct = ($i == $correct_choice) ? 1 : 0;

    $stmt2 = $koneksi->prepare("INSERT INTO choices (question_id, text, is_correct) VALUES (?, ?, ?)");
    $stmt2->bind_param("isi", $question_id, $choice_text, $is_correct);
    $stmt2->execute();
    $stmt2->close();
}

// Cek apakah teacher klik "Tambah Lagi"
if (isset($_POST['add_more'])) {
    header("Location: add_question.php?quiz_id=" . $quiz_id);
    exit;
}

// Jika klik selesai
header("Location: ../dashboard.php?success=quiz_saved");
exit;
