<?php
session_start();
include "db.php";

// Jika form disubmit
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Validasi simpel
    if ($username === "" || $password === "") {
        $error = "Username dan password wajib diisi!";
    } else {
        // Cek user
        $stmt = $koneksi->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {

            // Cocokkan password
            if (password_verify($password, $row['password'])) {
                // Set session
                $_SESSION['user'] = [
                    "id" => $row['id'],
                    "username" => $row['username'],
                    "role" => $row['role']
                ];

                // Redirect ke dashboard
                header("Location: dashboard.php");
                exit;
            } else {
                $error = "Password salah!";
            }

        } else {
            $error = "Username tidak ditemukan!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Perpustakaan</title>

    <style>
        body {
            margin: 0;
            padding: 0;
            background: #e9ecef;
            font-family: Arial, sans-serif;
        }

        .login-container {
            width: 90%;
            max-width: 380px;
            margin: 100px auto;
            padding: 25px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        h2 {
            text-align: center;
            margin-top: 0;
        }

        label {
            font-weight: bold;
            display: block;
            margin-top: 12px;
        }

        input {
            width: 100%;
            padding: 12px;
            margin-top: 6px;
            border: 1px solid #bbb;
            border-radius: 6px;
        }

        button {
            width: 100%;
            padding: 12px;
            margin-top: 20px;
            background: #0d6efd;
            border: none;
            border-radius: 6px;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }

        .error {
            margin-top: 15px;
            padding: 10px;
            background: #ffdddd;
            border-left: 4px solid red;
            color: red;
        }
    </style>
</head>

<body>

    <div class="login-container">
        <h2>Login</h2>

        <?php if ($error != ""): ?>
            <div class="error"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST">
            <label>Username</label>
            <input type="text" name="username" placeholder="Masukkan username">

            <label>Password</label>
            <input type="password" name="password" placeholder="Masukkan password">

            <button type="submit">Masuk</button>
        </form>
    </div>

</body>
</html>
