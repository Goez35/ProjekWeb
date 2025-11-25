<?php
include "../auth.php";
include "../koneksi.php";
require_login();

$id = $_POST['id'];
$title = $_POST['title'];
$description = $_POST['description'];
$visibility = $_POST['visibility'];

$cover_sql = "";

if (!empty($_FILES['cover']['name'])) {
    $ext = pathinfo($_FILES['cover']['name'], PATHINFO_EXTENSION);
    $cover = "cover_" . time() . "." . $ext;
    move_uploaded_file($_FILES['cover']['tmp_name'], "../uploads/" . $cover);
    $cover_sql = ", cover_image='$cover'";
}

$sql = "
    UPDATE quizzes 
    SET title='$title', description='$description', visibility='$visibility' $cover_sql
    WHERE id=$id
";

$koneksi->query($sql);

header("Location: my_quizzes.php");
exit;
