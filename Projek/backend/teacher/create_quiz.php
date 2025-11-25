<?php
include "../auth.php";
include "../koneksi.php";
require_login();
$user = current_user();

if ($user['role'] !== 'teacher') {
    echo "Hanya teacher yang boleh mengakses halaman ini.";
    exit;
}
?>
<?php include __DIR__ . '/../includes/header.php'; ?>
<body>

<div class="header">Buat Kuis Baru</div>

<div class="container mt-4">

    

        <form action="save_quiz.php" method="POST" enctype="multipart/form-data">

            <div class="mb-3">
                <label class="form-label">Judul Kuis</label>
                <input type="text" name="title" class="form-control" required placeholder="Contoh: Matematika Bab 1">
            </div>

            <div class="mb-3">
                <label class="form-label">Deskripsi</label>
                <textarea name="description" class="form-control" rows="3" placeholder="Opsional"></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Cover Image (opsional)</label>
                <input type="file" name="cover" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Visibility</label>
                <select name="visibility" class="form-select">
                    <option value="private">Private</option>
                    <option value="public">Public</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary w-100">Simpan & Tambah Pertanyaan</button>

        </form>
    

</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>
