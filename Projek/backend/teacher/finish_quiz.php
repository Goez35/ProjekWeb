<?php
require_once "../auth.php";
require_login();
require_once "../koneksi.php";

$user = current_user();

if (!isset($_GET['session_id'])) {
    die("Session ID tidak ditemukan.");
}

$session_id = intval($_GET['session_id']);

// Ambil data quiz (opsional kalau mau tampil nama quiz)
$session = $koneksi->query("SELECT quiz_id FROM quiz_sessions WHERE id=$session_id")->fetch_assoc();
$quiz_id = $session['quiz_id'];

$quiz = $koneksi->query("SELECT title FROM quizzes WHERE id=$quiz_id")->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Quiz Selesai</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #8360c3, #2ebf91);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: Poppins, sans-serif;
        }
        .card {
            border-radius: 18px;
            padding: 40px;
            text-align: center;
            color: #333;
            animation: fadeIn 0.6s ease-out;
        }
        h1 {
            font-weight: bold;
            font-size: 2.2rem;
        }
        p {
            font-size: 1.15rem;
            margin-top: 15px;
        }
        .btn-lg {
            margin-top: 25px;
            font-size: 1.1rem;
            padding: 14px 24px;
            border-radius: 14px;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>

<body>

<div class="card shadow-lg bg-white" style="max-width:600px; width:100%;">
    <h1>🎉 Quiz Completed!</h1>
    <p>
        Selamat! Semua soal untuk quiz <strong><?= htmlspecialchars($quiz['title']) ?></strong> telah selesai.
        Sekarang saatnya melihat siapa yang paling jago.
    </p>

    <a href="leaderboard.php?session_id=<?= $session_id ?>" class="btn btn-success btn-lg">
        Lihat Leaderboard 🏆
    </a>
</div>

</body>
</html>
