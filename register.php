<?php
include 'db.php';
session_start();

$hata = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ad = $_POST['ad'];
    $soyad = $_POST['soyad'];
    $mail = $_POST['mail'];
    $telefon = $_POST['telefon'];
    $adres = $_POST['adres'];
    $sifre = $_POST['sifre'];

    $kontrol = $conn->prepare("SELECT * FROM musteri WHERE mail = ?");
    $kontrol->bind_param("s", $mail);
    $kontrol->execute();
    $sonuc = $kontrol->get_result();

    if ($sonuc->num_rows > 0) {
        $hata = "Bu mail adresiyle zaten kayıt olunmuş.";
    } else {
        $ekle = $conn->prepare("INSERT INTO musteri (ad, soyad, mail, telefon, adres, sifre) VALUES (?, ?, ?, ?, ?, ?)");
        $ekle->bind_param("ssssss", $ad, $soyad, $mail, $telefon, $adres, $sifre);
        if ($ekle->execute()) {
            $_SESSION['kullanici_turu'] = 'musteri';
            $_SESSION['musteri_id'] = $ekle->insert_id;
            header("Location: index.php");
            exit;
        } else {
            $hata = "Kayıt sırasında hata oluştu.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kayıt Ol</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Kayıt Ol</div>
                <div class="card-body">
                    <?php if ($hata): ?>
                        <div class="alert alert-danger"><?php echo $hata; ?></div>
                    <?php endif; ?>
                    <form method="POST">
                        <div class="mb-3">
                            <label>Ad</label>
                            <input type="text" name="ad" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Soyad</label>
                            <input type="text" name="soyad" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Mail</label>
                            <input type="email" name="mail" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Telefon</label>
                            <input type="text" name="telefon" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Adres</label>
                            <textarea name="adres" class="form-control" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label>Şifre</label>
                            <input type="password" name="sifre" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Kayıt Ol</button>
                        <a href="index.php" class="btn btn-secondary">Ana Sayfaya Dön</a>
                        <a href="login.php" class="btn btn-link">Zaten hesabın var mı?</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
