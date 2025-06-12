<?php
include 'db.php';
session_start();

if (!isset($_SESSION['kullanici_turu'])) {
    header("Location: login.php");
    exit;
}

$turu = $_SESSION['kullanici_turu'];
$id = ($turu === 'musteri') ? $_SESSION['musteri_id'] : $_SESSION['admin_id'];
$tablo = ($turu === 'musteri') ? 'musteri' : 'admin';

$mesaj = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ad = $_POST['ad'];
    $soyad = $_POST['soyad'];
    $mail = $_POST['mail'];
    $telefon = $_POST['telefon'];
    $sifre = $_POST['sifre'];

    if ($turu === 'musteri') {
        $conn->query("CALL musteri_ad_degistir($id, '$ad')");
        $conn->query("CALL musteri_soyad_degistir($id, '$soyad')");
        $conn->query("CALL musteri_mail_degistir($id, '$mail')");
        $conn->query("CALL musteri_telefon_degistir($id, '$telefon')");
        $conn->query("CALL musteri_sifre_degistir($id, '$sifre')");
    } else {
        $conn->query("CALL admin_ad_degistir($id, '$ad')");
        $conn->query("CALL admin_soyad_degistir($id, '$soyad')");
        $conn->query("CALL admin_mail_degistir($id, '$mail')");
        $conn->query("CALL admin_telefon_degistir($id, '$telefon')");
        $conn->query("CALL admin_sifre_degistir($id, '$sifre')");
    }

    $mesaj = "Bilgiler başarıyla güncellendi.";
}

$sonuc = $conn->query("SELECT * FROM $tablo WHERE {$tablo}_id = $id");
$veri = $sonuc->fetch_assoc();

include 'navbar.php';
renderNavbar();
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Bilgileri</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h3>Profil Bilgileri</h3>

    <?php if ($mesaj): ?>
        <div class="alert alert-success"><?php echo $mesaj; ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label>Ad</label>
            <input type="text" name="ad" class="form-control" value="<?php echo $veri['ad']; ?>" required>
        </div>
        <div class="mb-3">
            <label>Soyad</label>
            <input type="text" name="soyad" class="form-control" value="<?php echo $veri['soyad']; ?>" required>
        </div>
        <div class="mb-3">
            <label>Mail</label>
            <input type="email" name="mail" class="form-control" value="<?php echo $veri['mail']; ?>" required>
        </div>
        <div class="mb-3">
            <label>Telefon</label>
            <input type="text" name="telefon" class="form-control" value="<?php echo $veri['telefon']; ?>" required>
        </div>
        <div class="mb-3">
            <label>Şifre</label>
            <input type="password" name="sifre" class="form-control" value="<?php echo $veri['sifre']; ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Güncelle</button>
        <a href="index.php" class="btn btn-secondary">Ana Sayfaya Dön</a>
    </form>
</div>
</body>
</html>