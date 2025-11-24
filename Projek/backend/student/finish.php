<?php
require_once "../auth.php";
require_login();
require_once "../koneksi.php";

$user = current_user();

// pastikan session aktif
if (!isset($_SESSION['session_id'])) {
    header("Location: join_room.php");
    exit;
}

$session_id = $_SESSION['session_id'];

// Ambil participant_id
$part = $koneksi->prepare("SELECT id FROM participants WHERE user_id = ? AND session_id = ?");
$part->bind_param("ii", $user['id'], $session_id);
$part->execute();
$participant = $part->get_result()->fetch_assoc();

if (!$participant) die("Participant tidak ditemukan.");

$participant_id = $participant['id'];

// Ambil submission data
$sub = $koneksi->prepare("SELECT id FROM submissions WHERE participant_id = ? AND session_id = ?");
$sub->bind_param("ii", $participant_id, $session_id);
$sub->execute();
$submission = $sub->get_result()->fetch_assoc();

if (!$submission) die("Submission tidak ditemukan.");

$submission_id = $submission['id'];

// Hitung skor & jawaban benar / salah
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

// Update submissions final data
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
    <title>Hasil Kuis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light d-flex justify-content-center align-items-center" style="min-height:100vh">

<div class="card shadow p-4 text-center" style="max-width:500px; width:100%;">
    <h2 class="mb-3">Kuis Selesai 🎉</h2>

    <h4 class="mb-2">Score Akhir:</h4>
    <h1 class="display-4 fw-bold"><?= $score ?></h1>

    <hr>

    <p class="mb-1">Jawaban Benar: <strong><?= $correct ?></strong></p>
    <p class="mb-3">Jawaban Salah: <strong><?= $wrong ?></strong></p>

    <a href="../dashboard.php" class="btn btn-primary mt-3 w-100">Kembali ke Dashboard</a>
</div>

</body>
</html>
