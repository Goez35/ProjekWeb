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
    <title>Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: url('backgroundquiz.png') center/cover no-repeat fixed;
            min-height: 100vh;
            color: white;
        }

        .navbar-custom {
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255,255,255,0.25);
        }

        .navbar-brand {
            font-weight: 600;
            font-size: 1.1rem;
            color: #fff !important;
        }

        .nav-link {
            color: #fff !important;
            font-weight: 500;
        }

        .main-container {
            margin-top: 75px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: calc(100vh - 75px);  /* biar full height di bawah navbar */
            text-align: center;
        }

        .card-menu {
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(10px);
            border-radius: 18px;
            padding: 26px;
            transition: .25s;
            color: white;
            cursor: pointer;
            min-height: 170px;         /* bikin tinggi semua sama */
            display: flex;
            flex-direction: column;
            justify-content: center;   /* biar text-nya center vertical */
            text-align: center;
        }

        .card-menu:hover {
            transform: translateY(-5px);
            background: rgba(255,255,255,0.25);
        }

        .card-menu p {
            color: #e3e3e3;
        }

        a {
            text-decoration: none;
        }

        .navbar-logo {
            height: 40px;             /* kecilin ukuran */
            width: auto;
            margin-right: 12px;       /* jarak sama teks biar napas */
        }

        .card-menu.student-card {
    width: 100%;
    max-width: 360px;       /* Biar kotaknya pas, tidak kurus */
    min-height: 160px;      /* Tinggi minimum cantik */
    padding: 35px 20px;
    border-radius: 25px;

    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;

    background: rgba(255,255,255,0.18);
    backdrop-filter: blur(12px);
}

    </style>
</head>

<body>

<nav class="navbar navbar-expand-lg navbar-custom fixed-top">
    <div class="container-fluid">
        <img src="logoremovedbg.png" alt="logo" class="navbar-logo">
        <a class="navbar-brand">Halo, <?= htmlspecialchars($user['fullname']) ?> (<?= $role ?>)</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav align-items-center">
                <li class="nav-item">
                    <a href="logout.php" class="btn btn-warning px-3 fw-bold">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>


<div class="container main-container">

    <?php if ($role == "teacher"): ?>

        <div class="row g-4 justify-content-center">
            <div class="col-md-4 d-flex">
                <a href="teacher/create_quiz.php" class="w-100">
                    <div class="card-menu shadow-lg h-100">
                        <h4 class="fw-bold">Buat Kuis Baru</h4>
                        <p>Buat kuis pilihan ganda seperti Quizizz</p>
                    </div>
                </a>
            </div>

            <div class="col-md-4 d-flex">
                <a href="teacher/my_quizzes.php" class="w-100">
                    <div class="card-menu shadow-lg h-100">
                        <h4 class="fw-bold">Kuis Saya</h4>
                        <p>Lihat semua kuis milikmu</p>
                    </div>
                </a>
            </div>
        </div>

   <?php else: ?>

    <div class="d-flex justify-content-center">
        <a href="student/join_room.php" style="text-decoration:none;">
            <div class="card-menu student-card shadow-lg">
                <h3 class="fw-bold">Join Kode Room</h3>
                <p style="color:#eee;">Masukkan kode yang diberikan guru</p>
            </div>
        </a>
    </div>
<?php endif; ?>



</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
