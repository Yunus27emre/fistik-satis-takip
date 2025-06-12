<?php
include 'db.php';
session_start();

if (!isset($_SESSION['kullanici_turu']) || $_SESSION['kullanici_turu'] !== 'musteri') {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sepet_id'])) {
    $sepet_id = intval($_POST['sepet_id']);

    $sql = "CALL siparis_olustur(?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $sepet_id);

    if ($stmt->execute()) {
        $mesaj = "Sipariş başarıyla oluşturuldu!";
    } else {
        $mesaj = "Sipariş oluşturulamadı: " . $conn->error;
    }
} else {
    header("Location: sepet.php");
    exit;
}

include 'navbar.php';
renderNavbar();
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sipariş Ver</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <div class="alert alert-info">
        <?php echo $mesaj; ?>
    </div>
    <a href="index.php" class="btn btn-primary">Ana Sayfaya Dön</a>
</div>
</body>
</html>
