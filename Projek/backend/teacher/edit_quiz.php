<?php
include "../auth.php";
include "../koneksi.php";
require_login();

$user = current_user();
if ($user['role'] !== 'teacher') {
    die("Akses ditolak.");
}

if (!isset($_GET['id'])) {
    die("Quiz ID tidak ditemukan.");
}

$quiz_id = intval($_GET['id']);
$sql = $koneksi->query("SELECT * FROM quizzes WHERE id = $quiz_id");
$quiz = $sql->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Kuis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="teacher_style.css">
</head>
<body class="p-4">

<h3>Edit Kuis: <?= htmlspecialchars($quiz['title']) ?></h3>

<form action="update_quiz.php" method="POST" enctype="multipart/form-data">

    <input type="hidden" name="id" value="<?= $quiz_id ?>">

    <label class="form-label mt-3">Judul</label>
    <input type="text" name="title" value="<?= htmlspecialchars($quiz['title']) ?>" class="form-control">

    <label class="form-label mt-3">Deskripsi</label>
    <textarea name="description" class="form-control"><?= htmlspecialchars($quiz['description']) ?></textarea>

    <label class="form-label mt-3">Cover Baru (opsional)</label>
    <input type="file" name="cover" class="form-control">

    <label class="form-label mt-3">Visibility</label>
    <select name="visibility" class="form-select">
        <option value="private" <?= $quiz['visibility'] == 'private'?'selected':'' ?>>Private</option>
        <option value="public" <?= $quiz['visibility'] == 'public'?'selected':'' ?>>Public</option>
    </select>

    <button class="btn btn-success mt-3">Simpan Perubahan</button>

</form>

</body>
</html>
