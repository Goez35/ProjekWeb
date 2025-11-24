<?php
require_once "../auth.php";
require_login();
require_once "../koneksi.php";

$user = current_user();

// pastikan session_id ada
if (!isset($_SESSION['session_id'])) {
    header("Location: join_room.php");
    exit;
}

$session_id = $_SESSION['session_id'];

// ambil data session
$stmt = $koneksi->prepare("
    SELECT s.*, u.fullname AS teacher_name, q.title
    FROM quiz_sessions s
    JOIN users u ON s.host_id = u.id
    JOIN quizzes q ON s.quiz_id = q.id
    WHERE s.id = ?
");
$stmt->bind_param("i", $session_id);
$stmt->execute();
$session = $stmt->get_result()->fetch_assoc();


if (!$session) {
    echo "Sesi tidak ditemukan.";
    exit;
}

// kalau teacher sudah start
if ($session['status'] === 'in_progress') {
    header("Location: quiz_play.php?q=1");
    exit;
}

// ambil peserta
$participants = $koneksi->prepare("SELECT p.nickname 
                                   FROM participants p 
                                   WHERE p.session_id = ?");
$participants->bind_param("i", $session_id);
$participants->execute();
$participants = $participants->get_result();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Waiting Room</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <meta http-equiv="refresh" content="4"> <!-- auto refresh tiap 4 detik -->
</head>

<body class="bg-light d-flex align-items-center justify-content-center" style="min-height:100vh">

<div class="container" style="max-width:450px;">
    <div class="card shadow p-4">
        <h3 class="text-center mb-1">Menunggu Peserta Lain...</h3>
        <p class="text-center text-muted mb-3">
            Teacher: <strong><?= htmlspecialchars($session['teacher_name']) ?></strong><br>
            Room Code: <strong><?= htmlspecialchars($session['join_code']) ?></strong>
        </p>

        <h5 class="mb-2">Peserta bergabung:</h5>
        <ul class="list-group mb-3">
            <?php while ($row = $participants->fetch_assoc()): ?>
                <li class="list-group-item"><?= htmlspecialchars($row['nickname']) ?></li>
            <?php endwhile; ?>
        </ul>

        <div class="text-center">
            <p class="small text-secondary">Menunggu teacher memulai game...</p>
        </div>
    </div>
</div>

</body>
</html>
