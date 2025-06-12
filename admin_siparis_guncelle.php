<?php
include 'db.php';
session_start();

if (!isset($_SESSION['kullanici_turu']) || $_SESSION['kullanici_turu'] !== 'admin') {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: admin_siparisler.php");
    exit;
}

$siparis_id = intval($_GET['id']);
$mesaj = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $odeme = $_POST['odeme_durumu'];
    $teslim = $_POST['teslim_durumu'];

    $gecerli_odeme = ['Bekliyor', 'Ödendi', 'İptal'];
    $gecerli_teslim = ['Hazırlanıyor', 'Kargoda', 'Teslim Edildi'];

    if (!in_array($odeme, $gecerli_odeme) || !in_array($teslim, $gecerli_teslim)) {
        die('Geçersiz seçim.');
    }

    $stmt = $conn->prepare("UPDATE siparis SET odeme_durumu = ?, teslim_durumu = ? WHERE siparis_id = ?");
    $stmt->bind_param("ssi", $odeme, $teslim, $siparis_id);

    if ($stmt->execute()) {
        $mesaj = "Sipariş durumu güncellendi.";
    } else {
        $mesaj = "Hata: " . $conn->error;
    }
}

$stmt = $conn->prepare("SELECT * FROM siparis WHERE siparis_id = ?");
$stmt->bind_param("i", $siparis_id);
$stmt->execute();
$result = $stmt->get_result();
$siparis = $result->fetch_assoc();

include 'navbar.php';
renderNavbar();
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Sipariş Durumu Güncelle</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h3>Sipariş Durumu Güncelle (#<?php echo $siparis_id; ?>)</h3>
    <?php if ($mesaj): ?><div class="alert alert-info"><?php echo $mesaj; ?></div><?php endif; ?>
    <form method="POST">
        <div class="mb-3">
            <label>Ödeme Durumu</label>
            <select name="odeme_durumu" class="form-control">
                <option value="Bekliyor" <?php if($siparis['odeme_durumu'] === 'Bekliyor') echo 'selected'; ?>>Bekliyor</option>
                <option value="Ödendi" <?php if($siparis['odeme_durumu'] === 'Ödendi') echo 'selected'; ?>>Ödendi</option>
                <option value="İptal" <?php if($siparis['odeme_durumu'] === 'İptal') echo 'selected'; ?>>İptal</option>
            </select>

        </div>
        <div class="mb-3">
            <label>Teslim Durumu</label>
            <select name="teslim_durumu" class="form-control">
                <option value="Hazırlanıyor" <?php if($siparis['teslim_durumu'] === 'Hazırlanıyor') echo 'selected'; ?>>Hazırlanıyor</option>
                <option value="Kargoda" <?php if($siparis['teslim_durumu'] === 'Kargoda') echo 'selected'; ?>>Kargoda</option>
                <option value="Teslim Edildi" <?php if($siparis['teslim_durumu'] === 'Teslim Edildi') echo 'selected'; ?>>Teslim Edildi</option>
            </select>

        </div>
        <button type="submit" class="btn btn-primary">Güncelle</button>
        <a href="admin_siparisler.php" class="btn btn-secondary">Tüm Siparişlere Dön</a>
    </form>
</div>
</body>
</html>
