<?php
include 'db.php';
session_start();

if (!isset($_SESSION['kullanici_turu']) || $_SESSION['kullanici_turu'] !== 'admin') {
    header("Location: login.php");
    exit;
}

include 'navbar.php';
renderNavbar();
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Admin Paneli</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h3>Admin Paneli</h3>
    <div class="list-group">
        <a href="urun_listele.php" class="list-group-item list-group-item-action">Ürünleri Listele</a>
        <a href="urun_ekle.php" class="list-group-item list-group-item-action">Yeni Ürün Ekle</a>
    </div>
    <a href="../index.php" class="btn btn-secondary mt-4">Ana Sayfaya Dön</a>
</div>
</body>
</html>
