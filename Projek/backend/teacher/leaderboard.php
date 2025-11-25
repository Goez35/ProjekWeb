<?php
require_once "../auth.php";
require_login();
require_once "../koneksi.php";

$user = current_user();

// pastikan teacher
if ($user['role'] !== 'teacher') {
    die("Akses ditolak. Hanya teacher yang dapat melihat leaderboard.");
}

$session_id = $_GET['session_id'] ?? 0;

if (!$session_id) {
    die("Session ID tidak ditemukan.");
}

// ambil daftar peserta dan skor mereka
$list = $koneksi->prepare("
    SELECT p.nickname, s.score, s.correct_count, s.wrong_count
    FROM submissions s
    JOIN participants p ON s.participant_id = p.id
    WHERE s.session_id = ?
    ORDER BY s.score DESC
");
$list->bind_param("i", $session_id);
$list->execute();
$leaderboard = $list->get_result();
?>
<?php include __DIR__ . '/../includes/header.php'; ?>


    <h2 class="text-center mb-4">Leaderboard</h2>

    <table class="table-glass text-center" style="width: 100%;">
        <thead class="table-dark">
            <tr>
                <th>Peringkat</th>
                <th>Nama</th>
                <th>Score</th>
                <th>Benar</th>
                <th>Salah</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $rank = 1;
            while ($row = $leaderboard->fetch_assoc()):
            ?>
                <tr>
                    <td><?= $rank++ ?></td>
                    <td><?= htmlspecialchars($row['nickname']) ?></td>
                    <td><?= $row['score'] ?></td>
                    <td><?= $row['correct_count'] ?></td>
                    <td><?= $row['wrong_count'] ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <a href="../dashboard.php" class="btn btn-primary w-100 mt-3">Kembali Dashboard</a>


<?php include __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>
