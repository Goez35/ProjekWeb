<?php
require_once "../auth.php";
require_login();
require_once "../koneksi.php";

$user = current_user();

if (!isset($_SESSION['session_id'])) {
    header("Location: join_room.php");
    exit;
}

$session_id = $_SESSION['session_id'];

$part = $koneksi->prepare("SELECT id FROM participants WHERE user_id = ? AND session_id = ?");
$part->bind_param("ii", $user['id'], $session_id);
$part->execute();
$participant = $part->get_result()->fetch_assoc();
if (!$participant) die("Participant tidak ditemukan.");

$participant_id = $participant['id'];

$sub = $koneksi->prepare("SELECT id FROM submissions WHERE participant_id = ? AND session_id = ?");
$sub->bind_param("ii", $participant_id, $session_id);
$sub->execute();
$submission = $sub->get_result()->fetch_assoc();
if (!$submission) die("Submission tidak ditemukan.");

$submission_id = $submission['id'];

$sum = $koneksi->prepare("
    SELECT 
        SUM(points_awarded) AS total_score,
        SUM(is_correct) AS correct_count,
        SUM(CASE WHEN is_correct = 0 THEN 1 ELSE 0 END) AS wrong_count
    FROM submission_answers
    WHERE submission_id = ?
");
$sum->bind_param("i", $submission_id);
$sum->execute();
$result = $sum->get_result()->fetch_assoc();

$score = $result['total_score'] ?? 0;
$correct = $result['correct_count'] ?? 0;
$wrong = $result['wrong_count'] ?? 0;

$update = $koneksi->prepare("
    UPDATE submissions 
    SET score = ?, correct_count = ?, wrong_count = ?, finished_at = NOW()
    WHERE id = ?
");
$update->bind_param("iiii", $score, $correct, $wrong, $submission_id);
$update->execute();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kuis Selesai</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: url('../backgroundquiz.png') center/cover no-repeat fixed;
            background-color: #2a0055;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: Poppins, sans-serif;
        }

        .finish-card {
            width: 650px;
            background: rgba(60, 0, 110, 0.78);
            border-radius: 26px;
            padding: 50px;
            backdrop-filter: blur(10px);
            box-shadow: 0 10px 35px rgba(0,0,0,0.45);
            color: #fff;
            text-align: center;
            animation: fadeIn .6s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(15px); }
            to   { opacity: 1; transform: translateY(0);  }
        }

        .score-number {
            font-size: 4.5rem;
            font-weight: 900;
            color: #FFD43B;
            text-shadow: 0 0 15px rgba(255,255,255,0.35);
        }

        button, .btn, a.btn {
            padding: 14px;
            border-radius: 18px;
            font-size: 1.2rem;
            font-weight: 700;
            background-color: #FFD43B;
            color: #2a0055;
            border: none;
            transition: .25s;
        }

        button:hover, .btn:hover {
            background-color: #ffea7b;
            transform: scale(1.03);
        }

        hr {
            border-color: rgba(255,255,255,0.2);
            margin: 20px 0;
        }
    </style>

</head>

<body>

<div class="finish-card">
    <h2 class="fw-bold mb-2">🎉 Kuis Selesai! 🎉</h2>
    <p class="mb-4">Kerja bagus! Kamu sudah menyelesaikan semua soal 🔥</p>

    <h4 class="mb-1">Score Akhir</h4>
    <div class="score-number mb-3"><?= $score ?></div>

    <hr>

    <p>Jawaban Benar: <strong><?= $correct ?></strong></p>
    <p>Jawaban Salah: <strong><?= $wrong ?></strong></p>

    <a href="../dashboard.php" class="btn w-100 mt-4">Kembali ke Dashboard</a>
</div>

</body>
</html>
