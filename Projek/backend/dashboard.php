<?php
require_once "auth.php";
require_login();

$user = current_user();
$role = $user['role'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Quizizz</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f5f2ff;
            min-height: 100vh;
        }
        .header {
            background: #8A2BE2;
            padding: 18px;
            color: white;
            font-weight: 600;
            font-size: 20px;
        }
        .card-menu {
            border-radius: 16px;
            padding: 22px;
            transition: 0.2s;
            cursor: pointer;
        }
        .card-menu:hover {
            background: #eee1ff;
            transform: translateY(-3px);
        }
        a {
            text-decoration: none;
        }
    </style>
</head>

<body>

<div class="header">
    Selamat datang, <?= htmlspecialchars($user['fullname']) ?> (<?= $role ?>)
</div>

<div class="container mt-4">

    <?php if ($role == "teacher"): ?>
        <h3 class="mb-3">Menu Teacher</h3>

        <div class="row g-3">
            <div class="col-md-4">
                <a href="create_quiz.php">
                    <div class="card-menu bg-white shadow-sm">
                        <h5>Buat Kuis Baru</h5>
                        <p class="text-muted">Buat kuis pilihan ganda seperti Quizizz</p>
                    </div>
                </a>
            </div>

            <div class="col-md-4">
                <a href="my_quizzes.php">
                    <div class="card-menu bg-white shadow-sm">
                        <h5>Kuis Saya</h5>
                        <p class="text-muted">Lihat semua kuis yang kamu buat</p>
                    </div>
                </a>
            </div>

            <div class="col-md-4">
                <a href="sessions.php">
                    <div class="card-menu bg-white shadow-sm">
                        <h5>Mulai Game</h5>
                        <p class="text-muted">Bikin kode room untuk siswa join</p>
                    </div>
                </a>
            </div>
        </div>

    <?php else: ?>
        <h3 class="mb-3">Menu Student</h3>

        <div class="row g-3">
            <div class="col-md-6">
                <a href="join_room.php">
                    <div class="card-menu bg-white shadow-sm">
                        <h5>Join Kode Room</h5>
                        <p class="text-muted">Masukkan kode room dari guru</p>
                    </div>
                </a>
            </div>

            <div class="col-md-6">
                <a href="history.php">
                    <div class="card-menu bg-white shadow-sm">
                        <h5>Riwayat Nilai</h5>
                        <p class="text-muted">Lihat nilai kuis kamu</p>
                    </div>
                </a>
            </div>
        </div>
    <?php endif; ?>

    <div class="mt-4">
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>

</div>


</body>
</html>
