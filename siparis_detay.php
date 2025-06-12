<?php
include 'db.php';
session_start();

if (!isset($_SESSION['kullanici_turu']) || $_SESSION['kullanici_turu'] !== 'musteri') {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: siparislerim.php");
    exit;
}

$siparis_id = intval($_GET['id']);
$musteri_id = $_SESSION['musteri_id'];

$kontrol = $conn->prepare("SELECT * FROM siparis WHERE siparis_id = ? AND musteri_id = ?");
$kontrol->bind_param("ii", $siparis_id, $musteri_id);
$kontrol->execute();
$sonuc = $kontrol->get_result();

if ($sonuc->num_rows === 0) {
    echo "Bu siparişe erişim yetkiniz yok.";
    exit;
}

$sql = "SELECT u.ad, sd.miktar_kg, sd.birim_fiyat, (sd.miktar_kg * sd.birim_fiyat) AS toplam
        FROM siparis_detay sd
        LEFT JOIN urun u ON sd.urun_id = u.urun_id
        WHERE sd.siparis_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $siparis_id);
$stmt->execute();
$result = $stmt->get_result();

include 'navbar.php';
renderNavbar();
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Sipariş Detayı</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h3>Sipariş Detayı (#<?php echo $siparis_id; ?>)</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Ürün</th>
                <th>Miktar (kg)</th>
                <th>Birim Fiyat</th>
                <th>Toplam</th>
            </tr>
        </thead>
        <tbody>
        <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo isset($row['ad']) ? $row['ad'] : '(silinmiş ürün)'; ?></td>
                    <td><?php echo $row['miktar_kg']; ?></td>
                    <td><?php echo number_format($row['birim_fiyat'], 2); ?> TL</td>
                    <td><?php echo number_format($row['toplam'], 2); ?> TL</td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="4" class="text-center text-muted">Sipariş detayı bulunamadı.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>

    <a href="siparislerim.php" class="btn btn-secondary">Siparişlerime Dön</a>
</div>
</body>
</html>
