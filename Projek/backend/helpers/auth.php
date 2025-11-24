<?php
session_start();

function is_logged_in() {
    return isset($_SESSION['user']);
}

function current_user() {
    return isset($_SESSION['user']) ? $_SESSION['user'] : null;
}

function require_login() {
    if (!isset($_SESSION['user'])) {
        // kalau belum login, langsung pindah ke halaman login
        header("Location: login.php");
        exit;
    }
}

function require_teacher() {
    if (!isset($_SESSION['user'])) {
        header("Location: login.php");
        exit;
    }

    // kalau user bukan teacher
    if ($_SESSION['user']['role'] != "teacher") {
        die("Akses ditolak! Halaman ini khusus teacher.");
    }
}
?>
