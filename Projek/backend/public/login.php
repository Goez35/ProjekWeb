<?php
session_start();
require_once "koneksi.php";

// jika ada pesan sukses dari register
$flash = $_SESSION['flash_success'] ?? "";
unset($_SESSION['flash_success']);

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = "Username dan password harus diisi!";
    } else {
        $stmt = $koneksi->prepare("SELECT id, username, password, role FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            if (password_verify($password, $row["password"])) {
                $_SESSION["user"] = [
                    "id" => $row["id"],
                    "username" => $row["username"],
                    "role" => $row["role"]
                ];
                header("Location: dashboard.php");
                exit;
            } else {
                $error = "Password salah!";
            }
        } else {
            $error = "Akun tidak ditemukan!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quizizz Login</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #8A2BE2, #4C00FF);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            width: 380px;
            background: #ffffff;
            border-radius: 18px;
            box-shadow: 0px 15px 40px rgba(0,0,0,0.25);
            padding: 40px 35px;
            animation: fadeIn 0.5s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(25px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .login-title {
            font-size: 26px;
            font-weight: 600;
            text-align: center;
            margin-bottom: 25px;
        }

        .form-control {
            border-radius: 12px;
            padding: 12px;
        }

        .btn-login {
            width: 100%;
            padding: 14px;
            font-size: 16px;
            font-weight: 600;
            border-radius: 12px;
            background: #8A2BE2;
            border: none;
        }

        .btn-login:hover { background: #6c00e9; }

        .google-btn {
            border-radius: 12px;
            padding: 12px;
            border: 2px solid #ddd;
            background: white;
            font-weight: 500;
        }

        .google-btn:hover { background: #f2f2f2; }

        .divider {
            text-align: center;
            margin: 18px 0;
            position: relative;
            color: #868686;
        }
        .divider:before, .divider:after {
            content: "";
            width: 40%;
            height: 1.5px;
            background: #ddd;
            position: absolute;
            top: 50%;
        }
        .divider:before { left: 0; }
        .divider:after  { right: 0; }

        .link {
            text-decoration: none;
            color: #8A2BE2;
            font-weight: 500;
        }
    </style>
</head>

<body>

<div class="login-card">

    <h2 class="login-title">Login ke Quizizz</h2>

    <!-- Pesan sukses dari register -->
    <?php if ($flash): ?>
        <div class="alert alert-success"><?= $flash ?></div>
    <?php endif; ?>

    <!-- Pesan error -->
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label class="form-label fw-semibold">Email atau Username</label>
            <input type="text" name="username" class="form-control" placeholder="Masukkan email / username">
        </div>

        <div class="mb-2">
            <label class="form-label fw-semibold">Password</label>
            <input type="password" name="password" class="form-control" placeholder="Masukkan password">
        </div>

        <div class="text-end mb-3">
            <a href="#" class="small link">Lupa password?</a>
        </div>

        <button type="submit" class="btn btn-login text-white">Masuk</button>
    </form>

    <div class="divider">atau</div>

    <button class="google-btn w-100 d-flex align-items-center justify-content-center mb-3">
        <img src="https://www.svgrepo.com/show/475656/google-color.svg" width="22" class="me-2">
        Masuk dengan Google
    </button>

    <p class="text-center mt-2">
        Belum punya akun? <a class="link" href="register.php">Daftar sekarang</a>
    </p>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
