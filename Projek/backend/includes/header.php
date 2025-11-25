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

        .quiz-card {
            background: rgba(255,255,255,0.12);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-radius: 18px;
            overflow: hidden;
            color: white;
            transition: .25s;
        }

        .quiz-card:hover {
            transform: translateY(-6px);
            background: rgba(255,255,255,0.18);
        }

        .quiz-image {
            width: 100%;
            height: 160px;
            object-fit: cover;
            border-bottom: 1px solid rgba(255,255,255,0.25);
        }
        
        .table-glass {
            background: rgba(255,255,255,0.08);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            color: #fff;
            border-radius: 14px;
            overflow:hidden;
        }

        .table-glass thead {
            background: rgba(0,0,0,0.6) !important;
            color: #fff;
            font-weight: 700;
        }

        .table-glass tbody tr {
            border-bottom: 1px solid rgba(255,255,255,0.15);
        }

        .table-glass tbody tr:hover {
            background: rgba(255,255,255,0.12);
        }

        .table-glass td, .table-glass th {
            border: none;
            padding: 14px 10px;
        }
    </style>
</head>

<body>

<div class="container d-flex justify-content-center align-items-start" style="padding-top:32px; padding-bottom:32px;">
  <div class="main-card">
