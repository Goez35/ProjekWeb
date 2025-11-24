<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once "koneksi.php";

function require_login() {
    if (!isset($_SESSION["user"])) {
        header("Location: login.php");
        exit;
    }
}

function current_user() {
    return $_SESSION["user"] ?? null;
}
