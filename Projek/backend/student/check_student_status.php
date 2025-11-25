<?php
require_once "../koneksi.php";

$session_id = $_GET["session_id"] ?? null;

if (!$session_id) {
    echo json_encode(["status" => "error"]);
    exit;
}

$stmt = $koneksi->prepare("SELECT status FROM quiz_sessions WHERE id = ?");
$stmt->bind_param("i", $session_id);
$stmt->execute();
$res = $stmt->get_result()->fetch_assoc();
$stmt->close();

if ($res) {
    echo json_encode(["status" => $res["status"]]);
} else {
    echo json_encode(["status" => "not_found"]);
}
?>
