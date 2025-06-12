<?php
include 'db.php';
session_start();

if (!isset($_SESSION['kullanici_turu']) || $_SESSION['kullanici_turu'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$mesaj = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ad = $_POST['ad'];
    $kategori = $_POST['kategori'];
    $detay = $_POST['detay'];
    $fiyat = floatval($_POST['fiyat']);
    $birim = $_POST['birim'];
    $stok = floatval($_POST['stok']);
    $resim = $_POST['resim']; 
    $durum = 'aktif';

    $stmt = $conn->prepare("INSERT INTO urun (ad, kategori, detay, fiyat, birim, stok, durum, resim) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssdsdss", $ad, $kategori, $detay, $fiyat, $birim, $stok, $durum, $resim);

    if ($stmt->execute()) {
        $mesaj = "Ürün başarıyla eklendi.";
    } else {
        $mesaj = "Hata oluştu: " . $conn->error;
    }
}

include 'navbar.php';
renderNavbar();
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Ürün Ekle</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h3>Yeni Ürün Ekle</h3>
    <?php if ($mesaj): ?>
        <div class="alert alert-info"><?php echo $mesaj; ?></div>
    <?php endif; ?>
    <form method="POST">
        <div class="mb-3">
            <label>Ürün Adı</label>
            <input type="text" name="ad" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Kategori</label>
            <select name="kategori" class="form-control" required>
            <option value="kabuk">Kabuk</option>
            <option value="iç">İç</option>
            </select>
        </div>
        <div class="mb-3">
            <label>Detay</label>
            <textarea name="detay" class="form-control" required></textarea>
        </div>
        <div class="mb-3">
            <label>Fiyat (TL)</label>
            <input type="number" step="0.01" name="fiyat" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Birim</label>
            <input type="text" name="birim" class="form-control" placeholder="kg, paket, adet..." required>
        </div>
        <div class="mb-3">
            <label>Stok (kg)</label>
            <input type="number" step="0.01" name="stok" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Resim URL</label>
            <input type="text" name="resim" class="form-control" placeholder="resim.jpg veya https://...">
        </div>
        <button type="submit" class="btn btn-success">Ekle</button>
        <a href="dashboard.php" class="btn btn-secondary">Admin Paneline Dön</a>
    </form>
</div>
</body>
</html>
