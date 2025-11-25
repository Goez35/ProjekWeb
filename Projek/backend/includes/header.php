<?php
// common header included into teacher pages
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?= isset($page_title) ? htmlspecialchars($page_title) : 'Quizizz' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: url('../backgroundquizteacher.png') center/cover no-repeat fixed;
            min-height: 100vh;
            font-family: Poppins, sans-serif;
            color: #fff;
        }

        .main-card {
            background: rgba(62, 151, 228, 0.35); /* warna biru transparan */
            backdrop-filter: blur(18px);          /* efek blur kaca */
            -webkit-backdrop-filter: blur(18px);  /* biar compatible Safari */
            border-radius: 20px;
            padding: 32px 42px;
            width: 100%;
            max-width: 1000px;
            box-shadow: 0 12px 32px rgba(0,0,0,0.45);
            }


        nav {
            background: rgba(255,255,255,0.08);
            backdrop-filter: blur(8px);
            padding: 10px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.15);
        }

        .navbar-logo { height:36px; width:auto; }

        label { font-weight:600; color:white; }

        input, textarea, select {
            background: rgba(255,255,255,0.22) !important;
            border: none !important;
            color: #fff !important;
        }

        input::placeholder, textarea::placeholder {
            color: #f7f7f7;
        }

        .btn-primary-custom {
            background-color:#FFD43B;
            color:#1a1a1a;
            font-weight:700;
            border-radius:12px;
            padding:10px 18px;
        }
    </style>
</head>

<body>

<nav class="navbar navbar-expand-lg">
  <div class="container-fluid">
    <div class="d-flex align-items-center gap-2">
      <img src="../logoremovedbg.png" class="navbar-logo" alt="logo">
      <strong><?= htmlspecialchars(current_user()['fullname'] ?? 'Teacher') ?></strong>
    </div>

    <div class="collapse navbar-collapse justify-content-end">
      <a href="../dashboard.php" class="btn btn-sm btn-outline-light me-2">Dashboard</a>
      <a href="../logout.php" class="btn btn-sm btn-warning fw-bold">Logout</a>
    </div>
  </div>
</nav>

<div class="container d-flex justify-content-center align-items-start" style="padding-top:32px; padding-bottom:32px;">
  <div class="main-card">
