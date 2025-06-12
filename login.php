<?php
include 'db.php';
session_start();

$hata = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mail = $_POST['mail'];
    $sifre = $_POST['sifre'];

    $stmt = $conn->prepare("SELECT * FROM musteri WHERE mail = ? AND sifre = ?");
    $stmt->bind_param("ss", $mail, $sifre);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $musteri = $result->fetch_assoc();
        $_SESSION['kullanici_turu'] = 'musteri';
        $_SESSION['musteri_id'] = $musteri['musteri_id'];
        header("Location: index.php");
        exit;
    } else {
        $stmt = $conn->prepare("SELECT * FROM admin WHERE mail = ? AND sifre = ?");
        $stmt->bind_param("ss", $mail, $sifre);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $admin = $result->fetch_assoc();
            $_SESSION['kullanici_turu'] = 'admin';
            $_SESSION['admin_id'] = $admin['admin_id'];
            header("Location: index.php");
            exit;
        } else {
            $hata = "Geçersiz e-posta veya şifre.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giriş Yap</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Giriş Yap</div>
                <div class="card-body">
                    <?php if ($hata): ?>
                        <div class="alert alert-danger"><?php echo $hata; ?></div>
                    <?php endif; ?>
                    <form method="POST">
                        <div class="mb-3">
                            <label>E-posta</label>
                            <input type="email" name="mail" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Şifre</label>
                            <input type="password" name="sifre" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Giriş Yap</button>
                        <a href="index.php" class="btn btn-secondary">Ana Sayfaya Dön</a>
                        <a href="register.php" class="btn btn-link">Hesabın yok mu? Kayıt Ol</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
