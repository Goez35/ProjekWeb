<?php
require_once "../auth.php";
require_login();
require_once "../koneksi.php";

$session_id = $_GET['session_id'] ?? null;

if (!$session_id) {
    die("Session ID tidak ditemukan.");
}

// ambil info sesi
$stmt = $koneksi->prepare("
    SELECT qs.*, q.title AS quiz_title 
    FROM quiz_sessions qs
    JOIN quizzes q ON qs.quiz_id = q.id
    WHERE qs.id = ?
");
$stmt->bind_param("i", $session_id);
$stmt->execute();
$session = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$session) {
    die("Sesi tidak ditemukan.");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Waiting Room</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: url('../backgroundquiz.png') center/cover no-repeat fixed;
            min-height: 100vh;
            color: white;
        }

        .glass-card {
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(12px);
            border-radius: 25px;
            padding: 45px 35px;
            text-align: center;
            max-width: 500px;
        }

        .glass-card h2 {
            font-weight: 700;
        }

        .sub-text {
            color: #e5e5e5;
            font-size: 1rem;
            margin-top: -5px;
        }

        .loader {
            margin-top: 25px;
            width: 55px;
            height: 55px;
            border: 6px solid rgba(255,255,255,0.25);
            border-top-color: #ffca28;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>

<body class="d-flex align-items-center justify-content-center">

<div class="glass-card shadow-lg">

    <h2>Menunggu Guru Memulai Kuis...</h2>
    <p class="sub-text">Kuis: <b><?= htmlspecialchars($session['quiz_title']) ?></b></p>
    <p class="sub-text mb-2">Kode Room: <b><?= htmlspecialchars($session['join_code']) ?></b></p>

    <div class="loader" style="margin: 0 auto;"></div>

    <p class="sub-text mt-4">Tetap di halaman ini ya 🌟</p>
</div>

<script>
// auto refresh status tiap 2 detik
setInterval(() => {
    fetch("check_session_status.php?session_id=<?= $session_id ?>")
        .then(r => r.json())
        .then(res => {
            if (res.status === "in_progress") {
                window.location.href = "play_question.php?session_id=<?= $session_id ?>";
            }
        });
}, 2000);
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
