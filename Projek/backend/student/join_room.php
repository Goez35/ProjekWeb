<?php
require_once "../auth.php";
require_login();
require_once "../koneksi.php";

$user = current_user();
$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $room_code = trim($_POST['room_code']);

    if ($room_code == "") {
        $error = "Kode room tidak boleh kosong!";
    } else {
        $stmt = $koneksi->prepare("SELECT * FROM quiz_sessions WHERE join_code = ? AND status = 'waiting'");
        $stmt->bind_param("s", $room_code);
        $stmt->execute();
        $session = $stmt->get_result()->fetch_assoc();

        if ($session) {
            // simpan peserta baru
            $nickname = $user['fullname'];

            $insert = $koneksi->prepare("INSERT INTO participants (session_id, user_id, nickname) VALUES (?, ?, ?)");
            $insert->bind_param("iis", $session['id'], $user['id'], $nickname);
            $insert->execute();

            $_SESSION['session_id'] = $session['id'];

            header("Location: waiting_room.php?session_id=" . $session['id']);
            exit;
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
</head>

<body class="bg-light d-flex align-items-center justify-content-center" style="min-height:100vh;">

<div class="container" style="max-width:420px;">
    <h3 class="text-center mb-3">Masukkan Kode Room</h3>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST" class="card p-4 shadow-sm">
        <input type="text" name="room_code" class="form-control mb-3"
               placeholder="Contoh: AB12CD">
        <button class="btn btn-primary w-100">Join</button>
    </form>
</div>

</body>
</html>
