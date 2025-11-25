<?php
// backend/student/join_room.php
require_once "../auth.php";
require_login();
require_once "../koneksi.php";

$user = current_user();

// Inisialisasi variabel agar tidak terjadi "Undefined variable"
$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $room_code = trim($_POST['room_code']);

    if ($room_code === "") {
        $error = "Kode room tidak boleh kosong!";
    } else {
        $stmt = $koneksi->prepare("SELECT * FROM quiz_sessions WHERE join_code = ? AND status = 'waiting'");
        $stmt->bind_param("s", $room_code);
        $stmt->execute();
        $res = $stmt->get_result();
        $session = $res ? $res->fetch_assoc() : null;
        $stmt->close();

        if ($session) {
            // simpan peserta baru
            $nickname = $user['fullname'] ?? $user['username'];

            $insert = $koneksi->prepare("INSERT INTO participants (session_id, user_id, nickname) VALUES (?, ?, ?)");
            $insert->bind_param("iis", $session['id'], $user['id'], $nickname);
            $ok = $insert->execute();
            $insert->close();

            if ($ok) {
                $participant_id = $koneksi->insert_id;

                // buat submission untuk peserta baru
                $makeSub = $koneksi->prepare("
                    INSERT INTO submissions (session_id, participant_id, score, correct_count, wrong_count, finished_at)
                    VALUES (?, ?, 0, 0, 0, NULL)
                ");
                $makeSub->bind_param("ii", $session['id'], $participant_id);
                $makeSub->execute();
                $makeSub->close();

                // simpan participant id dan session id di session user
                $_SESSION['participant_id'] = $participant_id;
                $_SESSION['session_id'] = $session['id'];

                header("Location: waiting_room.php?session_id=" . $session['id']);
                exit;
            } else {
                $error = "Gagal menyimpan peserta. Coba lagi.";
            }

        } else {
            $error = "Kode room tidak ditemukan atau sesi sudah ditutup!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Join Room</title>
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
            padding: 38px 28px;
            color: #fff;
            text-align: center;
            width: 100%;
        }
        .glass-card h3 { font-weight: 700; margin-bottom: 15px; }
        .form-control {
            border-radius: 15px;
            padding: 14px;
            font-size: 1.1rem;
            text-align: center;
        }
        .btn-join {
            background: #ffca28;
            color: #000;
            font-weight: 600;
            border-radius: 15px;
            padding: 12px;
            font-size: 1.1rem;
        }
        .btn-join:hover { background: #ffc107; }
        .alert { border-radius: 12px; font-size: 0.95rem; }
    </style>
</head>

<body class="d-flex align-items-center justify-content-center">

<div style="max-width: 520px; width:100%;" class="px-3">
    <?php if ($error !== ""): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if ($success !== ""): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <div class="glass-card shadow-lg">
        <h3>Masukkan Kode Room</h3>

        <form method="POST" class="mt-3">
            <input type="text" name="room_code"
                   class="form-control mb-3"
                   placeholder="Contoh: AB12CD"
                   required
                   maxlength="10"
                   style="letter-spacing:2px; text-transform:uppercase;"
                   oninput="this.value = this.value.toUpperCase();">

            <button type="submit" class="btn btn-join w-100">Join</button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
