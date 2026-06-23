<?php
/**
 * File: views/header.php
 * Header untuk semua halaman
 */
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Informasi Kepegawaian - UAS PBO</title>
    <link rel="stylesheet" href="css/style.css">
    <!-- Font Awesome untuk ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <div class="nav-logo">
                <a href="index.php">🏢 SIK</a>
                <span class="nav-subtitle">Sistem Informasi Kepegawaian</span>
            </div>
            <ul class="nav-menu">
                <li><a href="index.php" class="active"><i class="fas fa-home"></i> Beranda</a></li>
                <li><a href="index.php#tab-tetap"><i class="fas fa-user-tie"></i> Tetap</a></li>
                <li><a href="index.php#tab-kontrak"><i class="fas fa-file-contract"></i> Kontrak</a></li>
                <li><a href="index.php#tab-magang"><i class="fas fa-graduation-cap"></i> Magang</a></li>
            </ul>
            <div class="nav-user">
                <span><i class="fas fa-user-circle"></i> Admin</span>
            </div>
        </div>
    </nav>
    <main>