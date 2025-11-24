<?php
include "../auth.php";
include "../koneksi.php";
require_login();

$user = current_user();
$teacher_id = $user['id'];

$title = $_POST['title'];
$description = $_POST['description'];
$visibility = $_POST['visibility'];

$cover_name = NULL;

// Jika ada cover diupload
if (!empty($_FILES['cover']['name'])) {
    $ext = pathinfo($_FILES['cover']['name'], PATHINFO_EXTENSION);
    $cover_name = "cover_" . time() . "." . $ext;

    // buat folder uploads jika belum ada
    if (!is_dir("../uploads")) {
        mkdir("../uploads");
    }

    move_uploaded_file($_FILES['cover']['tmp_name'], "../uploads/" . $cover_name);
}

// Insert ke database
$stmt = $koneksi->prepare("
    INSERT INTO quizzes (title, description, cover_image, visibility, created_by)
    VALUES (?, ?, ?, ?, ?)
");
$stmt->bind_param("ssssi", $title, $description, $cover_name, $visibility, $teacher_id);
$stmt->execute();

$quiz_id = $stmt->insert_id;

$stmt->close();

// Redirect untuk tambah pertanyaan
header("Location: add_question.php?quiz_id=" . $quiz_id);
exit;
