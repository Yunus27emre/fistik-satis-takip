<?php
include 'db.php';
session_start();

if (!isset($_SESSION['kullanici_turu']) || $_SESSION['kullanici_turu'] !== 'admin') {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: urun_listele.php");
    exit;
}

$urun_id = intval($_GET['id']);
$mesaj = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ad = $_POST['ad'];
    $detay = $_POST['detay'];
    $fiyat = floatval($_POST['fiyat']);
    $birim = $_POST['birim'];
    $stok = floatval($_POST['stok']);
    $aktif = isset($_POST['aktif']) ? 1 : 0;
    $resim = $_POST['resim'];

    $stmt = $conn->prepare("UPDATE urun SET ad=?, detay=?, fiyat=?, birim=?, stok=?, durum=?, resim=? WHERE urun_id=?");
    $stmt->bind_param("ssdssssi", $ad, $detay, $fiyat, $birim, $stok, $aktif, $resim, $urun_id);

    if ($stmt->execute()) {
        $mesaj = "Ürün başarıyla güncellendi.";
    } else {
        $mesaj = "Güncelleme hatası: " . $conn->error;
    }
}

$stmt = $conn->prepare("SELECT * FROM urun WHERE urun_id = ?");
$stmt->bind_param("i", $urun_id);
$stmt->execute();
$sonuc = $stmt->get_result();
$urun = $sonuc->fetch_assoc();

include 'navbar.php';
renderNavbar();
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Ürün Güncelle</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h3>Ürün Güncelle</h3>
    <?php if ($mesaj): ?>
        <div class="alert alert-info"><?php echo $mesaj; ?></div>
    <?php endif; ?>
    <form method="POST">
        <div class="mb-3">
            <label>Ürün Adı</label>
            <input type="text" name="ad" class="form-control" value="<?php echo $urun['ad']; ?>" required>
        </div>
        <div class="mb-3">
            <label>Detay</label>
            <textarea name="detay" class="form-control" required><?php echo $urun['detay']; ?></textarea>
        </div>
        <div class="mb-3">
            <label>Fiyat (TL)</label>
            <input type="number" step="0.01" name="fiyat" class="form-control" value="<?php echo $urun['fiyat']; ?>" required>
        </div>
        <div class="mb-3">
            <label>Birim</label>
            <input type="text" name="birim" class="form-control" value="<?php echo $urun['birim']; ?>" required>
        </div>
        <div class="mb-3">
            <label>Stok (kg)</label>
            <input type="number" step="0.01" name="stok" class="form-control" value="<?php echo $urun['stok']; ?>" required>
        </div>
        <div class="mb-3">
            <label>Resim URL</label>
            <input type="text" name="resim" class="form-control" value="<?php echo $urun['resim']; ?>">
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" name="aktif" class="form-check-input" <?php echo $urun['durum'] ? 'checked' : ''; ?>>
            <label class="form-check-label">Aktif mi?</label>
        </div>
        <button type="submit" class="btn btn-primary">Güncelle</button>
        <a href="urun_listele.php" class="btn btn-secondary">Listeye Dön</a>
    </form>
</div>
</body>
</html>
