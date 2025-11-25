<?php
include "../auth.php";
include "../koneksi.php";
require_login();

$user = current_user();
if ($user['role'] !== 'teacher') {
    die("Hanya teacher yang boleh mengakses halaman ini.");
}

if (!isset($_GET['session_id'])) {
    die("Session ID hilang.");
}

$session_id = intval($_GET['session_id']);

// ambil data session
$sql = $koneksi->query("
    SELECT qs.*, q.title 
    FROM quiz_sessions qs
    JOIN quizzes q ON qs.quiz_id = q.id
    WHERE qs.id = $session_id
");

if ($sql->num_rows == 0) {
    die("Session tidak ditemukan.");
}

$session = $sql->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Lobby Game (Teacher)</title>

    <meta http-equiv="refresh" content="3"> 
    <!-- Auto refresh setiap 3 detik -->

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="teacher_style.css">

    <style>
        body {
            background: #f5f0ff;
            font-family: Poppins, sans-serif;
        }
        .header {
            background: #8A2BE2;
            padding: 15px;
            color: white;
            font-weight: bold;
            font-size: 22px;
        }
        .code-box {
            background: white;
            border-radius: 15px;
            padding: 15px;
            font-size: 40px;
            font-weight: bold;
            letter-spacing: 10px;
        }
        .student-card {
            background: white;
            border-radius: 10px;
            padding: 10px;
        }
    </style>
</head>

<body>

<div class="header">Lobby Game – <?= htmlspecialchars($session['title']) ?></div>

<div class="container mt-4">

    <div class="text-center mb-4">
        <h4>Kode Room</h4>
        <div class="code-box shadow"><?= $session['join_code'] ?></div>
    </div>

    <h5>Peserta yang sudah join:</h5>

    <div class="row g-2 mt-2">

    <?php
        $participants = $koneksi->query("
            SELECT * FROM participants WHERE session_id = $session_id
        ");

        if ($participants->num_rows == 0) {
            echo "<p class='text-muted'>Belum ada peserta yang join...</p>";
        }

        while ($p = $participants->fetch_assoc()):
    ?>
        <div class="col-md-3">
            <div class="student-card shadow-sm">
                <?= htmlspecialchars($p['nickname']) ?>
            </div>
        </div>
    <?php endwhile; ?>

    </div>

    <div class="mt-4 text-center">

        <a href="start_game.php?session_id=<?= $session_id ?>" 
           class="btn btn-success btn-lg w-50">
            Mulai Game
        </a>

    </div>

</div>

</body>
</html>
