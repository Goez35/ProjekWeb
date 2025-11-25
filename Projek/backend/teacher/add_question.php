<?php
include "../auth.php";
include "../koneksi.php";
require_login();

$user = current_user();

if ($user['role'] !== 'teacher') {
    die("Hanya teacher yang boleh menambah pertanyaan.");
}

if (!isset($_GET['quiz_id'])) {
    die("Quiz ID tidak ditemukan.");
}

$quiz_id = intval($_GET['quiz_id']);
?>
<?php include __DIR__ . '/../includes/header.php'; ?>
<body>

<div class="header">
    Tambah Pertanyaan untuk Kuis #<?= $quiz_id ?>
</div>

<div class="container mt-4">


        <form action="save_question.php" method="POST">

            <input type="hidden" name="quiz_id" value="<?= $quiz_id ?>">

            <div class="mb-3">
                <label class="form-label">Isi Pertanyaan</label>
                <textarea name="question_text" class="form-control" required placeholder="Masukkan pertanyaan..." rows="2"></textarea>
            </div>

            <label class="form-label">Pilihan Jawaban</label>

            <?php for ($i = 1; $i <= 4; $i++): ?>
                <div class="input-group mb-2">
                    <div class="input-group-text">
                        <input type="radio" name="correct_choice" value="<?= $i ?>" required>
                    </div>
                    <input type="text" name="choice<?= $i ?>" class="form-control" placeholder="Pilihan <?= $i ?>" required>
                </div>
            <?php endfor; ?>

            <div class="mt-3 d-flex gap-2">
                <button type="submit" name="add_more" value="1" class="btn btn-primary w-50">
                    Simpan & Tambah Lagi
                </button>

                <button type="submit" name="finish" value="1" class="btn btn-success w-50">
                    Selesai Buat Kuis
                </button>
            </div>

        </form>


</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>
