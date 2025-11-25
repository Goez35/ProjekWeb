<?php
include "../auth.php";
include "../koneksi.php";
require_login();

$user = current_user();

if ($user['role'] !== 'teacher') {
    die("Hanya teacher yang boleh mengakses halaman ini.");
}

$teacher_id = $user['id'];

// ambil semua kuis milik teacher
$sql = "
    SELECT q.*, 
    (SELECT COUNT(*) FROM questions WHERE quiz_id = q.id) AS total_questions
    FROM quizzes q
    WHERE created_by = $teacher_id
    ORDER BY created_at DESC
";

$result = $koneksi->query($sql);
?>
<?php include __DIR__ . '/../includes/header.php'; ?>
<body>

<div class="header">Kuis Saya</div>

<div class="container mt-4">

    <div class="mb-3">
        <a href="create_quiz.php" class="btn btn-primary">+ Buat Kuis Baru</a>
    </div>

    <div class="row g-4">

        <?php if ($result->num_rows === 0): ?>

            <div class="col-12 text-center mt-5">
                <h5 class="text-muted">Belum ada kuis dibuat.</h5>
            </div>

        <?php else: ?>

            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="col-md-4">
                    <div class="card quiz-card shadow-sm">

                        <?php if ($row['cover_image']): ?>
                            <img src="../uploads/<?= $row['cover_image'] ?>" class="quiz-image">
                        <?php else: ?>
                            <img src="../default_cover.jpg" class="quiz-image">
                        <?php endif; ?>

                        <div class="p-3">
                            <h5><?= htmlspecialchars($row['title']) ?></h5>
                            <p class="text-muted small mb-1">
                                <?= $row['total_questions'] ?> Pertanyaan
                            </p>

                            <div class="d-flex gap-2 mt-2">
                                <a href="add_question.php?quiz_id=<?= $row['id'] ?>" class="btn btn-sm btn-secondary">Tambah Soal</a>
                                <a href="edit_quiz.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                <a href="start_session.php?quiz_id=<?= $row['id'] ?>" class="btn btn-sm btn-success">Mulai Game</a>
                            </div>
                        </div>

                    </div>
                </div>
            <?php endwhile; ?>

        <?php endif; ?>

    </div>

</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>
