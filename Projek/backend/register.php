<?php
// register.php
// Form + proses register sederhana (gaya koding kamu)
// Pastikan ada file koneksi.php yang mendefinisikan $koneksi

session_start();
require_once "koneksi.php";

$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = trim($_POST['fullname'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $role     = $_POST['role'] ?? 'student';

    // validasi sederhana
    if ($fullname === '' || $email === '' || $username === '' || $password === '') {
        $error = "Semua field wajib diisi!";
    } else {
        // cek apakah username atau email sudah ada
        $stmt = $koneksi->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        if (!$stmt) {
            die("Prepare gagal: " . $koneksi->error);
        }
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows > 0) {
            $error = "Username atau email sudah dipakai!";
        } else {
            // masukkan user baru
            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            $ins = $koneksi->prepare("INSERT INTO users (fullname, email, username, password, role) VALUES (?, ?, ?, ?, ?)");
            if (!$ins) {
                die("Prepare gagal: " . $koneksi->error);
            }
            $ins->bind_param("sssss", $fullname, $email, $username, $password_hash, $role);
            $ok = $ins->execute();

            if ($ok) {
                // sukses -> redirect ke login
                $_SESSION['flash_success'] = "Registrasi berhasil. Silakan login.";
                header("Location: login.php");
                exit;
            } else {
                $error = "Gagal menyimpan data: " . $ins->error;
            }
        }

        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar - Quizizz Clone</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Font Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #8A2BE2, #4C00FF);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .card-register {
            width: 420px;
            border-radius: 16px;
            padding: 32px;
            box-shadow: 0 12px 40px rgba(0,0,0,0.25);
        }
        .form-control {
            border-radius: 10px;
            padding: 12px;
        }
        .btn-primary {
            background: #8A2BE2;
            border: none;
            border-radius: 10px;
            padding: 12px 16px;
            font-weight: 600;
        }
        .btn-primary:hover { background: #6c00e9; }
        .small-link { font-size: .95rem; }
    </style>
</head>
<body>

<div class="card card-register bg-white">
    <h3 class="text-center mb-3">Buat Akun Quizizz</h3>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="POST" novalidate>
        <div class="mb-3">
            <label class="form-label">Nama Lengkap</label>
            <input type="text" name="fullname" class="form-control" placeholder="Nama lengkap" value="<?= isset($fullname) ? htmlspecialchars($fullname) : '' ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" placeholder="nama@contoh.com" value="<?= isset($email) ? htmlspecialchars($email) : '' ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" name="username" class="form-control" placeholder="username" value="<?= isset($username) ? htmlspecialchars($username) : '' ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" placeholder="Buat password minimal 6 karakter">
        </div>

        <div class="mb-3">
            <label class="form-label">Daftar sebagai</label>
            <select name="role" class="form-select form-control">
                <option value="student" <?= (isset($role) && $role === 'student') ? 'selected' : '' ?>>Student</option>
                <option value="teacher" <?= (isset($role) && $role === 'teacher') ? 'selected' : '' ?>>Teacher</option>
            </select>
        </div>

        <div class="d-grid">
            <button type="submit" class="btn btn-primary">Daftar</button>
        </div>

        <div class="text-center mt-3 small-link">
            Sudah punya akun? <a href="login.php">Masuk</a>
        </div>
    </form>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

