<?php
include "../auth.php";
include "../koneksi.php";
require_login();

$user = current_user();
if ($user['role'] !== 'teacher') die("Akses ditolak.");

if (!isset($_GET['quiz_id'])) die("Quiz tidak ditemukan.");

$quiz_id = intval($_GET['quiz_id']);

// generate kode room unik 6-digit
$join_code = strtoupper(substr(md5(time()), 0, 6));

$stmt = $koneksi->prepare("
    INSERT INTO quiz_sessions (quiz_id, host_id, join_code, status)
    VALUES (?, ?, ?, 'waiting')
");
$stmt->bind_param("iis", $quiz_id, $user['id'], $join_code);
$stmt->execute();
$session_id = $stmt->insert_id;

?>
<?php include __DIR__ . '/../includes/header.php'; ?>
<body class="p-4 text-center">

<h2>Kode Room:</h2>
<h1 style="font-size:60px; letter-spacing:10px;"><?= $join_code ?></h1>

<p class="text-muted">Berikan kode ini kepada siswa</p>

<a href="session_lobby.php?session_id=<?= $session_id ?>" class="btn btn-primary mt-3">
    Masuk ke Lobi Game
</a>

<?php include __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>

//bagus jelek
